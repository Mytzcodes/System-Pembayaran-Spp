<?php
/**
 * Report Controller
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Pembayaran.php';

$auth = new AuthController();
$auth->checkRole(['admin']);
$auth->checkSessionTimeout();

$pembayaranModel = new Pembayaran();

// Handle export
if (isset($_GET['export'])) {
    $format = $_GET['export'];
    $tahun = $_GET['tahun'] ?? date('Y');
    $bulan = $_GET['bulan'] ?? null;
    
    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="laporan_pembayaran_' . $tahun . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Tanggal', 'NISN', 'Nama Siswa', 'Bulan', 'Tahun', 'Jumlah', 'Petugas']);
        
        $payments = $pembayaranModel->getAll();
        foreach ($payments as $payment) {
            if ($payment['tahun_dibayar'] == $tahun) {
                fputcsv($output, [
                    $payment['id'],
                    $payment['tgl_bayar'],
                    $payment['nisn'],
                    $payment['nama_siswa'],
                    $payment['bulan_dibayar'],
                    $payment['tahun_dibayar'],
                    $payment['jumlah_bayar'],
                    $payment['nama_petugas']
                ]);
            }
        }
        fclose($output);
        exit;
    }
}

$user = $_SESSION['user'];
$role = $user['role'];

$tahun = $_GET['tahun'] ?? date('Y');
$monthlyStats = $pembayaranModel->getMonthlyStats($tahun);

require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/layouts/sidebar.php';
require_once __DIR__ . '/../views/admin/laporan.php';
require_once __DIR__ . '/../views/layouts/footer.php';
