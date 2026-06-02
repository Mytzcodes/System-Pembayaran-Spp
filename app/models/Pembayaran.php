<?php
require_once __DIR__ . '/DB.php';

class Pembayaran {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance()->getConnection();
    }
    
    public function getAll($limit = null) {
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nis, pt.nama_petugas, sp.nominal 
                FROM pembayaran p
                JOIN siswa s ON p.nisn = s.nisn
                JOIN petugas pt ON p.id_petugas = pt.id
                JOIN spp sp ON p.id_spp = sp.id
                ORDER BY p.tgl_bayar DESC";
        if ($limit) $sql .= " LIMIT $limit";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT p.*, s.nama as nama_siswa, s.nis, pt.nama_petugas 
                                     FROM pembayaran p
                                     JOIN siswa s ON p.nisn = s.nisn
                                     JOIN petugas pt ON p.id_petugas = pt.id
                                     WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByNisn($nisn) {
        $stmt = $this->db->prepare("CALL sp_get_history_siswa(?)");
        $stmt->execute([$nisn]);
        return $stmt->fetchAll();
    }
    
    public function createWithStoredProc($data) {
        $stmt = $this->db->prepare("CALL sp_tambah_pembayaran(?, ?, ?, ?, ?, ?, ?, @result, @message, @payment_id)");
        $stmt->execute([
            $data['nisn'],
            $data['id_petugas'],
            $data['id_spp'],
            $data['bulan_dibayar'],
            $data['tahun_dibayar'],
            $data['jumlah_bayar'],
            $data['keterangan'] ?? null
        ]);
        
        $result = $this->db->query("SELECT @result as result, @message as message, @payment_id as payment_id")->fetch();
        return $result;
    }
    
    public function getMonthlyStats($tahun) {
        $stmt = $this->db->prepare("SELECT 
            bulan_dibayar, 
            SUM(jumlah_bayar) as total,
            COUNT(*) as count
            FROM pembayaran 
            WHERE tahun_dibayar = ?
            GROUP BY bulan_dibayar
            ORDER BY bulan_dibayar");
        $stmt->execute([$tahun]);
        return $stmt->fetchAll();
    }
    
    public function getTotalByPeriod($start, $end) {
        $stmt = $this->db->prepare("SELECT SUM(jumlah_bayar) as total FROM pembayaran 
                                     WHERE tgl_bayar BETWEEN ? AND ?");
        $stmt->execute([$start, $end]);
        return $stmt->fetch()['total'] ?? 0;
    }
}
