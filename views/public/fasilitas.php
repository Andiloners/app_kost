<section class="py-5 bg-white">
  <div class="container">
    <h2 class="mb-4 text-center">Fasilitas Unggulan</h2>
    <div class="row g-4 justify-content-center">
      <?php foreach($fasilitas_unggulan as $f): ?>
      <div class="col-6 col-md-2">
        <div class="card text-center shadow-sm p-3">
          <i class="fa <?= $f['icon'] ?> fa-2x mb-2 text-primary"></i>
          <div><?= $f['nama'] ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section> 