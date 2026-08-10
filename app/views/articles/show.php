<?php $title = 'Detail Artikel'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-none">
            <?php if ($article['cover']): ?>
            <img src="/public/<?= htmlspecialchars($article['cover']) ?>" class="card-img-top" alt="<?= htmlspecialchars($article['title']) ?>">
            <?php endif; ?>
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-primary"><?= htmlspecialchars($article['category_name']) ?></span>
                    <span style="color: var(--mute); font-size: 14px;" class="ms-2">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($article['username']) ?>
                    </span>
                    <span style="color: var(--mute); font-size: 14px;" class="ms-2">
                        <i class="fas fa-calendar"></i> <?= date('d F Y', strtotime($article['created_at'])) ?>
                    </span>
                </div>

                <h2 class="mb-4" style="font-weight: 900;"><?= htmlspecialchars($article['title']) ?></h2>

                <div class="article-content">
                    <?= $article['content'] ?>
                </div>

                <?php if (isLoggedIn() && $article['user_id'] == auth()['id']): ?>
                <hr>
                <div class="d-flex gap-2">
                    <a href="/articles/edit/<?= $article['id'] ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="/articles/delete/<?= $article['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center mt-4 mb-5">
            <a href="/articles" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>