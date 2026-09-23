# 📚 Sistem Informasi Perpustakaan Sekolah (Laravel & Bootstrap 5)

Aplikasi Web **Sistem Informasi Perpustakaan Sekolah** berbasis **Laravel** dan **Bootstrap 5** yang dirancang sesuai standar Modul Pembelajaran Pemrograman Web Kelas XII.

---

## ✨ Fitur Utama
1. **Autentikasi Petugas:** Login & Logout khusus Petugas/Admin Perpustakaan dengan session middleware terproteksi.
2. **Dashboard Statistik:** Menampilkan total judul buku, total stok buku, total anggota terdaftar, jumlah transaksi aktif (sedang dipinjam), dan riwayat pengembalian.
3. **Manajemen Data Buku (CRUD):** Tambah, Edit, Hapus, validasi kode buku unik, penyesuaian stok, serta fitur pencarian/filter.
4. **Manajemen Data Anggota (CRUD):** Tambah, Edit, Hapus, validasi NIS unik, serta fitur pencarian nama/NIS/kelas.
5. **Sirkulasi Peminjaman Buku:** Form pencatatan peminjaman dengan dropdown otomatis (hanya menampilkan buku berstok > 0). **Stok buku otomatis berkurang 1**.
6. **Sirkulasi Pengembalian Buku:** Tombol aksi pengembalian yang mencatat tanggal kembali secara otomatis dan **menambah stok buku kembali (+1)**.
7. **UI Responsive & Modern:** Menggunakan Bootstrap 5 & Bootstrap Icons yang bersih, rapi, dan responsif di semua perangkat.

---

## 🛠️ Kebutuhan Sistem & Teknologi
- **PHP:** >= 8.2
- **Framework:** Laravel 11.x
- **Database:** MySQL / MariaDB
- **Web Server Local:** Laragon / XAMPP / Apache / Nginx
- **Front-End:** Bootstrap 5.3 + Bootstrap Icons
- **Database Manager:** HeidiSQL / phpMyAdmin

---

## 🚀 Panduan Instalasi & Menjalankan Project

### 1. Clone Repository
```bash
git clone https://github.com/username/Perpustakaan.git
cd Perpustakaan
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan koneksi database MySQL Laragon Anda:
```dotenv
APP_NAME="Perpustakaan Sekolah"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://perpustakaan.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perpus
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat Database di MySQL
Buka **HeidiSQL** atau **phpMyAdmin** (`http://localhost/phpmyadmin`), lalu buat database baru dengan nama:
```sql
CREATE DATABASE db_perpus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Seeder Data Awal
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Server Aplikasi
- **Jika Menggunakan Laragon Auto-Virtual Host:**
  Buka browser dan akses: `http://perpustakaan.test`
- **Atau Menggunakan PHP Built-in Server:**
  ```bash
  php artisan serve
  ```
  Buka browser di: `http://127.0.0.1:8000`

---

## 🔑 Akun Login Petugas / Admin (Demo)

| Role | Email | Password |
|---|---|---|
| **Admin / Petugas** | `admin@perpus.com` | `admin123` |

---

## 🗄️ Struktur Tabel Database (`db_perpus`)

```
buku
 ├── id (PK)
 ├── kode_buku (Unique, String 20)
 ├── judul (String 150)
 ├── pengarang (String 100)
 ├── penerbit (String 100)
 ├── tahun_terbit (Year)
 ├── stok (Integer)
 └── timestamps

anggota
 ├── id (PK)
 ├── nis (Unique, String 20)
 ├── nama (String 100)
 ├── kelas (String 20)
 └── timestamps

peminjaman
 ├── id (PK)
 ├── buku_id (FK -> buku.id)
 ├── anggota_id (FK -> anggota.id)
 ├── tanggal_pinjam (Date)
 ├── tanggal_kembali (Date, Nullable)
 ├── status (Enum: 'dipinjam', 'kembali')
 └── timestamps
```

---

## 🧪 Menjalankan Automated Testing

Untuk menguji seluruh alur autentikasi, CRUD, dan sirkulasi peminjaman/pengembalian:
```bash
php artisan test
```

---

## 📝 Catatan Tugas & Pengumpulan
Dibuat untuk memenuhi tugas praktik **Pemrograman Web dan Perangkat Bergerak (PWPB)** Kelas XII SMK.
