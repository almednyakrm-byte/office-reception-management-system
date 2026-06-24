**create_حجز-مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = [];

    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    $email = trim($_POST['email']);
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is required and must be valid';
    }

    $phone = trim($_POST['phone']);
    if (empty($phone)) {
        $errors[] = 'Phone is required';
    }

    $date = trim($_POST['date']);
    if (empty($date)) {
        $errors[] = 'Date is required';
    }

    $time = trim($_POST['time']);
    if (empty($time)) {
        $errors[] = 'Time is required';
    }

    // Insert data into database
    if (empty($errors)) {
        $sql = "INSERT INTO حجز_مواعيد (name, email, phone, date, time) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $email, $phone, $date, $time]);
        header('Location: list_حجز-مواعيد.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create appointment form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create Appointment</h2>
    <form id="create-appointment-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-slate-900 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-slate-900 text-sm font-bold mb-2">Phone:</label>
            <input type="tel" id="phone" name="phone" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-slate-900 text-sm font-bold mb-2">Date:</label>
            <input type="date" id="date" name="date" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="time" class="block text-slate-900 text-sm font-bold mb-2">Time:</label>
            <input type="time" id="time" name="time" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create Appointment</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_حجز-مواعيد.js**
javascript
// Get form element
const form = document.getElementById('create-appointment-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to server
    fetch('../backend/حجز-مواعيد.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect to list page
            window.location.href = 'list_حجز-مواعيد.php';
        } else {
            // Display error message
            alert(data.error);
        }
    })
    .catch((error) => {
        console.error(error);
    });
});


**Note:** Make sure to update the `../backend/حجز-مواعيد.php` file to handle the form data and insert it into the database. Also, update the `../includes/header.php` and `../includes/footer.php` files to include the necessary HTML structure.