<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';
require '../includes/telegram.php';

redirectIfNotLoggedIn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];
    $user_id = $_SESSION['user_id'];

    // Send the support message to the librarian via Telegram
    $supportMessage = "Support Request from User ID $user_id:\n$message";
    sendToTelegram($supportMessage);

    $success = "Your support request has been submitted.";
}
?>
<?php include '../templates/header.php'; ?>
<h1>Support and Feedback</h1>
<form method="POST" action="support.php">
    <label for="message">Your Message</label>
    <textarea id="message" name="message" required></textarea>
    <button type="submit">Submit</button>
</form>
<?php if (isset($success)): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>
<?php include '../templates/footer.php'; ?>
