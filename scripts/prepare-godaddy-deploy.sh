#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUTPUT_DIR="$ROOT_DIR/deploy/godaddy"

prepare_bundle() {
  local app_dir="$1"
  local bundle_name="$2"
  local bundle_root="$OUTPUT_DIR/$bundle_name"
  local web_root="$bundle_root/public_html"

  echo "==> Building React app: $app_dir"
  (
    cd "$ROOT_DIR/$app_dir"
    npm ci
    npm run build
  )

  echo "==> Preparing bundle: $bundle_name"
  rm -rf "$bundle_root"
  mkdir -p "$web_root"

  rsync -a "$ROOT_DIR/$app_dir/dist/" "$web_root/"
  rsync -a "$ROOT_DIR/backend/api/" "$web_root/api/"
  rsync -a "$ROOT_DIR/backend/config/" "$web_root/config/"

  cp "$ROOT_DIR/backend/.env.example" "$web_root/.env.example"
  cp "$ROOT_DIR/backend/database.sql" "$bundle_root/database.sql"
}

mkdir -p "$OUTPUT_DIR"

prepare_bundle "frontend" "software"
prepare_bundle "kunalSoftware" "kunalSoftware"

(
  cd "$OUTPUT_DIR"
  rm -f software.zip kunalSoftware.zip
  zip -rq software.zip software
  zip -rq kunalSoftware.zip kunalSoftware
)

echo
echo "GoDaddy bundles are ready:"
echo "  $OUTPUT_DIR/software"
echo "  $OUTPUT_DIR/kunalSoftware"
echo "  $OUTPUT_DIR/software.zip"
echo "  $OUTPUT_DIR/kunalSoftware.zip"
