<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If not logged in, redirect to the login page
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('./photos/16.png'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Align content to the left */
            padding-left: 50px; 
        }
        .main-container {
            background-color: rgba(245, 252, 226, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 5px 5px 5px rgba(250, 169, 169, 0.1);
            text-align: left; /* Align text to the left */
            max-width: 500px;
            width: 100%;
            height: 90%;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .form-group label {
            width: 180px; 
            text-align: left;
            /* margin-right: 6px; */
            font-size: 18px;
            /* display: block; */
        }
        .form-group input, .form-group select {
            width: 60%;
            padding: 8px; 
            box-sizing: border-box;
        }
        .custom-button {
            width: 100%; 
            height: 40px;
            background-color: #f9c724d2;
            color: black;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 5%;
        }
        .custom-button:hover {
            background-color: #f7d461d2;
        }
        .custom-file-input {
            color: rgb(30, 188, 9);
            border-radius: 10px;
            font-size: 15px;
            padding: 2px;
            box-sizing: border-box;
        }
        #image-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-top: 10%;
            margin-left: 0;
            max-width: 70%; 
            height: auto;
            border: 2px solid #ddd;
            border-radius: 10px;
        }
        table {
            width: 100%; 
            margin-top: 10%;
        }
        td {
            border: none;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h2>Profile Settings</h2>
        <div class="file-upload-wrapper">
            <img id="image-preview" src="./photos/12.jpg" alt="Image Preview">
            <input type="file" id="file" name="filename" class="custom-file-input" accept="image/*">
        </div>
        
        <form method="POST" action="mainPage.html" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="fullname">Full Name:</label>
                            <label for="fullname"> <?php echo($_SESSION['user_firstname']. ' ' .$_SESSION['user_lastname'])?> </label>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label>Booked Time: </label>
                            <label><?php echo($_SESSION['appointment_day'].', '. $_SESSION['appointment_time'])?></label>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label for="address">Address:</label>
                            <input id="address" name="address"  value= "<?php echo($_SESSION['user_address'])?>">
                        </div>

                        <div class="form-group" style="margin-top: 20px;"> 
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"  value= "<?php echo($_SESSION['user_email'])?>">
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label for="phone">Phone:</label>
                            <input type="text" id="phone" name="phone" pattern="[3-9]{1}[0-9]{9}" title="Phone number with 3-9 and remaining 9 digits with 0-9." value= "<?php echo($_SESSION['user_phone'])?>">
                        </div>

                        <button type="submit" class="custom-button">Confirm</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script>
        document.getElementById('file').addEventListener('change', function(event) {
            var imagePreview = document.getElementById('image-preview');
            var file = event.target.files[0];
            
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = './photos/12.jpg'; // Set to default if no file is selected
            }
        });
    </script>
</body>
</html>
