#!/usr/bin/env bash
set -euo pipefail

# Read config from sonar-project.properties
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
WHITESPACE_CHARS='[:space:]'
PROJECT_KEY=$(grep 'sonar.projectKey=' "$SCRIPT_DIR/sonar-project.properties" | cut -d= -f2 | tr -d "$WHITESPACE_CHARS")
ORGANIZATION=$(grep 'sonar.organization=' "$SCRIPT_DIR/sonar-project.properties" | cut -d= -f2 | tr -d "$WHITESPACE_CHARS")
SONAR_HOST=$(grep 'sonar.host.url=' "$SCRIPT_DIR/sonar-project.properties" | cut -d= -f2 | tr -d "$WHITESPACE_CHARS")

# Token is read from SONAR_TOKEN environment variable (not stored in file for security)

REPORT_DIR="$SCRIPT_DIR/reports"
OUTPUT_FILE="$REPORT_DIR/sonar-report.json"
DOWNLOAD=true

# Parse arguments
for arg in "$@"; do
  case "$arg" in
    --download) DOWNLOAD=true ;;
    --no-download) DOWNLOAD=false ;;
    --output=*) OUTPUT_FILE="${arg#--output=}" ;;
    *) echo "Unknown option: $arg"; exit 1 ;;
  esac
done

mkdir -p "$REPORT_DIR"

# Verify SONAR_TOKEN environment variable is set
if [[ -z "${SONAR_TOKEN:-}" ]]; then
  echo "Error: SONAR_TOKEN environment variable not set"
  exit 1
fi

# Run analysis
echo "Running SonarCloud analysis..."
SCANNER_EXIT=0
sonar-scanner \
  -Dsonar.projectKey="$PROJECT_KEY" \
  -Dsonar.organization="$ORGANIZATION" \
  -Dsonar.host.url="$SONAR_HOST" \
  -Dsonar.qualitygate.wait=true \
  || SCANNER_EXIT=$?

# Download report if requested (always runs, even when quality gate fails)
if [[ "$DOWNLOAD" = true ]]; then
  echo ""
  echo "Downloading report to $OUTPUT_FILE..."
  curl -s -u "${SONAR_TOKEN}:" \
    "$SONAR_HOST/api/issues/search?componentKeys=${PROJECT_KEY}&resolved=false&ps=500&organization=${ORGANIZATION}" \
    -o "$OUTPUT_FILE"
  echo "Report saved to $OUTPUT_FILE"

  # Convert JSON report to Markdown
  MD_FILE="${OUTPUT_FILE%.json}.md"
  echo "Converting report to $MD_FILE..."

  TOTAL=$(jq '.total // 0' "$OUTPUT_FILE")
  DATE=$(date '+%Y-%m-%d %H:%M')

  {
    echo "# SonarCloud Report — $PROJECT_KEY"
    echo "_Generated: $DATE — $TOTAL open issue(s)_"
    echo ""

    if [[ "$TOTAL" -eq 0 ]]; then
      echo "No open issues found."
    else
      jq -r '
        .issues[] |
        "### \(.severity) — \(.type)\n" +
        "- **File:** \(.component | sub("^[^:]+:"; ""))\n" +
        "- **Line:** \(.textRange.startLine // "N/A")\n" +
        "- **Rule:** \(.rule)\n" +
        "- **Message:** \(.message)\n" +
        "- **Effort:** \(.effort // "N/A")\n"
      ' "$OUTPUT_FILE"
    fi
  } > "$MD_FILE"

  echo "Markdown saved to $MD_FILE"
fi

echo ""
echo "Done. View results at $SONAR_HOST/dashboard?id=${PROJECT_KEY}"

exit "${SCANNER_EXIT:-0}"
