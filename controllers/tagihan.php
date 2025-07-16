<?php
// controllers/tagihan.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/activity_log.php';

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

try {
    switch ($action) {
        case 'list':
            // Ambil semua tagihan dengan join ke penghuni dan kamar
            $stmt = $pdo->query("
                SELECT t.*, p.nama as penghuni_nama, k.nomor as kamar_nomor 
                FROM tb_tagihan t 
                LEFT JOIN tb_penghuni p ON t.penghuni_id = p.id 
                LEFT JOIN tb_kamar k ON t.kamar_id = k.id 
                ORDER BY t.created_at DESC
            ");
            $tagihan = $stmt->fetchAll();
            include '../views/admin/tagihan_list.php';
            break;
            
        case 'generate':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $bulan = $_POST['bulan'] ?? date('n');
                $tahun = $_POST['tahun'] ?? date('Y');
                
                // Ambil semua penghuni aktif
                $stmt = $pdo->query("
                    SELECT p.*, k.harga, k.nomor as kamar_nomor 
                    FROM tb_penghuni p 
                    LEFT JOIN tb_kamar k ON p.kamar_id = k.id 
                    WHERE p.tgl_keluar IS NULL
                ");
                $penghuni_aktif = $stmt->fetchAll();
                
                $generated = 0;
                foreach ($penghuni_aktif as $penghuni) {
                    // Cek apakah tagihan untuk periode ini sudah ada
                    $stmt = $pdo->prepare("
                        SELECT COUNT(*) FROM tb_tagihan 
                        WHERE penghuni_id = ? AND periode_bulan = ? AND periode_tahun = ?
                    ");
                    $stmt->execute([$penghuni['id'], $bulan, $tahun]);
                    
                    if ($stmt->fetchColumn() == 0) {
                        // Generate tagihan baru
                        $tgl_jatuh_tempo = date('Y-m-d', strtotime("$tahun-$bulan-15"));
                        
                        $stmt = $pdo->prepare("
                            INSERT INTO tb_tagihan (penghuni_id, kamar_id, periode_bulan, periode_tahun, jumlah, tgl_jatuh_tempo) 
                            VALUES (?, ?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([
                            $penghuni['id'], 
                            $penghuni['kamar_id'], 
                            $bulan, 
                            $tahun, 
                            $penghuni['harga'], 
                            $tgl_jatuh_tempo
                        ]);
                        $generated++;
                    }
                }
                
                $success = "Berhasil generate $generated tagihan untuk periode " . date('F Y', strtotime("$tahun-$bulan-01"));
                log_activity($_SESSION['user']['username'] ?? 'admin', 'tagihan', 'generate', 'Generate tagihan periode: ' . $bulan . '/' . $tahun . ', total: ' . $generated);
                header("Location: ?page=admin&menu=tagihan&action=list");
                exit;
            }
            include '../views/admin/tagihan_generate.php';
            break;
            
        case 'bayar':
            $id = $_GET['id'] ?? 0;
            // Ambil data tagihan beserta nama penghuni dan nomor kamar
            $stmt = $pdo->prepare("
                SELECT t.*, 
                    p.nama as penghuni_nama, 
                    k.nomor as kamar_nomor,
                    (
                        SELECT IFNULL(SUM(jumlah),0) FROM tb_pembayaran WHERE tagihan_id = t.id
                    ) as total_terbayar
                FROM tb_tagihan t
                LEFT JOIN tb_penghuni p ON t.penghuni_id = p.id
                LEFT JOIN tb_kamar k ON t.kamar_id = k.id
                WHERE t.id = ?
            ");
            $stmt->execute([$id]);
            $tagihan = $stmt->fetch();
            if (!$tagihan) {
                $error = "Tagihan tidak ditemukan!";
                header("Location: ?page=admin&menu=tagihan&action=list");
                exit;
            }
            $sisa = $tagihan['jumlah'] - $tagihan['total_terbayar'];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Bersihkan input dari karakter non-angka
                $jumlah_bayar = $_POST['jumlah_bayar'] ?? 0;
                $jumlah_bayar = preg_replace('/[^0-9]/', '', $jumlah_bayar);
                $jumlah_bayar = (int)$jumlah_bayar;
                $keterangan = $_POST['keterangan'] ?? '';
                if ($jumlah_bayar <= 0) {
                    $error = "Jumlah pembayaran harus lebih dari 0!";
                } elseif ($jumlah_bayar > $sisa) {
                    $error = "Jumlah pembayaran tidak boleh lebih dari sisa tagihan!";
                } else {
                    // Simpan ke tb_pembayaran
                    $stmt = $pdo->prepare("INSERT INTO tb_pembayaran (tagihan_id, tanggal, jumlah, keterangan) VALUES (?, CURDATE(), ?, ?)");
                    $stmt->execute([$id, $jumlah_bayar, $keterangan]);
                    // Hitung total terbayar setelah pembayaran
                    $stmt = $pdo->prepare("SELECT IFNULL(SUM(jumlah),0) as total FROM tb_pembayaran WHERE tagihan_id = ?");
                    $stmt->execute([$id]);
                    $total_terbayar = $stmt->fetch()['total'];
                    // Update status tagihan
                    if ($total_terbayar >= $tagihan['jumlah']) {
                        $stmt = $pdo->prepare("UPDATE tb_tagihan SET status = 'lunas', tgl_bayar = CURDATE(), keterangan = ? WHERE id = ?");
                        $stmt->execute([$keterangan, $id]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE tb_tagihan SET status = 'cicil', keterangan = ? WHERE id = ?");
                        $stmt->execute([$keterangan, $id]);
                    }
                    log_activity($_SESSION['user']['username'] ?? 'admin', 'pembayaran', 'bayar', 'Pembayaran tagihan ID: ' . $id . ', jumlah: ' . $jumlah_bayar);
                    header("Location: ?page=admin&menu=tagihan&action=receipt&id=" . $id);
                    exit;
                }
            }
            // Ambil history pembayaran
            $stmt = $pdo->prepare("SELECT * FROM tb_pembayaran WHERE tagihan_id = ? ORDER BY tanggal, id");
            $stmt->execute([$id]);
            $history = $stmt->fetchAll();
            include '../views/admin/pembayaran_form.php';
            break;
            
        case 'receipt':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("
                SELECT t.*, p.nama as penghuni_nama, p.no_hp, p.alamat, k.nomor as kamar_nomor, k.harga
                FROM tb_tagihan t
                LEFT JOIN tb_penghuni p ON t.penghuni_id = p.id
                LEFT JOIN tb_kamar k ON t.kamar_id = k.id
                WHERE t.id = ?
            ");
            $stmt->execute([$id]);
            $tagihan = $stmt->fetch();
            if (!$tagihan) {
                $error = "Tagihan tidak ditemukan!";
                header("Location: ?page=admin&menu=tagihan&action=list");
                exit;
            }
            // Ambil history pembayaran
            $stmt = $pdo->prepare("SELECT * FROM tb_pembayaran WHERE tagihan_id = ? ORDER BY tanggal, id");
            $stmt->execute([$id]);
            $history = $stmt->fetchAll();
            include '../views/admin/pembayaran_receipt.php';
            break;
            
        case 'delete':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM tb_tagihan WHERE id = ?");
            $stmt->execute([$id]);
            log_activity($_SESSION['user']['username'] ?? 'admin', 'tagihan', 'hapus', 'Hapus tagihan ID: ' . $id);
            header("Location: ?page=admin&menu=tagihan&action=list");
            exit;
            break;
            
        case 'pembayaran_list':
            // Ambil tagihan yang belum lunas/cicil
            $stmt = $pdo->query("
                SELECT t.*, p.nama as penghuni_nama, k.nomor as kamar_nomor 
                FROM tb_tagihan t 
                LEFT JOIN tb_penghuni p ON t.penghuni_id = p.id 
                LEFT JOIN tb_kamar k ON t.kamar_id = k.id 
                WHERE t.status != 'lunas' 
                ORDER BY t.created_at DESC
            ");
            $tagihan = $stmt->fetchAll();
            $is_pembayaran_menu = true;
            include '../views/admin/tagihan_list.php';
            break;
            
        default:
            header("Location: ?page=admin&menu=tagihan&action=list");
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