<?php
// views/admin/tagihan_list.php
$title = 'Daftar Tagihan';
$active = 'tagihan';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <?php if (isset($is_pembayaran_menu) && $is_pembayaran_menu): ?>
        <h2 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Kelola Pembayaran</h2>
        <div class="text-muted ms-2">Menu ini untuk proses pembayaran tagihan penghuni.</div>
    <?php else: ?>
        <h2 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>Kelola Tagihan</h2>
        <div class="text-muted ms-2">Menu ini untuk monitoring dan penagihan tagihan kost.</div>
    <?php endif; ?>
    <div>
        <?php if (!isset($is_pembayaran_menu) || !$is_pembayaran_menu): ?>
        <a href="?page=admin&menu=tagihan&action=generate" class="btn btn-success">
            <i class="bi bi-plus-circle me-2"></i>Generate Tagihan
        </a>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($is_pembayaran_menu) && $is_pembayaran_menu): ?>
<div class="alert alert-info mb-3"><i class="bi bi-info-circle me-2"></i>Menu ini hanya menampilkan tagihan yang belum lunas/cicil. Klik <b>Bayar</b> untuk proses pembayaran.</div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Tagihan</h5>
        <div>
            <select id="filterStatus" class="form-select d-inline w-auto">
                <option value="">Semua Status</option>
                <option value="lunas">Lunas</option>
                <option value="belum_bayar">Belum Bayar</option>
                <option value="cicil">Cicil</option>
            </select>
            <input type="month" id="filterPeriode" class="form-control d-inline w-auto">
            <button class="btn btn-outline-success" id="exportExcel"><i class="bi bi-file-earmark-excel"></i></button>
            <button class="btn btn-outline-primary" id="printTagihan"><i class="bi bi-printer"></i></button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tagihanTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Nama</th>
                    <th>Kamar</th>
                    <th>Periode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Jatuh Tempo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tagihan)): ?>
                    <?php foreach($tagihan as $t): ?>
                    <tr>
                        <td><input type="checkbox" class="selectTagihan" value="<?= $t['id'] ?>"></td>
                        <td><?= htmlspecialchars($t['penghuni_nama']) ?></td>
                        <td><?= htmlspecialchars($t['kamar_nomor']) ?></td>
                        <td><?= $t['periode_bulan'].'/'.$t['periode_tahun'] ?></td>
                        <td><?= format_rupiah($t['jumlah']) ?></td>
                        <td>
                            <?php if($t['status']=='lunas'): ?>
                                <span class="badge bg-success">Lunas</span>
                            <?php elseif($t['status']=='belum_bayar'): ?>
                                <span class="badge bg-danger">Belum Bayar</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Cicil</span>
                            <?php endif; ?>
                        </td>
                        <td><?= format_tanggal($t['tgl_jatuh_tempo']) ?></td>
                        <td>
                            <?php if (isset($is_pembayaran_menu) && $is_pembayaran_menu): ?>
                                <?php if($t['status'] != 'lunas'): ?>
                                    <a href="?page=admin&menu=tagihan&action=bayar&id=<?= $t['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-cash-coin"></i> Bayar
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                            <?php if (!isset($is_pembayaran_menu) || !$is_pembayaran_menu): ?>
                                <a href="?page=admin&menu=tagihan&action=delete&id=<?= $t['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus tagihan ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada data tagihan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 
<script>
document.getElementById('printTagihan').onclick = function() {
    var checked = document.querySelector('.selectTagihan:checked');
    if (!checked) {
        alert('Pilih satu tagihan yang ingin dicetak resinya!');
        return;
    }
    var id = checked.value;
    window.open('?page=admin&menu=tagihan&action=receipt&id=' + id, '_blank');
};
document.getElementById('selectAll').onclick = function() {
    var checkboxes = document.querySelectorAll('.selectTagihan');
    checkboxes.forEach(cb => cb.checked = this.checked);
};
</script> 