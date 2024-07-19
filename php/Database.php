<?php
class Database {
    private $host = "localhost";
    private $db_name = "taskminder";
    // Default XAMPP user name and password
    private $username = "root";  
    private $password = ""; 
    public $connection;

    // Connect to the database
    public function getConnection() {
        // Initialize connection
        $this->connection = null;
        try {
            // Create PDO instance using variables above to connect to DB
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            // Set attribute for error handling
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            // Error handling, catch the exception and print the error message
            echo "Connection error: " . $exception->getMessage();
            echo json_encode(['error' => 'Database connection error']);
            exit;
        }
        return $this->connection;
    }
}
?>