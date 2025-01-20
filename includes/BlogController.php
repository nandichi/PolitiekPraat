<?php
class BlogController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($limit = null) {
        $sql = "SELECT blogs.*, users.username as author_name 
                FROM blogs 
                JOIN users ON blogs.author_id = users.id 
                ORDER BY published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . $limit;
        }
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getBySlug($slug) {
        $this->db->query("SELECT blogs.*, users.username as author_name 
                         FROM blogs 
                         JOIN users ON blogs.author_id = users.id 
                         WHERE blogs.slug = :slug");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO blogs (title, slug, summary, content, image_path, author_id) 
                         VALUES (:title, :slug, :summary, :content, :image_path, :author_id)");
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':slug', generateSlug($data['title']));
        $this->db->bind(':summary', $data['summary']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':author_id', $_SESSION['user_id']);

        return $this->db->execute();
    }

    public function update($data) {
        $this->db->query("UPDATE blogs 
                         SET title = :title, summary = :summary, content = :content, 
                             image_path = :image_path 
                         WHERE id = :id AND author_id = :author_id");
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':summary', $data['summary']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':author_id', $_SESSION['user_id']);

        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM blogs WHERE id = :id AND author_id = :author_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':author_id', $_SESSION['user_id']);
        return $this->db->execute();
    }
} 