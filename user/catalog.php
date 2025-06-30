<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';

redirectIfNotLoggedIn();

$sql = "SELECT * FROM books";
$stmt = $conn->query($sql);
$books = $stmt->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h1>Catalog</h1>
<ul>
    <?php foreach ($books as $book): ?>
        <li><?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']); ?></li>
    <?php endforeach; ?>
</ul>
<?php include '../templates/footer.php'; ?>
