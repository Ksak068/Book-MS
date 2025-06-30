<?php
session_start();
require 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $id_card = $_POST['id_card'];
    $password = $_POST['password'];

    // Fetch user details based on email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verify ID card number and password
    if ($user && $user['id_card'] === $id_card && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'librarian') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/home.php");
        }
        exit();
    } else {
        $error = "Invalid email, ID card number, or password";
    }
}
?>

<?php include 'templates/header.php'; ?>
<link rel="stylesheet" href="css/styles.css">
<h1>Login</h1>
<form method="POST" action="login.php">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
    
    <label for="id_card">ID Card Number</label>
    <input type="text" id="id_card" name="id_card" required>
    
    <!-- Password Field -->
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Login</button>
</form>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php include 'templates/footer.php'; ?>
