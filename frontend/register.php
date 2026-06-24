<!-- register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="flex justify-center items-center h-full">
        <div class="bg-white rounded-lg shadow-md w-1/2 p-8">
            <h2 class="text-3xl text-center text-slate-900 mb-4">Register</h2>
            <form id="register-form">
                <div class="mb-4">
                    <label for="username" class="block text-slate-900 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <small id="username-error" class="text-red-500"></small>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-slate-900 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                    <small id="email-error" class="text-red-500"></small>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <small id="password-error" class="text-red-500"></small>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Register</button>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('register-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const usernameError = document.getElementById('username-error');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');

            if (username === '') {
                usernameError.textContent = 'Username is required';
                usernameError.style.display = 'block';
                return;
            } else {
                usernameError.style.display = 'none';
            }

            if (email === '') {
                emailError.textContent = 'Email is required';
                emailError.style.display = 'block';
                return;
            } else {
                emailError.style.display = 'none';
            }

            if (password === '') {
                passwordError.textContent = 'Password is required';
                passwordError.style.display = 'block';
                return;
            } else {
                passwordError.style.display = 'none';
            }

            const data = new FormData();
            data.append('username', username);
            data.append('email', email);
            data.append('password', password);

            fetch('../backend/auth.php?action=register', {
                method: 'POST',
                body: data,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert('Registration successful');
                    window.location.href = 'login.php';
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => console.error(error));
        });
    </script>
</body>
</html>

This code uses Tailwind CSS to create a premium-looking registration form. It includes validation rules for the username, email, and password fields, and uses AJAX to submit the form to the backend PHP script. The form fields are validated on the client-side using JavaScript, and the form is submitted to the backend script using the Fetch API. The backend script is expected to return a JSON response indicating whether the registration was successful or not.