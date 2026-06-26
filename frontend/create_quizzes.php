<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Module slug
$mod_slug = 'quizzes';

// Page title
$page_title = 'Create Quiz';

// Include header
require_once 'header.php';
?>

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Create Quiz</h3>
                <p class="mt-1 text-sm text-gray-600">Create a new quiz record.</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form id="create-quiz-form">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="title" id="title" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" placeholder="Quiz title">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <div class="mt-1">
                                    <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Quiz description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <select id="category" name="category" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                        <option value="">Select category</option>
                                        <option value="history">History</option>
                                        <option value="science">Science</option>
                                        <option value="sports">Sports</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <select id="difficulty" name="difficulty" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                        <option value="">Select difficulty</option>
                                        <option value="easy">Easy</option>
                                        <option value="medium">Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="questions" class="block text-sm font-medium text-gray-700">Questions</label>
                                <div class="mt-1">
                                    <input type="number" id="questions" name="questions" class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" placeholder="Number of questions">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-300 hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-300">Create Quiz</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-quiz-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/quizzes.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>