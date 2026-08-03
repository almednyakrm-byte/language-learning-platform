<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Get the request data
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

// Connect to the database
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate the request data
    if (isset($data['id'])) {
        // Get a student by ID
        $stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->bindParam(':id', $data['id']);
        $stmt->execute();
        $student = $stmt->fetch();
        if ($student) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($student);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Student not found']);
        }
    } else {
        // Get all students
        $stmt = $pdo->prepare('SELECT * FROM students');
        $stmt->execute();
        $students = $stmt->fetchAll();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($students);
    }
}

// Handle POST requests
elseif ($method == 'POST') {
    // Validate the request data
    if (empty($data['name']) || empty($data['email'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Name and email are required']);
    } else {
        // Insert a new student
        $stmt = $pdo->prepare('INSERT INTO students (name, email) VALUES (:name, :email)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->execute();
        $studentId = $pdo->lastInsertId();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $studentId, 'name' => $data['name'], 'email' => $data['email']]);
    }
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate the request data
    if (empty($data['id']) || empty($data['name']) || empty($data['email'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'ID, name, and email are required']);
    } else {
        // Update a student
        $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email WHERE id = :id');
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['id' => $data['id'], 'name' => $data['name'], 'email' => $data['email']]);
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate the request data
    if (empty($data['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'ID is required']);
    } else {
        // Delete a student
        $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
        $stmt->bindParam(':id', $data['id']);
        $stmt->execute();
        http_response_code(204);
        header('Content-Type: application/json');
    }
}

// Close the database connection
$pdo = null;