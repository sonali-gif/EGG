<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$voter_id = isset($_POST['voter_id']) ? trim($_POST['voter_id']) : '';
$full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($voter_id) || empty($full_name) || empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "All fields required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email"]);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM voters WHERE voter_id = ?");
$stmt->bind_param("s", $voter_id);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Voter ID already exists"]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO voters (voter_id, full_name, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $voter_id, $full_name, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registration successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Registration failed"]);
}

$stmt->close();
$conn->close();
?>
