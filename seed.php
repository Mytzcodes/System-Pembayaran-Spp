<?php
/**
 * Database Seeder
 * Imports SQL schema and seed data into MySQL database
 */

// Load environment variables
$env = parse_ini_file(__DIR__ . '/.env');

$host = $env['DB_HOST'] ?? 'localhost';
$port = $env['DB_PORT'] ?? '3306';
$dbname = $env['DB_NAME'] ?? 'spp_sekolah';
$user = $env['DB_USER'] ?? 'root';
$pass = $env['DB_PASS'] ?? '';

echo "=== SPP Database Seeder ===\n\n";

// Check if mysql command is available
$mysqlCmd = 'mysql';
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // Windows - try common XAMPP/Laragon paths
    $possiblePaths = [
        'C:\\xampp\\mysql\\bin\\mysql.exe',
        'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysql.exe',
        'mysql'
    ];
    
    foreach ($possiblePaths as $path) {
        if (file_exists($path) || $path === 'mysql') {
            $mysqlCmd = $path;
            break;
        }
    }
}

// Try to import using mysql command
$sqlFile = __DIR__ . '/database/spp.sql';

if (!file_exists($sqlFile)) {
    die("Error: SQL file not found at $sqlFile\n");
}

echo "Attempting to import database...\n";
echo "Host: $host:$port\n";
echo "Database: $dbname\n";
echo "User: $user\n\n";

// Build mysql command
$passArg = $pass ? "-p$pass" : '';
$command = "\"$mysqlCmd\" -h $host -P $port -u $user $passArg < \"$sqlFile\" 2>&1";

exec($command, $output, $returnCode);

if ($returnCode === 0) {
    echo "✓ Database imported successfully!\n\n";
    echo "Default credentials:\n";
    echo "  Admin    : admin / Admin123!\n";
    echo "  Petugas  : petugas / Petugas123!\n";
    echo "  Siswa    : siswa1 / Siswa123!\n\n";
} else {
    echo "✗ Failed to import using mysql command.\n";
    echo "Output: " . implode("\n", $output) . "\n\n";
    echo "=== MANUAL IMPORT INSTRUCTIONS ===\n\n";
    
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        echo "For XAMPP:\n";
        echo "1. Open phpMyAdmin (http://localhost/phpmyadmin)\n";
        echo "2. Click 'Import' tab\n";
        echo "3. Choose file: $sqlFile\n";
        echo "4. Click 'Go'\n\n";
        
        echo "OR use command line:\n";
        echo "cd C:\\xampp\\mysql\\bin\n";
        echo "mysql -u root < \"$sqlFile\"\n\n";
    } else {
        echo "Run this command:\n";
        echo "mysql -h $host -P $port -u $user $passArg < $sqlFile\n\n";
    }
    
    echo "After manual import, default credentials are:\n";
    echo "  Admin    : admin / Admin123!\n";
    echo "  Petugas  : petugas / Petugas123!\n";
    echo "  Siswa    : siswa1 / Siswa123!\n";
}
