**edit_لغات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch the existing record details
$data = json_decode(file_get_contents('../backend/لغات.php?id=' . $id), true);

// Set the title
$title = 'تعديل لغة | ' . $_SESSION['site_name'];

// Include the header
include 'header.php';

?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-emerald-600 mb-4"><?= $title ?></h1>

    <form id="edit-language-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="code" class="block text-gray-700 text-sm font-bold mb-2">الرمز</label>
            <input type="text" id="code" name="code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['code'] ?>">
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">تعديل</button>
    </form>
</div>

<script>
    // Fetch the existing record details via GET
    fetch('../backend/لغات.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate the form fields
            document.getElementById('name').value = data.name;
            document.getElementById('code').value = data.code;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-language-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Send a PUT request to update the record
        fetch('../backend/لغات.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                code: document.getElementById('code').value
            })
        })
        .then(response => response.json())
        .then(data => {
            // Redirect to list page on success
            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
        })
        .catch(error => console.error(error));
    });
</script>

<?php
// Include the footer
include 'footer.php';
?>


**backend/لغات.php**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get the ID
$id = $_GET['id'];

// Connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the existing record details
$sql = "SELECT * FROM لغات WHERE id = '$id'";
$result = $conn->query($sql);

// Fetch the data
$data = $result->fetch_assoc();

// Close the connection
$conn->close();

// Output the data as JSON
echo json_encode($data);
?>


**backend/لغات.php (update record)**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get the ID
$id = $_GET['id'];

// Connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the updated record details
$name = $_POST['name'];
$code = $_POST['code'];

// Update the record
$sql = "UPDATE لغات SET name = '$name', code = '$code' WHERE id = '$id'";
$conn->query($sql);

// Close the connection
$conn->close();

// Output a success message
echo json_encode(array('success' => true));
?>