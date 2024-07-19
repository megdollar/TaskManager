<?php
// This file is used with the User class to utilize the methods needed
require_once 'User.php';

// Create an instance of User class
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        // If the action is to log in get the email and password 
        case 'login':
            $email = $_POST['email'];
            $password = $_POST['password'];
            // Call the login class passing in email and password provided
            if ($user->login($email, $password)) {
                echo "Login successful. Redirecting to dashboard.";
            } else {
                // Invalid email or password provided
                echo "Invalid emal or password, please try again.";
            }
            break;

        // If the user would like to create an account get the email, name, pw from the form submitted
        case 'createAccount':
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            // Call the create account method with the provided info
            if ($user->createAccount($name, $email, $password)) {
                echo "Account created successfully. Redirecting to login.";
            } else {
                echo "There was an issue with the account creation, please try again.";
            }
            break;
        
        // The user would like to update the profile
        case 'updateProfile':
           session_start();
           $userId = $_SESSION['userId'];
           $name = $_POST['name'];
           $email = $_POST['email'];
           $password = $_POST['password'];

           if ($user->updateProfile($userId, $name, $email, $password)) {
               $_SESSION['name'] = $name;
               $_SESSION['email'] = $email;
               echo "Account updated successfully. Redirecting to dashboard.";
           } else {
               echo "There was an issue updating the account, please try again.";
           }
           break;

        case 'logout':
            if ($user->logout()) {
                echo "Logout successful.";
            } else {
                echo "Error logging out.";
            }
            break;


        default:
            echo "Please log in or create an account.";
            break;
    }
}
?>
