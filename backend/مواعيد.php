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

// Define API endpoints
$endpoints = [
    '/muaawid' => [
        'GET' => function() use ($db) {
            // Select all appointments
            $stmt = $db->prepare('SELECT * FROM muaawid');
            $stmt->execute();
            $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($appointments);
        },
        'POST' => function() use ($db, $inputData) {
            // Validate and sanitize input data
            if (!isset($inputData['title']) || !isset($inputData['date']) || !isset($inputData['time'])) {
                http_response_code(400);
                return json_encode(['error' => 'Invalid input data']);
            }
            $title = $db->quote($inputData['title']);
            $date = $db->quote($inputData['date']);
            $time = $db->quote($inputData['time']);

            // Insert new appointment
            $stmt = $db->prepare('INSERT INTO muaawid (title, date, time) VALUES (:title, :date, :time)');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);
            $stmt->execute();

            // Return created appointment
            $newAppointment = $db->lastInsertId();
            return json_encode(['id' => $newAppointment]);
        }
    ],
    '/muaawid/{id}' => [
        'GET' => function($id) use ($db) {
            // Select appointment by ID
            $stmt = $db->prepare('SELECT * FROM muaawid WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$appointment) {
                http_response_code(404);
                return json_encode(['error' => 'Appointment not found']);
            }
            return json_encode($appointment);
        },
        'PUT' => function($id) use ($db, $inputData, $userRole, $userID) {
            // Validate and sanitize input data
            if (!isset($inputData['title']) || !isset($inputData['date']) || !isset($inputData['time'])) {
                http_response_code(400);
                return json_encode(['error' => 'Invalid input data']);
            }
            if ($userRole !== 'admin') {
                http_response_code(403);
                return json_encode(['error' => 'Forbidden']);
            }

            $title = $db->quote($inputData['title']);
            $date = $db->quote($inputData['date']);
            $time = $db->quote($inputData['time']);

            // Update appointment
            $stmt = $db->prepare('UPDATE muaawid SET title = :title, date = :date, time = :time WHERE id = :id');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Return updated appointment
            return json_encode(['message' => 'Appointment updated successfully']);
        },
        'DELETE' => function($id) use ($db, $userRole, $userID) {
            if ($userRole !== 'admin') {
                http_response_code(403);
                return json_encode(['error' => 'Forbidden']);
            }

            // Delete appointment
            $stmt = $db->prepare('DELETE FROM muaawid WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Return deleted appointment
            return json_encode(['message' => 'Appointment deleted successfully']);
        }
    ]
];

// Get endpoint and method from URL
$endpoint = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Get ID from URL parameter
$id = null;
if (strpos($endpoint, '/') !== false) {
    $id = explode('/', $endpoint)[count(explode('/', $endpoint)) - 1];
}

// Check if endpoint and method are valid
if (!isset($endpoints[$endpoint]) || !isset($endpoints[$endpoint][$method])) {
    http_response_code(405);
    return json_encode(['error' => 'Method not allowed']);
}

// Call endpoint function
$response = $endpoints[$endpoint][$method]($id);

// Set HTTP response headers
http_response_code(200);
header('Content-Type: application/json');

// Output response
echo $response;