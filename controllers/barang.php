<?php
// controllers/barang.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/activity_log.php';

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

try {
    switch ($action) {
        case 'list':
            // Cek apakah tabel barang sudah ada
            $stmt = $pdo->query("SHOW TABLES LIKE 'barang'");
            if ($stmt->rowCount() == 0) {
                // Buat tabel barang jika belum ada
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS barang (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        nama VARCHAR(100) NOT NULL,
                        jumlah INT NOT NULL DEFAULT 1,
                        kamar_id INT,
                        keterangan TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        FOREIGN KEY (kamar_id) REFERENCES tb_kamar(id) ON DELETE SET NULL
                    )
                ");
            }
            
            $stmt = $pdo->query("SELECT b.*, k.nomor as kamar_nomor FROM barang b LEFT JOIN tb_kamar k ON b.kamar_id = k.id ORDER BY b.id DESC");
            $barang = $stmt->fetchAll();
            include '../views/admin/barang_list.php';
            break;
            
        case 'add':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama = $_POST['nama'] ?? '';
                $jumlah = $_POST['jumlah'] ?? 1;
                $kamar_id = $_POST['kamar_id'] ?? '';
                $keterangan = $_POST['keterangan'] ?? '';
                
                if (empty($nama)) {
                    $error = "Nama barang harus diisi!";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO barang (nama, jumlah, kamar_id, keterangan) VALUES (?, ?, ?, ?)");
                    if ($stmt->execute([$nama, $jumlah, $kamar_id, $keterangan])) {
                        $success = "Barang berhasil ditambahkan!";
                        $user = $_SESSION['user']['username'] ?? 'admin';
                        log_activity($user, 'barang', 'tambah', 'Menambah barang: ' . $nama);
                        header("Location: ?page=admin&menu=barang&action=list");
                        exit;
                    } else {
                        $error = "Gagal menambahkan barang!";
                    }
                }
            }
            
            // Ambil daftar kamar
            $stmt = $pdo->query("SELECT * FROM tb_kamar ORDER BY nomor");
            $kamar_list = $stmt->fetchAll();
            include '../views/admin/form_barang.php';
            break;
            
        case 'edit':
            $id = $_GET['id'] ?? 0;
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama = $_POST['nama'] ?? '';
                $jumlah = $_POST['jumlah'] ?? 1;
                $kamar_id = $_POST['kamar_id'] ?? '';
                $keterangan = $_POST['keterangan'] ?? '';
                
                if (empty($nama)) {
                    $error = "Nama barang harus diisi!";
                } else {
                    $stmt = $pdo->prepare("UPDATE barang SET nama=?, jumlah=?, kamar_id=?, keterangan=? WHERE id=?");
                    if ($stmt->execute([$nama, $jumlah, $kamar_id, $keterangan, $id])) {
                        $success = "Barang berhasil diupdate!";
                        $user = $_SESSION['user']['username'] ?? 'admin';
                        log_activity($user, 'barang', 'edit', 'Edit barang: ' . $nama);
                        header("Location: ?page=admin&menu=barang&action=list");
                        exit;
                    } else {
                        $error = "Gagal mengupdate barang!";
                    }
                }
            }
            
            // Ambil data barang
            $stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
            $stmt->execute([$id]);
            $barang = $stmt->fetch();
            
            if (!$barang) {
                $error = "Barang tidak ditemukan!";
                header("Location: ?page=admin&menu=barang&action=list");
                exit;
            }
            
            // Ambil daftar kamar
            $stmt = $pdo->query("SELECT * FROM tb_kamar ORDER BY nomor");
            $kamar_list = $stmt->fetchAll();
            include '../views/admin/form_barang.php';
            break;
            
        case 'delete':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM barang WHERE id = ?");
            if ($stmt->execute([$id])) {
                $success = "Barang berhasil dihapus!";
                $user = $_SESSION['user']['username'] ?? 'admin';
                log_activity($user, 'barang', 'hapus', 'Hapus barang ID: ' . $id);
            } else {
                $error = "Gagal menghapus barang!";
            }
            
            header("Location: ?page=admin&menu=barang&action=list");
            exit;
            break;
            
        default:
            header("Location: ?page=admin&menu=barang&action=list");
            exit;
    }
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

if (!empty($error)) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>' . $error . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}

if (!empty($success)) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>' . $success . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
} 