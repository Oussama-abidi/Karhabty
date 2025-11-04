<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: login.html");
        exit();
    }

    // Prepare query to fetch user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$stmt) {
        error_log("Error preparing user query: " . $conn->error);
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: login.html");
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify user and password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        // Role-based redirection
        if ($user['role'] === 'mechanic') {
            $stmtMechanic = $conn->prepare("SELECT * FROM mechanics WHERE user_id = ?");
            if (!$stmtMechanic) {
                error_log("Error preparing mechanic query: " . $conn->error);
                $_SESSION['error'] = "An error occurred. Please try again.";
                header("Location: login.html");
                exit();
            }
            $stmtMechanic->bind_param("i", $user['id']);
            $stmtMechanic->execute();
            $mechanic = $stmtMechanic->get_result()->fetch_assoc();
            if (!$mechanic) {
                error_log("No mechanic found for user_id: " . $user['id']);
                $_SESSION['error'] = "Mechanic information not found.";
                header("Location: login.html");
                exit();
            }
            $_SESSION['location'] = $mechanic['location'];
            $_SESSION['rating'] = $mechanic['rating'];
            $stmtMechanic->close();
            header("Location: index.php");
        } else {
            $stmtClient = $conn->prepare("SELECT * FROM clients WHERE user_id = ?");
            if (!$stmtClient) {
                error_log("Error preparing client query: " . $conn->error);
                $_SESSION['error'] = "An error occurred. Please try again.";
                header("Location: login.html");
                exit();
            }
            $stmtClient->bind_param("i", $user['id']);
            $stmtClient->execute();
            $client = $stmtClient->get_result()->fetch_assoc();
            if (!$client) {
                error_log("No client found for user_id: " . $user['id']);
                $_SESSION['error'] = "Client information not found.";
                header("Location: login.html");
                exit();
            }
            $_SESSION['car_type'] = $client['car_type'];
            $_SESSION['location'] = $client['location'];
            $stmtClient->close();
            header("Location: index.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.html");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.html");
    exit();
}
