<?php
// models/penghuni.php
function getAllPenghuni($pdo) {
    $stmt = $pdo->query("SELECT * FROM tb_penghuni");
    return $stmt->fetchAll();
} 