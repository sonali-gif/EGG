<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['voter_id'])) {
    session_unset();
    session_destroy();
}

echo json_encode(['success' => true]);
?>
