<?php

    $error = "";
    $errors = array('error'=>"");

     if(isset($_POST['submit'])){
         require('PHPMailer/PHPMailerAutoload.php');
         $mail = new PHPMailer;
         $mail->HOST = 'stmp.gmail.com';
         $mail->Port = 587;
         $mail->SMTPAuth = true;
         $mail->SMTPSecure = 'tls';
         $mail->Username = ($_POST['email']);
         $mail->Password = '12345789KAxz';   

         $mail->SetFrom('noreply@cair.co.za');
         $mail->AddAddress($_POST['email']);
         $mail->AddReplyTo($_POST['email']);
         
         $mail->isHTML(true);
         $mail->Subject= 'Password Reset';
         $mail->Body= 'Your new password is 12345';     
    
        if(isset($_POST['email'])){
           if( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            
               if($mail->send()){
                    header('login.php');
               } 
               else{
                   $errors['error'] = "Reset Email is not sent.";
               }
           }
        }
     }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	 <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700' rel='stylesheet' type='text/css'>
	<title>Reset Password</title>
</head>
<body>
<nav class="navigation-bar-parent">
    <a href="index.php"> <!--this should be changed to the homepage-->
        <img class="logo" src="logo.svg" alt="logo">
    </a>
    <nav class="navigation-bar-middle">
        <ul class="nav-items">
            <li class="nav-item">
                <a href="#" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Research Groups</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">People</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Research Publications</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">About</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>
    </nav>
</nav>

<div class="login">
	<form action="forgot.php" method="POST" id ="login">
		</b>
		<p style="color: black">Reset your password</p>
		<input type='text' name= "username" id="name" placeholder="Enter your username">
		<br><br>
		<input type='text' name="email"  id="pswd" placeholder="Enter your email">
		<br><br>
		<input type='submit' name="submit" value="Submit"  id="sign">
        <div class="white-text">
            <?php echo $errors['error']?>
        </div>
		<br>
		<a href="login.php" style="font-size:14pt color:black ;">Login</a>
	</form>
</div>
</body>
</html>