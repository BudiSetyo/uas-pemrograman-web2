<?php

class Article
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all($page = 1, $perPage = 6, $search = '', $categoryId = null)
    {
        $offset = ($page - 1) * $perPage;
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where .= ' AND (a.title LIKE ? OR a.content LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($categoryId) {
            $where .= ' AND a.category_id = ?';
            $params[] = $categoryId;
        }

        $sql = "SELECT a.*, u.username, c.name as category_name
                FROM articles a
                JOIN users u ON a.user_id = u.id
                JOIN categories c ON a.category_id = c.id
                WHERE 1=1 $where
                ORDER BY a.created_at DESC
                LIMIT ? OFFSET ?";

        $params[] = $perPage;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countAll($search = '', $categoryId = null)
    {
        $where = '';
        $params = [];

        if (!empty($search)) {
            $where .= ' AND (title LIKE ? OR content LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($categoryId) {
            $where .= ' AND category_id = ?';
            $params[] = $categoryId;
        }

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as count FROM articles WHERE 1=1 $where"
        );
        $stmt->execute($params);
        return $stmt->fetch()['count'];
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.username, c.name as category_name
             FROM articles a
             JOIN users u ON a.user_id = u.id
             JOIN categories c ON a.category_id = c.id
             WHERE a.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findBySlug($slug)
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.username, c.name as category_name
             FROM articles a
             JOIN users u ON a.user_id = u.id
             JOIN categories c ON a.category_id = c.id
             WHERE a.slug = ?"
        );
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO articles (user_id, category_id, title, slug, content, cover, excerpt)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        return $stmt->execute([
            $data['user_id'],
            $data['category_id'],
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['cover'] ?? null,
            $data['excerpt'] ?? null,
        ]);
    }

    public function update($id, $data)
    {
        $sql = 'UPDATE articles SET category_id = ?, title = ?, slug = ?, content = ?, excerpt = ?';
        $params = [$data['category_id'], $data['title'], $data['slug'], $data['content'], $data['excerpt'] ?? null];

        if (isset($data['cover']) && $data['cover'] !== null) {
            $sql .= ', cover = ?';
            $params[] = $data['cover'];
        }

        $sql .= ' WHERE id = ?';
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM articles WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getByUser($userId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare(
            "SELECT a.*, c.name as category_name
             FROM articles a
             JOIN categories c ON a.category_id = c.id
             WHERE a.user_id = ?
             ORDER BY a.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$userId, $perPage, $offset]);
        return $stmt->fetchAll();
    }

    public function countByUser($userId)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as count FROM articles WHERE user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetch()['count'];
    }

    public function latest($limit = 3)
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.username, c.name as category_name
             FROM articles a
             JOIN users u ON a.user_id = u.id
             JOIN categories c ON a.category_id = c.id
             ORDER BY a.created_at DESC
             LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}