<section class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center">Galeri Kost</h2>
    <div class="mb-3 text-center">
      <span class="badge bg-primary filter-chip mx-1" style="cursor:pointer;">Kamar</span>
      <span class="badge bg-secondary filter-chip mx-1" style="cursor:pointer;">Fasilitas</span>
      <span class="badge bg-danger filter-chip mx-1" style="cursor:pointer;">Semua</span>
    </div>
    <?php if(empty($gallery)): ?>
      <div class="text-center my-5">
        <img src="assets/images/empty.svg" width="120" alt="Empty">
        <p class="mt-3 text-muted">Belum ada foto galeri.</p>
      </div>
    <?php else: ?>
    <div class="row g-3">
      <?php foreach($gallery as $img): ?>
      <div class="col-6 col-md-3">
        <a href="assets/images/<?= $img['file'] ?>" class="gallery-lightbox" data-kategori="<?= $img['kategori'] ?>">
          <img src="assets/images/<?= $img['file'] ?>" class="img-fluid rounded shadow-sm" alt="Galeri">
        </a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section> 