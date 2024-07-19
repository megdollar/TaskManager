<?php
// Include User class
require_once '../php/User.php';

// Function to test user account creation
function testCreateAccount() {
    $user = new User();
    $name = "Test User1";
    $email = "testuser1@test.com";
    $password = "password123";

    if ($user->createAccount($name, $email, $password)) {
        echo "testCreateAccount: SUCCESS\n";
    } else {
        echo "testCreateAccount: FAILED\n";
    }
}

// Function to test logging in with correct password
function testLoginCorrectCredentials() {
    $user = new User();
    $email = "testuser1@test.com";
    $password = "password123";

    // Start a session manually before log in is called to test if login() is checking if a session has already been started
    session_start();

    if ($user->login($email, $password)) {
        echo "testLoginCorrectCredentials: SUCCESS\n";
    } else {
        echo "testLoginCorrectCredentials: FAILED\n";
    }
}

// Function to test logging in with incorrect password
function testLoginIncorrectCredentials() {
    $user = new User();
    $email = "testuser@test.com";
    $password = "ILoveToDoLists!";

    if ($user->login($email, $password)) {
        echo "testLoginIncorrectCredentials: SUCCESS\n";
    } else {
        echo "testLoginIncorrectCredentials: FAILED\n";
    }
}



// Function to test logging out
function testLogout() {
    $user = new User();

    // Start the session passing in the user details
    session_start();
    $_SESSION['userId'] = 1;
    $_SESSION['name'] = 'Test User1';
    $_SESSION['email'] = 'testuser1@test.com';

    if ($user->logout()) {
        // Session data should be cleared on log out
        if (empty($_SESSION)) {
            echo "testLogout: SUCCESS\n";
        } else {
            echo "testLogout: FAILED - Session data was not cleared\n";
        }
    } else {
        echo "testLogout: FAILED\n";
    }

    if ($user->logout()) {
        echo "testLogout: SUCCESS\n";
    } else {
        echo "testLogout: FAILED\n";
    }
}

// Function to test profile update
function testUpdateProfile() {
    $user = new User();
    $userId = 1;

    // Check if the userId exists in the User table
    $query = "SELECT * FROM user WHERE userId = :userId";
    $connection = $user->getConnection();
    $stmt = $connection->prepare($query);
    $stmt->bindParam(":userId", $userId);
    $stmt->execute();
    $validUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
   if (!$validUser) {
        echo "testUpdateProfile: FAILED - This user does not exist\n";
        return;
    }

    // Changes to be passed
    $name = "Megan Dollar";
    $email = "mdollar8@students.kennesaw.edu";
    $password = "SoftwareEngineering2024"; 

    if ($user->updateProfile($userId, $name, $email, $password)) {
        // Get the updated user information 
        $stmt = $connection->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        $updatedUserAccount = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the name and email provided matches the name and email in the DB after the update
        if ($updatedUserAccount['name'] === $name && $updatedUserAccount['email'] === $email) {
            echo "testUpdateProfile: SUCCESS\n";
        } else {
            echo "testUpdateProfile: FAILED - Name or email has not been updated\n";
        }

        // Attempt log in with the email and password, testing if the password was updated
        if ($user->login($email, $password)) {
            echo "testUpdateProfile: SUCCESSFUL LOG IN WITH NEW PASSWORD\n";
        } else {
            echo "testUpdateProfile: FAILED - Password not was updated\n";
        }
    } else {
        echo "testUpdateProfile: FAILED\n";
    }
}

// Run tests
testCreateAccount();
testLoginCorrectCredentials();
testLoginIncorrectCredentials();
testLogout();
testUpdateProfile();
?>
