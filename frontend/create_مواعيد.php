**create_مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    if (!empty($name) && !empty($date) && !empty($time)) {
        // Insert data into database
        $query = "INSERT INTO مواعيد (name, date, time) VALUES ('$name', '$date', '$time')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_مواعيد.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill in all fields';
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create new مواعيد form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New مواعيد</h2>
    <form id="create-muaaid-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter name">
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-slate-900">Date:</label>
            <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="mb-4">
            <label for="time" class="block text-sm font-medium text-slate-900">Time:</label>
            <input type="time" id="time" name="time" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <button type="submit" name="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-lg hover:bg-indigo-600 focus:ring-indigo-500 focus:border-indigo-500">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    // Send form data via AJAX
    document.getElementById('create-muaaid-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/مواعيد.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_مواعيد.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>


**مواعيد.php (backend)**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['date']) && isset($_POST['time'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    $query = "INSERT INTO مواعيد (name, date, time) VALUES ('$name', '$date', '$time')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error inserting data']);
    }
}