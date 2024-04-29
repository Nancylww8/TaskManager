<?php
// Starting the session
session_start();

// Checking if user_id and username are set in the session
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    // Unsetting all session variables
    session_unset();
    // Destroying the session
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="./css/task.css">
    <style>
        /* CSS styles*/
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        .logo {
            background-color: #2b8d8d;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .login-btn {
            width: 100%;
            padding: 10px 20px;
            background-color: #2b8d8d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color: #2b8d8d;
        }
        .register {
            text-align: center;
            margin-top: 20px;
        }
        .register a {
            color: #2b8d8d;
            text-decoration: none;
        }
        .register a:hover {
            text-decoration: underline;
        }
        </style>
</head>
<body>
    <div class="main-content">
        <!-- Content Container -->
        <div class="content-container">
            <!-- Logout Container -->
            <div class="logout-container">
                <h2>You have been successfully logged out.</h2>
                <p>Thank you for using our Task Manager application.</p><br><br>
                <!-- Prompt to login -->
                <div class="login-container">
                    <div class="logo">Task Manager</div>
                    <form id="loginForm" action="index.php" method="post">
                     
                        <div class="button-container">
                            <button type="submit" class="login-btn">Login</button>
                        </div>
                    </form>
                    <!-- Link to registration page -->
                    <div class="register">
                        Don't have an account? <a href="../pages/registration.html">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Task Manager. All rights reserved.</p>
    </footer>

    <script>
        // JavaScript for handling form submission
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            // Retrieving email and password values
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            // Logging the login attempt
            console.log('Login attempt with email: ' + email + ' and password: ' + password);
        });
    </script>
</body>
</html>







