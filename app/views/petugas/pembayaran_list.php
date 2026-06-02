<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Riwayat Pembayaran</h1>
            <a href="<?= url('pembayaran/create') ?>" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Input Pembayaran Baru
            </a>
        </div>
        
        <div class="card">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Bulan/Tahun</th>
                            <th>Jumlah</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pembayaranList as $payment): ?>
                        <tr>
                            <td><?= $payment['id'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($payment['tgl_bayar'])) ?></td>
                            <td><?= htmlspecialchars($payment['nisn']) ?></td>
                            <td><?= htmlspecialchars($payment['nama_siswa']) ?></td>
                            <td><?= $payment['bulan_dibayar'] ?>/<?= $payment['tahun_dibayar'] ?></td>
                            <td>Rp <?= number_format($payment['jumlah_bayar']) ?></td>
                            <td><?= htmlspecialchars($payment['nama_petugas']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="viewReceipt(<?= $payment['id'] ?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
