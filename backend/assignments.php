<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'You must be logged in to access this resource']);
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
    $assignment_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($assignment_id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid assignment ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('SELECT * FROM assignments WHERE id = :id');
    $stmt->bindParam(':id', $assignment_id);
    $stmt->execute();
    $assignment = $stmt->fetch();

    // Process output
    if ($assignment === false) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Assignment not found']);
    } else {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($assignment);
    }
}

// Handle POST requests
elseif ($method == 'POST') {
    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request body']);
        exit;
    }

    $title = filter_var($input['title'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'] ?? null, FILTER_SANITIZE_STRING);
    $due_date = filter_var($input['due_date'] ?? null, FILTER_SANITIZE_STRING);

    if ($title === null || $description === null || $due_date === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('INSERT INTO assignments (title, description, due_date) VALUES (:title, :description, :due_date)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->execute();

    // Process output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Assignment created successfully']);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You do not have permission to edit assignments']);
        exit;
    }

    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request body']);
        exit;
    }

    $assignment_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if ($assignment_id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid assignment ID']);
        exit;
    }

    $title = filter_var($input['title'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'] ?? null, FILTER_SANITIZE_STRING);
    $due_date = filter_var($input['due_date'] ?? null, FILTER_SANITIZE_STRING);

    if ($title === null || $description === null || $due_date === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('UPDATE assignments SET title = :title, description = :description, due_date = :due_date WHERE id = :id');
    $stmt->bindParam(':id', $assignment_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->execute();

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Assignment updated successfully']);
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You do not have permission to delete assignments']);
        exit;
    }

    // Validate and sanitize input
    $assignment_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($assignment_id === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid assignment ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('DELETE FROM assignments WHERE id = :id');
    $stmt->bindParam(':id', $assignment_id);
    $stmt->execute();

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Assignment deleted successfully']);
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}