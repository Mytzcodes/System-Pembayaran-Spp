<?php
require_once __DIR__ . '/DB.php';

class Petugas {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance()->getConnection();
    }
    
    public function getAll() {
        $sql = "SELECT p.*, u.username, u.email FROM petugas p 
                LEFT JOIN users u ON p.user_id = u.id 
                ORDER BY p.nama_petugas ASC";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT p.*, u.username, u.email FROM petugas p 
                                     LEFT JOIN users u ON p.user_id = u.id 
                                     WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM petugas WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO petugas (user_id, nama_petugas, no_telp) VALUES (?, ?, ?)");
        return $stmt->execute([$data['user_id'], $data['nama_petugas'], $data['no_telp']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE petugas SET nama_petugas = ?, no_telp = ? WHERE id = ?");
        return $stmt->execute([$data['nama_petugas'], $data['no_telp'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM petugas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
