<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isLibrarian() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'librarian';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}

function redirectIfNotLibrarian() {
    if (!isLibrarian()) {
        header('Location: ../user/home.php');
        exit();
    }
}
?>
