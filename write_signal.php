<?php
// write_signal.php - Handle WebRTC signaling via file storage with ngrok compatibility
header('Content-Type: application/json');
// SECURITY: For production, replace '*' with your specific domain, e.g., 'https://www.yourdomain.com'
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookingId = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
    $data = isset($_POST['data']) ? $_POST['data'] : '';
    
    if ($bookingId <= 0 || empty($data)) {
        echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
        exit;
    }
    
    try {
        $signalData = json_decode($data, true);
        if (!$signalData) {
            throw new Exception('Invalid JSON data');
        }
        
        $chatDir = __DIR__ . DIRECTORY_SEPARATOR . 'chats';
        if (!is_dir($chatDir)) {
            if (!mkdir($chatDir, 0777, true)) {
                throw new Exception('Failed to create chats directory');
            }
        }
        
        $signalFile = $chatDir . DIRECTORY_SEPARATOR . 'booking_' . $bookingId . '.sig';
        
        // Append the signal to the file
        $line = json_encode($signalData) . "\n";
        $result = file_put_contents($signalFile, $line, FILE_APPEND | LOCK_EX);
        
        if ($result !== false) {
            echo json_encode(['success' => true, 'bytes_written' => $result]);
        } else {
            throw new Exception('Failed to write signal file');
        }
        
    } catch (Exception $e) {
        error_log('Write signal error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>