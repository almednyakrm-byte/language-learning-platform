<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Read inputs from JSON body
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request to retrieve all languages
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is logged in
    if (!$userRole) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    try {
        // Prepare SQL query to retrieve all languages
        $stmt = $pdo->prepare('SELECT * FROM languages');
        $stmt->execute();

        // Fetch and return all languages
        $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($languages);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle GET request to retrieve a single language by ID
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($inputData['id'])) {
    // Check if user is logged in
    if (!$userRole) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    try {
        // Prepare SQL query to retrieve a single language by ID
        $stmt = $pdo->prepare('SELECT * FROM languages WHERE id = :id');
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->execute();

        // Fetch and return a single language
        $language = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($language) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($language);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Language not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle POST request to create a new language
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!$userRole) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate and sanitize input data
    if (!isset($inputData['name']) || !isset($inputData['code'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    try {
        // Prepare SQL query to create a new language
        $stmt = $pdo->prepare('INSERT INTO languages (name, code) VALUES (:name, :code)');
        $stmt->bindParam(':name', $inputData['name']);
        $stmt->bindParam(':code', $inputData['code']);
        $stmt->execute();

        // Return the newly created language
        $newLanguage = $pdo->lastInsertId();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $newLanguage, 'name' => $inputData['name'], 'code' => $inputData['code']]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT request to update an existing language
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and has admin role
    if (!$userRole || $userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate and sanitize input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['code'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    try {
        // Prepare SQL query to update an existing language
        $stmt = $pdo->prepare('UPDATE languages SET name = :name, code = :code WHERE id = :id');
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->bindParam(':name', $inputData['name']);
        $stmt->bindParam(':code', $inputData['code']);
        $stmt->execute();

        // Return the updated language
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['id' => $inputData['id'], 'name' => $inputData['name'], 'code' => $inputData['code']]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE request to delete a language
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and has admin role
    if (!$userRole || $userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate and sanitize input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    try {
        // Prepare SQL query to delete a language
        $stmt = $pdo->prepare('DELETE FROM languages WHERE id = :id');
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->execute();

        // Return a success message
        http_response_code(200);
        echo json_encode(['message' => 'Language deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Return a 405 Method Not Allowed response for unsupported methods
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}