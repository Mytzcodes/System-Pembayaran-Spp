<?php
/**
 * Fix Passwords Script
 * Run this to reset all passwords to known values
 */

require_once __DIR__ . '/app/models/DB.php';

echo "=== Password Fix Script ===\n\n";

try {
    $db = DB::getInstance()->getConnection();
    
    // Define passwords
    $passwords = [
        'admin' => 'Admin123!',
        'petugas' => 'Petugas123!',
        'petugas2' => 'Petugas123!',
        'siswa1' => 'Siswa123!',
        'siswa2' => 'Siswa123!',
        'siswa3' => 'Siswa123!',
        'siswa4' => 'Siswa123!',
        'siswa5' => 'Siswa123!',
        'siswa6' => 'Siswa123!',
        'siswa7' => 'Siswa123!',
        'siswa8' => 'Siswa123!',
        'siswa9' => 'Siswa123!',
        'siswa10' => 'Siswa123!'
    ];
    
    echo "Updating passwords...\n\n";
    
    foreach ($passwords as $username => $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
        $result = $stmt->execute([$hashedPassword, $username]);
        
        if ($result) {
            echo "✓ Updated: $username / $password\n";
            
            // Verify the hash works
            $stmt = $db->prepare("SELECT password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                echo "  ✓ Verified: Password hash is correct\n";
            } else {
                echo "  ✗ ERROR: Password verification failed!\n";
            }
        } else {
            echo "✗ Failed: $username\n";
        }
        echo "\n";
    }
    
    echo "\n=== Summary ===\n";
    echo "All passwords have been reset!\n\n";
    echo "Login Credentials:\n";
    echo "  Admin    : admin / Admin123!\n";
    echo "  Petugas  : petugas / Petugas123!\n";
    echo "  Siswa    : siswa1 / Siswa123!\n\n";
    
    echo "Now try to login at: http://localhost/TESTING%20SKEMA/spp-native/public/\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nMake sure:\n";
    echo "1. MySQL is running\n";
    echo "2. Database 'spp_sekolah' exists\n";
    echo "3. .env file is configured correctly\n";
}
