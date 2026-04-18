---
name: mcp-community
description: >
  Interactie met de PolitiekPraat community via MCP: reageren op blogs,
  forum-topics plaatsen/beantwoorden, stemmen op polls en nieuwsbrief
  beheren. Alle schrijf-acties gebeuren namens de ingelogde user.
version: 1.0.0
language: nl-NL
---

# Community-interactie via MCP

## Wanneer gebruiken

- Gebruiker wil via een agent reageren op een blog of forum-topic.
- Agent moet een poll aanmaken bij een nieuwe blog.
- Gebruiker wil zich (af)melden voor de nieuwsbrief.

## Benodigde authenticatie

OAuth 2.0 met `mcp.write` plus domain-scope:

- `comments.write` voor comments
- `forum.write` voor forum-topics en -replies
- `polls.write` voor polls en stemmen
- `newsletter.write` voor nieuwsbrief aan/afmelden

Alleen eigen content kan bewerkt/verwijderd worden, tenzij de user scope
`blogs.admin` heeft.

## Belangrijkste tools

Comments:

- `list_comments_for_blog`, `post_comment`, `update_comment`, `delete_comment`

Forum:

- `list_forum_topics`, `get_forum_topic`, `post_forum_topic`,
  `reply_to_forum_topic`, `update_forum_topic`, `delete_forum_reply`

Polls:

- `get_blog_poll`, `create_blog_poll`, `vote_blog_poll`

Nieuwsbrief:

- `subscribe_newsletter`, `unsubscribe_newsletter`

## Regels

- Hou toon respectvol en on-topic; modereer content waar nodig.
- Polls moeten 2-6 opties bevatten; users mogen één stem per poll.
- Nieuwsbrief-acties werken alleen voor het e-mailadres van de ingelogde user.
