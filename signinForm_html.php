
<?php
session_start();

if (empty($_SESSION['token'])) {
    $token = bin2hex(random_bytes(35));
    $_SESSION['token'] = $token;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'> 
    <title>PatientForm</title> <!--Title of page on browser's tab-->
    <meta name='viewport' content='width=device-width, initial-scale=1'> <!--Adaptation to the mobile screen -->
    <!-- <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script> -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        .spaced {
            margin-right: 20px; /* Adjust the value as needed */
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
            border-collapse: collapse;
            width: 100%;
        }
        td {
            /* border: none; */
            padding: 16px;
            width : 40%; 
        }
        
        .custom-button {
            width: 150px; 
            height: 50px; 
            background-color: #f9c724d2; 
            color: black; 
            border: none; 
            border-radius: 10px; 
            font-size: 16px; 
            cursor: pointer; 
            position: relative; 
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
        .tooltip {
            visibility: hidden;
            width: 200px;
            background-color: #f9f9f9;
            color: goldenrod;
            text-align: left;
            position: absolute;
            bottom: 110%; 
            left: 60%;
            margin-left: -120px; 
            opacity: 0;
            transition: opacity 0.3s;
            font-size:small;
        }
        .tooltip::after {
            content: "";
            position: absolute;
            top: 100%; 
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: black transparent transparent transparent;
        }
        .custom-button:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }
        body {
            font-family: Arial, sans-serif;
            background-image: url('./photos/7.jpg'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            display: flex;
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            margin: 0;
        }

        .container {
            
            background-color: rgba(245, 252, 226, 0.88);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 10px 10px 50px rgba(250, 169, 169, 0.1);
            text-align: center;
            max-width: 1000px;
            width: 100%;
        }
        
    </style>

</head>
<body >
    <div class="container">
    <!-- <p>paragraph 1</p> -->
    <h1 style="text-align: center;" > Sign Up </h1>

    <form method="POST" action="signinForm.php" id="signin-form"  enctype="multipart/form-data"> <!--enctype is used for uploading a file to the server-->

        <!-- <label for="file">File Upload</label>
        <input type="file" id=file name="filename" > -->

        <div style="display: none;">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>"> <!--SCRF-->
            <!-- Honypot Field-->
            <label for="name">Name</label> 
             <input type="text" id="name" name ="name" placeholder="name"> 
        </div>    
    
        <table>
            <tr>
                <td>
                    <div class="form-group">
                        <label for="firstname">First Name*</label>
                        <input type="text" id="firstname" name ="firstname" required placeholder="name"> 
                    </div>
                </td>
                
                <td>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" placeholder="surname">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                        title="Pleas enter a valid email address." placeholder="shs@gmail.com" >
                    </div>
                </td>
                
                <td>
                    <div class="form-group">
                    <label for="password">Password*</label>
                    <input type="password" id="password" name="password" required placeholder="1234yL87">
                </div>
            </td>
            </tr>

            <tr>
                <td>
                    <div class="form-group">
                        <label for="nationalId">NationalId*</label>
                        <input type="password" id="nationalId" name="nationalId" pattern="[A-Za-z]{5}" title="Five letter code" placeholder="EfcBn" required>
                    </div>
                </td>
                
                <td>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" name ="gender" >
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="O">Other</option>
                                </select>
                    </div>
                    
                </td>
            </tr>

            <tr>
                <td>
                    <div class="form-group">
                        <label for="bdate">BirthDate</label>
                        <input type="datetime-local"  min='1899-01-01' max='2024-06-18' id="bdate" name="bdate" placeholder="2001-02-17T00:00">
                    </div>
                </td>
                
                <td>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <!-- <textarea name="address" id="address"> </textarea> -->
                        <input type="address" id="address" name="address" placeholder="via martinin">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" pattern="[3-9]{1}[0-9]{9}" title="Phone number with 3-9 and remaining 9 digit with 0-9." 
                        placeholder="32499987898">
                    </div>
                </td>

                <td>
                    <div class="form-group">
                        <label for="role">Role*</label>
                        <select name="role" id="role" name ="role" required>
                                <option value=1>patient</option>
                                <option value=2>Doctor</option>
                                <option value=3>Admin</option>
                                </select>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="form-group">
                        <label for="terms">Confirm All*</label>
                        <input type="checkbox" id="terms" name="terms" style="width: 20px; margin-left:0;" required>
                    </div>
                </td>
            </tr>

        </table>
        

        <!-- onclick="saveForm()" -->
        <div style="text-align: center;" > 

            <!-- reCAPTCHA widget -->
            <div class="g-recaptcha" data-sitekey="6LdcRvwpAAAAAHerxSkbNZrT-1Qx5OKt_1PIOgBE" data-callback="enableSubmit" style="text-align: center; display: inline-block;"></div><br>
            <br>

            <button type="submit" id="submitButton" onclick="saveForm()" class="custom-button" disabled >
            Save / Submit <span class="tooltip">Please complete the mandatory fields(*) and reCAPTCHA to enable the submit button.</span>
            </button>
            &nbsp; <!-- an space between two button -->
            <button type="reset" class="custom-button">Reset</button>
            
            
        </div>
    </form>
    </div>

</body>
</html>

<script>
        function enableSubmit() {
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = false;
            submitButton.classList.add('enabled');
        }

</script>