<?php
// We need to create objects for all in this handler 
require_once 'Task.php';
require_once 'Category.php';
require_once 'Occurence.php';
require_once 'Notification.php';

// Create objects for each class
$task = new Task();
$category = new Category();
$occurence = new Occurence();
$notification = new Notification();

// If we are trying to submit the form get the action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    try {
        // Start output buffering
        ob_start(); 
        switch ($action) {
            // Create a new task getting the information passed in from the form
            case 'create_task':
                session_start();
                $userId = $_SESSION['userId'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $dueDate = $_POST['dueDate'];
                $priority = $_POST['priority'];
                $categoryName = $_POST['categoryName'];
                $occurencePattern = $_POST['occurencePattern'] ?? null;
                $occurenceInterval = $_POST['occurenceInterval'] ?? null;
                $notificationTime = $_POST['notificationTime'] ?? null;
                $notificationMethod = $_POST['notificationMethod'] ?? null;

                // Create category using name
                $categoryId = $category->addCategory($categoryName, '');
                if (!$categoryId) {
                    throw new Exception("Error adding category");
                }

                // Create task using details from form calling method from task class
                $result = $task->createTask($userId, $title, $description, $dueDate, $priority, $categoryId);
                if (!$result) {
                    throw new Exception("Error creating task");
                }

                // Get the task id created
                $taskId = $task->getLastInsertId();

                // Create occurence if there is an occurence pattern
                if ($occurencePattern) {
                    $occurenceId = $occurence->setOccurence($taskId, $occurencePattern, $occurenceInterval);
                    if (!$occurenceId) {
                        throw new Exception("Error setting occurence");
                    }
                }

                // Create notification if there is a notification time
                if ($notificationTime) {
                    $notificationTime = date('Y-m-d H:i:s', strtotime($notificationTime));
                    $notificationId = $notification->scheduleNotification($taskId, $userId, $notificationTime);
                    if (!$notificationId) {
                        throw new Exception("Error scheduling notification");
                    }
                }

                echo json_encode(['success' => true]);
                break;

            // Update an existing task using data from the form
            case 'update_task':
                $taskId = $_POST['taskId'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $dueDate = $_POST['dueDate'];
                $priority = $_POST['priority'];
                $status = $_POST['status'];
                $categoryName = $_POST['categoryName'];
                $occurencePattern = $_POST['occurencePattern'] ?? null;
                $occurenceInterval = $_POST['occurenceInterval'] ?? null;
                $notificationTime = $_POST['notificationTime'] ?? null;
                $notificationMethod = $_POST['notificationMethod'] ?? null;

                // Update category name
                $categoryId = $category->addCategory($categoryName, '');
                if (!$categoryId) {
                    throw new Exception("Error adding category");
                }

                // Update task with new information
                $result = $task->updateTask($taskId, $title, $description, $dueDate, $priority, $status, $categoryId);
                if (!$result) {
                    throw new Exception("Error updating task");
                }

                // Update occurence pattern and interval
                if ($occurencePattern) {
                    $occurenceId = $occurence->updateOccurence($taskId, $occurencePattern, $occurenceInterval);
                    if (!$occurenceId) {
                        throw new Exception("Error updating occurence");
                    }
                }

                // Update notification time
                if ($notificationTime) {
                    $notificationTime = date('Y-m-d H:i:s', strtotime($notificationTime));
                    $notificationId = $notification->scheduleNotification($taskId, $userId, $notificationTime);
                    if (!$notificationId) {
                        throw new Exception("Error scheduling notification");
                    }
                }

                echo json_encode(['success' => true]);
                break;
            
                // delete a task -- this one required additional methods added to Task
                case 'delete_task':
                    $taskId = $_POST['taskId'];
                
                    // Drop foreign key constraints in occurence and notification for taskId
                    $query1 = "ALTER TABLE occurence DROP FOREIGN KEY IF EXISTS occurence_ibfk_1";
                    $query2 = "ALTER TABLE notification DROP FOREIGN KEY IF EXISTS notification_ibfk_3";
                    // Call Task method to execute query dropping constraints
                    $task->executeQuery($query1);
                    $task->executeQuery($query2);
                
                    // Delete the task 
                    $result = $task->deleteTask($taskId);
                
                    // Re-add constraints with ON DELETE CASCADE 
                    
                    $query3 = "ALTER TABLE occurence ADD CONSTRAINT occurence_ibfk_1 FOREIGN KEY (taskId) REFERENCES task(taskId) ON DELETE CASCADE";
                    $query4 = "ALTER TABLE notification ADD CONSTRAINT notification_ibfk_3 FOREIGN KEY (taskId) REFERENCES task(taskId) ON DELETE CASCADE";
                    $task->executeQuery($query3);
                    $task->executeQuery($query4);
                
                    echo json_encode(['success' => $result]);
                break;
                
                // Mark task completed
                case 'complete_task':
                    $taskId = $_POST['taskId'];
                    $result = $task->completeTask($taskId);
                    echo json_encode(['success' => $result]);
                    break;
                
                // Calls the view task method to get the details of a task by taskId
                case 'view_task':
                    $taskId = $_POST['taskId'];
                    $taskDetails = $task->viewTask($taskId);
                    echo json_encode($taskDetails);
                    break;
                
                // Calls the view tasks method passing in today's date
                case 'get_today_tasks':
                    session_start();
                    $userId = $_SESSION['userId'];
                    $date = date('Y-m-d');
                    $tasks = $task->viewTasks($userId, $date);
                    echo json_encode($tasks);
                    break;
                
                // Gets all tasks by userID 
                case 'get_all_tasks':
                    session_start();
                    $userId = $_SESSION['userId'];
                    $tasks = $task->viewTasks($userId);
                    echo json_encode($tasks);
                    break;

                default:
                    echo json_encode(['error' => 'Invalid action.']);
                    break;
            }
        } catch (Exception $e) {
            error_log("Error processing action $action: " . $e->getMessage());
            echo json_encode(['error' => 'Internal Server Error']);
        }
    }
?>
