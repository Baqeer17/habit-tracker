#  MahabBa - Modern Spiritual Habit Tracker (PWA)

[![MahabBa Live Demo](https://img.shields.io/badge/Live_Demo-View_App-brightgreen?style=for-the-badge)](https://mahabba-app.onrender.com)
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)

MahabBa adalah *Progressive Web App* (PWA) yang dirancang untuk membantu pengguna melacak rutinitas ibadah dan spiritual harian. Aplikasi ini memadukan arsitektur data relasional yang kokoh di sisi *backend* dengan pendekatan UI/UX interaktif di sisi *frontend*, menghilangkan pengalaman kaku dari aplikasi pelacak kebiasaan konvensional.

<div align="center">
  <img width="949" height="413" alt="Cuplikan layar 2026-10-01 194553" src="https://github.com/user-attachments/assets/20f68859-b5b4-458a-8892-087cb9282ba0" />
</div>

---

##  Filosofi Desain & Arsitektur

Proyek ini dibangun sebagai solusi teknis atas dua masalah utama dalam aplikasi manajemen kebiasaan: **kelelahan antarmuka (UI fatigue)** dan **inefisiensi pengambilan data historis**.

1. **Evolusi Antarmuka (Front-end):** Mengadopsi *Interactive Floating Cards* dengan *state management* warna untuk penggunaan harian guna memberikan *reward* psikologis (dopamin visual) saat tugas selesai, sekaligus menyediakan tampilan *Tabel Klasik* untuk pemindaian data riwayat mingguan secara komprehensif.
2. **Integritas Data (Back-end):** Menggunakan pangkalan data **PostgreSQL** dengan skema relasional yang dioptimalkan. Arsitektur ini memastikan bahwa pencatatan habit harian tidak menghasilkan anomali data (tumpang tindih) saat sistem melakukan rekapitulasi grafik mingguan/bulanan.

##  Tumpukan Teknologi (*Tech Stack*)

- **Lapis Antarmuka (Front-end):** Blade Templating, Tailwind CSS
- **Lapis Logika (Back-end):** Laravel (PHP)
- **Pangkalan Data (Database):** PostgreSQL
- **Infrastruktur:** Progressive Web App (PWA) Ready, di-*deploy* melalui Render.

---

##  Fitur Utama

-  **Autentikasi Aman:** Sistem pendaftaran, *login*, dan manajemen sesi terenkripsi.
-  **Spiritual Tracker Ganda:** *Toggle* cerdas antara mode harian (*fast-action floating cards*) dan mode riwayat (*data scanning table*).
-  **Kalkulator Zakat Otomatis:** Mesin penghitung zakat bawaan dengan logika nisab yang akurat.
-  **PWA Support:** Dapat diinstal di layar utama perangkat seluler (*Add to Home Screen*) layaknya *native app*.

---

##  Skema Pangkalan Data (Database Architecture)

Fokus utama aplikasi ini ada pada efisiensi relasi antar entitas. Berikut adalah gambaran relasi inti sistem:

*   **`users` table:** Mengelola kredensial dan sesi autentikasi.
*   **`habits` table:** Menyimpan master data jenis ibadah (Salat Wajib, Sunah, dll).
*   **`user_habit_logs` table:** Tabel transaksional utama (Pivot) yang merekam riwayat setiap *habit* pengguna berdasarkan *timestamp* harian, dirancang untuk kueri pelaporan yang cepat.

---

##  Panduan Instalasi (Local Development)

Untuk menjalankan MahabBa di lingkungan lokal (*localhost*), pastikan mesin Anda memiliki PHP, Composer, Node.js, dan PostgreSQL, lalu ikuti langkah berikut:

### 1. Kloning Repositori
```bash
git clone [https://github.com/](https://github.com/)[NAMA_GITHUB_KAMU]/mahabba.git
cd mahabba
```
2. Instalasi Dependensi
```Bash
composer install
npm install && npm run build
```
```3. Konfigurasi Environment
Duplikasi file .env.example dan ubah namanya menjadi .env.
```
```Bash
cp .env.example .env
```
Buka file .env dan atur koneksi database PostgreSQL Anda:

```Cuplikan kode
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mahabba_db
DB_USERNAME=postgres
DB_PASSWORD=[password_anda]
Generate application key:
```
```Bash
php artisan key:generate
```
4. Eksekusi Migrasi
Bangun arsitektur tabel database:

```Bash
php artisan migrate
```
5. Jalankan Server
```Bash
php artisan serve
```
Aplikasi kini dapat diakses melalui http://localhost:8000.

 Tentang Pengembang
1. Baqeer Andhika Maulana

Berfokus pada persimpangan antara Arsitektur Informasi, Manajemen Pangkalan Data, dan Rekayasa Perangkat Lunak Web.

 LinkedIn: [https://www.linkedin.com/in/baqeer-andhika-maulana-60b6a22a8]

 Email: [baqeer.andhika.m@gmail.com]

2. Marsela Haniyah

Berfokus pada pembuatan dan pengembangan UI&UX serta tata letak komponen website dan desain website

 LinkedIn:[https://www.linkedin.com/in/marsela-haniyah-11213434a]
 
 Email:[marselahaniyah2@gmail.com]
