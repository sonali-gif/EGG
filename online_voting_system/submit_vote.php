<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

if (!isset($_SESSION['voter_id'])) {
    echo json_encode(["success" => false, "message" => "Not authenticated"]);
    exit;
}

$election_id = isset($_POST['election_id']) ? (int)$_POST['election_id'] : 1;
$candidate_id = isset($_POST['candidate_id']) ? (int)$_POST['candidate_id'] : 0;
$voter_id = $_SESSION['voter_id'];

if ($candidate_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid candidate"]);
    exit;
}

// Check if voter already voted
$stmt = $conn->prepare("SELECT id FROM votes WHERE voter_id = ? AND election_id = ?");
$stmt->bind_param("ii", $voter_id, $election_id);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "You have already voted"]);
    exit;
}

// Insert vote
$stmt = $conn->prepare("INSERT INTO votes (voter_id, candidate_id, election_id, vote_timestamp) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iii", $voter_id, $candidate_id, $election_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Vote submitted"]);
} else {
    echo json_encode(["success" => false, "message" => "Error submitting vote"]);
}

$stmt->close();
$conn->close();
?>
