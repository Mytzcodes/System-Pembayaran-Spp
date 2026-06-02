<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 32px; height: 32px; display: inline; vertical-align: middle; margin-right: 0.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Pembayaran SPP
            </h1>
        </div>
        
        <?php if ($currentSiswa): ?>
        <div class="alert alert-info">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p style="margin: 0.25rem 0;"><strong>Nama:</strong> <?= htmlspecialchars($currentSiswa['nama']) ?></p>
                <p style="margin: 0.25rem 0;"><strong>NISN:</strong> <?= htmlspecialchars($currentSiswa['nisn']) ?></p>
                <p style="margin: 0.25rem 0;"><strong>Kelas:</strong> <?= htmlspecialchars($currentSiswa['nama_kelas']) ?></p>
            </div>
        </div>
        
        <?php if (!empty($historyList)): ?>
        <div class="card">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal Bayar</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Jumlah</th>
                            <th>Petugas</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        foreach ($historyList as $payment): 
                        ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($payment['tgl_bayar'])) ?></td>
                            <td><?= $bulanNames[$payment['bulan_dibayar']] ?></td>
                            <td><?= $payment['tahun_dibayar'] ?></td>
                            <td><strong>Rp <?= number_format($payment['jumlah_bayar']) ?></strong></td>
                            <td><?= htmlspecialchars($payment['nama_petugas']) ?></td>
                            <td><?= htmlspecialchars($payment['keterangan'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--gray-50); font-weight: 600;">
                            <th colspan="3">Total Pembayaran</th>
                            <th style="color: var(--success);">Rp <?= number_format(array_sum(array_column($historyList, 'jumlah_bayar'))) ?></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Belum ada riwayat pembayaran.</span>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Data siswa tidak ditemukan.</span>
        </div>
        <?php endif; ?>
    </div>
</main>
