import fs from "node:fs/promises";
import path from "node:path";

const cwd = process.cwd();
const sourceDir = path.resolve(cwd, "public", "build");
const targetFromWebRoot = path.resolve(cwd, "build");
const targetSiblingWebRoot = path.resolve(cwd, "..", "public_html", "build");

try {
    await fs.access(sourceDir);
} catch {
    process.exit(0);
}

const targets = [];

try {
    await fs.access(path.resolve(cwd, "index.php"));
    targets.push(targetFromWebRoot);
} catch {
    // Current working directory is not the web root.
}

try {
    await fs.access(path.resolve(cwd, "..", "public_html", "index.php"));
    targets.push(targetSiblingWebRoot);
} catch {
    // Sibling public_html does not exist in this environment.
}

if (targets.length === 0) {
    process.exit(0);
}

for (const targetDir of targets) {
    await fs.rm(targetDir, { recursive: true, force: true });
    await fs.mkdir(targetDir, { recursive: true });
    await fs.cp(sourceDir, targetDir, { recursive: true });
    console.log(`Synced ${sourceDir} -> ${targetDir}`);
}
