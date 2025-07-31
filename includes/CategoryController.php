<?php

class CategoryController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Haal alle actieve categorieën op, gesorteerd op sort_order
     */
    public function getAll($activeOnly = true) {
        $sql = "SELECT * FROM blog_categories";
        
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        
        $sql .= " ORDER BY sort_order ASC, name ASC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Haal een categorie op aan de hand van ID
     */
    public function getById($id) {
        $this->db->query("SELECT * FROM blog_categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Haal een categorie op aan de hand van slug
     */
    public function getBySlug($slug) {
        $this->db->query("SELECT * FROM blog_categories WHERE slug = :slug AND is_active = 1");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    /**
     * Haal blogs op per categorie
     */
    public function getBlogsByCategory($categoryId, $limit = null) {
        $sql = "SELECT blogs.*, users.username as author_name, users.profile_photo, blog_categories.name as category_name, blog_categories.slug as category_slug, blog_categories.color as category_color
                FROM blogs 
                JOIN users ON blogs.author_id = users.id 
                LEFT JOIN blog_categories ON blogs.category_id = blog_categories.id
                WHERE blogs.category_id = :category_id
                ORDER BY blogs.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $this->db->query($sql);
        $this->db->bind(':category_id', $categoryId);
        return $this->db->resultSet();
    }

    /**
     * Tel het aantal blogs per categorie
     */
    public function getBlogCountByCategory() {
        $this->db->query("
            SELECT bc.id, bc.name, bc.slug, bc.color, bc.icon, COUNT(b.id) as blog_count
            FROM blog_categories bc
            LEFT JOIN blogs b ON bc.id = b.category_id
            WHERE bc.is_active = 1
            GROUP BY bc.id, bc.name, bc.slug, bc.color, bc.icon
            ORDER BY bc.sort_order ASC, bc.name ASC
        ");
        return $this->db->resultSet();
    }

    /**
     * Maak een nieuwe categorie aan
     */
    public function create($data) {
        $this->db->query("
            INSERT INTO blog_categories (name, slug, description, color, icon, sort_order, is_active) 
            VALUES (:name, :slug, :description, :color, :icon, :sort_order, :is_active)
        ");
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $this->generateSlug($data['name']));
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':color', $data['color'] ?? '#3B82F6');
        $this->db->bind(':icon', $data['icon'] ?? 'folder');
        $this->db->bind(':sort_order', $data['sort_order'] ?? 0);
        $this->db->bind(':is_active', $data['is_active'] ?? true);
        
        return $this->db->execute();
    }

    /**
     * Update een bestaande categorie
     */
    public function update($id, $data) {
        $this->db->query("
            UPDATE blog_categories 
            SET name = :name, 
                slug = :slug, 
                description = :description, 
                color = :color, 
                icon = :icon, 
                sort_order = :sort_order,
                is_active = :is_active,
                updated_at = NOW()
            WHERE id = :id
        ");
        
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug'] ?? $this->generateSlug($data['name']));
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':color', $data['color'] ?? '#3B82F6');
        $this->db->bind(':icon', $data['icon'] ?? 'folder');
        $this->db->bind(':sort_order', $data['sort_order'] ?? 0);
        $this->db->bind(':is_active', $data['is_active'] ?? true);
        
        return $this->db->execute();
    }

    /**
     * Verwijder een categorie (zet op inactief)
     */
    public function delete($id) {
        $this->db->query("UPDATE blog_categories SET is_active = 0 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Genereer een slug van een naam
     */
    private function generateSlug($name) {
        // Nederlandse karakters converteren
        $slug = str_replace(
            ['ä', 'ë', 'ï', 'ö', 'ü', 'ÿ', 'ß', 'ç'],
            ['a', 'e', 'i', 'o', 'u', 'y', 'ss', 'c'],
            strtolower($name)
        );
        
        // Alle niet-alfanumerieke karakters vervangen door koppeltekens
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        
        // Meerdere koppeltekens vervangen door enkele
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Koppeltekens aan begin en eind verwijderen
        $slug = trim($slug, '-');
        
        return $slug;
    }

    /**
     * Haal categorieën met hun populariteit op (voor analytics)
     */
    public function getCategoriesWithStats() {
        $this->db->query("
            SELECT 
                bc.*,
                COUNT(b.id) as total_blogs,
                AVG(b.views) as avg_views,
                SUM(b.likes) as total_likes,
                MAX(b.published_at) as latest_blog_date
            FROM blog_categories bc
            LEFT JOIN blogs b ON bc.id = b.category_id
            WHERE bc.is_active = 1
            GROUP BY bc.id
            ORDER BY total_blogs DESC, bc.sort_order ASC
        ");
        return $this->db->resultSet();
    }
}