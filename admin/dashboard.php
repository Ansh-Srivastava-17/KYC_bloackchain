<?php
session_start();
require_once '../php/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../index.html');
    exit;
}

// Fetch pending organizations
$stmt = $pdo->query("SELECT * FROM organizations WHERE status = 'pending'");
$pending_orgs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/main.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <main>
        <h2>Pending Organizations</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php foreach ($pending_orgs as $org): ?>
            <tr>
                <td><?= htmlspecialchars($org['name']) ?></td>
                <td><?= htmlspecialchars($org['email']) ?></td>
                <td>
                    <button onclick="approveOrg(<?= $org['id'] ?>)">Approve</button>
                    <button onclick="rejectOrg(<?= $org['id'] ?>)">Reject</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <script src="../js/admin.js"></script>
    <footer>
        <a href="../php/logout.php">Logout</a>
    </footer>
</body>
</html>
</body>
</html>