<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة تعليمية لتعليم اللغات مع امتحانات تفاعلية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-500 h-screen">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-end">
            <button class="bg-orange-300 hover:bg-orange-400 text-blue-500 font-bold py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
        </div>
        <div class="text-3xl text-white font-bold mt-4">مرحباً!</div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold mb-2">إجمالي الدورات</div>
                <div id="total-courses" class="text-3xl font-bold"></div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold mb-2">إجمالي الطلاب</div>
                <div id="total-students" class="text-3xl font-bold"></div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold mb-2">إجمالي المدرسين</div>
                <div id="total-instructors" class="text-3xl font-bold"></div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold mb-2">إجمالي الامتحانات</div>
                <div id="total-exams" class="text-3xl font-bold"></div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold mb-2">إدارة الدورات</div>
                <button class="bg-orange-300 hover:bg-orange-400 text-blue-500 font-bold py-2 px-4 rounded" onclick="window.location.href='courses.php'">إدارة الدورات</button>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold mb-2">إدارة الطلاب</div>
                <button class="bg-orange-300 hover:bg-orange-400 text-blue-500 font-bold py-2 px-4 rounded" onclick="window.location.href='students.php'">إدارة الطلاب</button>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <div class="text-lg font-bold mb-2">إدارة المدرسين</div>
                <button class="bg-orange-300 hover:bg-orange-400 text-blue-500 font-bold py-2 px-4 rounded" onclick="window.location.href='instructors.php'">إدارة المدرسين</button>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-courses').innerHTML = data.totalCourses;
                document.getElementById('total-students').innerHTML = data.totalStudents;
                document.getElementById('total-instructors').innerHTML = data.totalInstructors;
                document.getElementById('total-exams').innerHTML = data.totalExams;
            });

        function logout() {
            fetch('api/logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
        }
    </script>

    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</body>
</html>