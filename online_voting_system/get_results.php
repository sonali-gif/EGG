<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

$election_id = 1; // Current election

$stmt = $conn->prepare("SELECT id, title, description, status, (SELECT COUNT(*) FROM votes WHERE election_id = ?) as total_votes FROM elections WHERE id = ?");
$stmt->bind_param("ii", $election_id, $election_id);
$stmt->execute();
$election = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("
    SELECT 
        c.id,
        c.name as candidate_name,
        c.party,
        c.symbol,
        COUNT(v.id) as votes,
        (SELECT COUNT(*) FROM votes WHERE election_id = ?) as total_votes
    FROM candidates c
    LEFT JOIN votes v ON c.id = v.candidate_id AND v.election_id = ?
    WHERE c.election_id = ?
    GROUP BY c.id
    ORDER BY votes DESC
");
$stmt->bind_param("iii", $election_id, $election_id, $election_id);
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'success' => true,
    'election' => $election,
    'results' => $results
]);

$stmt->close();
$conn->close();
?>
