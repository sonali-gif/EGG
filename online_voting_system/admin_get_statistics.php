<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

$total_elections = $conn->query("SELECT COUNT(*) as count FROM elections")->fetch_assoc()['count'];
$total_candidates = $conn->query("SELECT COUNT(*) as count FROM candidates")->fetch_assoc()['count'];
$total_voters = $conn->query("SELECT COUNT(*) as count FROM voters WHERE is_admin = 0")->fetch_assoc()['count'];
$total_votes = $conn->query("SELECT COUNT(*) as count FROM votes")->fetch_assoc()['count'];

echo json_encode([
    'total_elections' => $total_elections,
    'total_candidates' => $total_candidates,
    'total_voters' => $total_voters,
    'total_votes' => $total_votes
]);

$conn->close();
?>
