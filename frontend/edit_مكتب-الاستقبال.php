**edit_مكتب-الاستقبال.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مكتب-الاستقبال.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title
$pageTitle = 'تعديل مكتب الاستقبال';

// Include header
include 'header.php';

?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">اسم المكتب</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-slate-900">عنوان المكتب</label>
            <input type="text" id="address" name="address" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['address'] ?>">
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
            <input type="text" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['phone'] ?>">
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
    </form>
</div>

<!-- Scripts -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/مكتب-الاستقبال.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('address').value = data.address;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('../backend/مكتب-الاستقبال.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/مكتب-الاستقبال.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$record = get_record($id);

// Output record details
echo json_encode($record);

// Function to get record details
function get_record($id) {
    // Database connection
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');

    // Query
    $query = "SELECT * FROM مكتب_الاستقبال WHERE id = '$id'";

    // Execute query
    $result = mysqli_query($conn, $query);

    // Fetch record details
    $record = mysqli_fetch_assoc($result);

    // Close database connection
    mysqli_close($conn);

    return $record;
}
?>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- Page content -->
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
        <!-- Header -->
        <header class="bg-slate-900 text-white p-4">
            <h1 class="text-3xl font-bold"><?= $pageTitle ?></h1>
        </header>
    </div>
</body>
</html>


**footer.php**

<!-- Footer -->
<footer class="bg-slate-900 text-white p-4">
    <p>&copy; <?= date('Y') ?> <?= $pageTitle ?></p>
</footer>


Note: This code assumes you have a `get_record` function in your backend that fetches the existing record details from the database. You should replace this function with your own implementation. Additionally, this code uses a simple database connection using `mysqli`. You should consider using a more secure and efficient database connection method.