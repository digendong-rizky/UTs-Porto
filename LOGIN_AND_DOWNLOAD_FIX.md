# Fix Login Redirect & Download PDF Issues

## ✅ Masalah yang Sudah Diperbaiki

### 1. Login Redirect ke Localhost
**File:** `frontend/src/pages/login.vue`
- ✅ Sudah diupdate untuk menggunakan `VITE_API_BASE_URL` dari environment variable
- ✅ Akan otomatis pakai URL backend production jika sudah di-set di Vercel

### 2. Download PDF Route
**File:** `backend/routes/api.php`
- ✅ Route public export sudah ada: `POST /api/portfolio/{publicLink}/export-pdf`
- ✅ Controller sudah implement: `PDFExportController@exportPublicPortfolio`

---

## 🔧 Yang Perlu Dilakukan

### 1. Update Google OAuth Console (PENTING!)

Google OAuth redirect URI harus sesuai dengan production:

1. **Login ke Google Cloud Console**: https://console.cloud.google.com/
2. **Pilih project Anda**
3. **Credentials** → Pilih OAuth 2.0 Client ID Anda
4. **Authorized redirect URIs** → Tambahkan:
   ```
   https://portoconnect.cdrrr.my.id/auth/google/callback
   ```
5. **Save** perubahan

**⚠️ PENTING:** Pastikan redirect URI di Google Console sama dengan `GOOGLE_REDIRECT_URI` di `.env` backend!

---

### 2. Set Environment Variable di Vercel (Frontend)

Pastikan di Vercel sudah set:
- **Name:** `VITE_API_BASE_URL`
- **Value:** `https://portoconnect.cdrrr.my.id`
- **Environment:** Production, Preview, Development

Setelah itu, **redeploy** frontend di Vercel.

---

### 3. Pastikan Backend .env Sudah Benar

Di Cloud Hebat, pastikan `.env` backend sudah:
```env
FRONTEND_URL=https://porto-connect-mu.vercel.app
GOOGLE_REDIRECT_URI=https://portoconnect.cdrrr.my.id/auth/google/callback
```

---

## 🧪 Testing

### Test Login Google OAuth:

1. **Buka frontend di Vercel**: `https://porto-connect-mu.vercel.app/login`
2. **Klik "Login with Google"**
3. **Setelah login**, harus redirect ke:
   - ✅ `https://porto-connect-mu.vercel.app/auth/callback?token=...`
   - ❌ BUKAN `http://localhost:5173/auth/callback`

### Test Download PDF:

1. **Buka portofolio public**: `https://porto-connect-mu.vercel.app/portfolio/{publicLink}`
2. **Klik tombol "Download PDF"**
3. **Harus download file PDF** tanpa error

---

## 🐛 Troubleshooting

### Login Masih Redirect ke Localhost?

1. **Cek Vercel Environment Variable:**
   - Pastikan `VITE_API_BASE_URL` sudah di-set
   - Pastikan sudah **redeploy** setelah set env var

2. **Cek Browser Console:**
   - Buka DevTools → Console
   - Ketik: `console.log(import.meta.env.VITE_API_BASE_URL)`
   - Harus menampilkan: `https://portoconnect.cdrrr.my.id`
   - Jika `undefined`, berarti env var belum ter-set atau belum redeploy

3. **Cek Backend .env:**
   - Pastikan `FRONTEND_URL` sudah benar
   - Clear cache: `php artisan config:clear`

### Download PDF Error?

1. **Cek Route:**
   - Pastikan route `POST /api/portfolio/{publicLink}/export-pdf` bisa diakses
   - Test dengan Postman/curl:
     ```bash
     curl -X POST https://portoconnect.cdrrr.my.id/api/portfolio/{publicLink}/export-pdf
     ```

2. **Cek Error di Browser Console:**
   - Buka Network tab
   - Coba download PDF
   - Lihat response error di request yang gagal

3. **Cek Backend Log:**
   - Cek `storage/logs/laravel.log` untuk error detail

4. **Cek Dependencies:**
   - Pastikan `barryvdh/laravel-dompdf` sudah terinstall
   - Pastikan extension PHP yang diperlukan sudah aktif

---

## 📝 Checklist

- [ ] Google OAuth redirect URI sudah diupdate di Google Console
- [ ] `VITE_API_BASE_URL` sudah di-set di Vercel
- [ ] Frontend sudah di-redeploy setelah set env var
- [ ] Backend `.env` sudah benar (`FRONTEND_URL`, `GOOGLE_REDIRECT_URI`)
- [ ] Backend cache sudah di-clear (`php artisan config:clear`)
- [ ] Test login Google OAuth berhasil
- [ ] Test download PDF berhasil

---

## 💡 Catatan

- **Google OAuth** memerlukan waktu beberapa menit untuk propagate perubahan redirect URI
- **Vercel** perlu redeploy setelah set environment variable baru
- **Backend** perlu clear cache setelah update `.env`

