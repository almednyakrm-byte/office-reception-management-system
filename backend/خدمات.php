<?php
require_once 'db.php';

// Get user role and authentication status
$userRole = $_SESSION['userRole'] ?? null;
$authenticated = $_SESSION['authenticated'] ?? false;

// Check if user is authenticated and authorized
if (!$authenticated || ($userRole !== 'admin' && $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'DELETE')) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true) ?? $_POST;

// Validate input data
if (!isset($inputData['id']) && !isset($inputData['name']) && !isset($inputData['description'])) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

// Sanitize input data
$inputData['name'] = trim($inputData['name'] ?? '');
$inputData['description'] = trim($inputData['description'] ?? '');

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM services');
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($services);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO services (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Service created successfully']);
        exit;
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create service']);
        exit;
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $stmt = $pdo->prepare('UPDATE services SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Service updated successfully']);
        exit;
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update service']);
        exit;
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $stmt = $pdo->prepare('DELETE FROM services WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Service deleted successfully']);
        exit;
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete service']);
        exit;
    }
}