-- Performance en dedupe indexes voor news_articles hot queries
-- Draai dit script veilig meerdere keren (idempotent via IF NOT EXISTS).

-- Houd alleen de oudste record per URL, zodat UNIQUE index kan worden toegevoegd.
DELETE n1
FROM news_articles n1
INNER JOIN news_articles n2 ON n1.url = n2.url
WHERE n1.id > n2.id;

ALTER TABLE news_articles
    ADD UNIQUE INDEX IF NOT EXISTS uq_news_articles_url (url(255)),
    ADD INDEX IF NOT EXISTS idx_source_published_at (source, published_at),
    ADD INDEX IF NOT EXISTS idx_orientation_published_at (orientation, published_at);
