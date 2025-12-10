# Fix Google OAuth redirect_uri_mismatch Error

## 🔴 Masalah

Error: **`redirect_uri_mismatch`** - Redirect URI yang dikirim aplikasi tidak cocok dengan yang terdaftar di Google Cloud Console.

---

## ✅ Solusi Lengkap

### Langkah 1: Cek Redirect URI yang Digunakan Aplikasi

Aplikasi backend menggunakan redirect URI dari `.env`:
```env
GOOGLE_REDIRECT_URI=https://portoconnect.cdrrr.my.id/auth/google/callback
```

**Redirect URI yang dikirim ke Google:**
```
https://portoconnect.cdrrr.my.id/auth/google/callback
```

---

### Langkah 2: Update Google Cloud Console

1. **Login ke Google Cloud Console:**
   - Buka: https://console.cloud.google.com/
   - Pilih project Anda

2. **Navigasi ke Credentials:**
   - Sidebar → **APIs & Services** → **Credentials**
   - Atau langsung: https://console.cloud.google.com/apis/credentials

3. **Edit OAuth 2.0 Client ID:**
   - Cari OAuth client ID Anda (yang punya Client ID: `1071716458452-rpcdpb1jdbgqcf756u0c6142laut7f41`)
   - Klik untuk edit

4. **Tambahkan Authorized redirect URIs:**
   
   **WAJIB tambahkan semua URI berikut:**
   ```
   https://portoconnect.cdrrr.my.id/auth/google/callback
   ```
   
   **Opsional (untuk development):**
   ```
   http://localhost:8000/auth/google/callback
   ```

5. **Save perubahan**

---

### Langkah 3: Pastikan Backend .env Sudah Benar

Di Cloud Hebat, pastikan `.env` backend sudah:
```env
GOOGLE_CLIENT_ID=1071716458452-rpcdpb1jdbgqcf756u0c6142laut7f41
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=https://portoconnect.cdrrr.my.id/auth/google/callback
```

**⚠️ PENTING:**
- Pastikan URL menggunakan `https://` (bukan `http://`)
- Pastikan tidak ada spasi atau karakter aneh
- Pastikan path `/auth/google/callback` sesuai dengan route di `backend/routes/web.php`

---

### Langkah 4: Clear Config Cache Backend

Setelah update `.env`, **WAJIB** clear cache:

```bash
php artisan config:clear
php artisan cache:clear
```

Atau jika tidak punya akses SSH, hubungi support Cloud Hebat untuk clear cache.

---

### Langkah 5: Verifikasi Redirect URI

**Test manual di browser:**

1. Buka: `https://portoconnect.cdrrr.my.id/auth/google`
2. Lihat URL di address bar setelah redirect ke Google
3. URL harus mengandung redirect URI yang benar

**Atau cek di browser console:**
- Buka Network tab
- Lihat request ke `accounts.google.com`
- Cek parameter `redirect_uri` di URL

---

## 🐛 Troubleshooting

### Masih Error redirect_uri_mismatch?

1. **Pastikan redirect URI di Google Console EXACTLY sama:**
   - Harus: `https://portoconnect.cdrrr.my.id/auth/google/callback`
   - Tidak boleh ada trailing slash: `/auth/google/callback/` ❌
   - Tidak boleh ada spasi atau karakter aneh

2. **Pastikan menggunakan HTTPS:**
   - Production harus pakai `https://` (bukan `http://`)
   - Google tidak akan accept HTTP di production

3. **Cek apakah ada typo:**
   - Domain: `portoconnect.cdrrr.my.id` (bukan `portoconnect.cdrrr.my.id/` dengan trailing slash)
   - Path: `/auth/google/callback` (bukan `/auth/google/callbacks` atau typo lain)

4. **Tunggu beberapa menit:**
   - Perubahan di Google Console butuh waktu untuk propagate (biasanya 1-5 menit)

5. **Clear browser cache:**
   - Kadang browser cache redirect URI lama
   - Coba incognito/private window

6. **Cek config cache sudah di-clear:**
   ```bash
   php artisan config:clear
   ```

---

## 📝 Checklist

- [ ] Redirect URI sudah ditambahkan di Google Cloud Console
- [ ] Redirect URI EXACTLY sama dengan yang di `.env`
- [ ] Menggunakan `https://` (bukan `http://`)
- [ ] Tidak ada trailing slash atau karakter aneh
- [ ] Backend `.env` sudah benar (`GOOGLE_REDIRECT_URI`)
- [ ] Config cache sudah di-clear (`php artisan config:clear`)
- [ ] Sudah tunggu beberapa menit setelah update Google Console
- [ ] Test login Google OAuth berhasil

---

## 💡 Catatan Penting

1. **Google OAuth redirect URI harus EXACT match:**
   - Case sensitive
   - Harus sama persis (termasuk protocol, domain, path)
   - Tidak boleh ada trailing slash jika tidak ada di config

2. **Multiple redirect URIs:**
   - Bisa tambahkan beberapa redirect URI di Google Console
   - Satu untuk production, satu untuk development
   - Tapi pastikan semua sudah benar

3. **Development vs Production:**
   - Development: `http://localhost:8000/auth/google/callback`
   - Production: `https://portoconnect.cdrrr.my.id/auth/google/callback`
   - Pastikan sesuai dengan environment yang digunakan

---

## 🆘 Masih Error?

Jika setelah semua langkah di atas masih error:

1. **Screenshot error** dari Google
2. **Screenshot Authorized redirect URIs** di Google Console
3. **Screenshot `.env` backend** (tutup credentials sensitif)
4. **Cek backend logs** (`storage/logs/laravel.log`)

Kemungkinan masalah lain:
- Domain backend tidak accessible dari internet
- SSL certificate tidak valid
- Firewall memblokir request dari Google

