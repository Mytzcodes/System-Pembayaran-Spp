<?php
/**
 * Front Controller - Clean URL Routing
 * Uses PATH_INFO for SEO-friendly URLs
 */

// Start session once at the beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/router.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$router = new Router(BASE_PATH);
$auth = new AuthController();

// ============================================
// PUBLIC ROUTES (No authentication required)
// ============================================

$router->get('/', function() use ($auth) {
    if ($auth->isAuthenticated()) {
        Router::redirect('/dashboard');
    } else {
        Router::redirect('/login');
    }
});

$router->get('/login', function() use ($auth) {
    if ($auth->isAuthenticated()) {
        Router::redirect('/dashboard');
    }
    require __DIR__ . '/login.php';
});

$router->post('/login', function() use ($auth) {
    // Login handled in login.php
    require __DIR__ . '/login.php';
});

$router->get('/logout', function() {
    require __DIR__ . '/logout.php';
});

// ============================================
// PROTECTED ROUTES (Authentication required)
// ============================================

$router->get('/dashboard', function() use ($auth) {
    if (!$auth->isAuthenticated()) {
        Router::redirect('/login');
    }
    $auth->checkSessionTimeout();
    require __DIR__ . '/../app/controllers/DashboardController.php';
});

// Admin Routes
$router->get('/admin/siswa', function() use ($auth) {
    $auth->checkRole(['admin']);
    $_GET['action'] = 'siswa';
    require __DIR__ . '/../app/controllers/AdminController.php';
});

$router->get('/admin/petugas', function() use ($auth) {
    $auth->checkRole(['admin']);
    $_GET['action'] = 'petugas';
    require __DIR__ . '/../app/controllers/AdminController.php';
});

$router->get('/admin/kelas', function() use ($auth) {
    $auth->checkRole(['admin']);
    $_GET['action'] = 'kelas';
    require __DIR__ . '/../app/controllers/AdminController.php';
});

$router->get('/admin/spp', function() use ($auth) {
    $auth->checkRole(['admin']);
    $_GET['action'] = 'spp';
    require __DIR__ . '/../app/controllers/AdminController.php';
});

$router->get('/admin/laporan', function() use ($auth) {
    $auth->checkRole(['admin']);
    require __DIR__ . '/../app/controllers/ReportController.php';
});

$router->post('/admin/:action', function($action) use ($auth) {
    $auth->checkRole(['admin']);
    require __DIR__ . '/../app/controllers/AdminController.php';
});

// Petugas Routes
$router->get('/pembayaran/create', function() use ($auth) {
    $auth->checkRole(['petugas']);
    $_GET['action'] = 'create';
    require __DIR__ . '/../app/controllers/PembayaranController.php';
});

$router->get('/pembayaran/list', function() use ($auth) {
    $auth->checkRole(['petugas']);
    $_GET['action'] = 'list';
    require __DIR__ . '/../app/controllers/PembayaranController.php';
});

$router->get('/pembayaran/receipt/:id', function($id) use ($auth) {
    $auth->checkRole(['petugas']);
    $_GET['action'] = 'receipt';
    $_GET['id'] = $id;
    require __DIR__ . '/../app/controllers/PembayaranController.php';
});

$router->post('/pembayaran/create', function() use ($auth) {
    $auth->checkRole(['petugas']);
    require __DIR__ . '/../app/controllers/PembayaranController.php';
});

// Siswa Routes
$router->get('/siswa/profile', function() use ($auth) {
    $auth->checkRole(['siswa']);
    $_GET['action'] = 'profile';
    require __DIR__ . '/../app/controllers/SiswaController.php';
});

$router->get('/siswa/history', function() use ($auth) {
    $auth->checkRole(['siswa']);
    $_GET['action'] = 'history';
    require __DIR__ . '/../app/controllers/SiswaController.php';
});

// API Routes
$router->get('/api/search', function() use ($auth) {
    if (!$auth->isAuthenticated()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    require __DIR__ . '/../app/controllers/SearchController.php';
});

// Dispatch the router
$router->dispatch();
