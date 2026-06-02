<?php
/**
 * Basic Functionality Tests
 * Run: php tests/basic_tests.php
 */

require_once __DIR__ . '/../app/models/DB.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Pembayaran.php';

echo "=== SPP System - Basic Tests ===\n\n";

$passed = 0;
$failed = 0;

// Test 1: Database Connection
echo "Test 1: Database Connection\n";
try {
    $db = DB::getInstance()->getConnection();
    if ($db) {
        echo "✓ PASS: Database connected successfully\n\n";
        $passed++;
    }
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n\n";
    $failed++;
}

// Test 2: User Authentication
echo "Test 2: User Authentication\n";
try {
    $userModel = new User();
    $user = $userModel->authenticate('admin', 'Admin123!');
    
    if ($user && $user['role'] === 'admin') {
        echo "✓ PASS: Admin authentication successful\n\n";
        $passed++;
    } else {
        echo "✗ FAIL: Admin authentication failed\n\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n\n";
    $failed++;
}

// Test 3: Invalid Authentication
echo "Test 3: Invalid Authentication\n";
try {
    $userModel = new User();
    $user = $userModel->authenticate('admin', 'wrongpassword');
    
    if (!$user) {
        echo "✓ PASS: Invalid credentials rejected correctly\n\n";
        $passed++;
    } else {
        echo "✗ FAIL: Invalid credentials accepted (security issue!)\n\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n\n";
    $failed++;
}

// Test 4: Duplicate Payment Prevention
echo "Test 4: Duplicate Payment Prevention\n";
try {
    $pembayaranModel = new Pembayaran();
    
    // Try to create duplicate payment (should fail)
    $testData = [
        'nisn' => '0051234567',
        'id_petugas' => 1,
        'id_spp' => 1,
        'bulan_dibayar' => 1,
        'tahun_dibayar' => 2024,
        'jumlah_bayar' => 300000,
        'keterangan' => 'Test duplicate'
    ];
    
    $result = $pembayaranModel->createWithStoredProc($testData);
    
    if ($result['result'] == 0 && strpos($result['message'], 'sudah ada') !== false) {
        echo "✓ PASS: Duplicate payment correctly prevented\n\n";
        $passed++;
    } else {
        echo "✗ FAIL: Duplicate payment not prevented (data integrity issue!)\n\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n\n";
    $failed++;
}

// Test 5: Stored Procedure Execution
echo "Test 5: Stored Procedure Execution\n";
try {
    $db = DB::getInstance()->getConnection();
    $stmt = $db->prepare("CALL sp_get_history_siswa('0051234567')");
    $stmt->execute();
    $history = $stmt->fetchAll();
    
    if (is_array($history)) {
        echo "✓ PASS: Stored procedure executed successfully\n";
        echo "  Found " . count($history) . " payment records\n\n";
        $passed++;
    } else {
        echo "✗ FAIL: Stored procedure execution failed\n\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n\n";
    $failed++;
}

// Test 6: Password Hashing
echo "Test 6: Password Hashing\n";
try {
    $password = 'TestPassword123!';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    if (password_verify($password, $hash)) {
        echo "✓ PASS: Password hashing and verification working\n\n";
        $passed++;
    } else {
        echo "✗ FAIL: Password verification failed\n\n";
        $failed++;
    }
} catch (Exception $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n\n";
    $failed++;
}

// Summary
echo "=================================\n";
echo "Test Summary\n";
echo "=================================\n";
echo "Total Tests: " . ($passed + $failed) . "\n";
echo "Passed: $passed ✓\n";
echo "Failed: $failed ✗\n";
echo "Success Rate: " . round(($passed / ($passed + $failed)) * 100, 2) . "%\n";
echo "=================================\n";

if ($failed === 0) {
    echo "\n✓ All tests passed! System is ready.\n";
    exit(0);
} else {
    echo "\n⚠️  Some tests failed. Please check the errors above.\n";
    exit(1);
}
