<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "resume_db");

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";

// Validate input
if (empty($username) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

// Validate username format
if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Username must be 3-50 characters, letters, numbers, or underscores only"]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid email format"]);
    exit;
}

// Validate password length
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters"]);
    exit;
}

// Check if username already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["success" => false, "message" => "Username already exists"]);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["success" => false, "message" => "Email already registered"]);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("sss", $username, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Account created successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to create account"]);
}

$stmt->close();
$conn->close();
?>
