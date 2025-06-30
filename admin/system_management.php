<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';

redirectIfNotLoggedIn();
redirectIfNotLibrarian();

include '../templates/header.php';
?>
<h1>System Management</h1>
<p>System management functionalities can be implemented here.</p>
<?php include '../templates/footer.php'; ?>
