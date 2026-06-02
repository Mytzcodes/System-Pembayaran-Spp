<?php
require_once __DIR__ . '/../app/models/AuditLog.php';

if (isset($_SESSION['user'])) {
    $audit = new AuditLog();
    $audit->log('logout', $_SESSION['user']['id'], $_SESSION['user']['role'], 'users', $_SESSION['user']['id']);
}

session_destroy();
header('Location: /login');
exit;
