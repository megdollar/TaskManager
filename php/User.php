<?php
// Establish database connection
require_once 'Database.php';

class User {
    // Properties for the DB connection
    private $connection;
    private $table_name = "user";

    // User attributes and methods
    public $userId;
    public $name;
    public $email;
    public $password;

    // Initialize a new Database object using the getConnection method from the class
    public function __construct() {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    // public method added for userTesting
    public function getConnection() {
        return $this->connection;
    }

    // Method to create a new user and insert the data into the user table
    public function createAccount($name, $email, $password) {
        // Code needed to be uncommented after performing UserTests.php
        // Check if user already exists in the DB
        // $query = "SELECT COUNT(*) as count from " . $this->table_name . " WHERE email=:email";
        // $stmt = $this->connection->prepare($query);
        // $stmt->bindParam(':email', $email);
        // $stmt->execute();
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // if ($row['count'] > 0) {
        //     echo "Error: A user with this email already exists\n";
        //     return false;
        // }

        // Create a new user
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, email=:email, password=:password";
        $stmt = $this->connection->prepare($query);

        // Sanitize the data by converting special characters to HTML entities and striping HTML and PHP tags
        $this->name = htmlspecialchars(strip_tags($name));
        $this->email = htmlspecialchars(strip_tags($email));
        // Hash the password using algorithms to securely store the password
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        // Sanitize the data by converting special characters to HTML entities and striping HTML and PHP tags
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        // Try to insert the user
        if ($stmt->execute()) {
            return true;
        }
        // Error handling, print out the details
        echo "Error: " . $stmt->errorInfo()[2] . "\n";
        return false;
    }

    // Method for an existing user to log in
    public function login($email, $password) {
        // Select all columns from user table matching on email 
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->connection->prepare($query);

        // Sanitize the email
        $email = htmlspecialchars(strip_tags($email));
        // Bind the email to the ? in the query
        $stmt->bindParam(1, $email);
        // Execute the query
        $stmt->execute();

        // Check if one user matches the results
        if ($stmt->rowCount() == 1) {
            // Get the data from the db and assign the data to the class properties
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->password = $row['password'];

            // Use built in function to check if the provided password matches the hashed password
            if (password_verify($password, $this->password)) {
                // Code needed to be uncommented after performing UserTests.php
                // Check if session is already started before starting a session
                // if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                // }               
                $_SESSION['userId'] = $row['userId'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email']; 
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Log out the user
    public function logout() {
        // Check if session is already started before starting a session
        // if (session_status() == PHP_SESSION_NONE) {
            session_start();
        // }
        // Unset all of the session variables
        $_SESSION = array();

        // Check if session uses cookies and delete them
        if (ini_get("session.use_cookies")) {
            // Get the current settings for session cookie
            $params = session_get_cookie_params();
            // Remove the cookie
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();

        return true;
    }

    // Method to update the user profile
    public function updateProfile($userId, $name, $email, $password = null) {        
        // Sanitize the data
        $userId = htmlspecialchars(strip_tags($userId));
        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email));

    // Check if the password is being updated
    if (!empty($password)) {
        $password = password_hash(htmlspecialchars(strip_tags($password)), PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table_name . " SET name=:name, email=:email, password=:password WHERE userId=:userId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':password', $password);
    } else {
        $query = "UPDATE " . $this->table_name . " SET name=:name, email=:email WHERE userId=:userId";
        $stmt = $this->connection->prepare($query);
    }

        // Bind data to the variables
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":userId", $userId);

        // Execute the SQL
        if ($stmt->execute()) {
            return true;
        }
        // Print errors
        echo "Error: " . $stmt->errorInfo()[2] . "\n";
        return false;
    }
}
?>
