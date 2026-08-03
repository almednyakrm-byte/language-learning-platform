<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define allowed roles for each operation
$allowedRoles = array(
    'GET' => 'user',
    'POST' => 'user',
    'PUT' => 'admin',
    'DELETE' => 'admin'
);

// Check if user has permission to perform the requested operation
if ($input['action'] != 'GET' && $_SESSION['user_role'] != $allowedRoles[$input['action']]) {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Validate input data
if ($input['action'] == 'POST') {
    // Validate required fields
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
}

// Process the request
switch ($input['action']) {
    case 'GET':
        // Retrieve all languages
        $stmt = $pdo->prepare('SELECT * FROM languages');
        $stmt->execute();
        $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($languages);
        break;
    case 'POST':
        // Insert new language
        $name = $pdo->quote($input['name']);
        $description = $pdo->quote($input['description']);
        $stmt = $pdo->prepare('INSERT INTO languages (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('message' => 'Language created successfully'));
        break;
    case 'PUT':
        // Update existing language
        $id = $input['id'];
        $name = $pdo->quote($input['name']);
        $description = $pdo->quote($input['description']);
        $stmt = $pdo->prepare('UPDATE languages SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Language updated successfully'));
        break;
    case 'DELETE':
        // Delete language
        $id = $input['id'];
        $stmt = $pdo->prepare('DELETE FROM languages WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Language deleted successfully'));
        break;
    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}



// db.php
<?php
$pdo = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>