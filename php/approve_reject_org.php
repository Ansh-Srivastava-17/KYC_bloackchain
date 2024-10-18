<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $org_id = $_POST['org_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE organizations SET status = ? WHERE id = ?");
    
    try {
        $stmt->execute([$status, $org_id]);
        echo json_encode(['success' => true, 'message' => 'Organization status updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
