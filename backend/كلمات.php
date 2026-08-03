<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (empty($inputData)) {
    $inputData = $_POST;
}

// Validate and sanitize input data
if (empty($inputData)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

// Define table name
$tableName = 'كلمات';

// GET all records
if (isset($inputData['action']) && $inputData['action'] == 'get_all') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM $tableName");
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif (isset($inputData['action']) && $inputData['action'] == 'get_one') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM $tableName WHERE id = :id");
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($record) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($record);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Record not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif (isset($inputData['action']) && $inputData['action'] == 'create') {
    try {
        // Validate and sanitize input data
        if (!isset($inputData['name']) || empty($inputData['name'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid input data']);
            exit;
        }
        
        // Insert new record
        $stmt = $pdo->prepare("INSERT INTO $tableName (name) VALUES (:name)");
        $stmt->bindParam(':name', $inputData['name']);
        $stmt->execute();
        
        // Get ID of newly inserted record
        $lastID = $pdo->lastInsertId();
        
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $lastID]);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif (isset($inputData['action']) && $inputData['action'] == 'update') {
    try {
        // Validate and sanitize input data
        if (!isset($inputData['id']) || empty($inputData['id']) || !isset($inputData['name']) || empty($inputData['name'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid input data']);
            exit;
        }
        
        // Check if user is admin
        if ($userRole != 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        
        // Update existing record
        $stmt = $pdo->prepare("UPDATE $tableName SET name = :name WHERE id = :id");
        $stmt->bindParam(':name', $inputData['name']);
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->execute();
        
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif (isset($inputData['action']) && $inputData['action'] == 'delete') {
    try {
        // Check if user is admin
        if ($userRole != 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        
        // Validate and sanitize input data
        if (!isset($inputData['id']) || empty($inputData['id'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid input data']);
            exit;
        }
        
        // Delete existing record
        $stmt = $pdo->prepare("DELETE FROM $tableName WHERE id = :id");
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->execute();
        
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid action']);
}
?>