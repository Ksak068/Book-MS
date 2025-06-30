<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';

redirectIfNotLoggedIn();
redirectIfNotLibrarian();

$sql = "SELECT * FROM users";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h1>User Management</h1>
<ul>
    <?php foreach ($users as $user): ?>
        <li><?php echo htmlspecialchars($user['name']) . " (" . htmlspecialchars($user['email']) . ") - " . htmlspecialchars($user['role']); ?></li>
    <?php endforeach; ?>
</ul>
<?php include '../templates/footer.php'; ?>
