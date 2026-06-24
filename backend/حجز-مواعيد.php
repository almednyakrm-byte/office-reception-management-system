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
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

// Define table name
$tableName = 'حجز مواعيد';

// GET all records
if (isset($inputData['action']) && $inputData['action'] == 'get_all') {
    // Check user role
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query
    $sql = "SELECT * FROM $tableName";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
    exit;
}

// GET single record
if (isset($inputData['action']) && $inputData['action'] == 'get_one') {
    // Check user role
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate ID
    if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // SQL query
    $sql = "SELECT * FROM $tableName WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($record);
    exit;
}

// POST new record
if (isset($inputData['action']) && $inputData['action'] == 'create') {
    // Check user role
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate data
    if (!isset($inputData['name']) || !isset($inputData['date']) || !isset($inputData['time'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data']);
        exit;
    }

    // Sanitize data
    $name = htmlspecialchars($inputData['name']);
    $date = htmlspecialchars($inputData['date']);
    $time = htmlspecialchars($inputData['time']);

    // SQL query
    $sql = "INSERT INTO $tableName (name, date, time, user_id) VALUES (:name, :date, :time, :user_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':user_id', $userID);
    $stmt->execute();

    // Output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record created successfully']);
    exit;
}

// PUT update record
if (isset($inputData['action']) && $inputData['action'] == 'update') {
    // Check user role
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate ID
    if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // Validate data
    if (!isset($inputData['name']) || !isset($inputData['date']) || !isset($inputData['time'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data']);
        exit;
    }

    // Sanitize data
    $name = htmlspecialchars($inputData['name']);
    $date = htmlspecialchars($inputData['date']);
    $time = htmlspecialchars($inputData['time']);

    // SQL query
    $sql = "UPDATE $tableName SET name = :name, date = :date, time = :time WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
    exit;
}

// DELETE record
if (isset($inputData['action']) && $inputData['action'] == 'delete') {
    // Check user role
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate ID
    if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // SQL query
    $sql = "DELETE FROM $tableName WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
    exit;
}

// Default response
http_response_code(404);
echo json_encode(['error' => 'Not found']);
exit;

?>