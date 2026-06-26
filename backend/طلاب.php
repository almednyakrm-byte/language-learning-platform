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

// Define routes
$routes = array(
    'GET' => array('/' => 'get_all', '/:id' => 'get_one'),
    'POST' => '/create',
    'PUT' => array('/:id' => 'update'),
    'DELETE' => array('/:id' => 'delete')
);

// Get route and method
$route = explode('/', $_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];
$method = strtoupper($method);

// Check if route exists
if (!isset($routes[$method][$route[1]])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Call corresponding function
$func = $routes[$method][$route[1]];
$func();

// Function to get all students
function get_all() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM طلاب");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($students);
}

// Function to get one student
function get_one() {
    global $pdo;
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM طلاب WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$student) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($student);
}

// Function to create a new student
function create() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO طلاب (name, email, phone) VALUES (:name, :email, :phone)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student created successfully'));
}

// Function to update a student
function update() {
    global $pdo;
    $id = $_GET['id'];
    // Validate input
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Update in database
    $stmt = $pdo->prepare("UPDATE طلاب SET name = :name, email = :email, phone = :phone WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student updated successfully'));
}

// Function to delete a student
function delete() {
    global $pdo;
    $id = $_GET['id'];
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM طلاب WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student deleted successfully'));
}
?>