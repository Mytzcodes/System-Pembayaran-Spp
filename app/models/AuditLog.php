<?php
require_once __DIR__ . '/DB.php';

class AuditLog {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance()->getConnection();
    }
    
    public function log($action, $actorId, $actorRole, $targetTable, $targetId, $payload = null, $ipAddress = null) {
        $stmt = $this->db->prepare("INSERT INTO audit_log 
            (action, actor_id, actor_role, target_table, target_id, payload, ip_address) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $payloadJson = $payload ? json_encode($payload) : null;
        $ip = $ipAddress ?? $_SERVER['REMOTE_ADDR'] ?? null;
        
        return $stmt->execute([$action, $actorId, $actorRole, $targetTable, $targetId, $payloadJson, $ip]);
    }
    
    public function getAll($limit = 100) {
        $stmt = $this->db->prepare("SELECT * FROM audit_log ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    public function getByActor($actorId, $limit = 50) {
        $stmt = $this->db->prepare("SELECT * FROM audit_log WHERE actor_id = ? ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$actorId, $limit]);
        return $stmt->fetchAll();
    }
}
