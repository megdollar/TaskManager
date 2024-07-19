<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management Login</title>
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
    <h1 class="display-5 fw-bold text-body-emphasis"><span class="blue">Task</span><span class="orange">Minder</span></h1>
        <form id="loginForm">
            <div class="form-group mt-3 mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="form-group mt-3 mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3 mb-3">Login</button>
        </form>
        <br>
        <p>Don't have an account? <a href="createAccount.php">Create one here</a>.</p>
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
            $('#loginForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: '../php/account_handler.php',
                    type: 'POST',
                    data: {
                        action: 'login',
                        email: $('#email').val(),
                        password: $('#password').val()
                    },
                    success: function(response) {
                        $('#result').html(response);
                        if (response.includes("Login successful")) {
                            window.location.href = 'dashboard.php';
                        }
                    }
                });
            });
        });

    </script>
</body>
</html>
