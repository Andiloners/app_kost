<?php
// views/admin/barang_list.php
$title = 'Daftar Barang';
$active = 'barang';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-box-seam me-2"></i>Daftar Barang</h2>
    <a href="?page=admin&menu=barang&action=add" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Barang
    </a>
</div>

<?php if (!empty($barang)): ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Kamar</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($barang as $b): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($b['nama']) ?></td>
                                <td><?= htmlspecialchars($b['jumlah']) ?></td>
                                <td>
                                    <?php if (!empty($b['kamar_nomor'])): ?>
                                        <span class="badge bg-info"><?= htmlspecialchars($b['kamar_nomor']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($b['keterangan'])): ?>
                                        <?= htmlspecialchars($b['keterangan']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= format_tanggal($b['created_at'] ?? '') ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="?page=admin&menu=barang&action=edit&id=<?= $b['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="?page=admin&menu=barang&action=delete&id=<?= $b['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Belum ada data barang. Silakan tambah barang baru.
    </div>
<?php endif; ?> 