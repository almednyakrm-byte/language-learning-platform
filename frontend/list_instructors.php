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
    <title>Instructors List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-blue-500 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="mr-4">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">Logout</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">Instructors List</h1>
        <div class="flex justify-between mb-4">
            <a href="create_instructors.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Add New Item</a>
            <input type="search" id="search" placeholder="Search..." class="px-4 py-2 border border-gray-300 rounded">
        </div>
        <table id="instructors-table" class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="instructors-tbody">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get instructors list
        fetch('../backend/instructors.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('instructors-tbody');
                data.forEach(instructor => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${instructor.name}</td>
                        <td class="px-4 py-2">${instructor.email}</td>
                        <td class="px-4 py-2">
                            <a href="edit_instructors.php?id=${instructor.id}" class="text-blue-500 hover:text-blue-600">Edit</a>
                            <button class="text-orange-300 hover:text-orange-400" onclick="deleteInstructor(${instructor.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete instructor using AJAX
        function deleteInstructor(id) {
            fetch('../backend/instructors.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted row from the table
                    const rows = document.getElementById('instructors-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[2].children[1].onclick.toString().includes(`deleteInstructor(${id})`)) {
                            rows[i].remove();
                            break;
                        }
                    }
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toUpperCase();
            const rows = document.getElementById('instructors-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const name = rows[i].children[0].textContent.toUpperCase();
                const email = rows[i].children[1].textContent.toUpperCase();
                if (name.includes(filter) || email.includes(filter)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>