**list_مناهج.php**

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
    <title>مناهج</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
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
            background-color: #2d3748;
            color: #fff;
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
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">مناهج</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مناهج.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>وصف</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const recordsTable = document.getElementById('records-table');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مناهج.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    const recordsHtml = data.map(record => `
                        <tr>
                            <td>${record.title}</td>
                            <td>${record.description}</td>
                            <td>${record.added_at}</td>
                            <td>
                                <a href="edit_مناهج.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsTable.innerHTML = recordsHtml;
                });
            } else {
                fetch('../backend/مناهج.php')
                .then(response => response.json())
                .then(data => {
                    const recordsHtml = data.map(record => `
                        <tr>
                            <td>${record.title}</td>
                            <td>${record.description}</td>
                            <td>${record.added_at}</td>
                            <td>
                                <a href="edit_مناهج.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsTable.innerHTML = recordsHtml;
                });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/مناهج.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }

        searchRecords();
    </script>
</body>
</html>


**backend/مناهج.php**

<?php
// Database connection
$conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $conn->prepare('SELECT * FROM مناهج WHERE title LIKE :search OR description LIKE :search');
    $stmt->bindParam(':search', '%' . $searchQuery . '%');
    $stmt->execute();
    $records = $stmt->fetchAll();
} else {
    $stmt = $conn->prepare('SELECT * FROM مناهج');
    $stmt->execute();
    $records = $stmt->fetchAll();
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('DELETE FROM مناهج WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Get records
echo json_encode($records);