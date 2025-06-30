<?php
session_start();
require 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $id_card = $_POST['id_card'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Using user-entered password
    $role = $_POST['role'];

    $sql = "INSERT INTO users (name, email, id_card, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $email, $id_card, $password, $role]);

    header("Location: login.php");
    exit();
}
?>

<?php include 'templates/header.php'; ?>
<link rel="stylesheet" href="css/styles.css">
<h1>Register</h1>
<form method="POST" action="register.php">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" required>
    
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
    
    <label for="id_card">ID Card Number</label>
    <input type="text" id="id_card" name="id_card" required>
    
    <!-- Password Field -->
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    
    <label for="role">Role</label>
    <select id="role" name="role">
        <option value="user">User</option>
        <option value="librarian">Librarian</option>
    </select>
    
    <button type="submit">Register</button>
</form>
<?php include 'templates/footer.php'; ?>
