# Tugas Case 3: Sistem Manajemen Barang

Aplikasi manajemen data barang berbasis Laravel untuk tugas mata kuliah **Dasar Komputer dan Pemrograman**. Pengguna dapat mendaftar, login, mengelola data barang (tambah, lihat, ubah, hapus ke sampah), memulihkan barang dari sampah, dan melihat statistik stok yang disimpan di cache.

**Nama:** [Nama Kamu]
**NIM:** [NIM Kamu]
**Live Demo:** [https://nama-project.onrender.com](https://nama-project.onrender.com)

## Daftar Isi

- Fitur
- Teknologi yang Digunakan
- Persyaratan
- Cara Menjalankan di Komputer Lokal (Laragon)
- Menjalankan Queue dan Schedule
- Mengakses dari Perangkat Lain
- Struktur File Penting
- Catatan untuk Live Demo

## Fitur

Setiap fitur wajib pada tugas dibuat langsung di dalam project ini, tanpa starter kit bawaan Laravel. Tabel berikut menunjukkan letak implementasinya.

| No| Persyaratan                         | Penjelasan | Lokasi Kode |
|---|-------------------------------------|------------|-------------|
| 1 | Login dan Register                  | Dibuat manual, password disimpan dengan hashing | `app/Http/Controllers/AuthController.php` |
| 2 | CRUD dengan soft delete             | Tambah, lihat, ubah, hapus ke sampah, pulihkan, dan hapus permanen | `app/Livewire/BarangIndex.php`, `app/Livewire/BarangSampah.php` |
| 3 | Migration dan Seeder                | Membuat tabel `barangs` dan mengisi data awal | `database/migrations/`, `database/seeders/BarangSeeder.php` |
| 4 | UI dengan Tailwind CSS dan Flux     | Tampilan dibangun dengan Tailwind CSS dan komponen Flux | `resources/css/app.css`, `resources/views/` |
| 5 | Akses dari perangkat lain           | Dapat dibuka dari HP dalam satu jaringan Wi-Fi | Lihat bagian "Mengakses dari Perangkat Lain" |
| 6 | Tampilan mobile-friendly            | Tata letak menyesuaikan layar HP dengan kelas responsif | `resources/views/` |
| 7 | Cache                               M| Statistik dashboard disimpan dengan `Cache::remember()` dan dihapus saat data berubah | `app/Livewire/BarangIndex.php` |
| 8 | Livewire                            | Seluruh CRUD berjalan tanpa memuat ulang halaman | `app/Livewire/`  |
| 9 | Alpine.js                           | Notifikasi yang hilang otomatis dengan `x-data` dan `x-show`        | `resources/views/livewire/barang-index.blade.php` |
| 10| Job, Queue, dan Schedule (opsional) | Job pencatat aktivitas, command pembersih sampah, dan jadwal harian | `app/Jobs/`, `app/Console/Commands/`, `routes/console.php` |

Selain fitur wajib di atas, aplikasi juga memiliki pencarian barang berdasarkan kode atau nama, halaman Sampah yang terpisah seperti folder sampah di galeri HP, dan notifikasi setiap kali ada aksi.

## Teknologi yang Digunakan

Aplikasi ini dibangun dengan **Laravel** sebagai framework PHP, **Livewire** untuk interaktivitas tanpa menulis JavaScript manual, **Flux** sebagai library komponen UI untuk Livewire, **Tailwind CSS** untuk gaya tampilan, dan **Alpine.js** yang sudah termasuk di dalam Livewire. Database yang digunakan adalah MySQL pada Laragon atau SQLite untuk pengembangan, dan PostgreSQL pada live demo.

## Persyaratan

Sebelum menjalankan project, pastikan komputer sudah memiliki PHP 8.3 atau lebih baru, Composer, Node.js beserta npm, dan database (MySQL dari Laragon, atau SQLite). Pengguna Windows dapat memakai Laragon yang sudah menyediakan PHP, Composer, MySQL, dan Node.js.

## Cara Menjalankan di Komputer Lokal (Laragon)

Langkah berikut dijalankan di terminal dari folder project.

**1. Clone repository**

```bash
git clone https://github.com/USERNAME/tugas-case3.git
cd tugas-case3
```

**2. Pasang dependensi PHP dan JavaScript**

```bash
composer install
npm install
```

**3. Buat file konfigurasi**

Salin `.env.example` menjadi `.env`, lalu buat kunci aplikasi:

```bash
copy .env.example .env
php artisan key:generate
```

**4. Atur koneksi database**

Buka file `.env`, lalu sesuaikan bagian database. Untuk MySQL di Laragon:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tugas_case3
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `tugas_case3` terlebih dahulu melalui menu Database di Laragon atau HeidiSQL.

**5. Buat tabel dan isi data awal**

```bash
php artisan migrate --seed
```

Perintah ini membuat seluruh tabel dan mengisi lima data barang awal.

**6. Bangun aset CSS dan JavaScript**

```bash
npm run build
```

Untuk pengembangan, kamu bisa memakai `npm run dev` agar perubahan langsung terlihat. Selama `npm run dev` berjalan, perangkat lain tidak dapat memuat tampilan dengan benar, sehingga untuk akses dari HP gunakan `npm run build`.

**7. Jalankan aplikasi**

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser, lalu daftar akun baru melalui halaman Register. Jika memakai Laragon dengan domain `.test`, alamat `http://tugas-case3.test` juga dapat digunakan.

## Menjalankan Queue dan Schedule

Fitur queue dan schedule bersifat opsional. Untuk mencobanya di komputer lokal, buka dua terminal tambahan. Terminal pertama menjalankan pekerja antrean:

```bash
php artisan queue:work
```

Terminal kedua menjalankan jadwal otomatis:

```bash
php artisan schedule:work
```

Saat barang dihapus, aktivitasnya dicatat oleh job di file `storage/logs/laravel.log`. Command pembersih sampah dapat dijalankan manual dengan perintah berikut, yang akan menghapus permanen barang yang sudah lebih dari 30 hari di sampah:

```bash
php artisan barang:bersihkan-sampah
```

Untuk menguji tanpa menunggu 30 hari, gunakan `--hari=0`.

## Mengakses dari Perangkat Lain

Aplikasi dapat dibuka dari HP atau laptop lain yang terhubung ke jaringan Wi-Fi yang sama. Pertama, cari alamat IPv4 laptop dengan perintah `ipconfig`. Kedua, jalankan build dan server dengan perintah berikut, lalu ganti `192.168.1.10` dengan IP milikmu:

```bash
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

Ketiga, buka `http://192.168.1.10:8000` di browser HP. Pastikan Windows Firewall mengizinkan akses pada jaringan private.

## Struktur File Penting

```text
tugas-case3/
├── app/
│   ├── Console/Commands/BersihkanSampahLama.php   # Command pembersih sampah
│   ├── Http/Controllers/AuthController.php        # Login, register, logout
│   ├── Jobs/CatatAktivitasBarang.php              # Job pencatat aktivitas
│   ├── Livewire/BarangIndex.php                   # CRUD dan statistik cache
│   ├── Livewire/BarangSampah.php                  # Halaman sampah
│   └── Models/Barang.php                          # Model dengan soft delete
├── database/
│   ├── migrations/                                # Tabel barangs
│   └── seeders/BarangSeeder.php                   # Data awal barang
├── resources/
│   ├── css/app.css                                # Tailwind dan Flux
│   └── views/                                     # Tampilan Blade dan Livewire
├── routes/
│   ├── web.php                                    # Rute aplikasi
│   └── console.php                                # Jadwal command
└── Dockerfile                                     # Konfigurasi live demo
```

## Catatan untuk Live Demo

Live demo berjalan di layanan hosting gratis Render dengan database PostgreSQL. Karena layanan gratis tidak menjalankan worker dan scheduler di belakang layar, konfigurasi demo memakai `QUEUE_CONNECTION=sync`, sehingga job langsung dikerjakan saat request berjalan. Layanan gratis juga akan tertidur saat lama tidak dipakai, sehingga halaman pertama kali dibuka bisa membutuhkan waktu beberapa puluh detik. Data di database demo dapat dihapus sewaktu-waktu sesuai kebijakan layanan hosting.

## Lisensi

Project ini dibuat untuk keperluan tugas kuliah.
