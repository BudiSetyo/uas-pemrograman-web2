<?php $title = 'Login'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center py-5">
    <div class="col-md-5">
        <div class="card shadow-none">
            <div class="card-body">
                <h3 class="text-center mb-4" style="font-weight: 900;"><i class="fas fa-sign-in-alt" style="color: var(--primary);"></i> Login</h3>

                <form action="/login" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?= error('username') ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars(old('username')) ?>" placeholder="Masukkan username">
                        <div class="invalid-feedback"><?= error('username') ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control <?= error('password') ? 'is-invalid' : '' ?>" placeholder="Masukkan password">
                        <div class="invalid-feedback"><?= error('password') ?></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>

                <p class="text-center mt-3 mb-0" style="color: var(--body); font-size: 14px;">
                    Belum punya akun? <a href="/register" style="color: var(--ink); font-weight: 600;">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>