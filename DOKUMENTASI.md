# 📖 Dokumentasi Sistem Kedai Wasis

## 📑 Daftar Isi

- [Tentang Aplikasi](#tentang-aplikasi)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Role Pengguna](#role-pengguna)
- [Instalasi](#instalasi)
  - [Instalasi dengan Laragon (Rekomendasi)](#-1-instalasi-dengan-laragon-rekomendasi)
  - [Instalasi Manual (PHP CLI & Artisan Serve / XAMPP)](#%EF%B8%8F-2-instalasi-manual-php-cli--artisan-serve--xampp)
- [Konfigurasi](#konfigurasi)
- [Alur Penggunaan](#alur-penggunaan)
- [Troubleshooting](#troubleshooting)

---

## 🎯 Tentang Aplikasi

**Kedai Wasis** adalah sistem manajemen kedai/restoran berbasis web yang dibangun dengan Laravel 13. Sistem ini memudahkan pengelolaan menu, pesanan, pembayaran, dan laporan penjualan dengan interface yang modern dan responsif.

### Keunggulan:
- ✅ Multi-role system (Admin, Kasir, Customer)
- ✅ QR Code untuk pemesanan self-service
- ✅ Integrasi Midtrans untuk payment gateway
- ✅ Laporan penjualan dengan export PDF
- ✅ Manajemen meja dan kategori menu
- ✅ Real-time order tracking

---

## ✨ Fitur Utama

### 🔐 Admin
- Dashboard dengan statistik penjualan
- Kelola kategori dan menu makanan/minuman
- Kelola meja dan generate QR Code untuk tiap meja
- Monitoring semua transaksi/pesanan
- Laporan penjualan dengan filter tanggal dan export PDF
- Manajemen user (Admin dan Kasir)

### 🧑‍💼 Kasir
- Dashboard transaksi hari ini
- Lihat daftar menu (read-only)
- Buat pesanan manual untuk walk-in customer
- Monitoring status pesanan
- Proses pembayaran (tunai/online)

### 👤 Customer (Pelanggan)
- Scan QR Code di meja untuk pesan
- Lihat menu dengan gambar dan harga
- Tambah item ke keranjang
- Checkout dan pilih metode pembayaran
- Track status pesanan real-time
- Download invoice/struk

---

## 🛠 Teknologi yang Digunakan

### Backend
- **Laravel 13** - PHP Framework
- **PHP 8.3+** - Programming Language
- **SQLite** - Database (default)

### Frontend
- **Tailwind CSS 4** - Styling
- **Vite** - Build tool & hot reload
- **Blade Templates** - Laravel templating engine

### Libraries & Packages
- **Midtrans PHP SDK** - Payment gateway integration
- **DomPDF** - Generate PDF reports & invoices
- **Laravel Tinker** - REPL untuk debugging

---

## 👥 Role Pengguna

### 1️⃣ Admin
**Login Default:**
```
Email: admin@kedai.com
Password: password
```

**Akses:**
- Full access ke semua fitur
- Route prefix: `/admin`

**Menu:**
- Dashboard & Analytics
- Kategori Menu (CRUD)
- Menu Makanan/Minuman (CRUD)
- Meja & QR Code (CRUD + Download QR)
- Transaksi (View + Update Status)
- Laporan (Filter & Export PDF)
- User Management (CRUD)

### 2️⃣ Kasir
**Login Default:**
```
Email: kasir@kedai.com
Password: password
```

**Akses:**
- Limited access untuk operasional harian
- Route prefix: `/kasir`

**Menu:**
- Dashboard Transaksi
- Lihat Menu (read-only)
- Buat Pesanan Manual
- Monitor & Update Status Pesanan
- Proses Pembayaran

### 3️⃣ Customer (Public)
**Akses:**
- Tanpa login (public)
- Route: `/order/{qr_token}`

**Flow:**
1. Scan QR Code di meja
2. Pilih menu dan tambahkan ke keranjang
3. Checkout dan pilih payment method
4. Track status pesanan
5. Download invoice

---

## 📥 Instalasi

### Prasyarat Umum
Sebelum melakukan instalasi, pastikan sistem Anda telah terinstall:
- **PHP 8.3** atau versi yang lebih tinggi (beserta ekstensi `sqlite3`, `pdo_sqlite`, `mbstring`, `openssl`, `curl`)
- **Composer** (Dependency manager untuk PHP)
- **Node.js (v18+) & NPM** (untuk kompilasi assets frontend/Vite)
- **Git** (opsional, untuk clone repository)

---

## 🚀 1. Instalasi dengan Laragon (Rekomendasi)

Laragon adalah lingkungan pengembangan lokal yang sangat direkomendasikan karena otomatis menangani pembuatan Virtual Host (`kedai.test`) dan konfigurasi server.

### Langkah 1: Persiapan Laragon
1. Download dan install **Laragon Full** dari [laragon.org](https://laragon.org/download/).
2. Buka Laragon, pastikan versi PHP sudah **8.3+**:
   - Klik kanan pada window Laragon → **PHP** → **Version** → Pilih **8.3.x**.
3. Klik tombol **Start All** di Laragon.

### Langkah 2: Tempatkan Proyek di Folder `www`
Pindahkan atau clone folder proyek ke dalam direktori `C:\laragon\www\kedai`.

- **Menggunakan Git:**
  ```bash
  cd C:\laragon\www
  git clone https://github.com/username/kedai.git
  cd kedai
  ```
- **Menggunakan ZIP:**
  Extract file ZIP proyek ke `C:\laragon\www\kedai`.

### Langkah 3: Install PHP & Node Dependencies
Buka **Terminal Laragon** (Klik tombol **Terminal** di Laragon atau klik kanan → **Terminal**):

```bash
cd C:\laragon\www\kedai

# Install dependensi Laravel (PHP)
composer install

# Install dependensi Frontend (Node.js)
npm install
```

### Langkah 4: Setup Environment (.env)
```bash
# Salin file konfigurasi lingkungan
copy .env.example .env

# Generate Application Encryption Key
php artisan key:generate
```

### Langkah 5: Setup Database & Storage Link
Proyek ini secara *default* menggunakan database ringan **SQLite**.

```bash
# Buat file database SQLite (jika belum ada)
type nul > database\database.sqlite

# Jalankan migrasi tabel dan seeder data awal
php artisan migrate:fresh --seed

# Buat simbolik link untuk penyimpanan gambar/upload
php artisan storage:link
```

### Langkah 6: Build Assets Frontend
```bash
# Untuk mode produksi (rekomendasi)
npm run build

# Atau untuk mode pengembangan (hot reloading)
npm run dev
```

### Langkah 7: Akses Aplikasi via Laragon
Laragon secara otomatis akan membuatkan domain lokal Virtual Host:
- **URL Aplikasi / Virtual Host**: [http://kedai.test](http://kedai.test)
- **Halaman Admin**: [http://kedai.test/admin](http://kedai.test/admin)
- *(Opsional jika tidak memakai Virtual Host)*: Jalankan `php artisan serve` lalu buka [http://localhost:8000](http://localhost:8000)

---

## 🛠️ 2. Instalasi Manual (PHP CLI & Artisan Serve / XAMPP)

Panduan ini digunakan jika Anda tidak memakai Laragon (misalnya menggunakan Command Prompt/PowerShell biasa dengan PHP standalone atau XAMPP).

### Langkah 1: Tempatkan Proyek
Tempatkan folder proyek di direktori pilihan Anda (misalnya `C:\xampp\htdocs\kedai` atau `D:\projects\kedai`).

### Langkah 2: Install Dependencies via Terminal/CMD
Buka **Command Prompt (cmd)** atau **PowerShell** sebagai Administrator, navigasikan ke folder proyek:

```bash
cd C:\path\to\kedai

# 1. Install dependensi PHP
composer install

# 2. Install dependensi JavaScript/Tailwind
npm install
```

### Langkah 3: Setup Environment
```bash
# Copy file environment
copy .env.example .env

# Generate APP_KEY
php artisan key:generate
```

### Langkah 4: Setup Database SQLite & Storage Link
```bash
# Buat file kosong database.sqlite di folder database
type nul > database\database.sqlite

# Jalankan migrasi dan seeder
php artisan migrate:fresh --seed

# Hubungkan folder storage publik
php artisan storage:link
```

### Langkah 5: Build Assets Frontend
```bash
npm run build
```

### Langkah 6: Jalankan Server Lokal (Artisan Serve)
Jalankan server bawaan Laravel:

```bash
php artisan serve
```

Aplikasi akan berjalan di:
- **URL Utama**: [http://127.0.0.1:8000](http://127.0.0.1:8000) atau [http://localhost:8000](http://localhost:8000)
- **Halaman Admin**: [http://localhost:8000/admin](http://localhost:8000/admin)

---

### (Opsional) Konfigurasi Virtual Host di XAMPP
Jika Anda menggunakan XAMPP dan ingin mengakses via domain `http://kedai.test`:

1. Buka file `C:\xampp\apache\conf\extra\httpd-vhosts.conf` dan tambahkan:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/kedai/public"
       ServerName kedai.test
       <Directory "C:/xampp/htdocs/kedai/public">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
2. Buka **Notepad sebagai Administrator**, lalu buka file `C:\Windows\System32\drivers\etc\hosts` dan tambahkan baris berikut di bagian bawah:
   ```text
   127.0.0.1 kedai.test
   ```
3. Restart modul Apache di XAMPP Control Panel. Akses via [http://kedai.test](http://kedai.test).

---

## 📦 Instalasi dari GitHub

### Langkah 1: Clone Repository
```bash
# Clone project dari GitHub
git clone https://github.com/username/kedai.git
cd kedai
```

### Langkah 2: Install Dependencies
```bash
# Install PHP dependencies dengan Composer
composer install

# Install JavaScript dependencies dengan NPM
npm install
```

### Langkah 3: Setup Environment
```bash
# Copy environment file
cp .env.example .env  # Linux/Mac
copy .env.example .env  # Windows

# Generate application key
php artisan key:generate

# Create storage link (untuk upload gambar)
php artisan storage:link
```

### Langkah 4: Setup Database
```bash
# Jika menggunakan SQLite (default)
touch database/database.sqlite  # Linux/Mac
type nul > database\database.sqlite  # Windows

# Jalankan migration
php artisan migrate

# Seed data dummy
php artisan db:seed
```

### Langkah 5: Build Frontend Assets
```bash
# Build untuk production
npm run build

# Atau jalankan development server dengan hot reload
npm run dev
```

### Langkah 6: Jalankan Server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## ⚙️ Konfigurasi

### Environment Variables (`.env`)

#### Konfigurasi Dasar
```env
APP_NAME="Kedai Wasis"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

#### Database (SQLite - Default)
```env
DB_CONNECTION=sqlite
# Tidak perlu konfigurasi tambahan untuk SQLite
```

#### Database (MySQL - Opsional)
Jika ingin menggunakan MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kedai
DB_USERNAME=root
DB_PASSWORD=
```

#### Midtrans Payment Gateway
Daftar di [Midtrans](https://midtrans.com) dan dapatkan credentials:
```env
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Cara mendapatkan Midtrans Credentials:**
1. Daftar di https://midtrans.com
2. Login ke Dashboard
3. Pilih Environment: **Sandbox** (untuk testing)
4. Buka Settings → Access Keys
5. Copy **Server Key** dan **Client Key**

---

## 📋 Alur Penggunaan

### 🔐 Alur Login & Authentication

```
1. User mengakses /login
   ↓
2. Input email & password
   ↓
3. Sistem validasi credentials
   ↓
4. Redirect berdasarkan role:
   - Admin → /admin (Dashboard Admin)
   - Kasir → /kasir (Dashboard Kasir)
```

---

### 👨‍💼 Alur Admin

#### A. Kelola Menu & Kategori
```
1. Login sebagai Admin
   ↓
2. Sidebar → "Kategori" atau "Menu"
   ↓
3. Kelola (Tambah/Edit/Hapus):
   - Kategori: Makanan, Minuman, Snack, Dessert
   - Menu: Nama, Harga, Deskripsi, Gambar, Kategori
```

#### B. Kelola Meja & QR Code
```
1. Sidebar → "Meja"
   ↓
2. Tambah meja baru:
   - Kode Meja (MEJA-01, MEJA-02, dst)
   - Nama Meja
   - Kapasitas
   ↓
3. Sistem auto-generate QR Token unik
   ↓
4. Download QR Code sebagai PDF
   ↓
5. Cetak dan tempelkan di meja
```

#### C. Monitoring Transaksi
```
1. Sidebar → "Transaksi"
   ↓
2. Lihat semua pesanan:
   - Pending (baru masuk)
   - Cooking (sedang diproses)
   - Ready (siap disajikan)
   - Completed (selesai)
   - Cancelled (dibatalkan)
   ↓
3. Klik detail untuk update status atau lihat item pesanan
```

#### D. Laporan Penjualan
```
1. Sidebar → "Laporan"
   ↓
2. Filter berdasarkan:
   - Tanggal mulai & akhir
   - Status pembayaran (Lunas/Pending)
   ↓
3. Lihat summary:
   - Total transaksi
   - Total pendapatan
   - Rata-rata per transaksi
   ↓
4. Export ke PDF
```

#### E. Kelola User
```
1. Sidebar → "Pengguna"
   ↓
2. Tambah user baru (Admin atau Kasir)
   ↓
3. Edit/Hapus user existing
```

---

### 🧑‍💼 Alur Kasir

#### A. Dashboard
```
1. Login sebagai Kasir
   ↓
2. Lihat ringkasan hari ini:
   - Total transaksi
   - Total pendapatan
   - Pesanan aktif
```

#### B. Buat Pesanan Manual (Walk-in Customer)
```
1. Sidebar → "Pesanan" → "Buat Pesanan Baru"
   ↓
2. Pilih meja
   ↓
3. Tambahkan menu ke keranjang
   ↓
4. Input nama customer (opsional)
   ↓
5. Simpan pesanan
   ↓
6. Status: Pending
```

#### C. Monitoring & Update Status Pesanan
```
1. Sidebar → "Pesanan"
   ↓
2. Lihat daftar pesanan aktif
   ↓
3. Klik detail pesanan
   ↓
4. Update status:
   - Pending → Cooking (dapur mulai masak)
   - Cooking → Ready (makanan siap)
   - Ready → Completed (disajikan ke meja)
```

#### D. Proses Pembayaran
```
1. Sidebar → "Pesanan" → Detail pesanan
   ↓
2. Klik "Proses Pembayaran"
   ↓
3. Pilih metode:
   - Tunai: Langsung lunas
   - Online: Generate Snap Token Midtrans
   ↓
4. Status pembayaran: Paid
   ↓
5. Status pesanan: Completed
```

---

### 👤 Alur Customer (Pelanggan)

#### A. Scan QR Code & Akses Menu
```
1. Customer duduk di meja
   ↓
2. Scan QR Code dengan smartphone
   ↓
3. Browser terbuka otomatis ke halaman menu
   URL: /order/{qr_token}
   ↓
4. Lihat daftar menu berdasarkan kategori
```

#### B. Pesan Menu
```
1. Klik menu yang diinginkan
   ↓
2. Atur jumlah
   ↓
3. Tambahkan ke keranjang
   ↓
4. Ulangi untuk menu lain
   ↓
5. Klik "Lihat Keranjang"
```

#### C. Checkout & Pembayaran
```
1. Review pesanan di keranjang
   ↓
2. Input nama customer (opsional)
   ↓
3. Klik "Pesan Sekarang"
   ↓
4. Pilih metode pembayaran:
   
   🏪 TUNAI:
   - Pesanan dikirim ke dapur
   - Bayar langsung ke kasir nanti
   
   💳 ONLINE (Midtrans):
   - Sistem generate Snap Token
   - Pop-up Midtrans muncul
   - Pilih metode: Gopay, OVO, QRIS, VA Bank, dll
   - Selesaikan pembayaran
   - Status otomatis update jika sukses
```

#### D. Track Status Pesanan
```
1. Setelah checkout, redirect ke halaman status
   URL: /order/{qr_token}/status/{order_id}
   ↓
2. Lihat status real-time:
   - 🔵 Pending: Pesanan baru diterima
   - 🟡 Cooking: Sedang dimasak
   - 🟢 Ready: Siap disajikan
   - ✅ Completed: Selesai disajikan
   ↓
3. Klik "Download Invoice" untuk struk PDF
```

---

## 📊 Database Schema (Simplified)

### Users
```
- id
- name
- email
- password
- role (admin/kasir)
```

### Tables (Meja)
```
- id
- kode_meja (MEJA-01)
- nama_meja
- kapasitas
- qr_token (unique)
```

### Categories
```
- id
- nama
- urutan
```

### Menus
```
- id
- category_id
- nama
- deskripsi
- harga
- gambar (URL)
- status (tersedia/habis)
```

### Orders
```
- id
- table_id
- customer_name
- order_status (pending/cooking/ready/completed/cancelled)
- total_amount
- payment_method (cash/online)
- payment_status (pending/paid)
- snap_token (Midtrans)
```

### Order Items
```
- id
- order_id
- menu_id
- quantity
- price
- subtotal
```

### Payments
```
- id
- order_id
- payment_method
- amount
- status
- transaction_id
```

---

## 🔧 Troubleshooting

### ❌ Error: "Class not found" atau "Target class does not exist"
**Solusi:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### ❌ Error: "No application encryption key has been specified"
**Solusi:**
```bash
php artisan key:generate
```

### ❌ Error: "SQLSTATE[HY000]: General error: 1 no such table"
**Solusi:**
```bash
php artisan migrate:fresh --seed
```

### ❌ Error: "Vite manifest not found"
**Solusi:**
```bash
npm install
npm run build
```

### ❌ Permission Denied pada storage/logs
**Solusi (Linux/Mac):**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**Solusi (Windows):**
Pastikan folder `storage` dan `bootstrap/cache` tidak read-only.

### ❌ QR Code tidak bisa di-scan
**Solusi:**
1. Pastikan URL di `.env` sudah benar:
   ```env
   APP_URL=http://localhost:8000
   ```
2. Generate ulang QR Code dari menu Meja

### ❌ Midtrans payment tidak berfungsi
**Solusi:**
1. Cek credentials di `.env` sudah benar
2. Pastikan menggunakan **Sandbox** untuk testing
3. Clear config cache:
   ```bash
   php artisan config:clear
   ```

### ❌ CSS/Style tidak muncul
**Solusi:**
```bash
npm run build
php artisan config:clear
```

---

## 📞 Support & Kontak

Untuk pertanyaan atau bantuan lebih lanjut, silakan hubungi:
- **Email**: support@kedaiwasis.com
- **GitHub Issues**: [Link Repository]/issues

---

## 📝 Catatan Penting

### Security
- ⚠️ **Jangan gunakan password default di production!**
- ⚠️ Ubah `APP_DEBUG=false` di production
- ⚠️ Set `APP_ENV=production` di production
- ⚠️ Gunakan HTTPS di production
- ⚠️ Backup database secara berkala

### Development
- Gunakan `npm run dev` untuk hot reload saat development
- Gunakan `npm run build` untuk production build
- Gunakan `php artisan serve` untuk local development
- Gunakan proper web server (Nginx/Apache) di production

### Testing
- Test pembayaran Midtrans di Sandbox dulu
- Verifikasi semua flow sebelum deploy ke production
- Test QR Code scanner dari berbagai device

---

## 🎓 Fitur Mendatang (Roadmap)

- [ ] Multi-cabang support
- [ ] Inventory management
- [ ] Employee attendance
- [ ] Customer loyalty program
- [ ] WhatsApp notification
- [ ] Kitchen display system
- [ ] Mobile app (Android/iOS)

---

## 📄 Lisensi

Project ini menggunakan lisensi **MIT License**.

---

## 👨‍💻 Developer

Developed with ❤️ using Laravel & Tailwind CSS

---

**Selamat menggunakan Kedai Wasis! 🎉**

Jika dokumentasi ini membantu, jangan lupa beri ⭐ di GitHub!
