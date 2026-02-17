<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

$election_id = isset($_POST['election_id']) ? (int)$_POST['election_id'] : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$party = isset($_POST['party']) ? trim($_POST['party']) : '';
$symbol = isset($_POST['symbol']) ? trim($_POST['symbol']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';

if ($election_id <= 0 || empty($name) || empty($party) || empty($symbol)) {
    echo json_encode(["success" => false, "message" => "All required fields missing"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO candidates (election_id, name, party, symbol, description) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $election_id, $name, $party, $symbol, $description);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Candidate added"]);
} else {
    echo json_encode(["success" => false, "message" => "Error adding candidate"]);
}

$stmt->close();
$conn->close();
?>
