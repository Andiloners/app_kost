<?php
// views/admin/penghuni_list.php
$title = 'Kelola Penghuni';
$active = 'penghuni';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="bi bi-people-fill me-2"></i>
            Daftar Penghuni
        </h2>
        <p class="text-muted mb-0">Kelola data penghuni kost</p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="input-group" style="min-width: 300px;">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" id="searchBox" placeholder="Cari penghuni...">
        </div>
        <a href="?page=admin&menu=penghuni&action=add" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Tambah Penghuni
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Data Penghuni (<?= count($penghuni) ?> orang)
            </h5>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" onclick="exportToExcel('penghuniTable', 'penghuni')">
                    <i class="bi bi-file-earmark-excel me-1"></i>Excel
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="printElement('penghuniTable')">
                    <i class="bi bi-printer me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="penghuniTable">
                <thead>
                    <tr>
                        <th data-sortable="true">No</th>
                        <th data-sortable="true">Nama</th>
                        <th data-sortable="true">No KTP</th>
                        <th data-sortable="true">No HP</th>
                        <th data-sortable="true">Kamar</th>
                        <th data-sortable="true">Tanggal Masuk</th>
                        <th data-sortable="true">Tanggal Keluar</th>
                        <th data-sortable="true">Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($penghuni)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    <h5>Belum ada data penghuni</h5>
                                    <p>Silakan tambahkan penghuni pertama</p>
                                    <a href="?page=admin&menu=penghuni&action=add" class="btn btn-primary">
                                        <i class="bi bi-person-plus me-2"></i>Tambah Penghuni
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($penghuni as $i => $p): ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <i class="bi bi-person-circle"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($p['nama']) ?></div>
                                            <small class="text-muted">ID: <?= $p['id'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code><?= htmlspecialchars($p['no_ktp']) ?></code>
                                </td>
                                <td>
                                    <a href="tel:<?= htmlspecialchars($p['no_hp']) ?>" class="text-decoration-none">
                                        <i class="bi bi-telephone me-1"></i>
                                        <?= htmlspecialchars($p['no_hp']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="bi bi-house-door me-1"></i>
                                        Kamar <?= htmlspecialchars($p['kamar_nomor'] ?? '-') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-check text-success me-2"></i>
                                        <?= date('d/m/Y', strtotime($p['tgl_masuk'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($p['tgl_keluar']): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-x text-danger me-2"></i>
                                            <?= date('d/m/Y', strtotime($p['tgl_keluar'])) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!$p['tgl_keluar']): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x-circle me-1"></i>Keluar
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="?page=admin&menu=penghuni&action=edit&id=<?= $p['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="?page=admin&menu=penghuni&action=delete&id=<?= $p['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirmDelete('<?= htmlspecialchars($p['nama']) ?>')"
                                           title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchBox').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.querySelector('table tbody');
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Confirm delete
function confirmDelete(nama) {
    return confirm(`Yakin ingin menghapus penghuni "${nama}"?`);
}

// Export to Excel
function exportToExcel(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) {
        alert('Tabel tidak ditemukan');
        return;
    }
    
    // Create a new table for export
    const exportTable = table.cloneNode(true);
    
    // Remove action column
    const actionHeader = exportTable.querySelector('thead th:last-child');
    if (actionHeader) actionHeader.remove();
    
    const actionCells = exportTable.querySelectorAll('tbody td:last-child');
    actionCells.forEach(cell => cell.remove());
    
    // Convert to worksheet
    const ws = XLSX.utils.table_to_sheet(exportTable);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Penghuni');
    XLSX.writeFile(wb, `${filename}_${new Date().toISOString().split('T')[0]}.xlsx`);
}

// Print element
function printElement(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        alert('Element tidak ditemukan');
        return;
    }
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Daftar Penghuni</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    @media print {
                        .btn-group { display: none !important; }
                        .input-group { display: none !important; }
                    }
                </style>
            </head>
            <body>
                <div class="container-fluid p-4">
                    <h3 class="mb-4">Daftar Penghuni Kost</h3>
                    ${element.outerHTML}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>

<style>
.avatar {
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: scale(1.01);
}

.btn-group .btn {
    border-radius: 6px;
    margin: 0 2px;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.9rem;
}

.badge {
    font-size: 0.8rem;
    padding: 6px 10px;
}

.card-header {
    background: white;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .input-group {
        min-width: 100% !important;
    }
}
</style> 