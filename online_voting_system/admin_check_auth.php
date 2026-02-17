<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in (simplified - use proper auth in production)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // For demo: allow access with special voter ID
    $conn = new mysqli("localhost", "root", "", "voting_system");
    
    if (isset($_SESSION['voter_id'])) {
        $voter_id = $_SESSION['voter_id'];
        $stmt = $conn->prepare("SELECT is_admin, full_name FROM voters WHERE id = ?");
        $stmt->bind_param("i", $voter_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result && $result['is_admin']) {
            $_SESSION['is_admin'] = true;
            echo json_encode([
                'authenticated' => true,
                'is_admin' => true,
                'admin_name' => $result['full_name']
            ]);
            exit;
        }
    }
    
    echo json_encode(['authenticated' => false, 'is_admin' => false]);
} else {
    echo json_encode([
        'authenticated' => true,
        'is_admin' => true,
        'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
    ]);
}
?>
