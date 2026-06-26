<?php
// Start the session to handle user authentication
session_start();

// Import the database connection
require_once 'db.php';

// Check if the request method is GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check the current session status
    if (isset($_SESSION['user_id'])) {
        // User is logged in, return the user ID
        echo json_encode(['status' => 'logged_in', 'user_id' => $_SESSION['user_id']]);
    } else {
        // User is not logged in, return a not logged in status
        echo json_encode(['status' => 'not_logged_in']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the action parameter
    if (isset($_POST['action'])) {
        // Handle login action
        if ($_POST['action'] === 'login') {
            // Check if the username and password fields are set
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // Prepare the SQL query to select the user by username
                $stmt = $db->prepare('SELECT id, password FROM users WHERE username = ?');
                $stmt->bind_param('s', $_POST['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                // Check if the user exists and the password is correct
                if ($user && password_verify($_POST['password'], $user['password'])) {
                    // Set the user ID in the session
                    $_SESSION['user_id'] = $user['id'];
                    echo json_encode(['status' => 'logged_in', 'user_id' => $user['id']]);
                } else {
                    echo json_encode(['status' => 'invalid_credentials']);
                }
            } else {
                // Invalid request, missing username or password
                echo json_encode(['status' => 'invalid_request']);
            }
        } 
        // Handle register action
        elseif ($_POST['action'] === 'register') {
            // Check if the username, email, and password fields are set
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                // Prepare the SQL query to insert a new user
                $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->bind_param('sss', $_POST['username'], $_POST['email'], $password_hash);
                if ($stmt->execute()) {
                    // Get the ID of the newly inserted user
                    $user_id = $db->insert_id;
                    // Set the user ID in the session
                    $_SESSION['user_id'] = $user_id;
                    echo json_encode(['status' => 'registered', 'user_id' => $user_id]);
                } else {
                    echo json_encode(['status' => 'registration_failed']);
                }
            } else {
                // Invalid request, missing username, email, or password
                echo json_encode(['status' => 'invalid_request']);
            }
        } 
        // Handle logout action
        elseif ($_POST['action'] === 'logout') {
            // Unset the user ID from the session
            unset($_SESSION['user_id']);
            echo json_encode(['status' => 'logged_out']);
        }
    } else {
        // Invalid request, missing action parameter
        echo json_encode(['status' => 'invalid_request']);
    }
} else {
    // Invalid request method
    echo json_encode(['status' => 'invalid_method']);
}