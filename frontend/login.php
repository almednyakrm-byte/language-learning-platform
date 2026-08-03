<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #2f4f7f, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        .glassmorphic {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
        .glassmorphic::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(20px);
            border-radius: 10px;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="flex justify-center items-center h-screen">
        <div class="glassmorphic bg-slate-900 text-white p-10 rounded-lg shadow-2xl">
            <h2 class="text-3xl font-bold mb-5">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                    <input type="text" id="username" name="username" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                    <input type="password" id="password" name="password" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500" placeholder="Password">
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
                <p class="text-sm text-gray-400 mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
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


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the Fetch API to submit the credentials to the backend PHP script and handle the response or error alerts dynamically. The code also includes a direct link to the register.php page.