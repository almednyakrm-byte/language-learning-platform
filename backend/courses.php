<?php
// Import database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Handle different HTTP methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Retrieve all courses or a specific course by id
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $course = $stmt->fetch();
            if ($course) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($course);
            } else {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Course not found']);
            }
        } else {
            $stmt = $pdo->prepare('SELECT * FROM courses');
            $stmt->execute();
            $courses = $stmt->fetchAll();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($courses);
        }
        break;

    case 'POST':
        // Create a new course
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden access']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['name']) || empty($data['description'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }
        $stmt = $pdo->prepare('INSERT INTO courses (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        if ($stmt->execute()) {
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Course created successfully']);
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Failed to create course']);
        }
        break;

    case 'PUT':
        // Update a course
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden access']);
            exit;
        }
        $id = $_GET['id'];
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($id) || empty($data['name']) || empty($data['description'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }
        $stmt = $pdo->prepare('UPDATE courses SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        if ($stmt->execute()) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Course updated successfully']);
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Failed to update course']);
        }
        break;

    case 'DELETE':
        // Delete a course
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden access']);
            exit;
        }
        $id = $_GET['id'];
        if (empty($id)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }
        $stmt = $pdo->prepare('DELETE FROM courses WHERE id = :id');
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Course deleted successfully']);
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Failed to delete course']);
        }
        break;

    default:
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Method not allowed']);
        break;
}