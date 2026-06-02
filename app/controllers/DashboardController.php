<?php
/**
 * Dashboard Controller
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Pembayaran.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Petugas.php';

$auth = new AuthController();
$auth->checkSessionTimeout();

if (!$auth->isAuthenticated()) {
    header('Location: /login');
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];
$view = $_GET['view'] ?? $role;

$pembayaranModel = new Pembayaran();
$siswaModel = new Siswa();

// Get statistics
$stats = [
    'total_siswa' => count($siswaModel->getAll()),
    'pembayaran_hari_ini' => $pembayaranModel->getTotalByPeriod(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')),
    'pembayaran_bulan_ini' => $pembayaranModel->getTotalByPeriod(date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59')),
    'recent_payments' => $pembayaranModel->getAll(5)
];

require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/layouts/sidebar.php';
?>

<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Dashboard <?= ucfirst($role) ?></h1>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value"><?= number_format($stats['total_siswa']) ?></div>
                    <div class="stat-card-label">Total Siswa</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value">Rp <?= number_format($stats['pembayaran_hari_ini']) ?></div>
                    <div class="stat-card-label">Pembayaran Hari Ini</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="stat-card-content">
                    <div class="stat-card-value">Rp <?= number_format($stats['pembayaran_bulan_ini']) ?></div>
                    <div class="stat-card-label">Pembayaran Bulan Ini</div>
                </div>
            </div>
        </div>
        
        <div class="recent-section">
            <h2>Pembayaran Terbaru</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Bulan/Tahun</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['recent_payments'] as $payment): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($payment['tgl_bayar'])) ?></td>
                            <td><?= htmlspecialchars($payment['nisn']) ?></td>
                            <td><?= htmlspecialchars($payment['nama_siswa']) ?></td>
                            <td><?= $payment['bulan_dibayar'] ?>/<?= $payment['tahun_dibayar'] ?></td>
                            <td>Rp <?= number_format($payment['jumlah_bayar']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../views/layouts/footer.php'; ?>
