<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="#6366F1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SPP App">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="description" content="Sistem Pembayaran SPP Modern - Aplikasi pembayaran SPP yang mudah dan cepat">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="<?= asset('images/logo.png') ?>">
    <title><?= $pageTitle ?? 'Dashboard' ?> - Sistem SPP</title>
    <link rel="stylesheet" href="<?= asset('css/modern.css') ?>">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="header-title">Sistem Pembayaran SPP</h1>
        </div>
        <div class="header-right">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
            <div class="user-menu">
                <button class="user-menu-btn" id="userMenuBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    <span class="badge badge-primary"><?= htmlspecialchars($_SESSION['user']['role']) ?></span>
                </button>
                <div class="user-menu-dropdown" id="userMenuDropdown">
                    <a href="<?= url('siswa/profile') ?>">Profile</a>
                    <a href="<?= url('logout') ?>">Logout</a>
                </div>
            </div>
        </div>
    </header>
