<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Check if user is admin
$is_admin = ($user_role == 'admin');

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method == 'GET') {
    // Validate and sanitize input parameters
    $params = [];
    if (isset($_GET['id'])) {
        $params['id'] = intval($_GET['id']);
    }

    // Prepare SQL query
    $sql = 'SELECT * FROM مناهج';
    if (isset($params['id'])) {
        $sql .= ' WHERE id = :id';
    }

    // Execute query using PDO Prepared Statements
    try {
        $stmt = $pdo->prepare($sql);
        if (isset($params['id'])) {
            $stmt->bindParam(':id', $params['id']);
        }
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
} elseif ($method == 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare SQL query
    $sql = 'INSERT INTO مناهج (name, description) VALUES (:name, :description)';

    // Execute query using PDO Prepared Statements
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Manhaj created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
} elseif ($method == 'PUT') {
    // Validate and sanitize input parameters
    $params = [];
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }
    $params['id'] = intval($_GET['id']);
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare SQL query
    $sql = 'UPDATE مناهج SET name = :name, description = :description WHERE id = :id';

    // Execute query using PDO Prepared Statements
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $params['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Manhaj updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
} elseif ($method == 'DELETE') {
    // Validate and sanitize input parameters
    $params = [];
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }
    $params['id'] = intval($_GET['id']);

    // Prepare SQL query
    $sql = 'DELETE FROM مناهج WHERE id = :id';

    // Execute query using PDO Prepared Statements
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $params['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Manhaj deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}