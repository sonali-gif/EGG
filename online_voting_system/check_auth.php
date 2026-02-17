<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['voter_id'])) {
    echo json_encode([
        'authenticated' => true,
        'voter_id' => $_SESSION['voter_id'],
        'voter_name' => $_SESSION['voter_name'] ?? 'Voter'
    ]);
} else {
    echo json_encode(['authenticated' => false]);
}
?>
