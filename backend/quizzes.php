<?php
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
    $quiz_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if quiz ID is provided
    if ($quiz_id) {
        // SQL query to retrieve a single quiz
        $stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = :id');
        $stmt->bindParam(':id', $quiz_id);
        $stmt->execute();
        $quiz = $stmt->fetch();

        // Check if quiz exists
        if ($quiz) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($quiz);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Quiz not found']);
        }
    } else {
        // SQL query to retrieve all quizzes
        $stmt = $pdo->prepare('SELECT * FROM quizzes');
        $stmt->execute();
        $quizzes = $stmt->fetchAll();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($quizzes);
    }
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

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Check if required fields are provided
    if (!$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // SQL query to create a new quiz
    $stmt = $pdo->prepare('INSERT INTO quizzes (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Quiz created successfully']);
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

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $quiz_id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Check if required fields are provided
    if (!$quiz_id || !$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // SQL query to retrieve the quiz
    $stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = :id');
    $stmt->bindParam(':id', $quiz_id);
    $stmt->execute();
    $quiz = $stmt->fetch();

    // Check if quiz exists
    if (!$quiz) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Quiz not found']);
        exit;
    }

    // SQL query to update the quiz
    $stmt = $pdo->prepare('UPDATE quizzes SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $quiz_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Quiz updated successfully']);
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

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $quiz_id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if required fields are provided
    if (!$quiz_id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // SQL query to retrieve the quiz
    $stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = :id');
    $stmt->bindParam(':id', $quiz_id);
    $stmt->execute();
    $quiz = $stmt->fetch();

    // Check if quiz exists
    if (!$quiz) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Quiz not found']);
        exit;
    }

    // SQL query to delete the quiz
    $stmt = $pdo->prepare('DELETE FROM quizzes WHERE id = :id');
    $stmt->bindParam(':id', $quiz_id);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Quiz deleted successfully']);
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}