<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Determine if the user is a client or a mechanic
$isMechanic = ($_SESSION['role'] === 'mechanic');

// Get the other user's ID (client_id or mechanic_id) from the query parameter
$otherUserId = $isMechanic ? ($_GET['client_id'] ?? null) : ($_GET['mechanic_id'] ?? null);

if (!$otherUserId) {
    die($isMechanic ? "Client ID is required." : "Mechanic ID is required.");
}

// Fetch the other user's details
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $otherUserId);
$stmt->execute();
$result = $stmt->get_result();
$otherUser = $result->fetch_assoc();
$stmt->close();

if (!$otherUser) {
    die($isMechanic ? "Client not found." : "Mechanic not found.");
}

// Fetch chat messages
$stmt = $conn->prepare("
    SELECT sender_id, message, created_at
    FROM messages
    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at ASC
");
$stmt->bind_param("iiii", $_SESSION['user_id'], $otherUserId, $otherUserId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($otherUser['username']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>Chat with <?php echo htmlspecialchars($otherUser['username']); ?></h2>
    <div class="chat-box border p-3 mb-3" style="height: 400px; overflow-y: scroll;">
        <?php foreach ($messages as $message): ?>
            <div class="<?php echo $message['sender_id'] == $_SESSION['user_id'] ? 'text-end' : 'text-start'; ?>">
                <p class="mb-1"><strong><?php echo $message['sender_id'] == $_SESSION['user_id'] ? 'You' : htmlspecialchars($otherUser['username']); ?>:</strong></p>
                <p class="bg-light p-2 rounded"><?php echo htmlspecialchars($message['message']); ?></p>
                <small class="text-muted"><?php echo $message['created_at']; ?></small>
            </div>
        <?php endforeach; ?>
    </div>
    <form method="POST" action="send_message.php">
        <input type="hidden" name="receiver_id" value="<?php echo $otherUserId; ?>">
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
            <button type="submit" class="btn btn-primary">Send</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>