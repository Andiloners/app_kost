# Aplikasi Manajemen Kost

**Pemilik:** Andi

Aplikasi web untuk mengelola kost dengan fitur manajemen penghuni, kamar, tagihan, dan pembayaran.

## 📋 Informasi Pemilik & Akses

### 👤 **Pemilik Aplikasi**
**Nama:** Andi  
**Role:** Pemilik  
**Username:** `andi`  
**Password:** `andi123`

### 🔐 **Login Aplikasi**

#### **Admin:**
- **Username:** `admin`
- **Password:** `admin123`

#### **Pemilik (Zaky Mubarok):**
- **Username:** `zaky`
- **Password:** `zaky123`

### 🎯 **Fitur Akses Pemilik**
Sebagai pemilik aplikasi, Zaky Mubarok memiliki akses penuh ke:

#### **1. Dashboard**
- Melihat statistik lengkap kost
- Monitoring penghuni dan kamar
- Tracking tagihan dan pembayaran

#### **2. Manajemen Penghuni**
- Menambah penghuni baru
- Mengedit data penghuni
- Menghapus data penghuni
- Melihat riwayat penghuni

#### **3. Manajemen Kamar**
- Menambah kamar baru
- Mengatur harga sewa
- Monitoring status kamar
- Tracking penghuni per kamar

#### **4. Manajemen Tagihan**
- Generate tagihan otomatis
- Monitoring pembayaran
- Laporan tagihan
- Riwayat pembayaran

#### **5. Manajemen Barang**
- Inventaris barang
- Tracking barang per kamar
- Laporan inventaris

#### **6. Laporan dan Analisis**
- Laporan penghuni
- Laporan keuangan
- Analisis pendapatan
- Statistik kost

## 🚀 Fitur Utama

### 1. Manajemen Penghuni
- Tambah, edit, dan hapus data penghuni
- Pencarian penghuni
- Status penghuni (aktif/keluar)
- Informasi lengkap penghuni (nama, KTP, HP, alamat)

### 2. Manajemen Kamar
- Tambah, edit, dan hapus data kamar
- Status kamar (kosong/terisi)
- Informasi harga sewa
- Tracking penghuni yang menempati

### 3. Manajemen Tagihan
- Generate tagihan otomatis
- Status pembayaran (lunas/belum bayar/cicil)
- Jatuh tempo pembayaran
- Riwayat pembayaran

### 4. Manajemen Barang
- Inventaris barang per kamar
- Tracking barang yang ada di setiap kamar

### 5. Dashboard Admin
- Statistik penghuni, kamar, dan tagihan
- Kamar tersedia
- Tagihan jatuh tempo
- Penghuni terbaru

## 📦 Instalasi

### 1. Persyaratan Sistem
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)
- XAMPP (direkomendasikan)

### 2. Langkah Instalasi

1. **Clone atau download project**
   ```bash
   git clone [repository-url]
   cd app_kost
   ```

2. **Setup Database**
   - Buka browser dan akses: `http://localhost/app_kost/setup_database.php`
   - Atau import file `data/database_kost.sql`

3. **Konfigurasi Database**
   - Edit file `config/config.php`
   - Sesuaikan konfigurasi database:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'db_kost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

4. **Akses Aplikasi**
   - Buka browser
   - Akses: `http://localhost/app_kost/public/`

## 🗄️ Setup Database

### ⚠️ **PENTING: Nama Database Konsisten**

Aplikasi kost ini menggunakan **nama database yang konsisten** di semua file:

#### 🎯 **Nama Database: `db_kost`**

### **Cara Import Database yang Benar:**

#### **Opsi 1: Menggunakan phpMyAdmin (Direkomendasikan)**

1. **Buka phpMyAdmin**
   - Akses: `http://localhost/phpmyadmin`

2. **Buat Database Baru**
   - Klik "New" atau "Baru"
   - Masukkan nama database: **`db_kost`** (harus persis)
   - Klik "Create" atau "Buat"

3. **Import File SQL**
   - Pilih database `db_kost`
   - Klik tab "Import" atau "Impor"
   - Klik "Choose File" atau "Pilih File"
   - Pilih file: `data/database_kost.sql`
   - Klik "Go" atau "Jalankan"

4. **Selesai!**
   - Database sudah siap digunakan
   - Login dengan user default

#### **Opsi 2: Menggunakan Command Line**

```bash
# Masuk ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE db_kost;

# Import file SQL
source /path/to/app_kost/data/database_kost.sql;
```

#### **Opsi 3: Setup Otomatis**

1. **Jalankan setup otomatis**
   - Buka browser: `http://localhost/app_kost/setup_database.php`
   - File akan otomatis membuat database dan tabel

### **Struktur Database**

File `database_kost.sql` berisi:

✅ **7 Tabel Lengkap:**
- `tb_kamar` - Data kamar kost
- `tb_penghuni` - Data penghuni
- `tb_tagihan` - Data tagihan
- `user` - Data user admin
- `barang` - Data inventaris
- `tb_pembayaran` - History pembayaran
- `activity_log` - Log aktivitas

✅ **User Default:**
- Admin dan pemilik sudah tersedia

✅ **Foreign Key Relations:**
- Semua relasi antar tabel sudah benar

✅ **Database Bersih:**
- Tidak ada data sample
- Siap untuk data real

### **Troubleshooting Database:**

#### ❌ **Error: "Unknown database 'app_kost'"**
```
Solusi: Pastikan menggunakan nama database 'db_kost' bukan 'app_kost'
```

#### ❌ **Error: "Database doesn't exist"**
```
Solusi: Buat database dengan nama 'db_kost' terlebih dahulu
```

#### ❌ **Error: "Access denied"**
```
Solusi: Pastikan username='root' dan password='' (kosong)
```

#### ❌ **Error: "Duplicate entry"**
```
Solusi: Database sudah ada data, hapus database lama dan buat ulang
```

### **Checklist Setup Database:**

- [ ] Database dibuat dengan nama: **`db_kost`**
- [ ] File SQL diimport ke database **`db_kost`**
- [ ] Konfigurasi di `config/config.php` menggunakan **`db_kost`**
- [ ] Aplikasi bisa diakses tanpa error
- [ ] Login berhasil dengan user default

## 🔒 Fitur Keamanan

- Session management
- Password hashing (MD5)
- Input validation
- SQL injection protection dengan PDO prepared statements
- Akses kontrol berdasarkan role
- Log aktivitas lengkap

## 🛠️ Troubleshooting

### 1. Koneksi Database Error
- Pastikan MySQL service berjalan
- Cek konfigurasi di `config/config.php`
- Jalankan `setup_database.php` untuk setup database otomatis

### 2. Halaman Tidak Muncul
- Pastikan web server (Apache) berjalan
- Cek error log Apache
- Pastikan file permission benar

### 3. Fitur Tidak Berfungsi
- Cek error log PHP
- Pastikan semua tabel database sudah dibuat
- Cek koneksi database

## 📈 Pengembangan

### Menambah Fitur Baru
1. Buat controller di folder `controllers/`
2. Buat view di folder `views/admin/`
3. Update routing di `public/index.php`
4. Update sidebar di `views/admin/sidebar.php`

### Customisasi Tampilan
- Edit file CSS di `public/assets/css/`
- Edit file JavaScript di `public/assets/js/`
- Gunakan Bootstrap 5 untuk styling

## 📞 Support

Untuk bantuan dan pertanyaan, silakan hubungi:
- **Pemilik Aplikasi:** Andi
- **Email:** [email andi]
- **Telepon:** [nomor telepon andi]

Atau buat issue di repository.

## 📝 Catatan Penting

- Aplikasi ini dikembangkan khusus untuk manajemen kost
- Database bersih tanpa data sample
- Siap untuk digunakan dengan data real
- Backup database secara berkala
- Jangan menggunakan nama database lain selain `db_kost`
- Gunakan nama persis `db_kost` untuk konsistensi

## 📄 Lisensi

Project ini menggunakan lisensi MIT. 