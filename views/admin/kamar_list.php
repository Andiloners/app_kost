<?php
// views/admin/kamar_list.php
$title = 'Kelola Kamar';
$active = 'kamar';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-door-closed-fill me-2"></i>Kelola Kamar</h2>
    <a href="?page=admin&menu=kamar&action=add" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Kamar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nomor Kamar</th>
                        <th>Harga Sewa</th>
                        <th>Status</th>
                        <th>Penghuni</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
      <tbody>
        <?php foreach ($kamars as $i => $kamar): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><i class="bi bi-house-door-fill text-primary me-1"></i><b><?= htmlspecialchars($kamar['nomor']) ?></b></td>
          <td><?= format_rupiah($kamar['harga']) ?></td>
          <td>
            <?php if ($kamar['status'] === 'kosong'): ?>
              <span class="badge" style="background:linear-gradient(90deg,#7b6cf6 0%,#6a5acd 100%);color:#fff;">Kosong</span>
            <?php else: ?>
              <span class="badge bg-success">Terisi</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($kamar['status'] === 'kosong'): ?>
              <span class="badge" style="background:linear-gradient(90deg,#7b6cf6 0%,#6a5acd 100%);color:#fff;">Kosong</span>
            <?php else: ?>
              <?php if (!empty($kamar['penghuni_nama'])): ?>
                <span class="badge bg-info"><?= htmlspecialchars($kamar['penghuni_nama']) ?></span>
              <?php else: ?>
                <span class="badge bg-warning text-dark">Terisi</span>
              <?php endif; ?>
            <?php endif; ?>
          </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="?page=admin&menu=kamar&action=edit&id=<?= $kamar['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="?page=admin&menu=kamar&action=delete&id=<?= $kamar['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Yakin ingin menghapus kamar ini?')">
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