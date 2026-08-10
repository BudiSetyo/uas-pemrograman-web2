<?php

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('/login');
        }
        $this->userModel = new User();
    }

    public function index()
    {
        $user = $this->userModel->findById(auth()['id']);
        view('profile.index', ['user' => $user]);
        clearFlash();
    }

    public function update()
    {
        $userId = auth()['id'];
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $errors = [];

        // Username validation
        if (empty($username)) {
            $errors['username'] = 'Username harus diisi';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'Username minimal 3 karakter';
        } else {
            $existing = $this->userModel->findByUsername($username);
            if ($existing && $existing['id'] != $userId) {
                $errors['username'] = 'Username sudah digunakan';
            }
        }

        // Email validation
        if (empty($email)) {
            $errors['email'] = 'Email harus diisi';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tidak valid';
        } else {
            $existing = $this->userModel->findByEmail($email);
            if ($existing && $existing['id'] != $userId) {
                $errors['email'] = 'Email sudah digunakan';
            }
        }

        // Password validation
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $errors['password'] = 'Password minimal 6 karakter';
            } elseif ($password !== $confirmPassword) {
                $errors['confirm_password'] = 'Konfirmasi password tidak cocok';
            }
        }

        // Avatar upload
        $avatar = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['avatar']['type'];
            $fileSize = $_FILES['avatar']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                $errors['avatar'] = 'Tipe file harus JPG, PNG, GIF, atau WebP';
            } elseif ($fileSize > 2 * 1024 * 1024) {
                $errors['avatar'] = 'Ukuran file maksimal 2MB';
            } else {
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
                $destination = __DIR__ . '/../../public/uploads/avatars/' . $filename;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
                    $avatar = 'uploads/avatars/' . $filename;
                }
            }
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld(['username' => $username, 'email' => $email]);
            redirect('/profile');
        }

        $updateData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ];

        $this->userModel->updateProfile($userId, $updateData);

        if ($avatar) {
            $this->userModel->updateAvatar($userId, $avatar);
        }

        // Update session
        $_SESSION['user'] = $this->userModel->findById($userId);

        setSuccess('Profil berhasil diperbarui');
        redirect('/profile');
    }
}