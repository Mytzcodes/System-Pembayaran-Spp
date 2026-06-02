<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/AuditLog.php';

class AuthController {
    private $userModel;
    private $auditLog;
    
    public function __construct() {
        $this->userModel = new User();
        $this->auditLog = new AuditLog();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function login($username, $password) {
        $user = $this->userModel->authenticate($username, $password);
        
        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            $_SESSION['last_activity'] = time();
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            
            $this->auditLog->log('login', $user['id'], $user['role'], 'users', $user['id']);
            return true;
        }
        
        return false;
    }
    
    public function logout() {
        if (isset($_SESSION['user'])) {
            $this->auditLog->log('logout', $_SESSION['user']['id'], $_SESSION['user']['role'], 'users', $_SESSION['user']['id']);
        }
        session_destroy();
    }
    
    public function isAuthenticated() {
        return isset($_SESSION['user']);
    }

    
    public function checkRole($allowedRoles) {
        if (!$this->isAuthenticated()) {
            header('Location: /login?error=unauthorized');
            exit;
        }
        
        if (!in_array($_SESSION['user']['role'], $allowedRoles)) {
            http_response_code(403);
            if (file_exists(__DIR__ . '/../../public/errors/403.php')) {
                require __DIR__ . '/../../public/errors/403.php';
            } else {
                die('Access denied');
            }
            exit;
        }
    }
    
    public function checkSessionTimeout() {
        $env = parse_ini_file(__DIR__ . '/../../.env');
        $timeout = $env['SESSION_TIMEOUT'] ?? 1800;
        
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
            $this->logout();
            header('Location: /login?timeout=1');
            exit;
        }
        
        $_SESSION['last_activity'] = time();
    }
    
    public function verifyCsrf($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public function getCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
