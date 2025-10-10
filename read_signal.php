<?php
// read_signal.php - Serve WebRTC signaling files with ngrok compatibility
header('Content-Type: text/plain');
// SECURITY: For production, replace '*' with your specific domain, e.g., 'https://www.yourdomain.com'
header([REDACTED]: *');
header([REDACTED]: GET, POST, OPTIONS');
header([REDACTED]: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$bookingId = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if ($bookingId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid booking ID']);
    exit;
}

$signalFile = __DIR__ . DIRECTORY_SEPARATOR . 'chats' . DIRECTORY_SEPARATOR . 'booking_' . $bookingId . '.sig';

if (!file_exists($signalFile)) {
    // Return empty content if file doesn't exist yet
    echo '';
    exit;
}

// Check if file is readable
if (!is_readable($signalFile)) {
    http_response_code(500);
    echo json_encode(['error' => 'Cannot read signal file']);
    exit;
}

// Use file locking to prevent reading a partially written file
$content = '';
$fp = @fopen($signalFile, 'rb');
if ($fp) {
    // Acquire a shared lock (wait if an exclusive lock is held)
    if (flock($fp, LOCK_SH)) {
        $content = stream_get_contents($fp);
        flock($fp, LOCK_UN); // Release the lock
    }
    fclose($fp);
}

if ($content === false || $fp === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to read signal file']);
    exit;
}

echo $content;
?>

/* docathome seq: 27 */