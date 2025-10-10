<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$bookingId = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
$sender = isset($_POST['sender']) ? trim($_POST['sender']) : 'patient'; // 'patient' or 'doctor'
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($bookingId <= 0 || $message === '') {
    echo json_encode(['success' => false, 'error' => 'Missing booking_id or message']);
    exit;
}

$chatDir = __DIR__ . DIRECTORY_SEPARATOR . 'chats';
if (!is_dir($chatDir)) {
    mkdir($chatDir, 0777, true);
}
$chatFile = $chatDir . DIRECTORY_SEPARATOR . 'booking_' . $bookingId . '.txt';

$time = date('Y-m-d H:i:s');
$line = $time . "|" . ($sender === 'doctor' ? 'doctor' : 'patient') . "|" . str_replace(["\r", "\n"], [' ', ' '], $message) . "\n";

$ok = file_put_contents($chatFile, $line, FILE_APPEND | LOCK_EX) !== false;

echo json_encode(['success' => $ok]);
?>

/* docathome seq: 28 */