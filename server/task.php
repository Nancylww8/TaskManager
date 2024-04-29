<?php
// Start the session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
    header("Location: ./login_form.html");
    exit();
}

// Database connection parameters
$hostname = 'localhost';
$username = 'root';
$password = ''; 
$database = 'task_management_db';

// Create connection to the database
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// SQL query to select tasks for the logged-in user
$sql = "SELECT * FROM task WHERE user_id='$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="../css/task.css">
</head>
<body>
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <span class="navbar-brand">Task Manager</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>

        <div class="content-container">
            <div class="left-container">
                <!-- User Profile -->
                <div class="user-profile">
                    <img src="../images/avatar.png" alt="User Avatar" class="user-avatar"><br>
                    <span class="username">
                        <?php echo $_SESSION['username']; ?>
                    </span><br>
                </div>
            </div>

            <div class="right-container">
                <div class="tasks-container">
                    <!-- New Task Button -->
                    <button id="add-task-btn">New Task</button>

                    <!-- Sort Dropdown -->
                    <select id="sort-select" placeholder="Sort by due date">
                        <option value="sortDefault" hidden>Sort by due date</option>
                        <option value="ascending">Ascending</option>
                        <option value="descending">Descending</option>
                    </select>                   
                    <button id="sort-button">Sort</button>
                    
                    <!-- Search Tasks -->
                    <input type="text" id="search-input" placeholder="Search tasks">
                    <button id="search-btn">Search</button><br><br>
				
					<!-- Filter by Priority -->
					<div class="filter">
                        <label for="priorityFilter">Filter by Priority:</label>
                        <select id="priorityFilter" onchange="filterTasks()">
                            <option value="">All Priority</option>
					        <option value="HIGH">High</option>
					        <option value="MEDIUM">Medium</option>
					        <option value="LOW">Low</option>
                        </select>
                    </div><br>
            
                    <!-- Display tasks in a table -->
                    <?php
                    if ($result->num_rows > 0) {
                        echo "<table class='tasks-table'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Task Name</th>";
                        echo "<th>Due Date</th>";
                        echo "<th>Priority</th>";
                        echo "<th>Status</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        // Display tasks
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='task-item'>";
                            echo "<td class='task-name'><a href='task_detail.php?task_id=" . $row['id'] . "'>" . $row['title'] . "</a></td>";
                            echo "<td class='due-date'>" . $row['due_date'] . "</td>";
                            echo "<td class='priority'>" . $row['priority'] . "</td>";
                            echo "<td class='status'>" . $row['status'] . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "No tasks found.";
                        // Display empty table if no tasks found
                        echo "<table class='tasks-table'><thead><tr><th>Task Name</th><th>Due Date</th><th>Priority</th><th>Status</th></tr></thead><tbody></tbody></table>";
                    }

                    // Close database connection
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; 2024 Task Manager. All rights reserved.</p>
        </footer>
    </div>

    <!-- JavaScript -->
    <script src="../scripts/task.js"></script>
    <script>
        // Function to filter tasks by priority
        function filterTasks() {
            const selectedPriority = document.getElementById('priorityFilter').value;
            console.log('selected priority:', selectedPriority);

            const tasks = document.querySelectorAll('.task-item');

            tasks.forEach(task => {
                const priority = task.querySelector('.priority').textContent.trim();
                console.log('task priority', priority);

                if (selectedPriority === '' || priority === selectedPriority) {
                    task.style.display = 'table-row';
                } else {
                    task.style.display = 'none';
                }
            });
        }

        // Initialize filterTasks function on page load
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('priorityFilter').addEventListener('change', filterTasks);
        });

        // Function to sort tasks by due date
        function sortTasks() {
            const selectedSorting = document.getElementById('sort-select').value;
            const tasksContainer = document.querySelector('.tasks-container tbody');
            let tasks = Array.from(document.querySelectorAll('.task-item'));

            tasks.sort((a, b) => {
                const dateA = new Date(a.querySelector('.due-date').textContent.trim());
                const dateB = new Date(b.querySelector('.due-date').textContent.trim());

                return selectedSorting === 'ascending' ? dateA - dateB : dateB - dateA;
            });

            tasksContainer.innerHTML = '';
            tasks.forEach(task => tasksContainer.appendChild(task));
        }

        // Initialize sortTasks function on page load
       document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('priorityFilter').addEventListener('change', filterTasks);
            document.getElementById('sort-button').addEventListener('click', sortTasks); 
        });

        // Function to search tasks
        function searchTasks() {
            const searchText = document.getElementById('search-input').value.toLowerCase();
            const tasks = document.querySelectorAll('.task-item');

            tasks.forEach(task => {
                const taskName = task.querySelector('.task-name').textContent.toLowerCase();
                const dueDate = task.querySelector('.due-date').textContent.toLowerCase();
                const priority = task.querySelector('.priority').textContent.toLowerCase();
                const status = task.querySelector('.status').textContent.toLowerCase();

                if (taskName.includes(searchText) || dueDate.includes(searchText) ||
                    priority.includes(searchText) || status.includes(searchText)) {
                    task.style.display = 'table-row';
                } else {
                    task.style.display = 'none';
                }
            });
        }

        // Initialize searchTasks function on page load
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('priorityFilter').addEventListener('change', filterTasks);
            document.getElementById('sort-button').addEventListener('click', sortTasks);
            document.getElementById('search-btn').addEventListener('click', searchTasks);
        });
    </script>
</body>
</html>
