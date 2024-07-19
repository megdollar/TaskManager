<?php
session_start();
// Check to see if user is logged in, if not redirect to index page
if (!isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMinder Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;900&family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="../css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>

</head>
<body>
<div id="header"></div>
    <div class="container px-4 py-5 my-5 text-center">
        <h1 class="mb-3">Welcome, <?php echo $_SESSION['name']; ?>!</h1>
        <a href="editAccount.php" class="btn btn-info">Edit Account</a>
        <button id="logoutButton" class="btn btn-danger">Log Out</button>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" id="listViewButton">Today's Tasks</button>
            <button type="button" class="btn btn-secondary" id="calendarViewButton">Calendar View</button>
        </div>
        <!-- Future dev: allow user to click on task to display additional details, along with the ability to mark complete, delete, or edit (redirect to edit page) -->
        <div id="listView" style="display: none;">
            <h3 class="mt-3 mb-3">Today's Tasks</h3>
            <ul id="tasksList" class="list-group"></ul>
        </div>

        <div id="calendarView" style="display: none;">
            <h3 class="mt-3 mb-3">Calendar View</h3>
            <div id="calendar"></div>
        </div>


        <div id="result"></div>
    </div>
    <div id="footer"></div>

    <script src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script>
         $(function () {
          $("#header").load("header.html");
          $("#footer").load("footer.html");
      });
        $(document).ready(function() {
            // pass logout action to account_handler
            $('#logoutButton').click(function() {
                $.ajax({
                    url: '../php/account_handler.php',
                    type: 'POST',
                    data: { action: 'logout' },
                    success: function(response) {
                        window.location.href = 'index.php';
                    }
                });
            });

            // Toggle list and calendar view
            $('#listViewButton').click(function() {
                $('#listView').show();
                $('#calendarView').hide();
                // call function to get list view tasks
                getTodayTasks();
            });

            $('#calendarViewButton').click(function() {
                $('#calendarView').show();
                $('#listView').hide();
                // call function to get calendar view tasks
                getCalendarTasks();
            });

            // list view of tasks for today, calling task_handler 
            function getTodayTasks() {
                $.ajax({
                    url: '../php/task_handler.php',
                    type: 'POST',
                    data: { action: 'get_today_tasks' },
                    success: function(response) {
                        let tasks = JSON.parse(response);
                        $('#tasksList').empty();
                        tasks.forEach(task => {
                            // Put each task as a list element displaying the title
                            $('#tasksList').append(`<li class="list-group-item">${task.title}</li>`);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching today\'s tasks:', error);
                        console.log(xhr.responseText);
                    }
                });
            }

            // Initialize FullCalendar and get tasks for calendar view calling task_handler
            function getCalendarTasks() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: function(fetchInfo, successCallback, failureCallback) {
                        $.ajax({
                            url: '../php/task_handler.php',
                            type: 'POST',
                            data: { action: 'get_all_tasks' },
                            success: function(response) {
                                let tasks = JSON.parse(response);
                                // Get the task title and due date for each task to map to the calendar
                                let events = tasks.map(task => ({
                                    title: task.title,
                                    start: task.dueDate
                                }));
                                successCallback(events);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching calendar tasks:', error);
                                console.log(xhr.responseText);
                                failureCallback(error);
                            }
                        });
                    }
                });
                calendar.render();
            }
             // Initial load, show list view and call function to get the daily tasks
             $('#listView').show();
            $('#calendarView').hide();
            getTodayTasks();
        });
    </script>
</body>
</html>
