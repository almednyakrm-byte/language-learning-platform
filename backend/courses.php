<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'You must be logged in to access this resource.']);
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize the database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize the input
    $course_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if the course ID is provided
    if ($course_id) {
        // SQL query to select a course by ID
        $stmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
        $stmt->bindParam(':id', $course_id);
        $stmt->execute();
        $course = $stmt->fetch();

        // Check if the course exists
        if ($course) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($course);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Course not found.']);
        }
    } else {
        // SQL query to select all courses
        $stmt = $pdo->prepare('SELECT * FROM courses');
        $stmt->execute();
        $courses = $stmt->fetchAll();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($courses);
    }
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'You do not have permission to create courses.']);
        exit;
    }

    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check if the input is valid
    if ($name && $description) {
        // SQL query to insert a new course
        $stmt = $pdo->prepare('INSERT INTO courses (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Course created successfully.']);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid input data.']);
    }
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'You do not have permission to update courses.']);
        exit;
    }

    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $course_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check if the input is valid
    if ($course_id && $name && $description) {
        // SQL query to update a course
        $stmt = $pdo->prepare('UPDATE courses SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $course_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Course updated successfully.']);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid input data.']);
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'You do not have permission to delete courses.']);
        exit;
    }

    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $course_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if the input is valid
    if ($course_id) {
        // SQL query to delete a course
        $stmt = $pdo->prepare('DELETE FROM courses WHERE id = :id');
        $stmt->bindParam(':id', $course_id);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Course deleted successfully.']);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Invalid input data.']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Method not allowed.']);
}