<?php
// check_db.php - File untuk mengecek koneksi database
require_once 'config/config.php';

echo "<h2>Status Koneksi Database</h2>";

try {
    // Test koneksi
    $stmt = $pdo->query("SELECT 1");
    echo "<p style='color: green;'>✓ Koneksi database berhasil!</p>";
    
    // Cek tabel yang ada
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tabel yang tersedia:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Cek data di setiap tabel
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetch()['count'];
        echo "<p><strong>$table:</strong> $count data</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Error koneksi database: " . $e->getMessage() . "</p>";
}
?> 