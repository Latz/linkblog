# SONAR_TOKEN Setup Guide

This guide explains how to set up and manage SonarCloud tokens for this project and others.

## Overview

Three approaches for managing `SONAR_TOKEN` across your projects:

1. **Per-project `.env` file** (easiest for local development)
2. **Centralized credentials manager** (best for multiple projects)
3. **Environment variable** (for CI/CD pipelines)

---

## Option 1: Per-Project `.env` File (Recommended for Local Development)

### Setup

```bash
# Copy the example file
cp .env.example .env

# Edit and add your token
nano .env
```

### .env File Format

```bash
SONAR_TOKEN=squ_your_actual_token_here
```

### Usage

```bash
# Run sonar-scanner with wrapper script (auto-loads .env)
./bin/run-sonar

# Or manually set the variable
export SONAR_TOKEN=$(grep SONAR_TOKEN .env | cut -d= -f2)
./bin/sonar-scanner.sh
```

**Security:** `.env` is in `.gitignore` by default - never commit it.

---

## Option 2: Centralized Credentials Manager (Recommended for Multiple Projects)

### Initial Setup

```bash
# One-time setup: store tokens for all your projects
sonar-token-manager set Latz_linkblog "squ_your_token_here"
sonar-token-manager set OtherProject_key "squ_another_token"

# List stored projects
sonar-token-manager list
```

### Usage

```bash
# Simply run the wrapper script - it auto-loads from credentials manager
./bin/run-sonar

# Or directly with sonar-scanner.sh if sonar-token-manager is in PATH
./bin/sonar-scanner.sh
```

**File Location:** `~/.sonar/credentials.conf` (mode 600, user-only access)

**Credentials Manager Location:** Ensure `sonar-token-manager` is in your PATH:

```bash
# Check if in PATH
which sonar-token-manager

# If not, add to PATH in ~/.bashrc or ~/.zshrc:
export PATH="$HOME/bin:$PATH"
```

---

## Option 3: Environment Variable (For CI/CD)

### GitHub Actions Example

```yaml
name: SonarCloud Analysis

on: [push, pull_request]

jobs:
  sonar:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Run SonarCloud analysis
        env:
          SONAR_TOKEN: ${{ secrets.SONAR_TOKEN }}
        run: ./bin/sonar-scanner.sh
```

### GitLab CI Example

```yaml
sonar-analysis:
  script:
    - export SONAR_TOKEN=$SONAR_TOKEN
    - ./bin/sonar-scanner.sh
  only:
    - merge_requests
```

### Manual Usage

```bash
export SONAR_TOKEN="squ_your_token_here"
./bin/sonar-scanner.sh
```

---

## Token Resolution Order

The system tries to load the token in this order:

1. `.env` file in project root (if present)
2. `sonar-token-manager` (if available in PATH)
3. `SONAR_TOKEN` environment variable
4. Error if none found

---

## Applying to Other Projects

### For each new project:

1. **Copy the sonar-scanner infrastructure:**
   ```bash
   # From this project
   cp bin/sonar-scanner.sh /path/to/other-project/bin/
   cp bin/run-sonar /path/to/other-project/bin/
   cp .env.example /path/to/other-project/
   ```

2. **Update `sonar-project.properties`:**
   ```bash
   sonar.projectKey=YourProject_key
   sonar.organization=your-org
   # ... rest of config
   ```

3. **Store token in credentials manager:**
   ```bash
   sonar-token-manager set YourProject_key "squ_your_token"
   ```

4. **Run scans:**
   ```bash
   cd /path/to/other-project
   ./bin/run-sonar  # Auto-loads token from credentials manager
   ```

---

## Security Best Practices

✅ **Do:**
- Store tokens in `~/.sonar/credentials.conf` (encrypted storage recommended)
- Use `.env` file locally (add to `.gitignore`)
- Use CI/CD secrets (GitHub Secrets, GitLab Variables, etc.)
- Rotate tokens regularly
- Use project-specific tokens if supported by SonarCloud

❌ **Don't:**
- Commit tokens to version control
- Use `SONAR_TOKEN` in shell history (use `.env` file instead)
- Share token values in chat/email
- Use the same token across multiple environments

---

## Troubleshooting

### Token not found

```bash
# Check if .env exists
ls -la .env

# Check if credentials manager has the token
sonar-token-manager list

# Check environment variable
echo $SONAR_TOKEN
```

### sonar-token-manager not found

```bash
# Ensure it's in PATH
export PATH="$HOME/bin:$PATH"

# Or use full path
/path/to/sonar-token-manager get ProjectKey
```

### "SONAR_TOKEN environment variable not set"

```bash
# Try Option 1: Create .env file
cp .env.example .env
nano .env  # Add your token
./bin/run-sonar

# Or Option 2: Use credentials manager
sonar-token-manager set Latz_linkblog "squ_..."
./bin/run-sonar

# Or Option 3: Set manually
export SONAR_TOKEN="squ_..."
./bin/sonar-scanner.sh
```

---

## Getting a Token

1. Visit https://sonarcloud.io/account/security
2. Generate a new token
3. Copy and store securely

