<?php
require_once 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = $_POST['username'];
    $email     = $_POST['email'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userType  = $_POST['userType']; 
    $carType   = $_POST['car_type']; 
    $location  = $_POST['location'];
    $bio       = isset($_POST['bio']) ? $_POST['bio'] : null;

    // Check if username or email already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $_SESSION['error'] = "Username or Email already exists.";
        header("Location: signup.php");
        exit;
    }
    $checkStmt->close();

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $_SESSION['error'] = "Error preparing user insert: " . $conn->error;
        header("Location: signup.php");
        exit;
    }

    $stmt->bind_param("ssss", $username, $email, $password, $userType);

    if ($stmt->execute()) {
        $userId = $conn->insert_id; // get the ID of the inserted user

        if ($userType === 'client') {
            $stmtClient = $conn->prepare("INSERT INTO clients (user_id,car_type, location) VALUES (?, ?, ?)");
            if (!$stmtClient) {
                error_log("Error preparing client insert: " . $conn->error);
                $_SESSION['error'] = "Error preparing client insert: " . $conn->error;
                header("Location: signup.php");
                exit;
            }
            $stmtClient->bind_param("iss", $userId, $carType, $location);
            $stmtClient->execute();
            $stmtClient->close();

        } elseif ($userType === 'mechanic') {
            $stmtMechanic = $conn->prepare("INSERT INTO mechanics (user_id, location, car_type, bio) VALUES (?, ?, ?, ?)");
            if (!$stmtMechanic) {
                $_SESSION['error'] = "Error preparing mechanic insert: " . $conn->error;
                header("Location: signup.php");
                exit;
            }
            $stmtMechanic->bind_param("isss", $userId, $location, $carType, $bio);
            $stmtMechanic->execute();
            $stmtMechanic->close();
        }

        $_SESSION['success'] = "Registration successful!";
        header("Location: index.php");
        exit;

    } else {
        $_SESSION['error'] = "Error registering user: " . $stmt->error;
        header("Location: signup.php");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: signup.php");
    exit;
}
