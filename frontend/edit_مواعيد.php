**edit_مواعيد.php**

<?php
// Session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مواعيد.php?id=' . $id;
$record = json_decode(file_get_contents($url), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found!';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit مواعيد';
$mod_slug = 'مواعيد';

// Include header and footer
include 'header.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
            <input id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="<?= $record['name'] ?>">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
            <textarea id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4"><?= $record['description'] ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="date">Date</label>
            <input id="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="date" value="<?= $record['date'] ?>">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/مواعيد.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
            document.getElementById('date').value = data.date;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send AJAX PUT request
        fetch('../backend/مواعيد.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>',
            },
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_<?= $mod_slug ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/مواعيد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(404);
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if ID is valid
if (!ctype_digit($id)) {
    http_response_code(404);
    exit;
}

// Fetch record from database
// Replace with your database query
$record = [
    'id' => $id,
    'name' => 'Record Name',
    'description' => 'Record Description',
    'date' => '2022-01-01',
];

// Return record as JSON
header('Content-Type: application/json');
echo json_encode($record);