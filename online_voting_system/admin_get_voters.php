<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

$stmt = $conn->prepare("SELECT id, voter_id, full_name, email, (SELECT COUNT(*) FROM votes WHERE voter_id = voters.id) as has_voted FROM voters WHERE is_admin = 0 ORDER BY id DESC");
$stmt->execute();
$voters = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['voters' => $voters]);

$stmt->close();
$conn->close();
?>
