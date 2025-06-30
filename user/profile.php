<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    $sql = "UPDATE users SET name = ?, email = ?" . ($password ? ", password = ?" : "") . " WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($password) {
        $stmt->execute([$name, $email, $password, $user_id]);
    } else {
        $stmt->execute([$name, $email, $user_id]);
    }
    $success = "Profile updated successfully.";
}

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<?php include '../templates/header.php'; ?>
<h1>Profile</h1>
<form method="POST" action="profile.php">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    <label for="password">New Password (leave blank to keep current)</label>
    <input type="password" id="password" name="password">
    <button type="submit">Update Profile</button>
</form>
<?php if (isset($success)): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>
<?php include '../templates/footer.php'; ?>
