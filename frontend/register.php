<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-blue-500 flex justify-center items-center">
    <div class="bg-white p-10 rounded shadow-md w-1/2">
        <h2 class="text-orange-300 text-3xl font-bold mb-4">Register</h2>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <span class="text-red-500 text-xs" id="username-error"></span>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <span class="text-red-500 text-xs" id="email-error"></span>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <span class="text-red-500 text-xs" id="password-error"></span>
            </div>
            <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Register</button>
        </form>
        <div class="mt-4" id="register-response"></div>
    </div>

    <script>
        const registerForm = document.getElementById('register-form');
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Validation
            let valid = true;
            if (!username.match(/[A-Za-z\u0600-\u06FF0-9\s]+/)) {
                document.getElementById('username-error').innerText = 'Invalid username';
                valid = false;
            } else {
                document.getElementById('username-error').innerText = '';
            }

            if (!email.match(/[^@]+@[^@]+\.[^@]+/)) {
                document.getElementById('email-error').innerText = 'Invalid email';
                valid = false;
            } else {
                document.getElementById('email-error').innerText = '';
            }

            if (password.length < 8) {
                document.getElementById('password-error').innerText = 'Password must be at least 8 characters';
                valid = false;
            } else {
                document.getElementById('password-error').innerText = '';
            }

            if (valid) {
                const formData = new FormData();
                formData.append('username', username);
                formData.append('email', email);
                formData.append('password', password);

                fetch('../backend/auth.php?action=register', {
                    method: 'POST',
                    body: formData
                })
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById('register-response').innerText = data.message;
                    if (data.success) {
                        document.getElementById('register-response').classList.add('text-green-500');
                    } else {
                        document.getElementById('register-response').classList.add('text-red-500');
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
            }
        });
    </script>
</body>
</html>