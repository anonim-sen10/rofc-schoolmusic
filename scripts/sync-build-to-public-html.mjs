import fs from "node:fs/promises";
import path from "node:path";

const cwd = process.cwd();
const normalizedCwd = cwd.toLowerCase();
const publicHtmlMarker = `${path.sep}public_html${path.sep}`;
const isInPublicHtml = normalizedCwd.includes(publicHtmlMarker) || normalizedCwd.endsWith(`${path.sep}public_html`);

if (!isInPublicHtml) {
    process.exit(0);
}

const sourceDir = path.resolve(cwd, "public", "build");
const targetDir = path.resolve(cwd, "..", "build");

try {
    await fs.access(sourceDir);
} catch {
    process.exit(0);
}

await fs.rm(targetDir, { recursive: true, force: true });
await fs.mkdir(targetDir, { recursive: true });
await fs.cp(sourceDir, targetDir, { recursive: true });

console.log(`Synced ${sourceDir} -> ${targetDir}`);
