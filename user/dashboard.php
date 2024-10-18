<?php
session_start();
require_once '../php/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header('Location: ../index.html');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's documents
$stmt = $pdo->prepare("SELECT * FROM documents WHERE user_id = ?");
$stmt->execute([$user_id]);
$documents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../styles/main.css">
</head>
<body>
    <header>
        <h1>User Dashboard</h1>
    </header>
    <main>
        <h2>Upload Documents</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <label for="document_a">Document A:</label>
            <input type="file" id="document_a" name="document_a" required>
            
            <label for="document_b">Document B:</label>
            <input type="file" id="document_b" name="document_b" required>
            
            <label for="document_c">Document C:</label>
            <input type="file" id="document_c" name="document_c" required>
            
            <button type="submit">Upload Documents</button>
        </form>

        <h2>Your Documents</h2>
        <table>
            <tr>
                <th>Document Type</th>
                <th>Status</th>
                <th>Uploaded At</th>
            </tr>
            <?php foreach ($documents as $doc): ?>
            <tr>
                <td><?= htmlspecialchars($doc['document_type']) ?></td>
                <td><?= htmlspecialchars($doc['status']) ?></td>
                <td><?= htmlspecialchars($doc['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <script src="../js/user.js"></script>
    <footer>
        <a href="../php/logout.php">Logout</a>
    </footer>
</body>
</html>
</body>
</html>
