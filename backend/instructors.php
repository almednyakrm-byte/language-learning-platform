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
    $instructor_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if instructor ID is provided
    if ($instructor_id) {
        // SQL query to retrieve instructor by ID
        $stmt = $pdo->prepare('SELECT * FROM instructors WHERE id = :id');
        $stmt->bindParam(':id', $instructor_id);
        $stmt->execute();

        // Fetch instructor data
        $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if instructor exists
        if ($instructor) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($instructor);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Instructor not found']);
        }
    } else {
        // SQL query to retrieve all instructors
        $stmt = $pdo->prepare('SELECT * FROM instructors');
        $stmt->execute();

        // Fetch all instructors
        $instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($instructors);
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
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_VALIDATE_EMAIL);

    // Check if input is valid
    if ($name && $email) {
        // SQL query to insert new instructor
        $stmt = $pdo->prepare('INSERT INTO instructors (name, email) VALUES (:name, :email)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Get inserted instructor ID
        $instructor_id = $pdo->lastInsertId();

        // SQL query to retrieve inserted instructor
        $stmt = $pdo->prepare('SELECT * FROM instructors WHERE id = :id');
        $stmt->bindParam(':id', $instructor_id);
        $stmt->execute();

        // Fetch inserted instructor data
        $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode($instructor);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
    }
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
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $instructor_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_VALIDATE_EMAIL);

    // Check if input is valid
    if ($instructor_id && $name && $email) {
        // SQL query to update instructor
        $stmt = $pdo->prepare('UPDATE instructors SET name = :name, email = :email WHERE id = :id');
        $stmt->bindParam(':id', $instructor_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // SQL query to retrieve updated instructor
        $stmt = $pdo->prepare('SELECT * FROM instructors WHERE id = :id');
        $stmt->bindParam(':id', $instructor_id);
        $stmt->execute();

        // Fetch updated instructor data
        $instructor = $stmt->fetch(PDO::FETCH_ASSOC);

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($instructor);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
    }
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
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $instructor_id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if input is valid
    if ($instructor_id) {
        // SQL query to delete instructor
        $stmt = $pdo->prepare('DELETE FROM instructors WHERE id = :id');
        $stmt->bindParam(':id', $instructor_id);
        $stmt->execute();

        http_response_code(204);
        header('Content-Type: application/json');
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
    }
}

// Close database connection
$pdo = null;