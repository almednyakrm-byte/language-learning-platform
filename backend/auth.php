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
        $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
    } else {
        // User is not logged in, return a not logged in status
        $response = array('status' => 'not_logged_in');
    }
    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the action parameter
    if (isset($_POST['action'])) {
        // Handle the login action
        if ($_POST['action'] === 'login') {
            // Check if the username and password fields are set
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // Prepare the SQL query to select the user
                $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
                // Bind the username parameter
                $stmt->bind_param('s', $_POST['username']);
                // Execute the query
                $stmt->execute();
                // Get the result
                $result = $stmt->get_result();
                // Check if a user was found
                if ($result->num_rows > 0) {
                    // Get the user data
                    $user = $result->fetch_assoc();
                    // Check if the password is correct
                    if (password_verify($_POST['password'], $user['password'])) {
                        // Password is correct, start a new session
                        $_SESSION['user_id'] = $user['id'];
                        // Send a logged in response
                        $response = array('status' => 'logged_in', 'user_id' => $user['id']);
                    } else {
                        // Password is incorrect, send an error response
                        $response = array('status' => 'error', 'message' => 'Invalid password');
                    }
                } else {
                    // User not found, send an error response
                    $response = array('status' => 'error', 'message' => 'User not found');
                }
            } else {
                // Username or password field is missing, send an error response
                $response = array('status' => 'error', 'message' => 'Missing username or password');
            }
        } // Handle the register action
        elseif ($_POST['action'] === 'register') {
            // Check if the username, email, and password fields are set
            if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                // Check if the username and email are valid
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && ctype_alnum($_POST['username'])) {
                    // Prepare the SQL query to insert the new user
                    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                    // Hash the password
                    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    // Bind the parameters
                    $stmt->bind_param('sss', $_POST['username'], $_POST['email'], $password_hash);
                    // Execute the query
                    if ($stmt->execute()) {
                        // User created successfully, start a new session
                        $_SESSION['user_id'] = $db->insert_id;
                        // Send a registered response
                        $response = array('status' => 'registered', 'user_id' => $db->insert_id);
                    } else {
                        // Error creating user, send an error response
                        $response = array('status' => 'error', 'message' => 'Error creating user');
                    }
                } else {
                    // Invalid username or email, send an error response
                    $response = array('status' => 'error', 'message' => 'Invalid username or email');
                }
            } else {
                // Username, email, or password field is missing, send an error response
                $response = array('status' => 'error', 'message' => 'Missing username, email, or password');
            }
        } // Handle the logout action
        elseif ($_POST['action'] === 'logout') {
            // Destroy the session
            session_destroy();
            // Send a logged out response
            $response = array('status' => 'logged_out');
        }
    } else {
        // Action parameter is missing, send an error response
        $response = array('status' => 'error', 'message' => 'Missing action parameter');
    }
    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request method, send an error response
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    header('Content-Type: application/json');
    echo json_encode($response);
}