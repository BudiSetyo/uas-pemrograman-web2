<?php $title = 'Beranda'; require __DIR__ . '/layouts/header.php'; ?>

<div class="hero-band rounded-4 mb-5">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 fw-black" style="font-weight: 900;">Selamat Datang di CMS Mini</h1>
            <p class="fs-5 col-md-10" style="color: var(--body);">Platform sederhana untuk membaca dan menulis artikel. Bagikan pengetahuan Anda dengan dunia!</p>
            <div class="d-flex gap-3 mt-4">
                <a href="/articles" class="btn btn-primary">
                    <i class="fas fa-book-open"></i> Lihat Artikel
                </a>
                <?php if (!isLoggedIn()): ?>
                <a href="/register" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4 mt-4 mt-md-0">
            <div class="currency-converter">
                <h5 class="fw-bold mb-3" style="font-size: 18px;"><i class="fas fa-newspaper"></i> Jelajahi</h5>
                <p style="color: var(--body); font-size: 14px;">Temukan berbagai artikel menarik dari penulis berbakat.</p>
                <a href="/articles" class="btn btn-primary w-100">Mulai Membaca</a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <h3 class="mb-4" style="font-weight: 900; font-size: 32px;"><i class="fas fa-tags" style="color: var(--primary);"></i> Kategori</h3>
        <div class="d-flex flex-wrap gap-2">
            <?php foreach ($categories as $cat): ?>
            <a href="/articles?category=<?= $cat['id'] ?>" class="btn btn-outline-primary btn-sm">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<h3 class="mb-4" style="font-weight: 900; font-size: 32px;"><i class="fas fa-clock" style="color: var(--primary);"></i> Artikel Terbaru</h3>

<div class="row">
    <?php if (empty($articles)): ?>
    <div class="col-12">
        <div class="card-feature-sage text-center py-5">
            <i class="fas fa-newspaper fa-4x" style="color: var(--mute); margin-bottom: var(--space-lg); display: block;"></i>
            <p style="color: var(--body);">Belum ada artikel. Jadilah yang pertama menulis!</p>
            <?php if (isLoggedIn()): ?>
            <a href="/articles/create" class="btn btn-primary mt-3">Buat Artikel Pertama</a>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($articles as $article): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-none">
            <?php if ($article['cover']): ?>
            <img src="/public/<?= htmlspecialchars($article['cover']) ?>" class="card-img-top" alt="<?= htmlspecialchars($article['title']) ?>" style="height: 200px; object-fit: cover;">
            <?php else: ?>
            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 200px; border-radius: var(--rounded-xl) var(--rounded-xl) 0 0;">
                <i class="fas fa-image fa-3x" style="color: var(--mute);"></i>
            </div>
            <?php endif; ?>
            <div class="card-body">
                <span class="badge bg-primary mb-2"><?= htmlspecialchars($article['category_name']) ?></span>
                <h5 class="card-title" style="font-weight: 700;"><?= htmlspecialchars($article['title']) ?></h5>
                <p class="card-text" style="color: var(--body); font-size: 14px;"><?= htmlspecialchars($article['excerpt'] ?? truncate(strip_tags($article['content']), 150)) ?></p>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <small style="color: var(--mute); font-size: 12px;">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($article['username']) ?> |
                        <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($article['created_at'])) ?>
                    </small>
                    <a href="/articles/<?= htmlspecialchars($article['slug']) ?>" class="btn btn-sm btn-outline-primary">Baca</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (!empty($articles)): ?>
<div class="text-center mb-5">
    <a href="/articles" class="btn btn-primary">
        <i class="fas fa-list"></i> Lihat Semua Artikel
    </a>
</div>
<?php endif; ?>

<?php require __DIR__ . '/layouts/footer.php'; ?>