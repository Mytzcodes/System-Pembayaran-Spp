<?php
/**
 * Test Login Script
 * Debug login issues
 */

require_once __DIR__ . '/app/models/DB.php';
require_once __DIR__ . '/app/models/User.php';

echo "=== Login Test Script ===\n\n";

// Test credentials
$testUsers = [
    ['username' => 'admin', 'password' => 'Admin123!'],
    ['username' => 'petugas', 'password' => 'Petugas123!'],
    ['username' => 'siswa1', 'password' => 'Siswa123!']
];

try {
    $db = DB::getInstance()->getConnection();
    $userModel = new User();
    
    echo "Database connection: ✓ OK\n\n";
    
    foreach ($testUsers as $test) {
        $username = $test['username'];
        $password = $test['password'];
        
        echo "Testing: $username / $password\n";
        echo str_repeat('-', 50) . "\n";
        
        // Check if user exists
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo "✗ User not found in database!\n";
            echo "  Run: php seed.php to create users\n\n";
            continue;
        }
        
        echo "✓ User found in database\n";
        echo "  ID: {$user['id']}\n";
        echo "  Username: {$user['username']}\n";
        echo "  Role: {$user['role']}\n";
        echo "  Active: {$user['is_active']}\n";
        echo "  Password hash: " . substr($user['password'], 0, 20) . "...\n";
        
        // Test password verification
        if (password_verify($password, $user['password'])) {
            echo "✓ Password verification: SUCCESS\n";
        } else {
            echo "✗ Password verification: FAILED\n";
            echo "  The password hash in database doesn't match!\n";
            echo "  Run: php fix-passwords.php to fix\n";
        }
        
        // Test authenticate method
        $authResult = $userModel->authenticate($username, $password);
        if ($authResult) {
            echo "✓ User::authenticate(): SUCCESS\n";
            echo "  Login should work!\n";
        } else {
            echo "✗ User::authenticate(): FAILED\n";
            echo "  Login will not work!\n";
        }
        
        echo "\n";
    }
    
    echo "=== Recommendations ===\n";
    echo "If login fails:\n";
    echo "1. Run: php fix-passwords.php\n";
    echo "2. Clear browser cookies\n";
    echo "3. Try login again\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nMake sure:\n";
    echo "1. MySQL is running\n";
    echo "2. Database 'spp_sekolah' exists\n";
    echo "3. Run: php seed.php (or import database/spp.sql)\n";
}
