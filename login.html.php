<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        body {
            font-family: Arial, sans-serif;
            background-image: url('./photos/background.png'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            display: flex;
            justify-content: flex-start; 
            align-items: flex-start; 
            height: 100vh;
            margin: 0;
            
        }
        .login-container {
            background-color: rgba(252, 245, 226, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 10px 10px 50px rgba(250, 169, 169, 0.1);
            height: 80%;
            width: 100%;
            max-width: 400px; 
            text-align: center;
            margin: 50px; 
            margin-left: 50px; 
           
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 20px;
            margin: 30px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; 
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 20px;
            margin: 20px 0;
            background-color:  #fcb40bb4;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #4aad4f;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if(isset($_SESSION["error"]))
        {
            echo "<div class='error-message'>{$_SESSION['error']}</div>";
            // Unset the error after displaying it once
            unset($_SESSION["error"]);
        }
        ?>



        <form action="login.php" method="POST">
            <br>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
