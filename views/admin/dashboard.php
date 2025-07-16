<?php
// views/admin/dashboard.php
$title = 'Dashboard Admin';
$active = 'dashboard';

// Gunakan koneksi database yang sudah ada
require_once __DIR__ . '/../../config/config.php';

// Ambil data user dari session
$user = $_SESSION['user'] ?? null;

// Ambil data statistik dari database
try {
    // Statistik penghuni
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_penghuni WHERE tgl_keluar IS NULL");
    $penghuni_aktif = $stmt->fetch()['total'];

    // Statistik kamar
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_kamar WHERE status = 'kosong'");
    $kamar_kosong = $stmt->fetch()['total'];
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_kamar");
    $total_kamar = $stmt->fetch()['total'];

    // Statistik tagihan
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_tagihan WHERE status = 'belum_bayar' OR status = 'cicil'");
    $tagihan_pending = $stmt->fetch()['total'];
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_tagihan WHERE status = 'lunas'");
    $tagihan_lunas = $stmt->fetch()['total'];

    // Statistik barang
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM barang");
    $total_barang = $stmt->fetch()['total'];

    // Data untuk chart status kamar
    $stmt = $pdo->query("SELECT status, COUNT(*) as jumlah FROM tb_kamar GROUP BY status");
    $kamar_chart_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Data untuk chart status tagihan
    $stmt = $pdo->query("SELECT status, COUNT(*) as jumlah FROM tb_tagihan GROUP BY status");
    $tagihan_chart_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Data pendapatan bulanan (6 bulan terakhir)
    $stmt = $pdo->query("SELECT DATE_FORMAT(tgl_bayar, '%b %Y') as bulan, SUM(jumlah) as total FROM tb_tagihan WHERE status = 'lunas' AND tgl_bayar IS NOT NULL GROUP BY YEAR(tgl_bayar), MONTH(tgl_bayar) ORDER BY tgl_bayar DESC LIMIT 6");
    $monthly_revenue = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

    // Gabungan aktivitas terbaru: penghuni, pembayaran, tagihan
    $recent_activities = [];
    // Penghuni terbaru
    $stmt = $pdo->query("SELECT nama, kamar_id, tgl_masuk FROM tb_penghuni ORDER BY tgl_masuk DESC LIMIT 5");
    foreach ($stmt->fetchAll() as $row) {
        $kamar = '-';
        if ($row['kamar_id']) {
            $kamar_stmt = $pdo->prepare("SELECT nomor FROM tb_kamar WHERE id = ?");
            $kamar_stmt->execute([$row['kamar_id']]);
            $kamar = $kamar_stmt->fetchColumn() ?: '-';
        }
        $recent_activities[] = [
            'type' => 'penghuni',
            'desc' => 'Penghuni Baru: ' . $row['nama'] . ' masuk kamar ' . $kamar,
            'waktu' => $row['tgl_masuk'],
            'icon' => 'person-plus',
            'color' => 'primary',
        ];
    }
    // Pembayaran terakhir
    $stmt = $pdo->query("SELECT p.*, t.penghuni_id, t.periode_bulan, t.periode_tahun FROM tb_pembayaran p LEFT JOIN tb_tagihan t ON p.tagihan_id = t.id ORDER BY p.tanggal DESC, p.id DESC LIMIT 5");
    foreach ($stmt->fetchAll() as $row) {
        $nama = '-';
        if ($row['penghuni_id']) {
            $penghuni_stmt = $pdo->prepare("SELECT nama FROM tb_penghuni WHERE id = ?");
            $penghuni_stmt->execute([$row['penghuni_id']]);
            $nama = $penghuni_stmt->fetchColumn() ?: '-';
        }
        $recent_activities[] = [
            'type' => 'pembayaran',
            'desc' => 'Pembayaran: ' . $nama . ' membayar ' . number_format($row['jumlah'],0,',','.') . ' pada tagihan ' . $row['periode_bulan'] . '/' . $row['periode_tahun'],
            'waktu' => $row['tanggal'],
            'icon' => 'cash-coin',
            'color' => 'success',
        ];
    }
    // Tagihan terakhir
    $stmt = $pdo->query("SELECT t.*, p.nama FROM tb_tagihan t LEFT JOIN tb_penghuni p ON t.penghuni_id = p.id ORDER BY t.created_at DESC LIMIT 5");
    foreach ($stmt->fetchAll() as $row) {
        $recent_activities[] = [
            'type' => 'tagihan',
            'desc' => 'Tagihan: ' . $row['nama'] . ' periode ' . $row['periode_bulan'] . '/' . $row['periode_tahun'] . ' dibuat',
            'waktu' => $row['created_at'],
            'icon' => 'receipt-cutoff',
            'color' => 'info',
        ];
    }
    // Gabungkan dan urutkan berdasarkan waktu terbaru
    usort($recent_activities, function($a, $b) {
        return strtotime($b['waktu']) - strtotime($a['waktu']);
    });
    $recent_activities = array_slice($recent_activities, 0, 10);

} catch (PDOException $e) {
    // Jika ada error, gunakan data default
    $penghuni_aktif = 0;
    $penghuni_keluar = 0;
    $total_kamar = 0;
    $kamar_kosong = 0;
    $kamar_terisi = 0;
    $tagihan_pending = 0;
    $tagihan_lunas = 0;
    $total_barang = 0;
    $recent_activities = [];
}

// Monthly revenue (simulasi)
$monthly_revenue = [
    'Jan' => 15000000,
    'Feb' => 16000000,
    'Mar' => 15500000,
    'Apr' => 17000000,
    'May' => 16500000,
    'Jun' => 18000000
];

// Hapus query dan tampilan aktivitas terbaru dari dashboard
?>

<div class="welcome-section mb-5">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="welcome-title mb-2">
                Selamat datang, <span class="text-gradient"><?= htmlspecialchars($user['username'] ?? 'Admin') ?></span>!
            </h1>
            <p class="welcome-subtitle mb-0">
                Semoga harimu menyenangkan. Berikut ringkasan data kost hari ini.
            </p>
</div>
        <div class="col-md-4 text-end">
            <div class="current-time">
                <i class="bi bi-clock me-2"></i>
                <span id="currentTime"><?= date('d M Y H:i') ?></span>
          </div>
        </div>
      </div>
    </div>

<!-- Setelah section selamat datang -->
<div class="quick-actions-section mb-5">
    <div class="section-header">
        <h2 class="section-title">
            <i class="bi bi-lightning-charge-fill me-2"></i>
            Menu Akses Cepat
        </h2>
        <p class="section-description">Akses cepat ke fitur-fitur utama sistem</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-3 col-md-6">
            <a href="?page=admin&menu=penghuni&action=add" class="quick-action-item d-block text-center">
                <div class="quick-action-icon bg-primary mx-auto mb-2">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div class="quick-action-content">
                    <h6>Tambah Penghuni</h6>
                    <p>Daftarkan penghuni baru</p>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="?page=admin&menu=tagihan&action=generate" class="quick-action-item d-block text-center">
                <div class="quick-action-icon bg-success mx-auto mb-2">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div class="quick-action-content">
                    <h6>Generate Tagihan</h6>
                    <p>Buat tagihan bulanan</p>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="?page=admin&menu=barang&action=add" class="quick-action-item d-block text-center">
                <div class="quick-action-icon bg-info mx-auto mb-2">
                    <i class="bi bi-box-seam"></i>
  </div>
                <div class="quick-action-content">
                    <h6>Tambah Barang</h6>
                    <p>Kelola inventaris</p>
          </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="?page=admin&menu=kamar&action=list" class="quick-action-item d-block text-center">
                <div class="quick-action-icon bg-warning mx-auto mb-2">
                    <i class="bi bi-house-door"></i>
                </div>
                <div class="quick-action-content">
                    <h6>Kelola Kamar</h6>
                    <p>Lihat status kamar</p>
                </div>
            </a>
      </div>
    </div>
  </div>

<!-- Statistics Cards -->
<div class="stats-section mb-5">
    <div class="section-header">
        <h2 class="section-title">
            <i class="bi bi-graph-up me-2"></i>
            Statistik Dashboard
        </h2>
        <p class="section-description">Ringkasan data penting sistem</p>
    </div>
    
    <div class="row g-4">
        <!-- Penghuni Aktif -->
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-card-content">
                    <h3 class="stat-card-number" data-value="<?= $penghuni_aktif ?>"><?= $penghuni_aktif ?></h3>
                    <p class="stat-card-label">Penghuni Aktif</p>
                    <div class="stat-card-progress">
                        <div class="progress">
                            <div class="progress-bar" style="width: <?= ($penghuni_aktif / max($total_kamar, 1)) * 100 ?>%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
        
        <!-- Kamar Tersedia -->
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-icon">
                    <i class="bi bi-house-door-fill"></i>
          </div>
                <div class="stat-card-content">
                    <h3 class="stat-card-number" data-value="<?= $kamar_kosong ?>"><?= $kamar_kosong ?></h3>
                    <p class="stat-card-label">Kamar Tersedia</p>
                    <div class="stat-card-progress">
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: <?= ($kamar_kosong / max($total_kamar, 1)) * 100 ?>%"></div>
        </div>
      </div>
    </div>
  </div>
</div>

        <!-- Tagihan Pending -->
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-icon">
                    <i class="bi bi-receipt-cutoff"></i>
  </div>
                <div class="stat-card-content">
                    <h3 class="stat-card-number" data-value="<?= $tagihan_pending ?>"><?= $tagihan_pending ?></h3>
                    <p class="stat-card-label">Tagihan Pending</p>
                    <div class="stat-card-progress">
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: <?= ($tagihan_pending / max($tagihan_pending + $tagihan_lunas, 1)) * 100 ?>%"></div>
    </div>
    </div>
    </div>
  </div>
</div>

        <!-- Total Barang -->
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-card-info">
                <div class="stat-card-icon">
                    <i class="bi bi-box-seam"></i>
    </div>
                <div class="stat-card-content">
                    <h3 class="stat-card-number" data-value="<?= $total_barang ?>"><?= $total_barang ?></h3>
                    <p class="stat-card-label">Total Barang</p>
                    <div class="stat-card-progress">
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 100%"></div>
      </div>
    </div>
  </div>
</div>
      </div>
    </div>
  </div>

<!-- Charts Section -->

<!-- Recent Activities -->
<div class="recent-activities-section mb-5">
    <div class="section-header">
        <h2 class="section-title">
            <i class="bi bi-clock-history me-2"></i>
            Aktivitas Terbaru
        </h2>
        <p class="section-description">Gabungan aktivitas penghuni, pembayaran, dan tagihan terbaru</p>
    </div>
    <div class="recent-activities-card">
        <div class="card-body">
            <div class="timeline-list">
                <?php if (empty($recent_activities)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2">Belum ada aktivitas</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="timeline-item mb-4 d-flex">
                            <div class="timeline-icon flex-shrink-0 me-3">
                                <span class="badge bg-<?= $activity['color'] ?>">
                                    <i class="bi bi-<?= $activity['icon'] ?>"></i>
                                </span>
                            </div>
                            <div class="timeline-content flex-grow-1">
                                <div class="mb-1">
                                    <?= htmlspecialchars($activity['desc']) ?>
                                </div>
                                <div class="text-muted small">
                                    <?= date('d-m-Y H:i', strtotime($activity['waktu'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Additional Styles -->
<style>
.welcome-section {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 30px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 30px;
}

.welcome-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark-color);
    margin: 0;
}

.welcome-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    margin: 0;
}

.text-gradient {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.current-time {
    background: var(--gradient-primary);
    color: white;
    padding: 12px 20px;
    border-radius: var(--border-radius);
    font-weight: 600;
    display: inline-block;
}

.section-header {
    margin-bottom: 25px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 8px;
}

.section-description {
    color: #6c757d;
    margin: 0;
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 25px;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
}

.stat-card-success::before {
    background: var(--gradient-success);
}

.stat-card-warning::before {
    background: var(--gradient-warning);
}

.stat-card-info::before {
    background: var(--gradient-primary);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.stat-card-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.stat-card-success .stat-card-icon {
    background: var(--gradient-success);
}

.stat-card-warning .stat-card-icon {
    background: var(--gradient-warning);
}

.stat-card-info .stat-card-icon {
    background: var(--gradient-primary);
}

.stat-card-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark-color);
    margin: 0 0 5px 0;
}

.stat-card-label {
    color: #6c757d;
    font-weight: 600;
    margin: 0 0 15px 0;
}

.stat-card-progress {
    margin-top: 15px;
}

.progress {
    height: 8px;
    border-radius: 4px;
    background: #e9ecef;
}

.progress-bar {
    border-radius: 4px;
    transition: width 0.6s ease;
}

/* Chart Cards */
.chart-card {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    transition: all 0.3s ease;
}

.chart-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.chart-card-header {
    background: white;
    padding: 20px 25px;
    border-bottom: 1px solid #e9ecef;
}

.chart-card-title {
    font-weight: 700;
    color: var(--dark-color);
    margin: 0;
}

.chart-card-body {
    padding: 20px;
}

/* Quick Actions */
.quick-actions-card {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.quick-action-item {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--dark-color);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.quick-action-item:hover {
    background: #f8f9fa;
    border-color: var(--primary-color);
    transform: translateY(-2px);
    text-decoration: none;
    color: var(--dark-color);
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-right: 15px;
}

.quick-action-content h6 {
    font-weight: 700;
    margin: 0 0 5px 0;
}

.quick-action-content p {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

/* Recent Activities */
.recent-activities-card {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.activities-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f1f3f4;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    margin-right: 15px;
}

.activity-content h6 {
    font-weight: 600;
    margin: 0 0 5px 0;
    font-size: 0.9rem;
}

.activity-content p {
    color: #6c757d;
    margin: 0 0 5px 0;
    font-size: 0.85rem;
}

.activity-content small {
    font-size: 0.75rem;
}

/* Animation for stat cards */
.stat-card {
    animation: fadeInUp 0.6s ease-out;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

/* Responsive */
@media (max-width: 768px) {
    .welcome-title {
        font-size: 2rem;
    }
    
    .stat-card-number {
        font-size: 2rem;
    }
    
    .quick-action-item {
        flex-direction: column;
        text-align: center;
    }
    
    .quick-action-icon {
        margin-right: 0;
        margin-bottom: 10px;
    }
}
.timeline-list {
    border-left: 3px solid #e9ecef;
    margin-left: 18px;
    padding-left: 18px;
}
.timeline-item {
    position: relative;
}
.timeline-icon {
    margin-left: -36px;
    margin-top: 2px;
}
.timeline-icon .badge {
    font-size: 1.1rem;
    padding: 10px 12px;
    border-radius: 50%;
}
.timeline-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
</style>

<script>
// Update current time
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) + ' ' + now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('currentTime').textContent = timeString;
}

// Update time every second
setInterval(updateTime, 1000);
updateTime(); // Initial call

// Animate statistics
function animateStatistics() {
    const statNumbers = document.querySelectorAll('.stat-card-number');
    
    statNumbers.forEach(element => {
        const targetValue = parseInt(element.getAttribute('data-value') || '0');
        const duration = 2000;
        const steps = 60;
        const increment = targetValue / steps;
        let currentValue = 0;
        let step = 0;
        
        const timer = setInterval(() => {
            step++;
            currentValue += increment;
            
            if (step >= steps) {
                element.textContent = targetValue;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(currentValue);
            }
        }, duration / steps);
    });
}

// Initialize charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Animate statistics
    setTimeout(animateStatistics, 500);
    
    // Initialize charts
    initCharts();
});

// Initialize charts
function initCharts() {
    // Kamar Status Chart
    const kamarCtx = document.getElementById('kamarChart').getContext('2d');
    new Chart(kamarCtx, {
        type: 'doughnut',
  data: {
            labels: ['Terisi', 'Kosong'],
    datasets: [{
                data: [<?= $kamar_terisi ?>, <?= $kamar_kosong ?>],
      backgroundColor: [
                    '#28a745',
                    '#6c757d'
      ],
                borderWidth: 0
    }]
  },
  options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Tagihan Status Chart
    const tagihanCtx = document.getElementById('tagihanChart').getContext('2d');
    new Chart(tagihanCtx, {
        type: 'bar',
  data: {
            labels: ['Lunas', 'Pending'],
    datasets: [{
                label: 'Jumlah Tagihan',
                data: [<?= $tagihan_lunas ?>, <?= $tagihan_pending ?>],
                backgroundColor: [
                    '#28a745',
                    '#ffc107'
                ],
                borderWidth: 0,
                borderRadius: 8
    }]
  },
  options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
  data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [{
                label: 'Pendapatan (Juta)',
                data: [15, 16, 15.5, 17, 16.5, 18],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
    }]
  },
  options: {
    responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + 'M';
                        }
                    }
                }
            }
        }
    });
}
</script> 