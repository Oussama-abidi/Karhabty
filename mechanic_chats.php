<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is a mechanic
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'mechanic') {
    header("Location: login.php");
    exit;
}

// Fetch chats for the mechanic
$mechanicId = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT DISTINCT u.id AS client_id, u.username AS client_name
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.receiver_id = ?
");
$stmt->bind_param("i", $mechanicId);
$stmt->execute();
$result = $stmt->get_result();
$chats = [];
while ($row = $result->fetch_assoc()) {
    $chats[] = $row;
}
$stmt->close();

// Debugging: Check the chats array
// echo "<pre>";
// print_r($chats);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Chats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>Your Chats</h2>
    <?php if (!empty($chats)): ?>
        <ul class="list-group">
            <?php foreach ($chats as $chat): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?php echo htmlspecialchars($chat['client_name']); ?></span>
                    <a href="chat.php?client_id=<?php echo htmlspecialchars($chat['client_id']); ?>" class="btn btn-primary btn-sm">View Chat</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No chats available.</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>