<?php
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "resume_db");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

if (!$username || !$password) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

/* ===== FETCH USER ===== */
$stmt = $conn->prepare("SELECT id, username, password, is_verified FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
    exit;
}

$user = $result->fetch_assoc();

/* ===== VERIFY PASSWORD ===== */
if (!password_verify($password, $user['password'])) {
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
    exit;
}

/* ===== CHECK EMAIL VERIFIED ===== */
if ($user['is_verified'] == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Please verify your email with OTP before login"
    ]);
    exit;
}

/* ===== LOGIN SUCCESS ===== */
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

echo json_encode([
    "success" => true,
    "message" => "Login successful"
]);

$conn->close();
?>
