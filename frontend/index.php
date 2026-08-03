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
    <title>منصة تعليم لغة مع محادثات وحساب النقاط</title>
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
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-slate-900">منصة تعليم لغة مع محادثات وحساب النقاط</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-lg font-bold text-slate-900 mb-2">معلومات عامة</h2>
            <div class="flex flex-wrap justify-between items-center mb-4">
                <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">طلاب</h3>
                    <p id="students-count" class="text-lg text-slate-900"></p>
                </div>
                <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">معلمين</h3>
                    <p id="teachers-count" class="text-lg text-slate-900"></p>
                </div>
                <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">مدرسين</h3>
                    <p id="lecturers-count" class="text-lg text-slate-900"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-lg font-bold text-slate-900 mb-2">روابط سريعة</h2>
            <ul class="flex flex-wrap justify-between items-center mb-4">
                <li class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
                    <a href="#" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">طلاب</a>
                </li>
                <li class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
                    <a href="#" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">معلمين</a>
                </li>
                <li class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
                    <a href="#" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">مدرسين</a>
                </li>
                <li class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
                    <a href="#" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">مناهج</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('students-count').textContent = data.students;
                document.getElementById('teachers-count').textContent = data.teachers;
                document.getElementById('lecturers-count').textContent = data.lecturers;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a glassmorphism card layout with a premium design. It also includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a Javascript API call from the backend files.

Note: You need to replace `/api/stats` with the actual API endpoint that returns the stats data. Also, you need to create the `logout.php` file to handle the logout functionality.