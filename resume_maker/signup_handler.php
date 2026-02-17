<?php
// Keep errors out of HTTP output and log them to a file for debugging
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/php_errors.log');
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "resume_db");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$username = trim($_POST['username']);
$email    = trim($_POST['email']);
$password = $_POST['password'];

if (!$username || !$email || !$password) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

/* ===== CHECK EXISTING USER ===== */
$check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
$check->bind_param("ss", $username, $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Username or email already exists"]);
    exit;
}

/* ===== HASH PASSWORD ===== */
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

/* ===== GENERATE OTP ===== */
$otp = strval(rand(100000, 999999));

/* ===== INSERT USER (save OTP on creation) ===== */
$stmt = $conn->prepare("INSERT INTO users (username, email, password, otp, otp_created_at) VALUES (?,?,?,?,NOW())");
if ($stmt === false) {
    $err = $conn->error;
    $resp = ['success' => false, 'message' => 'Server error (DB prepare failed)'];
    if (in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1','::1']) || ($_SERVER['SERVER_NAME'] ?? '') === 'localhost') $resp['debug'] = $err;
    echo json_encode($resp);
    exit;
}

$stmt->bind_param("ssss", $username, $email, $hashed_password, $otp);
$executed = $stmt->execute();
if ($executed) {
    // keep email in session so verify/resend can use it
    $_SESSION['pending_verification_email'] = $email;

    /* ===== SEND EMAIL OTP ===== */
    $mailSent = mail(
        $email,
        "Resume Maker OTP Verification",
        "Your OTP is: $otp\n\nDo not share this code with anyone."
    );

    // prepare response (include OTP in response on localhost for easier dev/testing)
    $response = [
        "success" => true,
        "message" => "OTP sent to your email",
        "pending_email" => $email
    ];

    if (!$mailSent) {
        $response['warning'] = 'Mail not sent (check PHP mail configuration)';
    }

    $remote = $_SERVER['REMOTE_ADDR'] ?? '';
    $server = $_SERVER['SERVER_NAME'] ?? '';
    if (in_array($remote, ['127.0.0.1', '::1']) || $server === 'localhost') {
        $response['otp_debug'] = $otp; // dev-only helper
    }

    echo json_encode($response);

} else {
    $err = $stmt->error ?: $conn->error;
    $resp = ['success' => false, 'message' => 'Signup failed'];
    if (in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1','::1']) || ($_SERVER['SERVER_NAME'] ?? '') === 'localhost') $resp['debug'] = $err;
    echo json_encode($resp);
}

$conn->close();
?>
