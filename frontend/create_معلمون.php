**create_معلمون.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include Tailwind CSS
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Create معلمون form -->
<div class="container mx-auto p-4 mt-6">
    <h2 class="text-2xl font-bold text-emerald-600 mb-4">Create New معلمون</h2>
    <form id="create-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Name">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Email">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">Phone</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" type="text" placeholder="Phone">
        </div>
        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create</button>
    </form>
</div>

<!-- Include JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/معلمون.php',
                data: formData,
                success: function(data) {
                    if (data == 'success') {
                        window.location.href = 'list_معلمون.php';
                    } else {
                        alert('Error creating record');
                    }
                }
            });
        });
    });
</script>

<!-- Include footer -->
<?php
include 'footer.php';
?>

**Note:** Make sure to replace `header.php` and `footer.php` with your actual header and footer files. Also, update the `../backend/معلمون.php` URL to match your actual backend file.