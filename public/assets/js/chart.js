document.addEventListener('DOMContentLoaded', function() {
  if (window.Chart) {
    // Grafik pembayaran bulanan
    new Chart(document.getElementById('chartPembayaran'), {
      type: 'line',
      data: { labels: ['Jan','Feb','Mar','Apr','Mei','Jun'], datasets: [{ label: 'Pembayaran', data: [2,3,4,5,3,4], borderColor: '#0d6efd', fill: false }] },
      options: { responsive: true }
    });
    // Pie chart status pembayaran
    new Chart(document.getElementById('chartStatus'), {
      type: 'pie',
      data: { labels: ['Lunas','Belum Bayar','Cicil'], datasets: [{ data: [10,3,2], backgroundColor: ['#198754','#dc3545','#ffc107'] }] },
      options: { responsive: true }
    });
    // Bar chart occupancy
    new Chart(document.getElementById('chartOccupancy'), {
      type: 'bar',
      data: { labels: ['Kosong','Terisi'], datasets: [{ label: 'Occupancy', data: [5,15], backgroundColor: ['#0d6efd','#198754'] }] },
      options: { responsive: true }
    });
  }
}); 