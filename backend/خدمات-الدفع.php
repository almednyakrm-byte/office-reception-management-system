<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Get all services
    $stmt = $pdo->prepare('SELECT * FROM services');
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return services
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($services);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Get request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize data
    if (!isset($data['name']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    
    // Sanitize data
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    
    // Insert new service
    $stmt = $pdo->prepare('INSERT INTO services (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    
    // Return new service
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Service created successfully'));
    exit;
}

// Handle PUT request
if ($method === 'PUT') {
    // Get request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize data
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    
    // Sanitize data
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Update service
    $stmt = $pdo->prepare('UPDATE services SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    
    // Return updated service
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Service updated successfully'));
    exit;
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Get request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize data
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    
    // Sanitize data
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Delete service
    $stmt = $pdo->prepare('DELETE FROM services WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Return deleted service
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Service deleted successfully'));
    exit;
}