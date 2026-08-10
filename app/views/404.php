<?php $title = 'Halaman Tidak Ditemukan'; require __DIR__ . '/layouts/header.php'; ?>

<div class="row justify-content-center py-5">
    <div class="col-md-6 text-center">
        <div class="display-1 mb-3" style="font-weight: 900; color: var(--mute);">404</div>
        <h2 class="mb-3" style="font-weight: 900;">Halaman Tidak Ditemukan</h2>
        <p style="color: var(--body); margin-bottom: var(--space-xl);">Halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <a href="/" class="btn btn-primary">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>
</div>

<?php require __DIR__ . '/layouts/footer.php'; ?>