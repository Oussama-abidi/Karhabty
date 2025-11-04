<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Get the message and receiver ID
$message = $_POST['message'] ?? '';
$receiverId = $_POST['receiver_id'] ?? null;

if (!$message || !$receiverId) {
    die("Message and receiver ID are required.");
}

// Insert the message into the database
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $_SESSION['user_id'], $receiverId, $message);
$stmt->execute();
$stmt->close();

// Redirect back to the chat page
header("Location: chat.php?" . ($_SESSION['role'] === 'mechanic' ? "client_id=$receiverId" : "mechanic_id=$receiverId"));
exit;