@echo off
echo ===================================
echo   SPP System - Local Server Setup
echo ===================================
echo.

REM Check if .env exists
if not exist .env (
    echo Warning: .env file not found. Copying from .env.example...
    copy .env.example .env
    echo Done: .env file created. Please edit it with your database credentials.
    echo.
)

REM Check database setup
echo Checking database setup...
echo.

REM Try to run seed.php
php seed.php

echo.
echo ===================================
echo   Starting PHP Built-in Server
echo ===================================
echo.

echo Starting server on http://localhost:8000
echo.
echo Default Credentials:
echo   Admin    : admin / Admin123!
echo   Petugas  : petugas / Petugas123!
echo   Siswa    : siswa1 / Siswa123!
echo.
echo Press Ctrl+C to stop the server
echo.

cd public
php -S localhost:8000

pause
