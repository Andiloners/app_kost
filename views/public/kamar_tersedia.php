<section id="kamarTersedia" class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4 text-center">Kamar Tersedia</h2>
    <div class="mb-3 text-center">
      <?php foreach($filter_chips as $chip): ?>
        <span class="badge bg-primary filter-chip mx-1" style="cursor:pointer;"> <?= $chip ?> <i class="fa fa-times ms-1"></i></span>
      <?php endforeach; ?>
      <span class="badge bg-danger filter-chip mx-1" style="cursor:pointer;">Reset</span>
    </div>
    <?php if(empty($kamar_kosong)): ?>
      <div class="text-center my-5">
        <img src="assets/images/empty.svg" width="120" alt="Empty">
        <p class="mt-3 text-muted">Tidak ada kamar tersedia.</p>
      </div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach($kamar_kosong as $kamar): ?>
      <div class="col-md-4">
        <div class="card kamar-card h-100 shadow-sm">
          <img src="assets/images/<?= $kamar['foto'] ?>" class="card-img-top gallery-img" alt="Kamar <?= $kamar['nomor'] ?>">
          <div class="card-body">
            <h5 class="card-title">Kamar <?= $kamar['nomor'] ?> <span class="badge bg-secondary ms-2"><?= $kamar['tipe'] ?></span></h5>
            <span class="badge bg-success fs-6 mb-2">Rp <?= number_format($kamar['harga'],0,',','.') ?>/bulan</span>
            <div class="mb-2">
              <?php foreach(explode(', ',$kamar['fasilitas']) as $f): ?>
                <span class="badge bg-info text-dark"> <?= $f ?> </span>
              <?php endforeach; ?>
            </div>
            <button class="btn btn-outline-primary btn-sm" 
              data-nomor="<?= $kamar['nomor'] ?>"
              data-harga="<?= number_format($kamar['harga'],0,',','.') ?>"
              data-tipe="<?= $kamar['tipe'] ?>"
              data-fasilitas="<?= htmlspecialchars($kamar['fasilitas']) ?>"
              data-foto="assets/images/<?= $kamar['foto'] ?>"
              data-status="<?= $kamar['status'] ?? 'kosong' ?>"
              onclick="showDetailKamar(this)">Detail</button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- Modal Detail Kamar -->
<div class="modal fade" id="modalDetailKamar" tabindex="-1" aria-labelledby="modalDetailKamarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetailKamarLabel">Detail Kamar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modalKamarFoto" src="" class="img-fluid rounded mb-3" alt="Foto Kamar">
        <ul class="list-group mb-2">
          <li class="list-group-item"><b>Nomor:</b> <span id="modalKamarNomor"></span></li>
          <li class="list-group-item"><b>Tipe:</b> <span id="modalKamarTipe"></span></li>
          <li class="list-group-item"><b>Harga:</b> Rp <span id="modalKamarHarga"></span>/bulan</li>
          <li class="list-group-item"><b>Fasilitas:</b> <span id="modalKamarFasilitas"></span></li>
          <li class="list-group-item"><b>Status:</b> <span id="modalKamarStatus"></span></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btnBookingKamar">Booking Kamar</button>
      </div>
    </div>
  </div>
</div> 