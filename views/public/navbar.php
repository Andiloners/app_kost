<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Kost Andi</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link smooth-scroll" href="#kamarTersedia">Kamar</a></li>
        <li class="nav-item"><a class="nav-link smooth-scroll" href="#contact">Kontak</a></li>
        <li class="nav-item"><a class="nav-link" href="#" role="button" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formLogin" method="post" action="" autocomplete="off">
        <div class="modal-body">
          <div class="mb-3">
            <label for="loginUsername" class="form-label">Username</label>
            <input type="text" class="form-control" id="loginUsername" name="username" required autofocus autocomplete="off">
          </div>
          <div class="mb-3">
            <label for="loginPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="loginPassword" name="password" required autocomplete="off">
          </div>
          <!-- <div id="loginError" class="alert alert-danger mb-2<?= (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($login_error)) ? '' : ' d-none' ?>">
            <?= (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($login_error)) ? htmlspecialchars($login_error) : '' ?>
          </div> -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">Login</button>
        </div>
      </form>
    </div>
  </div>
</div> 