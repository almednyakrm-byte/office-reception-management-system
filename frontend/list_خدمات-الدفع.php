**list_خدمات-الدفع.php**

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
    <title>خدمات الدفع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 18, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">خدمات الدفع</span>
        <a href="profile.php">حسابي</a>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-bold">خدمات الدفع</h2>
            <a href="create_خدمات-الدفع.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="ابحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الخدمة</th>
                    <th>وصف الخدمة</th>
                    <th>سعر الخدمة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table records will be populated here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const tableBody = document.getElementById('table-body');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const tableRows = tableBody.children;
            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const cells = row.children;
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchQuery)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        async function fetchRecords() {
            try {
                const response = await fetch('../backend/خدمات-الدفع.php', { method: 'GET' });
                const data = await response.json();
                const tableRows = tableBody.children;
                tableBody.innerHTML = '';
                data.forEach((record) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.name}</td>
                        <td>${record.description}</td>
                        <td>${record.price}</td>
                        <td>
                            <a href="edit_خدمات-الدفع.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/خدمات-الدفع.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    fetchRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }

        fetchRecords();
    </script>
</body>
</html>

**backend/خدمات-الدفع.php**

<?php
// Assuming you have a database connection established
$db = new PDO('dsn', 'username', 'password');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare('DELETE FROM services WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
} elseif (isset($_GET['GET'])) {
    $stmt = $db->prepare('SELECT * FROM services');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
}
?>