# PENGEMBANGAN SISTEM MANAJEMEN TRANSPORTASI UNTUK OPTIMALISASI RUTE DAN PERHITUNGAN TARIF TRUK DENGAN ALGORITMA DIJKSTRA PADA PT ‘X’
## USER MANUAL

**Disusun Oleh:**  
**Chandra Pratama Setya Putra**  
NRP: 160422108  

**PROGRAM STUDI TEKNIK INFORMATIKA**  
**PROGRAM INFORMATION MANAGEMENT & ENTERPRISE SYSTEM**  
**FAKULTAS TEKNIK UNIVERSITAS SURABAYA**  
**SURABAYA**  
**2026**  

---

## DAFTAR ISI

- [A. Kebutuhan Sistem](#a-kebutuhan-sistem)
  - [A.1 Kebutuhan Perangkat Keras (Hardware)](#a1-kebutuhan-perangkat-keras-hardware)
  - [A.2 Kebutuhan Perangkat Lunak (Software)](#a2-kebutuhan-perangkat-lunak-software)
- [B. Cara Instalasi](#b-cara-instalasi)
  - [B.1 Memasang Perangkat Pendukung](#b1-memasang-perangkat-pendukung)
  - [B.2 Menyiapkan Basis Data](#b2-menyiapkan-basis-data)
  - [B.3 Menyalin Source Code](#b3-menyalin-source-code)
  - [B.4 Konfigurasi Berkas .env](#b4-konfigurasi-berkas-env)
  - [B.5 Memasang Dependensi dan Membangun Aset](#b5-memasang-dependensi-dan-membangun-aset)
  - [B.6 Membuat Tabel dan Data Awal (Migrasi & Seeder)](#b6-membuat-tabel-dan-data-awal-migrasi--seeder)
  - [B.7 Menjalankan Aplikasi](#b7-menjalankan-aplikasi)
- [C. Cara Penggunaan](#c-cara-penggunaan)
  - [C.1 Mengakses Aplikasi dan Login](#c1-mengakses-aplikasi-dan-login)
  - [C.2 Dashboard Administrator](#c2-dashboard-administrator)
  - [C.3 Dashboard Operator](#c3-dashboard-operator)
  - [C.4 Dashboard Manager](#c4-dashboard-manager)
  - [C.5 Master Data Pelanggan (Customer)](#c5-master-data-pelanggan-customer)
  - [C.6 Master Data Barang (Katalog Muatan)](#c6-master-data-barang-katalog-muatan)
  - [C.7 Master Data Lokasi (Simpul / Nodes Distribusi)](#c7-master-data-lokasi-simpul--nodes-distribusi)
  - [C.8 Master Data Rute Antar Titik (Edges Graph & Jarak Km)](#c8-master-data-rute-antar-titik-edges-graph--jarak-km)
  - [C.9 Master Data Truk (Armada & Kapasitas Muat)](#c9-master-data-truk-armada--kapasitas-muat)
  - [C.10 Master Data Supir (Driver & Lisensi SIM)](#c10-master-data-supir-driver--lisensi-sim)
  - [C.11 Master Data Komponen Biaya Operasional](#c11-master-data-komponen-biaya-operasional)
  - [C.12 Pembuatan Order & Pengiriman Terintegrasi (Integrated Shipment Wizard)](#c12-pembuatan-order--pengiriman-terintegrasi-integrated-shipment-wizard)
  - [C.13 Visualisasi Algoritma Dijkstra & Perhitungan Estimasi Tiba (ETA)](#c13-visualisasi-algoritma-dijkstra--perhitungan-estimasi-tiba-eta)
  - [C.14 Manajemen Daftar Pengiriman & Pembaruan Status Real-Time](#c14-manajemen-daftar-pengiriman--pembaruan-status-real-time)
  - [C.15 Penerbitan & Cetak Surat Jalan Resmi (PDF)](#c15-penerbitan--cetak-surat-jalan-resmi-pdf)
  - [C.16 Monitoring Pengiriman & Pelacakan Armada Lintas Kota](#c16-monitoring-pengiriman--pelacakan-armada-lintas-kota)
  - [C.17 Visualisasi Peta Rute Interaktif (GIS Leaflet Map)](#c17-visualisasi-peta-rute-interaktif-gis-leaflet-map)
  - [C.18 Laporan Pemesanan (Order Report)](#c18-laporan-pemesanan-order-report)
  - [C.19 Laporan Pengiriman & Ketepatan Waktu (Shipment Report)](#c19-laporan-pengiriman--ketepatan-waktu-shipment-report)
  - [C.20 Laporan Biaya Operasional (Biaya Report)](#c20-laporan-biaya-operasional-biaya-report)
  - [C.21 Evaluasi Kinerja Supir (Driver Performance)](#c21-evaluasi-kinerja-supir-driver-performance)
  - [C.22 Utilisasi Armada Truk (Truck Usage)](#c22-utilisasi-armada-truk-truck-usage)
  - [C.23 Cetak Dokumen Laporan Manajemen Eksekutif (PDF)](#c23-cetak-dokumen-laporan-manajemen-eksekutif-pdf)

---

## A. KEBUTUHAN SISTEM

Logistik App adalah sistem manajemen transportasi dan distribusi pengiriman logistik berbasis web yang dibangun dengan framework **Laravel 12** dan basis data **MySQL/MariaDB**. Aplikasi ini dilengkapi dengan algoritma *Dijkstra Single-Source Shortest Path* untuk optimasi rute terpendek, visualisasi peta geografis interaktif (*GIS Leaflet OpenStreetMap*), modul kalkulasi tarif biaya operasional otomatis, serta pelaporan manajerial eksekutif format PDF.

Aplikasi dijalankan pada komputer server (atau web server lokal saat tahap pengembangan), kemudian diakses oleh pengguna melalui peramban web (*browser*) dari komputer desktop, laptop, maupun perangkat mobile. Oleh karena itu, kebutuhan sistem dibagi menjadi dua sisi: server dan pengguna.

### A.1 Kebutuhan Perangkat Keras (Hardware)

**Kebutuhan perangkat keras pada sisi server:**

| Komponen | Minimum | Direkomendasikan |
| :--- | :--- | :--- |
| **Prosesor** | Intel Core i3 / AMD setara (2 inti) | Intel Core i5 / AMD Ryzen 5 ke atas (4 inti / 8 thread) |
| **Memori (RAM)** | 4 GB | 8 GB atau lebih |
| **Penyimpanan** | 2 GB ruang kosong | 10 GB (SSD NVMe) untuk basis data & arsip surat jalan |
| **Jaringan** | Koneksi jaringan lokal (LAN) / Internet | Internet stabil &ge; 20 Mbps untuk tiles peta GIS Leaflet |

**Kebutuhan perangkat keras pada sisi pengguna:**

| Komponen | Keterangan |
| :--- | :--- |
| **Perangkat** | Komputer desktop, laptop, tablet, atau smartphone |
| **Memori (RAM)** | Minimum 2 GB (direkomendasikan 4 GB untuk rendering peta interaktif GIS) |
| **Layar** | Resolusi minimum 1024 &times; 768 piksel; tampilan otomatis responsif (*responsive design*) |
| **Jaringan** | Koneksi ke jaringan server lokal atau koneksi internet |

---

### A.2 Kebutuhan Perangkat Lunak (Software)

| Perangkat Lunak | Versi Acuan | Kegunaan |
| :--- | :--- | :--- |
| **Sistem Operasi** | Windows 10/11, Linux Ubuntu 22.04 LTS | Lingkungan untuk menjalankan server web dan database |
| **XAMPP** | v3.3.0 (PHP 8.2 & MariaDB 10.4) | Paket web server Apache dan basis data MySQL/MariaDB |
| **PHP** | PHP 8.2.12 (minimum PHP 8.2) | Menjalankan kode program backend Laravel 12 |
| **MySQL / MariaDB** | MariaDB 10.4.32 / MySQL 8.0 | Menyimpan master data, graf rute, pesanan, dan biaya |
| **Composer** | Composer 2.8+ / 2.9+ | Pustaka dependensi PHP (Laravel framework, DomPDF) |
| **Node.js & npm** | Node.js 20+ / 22+ & npm 10+ | Membangun aset tampilan frontend (CSS, JS via Vite) |
| **Laravel Framework** | Laravel 12.0+ | Kerangka kerja aplikasi MVC |
| **Peramban (Browser)** | Chrome, Edge, Firefox versi terbaru | Mengakses antarmuka sistem dari pengguna |

> **Catatan.** Basis data relasional yang digunakan bernama `tugasakhir` yang menyimpan tabel relasi simpul rute, alokasi armada truk, serta riwayat operasional pengiriman.

---

## B. CARA INSTALASI

### B.1 Memasang Perangkat Pendukung
1. Pasang **XAMPP** (sudah mencakup PHP 8.2 dan MariaDB) dari `apachefriends.org`.
2. Pasang **Composer** dari `getcomposer.org`, arahkan ke `C:\xampp\php\php.exe`.
3. Pasang **Node.js** versi LTS dari `nodejs.org` (npm ikut terpasang).
4. Verifikasi instalasi:
```bash
php -v
composer --version
node -v && npm -v
```
*(Gambar B.1 Pemeriksaan versi PHP, Composer, dan Node.js)*

### B.2 Menyiapkan Basis Data
1. Buka XAMPP Control Panel, klik **Start** pada modul **Apache** dan **MySQL**.
2. Buat basis data `tugasakhir` via phpMyAdmin (`http://localhost/phpmyadmin`) atau baris perintah:
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS tugasakhir;"
```
*(Gambar B.2 Pembuatan basis data tugasakhir)*

### B.3 Menyalin Source Code
Salin folder aplikasi ke direktori web server XAMPP (`C:\xampp\htdocs\logistikapp`), lalu buka terminal dan masuk ke folder tersebut:
```bash
cd C:\xampp\htdocs\logistikapp
```

### B.4 Konfigurasi Berkas .env
Salin berkas `.env.example` menjadi `.env`, lalu atur koneksi basis data:
```bash
copy .env.example .env
```

| Pengaturan | Nilai Acuan |
| :--- | :--- |
| `APP_NAME` | LogistikApp |
| `APP_URL` | `http://localhost/logistikapp/public` (atau `http://localhost:8000`) |
| `DB_CONNECTION` | mysql |
| `DB_HOST / DB_PORT`| 127.0.0.1 / 3306 |
| `DB_DATABASE` | tugasakhir |
| `DB_USERNAME` | root |
| `DB_PASSWORD` | *(kosongkan jika default XAMPP)* |

> **Perhatian.** Pastikan nilai `DB_DATABASE` telah sesuai dengan database yang dibuat di langkah B.2.

### B.5 Memasang Dependensi dan Membangun Aset
Jalankan instruksi berikut secara berurutan:
```bash
composer install
npm install
npm run build
php artisan key:generate
```
*(Gambar B.3 Pembangunan aset tampilan dan penyiapan kunci aplikasi)*

### B.6 Membuat Tabel dan Data Awal (Migrasi & Seeder)
Eksekusi migrasi skema tabel beserta seeder bawaan:
```bash
php artisan migrate --seed
php artisan migrate:status
```
*(Gambar B.4 Status migrasi tabel berhasil dijalankan)*

### B.7 Menjalankan Aplikasi
Aplikasi dapat diakses langsung via server Apache XAMPP:
`http://localhost/logistikapp/public/`

Atau melalui server lokal Laravel:
```bash
php artisan serve
```
*(Gambar B.5 Aplikasi berjalan pada alamat http://127.0.0.1:8000)*

---

## C. CARA PENGGUNAAN

### C.1 Mengakses Aplikasi dan Login
Akses URL aplikasi melalui peramban. Masukkan email dan kata sandi, lalu klik **Login**.

| Peran (Role) | Email Default | Password | Wewenang & Hak Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@gmail.com` | `ajeng` | Akses penuh seluruh Master Data (Pelanggan, Barang, Lokasi, Rute, Truk, Driver, Biaya). |
| **Operator** | `operator@gmail.com` | `sucipto` | Operasional: Wizard Pengiriman Terintegrasi, Eksekusi Dijkstra & ETA, Status Kirim, Cetak Surat Jalan PDF, Peta GIS. |
| **Manager** | `manager@gmail.com` | `narlin` | Evaluasi & Monitoring: Dashboard KPI, Laporan Order, Laporan Pengiriman, Biaya Riil, Kinerja Supir, Utilisasi Truk, Ekspor PDF. |

*(Gambar C.1 Halaman Login Sistem Terpadu)*

### C.2 Dashboard Administrator
Menyajikan statistik total data master: jumlah pelanggan aktif, katalog muatan barang, total armada truk, dan total supir aktif.  
*(Gambar C.2 Halaman Dashboard Administrator)*

### C.3 Dashboard Operator
Menampilkan rekapitulasi status pengiriman harian (*Menunggu*, *Dalam Perjalanan*, dan *Selesai*), ketersediaan armada truk gudang, serta tombol cepat pembuatan pengiriman baru (*Integrated Shipment*).  
*(Gambar C.3 Halaman Dashboard Operator)*

### C.4 Dashboard Manager
Menampilkan metriks KPI eksekutif: rasio ketepatan waktu pengiriman (*On-Time Delivery*), total pengeluaran biaya operasional riil (BBM + Tol + Uang Saku), serta grafik tren distribusi bulanan.  
*(Gambar C.4 Halaman Dashboard Manager)*

### C.5 Master Data Pelanggan (Customer)
Menu `/customers` mencatat data perusahaan pemesan (Nama Perusahaan, Alamat Fasilitas, Kontak Telepon, dan Email).  
*(Gambar C.5 Halaman Master Data Pelanggan)*

### C.6 Master Data Barang (Katalog Muatan)
Menu `/barang` mengelola katalog komoditas: kode barang, nama muatan, berat satuan (kg), volume kubikasi (m³), dan satuan kemasan (Sak, Box, Pallet, Drum). Digunakan sistem untuk validasi kapasitas angkut truk.  
*(Gambar C.6 Halaman Master Data Barang)*

### C.7 Master Data Lokasi (Simpul / Nodes Distribusi)
Menu `/locations` mengelola simpul geografis: Gudang Asal Surabaya, Hub Perantara, dan Kota Tujuan beserta koordinat GPS (Latitude dan Longitude) presisi.  
*(Gambar C.7 Halaman Master Titik Lokasi Simpul)*

### C.8 Master Data Rute Antar Titik (Edges Graph & Jarak Km)
Menu `/routes` menghubungkan dua simpul lokasi beserta jarak tempuh riil (km) yang menjadi acuan graf algoritma Dijkstra.  
*(Gambar C.8 Halaman Master Relasi Rute Antar Titik)*

### C.9 Master Data Truk (Armada & Kapasitas Muat)
Menu `/truck` mencatat nomor polisi kendaraan, tipe truk (CDD, Fuso, Tronton, Wingbox), ambang batas berat muatan (kg), kapasitas kubikasi bak, dan status ketersediaan armada.  
*(Gambar C.9 Halaman Master Data Truk)*

### C.10 Master Data Supir (Driver & Lisensi SIM)
Menu `/driver` mencatat nama lengkap pengemudi, kontak HP, lisensi SIM (B1/B2 Umum), masa berlaku, dan status penugasan supir.  
*(Gambar C.10 Halaman Master Data Supir)*

### C.11 Master Data Komponen Biaya Operasional
Menu `/komponen-biaya` mengatur standar biaya operasional: konsumsi BBM solar per km, tarif tol kota tujuan, uang jalan harian supir, dan upah buruh bongkar muat.  
*(Gambar C.11 Halaman Master Komponen Biaya)*

### C.12 Pembuatan Order & Pengiriman Terintegrasi (Integrated Shipment Wizard)
Menu `/shipments-integrated/create` menggabungkan 6 langkah pada satu halaman:
1. **Pilih Customer & Kota Tujuan** serta tanggal estimasi kirim, lalu klik **Cek Armada & Hitung ETA**.
2. **Sistem Mengeksekusi Algoritma Dijkstra:** Menyajikan jarak terpendek, simpul lintasan terbaik, visualisasi polyline pada peta Leaflet GIS, dan jam estimasi kedatangan (ETA).
3. **Pilih Truk & Driver yang Tersedia:** Sistem memfilter armada yang bebas tugas pada tanggal tersebut.
4. **Input Muatan Kargo:** Memasukkan item muatan; sistem memvalidasi batas kapasitas tonase.
5. **Kalkulasi Biaya Riil:** Sistem menghitung otomatis biaya BBM, tol, dan uang saku supir.
6. **Simpan Transaksi:** Menerbitkan nomor pesanan, nomor pengiriman, serta mengunci status truk dan supir menjadi *Digunakan*.  
*(Gambar C.12 Antarmuka Pembuatan Pengiriman Terintegrasi)*

### C.13 Visualisasi Algoritma Dijkstra & Perhitungan Estimasi Tiba (ETA)
- Formula Waktu: `Total Jam = (Jarak / 50 km/jam) + 3 Jam (Bongkar Muat)`
- Formula Hari: `Floor( Total Jam / 10 Jam Kerja Maksimal per Hari )`
- Menampilkan tabel relaksasi bobot jarak per iterasi langkah Dijkstra.  
*(Gambar C.13 Langkah Matematis Algoritma Dijkstra)*

### C.14 Manajemen Daftar Pengiriman & Pembaruan Status Real-Time
Menu `/shipments` menampilkan daftar nomor surat jalan dan status perjalanan (*Menunggu*, *Dalam Perjalanan*, *Selesai*, *Retur*, *Batal*). Saat status diubah menjadi *Selesai*, sistem secara otomatis melepas (*release*) truk dan driver kembali menjadi *Tersedia*.  
*(Gambar C.14 Daftar Pengiriman dan Pembaruan Status)*

### C.15 Penerbitan & Cetak Surat Jalan Resmi (PDF)
Menu `/shipment/{id}/surat-jalan` mencetak berkas PDF legal 4 rangkap yang dilengkapi kop surat, data penerima, nomor polisi armada, nama supir, rincian kargo, dan 4 kolom tanda tangan (Gudang, Supir, Penerima, Security).  
*(Gambar C.15 Pratinjau Dokumen Cetak Surat Jalan Resmi PDF)*

### C.16 Monitoring Pengiriman & Pelacakan Armada Lintas Kota
Menu `/monitoring-shipment` menyajikan radar pengawasan lokasi terakhir armada di jalan raya dan durasi perjalanan terhadap target ETA.  
*(Gambar C.16 Dasbor Monitoring Armada Real-Time)*

### C.17 Visualisasi Peta Rute Interaktif (GIS Leaflet Map)
Menu `/visualisasi-map` menampilkan peta geografis OpenStreetMap seluruh jaringan simpul kota dan rute lintasan jalan Jawa Timur secara interaktif.  
*(Gambar C.17 Visualisasi Peta GIS Jaringan Rute)*

### C.18 Laporan Pemesanan (Order Report)
Menu `/manager/order-report` menyajikan rekapitulasi transaksi order pemesanan kargo dengan filter rentang tanggal dan status penyelesaian.  
*(Gambar C.18 Laporan Pemesanan Kargo)*

### C.19 Laporan Pengiriman & Ketepatan Waktu (Shipment Report)
Menu `/manager/shipment-report` mengaudit selisih antara estimasi waktu tiba (ETA Dijkstra) dengan waktu kedatangan aktual di lokasi bongkar.  
*(Gambar C.19 Laporan Ketepatan Pengiriman)*

### C.20 Laporan Biaya Operasional (Biaya Report)
Menu `/manager/biaya-report` menyajikan audit finansial biaya operasional distribusi (BBM, Tol, Supir, Bongkar Muat) per pengiriman.  
*(Gambar C.20 Laporan Finansial Biaya Operasional)*

### C.21 Evaluasi Kinerja Supir (Driver Performance)
Menu `/manager/driver-performance` menampilkan metrik produktivitas supir (frekuensi trip yang diselesaikan dan total kilometer tempuh).  
*(Gambar C.21 Evaluasi Kinerja Supir)*

### C.22 Utilisasi Armada Truk (Truck Usage)
Menu `/manager/truck-usage` menyajikan rasio produktivitas aset armada kendaraan (hari operasi di jalan vs hari henti/bengkel).  
*(Gambar C.22 Analisis Tingkat Utilisasi Armada Truk)*

### C.23 Cetak Dokumen Laporan Manajemen Eksekutif (PDF)
Menu `/manager/management-report/pdf` menghasilkan berkas laporan manajemen komprehensif dalam format PDF siap cetak untuk bahan evaluasi jajaran pimpinan.  
*(Gambar C.23 Dokumen Cetak Laporan Manajemen Eksekutif PDF)*
