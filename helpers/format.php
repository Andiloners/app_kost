<?php
function format_tanggal($tanggal) {
    return date('d-m-Y', strtotime($tanggal));
}

function format_rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

 