<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON body
$input_data = json_decode(file_get_contents('php://input'), true);

// Handle GET request to retrieve all teachers
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepare SELECT query
        $stmt = $pdo->prepare('SELECT * FROM معلمون');
        $stmt->execute();
        
        // Fetch and return all teachers
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teachers);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle POST request to create a new teacher
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($input_data['اسم المعلم']) || !isset($input_data['رقم الهاتف']) || !isset($input_data['البريد الالكتروني'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }
    
    // Sanitize input data
    $name = filter_var($input_data['اسم المعلم'], FILTER_SANITIZE_STRING);
    $phone = filter_var($input_data['رقم الهاتف'], FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($input_data['البريد الالكتروني'], FILTER_SANITIZE_EMAIL);
    
    // Check if user is admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    try {
        // Prepare INSERT query
        $stmt = $pdo->prepare('INSERT INTO معلمون (اسم المعلم, رقم الهاتف, البريد الالكتروني) VALUES (:name, :phone, :email)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        // Return success response
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle GET request to retrieve a single teacher
elseif (isset($input_data['id'])) {
    try {
        // Prepare SELECT query
        $stmt = $pdo->prepare('SELECT * FROM معلمون WHERE id = :id');
        $stmt->bindParam(':id', $input_data['id']);
        $stmt->execute();
        
        // Fetch and return single teacher
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($teacher) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($teacher);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Teacher not found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle PUT request to update a teacher
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate input data
    if (!isset($input_data['id']) || !isset($input_data['اسم المعلم']) || !isset($input_data['رقم الهاتف']) || !isset($input_data['البريد الالكتروني'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }
    
    // Sanitize input data
    $name = filter_var($input_data['اسم المعلم'], FILTER_SANITIZE_STRING);
    $phone = filter_var($input_data['رقم الهاتف'], FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($input_data['البريد الالكتروني'], FILTER_SANITIZE_EMAIL);
    
    // Check if user is admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    try {
        // Prepare UPDATE query
        $stmt = $pdo->prepare('UPDATE معلمون SET اسم المعلم = :name, رقم الهاتف = :phone, البريد الالكتروني = :email WHERE id = :id');
        $stmt->bindParam(':id', $input_data['id']);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        // Return success response
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle DELETE request to delete a teacher
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate input data
    if (!isset($input_data['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }
    
    // Check if user is admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    try {
        // Prepare DELETE query
        $stmt = $pdo->prepare('DELETE FROM معلمون WHERE id = :id');
        $stmt->bindParam(':id', $input_data['id']);
        $stmt->execute();
        
        // Return success response
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}