# Setup Environment Variables Backend (Cloud Hebat)

## Variabel yang HARUS Diupdate untuk Production

Berdasarkan `.env` Anda saat ini, berikut yang **WAJIB** diubah:

### 1. **APP_ENV** (PENTING untuk Security)
```env
# SEBELUM (Development):
APP_ENV=local

# SESUDAH (Production):
APP_ENV=production
```

### 2. **APP_DEBUG** (PENTING untuk Security)
```env
# SEBELUM (Development):
APP_DEBUG=true

# SESUDAH (Production):
APP_DEBUG=false
```
**⚠️ PENTING:** Set ke `false` di production untuk menghindari expose error details ke public!

### 3. **FRONTEND_URL** (WAJIB untuk CORS)
```env
# SEBELUM (Development):
FRONTEND_URL=http://localhost:5173

# SESUDAH (Production):
FRONTEND_URL=https://porto-connect-mu.vercel.app
```
**Ganti dengan URL Vercel production Anda yang sebenarnya!**

### 4. **GOOGLE_REDIRECT_URI** (Untuk Google OAuth)
```env
# SEBELUM (Development):
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# SESUDAH (Production):
GOOGLE_REDIRECT_URI=https://portoconnect.cdrrr.my.id/auth/google/callback
```

### 5. **APP_URL** (Sudah Benar ✅)
```env
APP_URL=https://portoconnect.cdrrr.my.id
```
**Sudah benar, tidak perlu diubah!**

---

## Ringkasan Perubahan

Copy-paste bagian ini ke `.env` Anda di Cloud Hebat:

```env
# Environment
APP_ENV=production
APP_DEBUG=false

# URLs
APP_URL=https://portoconnect.cdrrr.my.id
FRONTEND_URL=https://porto-connect-mu.vercel.app

# Google OAuth
GOOGLE_REDIRECT_URI=https://portoconnect.cdrrr.my.id/auth/google/callback

# Database (tetap sama)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbbpsjgl_porto
DB_USERNAME=dbbpsjgl_porto
DB_PASSWORD=portoconnect

# ... (variabel lain tetap sama)
```

---

## Langkah-langkah Update di Cloud Hebat

1. **Login ke Cloud Hebat Dashboard**
2. **Cari menu "Environment Variables" atau "Config" atau ".env Editor"**
3. **Update variabel di atas** (APP_ENV, APP_DEBUG, FRONTEND_URL, GOOGLE_REDIRECT_URI)
4. **Save/Update** perubahan
5. **Restart/Rebuild** aplikasi backend (jika ada tombol restart)

---

## Setelah Update, Jalankan di Server:

Jika Anda punya akses SSH ke server Cloud Hebat, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

Jika tidak punya akses SSH, biasanya Cloud Hebat akan auto-reload setelah update `.env`.

---

## Verifikasi Setelah Update

1. **Cek CORS:** Buka browser console di frontend Vercel, seharusnya tidak ada error CORS lagi
2. **Test API:** Coba akses `/explore`, data portofolio harus muncul
3. **Test Login:** Coba login, harus berfungsi normal

---

## Catatan Penting

- **APP_DEBUG=false** di production untuk security (jangan expose error details)
- **FRONTEND_URL** harus sesuai dengan URL Vercel Anda yang sebenarnya
- **GOOGLE_REDIRECT_URI** harus menggunakan domain backend production (bukan localhost)
- Pastikan **file `backend/config/cors.php`** sudah diupdate dengan pattern Vercel (sudah dilakukan sebelumnya)

---

## Jika Masih Error CORS Setelah Update

1. Pastikan `FRONTEND_URL` sudah benar
2. Pastikan file `backend/config/cors.php` sudah di-deploy ke server
3. Clear cache: `php artisan config:clear`
4. Restart aplikasi backend

