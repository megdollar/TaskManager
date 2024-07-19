<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMinder Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;900&family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>TaskMinder</h1>
        <h2>Create Account</h2>
        <form id="registerForm">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Account</button>
        </form>
        <br>
        <p>Already have an account? <a href="index.php">Login here</a>.</p>
        <div id="result"></div>
    </div>

     <script src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#registerForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: '../php/account_handler.php',
                    type: 'POST',
                    data: {
                        action: 'createAccount',
                        name: $('#name').val(),
                        email: $('#email').val(),
                        password: $('#password').val()
                    },
                    success: function(response) {
                        $('#result').html(response);
                        if (response.includes("Account created successfully")) {
                            window.location.href = 'index.php';
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
