**list_طلاب.php**

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
    <title>طلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ffffff;
            text-decoration: underline;
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
            background-color: #1f2937;
            color: #ffffff;
        }
        .search-bar {
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
        .search-bar button {
            background-color: #1f2937;
            color: #ffffff;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #1f2937;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-white"> | </span>
        <span class="text-white"><?= $_SESSION['username'] ?></span>
        <span class="text-white"> | </span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">طلاب</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_طلاب.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الطالب</th>
                    <th>البريد الإلكتروني</th>
                    <th>الجنسية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/طلاب.php';
                $response = fetchRecords($url);
                if ($response) {
                    $records = json_decode($response, true);
                    foreach ($records as $record) {
                        ?>
                        <tr>
                            <td><?= $record['name'] ?></td>
                            <td><?= $record['email'] ?></td>
                            <td><?= $record['nationality'] ?></td>
                            <td>
                                <a href="edit_طلاب.php?id=<?= $record['id'] ?>" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            const url = '../backend/طلاب.php?search=' + search;
            fetchRecords(url);
        }

        function fetchRecords(url) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.email}</td>
                            <td>${record.nationality}</td>
                            <td>
                                <a href="edit_طلاب.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch(`../backend/طلاب.php?id=${id}&action=delete`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        fetchRecords('../backend/طلاب.php');
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>


**backend/طلاب.php**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Search query
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM students WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR nationality LIKE '%$search%'";
} else {
    $query = "SELECT * FROM students";
}

// Execute query
$result = mysqli_query($conn, $query);

// Fetch records
$records = array();
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}

// JSON encode records
echo json_encode($records);

// Close connection
mysqli_close($conn);
?>


Note: Replace `'localhost'`, `'username'`, `'password'`, and `'database'` with your actual database credentials and name. Also, make sure to create a `students` table in your database with the necessary columns (e.g., `id`, `name`, `email`, `nationality`).