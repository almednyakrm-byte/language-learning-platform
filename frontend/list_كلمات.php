**list_كلمات.php**

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
    <title>كلمات</title>
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
    <header class="bg-teal-500 p-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-white text-lg font-bold">الصفحة الرئيسية</a>
            <div class="flex items-center">
                <p class="text-white text-lg font-bold mr-4">مرحباً <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-white text-lg font-bold hover:text-gray-500">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة كلمات</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_كلمات.php'">إضافة كلمة جديدة</button>
        <div class="mt-4">
            <input type="search" id="search" class="w-full p-2 mb-4 border border-gray-400 rounded" placeholder="بحث...">
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">الاسم</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/fetch@2.0.3/dist/fetch.min.js"></script>
    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        // Search functionality
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach(record => {
                const text = record.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        // Load records from backend
        fetch('../backend/كلمات.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم}</td>
                        <td>
                            <a href="edit_كلمات.php?id=${record.id}" class="text-teal-500 hover:text-gray-500">تعديل</a>
                            <button class="text-red-500 hover:text-gray-500" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Delete record
        function deleteRecord(id) {
            fetch('../backend/كلمات.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>

This code creates a premium Tailwind UI with a search bar, a table to display records, and buttons to edit and delete records. The search bar filters the records in real-time, and the delete button sends an AJAX request to the backend to delete the record. The records are loaded from the backend using the Fetch API.