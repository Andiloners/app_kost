<?php
/**
 * Setup Database Aplikasi Kost
 * File ini akan membuat database dan tabel yang diperlukan
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'db_kost';

try {
    // Koneksi ke MySQL tanpa memilih database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Setup Database Aplikasi Kost</h2>";
    echo "<hr>";
    
    // 1. Buat database
    echo "<h3>1. Membuat database...</h3>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database`");
    echo "✓ Database '$database' berhasil dibuat/ditemukan<br>";
    
    // 2. Pilih database
    $pdo->exec("USE `$database`");
    echo "✓ Database '$database' dipilih<br><br>";
    
    // 3. Buat tabel satu per satu
    echo "<h3>2. Membuat tabel...</h3>";
    
    // Tabel tb_kamar
    $sql = "CREATE TABLE IF NOT EXISTS tb_kamar (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nomor VARCHAR(10) NOT NULL UNIQUE,
        harga DECIMAL(12,2) NOT NULL,
        status ENUM('kosong','terisi') DEFAULT 'kosong',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✓ Tabel tb_kamar dibuat<br>";
    
    // Tabel user
    $sql = "CREATE TABLE IF NOT EXISTS user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(100) NOT NULL,
        role ENUM('admin','pemilik') NOT NULL DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✓ Tabel user dibuat<br>";
    
    // Tabel tb_penghuni
    $sql = "CREATE TABLE IF NOT EXISTS tb_penghuni (
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
    )";
    $pdo->exec($sql);
    echo "✓ Tabel tb_penghuni dibuat<br>";
    
    // Tabel tb_tagihan
    $sql = "CREATE TABLE IF NOT EXISTS tb_tagihan (
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
    )";
    $pdo->exec($sql);
    echo "✓ Tabel tb_tagihan dibuat<br>";
    
    // Tabel barang
    $sql = "CREATE TABLE IF NOT EXISTS barang (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        jumlah INT NOT NULL DEFAULT 1,
        kamar_id INT,
        keterangan TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (kamar_id) REFERENCES tb_kamar(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "✓ Tabel barang dibuat<br>";
    
    // Tabel tb_pembayaran
    $sql = "CREATE TABLE IF NOT EXISTS tb_pembayaran (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tagihan_id INT NOT NULL,
        tanggal DATE NOT NULL,
        jumlah DECIMAL(12,2) NOT NULL,
        keterangan TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (tagihan_id) REFERENCES tb_tagihan(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "✓ Tabel tb_pembayaran dibuat<br>";
    
    // Tabel activity_log
    $sql = "CREATE TABLE IF NOT EXISTS activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        activity VARCHAR(255) NOT NULL,
        description TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "✓ Tabel activity_log dibuat<br>";
    
    echo "<br>";
    
    // 4. Tambah user default
    echo "<h3>3. Menambahkan user default...</h3>";
    
    // Cek apakah user sudah ada
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
    $stmt->execute(['admin']);
    if ($stmt->fetchColumn() == 0) {
        $sql = "INSERT INTO user (username, password, role) VALUES 
                ('admin', MD5('admin123'), 'admin'),
                ('zaky', MD5('zaky123'), 'pemilik')";
        $pdo->exec($sql);
        echo "✓ User default ditambahkan<br>";
    } else {
        echo "✓ User default sudah ada<br>";
    }
    
    echo "  - Username: admin, Password: admin123<br>";
    echo "  - Username: zaky, Password: zaky123<br>";
    
    echo "<br>";
    
    // 5. Cek struktur tabel
    echo "<h3>4. Verifikasi struktur database:</h3>";
    $tables = ['tb_kamar', 'tb_penghuni', 'tb_tagihan', 'user', 'barang', 'tb_pembayaran', 'activity_log'];
    $table_exists = 0;
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "✓ Tabel '$table' tersedia<br>";
                $table_exists++;
            } else {
                echo "❌ Tabel '$table' tidak ditemukan<br>";
            }
        } catch (PDOException $e) {
            echo "❌ Error cek tabel '$table': " . $e->getMessage() . "<br>";
        }
    }
    
    echo "<br>";
    
    if ($table_exists == count($tables)) {
        echo "<h3>✅ Setup database selesai dengan sukses!</h3>";
        echo "<p>Database '$database' telah dibuat dengan semua tabel yang diperlukan.</p>";
        echo "<p>Anda bisa mulai menambahkan data melalui aplikasi.</p>";
        echo "<p><a href='public/index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Klik di sini untuk membuka aplikasi</a></p>";
    } else {
        echo "<h3>⚠️ Setup database selesai dengan beberapa error!</h3>";
        echo "<p>Beberapa tabel mungkin tidak berhasil dibuat. Silakan coba lagi atau import manual via phpMyAdmin.</p>";
        echo "<p><a href='public/index.php' style='background: #ffc107; color: black; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Coba akses aplikasi</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<h2>❌ Error Setup Database</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Pastikan:</p>";
    echo "<ul>";
    echo "<li>XAMPP sudah berjalan</li>";
    echo "<li>MySQL service aktif</li>";
    echo "<li>Username dan password database benar</li>";
    echo "</ul>";
}
?> 