<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';
require '../includes/telegram.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$books = [];

// Fetch all available books
$sql = "SELECT * FROM books WHERE available_copies > 0";
$stmt = $conn->query($sql);
$books = $stmt->fetchAll();

// Handle book borrowing request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrow'])) {
    $book_id = $_POST['book_id'];
    $issue_date = date('Y-m-d');
    
    // Insert a borrowing request with status 'pending'
    $sql = "INSERT INTO transactions (user_id, book_id, issue_date, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $book_id, $issue_date]);

    // Send the borrowing request to the librarian via Telegram
    $message = "New borrowing request:\nUser ID: $user_id\nBook ID: $book_id\nIssue Date: $issue_date";
    sendToTelegram($message);

    header("Location: borrow_return.php");
    exit();
}

// Fetch user transactions for returning books
$sql = "SELECT t.transaction_id, b.title, b.author, t.issue_date, t.return_date FROM transactions t JOIN books b ON t.book_id = b.book_id WHERE t.user_id = ? AND t.status = 'issued'";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();

// Handle book returning
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return'])) {
    $transaction_id = $_POST['transaction_id'];
    $return_date = date('Y-m-d');

    // Fetch the transaction details to check for fine
    $sql = "SELECT issue_date, return_date FROM transactions WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$transaction_id]);
    $transaction = $stmt->fetch();

    $issue_date = new DateTime($transaction['issue_date']);
    $return_date_obj = new DateTime($return_date);
    $expected_return_date = new DateTime($transaction['return_date']);
    $fine = 0;

    if ($return_date_obj > $expected_return_date) {
        $fine = 250; // Fine of 250 GHC
    }

    // Update the transaction
    $sql = "UPDATE transactions SET return_date = ?, status = 'returned', fine = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$return_date, $fine, $transaction_id]);

    // Update the available copies in the books table
    $sql = "UPDATE books SET available_copies = available_copies + 1 WHERE book_id = (SELECT book_id FROM transactions WHERE transaction_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$transaction_id]);

    header("Location: borrow_return.php");
    exit();
}
?>
<?php include '../templates/header.php'; ?>
<h1>Borrow and Return Books</h1>

<h2>Borrow a Book</h2>
<form method="POST" action="borrow_return.php">
    <label for="book_id">Select Book</label>
    <select id="book_id" name="book_id" required>
        <?php foreach ($books as $book): ?>
            <option value="<?php echo $book['book_id']; ?>"><?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="borrow">Request Borrow</button>
</form>

<h2>Return a Book</h2>
<form method="POST" action="borrow_return.php">
    <label for="transaction_id">Select Book</label>
    <select id="transaction_id" name="transaction_id" required>
        <?php foreach ($transactions as $transaction): ?>
            <option value="<?php echo $transaction['transaction_id']; ?>"><?php echo htmlspecialchars($transaction['title']) . " by " . htmlspecialchars($transaction['author']); ?> (Issued on <?php echo htmlspecialchars($transaction['issue_date']); ?>)</option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="return">Return Book</button>
</form>
<?php include '../templates/footer.php'; ?>
