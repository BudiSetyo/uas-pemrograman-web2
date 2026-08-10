<?php $title = 'Artikel'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0" style="font-weight: 900; font-size: 32px;"><i class="fas fa-list"></i> Semua Artikel</h3>
    <?php if (isLoggedIn()): ?>
    <a href="/articles/create" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Buat Artikel
    </a>
    <?php endif; ?>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <form action="/articles" method="GET" class="input-group">
            <?php if ($categoryId): ?>
            <input type="hidden" name="category" value="<?= $categoryId ?>">
            <?php endif; ?>
            <input type="text" name="search" class="form-control" placeholder="Cari artikel..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Cari
            </button>
            <?php if ($search || $categoryId): ?>
            <a href="/articles" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i> Reset
            </a>
            <?php endif; ?>
        </form>
    </div>
    <div class="col-md-4">
        <div class="dropdown">
            <button class="btn btn-outline-primary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter"></i>
                <?php
                if ($categoryId) {
                    foreach ($categories as $cat) {
                        if ($cat['id'] == $categoryId) {
                            echo htmlspecialchars($cat['name']);
                            break;
                        }
                    }
                } else {
                    echo 'Semua Kategori';
                }
                ?>
            </button>
            <ul class="dropdown-menu w-100">
                <li><a class="dropdown-item <?= !$categoryId ? 'active' : '' ?>" href="/articles<?= $search ? '?search=' . urlencode($search) : '' ?>">Semua Kategori</a></li>
                <?php foreach ($categories as $cat): ?>
                <li>
                    <a class="dropdown-item <?= $categoryId == $cat['id'] ? 'active' : '' ?>"
                       href="/articles?<?= $search ? 'search=' . urlencode($search) . '&' : '' ?>category=<?= $cat['id'] ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<?php if ($search): ?>
<div class="mb-3">
    <em style="color: var(--mute);">Menampilkan hasil pencarian untuk: <strong>"<?= htmlspecialchars($search) ?>"</strong></em>
</div>
<?php endif; ?>

<div class="row">
    <?php if (empty($articles)): ?>
    <div class="col-12">
        <div class="card-feature-sage text-center py-5">
            <i class="fas fa-newspaper fa-4x" style="color: var(--mute); margin-bottom: var(--space-lg); display: block;"></i>
            <p style="color: var(--body);">Tidak ada artikel ditemukan.</p>
            <?php if (isLoggedIn()): ?>
            <a href="/articles/create" class="btn btn-primary">Buat Artikel Baru</a>
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

    <?php if ($totalPages > 1): ?>
    <div class="col-12">
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $currentPage - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $categoryId ? '&category=' . $categoryId : '' ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $categoryId ? '&category=' . $categoryId : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $currentPage + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $categoryId ? '&category=' . $categoryId : '' ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <p class="text-center" style="color: var(--mute); font-size: 14px;">Halaman <?= $currentPage ?> dari <?= $totalPages ?> (<?= $total ?> artikel)</p>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>