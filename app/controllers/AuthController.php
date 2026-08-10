<?php

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginForm()
    {
        if (isLoggedIn()) {
            redirect('/');
        }
        view('auth.login');
        clearFlash();
    }

    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $errors = [];

        if (empty($username)) {
            $errors['username'] = 'Username harus diisi';
        }
        if (empty($password)) {
            $errors['password'] = 'Password harus diisi';
        }

        if (empty($errors)) {
            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                setSuccess('Selamat datang, ' . $user['username'] . '!');
                redirect('/');
            } else {
                $errors['login'] = 'Username atau password salah';
            }
        }

        setErrors($errors);
        setOld(['username' => $username]);
        redirect('/login');
    }

    public function registerForm()
    {
        if (isLoggedIn()) {
            redirect('/');
        }
        view('auth.register');
        clearFlash();
    }

    public function register()
    {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $errors = [];

        if (empty($username)) {
            $errors['username'] = 'Username harus diisi';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'Username minimal 3 karakter';
        } elseif ($this->userModel->findByUsername($username)) {
            $errors['username'] = 'Username sudah digunakan';
        }

        if (empty($email)) {
            $errors['email'] = 'Email harus diisi';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tidak valid';
        } elseif ($this->userModel->findByEmail($email)) {
            $errors['email'] = 'Email sudah digunakan';
        }

        if (empty($password)) {
            $errors['password'] = 'Password harus diisi';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Password minimal 6 karakter';
        }

        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Konfirmasi password tidak cocok';
        }

        if (!empty($errors)) {
            setErrors($errors);
            setOld(['username' => $username, 'email' => $email]);
            redirect('/register');
        }

        $this->userModel->create([
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);

        setSuccess('Registrasi berhasil! Silakan login.');
        redirect('/login');
    }

    public function logout()
    {
        session_destroy();
        redirect('/');
    }
}