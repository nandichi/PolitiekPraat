#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${1:-https://politiekpraat.nl}"
ENDPOINT="/api/blogs?page=1&limit=5"
url="${BASE_URL}${ENDPOINT}"

tmp_body="$(mktemp)"
status=$(curl -sS -o "$tmp_body" -w "%{http_code}" "$url" || true)

if [[ "$status" != "200" ]]; then
  echo "FAIL: $url gaf status $status"
  rm -f "$tmp_body"
  exit 1
fi

if ! python3 - "$tmp_body" <<'PY'
import json, sys
body_path = sys.argv[1]
with open(body_path, 'r', encoding='utf-8') as f:
    data = json.load(f)

if not isinstance(data, dict):
    raise SystemExit('response is geen object')
if data.get('success') is not True:
    raise SystemExit('success != true')
if not isinstance(data.get('timestamp'), str) or not data['timestamp']:
    raise SystemExit('timestamp ontbreekt')

payload = data.get('data')
if not isinstance(payload, dict):
    raise SystemExit('data ontbreekt')

blogs = payload.get('blogs')
pagination = payload.get('pagination')
if not isinstance(blogs, list):
    raise SystemExit('blogs is geen lijst')
if not isinstance(pagination, dict):
    raise SystemExit('pagination ontbreekt')

required_pagination = {'current_page', 'limit', 'total', 'total_pages', 'has_next', 'has_prev'}
if not required_pagination.issubset(pagination.keys()):
    raise SystemExit('pagination keys incompleet')

for i, blog in enumerate(blogs):
    if not isinstance(blog, dict):
        raise SystemExit(f'blog[{i}] is geen object')
    for key in ('id', 'title', 'slug', 'author', 'published_at', 'views', 'likes', 'media'):
        if key not in blog:
            raise SystemExit(f'blog[{i}] mist key: {key}')

print(f"OK: blogs={len(blogs)} total={pagination.get('total')}")
PY
then
  echo "FAIL: $url gaf geen geldig blogs contract"
  rm -f "$tmp_body"
  exit 1
fi

echo "PASS: $url"
rm -f "$tmp_body"
