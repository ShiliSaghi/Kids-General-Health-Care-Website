<?php
session_start();

// Function to verify reCAPTCHA v2
function verifyRecaptcha($recaptcha_response) {
    $secret_key = '6LdcRvwpAAAAABmEl6xSWtKg8r4N9dpSdQuHn9Oj';
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secret_key,
        'response' => $recaptcha_response
    );

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $response_keys = json_decode($response, true);

    return $response_keys['success'];
}


if($_SERVER['REQUEST_METHOD']== "POST"){

    // Check honeypot field
    if(!empty($_REQUEST['name'])){
        die("Spam detected!");
    }

    // Check CSRF token
    if($_SESSION['token'] != $_REQUEST['token'])
    {
        die("Invalid CSRF!");
    }

    // Verify reCAPTCHA
    if (empty($_POST['g-recaptcha-response']) || !verifyRecaptcha($_POST['g-recaptcha-response'])) {
        die("Invalid reCAPTCHA.");
    }
    else{
        print_r($_POST['email']);
        //print_r($_FILES['filename'] );

        //prevent SQL injection_input sanitization-----lab6
        //Remove whitespaces and slashes and encode html special characters-----lab6
        $patient_fname= trim(stripslashes(htmlspecialchars($_POST['firstname'])));
        $patient_lname= trim(stripslashes(htmlspecialchars($_POST['lastname'])));
        $patient_pass= $_POST['password'];
        $patient_nId= trim(stripslashes($_POST['nationalId']));
        $patient_gender= $_POST['gender'];
        $patient_bdate= !empty($_POST['bdate']) ? $_POST['bdate'] : null; //$_POST['bdate'];
        $patient_add= $_POST['address'];
        $patient_phone= $_POST['phone'];
        $patient_terms= $_POST['terms'];
        $patient_email= $_POST['email'];
        $role = $_POST['role'];

        echo $role; 


        // Remove all illegal characters from email
        $patient_email = filter_var($patient_email, FILTER_SANITIZE_EMAIL);
        // Validate e-mail
        if (!filter_var($patient_email, FILTER_VALIDATE_EMAIL)) {
            echo("<br>$patient_email is not a valid email address");
        } else {
            echo("<br>$patient_email is a valid email address");

        
            //Hash the received password by Bcrypt-----lab5
            $options = [
                'cost' => 12,
            ];
            $hashed_password = password_hash($patient_pass, PASSWORD_BCRYPT, $options);

            //prevent SQL injection_Bind the parameters & save patient data to the PostgreSQL database------lab6
            $dsn = 'pgsql:host=localhost;;port=5433;dbname=user_data';
            $username = 'postgres';
            $password = 'shili';
            try {
                // Create a PDO instance and set error for exception 
                $pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                echo "<br>Connected to the database successfully!";
        
                $stmt = $pdo->prepare("
                    INSERT INTO users (first_name, last_name, email, password, national_id, gender, birth_date, address, phone, terms, role_id)
                    VALUES (:first_name, :last_name, :email, :password, :national_id, :gender, :birth_date, :address, :phone, :terms, :role_id)
                ");
                $stmt->bindParam(':first_name', $patient_fname);
                $stmt->bindParam(':last_name', $patient_lname);
                $stmt->bindParam(':email', $patient_email);
                $stmt->bindParam(':password', $hashed_password); 
                $stmt->bindParam(':national_id', $patient_nId);
                $stmt->bindParam(':gender', $patient_gender);
                $stmt->bindParam(':birth_date', $patient_bdate);
                $stmt->bindParam(':address', $patient_add);
                $stmt->bindParam(':phone', $patient_phone);
                $stmt->bindParam(':terms', $patient_terms);
                $stmt->bindParam(':role_id', $role);
                //$stmt->bindParam(':role_id', 1);
                
                $stmt->execute();

                echo "<br>User data has been saved.";
            } catch (PDOException $e) {
                echo "<br>Error to execute database query: " . $e->getMessage();
            }
        }
    }
}

echo "<br>Form submitted successfully!";
 print_r($_REQUEST);

// Redirect to another page after successful form submission
$_SESSION['user_firstname'] = $patient_fname;
$_SESSION['user_lastname'] = $patient_lname;
$_SESSION['user_email'] = $patient_email;
$_SESSION['user_address'] = $patient_add;
$_SESSION['user_phone'] = $patient_phone;
$_SESSION['logged_in'] = true;

if ($role == 2) {
    $_SESSION['role'] = 'doctor';
    header('Location: search_users.php');
} else if ($role == 1){
    $_SESSION['role'] = 'patient';
    header('Location: appointment.php');
} else{ //admin
}

exit; // Make sure to exit after redirection
?>






