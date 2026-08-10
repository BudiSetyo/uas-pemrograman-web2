<?php

class ArticleController
{
    private $articleModel;
    private $categoryModel;

    public function __construct()
    {
        $this->articleModel = new Article();
        $this->categoryModel = new Category();
    }

    public function index()
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $search = trim($_GET['search'] ?? '');
        $categoryId = $_GET['category'] ?? null;
        $perPage = 6;

        $articles = $this->articleModel->all($page, $perPage, $search, $categoryId);
        $total = $this->articleModel->countAll($search, $categoryId);
        $totalPages = max(1, ceil($total / $perPage));
        $categories = $this->categoryModel->all();

        view('articles.index', [
            'articles' => $articles,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    public function show($slug)
    {
        $article = $this->articleModel->findBySlug($slug);

        if (!$article) {
            http_response_code(404);
            view('404');
            return;
        }

        view('articles.show', ['article' => $article]);
    }

    public function create()
    {
        if (!isLoggedIn()) {
            redirect('/login');
        }

        $categories = $this->categoryModel->all();
        view('articles.create', ['categories' => $categories]);
        clearFlash();
    }

    public function store()
    {
        if (!isLoggedIn()) {
            redirect('/login');
        }

        $title = trim($_POST['title'] ?? '');
        $categoryId = $_POST['category_id'] ?? '';
        $content = $_POST['content'] ?? '';
        $errors = [];

        if (empty($title)) {
            $errors['title'] = 'Judul harus diisi';
        } elseif (strlen($title) < 5) {
            $errors['title'] = 'Judul minimal 5 karakter';
        }

        if (empty($categoryId)) {
            $errors['category_id'] = 'Kategori harus dipilih';
        }

        if (empty($content)) {
            $errors['content'] = 'Konten harus diisi';
        } elseif (strlen(strip_tags($content)) < 20) {
            $errors['content'] = 'Konten minimal 20 karakter';
        }

        // Cover upload
        $cover = null;
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['cover']['type'];
            $fileSize = $_FILES['cover']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                $errors['cover'] = 'Tipe file harus JPG, PNG, GIF, atau WebP';
            } elseif ($fileSize > 5 * 1024 * 1024) {
                $errors['cover'] = 'Ukuran file maksimal 5MB';
            } else {
                $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
                $filename = 'cover_' . time() . '_' . uniqid() . '.' . $ext;
                $destination = __DIR__ . '/../../public/uploads/covers/' . $filename;

                if (move_uploaded_file($_FILES['cover']['tmp_name'], $destination)) {
                    $cover = 'uploads/covers/' . $filename;
                }
            }
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld(['title' => $title, 'category_id' => $categoryId, 'content' => $content]);
            redirect('/articles/create');
        }

        $slug = generateSlug($title);
        $excerpt = truncate(strip_tags($content), 200);

        $this->articleModel->create([
            'user_id' => auth()['id'],
            'category_id' => $categoryId,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'cover' => $cover,
            'excerpt' => $excerpt,
        ]);

        setSuccess('Artikel berhasil dibuat');
        redirect('/articles');
    }

    public function edit($id)
    {
        if (!isLoggedIn()) {
            redirect('/login');
        }

        $article = $this->articleModel->findById($id);

        if (!$article) {
            http_response_code(404);
            view('404');
            return;
        }

        if ($article['user_id'] != auth()['id']) {
            setErrors(['auth' => 'Anda tidak memiliki akses untuk mengedit artikel ini']);
            redirect('/articles');
        }

        $categories = $this->categoryModel->all();
        view('articles.edit', ['article' => $article, 'categories' => $categories]);
        clearFlash();
    }

    public function update($id)
    {
        if (!isLoggedIn()) {
            redirect('/login');
        }

        $article = $this->articleModel->findById($id);

        if (!$article) {
            http_response_code(404);
            view('404');
            return;
        }

        if ($article['user_id'] != auth()['id']) {
            setErrors(['auth' => 'Anda tidak memiliki akses untuk mengedit artikel ini']);
            redirect('/articles');
        }

        $title = trim($_POST['title'] ?? '');
        $categoryId = $_POST['category_id'] ?? '';
        $content = $_POST['content'] ?? '';
        $errors = [];

        if (empty($title)) {
            $errors['title'] = 'Judul harus diisi';
        } elseif (strlen($title) < 5) {
            $errors['title'] = 'Judul minimal 5 karakter';
        }

        if (empty($categoryId)) {
            $errors['category_id'] = 'Kategori harus dipilih';
        }

        if (empty($content)) {
            $errors['content'] = 'Konten harus diisi';
        } elseif (strlen(strip_tags($content)) < 20) {
            $errors['content'] = 'Konten minimal 20 karakter';
        }

        // Cover upload
        $cover = null;
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['cover']['type'];
            $fileSize = $_FILES['cover']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                $errors['cover'] = 'Tipe file harus JPG, PNG, GIF, atau WebP';
            } elseif ($fileSize > 5 * 1024 * 1024) {
                $errors['cover'] = 'Ukuran file maksimal 5MB';
            } else {
                $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
                $filename = 'cover_' . time() . '_' . uniqid() . '.' . $ext;
                $destination = __DIR__ . '/../../public/uploads/covers/' . $filename;

                if (move_uploaded_file($_FILES['cover']['tmp_name'], $destination)) {
                    $cover = 'uploads/covers/' . $filename;

                    // Delete old cover
                    if ($article['cover'] && file_exists(__DIR__ . '/../../public/' . $article['cover'])) {
                        unlink(__DIR__ . '/../../public/' . $article['cover']);
                    }
                }
            }
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld(['title' => $title, 'category_id' => $categoryId, 'content' => $content]);
            redirect('/articles/edit/' . $id);
        }

        $slug = generateSlug($title);
        $excerpt = truncate(strip_tags($content), 200);

        $updateData = [
            'category_id' => $categoryId,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
        ];

        if ($cover) {
            $updateData['cover'] = $cover;
        }

        $this->articleModel->update($id, $updateData);

        setSuccess('Artikel berhasil diperbarui');
        redirect('/articles');
    }

    public function delete($id)
    {
        if (!isLoggedIn()) {
            redirect('/login');
        }

        $article = $this->articleModel->findById($id);

        if (!$article) {
            http_response_code(404);
            view('404');
            return;
        }

        if ($article['user_id'] != auth()['id']) {
            setErrors(['auth' => 'Anda tidak memiliki akses untuk menghapus artikel ini']);
            redirect('/articles');
        }

        // Delete cover file
        if ($article['cover'] && file_exists(__DIR__ . '/../../public/' . $article['cover'])) {
            unlink(__DIR__ . '/../../public/' . $article['cover']);
        }

        $this->articleModel->delete($id);

        setSuccess('Artikel berhasil dihapus');
        redirect('/articles');
    }
}