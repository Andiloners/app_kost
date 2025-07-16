<?php
// views/admin/tagihan_generate.php
$title = 'Generate Tagihan';
$active = 'tagihan';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Generate Tagihan</h2>
    <a href="?page=admin&menu=tagihan&action=list" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form id="generateTagihanForm" method="POST" action="">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select class="form-select" name="bulan" required>
                        <?php foreach(range(1,12) as $b): ?>
                            <option value="<?= $b ?>" <?= $b == date('n') ? 'selected' : '' ?>>
                                <?= date('F', mktime(0,0,0,$b,1)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" name="tahun" required>
                        <?php for($y=date('Y')-2;$y<=date('Y')+1;$y++): ?>
                            <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-info" id="previewBtn">
                        <i class="bi bi-eye me-2"></i>Preview
                    </button>
                    <button type="submit" class="btn btn-success" id="generateBtn">
                        <i class="bi bi-check-circle me-2"></i>Generate
                    </button>
                </div>
            </div>
            <div id="previewResult" class="mt-4"></div>
            <div class="progress mt-3 d-none" id="progressBarWrap">
                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar" style="width:0%">0%</div>
            </div>
        </form>
    </div>
</div>

<div class="alert alert-info mt-3">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Petunjuk:</strong> Generate tagihan akan membuat tagihan untuk semua penghuni aktif pada periode yang dipilih. 
    Tagihan yang sudah ada untuk periode yang sama tidak akan dibuat ulang.
</div> 