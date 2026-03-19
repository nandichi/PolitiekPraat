-- Performance indexes voor forum-overzicht query
CREATE INDEX idx_forum_replies_topic_created_at ON forum_replies (topic_id, created_at);
CREATE INDEX idx_forum_topics_last_activity ON forum_topics (last_activity);
