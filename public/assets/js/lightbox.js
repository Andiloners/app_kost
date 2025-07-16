document.querySelectorAll('.gallery-lightbox').forEach(link => {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    const img = document.createElement('img');
    img.src = this.href;
    img.className = 'img-fluid rounded shadow';
    img.style.maxWidth = '90vw';
    img.style.maxHeight = '90vh';
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.top = 0;
    overlay.style.left = 0;
    overlay.style.width = '100vw';
    overlay.style.height = '100vh';
    overlay.style.background = 'rgba(0,0,0,0.8)';
    overlay.style.display = 'flex';
    overlay.style.alignItems = 'center';
    overlay.style.justifyContent = 'center';
    overlay.style.zIndex = 2000;
    overlay.appendChild(img);
    overlay.onclick = () => document.body.removeChild(overlay);
    document.body.appendChild(overlay);
  });
}); 