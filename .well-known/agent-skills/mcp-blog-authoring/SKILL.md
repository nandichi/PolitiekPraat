---
name: mcp-blog-authoring
description: >
  Autonoom politieke blogs schrijven, bewerken en publiceren op PolitiekPraat
  via de MCP-server. Ondersteunt draft, scheduled en published statussen,
  categorieen, tags, SEO-metadata en featured image (URL-upload of AI-generatie).
version: 1.0.0
language: nl-NL
---

# Autonoom blogs schrijven via MCP

## Wanneer gebruiken

- Gebruiker vraagt Claude (of een andere agent) om een nieuwe blog te schrijven
  en te plaatsen op PolitiekPraat.
- Gebruiker wil een draft voorbereiden, een blog inplannen voor publicatie,
  een bestaande blog bewerken of depubliceren.

## Benodigde authenticatie

Alle write-tools vereisen OAuth 2.0 met minimaal:

- Scope `mcp.write`
- Scope `blogs.write` (eigen blogs) of `blogs.admin` (alle blogs)
- Voor media: `media.write`

MCP endpoint: `https://politiekpraat.nl/.well-known/mcp/server-card.json`

## Standaard workflow

1. `create_blog_draft` met `title`, `content` (Markdown), optioneel `summary`,
   `category_id`, `tags`, `seo_title`, `seo_description`.
2. `set_blog_featured_image` met ofwel `image_url` (externe URL) of via
   `generate_blog_image` + resulterende `media_id`.
3. `publish_blog` om direct live te zetten, of `schedule_blog` met
   `scheduled_for` (ISO 8601, UTC of lokaal) om in te plannen.
4. Optioneel: `set_blog_category`, `set_blog_tags` voor updates.

## Belangrijke regels

- Content is Markdown; geen ruwe HTML.
- `reading_time` wordt automatisch berekend als je het leeg laat.
- `excerpt` = korte samenvatting voor overzichtspagina's (max 300 tekens).
- Gebruik geen click-bait titles; hou tone informatief en feitelijk.
- Geplande blogs krijgen automatisch `status=published` als
  `scheduled_for <= NOW()` door cron `publish_scheduled_blogs.php`.

## Nuttige read-tools

- `list_drafts` — alle eigen drafts
- `list_scheduled_blogs` — wat staat er klaar
- `list_blog_categories` — geldige category_ids
- `get_blog_analytics` — views/likes per blog
