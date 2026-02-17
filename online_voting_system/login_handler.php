<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$voter_id = isset($_POST['voter_id']) ? trim($_POST['voter_id']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($voter_id) || empty($password)) {
    echo json_encode(["success" => false, "message" => "All fields required"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, full_name, password FROM voters WHERE voter_id = ?");
$stmt->bind_param("s", $voter_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
    exit;
}

$voter = $result->fetch_assoc();

if (password_verify($password, $voter["password"])) {
    $_SESSION["voter_id"] = $voter["id"];
    $_SESSION["voter_name"] = $voter["full_name"];
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}

$stmt->close();
$conn->close();
?>
