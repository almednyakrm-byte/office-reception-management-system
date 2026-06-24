**list_حجز-مواعيد.php**

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
    <title>حجز مواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
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
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500">مرحباً <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">حجز مواعيد</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_حجز-مواعيد.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الحجز</th>
                    <th>تاريخ الحجز</th>
                    <th>وقت الحجز</th>
                    <th>حالة الحجز</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['time']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <a href="edit_حجز-مواعيد.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/حجز-مواعيد.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.date}</td>
                            <td>${record.time}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_حجز-مواعيد.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/حجز-مواعيد.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        function fetchRecords() {
            return fetch('../backend/حجز-مواعيد.php')
                .then(response => response.json())
                .then(data => data.records)
                .catch(error => console.error(error));
        }
    </script>
</body>
</html>


**backend/حجز-مواعيد.php**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM حجز_مواعيد WHERE CONCAT(id, date, time, status) LIKE '%$search%'";
} else {
    $query = "SELECT * FROM حجز_مواعيد";
}

// Fetch records
$result = mysqli_query($conn, $query);
$records = array();
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}

// Output records
header('Content-Type: application/json');
echo json_encode(array('records' => $records));

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    $query = "DELETE FROM حجز_مواعيد WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo json_encode(array('success' => true));
}

// Close connection
mysqli_close($conn);
?>

Note: This is a basic implementation and you should adjust it according to your specific requirements and database schema. Additionally, you should ensure that your backend is secure and follows best practices for handling user input and database queries.