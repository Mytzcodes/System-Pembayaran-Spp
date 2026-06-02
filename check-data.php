<?php
/**
 * Check Database Data
 */

require_once __DIR__ . '/app/models/DB.php';

$db = DB::getInstance()->getConnection();

echo "<h1>Database Data Check</h1>";

// Check Kelas
echo "<h2>Kelas</h2>";
$kelas = $db->query("SELECT * FROM kelas")->fetchAll();
echo "Total: " . count($kelas) . "<br>";
foreach ($kelas as $k) {
    echo "- {$k['id']}: {$k['nama_kelas']} ({$k['kompetensi_keahlian']})<br>";
}

// Check SPP
echo "<h2>SPP</h2>";
$spp = $db->query("SELECT * FROM spp")->fetchAll();
echo "Total: " . count($spp) . "<br>";
foreach ($spp as $s) {
    echo "- {$s['id']}: Tahun {$s['tahun']} - Rp " . number_format($s['nominal']) . "<br>";
}

// Check Users
echo "<h2>Users</h2>";
$users = $db->query("SELECT * FROM users")->fetchAll();
echo "Total: " . count($users) . "<br>";
foreach ($users as $u) {
    echo "- {$u['id']}: {$u['username']} ({$u['role']})<br>";
}

// Check Siswa
echo "<h2>Siswa</h2>";
$siswa = $db->query("SELECT s.*, k.nama_kelas, sp.nominal FROM siswa s LEFT JOIN kelas k ON s.id_kelas = k.id LEFT JOIN spp sp ON s.id_spp = sp.id")->fetchAll();
echo "Total: " . count($siswa) . "<br>";
foreach ($siswa as $s) {
    echo "- {$s['nisn']}: {$s['nama']} - {$s['nama_kelas']} - Rp " . number_format($s['nominal']) . "<br>";
}

// Check Petugas
echo "<h2>Petugas</h2>";
$petugas = $db->query("SELECT * FROM petugas")->fetchAll();
echo "Total: " . count($petugas) . "<br>";
foreach ($petugas as $p) {
    echo "- {$p['id']}: {$p['nama_petugas']} ({$p['username']})<br>";
}

// Check Pembayaran
echo "<h2>Pembayaran</h2>";
$pembayaran = $db->query("SELECT * FROM pembayaran LIMIT 10")->fetchAll();
echo "Total: " . count($pembayaran) . " (showing first 10)<br>";
foreach ($pembayaran as $p) {
    echo "- {$p['id']}: NISN {$p['nisn']} - Bulan {$p['bulan_dibayar']}/{$p['tahun_dibayar']} - Rp " . number_format($p['jumlah_bayar']) . "<br>";
}
