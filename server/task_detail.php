<?php
// Start session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
    header("Location: login_form.html");
    exit();
}

// Database connection parameters
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'task_management_db';

// Create database connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check if connection failed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['task_id'];
    
    if (isset($_POST['delete'])) {
        // Delete task logic
        $stmt = $conn->prepare("DELETE FROM task WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $stmt->close();
        header("Location: task.php"); // Redirect to the task list page
        exit();
    } else {
        // Update task logic
        $title = $_POST['title'];
        $description = $_POST['description'];
        $priority = $_POST['priority'];
        $status = $_POST['status'];
        $due_date = $_POST['due_date'];

        $stmt = $conn->prepare("UPDATE task SET title = ?, description = ?, priority = ?, status = ?, due_date = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $title, $description, $priority, $status, $due_date, $task_id);
        
        $stmt->execute();
        $stmt->close();
        header("Location: task.php"); // Redirect to the task list page
        exit();
    }
}

// Get task ID from request
if (empty($task_id)) {
    $task_id = $_GET['task_id'] ?? null;
}

// Retrieve task details from database
if ($task_id) {
    $stmt = $conn->prepare("SELECT * FROM task WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $stmt->close();
        exit('Task not found.');
    }
    $task = $result->fetch_assoc();
    $stmt->close();
} else {
    exit('No task ID provided.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Edit</title>   
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Task Edit</h1>
        </div>
        <form id="taskForm" method="POST">
            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
            <label for="title">Task name:</label><br>
            <!-- Textarea for task title -->
            <textarea id="title" name="title" rows="2" cols="50" required><?php echo htmlspecialchars($task['title']); ?></textarea><br><br>
            <label for="description">Task content:</label><br>
            <!-- Textarea for task description -->
            <textarea id="description" name="description" rows="4" cols="50" required><?php echo htmlspecialchars($task['description']); ?></textarea><br><br>
            <label for="priority">Priority:</label>
            <!-- Dropdown for task priority -->
            <select id="priority" name="priority">
                <option value="low" <?php echo $task['priority'] == 'low' ? 'selected' : ''; ?>>Low</option>
                <option value="medium" <?php echo $task['priority'] == 'medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="high" <?php echo $task['priority'] == 'high' ? 'selected' : ''; ?>>High</option>
            </select><br><br>
            <label for="due_date">Due Date:</label>
            <!-- Input for due date -->
            <input type="date" id="due_date" name="due_date" value="<?php echo $task['due_date']; ?>" required><br><br>
            <label for="status">Status:</label>
            <!-- Dropdown for task status -->
            <select id="status" name="status">
                <option value="todo" <?php echo $task['status'] == 'todo' ? 'selected' : ''; ?>>To Do</option>
                <option value="in_progress" <?php echo $task['status'] == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="done" <?php echo $task['status'] == 'done' ? 'selected' : ''; ?>>Done</option>
            </select><br><br>
            
            <div class="button-container">
                <!-- Button to submit form for updating task -->
                <button type="submit" id="updateTaskBtn">Update</button>
                <!-- Button to submit form for deleting task -->
                <button type="submit" id="deleteTaskBtn" form="deleteForm">Delete</button>
            </div>
        </form>

        <!-- Form for deleting task -->
        <form id="deleteForm" method="POST">
            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
            <input type="hidden" name="delete" value="true">
        </form>
    </div>
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Task Manager. All rights reserved.</p>
    </footer>
</body>
</html>

<style>
/* CSS Styles */
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

.button-container {
    display: flex;
    justify-content: center;
    gap: 20px;
}

#updateTaskBtn,
#deleteTaskBtn {
    width: 100px;
    padding: 10px;
    background-color: #2b8d8d;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px; 
}

#updateTaskBtn:hover,
#deleteTaskBtn:hover {
    background-color: #267373;
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
