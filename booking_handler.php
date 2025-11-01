<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: booking_form.php');
    exit;
}

function ensure_bookings_table(mysqli $conn): void {
    $conn->query("CREATE TABLE IF NOT EXISTS bookings (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(200) NOT NULL,
        phone VARCHAR(30) DEFAULT NULL,
        type ENUM('chat','video') NOT NULL,
        notes TEXT,
        status ENUM('pending','completed') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

ensure_bookings_table($conn);

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$type = trim($_POST['type'] ?? '');
$notes = trim($_POST['notes'] ?? '');

if ($name === '' || $email === '' || ($type !== 'chat' && $type !== 'video')) {
    echo '<p>Missing required fields. <a href="booking_form.php">Go back</a></p>';
    exit;
}

// Minimal table: bookings(id, name, email, phone, type, notes, created_at)
$stmt = $conn->prepare('INSERT INTO bookings (name, email, phone, type, notes, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
$stmt->bind_param('sssss', $name, $email, $phone, $type, $notes);
$ok = $stmt->execute();
$bookingId = $ok ? $stmt->insert_id : 0;
$stmt->close();

if (!$ok) {
    echo '<p>Failed to create booking. Please try again.</p>';
    exit;
}

// Create chat storage file for this booking
$chatDir = __DIR__ . DIRECTORY_SEPARATOR . 'chats';
if (!is_dir($chatDir)) {
    mkdir($chatDir, 0777, true);
}
$chatFile = $chatDir . DIRECTORY_SEPARATOR . 'booking_' . $bookingId . '.txt';
if (!file_exists($chatFile)) {
    file_put_contents($chatFile, "");
}

// Redirect based on consultation type
if ($type === 'chat') {
    header('Location: chat.php?booking_id=' . urlencode((string)$bookingId));
} else {
    header('Location: waiting.php?booking_id=' . urlencode((string)$bookingId) . '&role=patient');
}
exit;
?>