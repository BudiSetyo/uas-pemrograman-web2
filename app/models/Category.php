<?php

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findBySlug($slug)
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE slug = ?');
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (name, slug) VALUES (?, ?)'
        );
        return $stmt->execute([$data['name'], generateSlug($data['name'])]);
    }

    public function getArticleCount($id)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as count FROM articles WHERE category_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch()['count'];
    }
}