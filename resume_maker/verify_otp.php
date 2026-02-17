<?php
// Keep errors out of HTTP output and log them to a file for debugging
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/php_errors.log');
session_start();
header('Content-Type: application/json');
$conn = new mysqli("localhost","root","","resume_db");
if ($conn->connect_error) {
    echo json_encode(['success'=>false,'message'=>'Database connection failed']);
    exit;
}

$otp = trim($_POST['otp'] ?? '');
if (!$otp || !preg_match('/^\d{6}$/', $otp)) {
    echo json_encode(['success'=>false,'message'=>'Invalid OTP']);
    exit;
}

// find user by otp
$stmt = $conn->prepare("SELECT id, otp_created_at FROM users WHERE otp = ? LIMIT 1");
$stmt->bind_param("s", $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success'=>false,'message'=>'Wrong OTP']);
    exit;
}

$user = $result->fetch_assoc();

// check expiry (10 minutes)
if ($user['otp_created_at']) {
    $created = strtotime($user['otp_created_at']);
    if ($created < time() - 10 * 60) {
        echo json_encode(['success'=>false,'message'=>'OTP expired']);
        exit;
    }
}

// verify and clear otp
$update = $conn->prepare("UPDATE users SET is_verified=1, otp=NULL, otp_created_at=NULL WHERE id = ?");
$update->bind_param("i", $user['id']);
$update->execute();

if ($update->affected_rows > 0) {
    echo json_encode(['success'=>true,'message'=>'Account verified']);
} else {
    echo json_encode(['success'=>false,'message'=>'Verification failed']);
}

$conn->close();
?>
