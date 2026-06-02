<?php
/**
 * Search Controller - API for Autocomplete
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Siswa.php';

$auth = new AuthController();
$auth->checkSessionTimeout();

if (!$auth->isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$siswaModel = new Siswa();
$allSiswa = $siswaModel->getAll();

// Filter siswa by NISN, NIS, or Nama
$results = array_filter($allSiswa, function($siswa) use ($query) {
    $query = strtolower($query);
    return strpos(strtolower($siswa['nisn']), $query) !== false ||
           strpos(strtolower($siswa['nis']), $query) !== false ||
           strpos(strtolower($siswa['nama']), $query) !== false;
});

// Limit to 10 results
$results = array_slice(array_values($results), 0, 10);

echo json_encode($results);
