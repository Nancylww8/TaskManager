// JavaScript file (task.js)

// Function to navigate to the newTask.html page when clicking "New Task"
document.getElementById('new-task-btn').addEventListener('click', function() {
    window.location.href = 'newTask.html'; // Redirect to newTask.html
});

// Function to show filter options when clicking "Filter"
document.getElementById('filter-btn').addEventListener('click', function() {
    // Your logic to show a modal or popup with filter options
    alert("Filter options will be shown here.");
});

// Function to search tasks based on input text
document.getElementById('search-input').addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase().trim();
    const taskRows = document.querySelectorAll('.task-item');
    taskRows.forEach(row => {
        const taskName = row.querySelector('.task-name').textContent.toLowerCase();
        const dueDate = row.querySelector('.due-date').textContent.toLowerCase();
        const priority = row.querySelector('.priority').textContent.toLowerCase();
        const status = row.querySelector('.status').textContent.toLowerCase();
        if (taskName.includes(searchText) || dueDate.includes(searchText) || priority.includes(searchText) || status.includes(searchText)) {
            row.style.display = ''; // Show the row if any of the fields match the search text
        } else {
            row.style.display = 'none'; // Hide the row if no match
        }
    });
});