<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="h-screen bg-blue-500 flex justify-center items-center">
    <div class="glassmorphic-card bg-white bg-opacity-20 backdrop-filter backdrop-blur-md p-10 rounded-2xl w-80">
        <h1 class="text-3xl text-orange-300 font-bold mb-4">Login</h1>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-orange-300 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg shadow-sm border-orange-300 focus:ring-orange-300 focus:border-orange-300">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-orange-300 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg shadow-sm border-orange-300 focus:ring-orange-300 focus:border-orange-300">
            </div>
            <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded-lg">Login</button>
        </form>
        <p class="text-orange-300 text-sm mt-4">Don't have an account? <a href="register.php" class="text-blue-500 hover:text-blue-600">Register here</a></p>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();
                if (data.success) {
                    alert('Login successful!');
                    // Redirect to dashboard or home page
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>