#!/bin/bash

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
DIST_DIR="$PROJECT_DIR/dist"

echo "=========================================="
echo "LinkDigest Plugin Build"
echo "=========================================="
echo ""

# Resolve version from plugin header
VERSION=$(grep -m1 "Version:" "$PROJECT_DIR/linkdigest.php" | sed 's/.*Version: *//')
if [ -z "$VERSION" ]; then
    echo "ERROR: Could not read plugin version from linkdigest.php" >&2
    exit 1
fi

PLUGIN_NAME="linkdigest"
ZIP_NAME="${PLUGIN_NAME}-${VERSION}.zip"
ZIP_PATH="$DIST_DIR/$ZIP_NAME"
STAGE_DIR="$DIST_DIR/${PLUGIN_NAME}"

echo "Version : $VERSION"
echo "Output  : $ZIP_PATH"
echo ""

# Require node_modules (avoid slow npm install inside a build script)
if [ ! -d "$PROJECT_DIR/node_modules" ]; then
    echo "ERROR: node_modules not found. Run 'npm install' first." >&2
    exit 1
fi

# Clean previous dist
rm -rf "$DIST_DIR"
mkdir -p "$STAGE_DIR"

# Build compiled JS/CSS (wp-scripts writes to build/)
echo "Building JavaScript assets..."
npm --prefix "$PROJECT_DIR" run build

# Copy files into staging directory, honouring .distignore
echo "Copying plugin files..."
rsync -a \
    --exclude-from="$PROJECT_DIR/.distignore" \
    --exclude='dist/' \
    "$PROJECT_DIR/" "$STAGE_DIR/"

# Zip the staging directory
echo "Creating archive..."
(cd "$DIST_DIR" && zip -r -q "$ZIP_PATH" "$PLUGIN_NAME")

echo ""
echo "=========================================="
echo "Build complete"
echo "=========================================="
echo ""
echo "Archive : $ZIP_NAME"
echo "Size    : $(du -h "$ZIP_PATH" | cut -f1)"
echo "Path    : $ZIP_PATH"
echo ""
