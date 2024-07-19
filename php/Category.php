<?php
// Establish database connection
require_once 'Database.php';

class Category {
    private $conn;
    private $table_name = "category";

    // Category attributes and methods
    public $categoryId;
    public $name;
    public $description;

    // Initialize a new Database object using the getConnection method from the class
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Method to add a new category
    public function addCategory($name, $description) {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, description=:description";
        $stmt = $this->conn->prepare($query);
    
        // Sanitize the data
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
    
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
    
        // Execute the query
        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Return the ID of the newly created category
        } else {
            return false; // Return false on failure
        }
    }
    

    // Method to update an existing category
    public function updateCategory($categoryId, $name, $description) {
        // SQL to update the fields
        $query = "UPDATE " . $this->table_name . " SET name = :name, description = :description WHERE categoryId = :categoryId";
        $stmt = $this->conn->prepare($query);

        // Sanitize the data
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $categoryId = htmlspecialchars(strip_tags($categoryId));

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categoryId', $categoryId);

        // Execute the query
        return $stmt->execute();
    }

    // Method to delete a category
    public function deleteCategory($categoryId) {
        // Delete category based on id
        $query = "DELETE FROM " . $this->table_name . " WHERE categoryId = :categoryId";
        $stmt = $this->conn->prepare($query);

        // Sanitize the data
        $categoryId = htmlspecialchars(strip_tags($categoryId));

        // Bind parameters
        $stmt->bindParam(':categoryId', $categoryId);

        // Execute the query
        return $stmt->execute();
    }

    // Method to view a category's details
    public function viewCategory($categoryId) {
        // SQL to get the category row based on id
        $query = "SELECT * FROM " . $this->table_name . " WHERE categoryId = :categoryId";
        $stmt = $this->conn->prepare($query);

        // Sanitize the data
        $categoryId = htmlspecialchars(strip_tags($categoryId));

        // Bind parameters
        $stmt->bindParam(':categoryId', $categoryId);

        // Execute the query
        $stmt->execute();

        // Get the category details
        if ($stmt->rowCount() > 0) {
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            return $category;

        } else {

            return null;

        }
    }


}
?>