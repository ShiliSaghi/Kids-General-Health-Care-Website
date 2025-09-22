<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search User</title>

    <style>
        body {
            /* font-family: Arial, sans-serif; */
            background-image: url('./photos/18.png'); 
            background-size: cover; 
            /* background-position: center; 
            background-repeat: no-repeat; 
            display: flex;
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            margin: 0; */
        }
        .form-group {
            display: flex;
            align-items: center;
            /* margin-bottom: 10px; */
        }
        .form-group label {
            width: 100px; 
            text-align: left;
            margin-right: 6px;
            margin-left: 30px;
            font-size: 18px;
        }
        .form-group input {
           
            width: 40%;
            padding: 8px; 
            margin-left: 10px;
            box-sizing: border-box;
        }
        .form-group select {
           
           width: 40%;
           padding: 8px; 
           margin-left: 10px;
           box-sizing: border-box;
       }

        table {
            width: 90%; /* Full width */
            border-collapse: collapse;
            padding: 20px;
            margin-left: 30px;
        }
        th, td {
            border: 1px solid black; 
            padding: 15px; 
            text-align: left; 
        }
        th {
            background-color: #f2f2f2; 
        }
        .wide-cell {
            width: 300px;
        }

        .custom-button {
            width: 200px; 
            height: 40px; 
            background-color: #f9c724d2; 
            color: black; 
            border: none; 
            border-radius: 10px; 
            font-size: 16px; 
            cursor: pointer; 
            position: relative; 
            margin-left: 25px;
        }
        .custom-button:hover {
            background-color: #f7d461d2; 
        }
        .custom-button.enabled {
            cursor: pointer; 
            background-color: #f9c724d2;
        }
        .custom-button:disabled {
            background-color: #ccc; 
            cursor: not-allowed; 
        }
    </style>

</head>

<body>
    <h2 style="text-align: center;">Search User</h2><br>
    <form action="search_users.php" method="GET" class="form-group" >
        <label for="email">Enter Email:</label>
        <input type="text" id="email" name="email" placeholder="Enter email">
        <button type="submit" class="custom-button" >Search</button>
    </form>

    <?php
    // Check if form is submitted with an email value
    if (isset($_GET['email']) && !empty($_GET['email'])) {

        // Database connection details
        $dsn = 'pgsql:host=localhost;;port=5433;dbname=user_data';
        $username = 'postgres';
        $password = 'shili';

        try {
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            //echo "<br>Connected to the database successfully!";

            $stmt = null;
            // SQL search query in table users
            if(strtolower($_GET['email'])=="all") 
            {
                $stmt = $pdo->prepare("SELECT * FROM users");
            }
            else{

                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");

                // Remove all illegal characters from email and Bind parameters
                $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
                $stmt->bindParam(':email', $email);
            }

            $stmt->execute();    // Execute the query
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch result

            // Check if user exists
            if ($users) {
                ?>
                <h3 style="padding: 10px;">&nbsp;&nbsp;&nbsp;Users...</h3>
                <table border="0">
                    <tr>
                        <th>Id</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>National Id</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Birth Date</th>
                        <th>Phone</th>
                    </tr>

                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['national_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['gender']); ?></td>
                            <td><?php echo htmlspecialchars($user['address']); ?></td>
                            <td><?php echo htmlspecialchars($user['birth_date']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>


                        <?php
                        if(strtolower($_GET['email'])!="all") 
                        {
                            // Now search in the appointment table based on user_id
                            $stmt_appointments = $pdo->prepare("SELECT * FROM appointment WHERE user_id = :user_id");
                            $stmt_appointments->bindParam(':user_id', $user['id']);
                            $stmt_appointments->execute();
                            $appointments = $stmt_appointments->fetchAll(PDO::FETCH_ASSOC);
                            
                            if ($appointments): ?>
                                <tr><td colspan="9">
                                <br>
                                <br>
                                    <h3 style="padding: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Appointment History:</h3>
                                    <table border="1">
                                        <tr>
                                            <!-- <th>Appointment ID</th> -->
                                            <th>Appointment Date</th>
                                            <th>Appointment Time</th>
                                        </tr>
                                        <?php foreach ($appointments as $appointment): ?>
                                            <tr>
                                                <!-- <td><?php echo htmlspecialchars($appointment['id']); ?></td> -->
                                                <td><?php echo htmlspecialchars($appointment['appointment_day']); ?></td>
                                                <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </td></tr>
                            <?php else: ?>
                                <br><br><br><br><br>
                                <tr ><h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No appointments found for this user.</h4></tr>
                            <?php endif; ?>
                  <?php } ?>
                        
                    

                <?php
            } else {
                echo "No user found with email '$email'.";
            }

        } catch (PDOException $e) {
            echo "Error in reading data: " . $e->getMessage();
        }
    }
    ?>

</body>
</html>
