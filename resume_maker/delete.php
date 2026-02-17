<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$conn = new mysqli("localhost","root","","resume_db");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

$id = intval($_POST['id']);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM resumes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Delete failed"]);
}

$stmt->close();
$conn->close();
?>
