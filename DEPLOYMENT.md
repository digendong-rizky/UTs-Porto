# Deployment Guide - PortoConnect

## Masalah yang Sering Terjadi

### Error: `ERR_CONNECTION_REFUSED` atau `Network Error` setelah deploy

**Penyebab:** Frontend masih menggunakan `localhost:8000` sebagai API URL setelah deploy.

**Solusi:** Set environment variable `VITE_API_BASE_URL` di platform hosting Anda.

---

## Setup Environment Variable

### Untuk Vercel (Frontend)

1. Login ke Vercel dashboard
2. Pilih project Anda
3. Masuk ke **Settings** → **Environment Variables**
4. Tambahkan variable baru:
   - **Name:** `VITE_API_BASE_URL`
   - **Value:** URL backend API Anda (contoh: `https://api.yourdomain.com` atau `https://your-backend.herokuapp.com`)
   - **Environment:** Pilih semua (Production, Preview, Development)
5. **Redeploy** aplikasi setelah menambahkan environment variable

### Untuk Platform Lain (Netlify, Railway, dll)

Cara sama seperti Vercel:
- Cari menu **Environment Variables** atau **Config Vars**
- Tambahkan `VITE_API_BASE_URL` dengan value URL backend API Anda
- Redeploy aplikasi

---

## Konfigurasi Backend

### CORS Configuration

**File:** `backend/config/cors.php` sudah dikonfigurasi untuk:
- ✅ Mengizinkan semua subdomain Vercel (`.vercel.app`)
- ✅ Menggunakan pattern matching untuk preview deployments
- ✅ Menggunakan `FRONTEND_URL` dari environment variable

**Jika masih error CORS, pastikan:**

1. **Set environment variable di backend (Cloud Hebat):**
   - Variable: `FRONTEND_URL`
   - Value: URL frontend production Anda (contoh: `https://porto-connect-mu.vercel.app`)

2. **Atau tambahkan domain spesifik di `allowed_origins`:**
   ```php
   'allowed_origins' => [
       'https://porto-connect-mu.vercel.app',
       'https://your-custom-domain.com',
   ],
   ```

3. **Clear cache backend setelah update config:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Environment Variables Backend

Pastikan backend memiliki konfigurasi yang benar:
- `APP_URL` - URL aplikasi backend
- `FRONTEND_URL` - URL frontend (untuk CORS)
- Database credentials
- dll

---

## Testing Setelah Deploy

1. **Cek Environment Variable:**
   - Buka browser console di production
   - Ketik: `console.log(import.meta.env.VITE_API_BASE_URL)`
   - Harus menampilkan URL backend Anda, bukan `undefined`

2. **Test API Connection:**
   - Buka Network tab di DevTools
   - Coba akses halaman yang memanggil API (misal: `/explore`)
   - Request harus ke URL backend production, bukan `localhost:8000`

---

## Troubleshooting

### Masih error setelah set environment variable?

1. **Pastikan variable name benar:** Harus `VITE_API_BASE_URL` (dengan prefix `VITE_`)
2. **Redeploy:** Environment variable hanya aktif setelah redeploy
3. **Cek URL backend:** Pastikan backend API sudah online dan accessible
4. **Cek CORS:** Pastikan backend mengizinkan origin frontend Anda

### Development vs Production

- **Development:** Tidak perlu set `VITE_API_BASE_URL`, akan otomatis pakai `http://localhost:8000`
- **Production:** WAJIB set `VITE_API_BASE_URL` ke URL backend production

---

## Contoh URL Backend

- Heroku: `https://your-app.herokuapp.com`
- Railway: `https://your-app.railway.app`
- Cloud Hebat: `https://api.yourdomain.com` atau sesuai yang diberikan Cloud Hebat
- Custom domain: `https://api.yourdomain.com`

**PENTING:** Jangan sertakan trailing slash (`/`) di akhir URL!

