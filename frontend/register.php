<!-- register.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-1/2">
        <h1 class="text-3xl text-indigo-500 font-bold mb-4">Register</h1>
        <form id="register-form" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <p id="username-error" class="text-red-500 hidden"></p>
            </div>
            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                <p id="email-error" class="text-red-500 hidden"></p>
            </div>
            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" pattern="[A-Za-z0-9!@#$%^&*()_+=-{};:'<>,./?]" required>
                <p id="password-error" class="text-red-500 hidden"></p>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (username == '') {
                    $('#username-error').text('Username is required').show();
                    return false;
                } else if (!username.match(pattern)) {
                    $('#username-error').text('Invalid username').show();
                    return false;
                } else {
                    $('#username-error').hide();
                }

                if (email == '') {
                    $('#email-error').text('Email is required').show();
                    return false;
                } else if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    $('#email-error').text('Invalid email').show();
                    return false;
                } else {
                    $('#email-error').hide();
                }

                if (password == '') {
                    $('#password-error').text('Password is required').show();
                    return false;
                } else if (!password.match(pattern)) {
                    $('#password-error').text('Invalid password').show();
                    return false;
                } else {
                    $('#password-error').hide();
                }

                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        if (response == 'success') {
                            alert('Registration successful');
                            window.location.href = 'login.php';
                        } else {
                            alert('Registration failed');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


Note: This code uses the Tailwind CSS CDN for styling and jQuery for AJAX functionality. The `pattern` attribute is used to specify the validation rules for the input fields. The `match()` method is used to check if the input value matches the specified pattern. The `ajax()` method is used to send a POST request to the backend PHP script to register the user.