**create_خدمات-الدفع.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);

    // Insert data into database
    $query = "INSERT INTO services_payments (name, description, price) VALUES ('$name', '$description', '$price')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect back to list page
        header('Location: list_خدمات-الدفع.php');
        exit;
    } else {
        echo 'Error inserting data';
    }
}

// Include header
require_once '../includes/header.php';
?>

<!-- Create services payment form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New Service Payment</h2>
    <form id="create-service-payment-form" method="POST">
        <div class="mb-4">
            <label for="name" class="text-slate-900 block mb-2">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="text-slate-900 block mb-2">Description:</label>
            <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="text-slate-900 block mb-2">Price:</label>
            <input type="number" id="price" name="price" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create Service Payment</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_خدمات-الدفع.js**
javascript
// Get form element
const form = document.getElementById('create-service-payment-form');

// Add event listener to form submit
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/خدمات-الدفع.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list page
            window.location.href = 'list_خدمات-الدفع.php';
        } else {
            console.error(data.error);
        }
    })
    .catch((error) => console.error(error));
});


**Note:** Make sure to replace `../backend/خدمات-الدفع.php` with the actual backend script that handles the form data. Also, ensure that the database connection and query are correct.