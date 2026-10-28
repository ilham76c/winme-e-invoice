# E-Invoice

Aplikasi E-Invoice dengan backend Laravel, frontend Vue.js + Vite, dan database PostgreSQL yang dijalankan menggunakan Docker Compose.

## Prasyarat

Pastikan perangkat sudah memasang:

- Docker Engine
- Docker Compose v2 (`docker compose`)

## Menjalankan aplikasi dari kondisi awal

> **Peringatan:** langkah ini menghapus volume database PostgreSQL milik proyek. Seluruh data database yang tersimpan di volume tersebut akan hilang. Jalankan hanya jika memang ingin memulai dari database kosong.

### 1. Masuk ke direktori proyek

Jalankan perintah dari direktori yang berisi file `compose.yaml`:

```bash
cd /path/ke/proyek
```

### 2. Hentikan proyek dan hapus data lama

```bash
docker compose down -v --rmi local --remove-orphans
```

Perintah ini menghentikan dan menghapus container proyek, menghapus volume yang dikelola Compose (termasuk volume PostgreSQL), menghapus image lokal yang dibangun oleh Compose, dan menghapus container yatim. Data dalam volume yang dihapus tidak dapat dipulihkan kecuali memiliki cadangan.

### 3. Build ulang image tanpa cache

```bash
docker compose build --no-cache
```

### 4. Jalankan seluruh service

```bash
docker compose up -d
```

Saat container backend mulai berjalan, perintah startup yang dikonfigurasi di `compose.yaml` akan menyiapkan `.env` jika belum tersedia, memasang dependensi Composer, membuat `APP_KEY` jika belum ada, membersihkan konfigurasi Laravel, menjalankan migrasi, menjalankan seeder, lalu menjalankan server Laravel. PostgreSQL akan membuat database berdasarkan variabel environment pada service `db`.

### 5. Periksa status container

```bash
docker compose ps
```

### 6. Periksa log bila diperlukan

Semua service:

```bash
docker compose logs -f
```

Backend Laravel:

```bash
docker compose logs -f backend
```

Frontend:

```bash
docker compose logs -f frontend
```

Database PostgreSQL:

```bash
docker compose logs -f db
```

Tekan `Ctrl+C` untuk berhenti mengikuti log; ini tidak menghentikan container.

## Alamat aplikasi

Setelah seluruh service berjalan, aplikasi dapat diakses melalui alamat berikut:

- **Frontend (Vue.js + Vite):** http://localhost:5173
- **Backend (Laravel API):** http://localhost:8000
- **PostgreSQL dari host:** `localhost:5433` (jika port mapping `5433:5432` masih digunakan)

Frontend menggunakan `/api` sebagai base URL dan proxy Vite meneruskan permintaan API ke service backend Docker. Di dalam jaringan Compose, target proxy adalah `http://backend:8000`.

Frontend menggunakan `/api` sebagai base URL dan proxy Vite meneruskan permintaan API ke service backend Docker. Di dalam jaringan Compose, target proxy adalah `http://backend:8000`.

## Memeriksa migrasi dan seeder

Lihat status migrasi:

```bash
docker compose exec backend php artisan migrate:status
```

Menjalankan seeder secara manual jika diperlukan:

```bash
docker compose exec backend php artisan db:seed --force
```

Seeder dijalankan saat startup backend sesuai konfigurasi Compose. Pastikan seeder aman dijalankan berulang kali agar tidak membuat data duplikat atau gagal karena unique constraint. Jika `DatabaseSeeder` membuat user dengan email tetap, pastikan seeder tersebut juga menangani kondisi saat user sudah ada.

## Menghentikan aplikasi

Menghentikan dan menghapus container, tetapi mempertahankan volume database:

```bash
docker compose down
```

Menjalankan kembali aplikasi:

```bash
docker compose up -d
```

## Catatan konfigurasi

- File `compose.yaml` mengatur service backend, frontend, database, port, environment, volume, dan perintah startup.
- `backend/Dockerfile` menyiapkan runtime PHP dan ekstensi yang diperlukan Laravel.
- `frontend/Dockerfile` menyiapkan runtime frontend.
- Jika file `backend/.env` sudah ada, perintah startup yang hanya menyalin `.env.example` ketika `.env` belum ada tidak akan menimpa file tersebut.
- Jangan menyimpan kredensial produksi di repository.
- Untuk konfigurasi yang berbeda dari default di atas, ikuti nilai yang tercantum di `compose.yaml` dan file `.env` proyek.

## Troubleshooting

### Backend gagal terhubung ke database

Periksa log:

```bash
docker compose logs --tail=100 backend db
```

Pastikan `DB_HOST` menggunakan nama service database (`db`) dan port di dalam jaringan Docker menggunakan `5432`, bukan port host `5433`.

### Perubahan konfigurasi tidak terbaca Laravel

```bash
docker compose exec backend php artisan config:clear
docker compose restart backend
```

### Ingin reset database sepenuhnya

Ulangi langkah reset pada bagian **Menjalankan aplikasi dari kondisi awal**. Ingat bahwa `docker compose down -v` menghapus volume database proyek.

---