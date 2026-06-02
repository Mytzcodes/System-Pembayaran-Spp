<?php
require_once __DIR__ . '/DB.php';

class Spp {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance()->getConnection();
    }
    
    public function getAll() {
        return $this->db->query("SELECT * FROM spp ORDER BY tahun DESC")->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM spp WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO spp (tahun, nominal, keterangan) VALUES (?, ?, ?)");
        return $stmt->execute([$data['tahun'], $data['nominal'], $data['keterangan']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE spp SET tahun = ?, nominal = ?, keterangan = ? WHERE id = ?");
        return $stmt->execute([$data['tahun'], $data['nominal'], $data['keterangan'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM spp WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
