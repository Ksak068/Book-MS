<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Library Management System</h1>
        <nav>
            <a href="../index.php">Home</a>
            <a href="../user/search.php">Search</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="../register.php">Register</a>
                <a href="../login.php">Login</a>
            <?php else: ?>
                <?php if ($_SESSION['role'] == 'librarian'): ?>
                    <a href="../admin/dashboard.php">Admin Dashboard</a>
                <?php else: ?>
                    <a href="../user/home.php">Dashboard</a>
                    <a href="../user/profile.php">Profile</a>
                    <a href="../user/support.php">Support</a>
                <?php endif; ?>
                <a href="../logout.php">Logout</a>
            <?php endif; ?>
        </nav>
        <div class="content">
