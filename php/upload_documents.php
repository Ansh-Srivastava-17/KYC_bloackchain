<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

function sendJsonResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Error handling
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

try {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
        sendJsonResponse(false, 'Unauthorized access');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJsonResponse(false, 'Invalid request method');
    }

    $user_id = $_SESSION['user_id'];
    $upload_dir = __DIR__ . '/../uploads/';  // Use absolute path
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            sendJsonResponse(false, "Failed to create upload directory");
        }
    }
    
    // Check if directory is writable
    if (!is_writable($upload_dir)) {
        sendJsonResponse(false, "Upload directory is not writable");
    }

    $document_types = ['a', 'b', 'c'];
    $uploaded_files = [];

    foreach ($document_types as $type) {
        $file_key = "document_$type";
        if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) {
            sendJsonResponse(false, "Document $type is missing or invalid");
        }

        $tmp_name = $_FILES[$file_key]['tmp_name'];
        $name = basename($_FILES[$file_key]['name']);
        $upload_path = $upload_dir . $user_id . '_' . $type . '_' . $name;

        if (!move_uploaded_file($tmp_name, $upload_path)) {
            sendJsonResponse(false, "Failed to upload document $type. Error: " . error_get_last()['message']);
        }

        $uploaded_files[] = [
            'type' => $type,
            'path' => $upload_path
        ];
    }

    $stmt = $pdo->prepare("INSERT INTO documents (user_id, document_type, file_path) VALUES (?, ?, ?)");

    $pdo->beginTransaction();
    foreach ($uploaded_files as $file) {
        $stmt->execute([$user_id, $file['type'], $file['path']]);
    }
    $pdo->commit();

    sendJsonResponse(true, 'Documents uploaded successfully');

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    sendJsonResponse(false, 'Error: ' . $e->getMessage());
}