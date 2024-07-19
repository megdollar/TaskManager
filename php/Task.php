<?php
// Establist DB connection and use other classes
require_once 'Database.php';
require_once 'Category.php';
require_once 'Notification.php';
require_once 'Occurence.php';

class Task {
    private $conn;
    private $table_name = "task";

    // Task attributes and methods
    public $taskId;
    public $userId;
    public $title;
    public $description;
    public $dueDate;
    public $priority;
    public $status;
    public $categoryId;
    public $occurenceId;
    public $notificationId;

    // Initialize a new Database object using the getConnection method from the class
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Method to create a new task
    public function createTask($userId, $title, $description, $dueDate, $priority, $categoryId) {
        $query = "INSERT INTO " . $this->table_name . " SET 
                  userId=:userId, title=:title, description=:description, dueDate=:dueDate, 
                  priority=:priority, status='pending', categoryId=:categoryId";
        $stmt = $this->conn->prepare($query);

        // Sanitize the data
        $userId = htmlspecialchars(strip_tags($userId));
        $title = htmlspecialchars(strip_tags($title));
        $description = htmlspecialchars(strip_tags($description));
        $dueDate = htmlspecialchars(strip_tags($dueDate));
        $priority = htmlspecialchars(strip_tags($priority));

        // Bind parameters
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":dueDate", $dueDate);
        $stmt->bindParam(":priority", $priority);
        $stmt->bindParam(":categoryId", $categoryId);

        // Execute the query
        return $stmt->execute();
    }

    // This is needed to get the last created task in task_handler
    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }

    // Method to update existing task
    public function updateTask($taskId, $title, $description, $dueDate, $priority, $status, $categoryId) {
        $query = "UPDATE " . $this->table_name . "
                  SET title = :title,
                      description = :description,
                      dueDate = :dueDate,
                      priority = :priority,
                      status = :status,
                      categoryId = :categoryId
                  WHERE taskId = :taskId";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $title = htmlspecialchars(strip_tags($title));
        $description = htmlspecialchars(strip_tags($description));
        $dueDate = htmlspecialchars(strip_tags($dueDate));
        $priority = htmlspecialchars(strip_tags($priority));
        $status = htmlspecialchars(strip_tags($status));

        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':dueDate', $dueDate);
        $stmt->bindParam(':priority', $priority);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->bindParam(':taskId', $taskId);

        // Execute the query
        return $stmt->execute();
    }

    // Used in task_handler to execute queries in DB related to foreign key constraints
    public function executeQuery($query) {
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
    
    // Method to delete a task 
    public function deleteTask($taskId) {
        // Delete related notifications and occurrences first due to FK constraints
        $query1 = "DELETE FROM notification WHERE taskId = :taskId";
        $query2 = "DELETE FROM occurence WHERE taskId = :taskId";

        $stmt1 = $this->conn->prepare($query1);
        $stmt2 = $this->conn->prepare($query2);
    
        $stmt1->bindParam(':taskId', $taskId);
        $stmt2->bindParam(':taskId', $taskId);
    
        $stmt1->execute();
        $stmt2->execute();
    
        // Delete the task 
        $query = "DELETE FROM " . $this->table_name . " WHERE taskId = :taskId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':taskId', $taskId);
        return $stmt->execute();
    }
    
    

    // Method to update the status to completed
    public function completeTask($taskId) {
        $query = "UPDATE " . $this->table_name . " SET status = 'completed' WHERE taskId = :taskId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':taskId', $taskId);
        return $stmt->execute();
    }

    // Method to get all tasks used for calendar view and list view
    public function viewTasks($userId, $date = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE userId = :userId";
        if ($date) {
            $query .= " AND dueDate = :dueDate";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        if ($date) {
            $stmt->bindParam(':dueDate', $date);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to print out task details used for editing task in dashboard
    public function viewTask($taskId) {
        // Left join with category, occurence, notification to get the actual details instead of just the ids 
        $query = "SELECT t.*, c.name as categoryName, o.pattern as occurencePattern, o.occurenceInterval, n.reminderTime as notificationTime
                  FROM " . $this->table_name . " t
                  LEFT JOIN category c ON t.categoryId = c.categoryId
                  LEFT JOIN occurence o ON t.occurenceId = o.occurenceId
                  LEFT JOIN notification n ON t.notificationId = n.notificationId
                  WHERE t.taskId = :taskId";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC); 
            } else {
                return null; 
            }
        } catch (PDOException $e) {
            // Database errors
            error_log('Error fetching task details: ' . $e->getMessage());
            return null; 
        }
    }
    


}
?>
