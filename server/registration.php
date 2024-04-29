<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $username = $_POST['login'];
    $password = $_POST['password'];
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;
    $terms = isset($_POST['terms']) ? 1 : 0;

    // Perform form validation (you can add more validation as needed)
    $errors = [];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate username (example: minimum length)
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long";
    }

    // Validate password (example: minimum length)
   if (strlen($password) < 8 || !preg_match("/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $password)) {
        $errors[] = "Password must be at least 8 characters long and contain at least one uppercase letter and one lowercase letter";
    }


    // Check if terms and conditions are agreed
    if (!$terms) {
        $errors[] = "You must agree to the terms and conditions";
    }

    // If there are no validation errors, proceed with registration
    if (empty($errors)) {
        // Connect to your database 
        $servername = 'localhost';        
		$username_db ='root';
        $password_db = '';
        $dbname = "task_management_db";

        // Create connection
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }   
	
        // Prepare SQL statement to insert user data into the database  
        $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
		$stmt = $conn->prepare($sql);
       
        $stmt->bind_param("sss", $email, $username, $password);
        // Execute the prepared statement
        if ($stmt->execute()) {
			if($stmt->affected_rows > 0) {
                header("Location: ../index.php");
                exit();
            } else {
                 echo "No records were inserted.";
		    }
	    }		
        else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
} else {
    // Redirect to the registration form if accessed directly without form submission
    header("Location: ../pages/registration.html");
    exit();
}
?>

