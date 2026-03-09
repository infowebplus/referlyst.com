<?php

namespace App\Services;

use App\Models\BrandReferral;
use Illuminate\Support\Str;

class SubdomainHostsSyncService
{
    private const HOSTS_FILE_WINDOWS = 'C:\\Windows\\System32\\drivers\\etc\\hosts';

    /**
     * Regenerate scripts/sync-subdomains.ps1 and .bat with current brands.
     * Only runs when landing environment is local. Creates scripts dir if needed.
     */
    public function regenerateScript(): bool
    {
        if (config('referlyst.landing.environment') !== 'local') {
            return false;
        }

        $host = parse_url(config('app.url'), PHP_URL_HOST);
        if (! $host) {
            return false;
        }

        $subdomains = BrandReferral::query()
            ->whereNotNull('subdomain_for_landing_pages')
            ->where('subdomain_for_landing_pages', '!=', '')
            ->pluck('subdomain_for_landing_pages', 'name');

        /** @var array<string, string> $entries  hostname => brand name */
        $entries = $subdomains->isEmpty()
            ? []
            : $subdomains->mapWithKeys(fn (string $slug, string $name) => ["{$slug}.{$host}" => $name])->all();

        $scriptPath = base_path('scripts'.DIRECTORY_SEPARATOR.'sync-subdomains.ps1');
        $scriptsDir = dirname($scriptPath);
        if (! is_dir($scriptsDir)) {
            mkdir($scriptsDir, 0755, true);
        }

        $this->writeScript($entries, $scriptPath);

        return true;
    }

    /** @param array<string, string> $entries */
    private function writeScript(array $entries, string $scriptPath): void
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            return;
        }

        $hostsPath = self::HOSTS_FILE_WINDOWS;
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
        file_put_contents($scriptPath, $script);

        $batPath = Str::replaceLast('.ps1', '.bat', $scriptPath);
        $batContent = '@echo off'."\r\n"
            .'powershell -ExecutionPolicy Bypass -File "'.str_replace('/', '\\', $scriptPath).'"'."\r\n"
            .'if errorlevel 1 pause'."\r\n";
        file_put_contents($batPath, $batContent);
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
}
