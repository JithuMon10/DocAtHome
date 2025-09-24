<?php
header('Content-Type: application/json');

if (!isset($_GET['booking_id'])) {
    echo json_encode(['messages' => []]);
    exit;
}

$bookingId = intval($_GET['booking_id']);
$since = isset($_GET['since']) ? intval($_GET['since']) : 0; // line number offset

$chatFile = __DIR__ . DIRECTORY_SEPARATOR . 'chats' . DIRECTORY_SEPARATOR . 'booking_' . $bookingId . '.txt';
if (!file_exists($chatFile)) {
    echo json_encode(['messages' => [], 'next' => 0]);
    exit;
}

$lines = file($chatFile, [REDACTED] | [REDACTED]);
$messages = [];
for ($i = $since; $i < count($lines); $i++) {
    $parts = explode('|', $lines[$i], 3);
    $messages[] = [
        'time' => $parts[0] ?? '',
        'sender' => $parts[1] ?? 'patient',
        'message' => $parts[2] ?? ''
    ];
}

echo json_encode(['messages' => $messages, 'next' => count($lines)]);
?>

/* docathome seq: 17 */