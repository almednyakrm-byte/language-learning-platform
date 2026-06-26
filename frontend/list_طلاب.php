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
            font-family: 'Noto Kufi Arabic', sans-serif;
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
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: none;
            border-radius: 0.5rem;
            background-color: #fff;
        }
        .search-bar:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .btn {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span>مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">طلاب</h1>
        <button class="btn" onclick="location.href='create_طلاب.php'">إضافة جديد</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="btn" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الطالب</th>
                    <th>تاريخ الميلاد</th>
                    <th>العنوان</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/طلاب.php'), true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo $record['birthdate']; ?></td>
                        <td><?php echo $record['address']; ?></td>
                        <td>
                            <a href="edit_طلاب.php?id=<?php echo $record['id']; ?>" class="btn">تعديل</a>
                            <button class="btn" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
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
            fetch('../backend/طلاب.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.birthdate}</td>
                            <td>${record.address}</td>
                            <td>
                                <a href="edit_طلاب.php?id=${record.id}" class="btn">تعديل</a>
                                <button class="btn" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/طلاب.php', {
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
                        location.reload();
                    } else {
                        alert('حدث خطأ');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/طلاب.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'name' => 'محمد',
    'birthdate' => '1990-01-01',
    'address' => 'القاهرة'
);
$records[] = array(
    'id' => 2,
    'name' => 'عبد الله',
    'birthdate' => '1995-02-02',
    'address' => 'الإسكندرية'
);
$records[] = array(
    'id' => 3,
    'name' => 'علي',
    'birthdate' => '2000-03-03',
    'address' => 'الجيزة'
);

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['name'], $search) !== false || strpos($record['birthdate'], $search) !== false || strpos($record['address'], $search) !== false;
    });
}

// Output records as JSON
header('Content-Type: application/json');
echo json_encode($records);