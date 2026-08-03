<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM طلاب');
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($students);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM طلاب WHERE id = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($student) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($student);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    try {
        // Validate input data
        if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }

        // Sanitize input data
        $name = htmlspecialchars($input['name']);
        $email = htmlspecialchars($input['email']);
        $phone = htmlspecialchars($input['phone']);

        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO طلاب (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        // Get inserted ID
        $id = $pdo->lastInsertId();

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    try {
        // Validate input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }

        // Sanitize input data
        $id = htmlspecialchars($input['id']);
        $name = htmlspecialchars($input['name']);
        $email = htmlspecialchars($input['email']);
        $phone = htmlspecialchars($input['phone']);

        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE طلاب SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    try {
        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }

        // Sanitize input data
        $id = htmlspecialchars($input['id']);

        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM طلاب WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}
?>