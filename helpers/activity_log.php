<?php
/**
 * Helper function untuk mencatat aktivitas ke database
 * 
 * @param string $user Username yang melakukan aktivitas
 * @param string $module Modul yang diakses (penghuni, kamar, tagihan, dll)
 * @param string $action Aksi yang dilakukan (tambah, edit, hapus, dll)
 * @param string $description Deskripsi aktivitas
 * @return bool True jika berhasil, false jika gagal
 */
function log_activity($user, $module, $action, $description) {
    global $pdo;
    
    try {
        // Pastikan tabel activity_log ada
        $pdo->exec("CREATE TABLE IF NOT EXISTS activity_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user VARCHAR(100) NOT NULL,
            module VARCHAR(50) NOT NULL,
            action VARCHAR(50) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Insert log aktivitas
        $stmt = $pdo->prepare("INSERT INTO activity_log (user, module, action, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user, $module, $action, $description]);
        
        return true;
    } catch (Exception $e) {
        // Jika gagal, jangan hentikan aplikasi, hanya return false
        error_log("Failed to log activity: " . $e->getMessage());
        return false;
    }
}

/**
 * Ambil aktivitas terbaru untuk dashboard
 * 
 * @param int $limit Jumlah aktivitas yang diambil
 * @return array Array aktivitas terbaru
 */
function get_recent_activities($limit = 10) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Failed to get recent activities: " . $e->getMessage());
        return [];
    }
}

/**
 * Dapatkan icon Bootstrap untuk setiap modul aktivitas
 * 
 * @param string $module Nama modul
 * @return string Nama icon Bootstrap
 */
function get_activity_icon($module, $action = '') {
    $icons = [
        'penghuni' => 'person-check',
        'kamar' => 'house-door',
        'tagihan' => 'receipt-cutoff',
        'pembayaran' => 'credit-card',
        'barang' => 'box-seam',
        'default' => 'activity',
        // Aksi spesifik
        'tambah' => 'plus-circle',
        'edit' => 'pencil-square',
        'hapus' => 'trash',
        'bayar' => 'cash-coin',
        'generate' => 'arrow-repeat',
    ];
    if (!empty($action) && isset($icons[$action])) return $icons[$action];
    return $icons[$module] ?? $icons['default'];
}

function get_activity_color($action) {
    $colors = [
        'tambah' => 'success',
        'edit' => 'warning',
        'hapus' => 'danger',
        'bayar' => 'info',
        'generate' => 'primary',
        'default' => 'secondary',
    ];
    return $colors[$action] ?? $colors['default'];
}
?> 