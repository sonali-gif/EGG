<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

$election_id = 1; // Current election

$stmt = $conn->prepare("SELECT id, title, description, status FROM elections WHERE id = ?");
$stmt->bind_param("i", $election_id);
$stmt->execute();
$election = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("SELECT id, name, party, symbol, description FROM candidates WHERE election_id = ?");
$stmt->bind_param("i", $election_id);
$stmt->execute();
$candidates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$user_has_voted = false;
if (isset($_SESSION['voter_id'])) {
    $stmt = $conn->prepare("SELECT id FROM votes WHERE voter_id = ? AND election_id = ?");
    $stmt->bind_param("ii", $_SESSION['voter_id'], $election_id);
    $stmt->execute();
    $user_has_voted = $stmt->get_result()->num_rows > 0;
}

echo json_encode([
    'success' => true,
    'election' => $election,
    'candidates' => $candidates,
    'user_has_voted' => $user_has_voted
]);

$stmt->close();
$conn->close();
?>
