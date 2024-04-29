<?php
session_start();

// Database information
$hostname = 'localhost';  
$username = 'root';
$password = '';
$database = 'task_management_db'; 

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    
    if (!isset($_SESSION['user_id'])) {
        echo "user_id not connected.";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    
    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO task (title, description, priority, status, due_date, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $title, $description, $priority, $status, $due_date, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Task added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management Program</title>
    <link rel="stylesheet" href="../css/task.css">
	
	<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        padding: 0;
        background-color: #f4f4f4;
        font-size: 18px;
    }

    .header {
        display: flex;
        justify-content: center; 
        align-items: center;
        margin-bottom: 20px;
    }

    .header button {
        background: none;
        border: none;
        font-size: 22px;
        cursor: pointer;
    }

    .container {
        max-width: 80%;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        margin: 0;
        font-size: 30px;
    }

    form label {
        font-weight: bold;
        font-size: 20px;
    }

    form textarea,
    form input[type="date"],
    form select {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 18px;
    }

    #submitTaskBtn {
        width: 100%;
        padding: 10px;
        background-color: #2b8d8d;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
    }

    #submitTaskBtn:hover {
        background-color: #2b8d8d;
    }

    .task-item {
        margin-bottom: 10px;
        padding: 10px;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 18px;
    }

    .task-item p {
        margin: 5px 0;
    }

    .task-item.completed {
        background-color: #dff0d8;
        border-color: #d0e9c6;
    }

    .footer {
        position: fixed;
        bottom: 0;
        width: 96%;
        text-align: center;
        padding: 10px;
        background-color: #2b8d8d;
        color: #fff;
        font-size: 16px;
    }
</style>

</head>
<body>
    <!-- Container for the entire page content -->
    <div class="container">
        <!-- Header section -->
        <div class="header">
            <!-- Title of the page -->
            <h1>Task Edit</h1>
        </div>
        <!-- Form for adding a new task -->
        <form id="taskForm">
            <!-- Input field for task title -->
            <label for="title">Task name:</label><br>
            <textarea id="title" rows="2" cols="50" required></textarea><br><br>
            
            <!-- Input field for task description -->
            <label for="description">Task content:</label><br>
            <textarea id="description" rows="4" cols="50" required></textarea><br><br>

            <!-- Dropdown for task priority -->
            <label for="priority">Priority:</label>
            <select id="priority">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select><br><br>

            <!-- Input field for task due date -->
            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" required><br><br>

            <!-- Dropdown for task status -->
            <label for="status">Status:</label>
            <select id="status">
                <option value="todo">To Do</option>
                <option value="in_Progress">In Progress</option>
                <option value="done">Done</option>
            </select><br><br>

            <!-- Button to submit the form -->
            <button type="submit" id="submitTaskBtn">Submit</button>
        </form>
    </div>

    <!-- JavaScript section -->
    <script>
        // JavaScript code for form submission via AJAX
        document.getElementById('submitTaskBtn').addEventListener('click', function() {
            var title = document.getElementById('title').value;
            var description = document.getElementById('description').value;
            var due_date = document.getElementById('due_date').value;
        
            // Validation checks for input fields
            if(title.trim() === '') {
                alert('Task Name cannot be empty!');
                return; // Prevent further execution
            }
        
            if(description.trim() === '') {
                alert('Task Content cannot be empty!');
                return; // Prevent further execution
            }
        
            if(due_date.trim() === '') {
                alert('Due Date cannot be empty!');
                return; // Prevent further execution
            }
        
            // Fetching other form values
            var priority = document.getElementById('priority').value;
            var status = document.getElementById('status').value;
     
            // Creating an AJAX request
            var xhr = new XMLHttpRequest();
        
            xhr.open('POST', 'addTask.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
            xhr.onreadystatechange = function() {
                // Handling response from server
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Redirecting to task.php upon successful addition of task
                        window.location.href = 'task.php';
                    } else {
                        // Displaying error message if request fails
                        alert('Error: ' + xhr.status);
                    }
                }
            };
        
            // Sending form data to server
            xhr.send('title=' + encodeURIComponent(title) + '&description=' + encodeURIComponent(description) + '&priority=' + encodeURIComponent(priority) + '&status=' + encodeURIComponent(status) + '&due_date=' + encodeURIComponent(due_date));
        });
    </script>
    
    <!-- Footer section -->
    <footer class="footer">
        <!-- Copyright information -->
        <p>&copy; 2024 Task Manager. All rights reserved.</p>
    </footer>
</body>
</html>

