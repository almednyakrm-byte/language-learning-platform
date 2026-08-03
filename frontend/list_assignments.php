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
    <title>Assignments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="mr-4">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">Logout</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Assignments</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_assignments.php'">Add New Item</button>
            <input type="search" id="search" class="py-2 pl-10 text-sm text-gray-700" placeholder="Search...">
        </div>
        <table id="assignments-table" class="w-full table-auto border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="assignments-tbody">
                <!-- Table content will be generated dynamically -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch assignments data from backend
        fetch('../backend/assignments.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('assignments-tbody');
                data.forEach(assignment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${assignment.id}</td>
                        <td class="px-4 py-2">${assignment.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_assignments.php?id=${assignment.id}" class="text-blue-500 hover:text-blue-600">Edit</a>
                            <button class="text-orange-300 hover:text-orange-400" onclick="deleteAssignment(${assignment.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete assignment
        function deleteAssignment(id) {
            fetch('../backend/assignments.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting assignment');
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('assignments-tbody').children;
            for (const row of rows) {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>