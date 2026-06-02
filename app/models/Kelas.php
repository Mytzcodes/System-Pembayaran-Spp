<?php
require_once __DIR__ . '/DB.php';

class Kelas {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance()->getConnection();
    }
    
    public function getAll() {
        return $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kelas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO kelas (nama_kelas, kompetensi_keahlian) VALUES (?, ?)");
        return $stmt->execute([$data['nama_kelas'], $data['kompetensi_keahlian']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE kelas SET nama_kelas = ?, kompetensi_keahlian = ? WHERE id = ?");
        return $stmt->execute([$data['nama_kelas'], $data['kompetensi_keahlian'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM kelas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
