import { readdirSync, readFileSync, writeFileSync } from 'fs';
import { dirname, join } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const siteRoot = join(__dirname, '..');
const pagesDir = join(siteRoot, 'src', 'pages');
const partialsDir = join(siteRoot, 'src', 'partials');

const headStart = readFileSync(join(partialsDir, 'head-start.html'), 'utf8');
const headEnd = readFileSync(join(partialsDir, 'head-end.html'), 'utf8');
const header = readFileSync(join(partialsDir, 'header.html'), 'utf8');
const footer = readFileSync(join(partialsDir, 'footer.html'), 'utf8');

const metaBlockRegex = /^---\r?\n([\s\S]*?)\r?\n---\r?\n([\s\S]*)$/;

for (const file of readdirSync(pagesDir).filter((name) => name.endsWith('.html'))) {
    const raw = readFileSync(join(pagesDir, file), 'utf8');
    const match = raw.match(metaBlockRegex);

    if (!match) {
        throw new Error(`Page ${file} must start with a --- metadata block`);
    }

    const meta = Object.fromEntries(
        match[1]
            .split(/\r?\n/)
            .filter(Boolean)
            .map((line) => {
                const idx = line.indexOf(':');
                return [line.slice(0, idx).trim(), line.slice(idx + 1).trim()];
            }),
    );

    const title = meta.title || 'Referlyst';
    const description =
        meta.description || 'Launch and manage referral programs in minutes. No complex setup. Just growth.';

    const html = `${headStart}
    <meta name="description" content="${description}">
    <title>${title}</title>
${headEnd}
${header}
${match[2].trim()}
${footer}`;

    writeFileSync(join(siteRoot, file), html);
    console.log(`Assembled ${file}`);
}
