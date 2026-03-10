#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${1:-https://politiekpraat.nl}"
ENDPOINTS=(
  "/api/parties"
  "/api/endpoints/parties"
)

fail=0
for endpoint in "${ENDPOINTS[@]}"; do
  url="${BASE_URL}${endpoint}"
  tmp_body="$(mktemp)"
  status=$(curl -sS -o "$tmp_body" -w "%{http_code}" "$url" || true)

  if [[ "$status" != "200" ]]; then
    echo "FAIL: $url gaf status $status"
    fail=1
    rm -f "$tmp_body"
    continue
  fi

  if ! python3 - "$tmp_body" <<'PY'
import json, sys
body_path = sys.argv[1]
with open(body_path, 'r', encoding='utf-8') as f:
    data = json.load(f)
if not isinstance(data, dict):
    raise SystemExit(1)
if data.get('success') is not True:
    raise SystemExit(1)
payload = data.get('data', {})
if not isinstance(payload, dict) or 'parties' not in payload:
    raise SystemExit(1)
print(f"OK: parties={len(payload.get('parties', []))}")
PY
  then
    echo "FAIL: $url gaf geen geldig parties contract"
    fail=1
  else
    echo "PASS: $url"
  fi

  rm -f "$tmp_body"
done

if [[ "$fail" -ne 0 ]]; then
  echo "Contract smoke test gefaald"
  exit 1
fi

echo "Contract smoke test geslaagd"
