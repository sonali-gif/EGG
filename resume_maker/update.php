<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
$conn = new mysqli("localhost","root","","resume_db");
if ($conn->connect_error) {
    http_response_code(500);
    die('Database error');
}

// validate input
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$user_id = $_SESSION['user_id'];
$skills = isset($_POST['skills']) ? trim($_POST['skills']) : '';
$experience = isset($_POST['experience']) ? trim($_POST['experience']) : '';

if ($id <= 0 || $skills === '' ) {
    header('Location: my_resumes.php?updated=0');
    exit;
}

// use prepared statement
$stmt = $conn->prepare("UPDATE resumes SET skills = ?, experience = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param('ssii', $skills, $experience, $id, $user_id);
$ok = $stmt->execute();
$stmt->close();
$conn->close();

header('Location: my_resumes.php?updated=' . ($ok ? '1' : '0'));
exit;
?>
