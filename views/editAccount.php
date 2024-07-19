<?php
// If user is not logged in redirect
session_start();
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
    <title>TaskMinder Edit Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;900&family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="../css/style.css" rel="stylesheet">

</head>
<body>
<div id="header"></div>

    <div class="container px-4 py-5 my-5 text-center">
        <h1>Edit Account</h1>
        <form id="editAccountForm">
            <div class="form-group mt-3 mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" value="<?php echo $_SESSION['name']; ?>" required>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="<?php echo $_SESSION['email']; ?>" required>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="password">New Password</label>
                <input type="password" class="form-control" id="password">
            </div>
            <button type="submit" class="btn btn-primary mt-3 mb-3">Update Account</button>
        </form>
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
            $('#editAccountForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: '../php/account_handler.php',
                    type: 'POST',
                    data: {
                        action: 'updateProfile',
                        name: $('#name').val(),
                        email: $('#email').val(),
                        password: $('#password').val()
                    },
                    success: function(response) {
                        $('#result').html(response);
                        if (response.includes("Account updated successfully")) {
                            window.location.href = 'dashboard.php';

                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
