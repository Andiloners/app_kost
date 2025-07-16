// Preview kalkulasi tagihan
const previewBtn = document.getElementById('previewBtn');
const generateBtn = document.getElementById('generateBtn');
const previewResult = document.getElementById('previewResult');
const progressBarWrap = document.getElementById('progressBarWrap');
const progressBar = document.getElementById('progressBar');
if (previewBtn) {
  previewBtn.onclick = function() {
    // Simulasi preview kalkulasi
    previewResult.innerHTML = '<div class="alert alert-info">Total tagihan bulan ini: <b>Rp 2.000.000</b> (simulasi)</div>';
    generateBtn.disabled = false;
  };
}
if (generateBtn) {
  generateBtn.onclick = function(e) {
    e.preventDefault();
    progressBarWrap.classList.remove('d-none');
    let progress = 0;
    const interval = setInterval(()=>{
      progress += 10;
      progressBar.style.width = progress+'%';
      progressBar.innerText = progress+'%';
      if(progress>=100){
        clearInterval(interval);
        progressBar.innerText = 'Selesai';
        setTimeout(()=>{
          progressBarWrap.classList.add('d-none');
          progressBar.style.width = '0%';
          progressBar.innerText = '0%';
          alert('Tagihan berhasil digenerate! (simulasi)');
        }, 1000);
      }
    }, 200);
  };
}
// Export Excel (simulasi)
const exportExcel = document.getElementById('exportExcel');
if (exportExcel) {
  exportExcel.onclick = function() {
    alert('Export ke Excel (simulasi, backend pakai PHPSpreadsheet)');
  };
}
// Print
const printTagihan = document.getElementById('printTagihan');
if (printTagihan) {
  printTagihan.onclick = function() {
    window.print();
  };
}
// Notifikasi due date (simulasi)
// ... 