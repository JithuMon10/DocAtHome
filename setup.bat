@echo off
title DocAtHome - Automatic Setup
color 0A
echo.
echo ================================================================================
echo                    DOCATHOME - AUTOMATIC SETUP
echo                   Setting up your database...
echo ================================================================================
echo.

REM Check if XAMPP is installed
if not exist "C:\xampp\mysql\bin\mysql.exe" (
    color 0C
    echo [ERROR] XAMPP not found!
    echo.
    echo Please install XAMPP first:
    echo 1. Download from: https://www.apachefriends.org/
    echo 2. Install to C:\xampp
    echo 3. Run this setup again
    echo.
    pause
    exit /b 1
)

echo [1/5] Checking XAMPP installation... OK
echo.

REM Check if MySQL is running
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    color 0E
    echo [WARNING] MySQL is not running!
    echo.
    echo Please start MySQL:
    echo 1. Open XAMPP Control Panel
    echo 2. Click "Start" next to MySQL
    echo 3. Run this setup again
    echo.
    pause
    exit /b 1
)

echo [2/5] Checking MySQL service... RUNNING
echo.

REM Get the current directory
set CURRENT_DIR=%~dp0
set SQL_FILE=%CURRENT_DIR%docathome.sql

REM Check if SQL file exists
if not exist "%SQL_FILE%" (
    color 0C
    echo [ERROR] docathome.sql not found!
    echo Expected location: %SQL_FILE%
    echo.
    pause
    exit /b 1
)

echo [3/5] Found SQL file... OK
echo.

echo [4/5] Creating database and importing data...
echo.

REM Drop existing database if exists and create fresh
"C:\xampp\mysql\bin\mysql.exe" -u root -e "DROP DATABASE IF EXISTS docathome;" 2>nul
"C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE docathome CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

if %ERRORLEVEL% NEQ 0 (
    color 0C
    echo [ERROR] Failed to create database!
    echo.
    pause
    exit /b 1
)

echo    - Database 'docathome' created successfully
echo.

REM Import SQL file
"C:\xampp\mysql\bin\mysql.exe" -u root docathome < "%SQL_FILE%"

if %ERRORLEVEL% NEQ 0 (
    color 0C
    echo [ERROR] Failed to import SQL file!
    echo.
    pause
    exit /b 1
)

echo    - Tables imported successfully
echo.

REM Add missing status column if not exists
"C:\xampp\mysql\bin\mysql.exe" -u root docathome -e "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS status ENUM('pending','completed') NOT NULL DEFAULT 'pending' AFTER notes;" 2>nul

echo    - Added 'status' column to bookings table
echo.

REM Create messages table if not exists
"C:\xampp\mysql\bin\mysql.exe" -u root docathome -e "CREATE TABLE IF NOT EXISTS messages (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, booking_id INT UNSIGNED NOT NULL, sender ENUM('doctor','patient') NOT NULL, message TEXT NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP);" 2>nul

echo    - Ensured 'messages' table exists
echo.

REM Create chats directory if not exists
if not exist "%CURRENT_DIR%chats" (
    mkdir "%CURRENT_DIR%chats"
    echo    - Created 'chats' directory for signaling files
    echo.
)

REM Set proper permissions (Windows equivalent)
attrib +R -H "%CURRENT_DIR%chats" 2>nul

echo [5/5] Finalizing setup...
echo.

REM Test database connection
"C:\xampp\mysql\bin\mysql.exe" -u root docathome -e "SELECT COUNT(*) FROM bookings;" >nul 2>&1

if %ERRORLEVEL% EQU 0 (
    color 0A
    echo ================================================================================
    echo                         SETUP COMPLETED SUCCESSFULLY!
    echo ================================================================================
    echo.
    echo Your DocAtHome installation is ready!
    echo.
    echo NEXT STEPS:
    echo 1. Make sure Apache is running in XAMPP Control Panel
    echo 2. Open your browser
    echo 3. Go to: http://localhost/Nigiri/
    echo.
    echo FOR NGROK (HTTPS for mobile):
    echo 1. Download ngrok from: https://ngrok.com/download
    echo 2. Run: ngrok http 80
    echo 3. Use the https URL provided
    echo.
    echo DATABASE INFO:
    echo - Database: docathome
    echo - Username: root
    echo - Password: (empty)
    echo - Tables: bookings, messages
    echo.
    echo ================================================================================
    echo.
) else (
    color 0C
    echo [ERROR] Setup completed but database test failed!
    echo Please check your MySQL installation.
    echo.
)

pause
