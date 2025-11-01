<?php
$bookingId = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
if ($bookingId <= 0) { http_response_code(400); exit; }

$dir = __DIR__ . DIRECTORY_SEPARATOR . 'chats';
if (!is_dir($dir)) {
    if (!mkdir($dir, 0777, true)) {
        http_response_code(500);
        die('ERROR: Failed to create chats directory.');
    }
}

// Create join file to signal patient that doctor has joined
$joinFile = $dir . DIRECTORY_SEPARATOR . 'booking_' . $bookingId . '.join';
touch($joinFile);

echo 'ok';
?>
