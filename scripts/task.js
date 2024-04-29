// JavaScript file (task.js)

// Function to navigate to addTask.php when clicking "New Task"
var addTaskBtn = document.getElementById("add-task-btn");
addTaskBtn.addEventListener("click", function() {
    window.location.href = "../server/addTask.php";
});

// Function to handle logout
function handleLogout() {
    // Redirect to Logout.html
    window.location.href = "../server/logout.php";
}

// Add event listener to the logout button
document.addEventListener("DOMContentLoaded", function() {
    var logoutButton = document.querySelector('.logout-btn');
    if (logoutButton) {
        logoutButton.addEventListener('click', handleLogout);
    }
});
