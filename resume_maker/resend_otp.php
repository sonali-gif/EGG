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

// Accept email from session or POST
$email = trim($_POST['email'] ?? $_SESSION['pending_verification_email'] ?? '');
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success'=>false,'message'=>'Valid email required']);
    exit;
}

// Find user
$stmt = $conn->prepare("SELECT id, is_verified, otp_created_at FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success'=>false,'message'=>'No user with that email']);
    exit;
}

$user = $result->fetch_assoc();
if ($user['is_verified']) {
    echo json_encode(['success'=>false,'message'=>'Account already verified']);
    exit;
}

// cooldown: 60 seconds between resends
if ($user['otp_created_at']) {
    $last = strtotime($user['otp_created_at']);
    if ($last > time() - 60) {
        $wait = 60 - (time() - $last);
        echo json_encode(['success'=>false,'message'=>"Please wait {$wait} seconds before resending"]);
        exit;
    }
}

// generate and save OTP
$otp = strval(rand(100000, 999999));
$update = $conn->prepare("UPDATE users SET otp = ?, otp_created_at = NOW() WHERE id = ?");
$update->bind_param("si", $otp, $user['id']);
$update->execute();

// send email
$mailSent = mail($email, "Resume Maker OTP Verification (resend)", "Your OTP is: $otp\n\nDo not share this code with anyone.");

// keep pending email in session
$_SESSION['pending_verification_email'] = $email;

$response = ['success' => true, 'message' => 'OTP resent to email'];
if (!$mailSent) $response['warning'] = 'Mail not sent (check PHP mail configuration)';

$remote = $_SERVER['REMOTE_ADDR'] ?? '';
$server = $_SERVER['SERVER_NAME'] ?? '';
if (in_array($remote, ['127.0.0.1', '::1']) || $server === 'localhost') {
    $response['otp_debug'] = $otp;
}

echo json_encode($response);

$conn->close();
?>