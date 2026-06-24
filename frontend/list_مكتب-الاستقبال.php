**list_مكتب-الاستقبال.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مكتب الاستقبال</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            color: #f7f7f7;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #f7f7f7;
            text-decoration: none;
        }
        .header a:hover {
            color: #c5cae9;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1f2937;
            color: #f7f7f7;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem #1f2937;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مكتب الاستقبال</span>
        <span class="float-right">
            <a href="profile.php"><?= $_SESSION['username'] ?></a>
            <a href="logout.php">تسجيل الخروج</a>
        </span>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-3xl font-bold">قائمة مكتب الاستقبال</h1>
            <a href="create_مكتب-الاستقبال.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" placeholder="بحث" id="search-input">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" id="search-button">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>عنوان</th>
                    <th>تليفون</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const tableBody = document.getElementById('table-body');

        searchButton.addEventListener('click', async () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                const response = await fetch(`../backend/مكتب-الاستقبال.php?search=${searchQuery}`);
                const data = await response.json();
                renderTable(data);
            } else {
                renderTable(await fetch('../backend/مكتب-الاستقبال.php').then(response => response.json()));
            }
        });

        async function renderTable(data) {
            tableBody.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.اسم}</td>
                    <td>${record.عنوان}</td>
                    <td>${record.تليفون}</td>
                    <td>
                        <a href="edit_مكتب-الاستقبال.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                const response = await fetch(`../backend/مكتب-الاستقبال.php?id=${id}`, { method: 'DELETE' });
                if (response.ok) {
                    renderTable(await fetch('../backend/مكتب-الاستقبال.php').then(response => response.json()));
                } else {
                    alert('حذف السجل غير موفق');
                }
            }
        }

        renderTable(await fetch('../backend/مكتب-الاستقبال.php').then(response => response.json()));
    </script>
</body>
</html>


**backend/مكتب-الاستقبال.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM مكتب_الاستقبال WHERE اسم LIKE '%$searchQuery%' OR عنوان LIKE '%$searchQuery%' OR تليفون LIKE '%$searchQuery%'";
} else {
    $query = "SELECT * FROM مكتب_الاستقبال";
}

// Fetch records
$result = $conn->query($query);
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// JSON output
header('Content-Type: application/json');
echo json_encode($data);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM مكتب_الاستقبال WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('success' => true));
}
?>

Note: This is a basic example and you should adjust the database connection and query according to your actual database schema. Also, this example uses a simple search query, you may want to use a more advanced search query depending on your requirements.