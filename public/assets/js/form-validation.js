// Validasi form
const form = document.getElementById('penghuniForm');
if (form) {
  form.addEventListener('submit', function(e) {
    let valid = true;
    form.querySelectorAll('input,textarea').forEach(input => {
      if (!input.checkValidity()) {
        input.classList.add('is-invalid');
        valid = false;
      } else {
        input.classList.remove('is-invalid');
      }
    });
    if (!valid) {
      e.preventDefault();
      return false;
    }
    // Simulasi sukses
    e.preventDefault();
    document.getElementById('formSuccess').classList.remove('d-none');
    setTimeout(()=>{
      document.getElementById('formSuccess').classList.add('d-none');
      form.reset();
    }, 2000);
  });
} 