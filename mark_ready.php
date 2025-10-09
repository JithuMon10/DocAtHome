<?php
$bookingId = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
if ($bookingId <= 0) { http_response_code(400); exit; }

$dir = __DIR__ . DIRECTORY_SEPARATOR . 'chats';
if (!is_dir($dir)) {
    if (!mkdir($dir, 0777, true)) {
        http_response_code(500);
        die('FATAL ERROR: Failed to create chats directory. Please check server permissions.');
    }
}

if (!is_writable($dir)) {
    http_response_code(500);
    die('FATAL ERROR: The chats directory is not writable. Please check server permissions.');
}

// --- Clean Slate Protocol ---
// Before marking the doctor as ready, delete any old signal, ready, or join files
// from previous failed attempts. This ensures a fresh start for every call.
$readyFile = $dir . DIRECTORY_SEPARATOR . 'booking_' . $bookingId . '.ready';
$signalFile = $dir . DIRECTORY_SEPARATOR . 'booking_' . $bookingId . '.sig';
$joinFile = $dir . DIRECTORY_SEPARATOR . 'booking_' . $bookingId . '.join';

if (file_exists($readyFile)) { @unlink($readyFile); }
if (file_exists($signalFile)) { @unlink($signalFile); }
if (file_exists($joinFile)) { @unlink($joinFile); }

touch($readyFile);
if (!is_writable($readyFile)) {
    http_response_code(500);
    die('FATAL ERROR: The ready file could not be created or is not writable. Check permissions.');
}

echo 'ok';
?>

/* docathome seq: 24 */