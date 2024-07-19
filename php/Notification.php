<?php
// Establish database connection
require_once 'Database.php';

class Notification {
    private $conn;
    private $table_name = "notification";

    // Notification attributes and methods
    private $notificationId;
    private $taskId;
    private $userId;
    private $reminderTime;
    private $notificationSent;

    // Initialize a new Database object using the getConnection method from the class
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Method to create a notification with details
    public function scheduleNotification($taskId, $userId, $reminderTime) {

        $query = "INSERT INTO " . $this->table_name . " SET taskId=:taskId, userId=:userId, reminderTime=:reminderTime, notificationSent=0";
        $stmt = $this->conn->prepare($query);
    
        // Sanitize the data
        $taskId = htmlspecialchars(strip_tags($taskId));
        $userId = htmlspecialchars(strip_tags($userId));
        $reminderTime = htmlspecialchars(strip_tags($reminderTime));
    
        // Bind parameters
        $stmt->bindParam(':taskId', $taskId);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':reminderTime', $reminderTime);
    
        // Execute the query
        return $stmt->execute();
    }
    
    // Method to send a notification and update notificationSent bool to 1
    public function sendNotification($notificationId) {
        // SQL to get the notification details
        $query = "SELECT * FROM " . $this->table_name . " WHERE notificationId = :notificationId AND notificationSent = 0";
        $stmt = $this->conn->prepare($query);
    
        // Sanitize the data
        $notificationId = htmlspecialchars(strip_tags($notificationId));
    
        // Bind parameters
        $stmt->bindParam(':notificationId', $notificationId);
    
        // Execute the query
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $notification = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // FUTURE DEV: Actually send some kind of notification either by email or SMS
    
            // Mark the notification as sent
            $updateQuery = "UPDATE " . $this->table_name . " SET notificationSent = 1 WHERE notificationId = :notificationId";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':notificationId', $notificationId);
    
            if ($updateStmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Method to delete a notification by its ID
    public function deleteNotification($notificationId) {
        $query = "DELETE FROM " . $this->table_name . " WHERE notificationId = :notificationId";
        $stmt = $this->conn->prepare($query);

        // Sanitize the data
        $notificationId = htmlspecialchars(strip_tags($notificationId));

        // Bind parameters
        $stmt->bindParam(':notificationId', $notificationId);

        // Execute the query
        return $stmt->execute();
    }


    
}
?>
