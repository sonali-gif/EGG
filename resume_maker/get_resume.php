<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid resume id']);
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost","root","","resume_db");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$stmt = $conn->prepare("SELECT id, name, email, phone, skills, experience FROM resumes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Resume not found']);
    exit;
}

$resume = $result->fetch_assoc();
// return resume fields as JSON
echo json_encode($resume);

$stmt->close();
$conn->close();
?>