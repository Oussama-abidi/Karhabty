<?php
$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "karhbty";

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
