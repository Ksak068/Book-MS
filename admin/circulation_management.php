<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';
require '../includes/telegram.php';

redirectIfNotLoggedIn();
redirectIfNotLibrarian();

// Handle borrowing approval
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve'])) {
    $transaction_id = $_POST['transaction_id'];
    $return_date = $_POST['return_date'];

    // Update the transaction status to 'issued' and set return date
    $sql = "UPDATE transactions SET status = 'issued', return_date = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$return_date, $transaction_id]);

    // Decrease available copies
    $sql = "UPDATE books SET available_copies = available_copies - 1 WHERE book_id = (SELECT book_id FROM transactions WHERE transaction_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$transaction_id]);

    // Notify user via Telegram
    $message = "Borrowing request approved:\nTransaction ID: $transaction_id\nReturn Date: $return_date\nFine: 250 GHC if not returned by this date.";
    sendToTelegram($message);

    header("Location: circulation_management.php");
    exit();
}

// Handle borrowing rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reject'])) {
    $transaction_id = $_POST['transaction_id'];

    // Delete the transaction
    $sql = "DELETE FROM transactions WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$transaction_id]);

    // Notify user via Telegram
    $message = "Borrowing request rejected:\nTransaction ID: $transaction_id";
    sendToTelegram($message);

    header("Location: circulation_management.php");
    exit();
}

// Fetch all pending borrowing requests
$sql = "SELECT t.transaction_id, u.name, b.title, b.author, t.issue_date FROM transactions t JOIN books b ON t.book_id = b.book_id JOIN users u ON t.user_id = u.user_id WHERE t.status = 'pending'";
$stmt = $conn->query($sql);
$requests = $stmt->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<h1>Circulation Management</h1>

<h2>Pending Borrowing Requests</h2>
<?php if (!empty($requests)): ?>
    <ul>
        <?php foreach ($requests as $request): ?>
            <li>
                <?php echo "User: " . htmlspecialchars($request['name']) . " | Book: " . htmlspecialchars($request['title']) . " by " . htmlspecialchars($request['author']) . " | Requested on: " . htmlspecialchars($request['issue_date']); ?>
                <form method="POST" action="circulation_management.php" style="display:inline;">
                    <input type="hidden" name="transaction_id" value="<?php echo $request['transaction_id']; ?>">
                    <label for="return_date">Specify Return Date</label>
                    <input type="date" name="return_date" required>
                    <button type="submit" name="approve">Approve</button>
                    <button type="submit" name="reject">Reject</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No pending requests.</p>
<?php endif; ?>
<?php include '../templates/footer.php'; ?>
