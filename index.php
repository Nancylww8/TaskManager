<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Database connection parameters
    $hostname = 'localhost';    
	$username = 'root'; 
    $password = '';
    $database = 'task_management_db';

    // Create a new MySQLi connection
    $conn = new mysqli($hostname, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        // If connection fails, terminate and display error message
        die("Connection failed: " . $conn->connect_error);
    }

    // Get email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the user exists
    $sql = "SELECT * FROM users WHERE email='$email' AND BINARY password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, set session variables and redirect to the task page
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header("Location: server/task.php");
        exit();
    } else {
        // User does not exist, store error message in session
        $_SESSION['error'] = "Invalid email or password.";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
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
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Logo for Task Manager -->
    <div class="logo">Task Manager</div>
    <?php 
    // Check if there's any error message stored in session
    if (isset($_SESSION['error'])) { 
    ?>
        <!-- Display error message if any -->
        <div class="error-message"><?php echo $_SESSION['error']; ?></div>
    <?php 
        // Unset the error session after displaying it
        unset($_SESSION['error']);
    }
    ?>
    <!-- Login form -->
    <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Input field for email -->
        <div class="form-group">
            <input type="email" name="email" id="email" placeholder="Email" required>
        </div>
        <!-- Input field for password -->
        <div class="form-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
        </div>
        <!-- Button to submit the form -->
        <div class="button-container">
            <button type="submit" class="login-btn" name="login">Login</button>
        </div>
    </form>
    <!-- Link to registration page -->
    <div class="register">
        Don't have an account? <a href="pages/registration.html">register</a>
    </div>
</div>

<script>
    // JavaScript will be added here
    document.getElementById('loginForm').addEventListener('submit', function(event){        
        // Retrieve email and password from the form
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
        // Log the login attempt with email and password
        console.log('Login attempt with email: ' + email + ' and password: ' + password);        
    });
</script>

</body>
</html>
