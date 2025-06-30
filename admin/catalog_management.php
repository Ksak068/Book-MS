<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';

redirectIfNotLoggedIn();
redirectIfNotLibrarian();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $genre = $_POST['genre'];
        $description = $_POST['description'];
        $available_copies = $_POST['available_copies'];

        $sql = "INSERT INTO books (title, author, genre, description, available_copies) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$title, $author, $genre, $description, $available_copies]);

        $success = "Book added successfully.";
    }

    if (isset($_POST['edit'])) {
        $book_id = $_POST['book_id'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $genre = $_POST['genre'];
        $description = $_POST['description'];
        $available_copies = $_POST['available_copies'];

        $sql = "UPDATE books SET title = ?, author = ?, genre = ?, description = ?, available_copies = ? WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$title, $author, $genre, $description, $available_copies, $book_id]);

        $success = "Book updated successfully.";
    }

    if (isset($_POST['delete'])) {
        $book_id = $_POST['book_id'];

        $sql = "DELETE FROM books WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$book_id]);

        $success = "Book deleted successfully.";
    }
}

$sql = "SELECT * FROM books";
$stmt = $conn->query($sql);
$books = $stmt->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h1>Catalog Management</h1>
<form method="POST" action="catalog_management.php">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" required>
    <label for="author">Author</label>
    <input type="text" id="author" name="author" required>
    <label for="genre">Genre</label>
    <input type="text" id="genre" name="genre" required>
    <label for="description">Description</label>
    <textarea id="description" name="description" required></textarea>
    <label for="available_copies">Available Copies</label>
    <input type="number" id="available_copies" name="available_copies" required>
    <button type="submit" name="add">Add Book</button>
</form>
<h2>Edit or Delete Book</h2>
<form method="POST" action="catalog_management.php">
    <label for="book_id">Book ID</label>
    <input type="number" id="book_id" name="book_id" required>
    <label for="title">Title</label>
    <input type="text" id="title" name="title">
    <label for="author">Author</label>
    <input type="text" id="author" name="author">
    <label for="genre">Genre</label>
    <input type="text" id="genre" name="genre">
    <label for="description">Description</label>
    <textarea id="description" name="description"></textarea>
    <label for="available_copies">Available Copies</label>
    <input type="number" id="available_copies" name="available_copies">
    <button type="submit" name="edit">Edit Book</button>
    <button type="submit" name="delete">Delete Book</button>
</form>
<h2>Catalog</h2>
<ul>
    <?php foreach ($books as $book): ?>
        <li><?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']); ?></li>
    <?php endforeach; ?>
</ul>
<?php if (isset($success)): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>
<?php include '../templates/footer.php'; ?>
