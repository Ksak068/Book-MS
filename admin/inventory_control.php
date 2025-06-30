<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';

redirectIfNotLoggedIn();
redirectIfNotLibrarian();

$sql = "SELECT * FROM books";
$stmt = $conn->query($sql);
$books = $stmt->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h1>Inventory Control</h1>
<ul>
    <?php foreach ($books as $book): ?>
        <li><?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']) . " - " . htmlspecialchars($book['available_copies']) . " copies available"; ?></li>
    <?php endforeach; ?>
</ul>
<?php include '../templates/footer.php'; ?>
