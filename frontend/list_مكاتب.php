**list_مكاتب.php**

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
    <title>مكاتب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
        .text-slate-900 {
            color: #1a1d23;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <header class="bg-slate-900 p-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-slate-900 hover:text-indigo-500">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-slate-900">مرحباً <?= $_SESSION['username'] ?></span>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
                </div>
            </nav>
        </header>
        <main class="bg-slate-900 p-4">
            <h1 class="text-slate-900 text-3xl mb-4">مكاتب</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_مكاتب.php'">إضافة جديد</button>
            <div class="flex justify-between mb-4">
                <input type="search" class="bg-slate-900 text-indigo-500 p-2 rounded w-full" id="search" placeholder="بحث...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
            <table class="border-collapse border border-slate-900">
                <thead>
                    <tr>
                        <th class="bg-slate-900 text-indigo-500 p-2">الاسم</th>
                        <th class="bg-slate-900 text-indigo-500 p-2">العنوان</th>
                        <th class="bg-slate-900 text-indigo-500 p-2">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </main>
    </div>

    <script>
        // Search function
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/مكاتب.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="bg-slate-900 text-indigo-500 p-2">${record.الاسم}</td>
                            <td class="bg-slate-900 text-indigo-500 p-2">${record.العنوان}</td>
                            <td class="bg-slate-900 text-indigo-500 p-2">
                                <a href="edit_مكاتب.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        // Delete record function
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/مكاتب.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                });
            }
        }

        // Load records on page load
        fetch('../backend/مكاتب.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="bg-slate-900 text-indigo-500 p-2">${record.الاسم}</td>
                        <td class="bg-slate-900 text-indigo-500 p-2">${record.العنوان}</td>
                        <td class="bg-slate-900 text-indigo-500 p-2">
                            <a href="edit_مكاتب.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            });
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a search bar, and AJAX calls to fetch and delete records from the backend.