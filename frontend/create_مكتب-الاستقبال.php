**create_مكتب-الاستقبال.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Check for empty fields
    if (empty($name) || empty($address) || empty($phone) || empty($email)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO مكتب_الاستقبال (name, address, phone, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $address, $phone, $email);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        header('Location: list_مكتب-الاستقبال.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New مكتب الاستقبال</h2>
    <form action="" method="post" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 transition duration-500 ease-in-out transform border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Name">
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
            <input type="text" id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 transition duration-500 ease-in-out transform border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Address">
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
            <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 transition duration-500 ease-in-out transform border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Phone">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
            <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 transition duration-500 ease-in-out transform border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Email">
        </div>
        <button type="submit" name="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-lg hover:bg-indigo-600 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">Create</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مكتب-الاستقبال.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_مكتب-الاستقبال.php';
                    } else {
                        alert('Error creating record');
                    }
                }
            });
        });
    });
</script>

**Note:** Make sure to replace `../backend/مكتب-الاستقبال.php` with the actual PHP file that handles the form submission. Also, update the database connection and table name as needed.