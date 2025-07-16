<?php
$is_edit = isset($kamar['id']);
$title = $is_edit ? 'Edit Kamar' : 'Tambah Kamar Baru';
?>
<!-- Header Ungu -->
<div class="mb-4" style="border-radius:18px 18px 0 0;overflow:hidden;">
  <div class="d-flex align-items-center gap-2 px-4 py-3" style="background:linear-gradient(90deg,#7b6cf6 0%,#6a5acd 100%);">
    <i class="bi bi-plus-lg" style="font-size:1.5rem;color:#fff;"></i>
    <span style="font-size:1.3rem;font-weight:700;color:#fff;"> <?= $title ?> </span>
  </div>
</div>
<!-- Card Form -->
<div class="card shadow-sm" style="border-radius:0 0 18px 18px;border:none;">
  <div class="card-body p-4">
    <form method="POST" action="?page=admin&menu=kamar&action=<?= $is_edit ? 'edit&id='.$kamar['id'] : 'add' ?>">
      <!-- Nomor Kamar -->
      <div class="mb-3">
        <label class="form-label fw-semibold"><i class="bi bi-house-door-fill text-primary me-1"></i> Nomor Kamar</label>
        <div class="input-group mb-1">
          <span class="input-group-text bg-white border-end-0"><i class="bi bi-house-door-fill text-primary"></i></span>
          <input type="text" class="form-control border-start-0" name="nomor" placeholder="Contoh: A1, B2, C3" value="<?= $is_edit ? htmlspecialchars($kamar['nomor']) : '' ?>" required style="border-radius:0 8px 8px 0;">
        </div>
        <div class="form-text ms-1">Contoh: A1, B2, C3</div>
      </div>
      <!-- Harga Sewa -->
      <div class="mb-3">
        <label class="form-label fw-semibold"><i class="bi bi-cash-coin text-primary me-1"></i> Harga Sewa (per bulan)</label>
        <div class="input-group mb-1">
          <span class="input-group-text bg-white border-end-0">Rp</span>
          <input type="number" class="form-control border-start-0" name="harga" placeholder="500000" value="<?= $is_edit ? htmlspecialchars($kamar['harga']) : '' ?>" required min="0" style="border-radius:0 8px 8px 0;">
        </div>
      </div>
      <!-- Tombol -->
      <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold d-flex align-items-center" style="border-radius:10px;font-size:1.1rem;background:linear-gradient(90deg,#7b6cf6 0%,#6a5acd 100%);border:none;">
          <i class="bi bi-save me-2"></i> Simpan
        </button>
        <a href="?page=admin&menu=kamar&action=list" class="btn btn-light px-4 py-2 fw-bold d-flex align-items-center" style="border-radius:10px;font-size:1.1rem;border:1.5px solid #bdbdbd;">
          <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
      </div>
    </form>
  </div>
</div> 