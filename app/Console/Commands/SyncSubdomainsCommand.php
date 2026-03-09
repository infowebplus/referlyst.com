<?php

namespace App\Console\Commands;

use App\Models\BrandReferral;
use App\Services\SubdomainHostsSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncSubdomainsCommand extends Command
{
    protected $signature = 'subdomains:sync
                            {--dry-run : Show what would be added without making changes}
                            {--script= : Write a PowerShell script to the given path instead of patching the hosts file}';

    protected $description = 'Sync brand subdomains into the local hosts file (Windows + Herd only)';

    private const HOSTS_FILE_WINDOWS = 'C:\\Windows\\System32\\drivers\\etc\\hosts';

    private const HOSTS_FILE_UNIX = '/etc/hosts';

    private const MARKER_START = '# --- referlyst subdomains (managed) ---';

    private const MARKER_END = '# --- end referlyst subdomains ---';

    public function __construct(
        private SubdomainHostsSyncService $syncService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        if (config('referlyst.landing.environment') !== 'local') {
            $this->error('This command is for local development only (Windows + Herd). On live we use Cloudflare wildcard DNS.');

            return self::FAILURE;
        }

        $host = parse_url(config('app.url'), PHP_URL_HOST);

        if (! $host) {
            $this->error('Could not determine host from APP_URL.');

            return self::FAILURE;
        }

        $subdomains = BrandReferral::query()
            ->whereNotNull('subdomain_for_landing_pages')
            ->where('subdomain_for_landing_pages', '!=', '')
            ->pluck('subdomain_for_landing_pages', 'name');

        if ($subdomains->isEmpty()) {
            $this->info('No brands with subdomains found.');

            return self::SUCCESS;
        }

        /** @var array<string, string> $entries  map of hostname => brand name */
        $entries = $subdomains
            ->mapWithKeys(fn (string $slug, string $name) => ["{$slug}.{$host}" => $name])
            ->all();

        $this->info('Subdomains to sync:');
        foreach ($entries as $hostname => $brandName) {
            $this->line("  127.0.0.1  {$hostname}  ({$brandName})");
        }
        $this->newLine();

        if ($this->option('script')) {
            return $this->writeScript($entries, (string) $this->option('script'));
        }

        return $this->patchHostsFile($entries);
    }

    /** @param array<string, string> $entries */
    private function patchHostsFile(array $entries): int
    {
        $hostsPath = PHP_OS_FAMILY === 'Windows' ? self::HOSTS_FILE_WINDOWS : self::HOSTS_FILE_UNIX;

        if (! file_exists($hostsPath)) {
            $this->error("Hosts file not found: {$hostsPath}");

            return self::FAILURE;
        }

        if (! is_writable($hostsPath)) {
            $this->warn('Hosts file is not writable. Generating a script to run as Administrator...');
            if ($this->option('script')) {
                return $this->writeScript($entries, (string) $this->option('script'));
            }
            $this->syncService->regenerateScript();
            $scriptPath = base_path('scripts'.DIRECTORY_SEPARATOR.'sync-subdomains.ps1');
            $this->info("✓ Script written to: {$scriptPath}");
            $this->newLine();
            $this->line('Run the script (it will prompt for Administrator if needed):');
            $this->line('  • <comment>Right-click scripts/sync-subdomains.bat</comment> → Run as administrator, or');
            $this->line("  • In PowerShell: <comment>& \"{$scriptPath}\"</comment>");
            $this->newLine();
            $this->line('If subdomains stop working after restarting Herd, run the script again.');

            return self::SUCCESS;
        }

        $content = file_get_contents($hostsPath);

        // Strip the previous managed block, if any.
        $content = $this->stripManagedBlock($content);

        if ($this->option('dry-run')) {
            $this->line('<comment>--- would write to hosts file ---</comment>');
            $this->line($this->buildManagedBlock($entries));

            return self::SUCCESS;
        }

        $content = rtrim($content).PHP_EOL.PHP_EOL.$this->buildManagedBlock($entries).PHP_EOL;

        file_put_contents($hostsPath, $content);

        $this->info("✓ Hosts file updated: {$hostsPath}");
        $this->line('  Run <comment>ipconfig /flushdns</comment> (Windows) or <comment>sudo dscacheutil -flushcache</comment> (macOS) to apply.');

        return self::SUCCESS;
    }

    /** @param array<string, string> $entries */
    private function writeScript(array $entries, string $scriptPath): int
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $hostsPath = 'C:\\Windows\\System32\\drivers\\etc\\hosts';
            $regex = '(?m)^.*\.referlyst\.test.*\r?\n?';
            $replaceLine = "\$content = \$content -replace '".addcslashes($regex, "'")."', ''";
            $script = "# Referlyst: sync brand subdomains to hosts file (Windows + Herd)
# Double-click or run in PowerShell; will prompt for Administrator if needed.

# Self-elevate to Administrator (required to write hosts file)
\$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not \$isAdmin) {
    Start-Process powershell -Verb RunAs -ArgumentList '-ExecutionPolicy', 'Bypass', '-File', \$PSCommandPath
    exit
}

\$hostsPath = '{$hostsPath}'
\$linesToAdd = @(
{$this->powerShellLinesForHosts($entries)}
)

\$content = Get-Content \$hostsPath -Raw
if (-not \$content) { \$content = '' }
# Remove any line that contains a subdomain of referlyst; keep main referlyst.test
{$replaceLine}
# Ensure file ends with CRLF before appending
\$content = \$content.TrimEnd()
if (\$content -and -not \$content.EndsWith(\"`r`n\")) { \$content += \"`r`n\" }
\$content += \"`r`n# Referlyst brand subdomains (added by subdomains:sync)`r`n\"
\$content += (\$linesToAdd -join \"`r`n\") + \"`r`n\"
Set-Content -Path \$hostsPath -Value \$content -NoNewline -Encoding ASCII
ipconfig /flushdns | Out-Null
Write-Host ''
Write-Host 'Done. Subdomains added to hosts file.' -ForegroundColor Green
Write-Host 'Try: http://acme-coffee.referlyst.test/ (or your brand slug)' -ForegroundColor Cyan
Write-Host ''
Write-Host 'Current referlyst entries:' -ForegroundColor Gray
Get-Content \$hostsPath | Select-String 'referlyst'";
            $scriptPath = Str::finish($scriptPath, '.ps1');
        } else {
            $lines = ['#!/bin/bash', '# Run with: sudo bash '.basename($scriptPath), ''];
            foreach ($entries as $hostname => $_) {
                $lines[] = "echo '127.0.0.1 {$hostname}' >> /etc/hosts";
            }
            $script = implode(PHP_EOL, $lines);
        }

        file_put_contents($scriptPath, PHP_OS_FAMILY === 'Windows' ? $script : $script);

        if (PHP_OS_FAMILY === 'Windows') {
            $batPath = Str::replaceLast('.ps1', '.bat', $scriptPath);
            $batContent = '@echo off'."\r\n"
                .'powershell -ExecutionPolicy Bypass -File "'.str_replace('/', '\\', $scriptPath).'"'."\r\n"
                .'if errorlevel 1 pause'."\r\n";
            file_put_contents($batPath, $batContent);
        }

        $this->info("✓ Script written to: {$scriptPath}");
        $this->newLine();

        if (PHP_OS_FAMILY === 'Windows') {
            $this->line('Run the script (it will prompt for Administrator if needed):');
            $this->line('  • <comment>Right-click scripts/sync-subdomains.bat</comment> → Run as administrator, or');
            $this->line("  • In PowerShell: <comment>& \"{$scriptPath}\"</comment>");
            $this->newLine();
            $this->line('If subdomains stop working after restarting Herd, run the script again.');
        } else {
            $this->line("Run it with: <comment>sudo bash {$scriptPath}</comment>");
        }

        return self::SUCCESS;
    }

    /** @param array<string, string> $entries */
    private function powerShellLinesForHosts(array $entries): string
    {
        $out = [];
        foreach ($entries as $hostname => $brandName) {
            $out[] = "    '127.0.0.1 {$hostname} # {$brandName}'";
        }

        return implode("\n", $out);
    }

    /** @param array<string, string> $entries */
    private function buildManagedBlock(array $entries): string
    {
        $lines = [self::MARKER_START];
        foreach ($entries as $hostname => $brandName) {
            $lines[] = "127.0.0.1 {$hostname} # {$brandName}";
        }
        $lines[] = self::MARKER_END;

        return implode(PHP_EOL, $lines);
    }

    private function stripManagedBlock(string $content): string
    {
        $pattern = '/\n?'.preg_quote(self::MARKER_START, '/').'.*?'.preg_quote(self::MARKER_END, '/').'\n?/s';

        return preg_replace($pattern, '', $content) ?? $content;
    }
}
