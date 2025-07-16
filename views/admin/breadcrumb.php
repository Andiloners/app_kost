<?php
// Ambil $active dari variabel global jika belum ada
if (!isset($active)) {
    global $active;
}

$judul = 'Dashboard';
$icon = 'bi-speedometer2';
$description = 'Overview sistem manajemen kost';

switch ($active ?? '') {
    case 'penghuni': 
        $judul = 'Kelola Penghuni'; 
        $icon = 'bi-people-fill'; 
        $description = 'Manajemen data penghuni kost';
        break;
    case 'kamar': 
        $judul = 'Kelola Kamar'; 
        $icon = 'bi-door-closed-fill'; 
        $description = 'Manajemen kamar dan status hunian';
        break;
    case 'barang': 
        $judul = 'Kelola Barang'; 
        $icon = 'bi-box-seam'; 
        $description = 'Inventaris barang per kamar';
        break;
    case 'tagihan': 
        $judul = 'Kelola Tagihan'; 
        $icon = 'bi-receipt-cutoff'; 
        $description = 'Manajemen tagihan dan pembayaran';
        break;
    case 'pembayaran': 
        $judul = 'Kelola Pembayaran'; 
        $icon = 'bi-cash-coin'; 
        $description = 'Proses pembayaran tagihan';
        break;
    default:
        $description = 'Overview sistem manajemen kost';
}
?>

<div class="page-header mb-4">
    <div class="d-flex align-items-center justify-content-between">
        <div class="page-title">
            <div class="d-flex align-items-center mb-2">
                <div class="page-icon me-3">
                    <i class="bi <?= $icon ?>"></i>
                </div>
                <div>
                    <h1 class="page-title-text mb-0"><?= $judul ?></h1>
                    <p class="page-description mb-0"><?= $description ?></p>
                </div>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="?page=admin&menu=dashboard">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>
                <?php if ($active != 'dashboard'): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $judul ?>
                    </li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>
</div>

<style>
.page-header {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 25px 30px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 30px;
}

.page-title-text {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--dark-color);
    margin: 0;
}

.page-description {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 0;
}

.page-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow-md);
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb-item a:hover {
    color: var(--secondary-color);
}

.breadcrumb-item.active {
    color: var(--dark-color);
    font-weight: 600;
}
</style> 