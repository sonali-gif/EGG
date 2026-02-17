<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

$stmt = $conn->prepare("SELECT id, election_id, name, party, symbol, description FROM candidates ORDER BY election_id DESC, id DESC");
$stmt->execute();
$candidates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(['candidates' => $candidates]);

$stmt->close();
$conn->close();
?>
