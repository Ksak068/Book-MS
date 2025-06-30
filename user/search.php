<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';

redirectIfNotLoggedIn();

$books = [];
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR genre LIKE ?";
    $stmt = $conn->prepare($sql);
    $like_search = "%$search%";
    $stmt->execute([$like_search, $like_search, $like_search]);
    $books = $stmt->fetchAll();
}
?>
<?php include '../templates/header.php'; ?>
<h1>Search Library</h1>
<form method="GET" action="search.php">
    <label for="searchQuery">Search</label>
    <input type="text" id="searchQuery" name="search" required>
    <button type="submit">Search</button>
</form>
<div class="results">
    <h2>Search Results</h2>
    <?php if (!empty($books)): ?>
        <ul>
            <?php foreach ($books as $book): ?>
                <li><?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</div>
<?php include '../templates/footer.php'; ?>
