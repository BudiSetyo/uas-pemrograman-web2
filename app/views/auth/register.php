<?php $title = 'Register'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center py-5">
    <div class="col-md-5">
        <div class="card shadow-none">
            <div class="card-body">
                <h3 class="text-center mb-4" style="font-weight: 900;"><i class="fas fa-user-plus" style="color: var(--primary);"></i> Register</h3>

                <form action="/register" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?= error('username') ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars(old('username')) ?>" placeholder="Minimal 3 karakter">
                        <div class="invalid-feedback"><?= error('username') ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control <?= error('email') ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars(old('email')) ?>" placeholder="contoh@email.com">
                        <div class="invalid-feedback"><?= error('email') ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control <?= error('password') ? 'is-invalid' : '' ?>" placeholder="Minimal 6 karakter">
                        <div class="invalid-feedback"><?= error('password') ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="form-control <?= error('confirm_password') ? 'is-invalid' : '' ?>" placeholder="Ulangi password">
                        <div class="invalid-feedback"><?= error('confirm_password') ?></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-user-plus"></i> Daftar
                    </button>
                </form>

                <p class="text-center mt-3 mb-0" style="color: var(--body); font-size: 14px;">
                    Sudah punya akun? <a href="/login" style="color: var(--ink); font-weight: 600;">Login di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>