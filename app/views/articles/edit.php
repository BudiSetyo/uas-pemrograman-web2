<?php $title = 'Edit Artikel'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-none">
            <div class="card-header" style="background-color: var(--warning); color: var(--warning-content);">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Artikel</h5>
            </div>
            <div class="card-body">
                <form action="/articles/edit/<?= $article['id'] ?>" method="POST" enctype="multipart/form-data" id="articleForm">
                    <div class="mb-3">
                        <label class="form-label">Judul Artikel <span style="color: var(--negative);">*</span></label>
                        <input type="text" name="title" id="title" class="form-control <?= error('title') ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars(old('title') ?: $article['title']) ?>">
                        <div class="invalid-feedback"><?= error('title') ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori <span style="color: var(--negative);">*</span></label>
                        <select name="category_id" class="form-select <?= error('category_id') ? 'is-invalid' : '' ?>">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (old('category_id') ?: $article['category_id']) == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= error('category_id') ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konten <span style="color: var(--negative);">*</span></label>
                        <div id="editor" style="height: 400px;"><?= old('content') ?: $article['content'] ?></div>
                        <textarea name="content" id="content" hidden><?= htmlspecialchars(old('content') ?: $article['content']) ?></textarea>
                        <div class="invalid-feedback d-block"><?= error('content') ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cover / Gambar Sampul</label>
                        <?php if ($article['cover']): ?>
                        <div class="mb-2">
                            <img src="/public/<?= htmlspecialchars($article['cover']) ?>" style="height: 100px; object-fit: cover; border-radius: var(--rounded-md);" alt="Current cover">
                            <small class="d-block" style="color: var(--mute); font-size: 12px;">Cover saat ini. Upload file baru untuk mengganti.</small>
                        </div>
                        <?php endif; ?>
                        <input type="file" name="cover" class="form-control <?= error('cover') ? 'is-invalid' : '' ?>" accept="image/*">
                        <small style="color: var(--mute);">Format: JPG, PNG, GIF, WebP. Maks: 5MB</small>
                        <div class="invalid-feedback"><?= error('cover') ?></div>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Perbarui Artikel
                    </button>
                    <a href="/articles" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tulis konten artikel Anda di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['blockquote', 'code-block'],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    var form = document.getElementById('articleForm');
    form.onsubmit = function() {
        document.getElementById('content').value = quill.root.innerHTML;
        return true;
    };
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>