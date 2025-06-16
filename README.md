# IMK Project - Cahaya Hotel Pangururan 

## Teammate
Kelompok 6 
 - Rahmat Maulana Miftah         - 231402022
 - Jonathan Del Piero Manik      - 231402095
 - Nazwa Nabila                  - 231402098
 - Jonathan C. Amadeo Sembiring  - 231402111
 - Susi Pujiarti                 - 231402122

**Dosen Pengampu : Dedy Arisandi S.T., M.Kom.**

## Deskripsi Proyek
Aplikasi ini merupakan aplikasi reservasi online berbasis web yang dirancang untuk mendigitalisasi proses pemesanan di Penginapan Cahaya Pangururan.  Proyek ini dikembangkan sebagai solusi untuk menggantikan sistem manual yang rentan terhadap kesalahan, seperti pencatatan di buku tamu dan komunikasi via WhatsApp, dengan platform yang lebih efisien dan terstruktur.  

Aplikasi ini dibangun dengan menerapkan prinsip-prinsip Interaksi Manusia dan Komputer (IMK) dan pendekatan Design Thinking untuk memastikan pengalaman pengguna (UI/UX) yang ramah dan intuitif  

Proyek ini merupakan tugas besar untuk mata kuliah **Interaksi Manusia dan Komputer (IMK) di Program Studi Teknologi Informasi, Fakultas Ilmu Komputer dan Teknologi Informasi, Universitas Sumatera Utara.**

## Teknologi yang Digunakan
- PHP 8.x
- Laravel Framework
- MySQL Database
- Tailwind CSS
- Vite
- Node.js & NPM

## Persyaratan Sistem
- PHP >= 8.1
- Composer
- Node.js >= 16.x
- MySQL >= 5.7
- Web Server (Apache/Nginx)

## Instalasi

1. Clone repository ini
```bash
git clone https://github.com/JonathanChristian17/Human-Computer-Interaction-Project.git
cd Human-Computer-Interaction-Project
```

2. Install dependencies PHP menggunakan Composer
```bash
composer install
```

3. Install dependencies JavaScript menggunakan NPM
```bash
npm install
```

4. Salin file .env.example menjadi .env
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Konfigurasi database di file .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=penginapan_cahaya
DB_USERNAME=root
DB_PASSWORD=
```

7. Jalankan migrasi database
```bash
php artisan migrate
```

8. Jalankan seeder (jika ada)
```bash
php artisan db:seed
```

9. Jalankan tautan untuk direktori penyimpanan
```bash
php artisan storage:link
```

10. Compile assets
```bash
npm run dev
```

11. Jalankan server development
```bash
php artisan serve
```
