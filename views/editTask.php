<?php
// If user is not logged in redirect
session_start();
if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit;
}

require_once '../php/Task.php';
// create a new task object
$task = new Task();
$userId = $_SESSION['userId'];
// Sets the taskId if it is set
$taskId = isset($_GET['taskId']) ? $_GET['taskId'] : null;
// Initialize empty array for task details 
$taskDetails = [];

// If there is a task id get the details for the task
if ($taskId) {
    $taskDetails = $task->viewTask($taskId);
}

// Get all tasks for the user
$tasks = $task->viewTasks($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMinder Edit Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;900&family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<div id="header"></div>
    <div class="container px-4 py-5 my-5 text-center">
        <!-- If task if exists the user is editing a task, otherwise they are creating a task -->
        <h2><?php echo $taskId ? 'Edit Task' : 'Create Task'; ?></h2>
        <form id="taskForm">
            <input type="hidden" id="taskId" name="taskId" value="<?php echo htmlspecialchars($taskId); ?>">
            <div class="form-group mb-3 mt-3">
                <label for="title">Title</label>
                <!-- If editing a task, display current title -->
                <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($taskDetails['title']) ? htmlspecialchars($taskDetails['title']) : ''; ?>" required>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="description">Description</label>
                <!-- if editing a task, display current description -->
                <textarea class="form-control" id="description" name="description" required><?php echo isset($taskDetails['description']) ? htmlspecialchars($taskDetails['description']) : ''; ?></textarea>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="dueDate">Due Date</label>
                <!-- Current due date is displayed when editing task -->
                <input type="date" class="form-control" id="dueDate" name="dueDate" value="<?php echo isset($taskDetails['dueDate']) ? htmlspecialchars($taskDetails['dueDate']) : ''; ?>" required>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="priority">Priority</label>
                <select class="form-control" id="priority" name="priority" required>
                    <!-- Offer low, medium, high as priority for now -->
                    <option value="Low" <?php echo (isset($taskDetails['priority']) && $taskDetails['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                    <option value="Medium" <?php echo (isset($taskDetails['priority']) && $taskDetails['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                    <option value="High" <?php echo (isset($taskDetails['priority']) && $taskDetails['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
                </select>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <!-- Status is either pending or completed -->
                    <option value="pending" <?php echo (isset($taskDetails['status']) && $taskDetails['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="completed" <?php echo (isset($taskDetails['status']) && $taskDetails['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="categoryName">Category</label>
                <!-- Future dev: display existing categories with the option to add new ones to help remove duplicates -->
                <input type="text" class="form-control" id="categoryName" name="categoryName" value="<?php echo isset($taskDetails['categoryName']) ? htmlspecialchars($taskDetails['categoryName']) : ''; ?>" required>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="occurencePattern">Occurence Pattern</label>
                <select class="form-control" id="occurencePattern" name="occurencePattern">
                    <!-- How often does the task occur None (one-time), daily, weekly, monthly -->
                    <option value="">None</option>
                    <option value="daily" <?php echo (isset($taskDetails['occurencePattern']) && $taskDetails['occurencePattern'] == 'daily') ? 'selected' : ''; ?>>Daily</option>
                    <option value="weekly" <?php echo (isset($taskDetails['occurencePattern']) && $taskDetails['occurencePattern'] == 'weekly') ? 'selected' : ''; ?>>Weekly</option>
                    <option value="monthly" <?php echo (isset($taskDetails['occurencePattern']) && $taskDetails['occurencePattern'] == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                </select>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="occurenceInterval">Occurence Interval</label>
                <!-- Future dev: interval for occurence; make is more user friendly, it is kind of an odd display/field -->
                <input type="number" class="form-control" id="occurenceInterval" name="occurenceInterval" value="<?php echo isset($taskDetails['occurenceInterval']) ? htmlspecialchars($taskDetails['occurenceInterval']) : ''; ?>" placeholder="Leave empty if none">
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="notificationTime">Notification Time</label>
                <!-- When should the user be notified -->
                <input type="datetime-local" class="form-control" id="notificationTime" name="notificationTime" value="<?php echo isset($taskDetails['notificationTime']) ? htmlspecialchars($taskDetails['notificationTime']) : ''; ?>" placeholder="Leave empty if none">
            </div>
            <!-- Future dev: Notification method (sms/email) and Notification Method details (email address/phone number) -->
            
            <!-- The task is either being created or updated, if being updated offer the option to delete the task -->
            <button type="submit mt-3 mb-3" class="btn btn-primary"><?php echo $taskId ? 'Update Task' : 'Create Task'; ?></button>
            <?php if ($taskId): ?>
                <button type="button mt-3 mb-3" class="btn btn-danger delete-task" data-task-id="<?php echo $task['taskId']; ?>Delete Task</button>
            <?php endif; ?>
        </form>
        <div id="result"></div>
    </div>
    <div class="container">
        <h3>Your Tasks</h3>
        <?php if (count($tasks) > 0): ?>
            <ul class="list-group">
                <!-- Display all the tasks by user, offering option to edit or delete need to add category, notification, occurence -->
                <?php foreach ($tasks as $task): ?>
                    <li class="list-group-item mt-3 mb-3">
                        <h5><?php echo htmlspecialchars($task['title']); ?></h5>
                        <p><?php echo htmlspecialchars($task['description']); ?></p>
                        <p><strong>Due Date:</strong> <?php echo htmlspecialchars($task['dueDate']); ?></p>
                        <p><strong>Priority:</strong> <?php echo htmlspecialchars($task['priority']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($task['status']); ?></p>
                        <!-- Reload page passing in taskid to allow user to edit the task -->
                        <a href="editTask.php?taskId=<?php echo $task['taskId']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-task" data-task-id="<?php echo $task['taskId']; ?>">Delete</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No tasks found.</p>
        <?php endif; ?>
    </div>
    <div id="footer"></div>

    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script>
        $(function () {
            $("#header").load("header.html");
            $("#footer").load("footer.html");
        });

        $(document).ready(function() {
            $('#taskForm').submit(function(event) {
                event.preventDefault();
                // Serialize the form data
                let formData = $(this).serialize();
                // get the action of the form
                let action = $('#taskId').val() ? 'update_task' : 'create_task';

                // Post call with the action (update_task or create_task)
                $.ajax({
                    url: '../php/task_handler.php',
                    type: 'POST',
                    data: formData + '&action=' + action,
                    success: function(response) {
                        let result = JSON.parse(response);
                        if (result.success) {
                            $('#result').html('<div class="alert alert-success">Task ' + (action == 'update_task' ? 'updated' : 'created') + ' successfully.</div>');
                            if (action === 'create_task') {
                                // reload the page
                                location.reload();
                            }
                            else if (action === 'update_task') {
                                // Redirect to dashboard
                                window.location.href = 'dashboard.php'; 
                        }
                        } else {
                            // error 
                            $('#result').html('<div class="alert alert-danger">Failed to ' + (action == 'update_task' ? 'update' : 'create') + ' task.</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error managing task:', error);
                        $('#result').html('<div class="alert alert-danger">Error managing task.</div>');
                    }
                });
            });

            // delete task
            $('.delete-task').click(function() {
                if (confirm('Are you sure you want to delete this task?')) {
                    let taskId = $(this).data('task-id');

                    $.ajax({
                        url: '../php/task_handler.php',
                        type: 'POST',
                        data: { action: 'delete_task', taskId: taskId },
                        success: function(response) {
                            let result = JSON.parse(response);
                            if (result.success) {
                                // Redirect to dashboard
                                window.location.href = 'dashboard.php'; 
                            } else {
                                $('#result').html('<div class="alert alert-danger">Failed to delete task.</div>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting task:', error);
                            $('#result').html('<div class="alert alert-danger">Error deleting task.</div>');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
