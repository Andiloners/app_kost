<?php
require_once __DIR__ . '/../../helpers/format.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Pembayaran Tagihan</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
        .receipt-container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 32px 28px 24px 28px;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .receipt-logo {
            font-size: 2.2rem;
            color: #667eea;
            margin-bottom: 8px;
        }
        .receipt-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .receipt-divider {
            border: none;
            border-top: 2px dashed #667eea;
            margin: 18px 0 20px 0;
        }
        .receipt-table {
            width: 100%;
            font-size: 1rem;
            margin-bottom: 18px;
        }
        .receipt-table td {
            padding: 6px 0;
        }
        .receipt-label {
            color: #888;
            width: 40%;
        }
        .receipt-value {
            font-weight: 500;
            color: #222;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.95rem;
            color: #888;
        }
        .btn-print {
            display: inline-block;
            margin: 18px auto 0 auto;
            background: #667eea;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 28px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-print:hover {
            background: #4b5fc1;
        }
        @media print {
            body * {
                visibility: hidden !important;
            }
            .receipt-print-area, .receipt-print-area * {
                visibility: visible !important;
            }
            .receipt-print-area {
                position: absolute !important;
                left: 0; top: 0; width: 100vw; min-height: 100vh;
                background: #fff !important;
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .btn-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="receipt-container receipt-print-area">
        <div class="receipt-header">
            <div class="receipt-logo">
                <i class="bi bi-house-door-fill"></i>
            </div>
            <div class="receipt-title">Kost Bilqis Jaya Laksana</div>
            <div>Jl. Contoh Alamat No. 123, Kota</div>
            <div style="font-size:0.95rem; color:#888;">Resi Pembayaran Tagihan</div>
        </div>
        <hr class="receipt-divider">
        <table class="receipt-table">
            <tr>
                <td class="receipt-label">Nama Penghuni</td>
                <td class="receipt-value"><?= htmlspecialchars($tagihan['penghuni_nama'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="receipt-label">No. HP</td>
                <td class="receipt-value"><?= htmlspecialchars($tagihan['no_hp'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="receipt-label">Alamat</td>
                <td class="receipt-value"><?= htmlspecialchars($tagihan['alamat'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="receipt-label">Kamar</td>
                <td class="receipt-value">No. <?= htmlspecialchars($tagihan['kamar_nomor'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="receipt-label">Periode</td>
                <td class="receipt-value">
                    <?= sprintf('%02d', $tagihan['periode_bulan'] ?? 0) ?>/<?= $tagihan['periode_tahun'] ?? '-' ?>
                </td>
            </tr>
            <tr>
                <td class="receipt-label">Jumlah Tagihan</td>
                <td class="receipt-value"><?= format_rupiah($tagihan['jumlah'] ?? 0) ?></td>
            </tr>
            <tr>
                <td class="receipt-label">Status</td>
                <td class="receipt-value">
                    <?= ucfirst($tagihan['status'] ?? '-') ?>
                </td>
            </tr>
            <tr>
                <td class="receipt-label">Tanggal Bayar</td>
                <td class="receipt-value">
                    <?= !empty($tagihan['tgl_bayar']) ? date('d-m-Y', strtotime($tagihan['tgl_bayar'])) : '-' ?>
                </td>
            </tr>
            <tr>
                <td class="receipt-label">Keterangan</td>
                <td class="receipt-value">
                    <?= htmlspecialchars($tagihan['keterangan'] ?? '-') ?>
                </td>
            </tr>
        </table>
        <?php if (isset($history) && !empty($history)): ?>
        <hr class="receipt-divider">
        <div style="font-weight:600; margin-bottom:6px;">Riwayat Pembayaran</div>
        <table class="receipt-table">
            <tr>
                <th style="text-align:left;">Tanggal</th>
                <th style="text-align:left;">Jumlah</th>
                <th style="text-align:left;">Keterangan</th>
            </tr>
            <?php foreach($history as $h): ?>
            <tr>
                <td><?= date('d-m-Y', strtotime($h['tanggal'])) ?></td>
                <td><?= format_rupiah($h['jumlah']) ?></td>
                <td><?= htmlspecialchars($h['keterangan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
        <button class="btn-print" onclick="window.print()">
            <i class="bi bi-printer me-2"></i> Print Resi
        </button>
        <div class="receipt-footer">
            Dicetak pada: <?= date('d-m-Y H:i') ?>
        </div>
    </div>
    <!-- Bootstrap Icons CDN for logo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
</body>
</html> 