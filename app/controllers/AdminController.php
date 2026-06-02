<?php
/**
 * Admin Controller
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Siswa.php';
require_once __DIR__ . '/../models/Petugas.php';
require_once __DIR__ . '/../models/Kelas.php';
require_once __DIR__ . '/../models/Spp.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';

$auth = new AuthController();
$auth->checkRole(['admin']);
$auth->checkSessionTimeout();

$action = $_GET['action'] ?? 'siswa';
$siswaModel = new Siswa();
$petugasModel = new Petugas();
$kelasModel = new Kelas();
$sppModel = new Spp();
$userModel = new User();
$auditLog = new AuditLog();

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!$auth->verifyCsrf($csrfToken)) {
        die('CSRF token validation failed');
    }
    
    $postAction = $_POST['action'] ?? '';
    
    switch ($postAction) {
        // SISWA CRUD
        case 'create_siswa':
            $userData = [
                'username' => $_POST['nisn'],
                'password' => 'Siswa123!',
                'role' => 'siswa',
                'email' => $_POST['email'] ?? null
            ];
            $userModel->create($userData['username'], $userData['password'], $userData['role'], $userData['email']);
            $userId = DB::getInstance()->getConnection()->lastInsertId();
            
            $siswaData = [
                'nisn' => $_POST['nisn'],
                'nis' => $_POST['nis'],
                'nama' => $_POST['nama'],
                'id_kelas' => $_POST['id_kelas'],
                'alamat' => $_POST['alamat'],
                'no_telp' => $_POST['no_telp'],
                'id_spp' => $_POST['id_spp'],
                'user_id' => $userId
            ];
            $siswaModel->create($siswaData);
            $auditLog->log('create_siswa', $_SESSION['user']['id'], 'admin', 'siswa', $_POST['nisn'], $siswaData);
            header('Location: /admin/siswa?success=created');
            exit;
            
        case 'update_siswa':
            $siswaData = [
                'nis' => $_POST['nis'],
                'nama' => $_POST['nama'],
                'id_kelas' => $_POST['id_kelas'],
                'alamat' => $_POST['alamat'],
                'no_telp' => $_POST['no_telp'],
                'id_spp' => $_POST['id_spp']
            ];
            $siswaModel->update($_POST['nisn'], $siswaData);
            $auditLog->log('update_siswa', $_SESSION['user']['id'], 'admin', 'siswa', $_POST['nisn'], $siswaData);
            header('Location: /admin/siswa?success=updated');
            exit;
            
        case 'delete_siswa':
            $siswaModel->delete($_POST['nisn']);
            $auditLog->log('delete_siswa', $_SESSION['user']['id'], 'admin', 'siswa', $_POST['nisn']);
            header('Location: /admin/siswa?success=deleted');
            exit;
            
        // PETUGAS CRUD
        case 'create_petugas':
            $userData = [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'role' => 'petugas',
                'email' => $_POST['email'] ?? null
            ];
            $userModel->create($userData['username'], $userData['password'], $userData['role'], $userData['email']);
            $userId = DB::getInstance()->getConnection()->lastInsertId();
            
            $petugasData = [
                'nama_petugas' => $_POST['nama_petugas'],
                'username' => $_POST['username'],
                'no_telp' => $_POST['no_telp'] ?? null,
                'email' => $_POST['email'] ?? null,
                'user_id' => $userId
            ];
            $petugasModel->create($petugasData);
            $auditLog->log('create_petugas', $_SESSION['user']['id'], 'admin', 'petugas', $userId, $petugasData);
            header('Location: /admin/petugas?success=created');
            exit;
            
        case 'update_petugas':
            $petugasData = [
                'nama_petugas' => $_POST['nama_petugas'],
                'username' => $_POST['username'],
                'no_telp' => $_POST['no_telp'] ?? null,
                'email' => $_POST['email'] ?? null
            ];
            
            // Update password if provided
            if (!empty($_POST['password'])) {
                $userModel->updatePassword($_POST['username'], $_POST['password']);
            }
            
            $petugasModel->update($_POST['id'], $petugasData);
            $auditLog->log('update_petugas', $_SESSION['user']['id'], 'admin', 'petugas', $_POST['id'], $petugasData);
            header('Location: /admin/petugas?success=updated');
            exit;
            
        // KELAS CRUD
        case 'create_kelas':
            $kelasData = [
                'nama_kelas' => $_POST['nama_kelas'],
                'kompetensi_keahlian' => $_POST['kompetensi_keahlian']
            ];
            $kelasModel->create($kelasData);
            $auditLog->log('create_kelas', $_SESSION['user']['id'], 'admin', 'kelas', null, $kelasData);
            header('Location: /admin/kelas?success=created');
            exit;
            
        case 'update_kelas':
            $kelasData = [
                'nama_kelas' => $_POST['nama_kelas'],
                'kompetensi_keahlian' => $_POST['kompetensi_keahlian']
            ];
            $kelasModel->update($_POST['id'], $kelasData);
            $auditLog->log('update_kelas', $_SESSION['user']['id'], 'admin', 'kelas', $_POST['id'], $kelasData);
            header('Location: /admin/kelas?success=updated');
            exit;
            
        // SPP CRUD
        case 'create_spp':
            $sppData = [
                'tahun' => $_POST['tahun'],
                'nominal' => $_POST['nominal'],
                'keterangan' => $_POST['keterangan'] ?? null
            ];
            $sppModel->create($sppData);
            $auditLog->log('create_spp', $_SESSION['user']['id'], 'admin', 'spp', null, $sppData);
            header('Location: /admin/spp?success=created');
            exit;
            
        case 'update_spp':
            $sppData = [
                'tahun' => $_POST['tahun'],
                'nominal' => $_POST['nominal'],
                'keterangan' => $_POST['keterangan'] ?? null
            ];
            $sppModel->update($_POST['id'], $sppData);
            $auditLog->log('update_spp', $_SESSION['user']['id'], 'admin', 'spp', $_POST['id'], $sppData);
            header('Location: /admin/spp?success=updated');
            exit;
    }
}

$user = $_SESSION['user'];
$role = $user['role'];

require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/layouts/sidebar.php';

switch ($action) {
    case 'siswa':
        $siswaList = $siswaModel->getAll();
        $kelasList = $kelasModel->getAll();
        $sppList = $sppModel->getAll();
        require_once __DIR__ . '/../views/admin/siswa_list.php';
        break;
    case 'petugas':
        $petugasList = $petugasModel->getAll();
        require_once __DIR__ . '/../views/admin/petugas_list.php';
        break;
    case 'kelas':
        $kelasList = $kelasModel->getAll();
        require_once __DIR__ . '/../views/admin/kelas_list.php';
        break;
    case 'spp':
        $sppList = $sppModel->getAll();
        require_once __DIR__ . '/../views/admin/spp_list.php';
        break;
}

require_once __DIR__ . '/../views/layouts/footer.php';
