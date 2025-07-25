## Tentang Aplikasi

Aplikasi Perpustakaan adalah aplikasi web berbasis Laravel dengan tema AdminLTE untuk manajemen data pada sebuah perpustakaan. Aplikasi ini memungkinkan admin untuk mengelola data buku, data anggota, dan transaksi peminjaman.
### Beberapa Fitur yang tersedia:
- Manajemen data buku
    - Tambah, ubah, dan hapus data buku
- Manajemen data anggota
    - Tambah, ubah, dan hapus data anggota
- Manajemen transaksi peminjaman
    - Peminjaman dan pengembalian buku
- Autentikasi admin
    - Login dan logout

---

## Instalasi

1. **Clone repository**  
   ```bash
   git clone https://github.com/Yoga-Firmansyah/Library.git
   cd Library
   ```
2. **Install dependency PHP**  
   ```bash
   composer install
   ```
3. **Konfigurasi Environment**  
   Copy file .env dari .env.example
   ```bash
   cp .env.example .env
   ```
   Konfigurasi file `.env` sesuai konfigurasi lokal kamu:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=perpustakaan
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Generate key
   ```bash
   php artisan key:generate
   ```
4. **Migrasi & Seeder Database**  
   Jalankan migrasi database
   ```bash
   php artisan migrate
   ```
   *(Opsional)* Jalankan seeder:
   ```bash
   php artisan db:seed
   ```
5. **Jalankan Aplikasi**  
   ```bash
   php artisan serve
   ```
