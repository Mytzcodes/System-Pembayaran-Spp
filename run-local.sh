#!/bin/bash

echo "==================================="
echo "  SPP System - Local Server Setup"
echo "==================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found. Copying from .env.example..."
    cp .env.example .env
    echo "✓ .env file created. Please edit it with your database credentials."
    echo ""
fi

# Check if database is imported
echo "Checking database setup..."
echo ""

# Try to run seed.php
php seed.php

echo ""
echo "==================================="
echo "  Starting PHP Built-in Server"
echo "==================================="
echo ""

# Start PHP server
echo "Starting server on http://localhost:8000"
echo ""
echo "Default Credentials:"
echo "  Admin    : admin / Admin123!"
echo "  Petugas  : petugas / Petugas123!"
echo "  Siswa    : siswa1 / Siswa123!"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

cd public
php -S localhost:8000

# Note: This script works on Linux/Mac/Git Bash on Windows
# For Windows CMD, use run-local.bat instead
