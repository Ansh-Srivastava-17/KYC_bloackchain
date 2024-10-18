<?php
session_start();
require_once '../php/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'organization') {
    header('Location: ../index.html');
    exit;
}

// Fetch all users and their documents
$stmt = $pdo->query("
    SELECT u.id as user_id, u.name, u.email, d.id as doc_id, d.document_type, d.status, d.created_at
    FROM users u
    LEFT JOIN documents d ON u.id = d.user_id
    ORDER BY u.id, d.created_at DESC
");
$users_and_docs = $stmt->fetchAll();

// Group documents by user
$users = [];
foreach ($users_and_docs as $row) {
    if (!isset($users[$row['user_id']])) {
        $users[$row['user_id']] = [
            'id' => $row['user_id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'documents' => []
        ];
    }
    if ($row['doc_id']) {
        $users[$row['user_id']]['documents'][] = [
            'id' => $row['doc_id'],
            'type' => $row['document_type'],
            'status' => $row['status'],
            'created_at' => $row['created_at']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Dashboard</title>
    <link rel="stylesheet" href="../styles/main.css">
</head>
<body>
    <header>
        <h1>Organization Dashboard</h1>
    </header>
    <main>
        <h2>Registered Users and Their Documents</h2>
        <?php foreach ($users as $user): ?>
            <div class="user-section">
                <h3><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</h3>
                <?php if (empty($user['documents'])): ?>
                    <p>No documents uploaded yet.</p>
                <?php else: ?>
                    <table>
                        <tr>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Uploaded At</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($user['documents'] as $doc): ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['type']) ?></td>
                            <td><?= htmlspecialchars($doc['status']) ?></td>
                            <td><?= htmlspecialchars($doc['created_at']) ?></td>
                            <td>
                                <?php if ($doc['status'] === 'pending'): ?>
                                    <button onclick="approveDoc(<?= $doc['id'] ?>)">Approve</button>
                                    <button onclick="rejectDoc(<?= $doc['id'] ?>)">Reject</button>
                                <?php else: ?>
                                    <?= ucfirst($doc['status']) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </main>
    <script src="../js/org.js"></script>
    <footer>
        <a href="../php/logout.php">Logout</a>
    </footer>
</body>
</html>
</body>
</html>
