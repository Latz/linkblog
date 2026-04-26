#!/bin/bash

echo "=========================================="
echo "LinkDigest Test Suite"
echo "=========================================="
echo ""

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

cd "$PROJECT_DIR"

EXIT_CODES=()

# Run PHP Unit Tests
echo "Running PHP Unit Tests (Pest)..."
echo ""
composer run test:unit || EXIT_CODES+=(1)

echo ""
echo "=========================================="

# Run PHP Integration Tests
echo "Running PHP Integration Tests (Pest)..."
echo ""
composer run test:integration || EXIT_CODES+=(1)

echo ""
echo "=========================================="

# Run JavaScript Tests
echo "Running JavaScript Tests (Vitest)..."
echo ""
npm run test:js || EXIT_CODES+=(1)

echo ""
echo "=========================================="

# Run E2E Tests
echo "Running E2E Tests (Playwright)..."
echo ""
npm run test:e2e || EXIT_CODES+=(1)

echo ""
echo "=========================================="

# Run WordPress Plugin Check (excluding Plugin Repo tests)
echo "Running WordPress Plugin Check..."
echo ""
if command -v wp &> /dev/null; then
  wp plugin check LinkDigest \
    --exclude-checks=code_obfuscation,plugin_content,file_type,plugin_header_fields,plugin_updater,plugin_uninstall,plugin_review_phpcs,plugin_readme,localhost,no_unfiltered_uploads,trademarks,offloading_files \
    || EXIT_CODES+=(1)
else
  echo "⚠ WP-CLI not found, skipping WordPress plugin validation"
fi

echo ""
echo "=========================================="
echo "Test Results Summary"
echo "=========================================="

if [ ${#EXIT_CODES[@]} -eq 0 ]; then
  echo "✓ All tests passed!"
  exit 0
else
  echo "✗ Some tests failed (${#EXIT_CODES[@]} test suite(s) failed)"
  exit 1
fi
