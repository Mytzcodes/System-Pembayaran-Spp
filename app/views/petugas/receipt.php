<main class="main-content">
    <div class="container">
        <div class="receipt-container">
            <div class="receipt-header">
                <h1>BUKTI PEMBAYARAN SPP</h1>
                <p>Sistem Pembayaran SPP Sekolah</p>
            </div>
            
            <?php if ($payment): ?>
            <div class="receipt-body">
                <div class="receipt-info">
                    <div class="info-row">
                        <span class="label">No. Transaksi:</span>
                        <span class="value"><?= str_pad($payment['id'], 8, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Tanggal:</span>
                        <span class="value"><?= date('d F Y H:i', strtotime($payment['tgl_bayar'])) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">NISN:</span>
                        <span class="value"><?= htmlspecialchars($payment['nisn']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Nama Siswa:</span>
                        <span class="value"><?= htmlspecialchars($payment['nama_siswa']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Periode:</span>
                        <span class="value">Bulan <?= $payment['bulan_dibayar'] ?> Tahun <?= $payment['tahun_dibayar'] ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Petugas:</span>
                        <span class="value"><?= htmlspecialchars($payment['nama_petugas']) ?></span>
                    </div>
                </div>
                
                <div class="receipt-amount">
                    <div class="amount-label">Jumlah Dibayar</div>
                    <div class="amount-value">Rp <?= number_format($payment['jumlah_bayar']) ?></div>
                </div>
                
                <div class="receipt-qr">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <rect width="100" height="100" fill="white"/>
                        <text x="50" y="50" text-anchor="middle" font-size="10">QR Code</text>
                        <text x="50" y="65" text-anchor="middle" font-size="8"><?= $payment['id'] ?></text>
                    </svg>
                </div>
            </div>
            
            <div class="receipt-footer">
                <p>Terima kasih atas pembayaran Anda</p>
                <p class="small">Bukti pembayaran ini sah dan diproses oleh sistem</p>
            </div>
            
            <div class="receipt-actions no-print">
                <button onclick="printReceipt()" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </button>
                <a href="<?= url('pembayaran/create') ?>" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
            <?php else: ?>
            <div class="alert alert-error">Pembayaran tidak ditemukan</div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.receipt-container {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-md);
}

.receipt-header {
    text-align: center;
    border-bottom: 2px solid var(--primary);
    padding-bottom: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.receipt-info {
    margin-bottom: var(--spacing-lg);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid var(--border);
}

.info-row .label {
    font-weight: 600;
}

.receipt-amount {
    background: var(--primary);
    color: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius-sm);
    text-align: center;
    margin: var(--spacing-lg) 0;
}

.amount-label {
    font-size: 14px;
    margin-bottom: var(--spacing-sm);
}

.amount-value {
    font-size: 32px;
    font-weight: bold;
}

.receipt-qr {
    text-align: center;
    margin: var(--spacing-lg) 0;
}

.receipt-footer {
    text-align: center;
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--border);
    color: var(--muted);
}

.receipt-footer .small {
    font-size: 12px;
}

.receipt-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    margin-top: var(--spacing-lg);
}

@media print {
    .no-print {
        display: none !important;
    }
    
    .receipt-container {
        box-shadow: none;
    }
}
</style>
