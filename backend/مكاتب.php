<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (!$inputData) {
    $inputData = $_POST;
}

// Define CRUD operations
function getOffices() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM offices");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOffice($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM offices WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createOffice() {
    global $pdo;
    $data = array(
        'name' => $_POST['name'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'country' => $_POST['country']
    );
    $stmt = $pdo->prepare("INSERT INTO offices (name, address, city, country) VALUES (:name, :address, :city, :country)");
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':city', $data['city']);
    $stmt->bindParam(':country', $data['country']);
    $stmt->execute();
    return $pdo->lastInsertId();
}

function updateOffice($id) {
    global $pdo;
    $data = array(
        'name' => $_POST['name'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'country' => $_POST['country']
    );
    $stmt = $pdo->prepare("UPDATE offices SET name = :name, address = :address, city = :city, country = :country WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':city', $data['city']);
    $stmt->bindParam(':country', $data['country']);
    $stmt->execute();
}

function deleteOffice($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM offices WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($userRole === 'admin') {
        $offices = getOffices();
        header('Content-Type: application/json');
        echo json_encode($offices);
        exit;
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($userRole === 'admin') {
        if (isset($inputData['id'])) {
            updateOffice($inputData['id']);
            http_response_code(200);
            echo json_encode(array('message' => 'Office updated successfully'));
            exit;
        } else {
            $officeID = createOffice();
            http_response_code(201);
            echo json_encode(array('message' => 'Office created successfully', 'id' => $officeID));
            exit;
        }
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if ($userRole === 'admin') {
        $id = $_POST['id'];
        updateOffice($id);
        http_response_code(200);
        echo json_encode(array('message' => 'Office updated successfully'));
        exit;
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if ($userRole === 'admin') {
        $id = $_POST['id'];
        deleteOffice($id);
        http_response_code(200);
        echo json_encode(array('message' => 'Office deleted successfully'));
        exit;
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}