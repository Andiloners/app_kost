-- =====================================================
-- DATABASE APLIKASI KOST - LENGKAP
-- =====================================================
-- File ini berisi struktur database dan user default
-- Import file ini untuk setup database lengkap

-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS db_kost;
USE db_kost;

-- =====================================================
-- STRUKTUR TABEL
-- =====================================================

-- Buat tabel tb_kamar
CREATE TABLE IF NOT EXISTS tb_kamar (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nomor VARCHAR(10) NOT NULL UNIQUE,
  harga DECIMAL(12,2) NOT NULL,
  status ENUM('kosong','terisi') DEFAULT 'kosong',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Buat tabel tb_penghuni
CREATE TABLE IF NOT EXISTS tb_penghuni (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  no_ktp VARCHAR(20) NOT NULL UNIQUE,
  no_hp VARCHAR(20) NOT NULL,
  alamat TEXT,
  tgl_masuk DATE NOT NULL,
  tgl_keluar DATE NULL,
  kamar_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (kamar_id) REFERENCES tb_kamar(id) ON DELETE SET NULL
);

-- Buat tabel tb_tagihan
CREATE TABLE IF NOT EXISTS tb_tagihan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  penghuni_id INT NOT NULL,
  kamar_id INT NOT NULL,
  periode_bulan INT NOT NULL,
  periode_tahun INT NOT NULL,
  jumlah DECIMAL(12,2) NOT NULL,
  status ENUM('belum_bayar','cicil','lunas') DEFAULT 'belum_bayar',
  tgl_jatuh_tempo DATE NOT NULL,
  tgl_bayar DATE NULL,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (penghuni_id) REFERENCES tb_penghuni(id) ON DELETE CASCADE,
  FOREIGN KEY (kamar_id) REFERENCES tb_kamar(id) ON DELETE CASCADE
);

-- Buat tabel user untuk login admin/pemilik
CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(100) NOT NULL,
  role ENUM('admin','pemilik') NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Buat tabel barang untuk inventaris
CREATE TABLE IF NOT EXISTS barang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jumlah INT NOT NULL DEFAULT 1,
  kamar_id INT,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (kamar_id) REFERENCES tb_kamar(id) ON DELETE SET NULL
);

-- Tabel history pembayaran tagihan
CREATE TABLE IF NOT EXISTS tb_pembayaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tagihan_id INT NOT NULL,
  tanggal DATE NOT NULL,
  jumlah DECIMAL(12,2) NOT NULL,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tagihan_id) REFERENCES tb_tagihan(id) ON DELETE CASCADE
);

-- Tabel activity log untuk tracking aktivitas
CREATE TABLE IF NOT EXISTS activity_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  activity VARCHAR(255) NOT NULL,
  description TEXT,
  ip_address VARCHAR(45),
  user_agent TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE SET NULL
);

-- =====================================================
-- USER DEFAULT UNTUK LOGIN
-- =====================================================

-- Tambahkan user admin default
-- Password: admin123 (dienkripsi dengan MD5)
INSERT INTO user (username, password, role) VALUES
('andi', MD5('andi123'), 'admin'),
('andi', MD5('andi123'), 'pemilik');

-- =====================================================
-- CATATAN PENTING
-- =====================================================
-- Database ini sudah bersih tanpa data sample
-- Anda bisa menambahkan data melalui aplikasi
-- 
-- LOGIN DEFAULT:
-- Username: admin, Password: admin123
-- Username: zaky, Password: zaky123
-- 
-- CARA IMPORT:
-- 1. Buka phpMyAdmin
-- 2. Buat database baru bernama 'db_kost'
-- 3. Import file ini
-- 4. Selesai!
-- ===================================================== 