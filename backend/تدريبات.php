<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/get' => 'get',
    '/post' => 'post',
    '/put/:id' => 'put',
    '/delete/:id' => 'delete'
);

// Get route
$route = $_SERVER['REQUEST_URI'];
foreach ($routes as $pattern => $method) {
    if (preg_match('/^' . preg_quote($pattern, '/') . '$/', $route, $matches)) {
        $route = $method;
        break;
    }
}

// Call corresponding method
if ($route == 'get') {
    get();
} elseif ($route == 'post') {
    post();
} elseif ($route == 'put') {
    put();
} elseif ($route == 'delete') {
    delete();
}

// Helper functions
function get() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM تدريبات');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
}

function post() {
    global $db;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    
    // Prepare insert statement
    $stmt = $db->prepare('INSERT INTO تدريبات (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    
    // Execute insert statement
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Training created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create training'));
    }
}

function put() {
    global $db;
    // Get id from route
    $id = (int) $_GET['id'];
    
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Prepare update statement
    $stmt = $db->prepare('UPDATE تدريبات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    
    // Execute update statement
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Training updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update training'));
    }
}

function delete() {
    global $db;
    // Get id from route
    $id = (int) $_GET['id'];
    
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Prepare delete statement
    $stmt = $db->prepare('DELETE FROM تدريبات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    
    // Execute delete statement
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Training deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete training'));
    }
}