<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

$stmt = $conn->prepare("SELECT id, title, description, start_date, end_date, status FROM elections ORDER BY id DESC");
$stmt->execute();
$elections = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['elections' => $elections]);

$stmt->close();
$conn->close();
?>
