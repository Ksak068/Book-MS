<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';

redirectIfNotLoggedIn();
redirectIfNotLibrarian();

include '../templates/header.php';
?>
<h1>Admin Dashboard</h1>
<nav>
    <a href="catalog_management.php">Catalog Management</a>
    <a href="inventory_control.php">Inventory Control</a>
    <a href="circulation_management.php">Circulation Management</a>
    <a href="user_management.php">User Management</a>
    <a href="system_management.php">System Management</a>
</nav>
<?php include '../templates/footer.php'; ?>
