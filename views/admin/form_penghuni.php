<?php
// views/admin/form_penghuni.php
$is_edit = isset($penghuni['id']);
$title = $is_edit ? 'Edit Penghuni' : 'Tambah Penghuni';
$active = 'penghuni';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-person-plus me-2"></i>
        <?= $is_edit ? 'Edit Penghuni' : 'Tambah Penghuni' ?>
    </h2>
    <a href="?page=admin&menu=penghuni&action=list" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="" class="needs-validation" novalidate>
            <div class="row g-4">
                <!-- Nama Lengkap -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-person-fill text-primary me-2"></i>
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <input type="text" class="form-control" name="nama" 
                                   placeholder="Masukkan nama lengkap penghuni sesuai KTP" 
                                   value="<?= $is_edit ? htmlspecialchars($penghuni['nama']) : '' ?>" 
                                   required>
                        </div>
                        <div class="form-text">Masukkan nama lengkap penghuni sesuai KTP</div>
                    </div>
                </div>
                
                <!-- No KTP -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-credit-card-2-front text-primary me-2"></i>
                            No KTP <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-credit-card-2-front"></i>
                            </span>
                            <input type="text" class="form-control" name="no_ktp" 
                                   placeholder="Masukkan 16 digit nomor KTP" 
                                   value="<?= $is_edit ? htmlspecialchars($penghuni['no_ktp']) : '' ?>" 
                                   required maxlength="16">
                        </div>
                        <div class="form-text">Masukkan 16 digit nomor KTP penghuni</div>
                    </div>
                </div>
                
                <!-- No HP -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-telephone-fill text-primary me-2"></i>
                            No HP <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-telephone-fill"></i>
                            </span>
                            <input type="text" class="form-control" name="no_hp" 
                                   placeholder="Masukkan nomor HP aktif penghuni" 
                                   value="<?= $is_edit ? htmlspecialchars($penghuni['no_hp']) : '' ?>" 
                                   required maxlength="15">
                        </div>
                        <div class="form-text">Masukkan nomor HP aktif penghuni</div>
                    </div>
                </div>
                
                <!-- Kamar -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-house-door-fill text-primary me-2"></i>
                            Pilih Kamar
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-house-door-fill"></i>
                            </span>
                            <select class="form-select" name="kamar_id">
                                <option value="">Pilih Kamar</option>
                                <?php foreach ($kamar_list as $kamar): ?>
                                    <option value="<?= $kamar['id'] ?>" 
                                            <?= ($is_edit && $penghuni['kamar_id'] == $kamar['id']) ? 'selected' : '' ?>>
                                        Kamar <?= htmlspecialchars($kamar['nomor']) ?> - 
                                        <?= htmlspecialchars($kamar['status']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-text">Pilih kamar yang akan ditempati penghuni</div>
                    </div>
                </div>
                
                <!-- Tanggal Masuk -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-calendar-event-fill text-primary me-2"></i>
                            Tanggal Masuk
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar-event-fill"></i>
                            </span>
                            <input type="date" class="form-control" name="tgl_masuk" 
                                   value="<?= $is_edit ? htmlspecialchars($penghuni['tgl_masuk']) : '' ?>">
                        </div>
                        <div class="form-text">Isi tanggal masuk penghuni</div>
                    </div>
                </div>
                
                <!-- Tanggal Keluar (Optional, hanya edit) -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-calendar-x-fill text-primary me-2"></i>
                            Tanggal Keluar (Opsional)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar-x-fill"></i>
                            </span>
                            <input type="date" class="form-control" name="tgl_keluar" 
                                   value="<?= $is_edit ? htmlspecialchars($penghuni['tgl_keluar'] ?? '') : '' ?>">
                        </div>
                        <div class="form-text">Kosongkan jika penghuni masih aktif</div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions mt-5">
                <div class="d-flex justify-content-end gap-3">
                    <a href="?page=admin&menu=penghuni&action=list" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= $is_edit ? 'Update' : 'Simpan' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Form Validation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
    
    // Input validation for KTP (numbers only)
    const ktpInput = document.querySelector('input[name="no_ktp"]');
    ktpInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 16);
    });
    
    // Input validation for HP (numbers only)
    const hpInput = document.querySelector('input[name="no_hp"]');
    hpInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 15);
    });
});
</script>

<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.input-group-text {
    background: var(--light-color);
    border-color: #e9ecef;
    color: var(--primary-color);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.form-actions {
    border-top: 1px solid #e9ecef;
    padding-top: 2rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
</style> 