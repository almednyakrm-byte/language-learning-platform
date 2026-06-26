**list_معلمون.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معلمون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="bg-white shadow-md p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-lg font-bold">Home</a>
                <div class="flex items-center">
                    <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
                </div>
            </nav>
        </header>
        <div class="bg-white shadow-md p-4 mb-4">
            <h2 class="text-lg font-bold">معلمون</h2>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_معلمون.php'">Add New Item</button>
            <form class="flex items-center mb-4">
                <input type="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Search...">
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded ml-2" type="submit">Search</button>
            </form>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const url = '../backend/معلمون.php';
        const recordsContainer = document.getElementById('records');

        // Fetch records
        fetch(url, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${record.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_معلمون.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">Edit</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    recordsContainer.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Delete record
        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting record');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code uses the Fetch API to fetch records from the backend and display them in a table. It also includes a delete button for each record, which sends a DELETE request to the backend to delete the record. The search bar is not implemented yet, but you can add it by using the `filter()` method on the records array and updating the table accordingly.