# 🚗 Swift Ride — Rental Mobil Premium Minimalis

Swift Ride adalah platform website rental mobil premium yang dirancang dengan estetika modern, bersih, dan minimalis. Proyek ini dibangun menggunakan **PHP Native**, **MySQL**, dan **Tailwind CSS** untuk memberikan pengalaman pengguna yang cepat dan responsif.

---

## 🌟 Fitur Utama
- **Landing Page Modern:** Tampilan elegan dengan animasi *smooth*.
- **Katalog Armada:** Daftar mobil premium dengan filter pencarian dan kategori.
- **Admin Dashboard:** Kelola data armada (Tambah, Edit, Hapus) dengan mudah.
- **Inisialisasi Database Otomatis:** Database dan tabel akan terbuat sendiri saat aplikasi dijalankan pertama kali.
- **Sistem Keamanan:** Dilindungi dari serangan CSRF dan akses ilegal.

---

## 🛠️ Persyaratan Sistem
Sebelum memulai, pastikan komputer Anda sudah terinstall:
1.  **XAMPP** (Versi PHP 7.4 atau lebih baru). [Download XAMPP](https://www.apachefriends.org/download.html)
2.  **Git** (Opsional, untuk clone repository). [Download Git](https://git-scm.com/downloads)
3.  **Browser** (Chrome, Edge, atau Firefox).

---

## 🚀 Langkah Instalasi (Dari Nol)

Ikuti langkah-langkah di bawah ini secara berurutan:

### 1. Persiapan Folder
- Buka aplikasi **XAMPP Control Panel**.
- Jalankan (**Start**) modul **Apache** dan **MySQL**.
- Masuk ke folder instalasi XAMPP Anda (biasanya di `C:\xampp\htdocs`).

### 2. Clone atau Download Repository
Buka Terminal/Command Prompt di folder `htdocs` tersebut, lalu jalankan:
```bash
git clone https://github.com/Ghofurm/rental-mobil-swift-ride.git
```
*Atau, jika Anda tidak menggunakan Git, download file ZIP dari GitHub dan ekstrak ke dalam folder `C:\xampp\htdocs\rental-mobil-swift-ride`.*

### 3. Konfigurasi Environment (`.env`)
- Di dalam folder proyek, cari file bernama `.env.example`.
- Duplikat/Copy file tersebut dan ubah namanya menjadi hanya `.env`.
- Buka file `.env` dengan Notepad atau VS Code. Pastikan pengaturannya seperti ini (sesuaikan jika password MySQL Anda tidak kosong):
  ```env
  DB_HOST=127.0.0.1
  DB_NAME=swift_ride
  DB_USER=root
  DB_PASS=
  ```

### 4. Jalankan Aplikasi
- Buka browser Anda.
- Ketikkan alamat berikut di bar pencarian:
  ```text
  http://localhost/rental-mobil-swift-ride
  ```
- **Selesai!** Website akan muncul. 
- *Catatan: Saat pertama kali dibuka, sistem akan otomatis membuat database `swift_ride` dan tabel-tabel yang diperlukan.*

---

## 🔐 Akses Admin (Dashboard)
Untuk masuk ke panel pengelolaan mobil:
1.  Klik menu **Admin** di pojok kanan atas website atau buka `http://localhost/rental-mobil-swift-ride/login.php`.
2.  Gunakan kredensial default berikut:
    - **Email:** `admin@swiftride.id`
    - **Password:** `password123`
3.  Setelah masuk, Anda bisa menambah, mengedit, atau menghapus armada mobil yang muncul di halaman depan.

---

## 📁 Struktur Folder Utama
- `admin/`: Halaman pengelolaan data untuk administrator.
- `config/`: Berisi koneksi database dan inisialisasi otomatis.
- `includes/`: Potongan kode yang digunakan berulang (Header, Footer, Auth Check).
- `assets/`: File pendukung seperti CSS kustom dan JavaScript.
- `.env`: File rahasia untuk pengaturan database.

---

## ❓ Masalah Umum (FAQ)
- **Database tidak terhubung?** Pastikan modul MySQL di XAMPP sudah berwarna hijau (Running).
- **Halaman Error 404?** Pastikan nama folder di `htdocs` sama persis dengan yang ada di URL (cek huruf besar/kecil).
- **Gambar tidak muncul?** Pastikan Anda memiliki koneksi internet karena beberapa gambar menggunakan URL dari Unsplash.

---

## 👨‍💻 Kontribusi
Proyek ini bersifat open-source. Jika Anda ingin berkontribusi, silakan lakukan *fork* dan buat *pull request* dengan perubahan Anda.

---
*Dibuat dengan ❤️ untuk pengalaman berkendara yang lebih baik.*
