<?php
require_once __DIR__ . '/DB.php';

class Siswa {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance()->getConnection();
    }
    
    public function getAll() {
        $sql = "SELECT s.*, k.nama_kelas, k.kompetensi_keahlian, sp.nominal, sp.tahun, u.username 
                FROM siswa s 
                LEFT JOIN kelas k ON s.id_kelas = k.id 
                LEFT JOIN spp sp ON s.id_spp = sp.id
                LEFT JOIN users u ON s.user_id = u.id
                ORDER BY s.nama ASC";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function findByNisn($nisn) {
        $stmt = $this->db->prepare("SELECT s.*, k.nama_kelas, k.kompetensi_keahlian, sp.nominal, sp.tahun 
                                     FROM siswa s 
                                     LEFT JOIN kelas k ON s.id_kelas = k.id 
                                     LEFT JOIN spp sp ON s.id_spp = sp.id
                                     WHERE s.nisn = ?");
        $stmt->execute([$nisn]);
        return $stmt->fetch();
    }
    
    public function search($keyword) {
        $stmt = $this->db->prepare("SELECT s.*, k.nama_kelas FROM siswa s 
                                     LEFT JOIN kelas k ON s.id_kelas = k.id 
                                     WHERE s.nisn LIKE ? OR s.nis LIKE ? OR s.nama LIKE ? 
                                     LIMIT 10");
        $search = "%$keyword%";
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO siswa (nisn, nis, nama, id_kelas, alamat, no_telp, id_spp, user_id) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nisn'], $data['nis'], $data['nama'], $data['id_kelas'],
            $data['alamat'], $data['no_telp'], $data['id_spp'], $data['user_id'] ?? null
        ]);
    }
    
    public function update($nisn, $data) {
        $stmt = $this->db->prepare("UPDATE siswa SET nis = ?, nama = ?, id_kelas = ?, alamat = ?, no_telp = ?, id_spp = ? 
                                     WHERE nisn = ?");
        return $stmt->execute([
            $data['nis'], $data['nama'], $data['id_kelas'],
            $data['alamat'], $data['no_telp'], $data['id_spp'], $nisn
        ]);
    }
    
    public function delete($nisn) {
        $stmt = $this->db->prepare("DELETE FROM siswa WHERE nisn = ?");
        return $stmt->execute([$nisn]);
    }
    
    public function getUnpaidMonths($nisn, $tahun) {
        $stmt = $this->db->prepare("SELECT bulan_dibayar FROM pembayaran WHERE nisn = ? AND tahun_dibayar = ?");
        $stmt->execute([$nisn, $tahun]);
        $paid = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $allMonths = range(1, 12);
        return array_diff($allMonths, $paid);
    }
}
