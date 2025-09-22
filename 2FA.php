<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['2fa_code'])) {
    header("Location: login.php");
    exit();
}

//echo($_SESSION['user_email']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['2fa_code'])) {
    $entered_code = $_POST['2fa_code'];

    if ($entered_code == $_SESSION['2fa_code']) {
        unset($_SESSION['2fa_code']);
        //header("Location: appointment.php");
        //exit();
    } else {
        $error = "Invalid 2FA code.";
        //header("Location: appointment.php"); //just for test, it should delete later
    }

    echo $_SESSION['user_id'];
    echo $_SESSION['role'];

    // Redirect based on role
    if ($_SESSION['role'] == 'doctor') {
        header('Location: search_users.php');
    } else if ($_SESSION['role'] == 'patient'){
        header('Location: appointment.php');
    } else{ //admin
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2FA Verification</title>
    <style>
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
        .container {
            background-color: rgba(252, 245, 226, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 10px 10px 50px rgba(250, 169, 169, 0.1);
            height: 80%;
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin: 50px;
            
            
        }
        .container h2 {
            margin-bottom: 50px;
        }
        .container label,
        .container input[type="text"] {
            width: 90%;
            padding: 20px;
            margin: 50px 0;
            
        }
        .container input[type="submit"] {
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
        .container input[type="submit"]:hover {
            background-color: #4aad4f;
        }
    </style>
</head>
<body>
    <div class="container"> 
        <h2>Two-Factor Authentication</h2><br>
        <form method="post" action="">
            <label>Enter the 2FA code sent to your email:</label>
            <input type="text" name="2fa_code" required><br>
            <input type="submit" value="Verify">
        </form>
        <?php if (isset($error)) echo "<p>$error</p>";?>
    </div>
</body>
</html>
