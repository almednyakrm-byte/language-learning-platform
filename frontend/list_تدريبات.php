**list_تدريبات.php**

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
    <title>تدريبات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .bg-teal-500 {
            background-color: #0097a7;
        }
        .text-gray-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="bg-teal-500 p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-gray-500 hover:text-gray-700">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-gray-500 mr-2">مرحباً <?= $_SESSION['username'] ?></span>
                    <a href="logout.php" class="text-gray-500 hover:text-gray-700">تسجيل خروج</a>
                </div>
            </nav>
        </header>
        <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-bold mb-2">قائمة التدريبات</h2>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_تدريبات.php'">اضافة جديد</button>
            <div class="flex justify-between mb-4">
                <input type="search" id="search" class="p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-l" placeholder="بحث...">
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-r" onclick="search()"></button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">اسم التدريب</th>
                        <th class="px-4 py-2">تاريخ التدريب</th>
                        <th class="px-4 py-2">حالة التدريب</th>
                        <th class="px-4 py-2">إجراءات</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        function search() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/تدريبات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${record.name}</td>
                                <td class="px-4 py-2">${record.date}</td>
                                <td class="px-4 py-2">${record.status}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_تدريبات.php?id=${record.id}" class="text-gray-500 hover:text-gray-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/تدريبات.php')
                    .then(response => response.json())
                    .then(data => {
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${record.name}</td>
                                <td class="px-4 py-2">${record.date}</td>
                                <td class="px-4 py-2">${record.status}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_تدريبات.php?id=${record.id}" class="text-gray-500 hover:text-gray-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/تدريبات.php', {
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
                        search();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }

        search();
    </script>
</body>
</html>

This code includes the following features:

* Session validation to ensure the user is logged in before accessing the page.
* A premium Tailwind UI design with a teal and gray color scheme.
* A header navigation bar with links to the main page and logout.
* A table displaying a list of records with actions to edit and delete each record.
* A search bar that filters the records in real-time.
* AJAX JavaScript code that fetches the list of records from the backend and handles delete requests.

Note that this code assumes that the backend API is implemented to handle GET and DELETE requests for the `تدريبات` module.