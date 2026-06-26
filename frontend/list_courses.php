<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Get current user info
$current_user = $_SESSION['user'];

// Include database connection
include '../backend/db.php';

// Get courses list from database
$courses = array();
if ($result = $mysqli->query("SELECT * FROM courses")) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Courses List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_courses.php'">Add New Item</button>
            <input type="text" id="search" class="py-2 pl-10 text-sm text-gray-700" placeholder="Search...">
        </div>
        <table class="w-full table-auto" id="courses-table">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course) { ?>
                <tr>
                    <td class="px-4 py-2"><?php echo $course['id']; ?></td>
                    <td class="px-4 py-2"><?php echo $course['name']; ?></td>
                    <td class="px-4 py-2"><?php echo $course['description']; ?></td>
                    <td class="px-4 py-2">
                        <a href="edit_courses.php?id=<?php echo $course['id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteCourse(<?php echo $course['id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get courses list
        async function fetchCourses() {
            const response = await fetch('../backend/courses.php');
            const data = await response.json();
            return data;
        }

        // Delete course using Fetch API
        async function deleteCourse(id) {
            const response = await fetch('../backend/courses.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting course');
            }
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toUpperCase();
            const table = document.getElementById('courses-table');
            const rows = table.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toUpperCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>