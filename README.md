# CMS Mini - Portal Artikel

**Tugas UAS Pemrograman Web II** — Sistem manajemen konten (CMS) mini berbasis web untuk mengelola artikel, dibuat dengan PHP, MySQL (PDO), JavaScript, dan CSS.

## Fitur

| Fitur | Keterangan |
|-------|------------|
| **Register & Login** | Registrasi pengguna baru, login/logout dengan session |
| **Manajemen Profil** | Edit username, email, password, dan upload avatar (max 2MB) |
| **CRUD Artikel** | Buat, baca, edit, dan hapus artikel |
| **QuillJS Editor** | Rich text editor untuk konten artikel (bold, italic, list, blockquote, dll) |
| **Upload Cover** | Upload gambar sampul artikel (max 5MB) |
| **Pencarian** | Cari artikel berdasarkan judul atau konten |
| **Pagination** | 6 artikel per halaman |
| **Filter Kategori** | Filter artikel berdasarkan kategori (Teknologi, Olahraga, Kesehatan, dll) |
| **Validasi Form** | Validasi server-side untuk semua input |
| **MVC Pattern** | Router → Controller → Model → View |

## Teknologi

- **Backend:** PHP 8+ (native, tanpa framework)
- **Database:** MySQL/MariaDB dengan PDO (prepared statements)
- **Frontend:** Bootstrap 5, QuillJS 1.3.7, Font Awesome 6
- **CSS:** Desain sistem kustom dengan warna hijau *lime*
- **Arsitektur:** MVC sederhana dengan custom router

## Struktur Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AUTO_INCREMENT) | ID pengguna |
| username | VARCHAR(50) UNIQUE | Nama pengguna |
| email | VARCHAR(100) UNIQUE | Email |
| password | VARCHAR(255) | Hash password (bcrypt) |
| avatar | VARCHAR(255) | Path file avatar |
| created_at | TIMESTAMP | Waktu registrasi |

### Tabel `categories`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AUTO_INCREMENT) | ID kategori |
| name | VARCHAR(100) | Nama kategori |
| slug | VARCHAR(100) UNIQUE | Slug untuk URL |

### Tabel `articles`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | INT (PK, AUTO_INCREMENT) | ID artikel |
| user_id | INT (FK → users.id) | Penulis |
| category_id | INT (FK → categories.id) | Kategori |
| title | VARCHAR(200) | Judul |
| slug | VARCHAR(200) UNIQUE | Slug untuk URL |
| content | LONGTEXT | Konten (HTML dari QuillJS) |
| cover | VARCHAR(255) | Path file cover |
| excerpt | VARCHAR(500) | Cuplikan artikel |
| created_at | TIMESTAMP | Waktu publikasi |
| updated_at | TIMESTAMP | Waktu update |

## Struktur Direktori

```
cms-mini/
├── index.php                      # Entry point + Router
├── .htaccess                      # URL rewriting (Apache)
├── cms_mini.sql                   # Database schema + data awal
├── config/
│   └── database.php               # Koneksi PDO (singleton)
├── app/
│   ├── helpers.php                # Fungsi helper global
│   ├── controllers/
│   │   ├── HomeController.php     # Halaman beranda
│   │   ├── AuthController.php     # Register, login, logout
│   │   ├── ProfileController.php  # Profil & upload avatar
│   │   └── ArticleController.php  # CRUD artikel
│   ├── models/
│   │   ├── User.php               # Query users
│   │   ├── Category.php           # Query categories
│   │   └── Article.php            # Query articles (pagination, search)
│   └── views/
│       ├── layouts/
│       │   ├── header.php         # Navbar, alert flash messages
│       │   └── footer.php         # Footer, JS assets
│       ├── home.php               # Halaman beranda
│       ├── 404.php                # Halaman tidak ditemukan
│       ├── auth/
│       │   ├── login.php          # Form login
│       │   └── register.php       # Form register
│       ├── profile/
│       │   └── index.php          # Edit profil & avatar
│       └── articles/
│           ├── index.php          # Daftar artikel (search + pagination)
│           ├── create.php         # Form buat artikel (QuillJS)
│           ├── edit.php           # Form edit artikel (QuillJS)
│           └── show.php           # Detail artikel
└── public/
    ├── css/
    │   └── style.css              # Custom CSS (design system)
    ├── js/
    │   └── script.js              # JavaScript kustom
    └── uploads/
        ├── avatars/               # File avatar pengguna
        └── covers/                # File cover artikel
```

## Rute (Routes)

| Method | URL | Controller@Method | Keterangan |
|--------|-----|-------------------|------------|
| GET | `/` | HomeController@index | Beranda |
| GET | `/login` | AuthController@loginForm | Form login |
| POST | `/login` | AuthController@login | Proses login |
| GET | `/register` | AuthController@registerForm | Form register |
| POST | `/register` | AuthController@register | Proses register |
| GET | `/logout` | AuthController@logout | Logout |
| GET | `/profile` | ProfileController@index | Halaman profil |
| POST | `/profile` | ProfileController@update | Update profil |
| GET | `/articles` | ArticleController@index | Daftar artikel (search, page, category) |
| GET | `/articles/create` | ArticleController@create | Form buat artikel |
| POST | `/articles/create` | ArticleController@store | Simpan artikel baru |
| GET | `/articles/edit/{id}` | ArticleController@edit | Form edit artikel |
| POST | `/articles/edit/{id}` | ArticleController@update | Update artikel |
| GET | `/articles/delete/{id}` | ArticleController@delete | Hapus artikel |
| GET | `/articles/{slug}` | ArticleController@show | Detail artikel |

## Cara Instalasi

### 1. Clone atau salin proyek

```
git clone https://github.com/username/cms-mini.git
cd cms-mini
```

### 2. Buat database

```bash
mysql -u root -p < cms_mini.sql
```

Perintah di atas akan:
- Membuat database `cms_mini`
- Membuat tabel `users`, `categories`, `articles`
- Menyisipkan 6 kategori awal (Teknologi, Olahraga, Kesehatan, Pendidikan, Hiburan, Bisnis)

### 3. Konfigurasi database

Edit `config/database.php` dan sesuaikan kredensial MySQL:

```php
private $host = 'localhost';
private $dbname = 'cms_mini';
private $username = 'root';     // ganti sesuai MySQL Anda
private $password = '';         // ganti sesuai MySQL Anda
```

### 4. Jalankan server

**Opsi A — PHP Built-in Server (direkomendasikan):**

```bash
php -S localhost:8000
```

Lalu buka `http://localhost:8000` di browser.

> **Catatan:** Dengan PHP built-in server, `.htaccess` tidak diproses. Router tetap berfungsi karena `index.php` sebagai entry point. Jika menggunakan Apache, pastikan mod_rewrite aktif.

**Opsi B — Apache / XAMPP / Laragon:**

Letakkan folder `cms-mini` di `htdocs` (XAMPP) atau `www` (Laragon), lalu akses `http://localhost/cms-mini`.

### 5. Akses aplikasi

Buka browser dan kunjungi `http://localhost:8000` (sesuai port server).

1. Daftar akun baru melalui menu **Register**
2. Login dengan akun yang sudah dibuat
3. Mulai membuat artikel melalui menu **Buat Artikel**
4. Kelola profil melalui menu dropdown username → **Profil**

## Alur MVC

```
index.php (Front Controller)
    │
    ├── Router::dispatch()
    │       │
    │       ├── AuthController@login
    │       │       │
    │       │       ├── User::findByUsername()  ← Model
    │       │       ├── password_verify()       ← Validasi
    │       │       └── view('auth.login')      ← View
    │       │
    │       ├── ArticleController@index
    │       │       │
    │       │       ├── Article::all($page, $search, $category)  ← Model
    │       │       ├── Category::all()                          ← Model
    │       │       └── view('articles.index', $data)            ← View
    │       │
    │       └── ...
    │
    └── view() → app/views/...php
```

## Catatan Penting

- Semua password di-hash menggunakan `password_hash()` (bcrypt)
- Semua query menggunakan **prepared statements** (PDO) untuk keamanan SQL injection
- Upload file divalidasi tipe (JPG/PNG/GIF/WebP) dan ukuran (avatar max 2MB, cover max 5MB)
- Hanya pemilik artikel yang bisa mengedit/menghapus artikelnya
- Session flash messages digunakan untuk notifikasi sukses/error
- File helper (`app/helpers.php`) menyediakan fungsi global: `view()`, `redirect()`, `auth()`, `isLoggedIn()`, `generateSlug()`, `truncate()`, dll.