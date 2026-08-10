<?php $title = 'Profil Saya'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center py-4">
    <div class="col-md-8">
        <div class="card shadow-none">
            <div class="card-header" style="background-color: var(--primary); color: var(--on-primary);">
                <h5 class="mb-0"><i class="fas fa-id-card"></i> Profil Saya</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <?php if ($user['avatar']): ?>
                    <img src="/public/<?= htmlspecialchars($user['avatar']) ?>" class="rounded-circle" width="120" height="120" style="object-fit: cover;" alt="Avatar">
                    <?php else: ?>
                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                        <i class="fas fa-user fa-4x" style="color: var(--mute);"></i>
                    </div>
                    <?php endif; ?>
                    <h4 class="mt-3" style="font-weight: 700;"><?= htmlspecialchars($user['username']) ?></h4>
                    <p style="color: var(--mute);"><?= htmlspecialchars($user['email']) ?></p>
                </div>

                <hr>

                <form action="/profile" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control <?= error('username') ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars(old('username') ?: $user['username']) ?>">
                            <div class="invalid-feedback"><?= error('username') ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control <?= error('email') ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars(old('email') ?: $user['email']) ?>">
                            <div class="invalid-feedback"><?= error('email') ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="form-control <?= error('password') ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback"><?= error('password') ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" class="form-control <?= error('confirm_password') ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback"><?= error('confirm_password') ?></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Profil</label>
                        <input type="file" name="avatar" class="form-control <?= error('avatar') ? 'is-invalid' : '' ?>" accept="image/*">
                        <small style="color: var(--mute);">Format: JPG, PNG, GIF. Maks: 2MB</small>
                        <div class="invalid-feedback"><?= error('avatar') ?></div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>