<?php
/**
 * Application Configuration
 * Centralized config for base path and URLs
 */

// Detect base path automatically
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = str_replace('\\', '/', dirname($scriptName));

// Remove /public from base path if present
$basePath = str_replace('/public', '', $basePath);

// Ensure base path starts with /
if ($basePath !== '/' && substr($basePath, 0, 1) !== '/') {
    $basePath = '/' . $basePath;
}

// Remove trailing slash
$basePath = rtrim($basePath, '/');

// Define constants
define('BASE_PATH', $basePath);
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . BASE_PATH);
define('PUBLIC_PATH', __DIR__);
define('APP_PATH', dirname(__DIR__) . '/app');

// Helper function to generate URLs
function url($path = '') {
    $path = ltrim($path, '/');
    return BASE_PATH . ($path ? '/' . $path : '');
}

// Helper function to generate asset URLs
function asset($path) {
    $path = ltrim($path, '/');
    return BASE_PATH . '/assets/' . $path;
}
