# Verifikasi Google OAuth Setup

## ✅ Yang Sudah Benar

1. ✅ `.env` backend sudah benar:
   - `GOOGLE_REDIRECT_URI=https://portoconnect.cdrrr.my.id/auth/google/callback`
   - `FRONTEND_URL=https://porto-connect-mu.vercel.app`

2. ✅ Google Console sudah ada redirect URIs:
   - `http://localhost:8000/auth/google/callback` (development)
   - `https://portoconnect.cdrrr.my.id/auth/google/callback` (production)

---

## 🔧 Langkah Verifikasi & Fix

### Langkah 1: Clear Config Cache (PENTING!)

**Ini adalah langkah PALING PENTING!** Laravel menyimpan config di cache, jadi setelah update `.env` HARUS clear cache.

Di server Cloud Hebat, jalankan:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

**Jika tidak punya akses SSH:**
- Hubungi support Cloud Hebat untuk clear cache
- Atau gunakan fitur "Terminal" di DirectAdmin jika ada

---

### Langkah 2: Verifikasi Config Ter-Load dengan Benar

Buat file test untuk cek apakah config sudah benar:

**File:** `backend/public/test-google-config.php` (HAPUS SETELAH TEST!)

```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

echo "<h2>Google OAuth Config Test</h2>";
echo "<pre>";

echo "From env('GOOGLE_REDIRECT_URI'):\n";
echo env('GOOGLE_REDIRECT_URI') . "\n\n";

echo "From config('services.google.redirect'):\n";
echo config('services.google.redirect') . "\n\n";

echo "From config('services.google'):\n";
print_r(config('services.google'));

echo "</pre>";
```

Akses: `https://portoconnect.cdrrr.my.id/test-google-config.php`

**Harus menampilkan:**
```
From env('GOOGLE_REDIRECT_URI'):
https://portoconnect.cdrrr.my.id/auth/google/callback

From config('services.google.redirect'):
https://portoconnect.cdrrr.my.id/auth/google/callback
```

**⚠️ HAPUS FILE INI SETELAH TEST untuk security!**

---

### Langkah 3: Test Redirect URI yang Dikirim

Buka di browser:
```
https://portoconnect.cdrrr.my.id/auth/google
```

Setelah redirect ke Google, lihat URL di address bar. URL harus mengandung:
```
redirect_uri=https://portoconnect.cdrrr.my.id/auth/google/callback
```

Jika masih `http://localhost:8000/auth/google/callback`, berarti config cache belum ter-clear.

---

### Langkah 4: Cek Log Error Detail

Jika masih error, cek log Laravel untuk detail error:
```bash
tail -f storage/logs/laravel.log
```

Atau via Cloud Hebat dashboard jika ada menu "Logs".

---

## 🐛 Troubleshooting

### Masih Error redirect_uri_mismatch?

1. **Pastikan config cache sudah di-clear:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Restart aplikasi backend:**
   - Di Cloud Hebat, restart PHP-FPM atau aplikasi
   - Atau tunggu beberapa menit untuk auto-reload

3. **Cek apakah ada typo di `.env`:**
   - Pastikan tidak ada spasi di awal/akhir value
   - Pastikan tidak ada karakter aneh
   - Pastikan menggunakan `https://` (bukan `http://`)

4. **Cek Google Console:**
   - Pastikan redirect URI EXACTLY sama
   - Tidak boleh ada trailing slash
   - Harus pakai `https://` untuk production

5. **Test dengan file test-google-config.php:**
   - Pastikan config ter-load dengan benar
   - Jika masih menampilkan localhost, berarti cache belum ter-clear

---

## 📝 Checklist Final

- [ ] Config cache sudah di-clear (`php artisan config:clear`)
- [ ] Cache sudah di-clear (`php artisan cache:clear`)
- [ ] Aplikasi backend sudah di-restart
- [ ] Test config dengan `test-google-config.php` menampilkan URL production
- [ ] Test redirect di browser menunjukkan URL production
- [ ] Google Console redirect URI sudah benar
- [ ] Test login Google OAuth berhasil

---

## 💡 Tips

- **Selalu clear cache setelah update `.env`** - ini adalah penyebab 90% masalah!
- **Gunakan file test** untuk verifikasi config ter-load dengan benar
- **Cek log** untuk detail error jika masih ada masalah
- **Tunggu beberapa menit** setelah update Google Console untuk propagate

---

## 🆘 Masih Error?

Jika setelah semua langkah di atas masih error:

1. **Screenshot hasil `test-google-config.php`**
2. **Screenshot URL redirect** setelah klik "Login with Google"
3. **Screenshot error** dari Google
4. **Cek backend logs** untuk detail error

Kemungkinan masalah lain:
- PHP-FPM tidak reload setelah update config
- Multiple `.env` files (misal `.env.production`)
- Server cache layer (OPcache) belum di-clear

