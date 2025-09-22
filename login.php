<?php
session_start();

// Database connection
$dsn = 'pgsql:host=localhost;;port=5433;dbname=user_data';
$username = 'postgres';
$password = 'shili';

// Log errors in a file----
function log_error($error_message) {
    $log_file = './error_log.txt'; //path of log file
    $current_time = date('Y-m-d H:i:s');
    $formatted_message = "[{$current_time}] ERROR: {$error_message}\n";
    file_put_contents($log_file, $formatted_message, FILE_APPEND);
}

// try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = htmlspecialchars(strtolower(trim($_POST['email'])));
        $password = trim($_POST['password']);

        // Prepare and execute query
        $stmt = $pdo->prepare('SELECT 
        users.id AS user_id , 
        users.first_name,
        users.last_name,
        users.email,
        users.password,
        users.address,
        users.phone,
        roles.role_name 
    FROM 
        users JOIN roles ON users.role_id = roles.id WHERE users.email = :email ');
        
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        
        //password_verify() Hash the received password and do whatever we did in lab5 
        if (password_verify($password, $user['password'])) {  
            
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Login successful, start a session and store user data-------lab7
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role_name'];
            $_SESSION['user_firstname'] = $user['first_name'];
            $_SESSION['user_lastname'] = $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_address'] = $user['address'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['logged_in'] = true;

            echo $_SESSION['user_id'];

            // Set session cookie parameters for security
            if (ini_set('session.cookie_secure', 1)) {
                ini_set('session.cookie_httponly', 1);
                ini_set('session.use_strict_mode', 1);
            }


            // Generate and send 2FA code
            $two_factor_code = rand(100000, 999999);
            $_SESSION['2fa_code'] = $two_factor_code;
            
            $to = $user['email'];
            $subject = "Your 2FA Code";
            $message = "Your 2FA code is: " . $two_factor_code;
            $headers = "From: noreply@yourdomain.com";
    
            mail($to, $subject, $message, $headers);

            header('Location: 2FA.php');
            exit();
        } else {
            // Login failed
            log_error("Invalid password attempt for username: {$username}");
            
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.html.php");

        }
     }
// } catch (PDOException $e) {
//     echo "myError: " . $e->getMessage();
// }
?>
