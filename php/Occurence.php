<?php
// Establish database connection
require_once 'Database.php';

class Occurence {
    private $conn;
    private $table_name = "occurence";

    // Occurence attributes and methods
    private $occurenceId;
    private $taskId;
    private $pattern;
    private $occurenceInterval;

    // Initialize a new Database object using the getConnection method from the class
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Method to set the occurence of task
    public function setOccurence($taskId, $pattern, $occurenceInterval) {
        // SQL to insert task occurence
        $query = "INSERT INTO " . $this->table_name . " SET taskId=:taskId, pattern=:pattern, occurenceInterval=:occurenceInterval";
        $stmt = $this->conn->prepare($query);

        // Sanitize the data
        $taskId = htmlspecialchars(strip_tags($taskId));
        $pattern = htmlspecialchars(strip_tags($pattern));
        $occurenceInterval = htmlspecialchars(strip_tags($occurenceInterval));


        // Bind parameters
        $stmt->bindParam(':taskId', $taskId);
        $stmt->bindParam(':pattern', $pattern);
        $stmt->bindParam(':occurenceInterval', $occurenceInterval);


        // Execute the query
        return $stmt->execute();
    }

    // Method to change existing occurence of task
    public function updateOccurence($taskId, $pattern, $occurenceInterval) {
        // SQL to change the pattern or occurence
        $query = "UPDATE " . $this->table_name . " SET pattern=:pattern, occurenceInterval=:occurenceInterval  WHERE occurenceId=:occurenceId";
        $stmt = $this->conn->prepare($query);

        // Sanitize the data
        $taskId = htmlspecialchars(strip_tags($taskId));
        $pattern = htmlspecialchars(strip_tags($pattern));
        $occurenceInterval = htmlspecialchars(strip_tags($occurenceInterval));


        // Bind parameterss
        $stmt->bindParam(':taskId', $taskId);
        $stmt->bindParam(':pattern', $pattern);
        $stmt->bindParam(':occurenceInterval', $occurenceInterval);


        // Execute the query
        return $stmt->execute();
    }

    // Method to delete an occurrence by its ID
    public function deleteOccurence($occurenceId) {
        $query = "DELETE FROM " . $this->table_name . " WHERE occurenceId = :occurenceId";
        $stmt = $this->conn->prepare($query);

        // Sanitize the data
        $occurenceId = htmlspecialchars(strip_tags($occurenceId));

        // Bind parameters
        $stmt->bindParam(':occurenceId', $occurenceId);

        // Execute the query
        return $stmt->execute();
    }


    // Method to display occurence of task
    public function viewOccurence($occurenceId) {
        // SQL to get the occurence
        $query = "SELECT * FROM " . $this->table_name . " WHERE occurenceId = :occurenceId";
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $occurenceId = htmlspecialchars(strip_tags($occurenceId));

        // Bind parameters
        $stmt->bindParam(':occurenceId', $occurenceId);

        // Execute the query
        $stmt->execute();
        
        // Print the details
        if ($stmt->rowCount() > 0) {
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            return $occurence;

        } else {

            return null;

        }
       
    }

}
?>
