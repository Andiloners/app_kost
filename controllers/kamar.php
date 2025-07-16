<?php
// controllers/kamar.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/activity_log.php';

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT k.*, p.nama as penghuni_nama FROM tb_kamar k LEFT JOIN tb_penghuni p ON k.id = p.kamar_id AND p.tgl_keluar IS NULL");
            $kamars = $stmt->fetchAll();
            include '../views/admin/kamar_list.php';
            break;
        case 'add':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nomor = $_POST['nomor'] ?? '';
                $harga = $_POST['harga'] ?? '';
                if (empty($nomor) || empty($harga)) {
                    $error = "Nomor kamar dan harga wajib diisi!";
                } else {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO tb_kamar (nomor, harga, status) VALUES (?, ?, 'kosong')");
                        $stmt->execute([$nomor, $harga]);
                        $user = $_SESSION['user']['username'] ?? 'admin';
                        log_activity($user, 'kamar', 'tambah', 'Menambah kamar: ' . $nomor);
                        header("Location: ?page=admin&menu=kamar&action=list");
                        exit;
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                            $error = "Nomor kamar sudah terdaftar, silakan gunakan nomor lain.";
                        } else {
                            $error = "Gagal menambah kamar! ".$e->getMessage();
                        }
                    }
                }
            }
            $kamar = [];
            include '../views/admin/form_kamar.php';
            break;
        case 'edit':
            $id = $_GET['id'] ?? 0;
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nomor = $_POST['nomor'] ?? '';
                $harga = $_POST['harga'] ?? '';
                if (empty($nomor) || empty($harga)) {
                    $error = "Nomor kamar dan harga wajib diisi!";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE tb_kamar SET nomor=?, harga=? WHERE id=?");
                        $stmt->execute([$nomor, $harga, $id]);
                        $user = $_SESSION['user']['username'] ?? 'admin';
                        log_activity($user, 'kamar', 'edit', 'Edit kamar: ' . $nomor);
                        header("Location: ?page=admin&menu=kamar&action=list");
                        exit;
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                            $error = "Nomor kamar sudah terdaftar, silakan gunakan nomor lain.";
                        } else {
                            $error = "Gagal mengupdate kamar! ".$e->getMessage();
                        }
                    }
                }
            }
            $stmt = $pdo->prepare("SELECT * FROM tb_kamar WHERE id=?");
            $stmt->execute([$id]);
            $kamar = $stmt->fetch();
            if (!$kamar) {
                $error = "Kamar tidak ditemukan!";
                header("Location: ?page=admin&menu=kamar&action=list");
                exit;
            }
            include '../views/admin/form_kamar.php';
            break;
        case 'delete':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM tb_kamar WHERE id=?");
            $stmt->execute([$id]);
            $user = $_SESSION['user']['username'] ?? 'admin';
            log_activity($user, 'kamar', 'hapus', 'Hapus kamar ID: ' . $id);
            header("Location: ?page=admin&menu=kamar&action=list");
            exit;
            break;
        default:
            header("Location: ?page=admin&menu=kamar&action=list");
            exit;
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
if (!empty($error)) {
    echo '<div class="alert alert-danger">'.$error.'</div>';
} 