<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';

redirectIfNotLoggedIn();

include '../templates/header.php';
?>
<h1>Dashboard</h1>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
<nav>
    <a href="catalog.php">View Catalog</a>
    <a href="borrow_return.php">Borrow/Return Books</a>
    <a href="profile.php">Profile</a>
    <a href="support.php">Support</a>
</nav>
<?php include '../templates/footer.php'; ?>
