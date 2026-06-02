<?php
/**
 * Pembayaran Controller
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Pembayaran.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Petugas.php';
require_once __DIR__ . '/../models/Spp.php';

$auth = new AuthController();
$auth->checkRole(['petugas']);
$auth->checkSessionTimeout();

$action = $_GET['action'] ?? 'create';
$pembayaranModel = new Pembayaran();
$siswaModel = new Siswa();
$sppModel = new Spp();
$petugasModel = new Petugas();

// Get petugas data
$petugas = $petugasModel->findByUserId($_SESSION['user']['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!$auth->verifyCsrf($csrfToken)) {
        die('CSRF token validation failed');
    }
    
    if ($_POST['action'] === 'create_pembayaran') {
        $data = [
            'nisn' => $_POST['nisn'],
            'id_petugas' => $petugas['id'],
            'id_spp' => $_POST['id_spp'],
            'bulan_dibayar' => $_POST['bulan_dibayar'],
            'tahun_dibayar' => $_POST['tahun_dibayar'],
            'jumlah_bayar' => $_POST['jumlah_bayar'],
            'keterangan' => $_POST['keterangan'] ?? null
        ];
        
        $result = $pembayaranModel->createWithStoredProc($data);
        
        if ($result['result'] == 1) {
            header('Location: /pembayaran/receipt/' . $result['payment_id']);
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

$user = $_SESSION['user'];
$role = $user['role'];

require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/layouts/sidebar.php';

switch ($action) {
    case 'create':
        $sppList = $sppModel->getAll();
        require_once __DIR__ . '/../views/petugas/pembayaran_form.php';
        break;
    case 'list':
        $pembayaranList = $pembayaranModel->getAll();
        require_once __DIR__ . '/../views/petugas/pembayaran_list.php';
        break;
    case 'receipt':
        $paymentId = $_GET['id'] ?? 0;
        $payment = $pembayaranModel->findById($paymentId);
        require_once __DIR__ . '/../views/petugas/receipt.php';
        break;
}

require_once __DIR__ . '/../views/layouts/footer.php';
