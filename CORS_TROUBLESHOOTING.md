# Troubleshooting CORS Error - Langkah Lengkap

## Masalah: CORS Error Masih Muncul Setelah Update Config

Jika setelah update `cors.php` dan `.env` masih muncul error CORS, ikuti langkah berikut:

---

## ✅ Langkah 1: Pastikan File Sudah Di-Update

Pastikan file `backend/config/cors.php` sudah di-deploy ke server Cloud Hebat dengan isi:

```php
'allowed_origins' => [
    env('FRONTEND_URL', 'http://localhost:5173'),
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'https://porto-connect-mu.vercel.app', // Production Vercel domain
    'https://porto-connect-git-main-lucinus-projects-268766af.vercel.app', // Preview deployment
],
```

---

## ✅ Langkah 2: Clear Cache Laravel (PENTING!)

**Ini adalah langkah yang PALING PENTING!** Laravel menyimpan config di cache, jadi setelah update config harus di-clear.

### Opsi A: Jika Punya Akses SSH ke Server

Login ke server Cloud Hebat via SSH, lalu jalankan:

```bash
cd /path/to/your/backend  # Ganti dengan path backend Anda
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Opsi B: Jika Tidak Punya Akses SSH

1. **Cek apakah Cloud Hebat punya fitur "Terminal" atau "SSH Console"** di dashboard
2. **Atau gunakan fitur "Run Command"** jika ada
3. **Atau hubungi support Cloud Hebat** untuk clear cache

### Opsi C: Via File Manager (Jika Support)

Jika Cloud Hebat punya file manager, buat file `clear-cache.php` di root backend:

```php
<?php
// clear-cache.php - Hapus file ini setelah digunakan!
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('config:clear');
$kernel->call('cache:clear');
$kernel->call('route:clear');
echo "Cache cleared!";
```

Akses via browser: `https://portoconnect.cdrrr.my.id/clear-cache.php`
**⚠️ HAPUS FILE INI SETELAH DIGUNAKAN untuk security!**

---

## ✅ Langkah 3: Restart Aplikasi Backend

Setelah clear cache, **restart aplikasi backend** di Cloud Hebat:
- Cari tombol "Restart" atau "Reboot" di dashboard
- Atau tunggu beberapa menit untuk auto-restart

---

## ✅ Langkah 4: Verifikasi Config Ter-Load

Buat file test sederhana untuk cek apakah config sudah benar:

**File:** `backend/public/test-cors.php` (HAPUS SETELAH TEST!)

```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$config = config('cors');
echo "<pre>";
echo "FRONTEND_URL from env: " . env('FRONTEND_URL') . "\n";
echo "Allowed Origins:\n";
print_r($config['allowed_origins']);
echo "\nAllowed Patterns:\n";
print_r($config['allowed_origins_patterns']);
echo "</pre>";
```

Akses: `https://portoconnect.cdrrr.my.id/test-cors.php`

Pastikan:
- `FRONTEND_URL` menampilkan `https://porto-connect-mu.vercel.app`
- `allowed_origins` berisi domain Vercel Anda
- `allowed_origins_patterns` berisi pattern Vercel

**⚠️ HAPUS FILE INI SETELAH TEST untuk security!**

---

## ✅ Langkah 5: Test CORS dari Browser

Buka browser console di frontend Vercel, lalu jalankan:

```javascript
fetch('https://portoconnect.cdrrr.my.id/api/portfolios/public?per_page=30', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
  },
  credentials: 'include'
})
.then(r => r.json())
.then(data => console.log('SUCCESS:', data))
.catch(err => console.error('ERROR:', err));
```

Jika berhasil, akan muncul data portofolio. Jika masih error CORS, lanjut ke langkah berikutnya.

---

## ✅ Langkah 6: Cek Response Headers

Buka Network tab di DevTools, klik request yang error, lalu cek **Response Headers**:
- Harus ada header `Access-Control-Allow-Origin: https://porto-connect-mu.vercel.app`
- Atau `Access-Control-Allow-Origin: *` (jika pattern match)

Jika header tidak ada, berarti:
1. Config belum ter-load (clear cache lagi)
2. Middleware CORS tidak jalan (cek `bootstrap/app.php`)

---

## ✅ Langkah 7: Tambahkan Domain Manual (Backup)

Jika masih tidak work, tambahkan semua domain Vercel Anda langsung ke `allowed_origins`:

```php
'allowed_origins' => [
    env('FRONTEND_URL', 'http://localhost:5173'),
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'https://porto-connect-mu.vercel.app',
    'https://porto-connect-git-main-lucinus-projects-268766af.vercel.app',
    'https://porto-connect-goj41942f-lucinus-projects-268766af.vercel.app',
    // Tambahkan semua domain Vercel yang muncul di dashboard
],
```

---

## ✅ Langkah 8: Cek .htaccess (Jika Pakai Apache)

Pastikan `.htaccess` tidak memblokir CORS headers. File `backend/public/.htaccess` seharusnya tidak ada yang memblokir.

---

## ✅ Langkah 9: Cek Log Error Laravel

Cek log Laravel untuk error terkait CORS:

```bash
tail -f storage/logs/laravel.log
```

Atau via Cloud Hebat dashboard jika ada menu "Logs".

---

## 🎯 Checklist Final

- [ ] File `cors.php` sudah di-update dan di-deploy
- [ ] File `.env` sudah di-update dengan `FRONTEND_URL`
- [ ] **Cache sudah di-clear** (`php artisan config:clear`)
- [ ] Aplikasi backend sudah di-restart
- [ ] Domain Vercel sudah ada di `allowed_origins`
- [ ] Pattern Vercel sudah ada di `allowed_origins_patterns`
- [ ] Test fetch dari browser console berhasil

---

## 💡 Tips

1. **Selalu clear cache setelah update config** - ini adalah penyebab 90% masalah CORS!
2. **Gunakan domain langsung di `allowed_origins`** sebagai backup selain pattern
3. **Test dengan browser console** untuk debug lebih mudah
4. **Cek response headers** untuk memastikan CORS header terkirim

---

## 🆘 Masih Error?

Jika setelah semua langkah di atas masih error:

1. **Screenshot error dari browser console** (Network tab + Console tab)
2. **Screenshot config cors.php** yang ada di server
3. **Screenshot .env** (tutup password/database credentials)
4. **Kirim ke support Cloud Hebat** atau buat issue baru

