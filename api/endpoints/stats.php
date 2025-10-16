<?php
/**
 * Statistics API Endpoint
 * Handles website statistics and analytics
 */

class StatsAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        $action = $segments[1] ?? null;
        
        switch ($method) {
            case 'GET':
                if ($action === 'trending') {
                    $this->getTrending();
                } elseif ($action === 'activity') {
                    $this->getRecentActivity();
                } else {
                    $this->getGeneralStats();
                }
                break;
                
            default:
                sendApiError('Method niet toegestaan. Alleen GET is beschikbaar.', 405);
        }
    }
    
    private function getGeneralStats() {
        try {
            $stats = [];
            
            // Blog statistics
            $this->db->query("SELECT COUNT(*) as total FROM blogs");
            $stats['blogs_total'] = $this->db->single()->total;
            
            $this->db->query("SELECT SUM(views) as total_views FROM blogs");
            $stats['blogs_total_views'] = $this->db->single()->total_views ?? 0;
            
            // User statistics
            $this->db->query("SELECT COUNT(*) as total FROM users");
            $stats['users_total'] = $this->db->single()->total;
            
            // Forum statistics
            $this->db->query("SELECT COUNT(*) as total FROM forum_topics");
            $stats['forum_topics_total'] = $this->db->single()->total;
            
            $this->db->query("SELECT COUNT(*) as total FROM forum_replies");
            $stats['forum_replies_total'] = $this->db->single()->total;
            
            // Comments statistics
            $this->db->query("SELECT COUNT(*) as total FROM comments");
            $stats['comments_total'] = $this->db->single()->total;
            
            // News statistics
            $this->db->query("SELECT COUNT(*) as total FROM news");
            $stats['news_total'] = $this->db->single()->total ?? 0;
            
            // Parties statistics
            $this->db->query("SELECT COUNT(*) as total FROM political_parties WHERE is_active = 1");
            $stats['parties_total'] = $this->db->single()->total ?? 0;
            
            sendApiResponse([
                'statistics' => $stats,
                'generated_at' => date('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen statistieken: ' . $e->getMessage(), 500);
        }
    }
    
    private function getTrending() {
        try {
            // Trending blogs (most views in last 7 days)
            $this->db->query("SELECT id, title, slug, views, published_at 
                FROM blogs 
                WHERE published_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                ORDER BY views DESC 
                LIMIT 10");
            $trending_blogs = $this->db->resultSet();
            
            // Most active forum topics (most replies in last 7 days)
            $this->db->query("SELECT 
                    forum_topics.id,
                    forum_topics.title,
                    forum_topics.views,
                    forum_topics.created_at,
                    (SELECT COUNT(*) FROM forum_replies WHERE topic_id = forum_topics.id) as reply_count
                FROM forum_topics
                WHERE forum_topics.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                ORDER BY reply_count DESC, forum_topics.views DESC
                LIMIT 10");
            $trending_topics = $this->db->resultSet();
            
            sendApiResponse([
                'trending' => [
                    'blogs' => $trending_blogs,
                    'forum_topics' => $trending_topics
                ],
                'period' => 'Last 7 days',
                'generated_at' => date('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen trending content: ' . $e->getMessage(), 500);
        }
    }
    
    private function getRecentActivity() {
        try {
            $limit = isset($_GET['limit']) ? min(50, max(1, intval($_GET['limit']))) : 20;
            
            // Recent blogs
            $this->db->query("SELECT 
                    id, title, slug, published_at, 
                    'blog' as type
                FROM blogs 
                ORDER BY published_at DESC 
                LIMIT :limit");
            $this->db->bind(':limit', $limit);
            $recent_blogs = $this->db->resultSet();
            
            // Recent forum topics
            $this->db->query("SELECT 
                    id, title, created_at,
                    'forum_topic' as type
                FROM forum_topics 
                ORDER BY created_at DESC 
                LIMIT :limit");
            $this->db->bind(':limit', $limit);
            $recent_topics = $this->db->resultSet();
            
            // Recent comments
            $this->db->query("SELECT 
                    comments.id,
                    comments.content,
                    comments.created_at,
                    blogs.title as blog_title,
                    users.username as author_name,
                    'comment' as type
                FROM comments
                JOIN blogs ON comments.blog_id = blogs.id
                JOIN users ON comments.user_id = users.id
                ORDER BY comments.created_at DESC 
                LIMIT :limit");
            $this->db->bind(':limit', $limit);
            $recent_comments = $this->db->resultSet();
            
            // Combine and sort by date
            $all_activity = array_merge($recent_blogs, $recent_topics, $recent_comments);
            usort($all_activity, function($a, $b) {
                $dateA = isset($a->published_at) ? $a->published_at : $a->created_at;
                $dateB = isset($b->published_at) ? $b->published_at : $b->created_at;
                return strtotime($dateB) - strtotime($dateA);
            });
            
            $all_activity = array_slice($all_activity, 0, $limit);
            
            sendApiResponse([
                'recent_activity' => $all_activity,
                'total' => count($all_activity),
                'generated_at' => date('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij ophalen recente activiteit: ' . $e->getMessage(), 500);
        }
    }
}

