#!/usr/bin/env node

/**
 * 🚀 Auto Deploy Script
 * Usage: node scripts/deploy.mjs [--no-build]
 * 
 * Workflow:
 * 1. Build frontend assets (npm run build)
 * 2. Git add + commit + push to main
 * 3. Deploy ke server via SSH
 * 4. Clear caches & run migrations (opsional)
 */

import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const projectRoot = path.join(__dirname, '..');
const envDeployPath = path.join(projectRoot, '.env.deploy');

// Color codes untuk console output
const colors = {
  reset: '\x1b[0m',
  bright: '\x1b[1m',
  dim: '\x1b[2m',
  red: '\x1b[31m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  blue: '\x1b[34m',
  magenta: '\x1b[35m',
  cyan: '\x1b[36m',
};

function log(type, message) {
  const timestamp = new Date().toLocaleTimeString();
  const prefix = {
    info: `${colors.cyan}ℹ${colors.reset}`,
    success: `${colors.green}✓${colors.reset}`,
    error: `${colors.red}✗${colors.reset}`,
    warn: `${colors.yellow}⚠${colors.reset}`,
    deploy: `${colors.magenta}🚀${colors.reset}`,
  }[type] || '•';

  console.log(`${colors.dim}[${timestamp}]${colors.reset} ${prefix} ${message}`);
}

function logSection(title) {
  console.log(`\n${colors.bright}${colors.blue}${'='.repeat(60)}${colors.reset}`);
  console.log(`${colors.bright}${colors.blue}${title.padEnd(60)}${colors.reset}`);
  console.log(`${colors.bright}${colors.blue}${'='.repeat(60)}${colors.reset}\n`);
}

function exec(command, options = {}) {
  try {
    log('info', `Executing: ${colors.dim}${command}${colors.reset}`);
    const result = execSync(command, {
      cwd: projectRoot,
      stdio: 'inherit',
      ...options,
    });
    return true;
  } catch (error) {
    log('error', `Command failed: ${command}`);
    throw error;
  }
}

// Parse .env.deploy file
function loadConfig() {
  if (!fs.existsSync(envDeployPath)) {
    log('error', `.env.deploy not found at ${envDeployPath}`);
    log('info', 'Creating template .env.deploy...');
    // Template akan di-buat oleh script setup
    process.exit(1);
  }

  const envContent = fs.readFileSync(envDeployPath, 'utf8');
  const config = {};

  envContent.split('\n').forEach((line) => {
    const trimmed = line.trim();
    if (trimmed && !trimmed.startsWith('#')) {
      const [key, ...valueParts] = trimmed.split('=');
      if (key) {
        config[key.trim()] = valueParts.join('=').trim();
      }
    }
  });

  return config;
}

// Validate SSH key
function validateSSHKey(config) {
  let keyPath = config.DEPLOY_SSH_KEY_PATH;

  if (!keyPath) {
    log('error', 'DEPLOY_SSH_KEY_PATH not configured in .env.deploy');
    process.exit(1);
  }

  // Handle relative paths
  if (!keyPath.startsWith('/')) {
    keyPath = path.resolve(projectRoot, keyPath);
  }

  if (!fs.existsSync(keyPath)) {
    log('error', `SSH key not found at ${keyPath}`);
    log('info', `Please set DEPLOY_SSH_KEY_PATH in ${envDeployPath}`);
    process.exit(1);
  }

  return keyPath;
}

// Build frontend assets
async function buildAssets() {
  logSection('Step 1: Building Frontend Assets');

  try {
    exec('npm ci');
    log('success', 'Dependencies installed');

    exec('npm run build');
    log('success', 'Frontend assets built successfully');

    return true;
  } catch (error) {
    log('error', 'Build failed');
    throw error;
  }
}

// Git operations
async function gitPush() {
  logSection('Step 2: Git Commit & Push');

  try {
    // Check if there are changes
    const status = execSync('git status --porcelain', {
      cwd: projectRoot,
      encoding: 'utf8',
    }).trim();

    if (!status) {
      log('warn', 'No changes to commit');
      return true;
    }

    log('info', 'Changes detected, committing...');
    exec('git add -A');

    const timestamp = new Date().toISOString();
    exec(`git commit -m "Build: ${timestamp}"`);

    log('info', 'Pushing to main branch...');
    exec('git push origin main');

    log('success', 'Code pushed to repository');
    return true;
  } catch (error) {
    log('error', 'Git operations failed');
    throw error;
  }
}

// Deploy via SSH
async function deployViaSSH(config, keyPath) {
  logSection('Step 3: Deploying to Server');

  const host = config.DEPLOY_SERVER_HOST;
  const user = config.DEPLOY_SERVER_USER;
  const port = config.DEPLOY_SERVER_PORT || '22';
  const appDir = config.DEPLOY_SERVER_APP_DIR;
  const webDir = config.DEPLOY_SERVER_WEB_DIR;

  // Validate config
  if (!host || !user || !appDir) {
    log('error', 'Incomplete SSH configuration in .env.deploy');
    process.exit(1);
  }

  log('info', `Target: ${user}@${host}:${port}`);
  log('info', `App Dir: ${appDir}`);
  log('info', `Web Dir: ${webDir}`);

  try {
    // SSH commands untuk deployment
    const deployCommands = `
set -e

echo "📦 Starting deployment..."

APP_DIR="${appDir}"
WEB_DIR="${webDir}"

# Pull latest code
cd "$APP_DIR"
echo "🔄 Pulling latest code..."
git pull origin main

# Install dependencies
echo "📚 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Upload assets (assume sudah ter-upload via SCP dari GitHub Actions)
# atau manual sync jika perlu

# Sync public folder ke web root
if [ "$WEB_DIR" != "$APP_DIR/public" ]; then
  echo "📁 Syncing public folder to web root..."
  mkdir -p "$WEB_DIR"
  rsync -a --delete "$APP_DIR/public/" "$WEB_DIR/"
fi

# Clear caches
echo "🔄 Clearing Laravel caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed successfully!"
    `.trim();

    // Escape quotes untuk SSH command
    const escapedCommand = deployCommands.replace(/"/g, '\\"');

    // Build SSH command
    let sshCmd = `ssh -i "${keyPath}" -p ${port} ${user}@${host}`;

    // Add passphrase if exists (untuk interactive prompt)
    if (config.DEPLOY_SSH_PASSPHRASE) {
      log('warn', 'Note: SSH passphrase configured, you may be prompted.');
    }

    log('info', 'Connecting to server and deploying...');
    exec(`${sshCmd} "${escapedCommand}"`);

    log('success', 'Deployment completed successfully!');
    return true;
  } catch (error) {
    log('error', 'Deployment failed');
    throw error;
  }
}

// Main execution
async function main() {
  try {
    logSection('🚀 ROFC Laravel Auto Deploy');

    const args = process.argv.slice(2);
    const skipBuild = args.includes('--no-build');

    // Load configuration
    log('info', 'Loading configuration...');
    const config = loadConfig();
    log('success', 'Configuration loaded');

    // Validate SSH key
    log('info', 'Validating SSH key...');
    const keyPath = validateSSHKey(config);
    log('success', `SSH key found: ${keyPath}`);

    // Build assets
    if (!skipBuild) {
      await buildAssets();
    } else {
      log('warn', 'Skipping build (--no-build flag)');
    }

    // Git push
    await gitPush();

    // Deploy
    await deployViaSSH(config, keyPath);

    logSection('✅ DEPLOYMENT SUCCESS');
    console.log(`${colors.green}${colors.bright}All done! Your application is live.${colors.reset}\n`);
  } catch (error) {
    logSection('❌ DEPLOYMENT FAILED');
    console.error(`${colors.red}${colors.bright}Deployment encountered an error.${colors.reset}\n`);
    process.exit(1);
  }
}

main();
