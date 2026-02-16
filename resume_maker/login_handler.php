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
$password = isset($_POST["password"]) ? $_POST["password"] : "";

// Validate input
if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Username and password are required"]);
    exit;
}

// Prevent username injection by limiting length
if (strlen($username) > 50) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid username"]);
    exit;
}

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
    $stmt->close();
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();

// Verify password
if (password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $username;
    echo json_encode(["success" => true, "message" => "Login successful"]);
} else {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
}

$stmt->close();
$conn->close();
?>
