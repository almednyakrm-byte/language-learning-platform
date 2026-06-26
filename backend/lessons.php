<?php
// Import database connection
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $lesson_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure: Select all lessons or a specific lesson by id
    if ($lesson_id) {
        $stmt = $pdo->prepare('SELECT * FROM lessons WHERE id = :id');
        $stmt->bindParam(':id', $lesson_id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM lessons');
    }
    
    // Execute query
    $stmt->execute();
    
    // Output processing: Return lessons in JSON format
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($lessons);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $title = filter_var($data['title'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);
    
    // Check if required fields are present
    if (!$title || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // SQL query structure: Insert new lesson
    $stmt = $pdo->prepare('INSERT INTO lessons (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    
    // Execute query
    $stmt->execute();
    
    // Output processing: Return created lesson id
    $lesson_id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $lesson_id]);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $lesson_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $title = filter_var($data['title'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);
    
    // Check if required fields are present
    if (!$lesson_id || (!$title && !$description)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // SQL query structure: Update existing lesson
    $stmt = $pdo->prepare('UPDATE lessons SET title = COALESCE(:title, title), description = COALESCE(:description, description) WHERE id = :id');
    $stmt->bindParam(':id', $lesson_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    
    // Execute query
    $stmt->execute();
    
    // Output processing: Return updated lesson id
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $lesson_id]);
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $lesson_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if required fields are present
    if (!$lesson_id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // SQL query structure: Delete existing lesson
    $stmt = $pdo->prepare('DELETE FROM lessons WHERE id = :id');
    $stmt->bindParam(':id', $lesson_id);
    
    // Execute query
    $stmt->execute();
    
    // Output processing: Return deleted lesson id
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $lesson_id]);
}

// Handle other request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}