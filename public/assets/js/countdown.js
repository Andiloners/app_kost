document.querySelectorAll('.countdown').forEach(function(el) {
  const deadline = new Date(el.dataset.deadline).getTime();
  function update() {
    const now = new Date().getTime();
    let diff = Math.floor((deadline - now) / 1000);
    if (diff < 0) {
      el.innerText = 'Sudah lewat';
      return;
    }
    const hari = Math.floor(diff / 86400);
    diff %= 86400;
    const jam = Math.floor(diff / 3600);
    diff %= 3600;
    const menit = Math.floor(diff / 60);
    const detik = diff % 60;
    el.innerText = `${hari}h ${jam}j ${menit}m ${detik}s`;
  }
  update();
  setInterval(update, 1000);
}); 