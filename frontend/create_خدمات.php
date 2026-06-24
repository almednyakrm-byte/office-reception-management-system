**create_خدمات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 text-lg font-bold mb-4">إضافة خدمة جديدة</h2>
        <form id="create-service-form">
            <div class="mb-4">
                <label for="name" class="text-slate-900 text-sm font-bold">اسم الخدمة</label>
                <input type="text" id="name" name="name" class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-full p-2" required>
            </div>
            <div class="mb-4">
                <label for="description" class="text-slate-900 text-sm font-bold">وصف الخدمة</label>
                <textarea id="description" name="description" class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-full p-2" required></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="text-slate-900 text-sm font-bold">سعر الخدمة</label>
                <input type="number" id="price" name="price" class="bg-gray-100 border border-gray-300 text-sm rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-full p-2" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-service-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/خدمات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_خدمات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**خدمات.php (backend)**

<?php
// Include database connection
require_once 'db.php';

// Check if form data is sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price'])) {
    // Prepare SQL query
    $sql = "INSERT INTO خدمات (name, description, price) VALUES (:name, :description, :price)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':price', $_POST['price']);
    $stmt->execute();

    // Check if query is successful
    if ($stmt->rowCount() > 0) {
        echo 'success';
    } else {
        echo 'Error: ' . $pdo->errorInfo()[2];
    }
} else {
    echo 'Error: No data sent';
}
?>