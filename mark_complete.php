<?php
require_once 'db.php';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) { echo json_encode(['success'=>false]); exit; }

$stmt = $conn->prepare('UPDATE bookings SET status="completed" WHERE id=?');
$stmt->bind_param('i', $id);
$ok = $stmt->execute();
$stmt->close();

echo json_encode(['success' => (bool)$ok]);
?>
