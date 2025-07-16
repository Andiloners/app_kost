<?php
// views/admin/pembayaran_form.php
$title = 'Pembayaran Tagihan';
$active = 'tagihan';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Pembayaran Tagihan</h2>
    <a href="?page=admin&menu=tagihan&action=list" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detail Tagihan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama Penghuni:</strong></td>
                                <td><?= htmlspecialchars($tagihan['penghuni_nama']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kamar:</strong></td>
                                <td><?= htmlspecialchars($tagihan['kamar_nomor']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Periode:</strong></td>
                                <td><?= $tagihan['periode_bulan'] ?>/<?= $tagihan['periode_tahun'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Jumlah Tagihan:</strong></td>
                                <td><span class="badge bg-primary fs-6"><?= format_rupiah($tagihan['jumlah']) ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Jatuh Tempo:</strong></td>
                                <td><?= format_tanggal($tagihan['tgl_jatuh_tempo']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <?php if($tagihan['status']=='lunas'): ?>
                                        <span class="badge bg-success">Lunas</span>
                                    <?php elseif($tagihan['status']=='belum_bayar'): ?>
                                        <span class="badge bg-danger">Belum Bayar</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Cicil</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Pembayaran</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                  <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="jumlah_bayar" class="form-label">Jumlah Pembayaran <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" 
                               value="<?= $tagihan['jumlah'] ?>" min="0" max="<?= $tagihan['jumlah'] ?>" required
                               placeholder="Contoh: 500000">
                        <div class="form-text">Maksimal: <?= format_rupiah($tagihan['jumlah']) ?>. Masukkan angka tanpa titik/koma.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                  placeholder="Catatan pembayaran (opsional)"><?= htmlspecialchars($tagihan['keterangan'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Proses Pembayaran
                        </button>
                        <a href="?page=admin&menu=tagihan&action=list" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
<?php if (!empty($history)): ?>
<div class="card mt-4">
    <div class="card-header">
        <h6 class="mb-0">Riwayat Pembayaran</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($history as $h): ?>
                <tr>
                    <td><?= format_tanggal($h['tanggal']) ?></td>
                    <td><?= format_rupiah($h['jumlah']) ?></td>
                    <td><?= htmlspecialchars($h['keterangan']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?> 
<script>
document.addEventListener('DOMContentLoaded', function() {
  var jumlahInput = document.getElementById('jumlah_bayar');
  if (jumlahInput) {
    jumlahInput.addEventListener('input', function(e) {
      // Hapus karakter selain angka
      this.value = this.value.replace(/[^0-9]/g, '');
    });
  }
});
</script> 