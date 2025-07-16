<?php
// views/admin/form_barang.php
$is_edit = isset($barang['id']);
$title = $is_edit ? 'Edit Barang' : 'Tambah Barang';
$active = 'barang';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-box-seam me-2"></i>
        <?= $is_edit ? 'Edit Barang' : 'Tambah Barang' ?>
    </h2>
    <a href="?page=admin&menu=barang&action=list" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               value="<?= htmlspecialchars($barang['nama'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" 
                               value="<?= htmlspecialchars($barang['jumlah'] ?? 1) ?>" min="1" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="kamar_id" class="form-label">Kamar (Opsional)</label>
                        <select class="form-select" id="kamar_id" name="kamar_id">
                            <option value="">Pilih Kamar</option>
                            <?php foreach ($kamar_list as $kamar): ?>
                                <option value="<?= $kamar['id'] ?>" 
                                        <?= ($barang['kamar_id'] ?? '') == $kamar['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kamar['nomor']) ?> - <?= format_rupiah($kamar['harga']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= htmlspecialchars($barang['keterangan'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="?page=admin&menu=barang&action=list" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= $is_edit ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div> 