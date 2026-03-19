#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT_DIR"

echo "[smoke] Check security baselines"
grep -Eq '^\s*display_errors\s*=\s*Off\b' php.ini
grep -Eq '^\s*display_errors\s*=\s*Off\b' .user.ini

echo "[smoke] Check API hardening includes"
grep -q "require_once __DIR__ . '/../includes/cors.php';" api/index.php
grep -q "require_once __DIR__ . '/../includes/rate_limiter.php';" api/index.php
grep -q "enforce_api_rate_limit();" api/index.php

echo "[smoke] Check gitignore hygiene"
grep -q "^node_modules/" .gitignore
grep -q "^\.DS_Store$" .gitignore


if [[ "${RUN_API_CONTRACT_TESTS:-0}" == "1" ]]; then
  echo "[smoke] Run API contract checks"
  bash scripts/tests/test-api-contract-parties.sh "${API_CONTRACT_BASE_URL:-https://politiekpraat.nl}"
  bash scripts/tests/test-api-contract-blogs.sh "${API_CONTRACT_BASE_URL:-https://politiekpraat.nl}"
fi

echo "[smoke] OK"
