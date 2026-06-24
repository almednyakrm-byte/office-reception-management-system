<?php
session_start();

// Check if user is authenticated
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
    <title>نظام إدارة مكاتب الاستقبال مع حجز مواعيد وخدمات دفع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-slate-900 text-white">
        <h1 class="text-3xl font-bold">نظام إدارة مكاتب الاستقبال مع حجز مواعيد وخدمات دفع</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900 text-white">
        <h1 class="text-2xl font-bold">مرحباً <?php echo $_SESSION['username']; ?></h1>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
        <?php
        // Fetch stats dynamically via Javascript API calls from the backend files
        $stats = json_decode(file_get_contents('stats.json'), true);
        ?>
        <div class="glassmorphism-card bg-white text-slate-900 p-4">
            <h2 class="text-lg font-bold">إجمالي الحجوزات</h2>
            <p class="text-3xl font-bold"><?php echo $stats['appointments_count']; ?></p>
        </div>
        <div class="glassmorphism-card bg-white text-slate-900 p-4">
            <h2 class="text-lg font-bold">إجمالي الدفع</h2>
            <p class="text-3xl font-bold"><?php echo $stats['payments_count']; ?></p>
        </div>
        <div class="glassmorphism-card bg-white text-slate-900 p-4">
            <h2 class="text-lg font-bold">إجمالي المكاتب</h2>
            <p class="text-3xl font-bold"><?php echo $stats['offices_count']; ?></p>
        </div>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900 text-white">
        <h1 class="text-2xl font-bold">الرابط السريع</h1>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
        <a href="offices.php" class="glassmorphism-card bg-white text-slate-900 p-4 hover:bg-slate-100">
            <h2 class="text-lg font-bold">مكتب الاستقبال</h2>
        </a>
        <a href="payments.php" class="glassmorphism-card bg-white text-slate-900 p-4 hover:bg-slate-100">
            <h2 class="text-lg font-bold">خدمات الدفع</h2>
        </a>
        <a href="appointments.php" class="glassmorphism-card bg-white text-slate-900 p-4 hover:bg-slate-100">
            <h2 class="text-lg font-bold">حجز مواعيد</h2>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('stats.json')
            .then(response => response.json())
            .then(data => {
                const appointmentsCount = document.querySelector('.appointments-count');
                appointmentsCount.textContent = data.appointments_count;

                const paymentsCount = document.querySelector('.payments-count');
                paymentsCount.textContent = data.payments_count;

                const officesCount = document.querySelector('.offices-count');
                officesCount.textContent = data.offices_count;
            });
    </script>
</body>
</html>


And here is the `stats.json` file:

json
{
    "appointments_count": 10,
    "payments_count": 20,
    "offices_count": 5
}


Note: You need to replace the `stats.json` file with your actual API endpoint that returns the stats data.