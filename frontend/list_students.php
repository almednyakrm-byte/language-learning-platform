<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Students List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">
                <a href="create_students.php">Add New Item</a>
            </button>
            <input type="search" id="search" class="py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="students-table" class="w-full table-auto border border-collapse">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Name</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody id="students-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch students data from backend
        fetch('../backend/students.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('students-tbody');
                data.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4">${student.id}</td>
                        <td class="py-2 px-4">${student.name}</td>
                        <td class="py-2 px-4">
                            <a href="edit_students.php?id=${student.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteStudent(${student.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete student via AJAX
        function deleteStudent(id) {
            fetch('../backend/students.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove deleted row from table
                    const rows = document.getElementById('students-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting student:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.getElementById('students-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>