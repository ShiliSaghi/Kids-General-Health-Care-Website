<?php
session_start();

// Database connection parameters
$dsn = 'pgsql:host=localhost;;port=5433;dbname=user_data';
$username = 'postgres';
$password = 'shili';

// Create a new PDO instance
try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    echo "<br>Connected to the database successfully!";

} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}



// Check if the form is submitted and required POST variables are set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_day']) && isset($_POST['appointment_time'])) {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        die('Patient not logged in.');
    }

    $user_id = $_SESSION['user_id'];
    $appointment_day = $_POST['appointment_day'];
    $appointment_time = $_POST['appointment_time'];
    echo $user_id;

    //Insert the appointment into the database
    $stmt = $pdo->prepare("INSERT INTO appointment (user_id, appointment_day, appointment_time) VALUES (:user_id, :appointment_day, :appointment_time)");

    // Bind parameters to the SQL statement
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':appointment_day', $appointment_day, PDO::PARAM_STR);
    $stmt->bindParam(':appointment_time', $appointment_time, PDO::PARAM_STR);

    //Save Session
    $_SESSION['appointment_day'] = $appointment_day;
    $_SESSION['appointment_time'] = $appointment_time;

    // Execute the statement
    try {
        $stmt->execute();
        echo "Appointment successfully booked!";
        header("Location: profileSettings_uploadFile_html.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid form submission.";
}
?>
