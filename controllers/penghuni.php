<?php
// controllers/penghuni.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/activity_log.php';

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

try {
    switch ($action) {
        case 'list':
            // Ambil semua data penghuni dengan join ke tabel kamar
            $stmt = $pdo->query("SELECT p.*, k.nomor as kamar_nomor FROM tb_penghuni p LEFT JOIN tb_kamar k ON p.kamar_id = k.id ORDER BY p.id DESC");
            $penghuni = $stmt->fetchAll();
            
            // Debug: tampilkan jumlah data
            if (empty($penghuni)) {
                echo '<div class="alert alert-info">Belum ada data penghuni. Silakan tambah penghuni baru.</div>';
            }
            
            // Tampilkan view list
            include '../views/admin/penghuni_list.php';
            break;
            
        case 'add':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Proses tambah penghuni
                $nama = $_POST['nama'] ?? '';
                $no_ktp = $_POST['no_ktp'] ?? '';
                $no_hp = $_POST['no_hp'] ?? '';
                $alamat = $_POST['alamat'] ?? '';
                $kamar_id = $_POST['kamar_id'] ?? '';
                $tgl_masuk = $_POST['tgl_masuk'] ?? '';
                
                if (empty($nama) || empty($no_ktp) || empty($no_hp)) {
                    $error = "Nama, No KTP, dan No HP harus diisi!";
                } else {
                    // Mulai transaksi
                    $pdo->beginTransaction();
                    try {
                        $stmt = $pdo->prepare("INSERT INTO tb_penghuni (nama, no_ktp, no_hp, alamat, kamar_id, tgl_masuk) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$nama, $no_ktp, $no_hp, $alamat, $kamar_id, $tgl_masuk]);
                        
                        // Update status kamar menjadi terisi
                        if ($kamar_id) {
                            $stmt = $pdo->prepare("UPDATE tb_kamar SET status = 'terisi' WHERE id = ?");
                            $stmt->execute([$kamar_id]);
                        }
                        
                        $pdo->commit();
                        $success = "Penghuni berhasil ditambahkan!";
                        $user = $_SESSION['user']['username'] ?? 'admin';
                        log_activity($user, 'penghuni', 'tambah', 'Menambah penghuni: ' . $nama);
                        header("Location: ?page=admin&menu=penghuni&action=list");
                        exit;
                    } catch (Exception $e) {
                        $pdo->rollback();
                        $error = "Gagal menambahkan penghuni: " . $e->getMessage();
                    }
                }
            }
            // Ambil kamar yang statusnya kosong saja
            $stmt = $pdo->query("SELECT * FROM tb_kamar WHERE status = 'kosong' ORDER BY nomor");
            $kamar_list = $stmt->fetchAll();
            include '../views/admin/form_penghuni.php';
            break;
        case 'edit':
            $id = $_GET['id'] ?? 0;
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Proses edit penghuni
                $nama = $_POST['nama'] ?? '';
                $no_ktp = $_POST['no_ktp'] ?? '';
                $no_hp = $_POST['no_hp'] ?? '';
                $alamat = $_POST['alamat'] ?? '';
                $kamar_id = $_POST['kamar_id'] ?? '';
                $tgl_masuk = $_POST['tgl_masuk'] ?? '';
                $tgl_keluar = $_POST['tgl_keluar'] ?? '';
                
                if (empty($nama) || empty($no_ktp) || empty($no_hp)) {
                    $error = "Nama, No KTP, dan No HP harus diisi!";
                } else {
                    // Mulai transaksi
                    $pdo->beginTransaction();
                    try {
                        // Ambil data penghuni lama untuk update status kamar
                        $stmt = $pdo->prepare("SELECT kamar_id FROM tb_penghuni WHERE id = ?");
                        $stmt->execute([$id]);
                        $penghuni_lama = $stmt->fetch();
                        $kamar_lama = $penghuni_lama['kamar_id'];
                        
                        // Update data penghuni
                        $stmt = $pdo->prepare("UPDATE tb_penghuni SET nama=?, no_ktp=?, no_hp=?, alamat=?, kamar_id=?, tgl_masuk=?, tgl_keluar=? WHERE id=?");
                        $stmt->execute([$nama, $no_ktp, $no_hp, $alamat, $kamar_id, $tgl_masuk, $tgl_keluar, $id]);
                        
                        // Update status kamar lama menjadi kosong jika berbeda
                        if ($kamar_lama && $kamar_lama != $kamar_id) {
                            $stmt = $pdo->prepare("UPDATE tb_kamar SET status = 'kosong' WHERE id = ?");
                            $stmt->execute([$kamar_lama]);
                        }
                        
                        // Update status kamar baru menjadi terisi
                        if ($kamar_id && $kamar_id != $kamar_lama) {
                            $stmt = $pdo->prepare("UPDATE tb_kamar SET status = 'terisi' WHERE id = ?");
                            $stmt->execute([$kamar_id]);
                        }
                        
                        $pdo->commit();
                        $success = "Data penghuni berhasil diupdate!";
                        $user = $_SESSION['user']['username'] ?? 'admin';
                        log_activity($user, 'penghuni', 'edit', 'Edit penghuni: ' . $nama);
                        header("Location: ?page=admin&menu=penghuni&action=list");
                        exit;
                    } catch (Exception $e) {
                        $pdo->rollback();
                        $error = "Gagal mengupdate data penghuni: " . $e->getMessage();
                    }
                }
            }
            // Ambil data penghuni yang akan diedit
            $stmt = $pdo->prepare("SELECT * FROM tb_penghuni WHERE id = ?");
            $stmt->execute([$id]);
            $penghuni = $stmt->fetch();
            if (!$penghuni) {
                $error = "Penghuni tidak ditemukan!";
                header("Location: ?page=admin&menu=penghuni&action=list");
                exit;
            }
            // Ambil semua kamar, tapi kamar yang sedang dipakai penghuni tetap bisa dipilih
            $stmt = $pdo->prepare("SELECT * FROM tb_kamar WHERE status = 'kosong' OR id = ? ORDER BY nomor");
            $stmt->execute([$penghuni['kamar_id']]);
            $kamar_list = $stmt->fetchAll();
            include '../views/admin/form_penghuni.php';
            break;
            
        case 'delete':
            $id = $_GET['id'] ?? 0;
            
            // Mulai transaksi
            $pdo->beginTransaction();
            try {
                // Ambil kamar_id sebelum menghapus
                $stmt = $pdo->prepare("SELECT kamar_id FROM tb_penghuni WHERE id = ?");
                $stmt->execute([$id]);
                $penghuni = $stmt->fetch();
                $kamar_id = $penghuni['kamar_id'];
                
                // Hapus penghuni
                $stmt = $pdo->prepare("DELETE FROM tb_penghuni WHERE id = ?");
                $stmt->execute([$id]);
                
                // Update status kamar menjadi kosong
                if ($kamar_id) {
                    $stmt = $pdo->prepare("UPDATE tb_kamar SET status = 'kosong' WHERE id = ?");
                    $stmt->execute([$kamar_id]);
                }
                
                $pdo->commit();
                $success = "Penghuni berhasil dihapus!";
                $user = $_SESSION['user']['username'] ?? 'admin';
                log_activity($user, 'penghuni', 'hapus', 'Hapus penghuni ID: ' . $id);
            } catch (Exception $e) {
                $pdo->rollback();
                $error = "Gagal menghapus penghuni: " . $e->getMessage();
            }
            
            header("Location: ?page=admin&menu=penghuni&action=list");
            exit;
            break;
            
        default:
            header("Location: ?page=admin&menu=penghuni&action=list");
            exit;
    }
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Jika ada error, tampilkan di halaman list
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