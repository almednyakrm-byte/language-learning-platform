<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Define database table name
$table_name = 'معلمين';

// Define validation rules
$validation_rules = array(
    'id' => 'numeric',
    'name' => 'required|string',
    'email' => 'required|email',
    'phone' => 'numeric',
    'role' => 'in:admin,teacher'
);

// Validate input data
foreach ($validation_rules as $field => $rule) {
    if (isset($input[$field])) {
        switch ($rule) {
            case 'numeric':
                if (!is_numeric($input[$field])) {
                    http_response_code(400);
                    echo json_encode(array('error' => 'Invalid ' . $field));
                    exit;
                }
                break;
            case 'required':
                if (empty($input[$field])) {
                    http_response_code(400);
                    echo json_encode(array('error' => 'Missing ' . $field));
                    exit;
                }
                break;
            case 'string':
                if (!is_string($input[$field])) {
                    http_response_code(400);
                    echo json_encode(array('error' => 'Invalid ' . $field));
                    exit;
                }
                break;
            case 'email':
                if (!filter_var($input[$field], FILTER_VALIDATE_EMAIL)) {
                    http_response_code(400);
                    echo json_encode(array('error' => 'Invalid ' . $field));
                    exit;
                }
                break;
            case 'in':
                if (!in_array($input[$field], explode(',', $rule))) {
                    http_response_code(400);
                    echo json_encode(array('error' => 'Invalid ' . $field));
                    exit;
                }
                break;
        }
    }
}

// Sanitize input data
$input = array_map('trim', $input);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Get all records
    $stmt = $pdo->prepare("SELECT * FROM $table_name");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
    exit;
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    // Get one record by ID
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($record);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Record not found'));
        exit;
    }
}

// Handle POST request
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    // Insert new record
    $stmt = $pdo->prepare("INSERT INTO $table_name (name, email, phone, role) VALUES (:name, :email, :phone, :role)");
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->bindParam(':role', $input['role']);
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record created successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create record'));
        exit;
    }
}

// Handle PUT request
if (isset($_PUT['action']) && $_PUT['action'] == 'update') {
    // Update existing record
    $id = $_PUT['id'];
    $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, email = :email, phone = :phone, role = :role WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->bindParam(':role', $input['role']);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record updated successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update record'));
        exit;
    }
}

// Handle DELETE request
if (isset($_DELETE['action']) && $_DELETE['action'] == 'delete') {
    // Delete existing record
    $id = $_DELETE['id'];
    $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Record deleted successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete record'));
        exit;
    }
}

// Handle invalid request
http_response_code(405);
echo json_encode(array('error' => 'Invalid request method'));
exit;