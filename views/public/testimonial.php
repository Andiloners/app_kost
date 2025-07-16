<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4 text-center">Testimoni Penghuni</h2>
    <?php if(empty($testimonials)): ?>
      <div class="text-center my-5">
        <img src="assets/images/empty.svg" width="120" alt="Empty">
        <p class="mt-3 text-muted">Belum ada testimoni.</p>
      </div>
    <?php else: ?>
    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach($testimonials as $i => $t): ?>
        <div class="carousel-item<?= $i==0?' active':'' ?>">
          <div class="d-flex flex-column align-items-center">
            <img src="assets/images/<?= $t['foto'] ?>" class="rounded-circle mb-3" width="80" height="80" alt="Testimoni">
            <blockquote class="blockquote text-center">
              <p class="mb-0"><?= $t['pesan'] ?></p>
              <footer class="blockquote-footer"><?= $t['nama'] ?></footer>
              <div class="text-warning mt-2">
                <?php for($b=0;$b<5;$b++): ?>
                  <i class="fa fa-star<?= $b<($t['rating']??5)?'':'-o' ?>"></i>
                <?php endfor; ?>
              </div>
            </blockquote>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
    <?php endif; ?>
  </div>
</section> 