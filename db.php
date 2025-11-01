<?php
// db.php
// Database connection for DocAtHome project

// --- CRITICAL SECURITY WARNING ---
// Using 'root' with an empty password is for LOCAL DEVELOPMENT ONLY.
// NEVER use these credentials in a production environment.
// Always create a dedicated database user with a strong, unique password for your application.
// ---
$host = "localhost";     // Database host
$user = "root";          // MySQL user
$pass = "";              // MySQL password
$dbname = "docathome";   // Database name

// Create connection
try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Optional: set charset
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die('Database connection error: ' . $e->getMessage());
}
?>