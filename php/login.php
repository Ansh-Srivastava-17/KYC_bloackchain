<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === 'admin@example.com' && $password === 'admin') {
        $_SESSION['user_id'] = 'admin';
        $_SESSION['user_type'] = 'admin';
        echo json_encode(['success' => true, 'redirect' => 'admin/dashboard.php']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = 'user';
        echo json_encode(['success' => true, 'redirect' => 'user/dashboard.php']);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM organizations WHERE email = ?");
        $stmt->execute([$email]);
        $org = $stmt->fetch();

        if ($org && password_verify($password, $org['password'])) {
            if ($org['status'] === 'approved') {
                $_SESSION['user_id'] = $org['id'];
                $_SESSION['user_type'] = 'organization';
                echo json_encode(['success' => true, 'redirect' => 'organization/dashboard.php']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Organization not yet approved']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
