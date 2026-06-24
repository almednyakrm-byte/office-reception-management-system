**edit_حجز-مواعيد.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$data = json_decode(file_get_contents('../backend/حجز-مواعيد.php?id=' . $id), true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit حجز مواعيد';
$mod_slug = 'حجز-مواعيد';

// Include header and navigation
include 'header.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold leading-tight text-slate-900 mb-4"><?= $page_title ?></h1>
    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="title" class="block text-sm font-medium text-slate-700">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg" value="<?= $data['title'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="date" class="block text-sm font-medium text-slate-700">Date</label>
            <input type="date" id="date" name="date" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg" value="<?= $data['date'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="time" class="block text-sm font-medium text-slate-700">Time</label>
            <input type="time" id="time" name="time" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg" value="<?= $data['time'] ?>">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Update</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/حجز-مواعيد.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('date').value = data.date;
            document.getElementById('time').value = data.time;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();
        // Send AJAX PUT request
        fetch('../backend/حجز-مواعيد.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                title: document.getElementById('title').value,
                date: document.getElementById('date').value,
                time: document.getElementById('time').value
            })
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_<?= $mod_slug ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/حجز-مواعيد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set.';
    exit;
}

// Get record details from database
// Replace this with your actual database query
$data = array(
    'id' => $_GET['id'],
    'title' => 'Example Title',
    'date' => '2022-01-01',
    'time' => '12:00:00'
);

// Return data as JSON
echo json_encode($data);
?>


Note: This code assumes that you have a `header.php` and `footer.php` file that includes the HTML header and footer, respectively. You'll need to create these files and modify them to match your specific needs.

Also, this code uses a simple database query to fetch the record details. You'll need to replace this with your actual database query.

Finally, this code uses the `fetch` API to send AJAX requests. If you're using an older browser that doesn't support the `fetch` API, you may need to use a library like Axios or jQuery to send the requests.