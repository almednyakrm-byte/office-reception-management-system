<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response indicating their status
    echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
    exit;
}

// Check if the user is attempting to register or login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the request is for registration or login
    if (isset($_POST['action']) && $_POST['action'] == 'register') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            // Sanitize and validate user input
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Check if the username and email are unique
            $query = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$username, $email]);
            $result = $stmt->fetch();

            // If the username or email is already taken, send an error response
            if ($result) {
                echo json_encode(array('status' => 'error', 'message' => 'Username or email already taken'));
                exit;
            }

            // Hash the password for secure storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$username, $email, $hashed_password]);

            // Send a success response
            echo json_encode(array('status' => 'success', 'message' => 'User registered successfully'));
        } else {
            // If any required fields are missing, send an error response
            echo json_encode(array('status' => 'error', 'message' => 'Missing required fields'));
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'login') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Sanitize and validate user input
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Check if the username and password are valid
            $query = "SELECT * FROM users WHERE username = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$username]);
            $result = $stmt->fetch();

            // If the username is not found, send an error response
            if (!$result) {
                echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
                exit;
            }

            // Verify the password using password_verify()
            if (password_verify($password, $result['password'])) {
                // If the password is valid, log the user in
                $_SESSION['user_id'] = $result['id'];
                echo json_encode(array('status' => 'success', 'message' => 'User logged in successfully'));
            } else {
                // If the password is invalid, send an error response
                echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
            }
        } else {
            // If any required fields are missing, send an error response
            echo json_encode(array('status' => 'error', 'message' => 'Missing required fields'));
        }
    }
}

// Check if the user is attempting to logout
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();
    echo json_encode(array('status' => 'success', 'message' => 'User logged out successfully'));
}

// If the user is not logged in, send a JSON response indicating their status
echo json_encode(array('status' => 'logged_out'));


This code handles user registration, login, logout, and checks the current session user status. It includes prepared statements for secure database interactions, password hashing, and verification. It also uses JSON responses for AJAX calls and includes descriptive comments for security checks and session handling.