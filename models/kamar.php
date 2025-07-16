<?php
// models/kamar.php
function getAllKamar($pdo) {
    $stmt = $pdo->query("SELECT * FROM tb_kamar");
    return $stmt->fetchAll();
} 