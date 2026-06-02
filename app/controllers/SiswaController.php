<?php
/**
 * Siswa Controller
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Pembayaran.php';

$auth = new AuthController();
$auth->checkRole(['siswa']);
$auth->checkSessionTimeout();

$action = $_GET['action'] ?? 'profile';
$siswaModel = new Siswa();
$pembayaranModel = new Pembayaran();

// Get siswa data from user
$siswaData = $siswaModel->getAll();
$currentSiswa = null;
foreach ($siswaData as $s) {
    if ($s['user_id'] == $_SESSION['user']['id']) {
        $currentSiswa = $s;
        break;
    }
}

$user = $_SESSION['user'];
$role = $user['role'];

require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/layouts/sidebar.php';

switch ($action) {
    case 'profile':
        require_once __DIR__ . '/../views/siswa/profile.php';
        break;
    case 'history':
        if ($currentSiswa) {
            $historyList = $pembayaranModel->getByNisn($currentSiswa['nisn']);
        }
        require_once __DIR__ . '/../views/siswa/history.php';
        break;
}

require_once __DIR__ . '/../views/layouts/footer.php';
