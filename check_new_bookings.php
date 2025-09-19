<?php
require_once 'db.php';
header('Content-Type: application/json');

// Get the latest ID that was present on the client's dashboard when it loaded
$latestId = isset($_GET['latest_id']) ? intval($_GET['latest_id']) : 0;

// Query for any bookings with a higher ID
$stmt = $conn->prepare("SELECT COUNT(*) as new_count FROM bookings WHERE id > ?");
$stmt->bind_param('i', $latestId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

$newCount = $data ? (int)$data['new_count'] : 0;

echo json_encode(['new_count' => $newCount]);
?>
/* docathome seq: 12 */