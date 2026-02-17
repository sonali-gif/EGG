<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "voting_system");

$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
$end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';

if (empty($title) || empty($description) || empty($start_date) || empty($end_date)) {
    echo json_encode(["success" => false, "message" => "All fields required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO elections (title, description, start_date, end_date, status) VALUES (?, ?, ?, ?, 'active')");
$stmt->bind_param("ssss", $title, $description, $start_date, $end_date);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Election created"]);
} else {
    echo json_encode(["success" => false, "message" => "Error creating election"]);
}

$stmt->close();
$conn->close();
?>
