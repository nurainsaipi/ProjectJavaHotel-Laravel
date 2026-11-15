# Java Hotel — Laravel Version

Web aplikasi manajemen hotel heritage Yogyakarta yang dikonversi dari PHP murni ke Laravel.

---

## 🚀 Cara Setup (Langkah demi Langkah)

### 1. Buat Project Laravel Baru
```bash
composer create-project laravel/laravel java-hotel-laravel
cd java-hotel-laravel
```

### 2. Copy semua file dari ZIP ini
Salin semua folder dan file dari zip ini ke dalam folder Laravel yang baru dibuat:
- `app/` → timpa seluruh isi folder app
- `database/` → timpa seluruh isi folder database
- `resources/views/` → timpa seluruh isi folder views
- `routes/web.php` → timpa file routes
- `public/css/style.css` → copy ke public/css/

### 3. Daftarkan Middleware

Buka `bootstrap/app.php`, tambahkan alias middleware:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'auth.admin' => \App\Http\Middleware\AuthAdmin::class,
    ]);
})
```

### 4. Setup Database

Copy `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Edit `.env` sesuai konfigurasi database lokal kamu:
```
DB_DATABASE=java_hotel
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate App Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 7. Jalankan Server
```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 🔑 Akun Admin Default
- **Username:** admin
- **Password:** admin123

---

## 📁 Struktur File Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php      ← Home, Pricing, Gallery, Check Booking
│   │   ├── BookingController.php   ← Form reservasi tamu
│   │   ├── AdminController.php     ← Login, Reservasi, Status Kamar, Reports
│   │   └── RoomController.php      ← CRUD Kelola Kamar
│   └── Middleware/
│       └── AuthAdmin.php           ← Proteksi halaman admin
├── Models/
│   ├── Admin.php
│   ├── Customer.php
│   ├── Room.php
│   ├── Booking.php
│   └── Facility.php
database/
├── migrations/                     ← Struktur tabel
└── seeders/
    └── DatabaseSeeder.php          ← Data awal (admin + rooms + facilities)
resources/views/
├── layouts/app.blade.php           ← Layout utama (sidebar, footer, modal booking)
├── home.blade.php
├── pricing.blade.php
├── gallery.blade.php
├── check.blade.php
└── admin/
    ├── login.blade.php
    ├── reservations.blade.php
    ├── rooms.blade.php
    ├── reports.blade.php
    └── manage-rooms.blade.php
routes/web.php                      ← Semua routing
public/css/style.css                ← CSS original (sama persis)
```

---

## 🗺️ Mapping Route

| URL | Method | Fungsi |
|-----|--------|--------|
| `/` | GET | Home |
| `/pricing` | GET | Harga Kamar |
| `/gallery` | GET | 3D Gallery |
| `/check` | GET | Cek Booking via NIK |
| `/booking` | POST | Submit reservasi |
| `/login` | GET/POST | Login admin |
| `/logout` | POST | Logout |
| `/admin/reservations` | GET | Daftar reservasi |
| `/admin/pay` | POST | Konfirmasi pembayaran |
| `/admin/checkout` | POST | Checkout tamu |
| `/admin/rooms` | GET | Status kamar |
| `/admin/rooms/status` | POST | Update status kamar |
| `/admin/reports` | GET | Laporan keuangan |
| `/admin/manage-rooms` | GET/POST | Kelola kamar |
| `/admin/manage-rooms/{id}` | PUT | Edit kamar |
| `/admin/manage-rooms/{id}` | DELETE | Hapus kamar |
