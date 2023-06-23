<?php 
     // Connect to the database
     include("connect_db.php");

     $member ="";
     session_start();
     $user = $_SESSION['username'];
        // getting user current details 
         $userName = mysqli_real_escape_string($connection, $user);
         $sql = "SELECT * FROM members WHERE username = '$userName' ";
         $result = mysqli_query($connection, $sql);
         $member =  mysqli_fetch_assoc($result);

         $email = $member['email'];
         $phoneNumber = $member['phone'];
         $password = $member['password'];

     
     $errors = array('userName'=>"",'email'=>"",'phoneNumber'=>"",'password'=>"");
     //updating user details
     if(isset($_POST['modify'])){
        // check userName
        if(empty($_POST['userName'])){
            $errors['userName'] = "Username is required.";
         }
         else{
            $userName = $_POST['userName'];
         }
         // check email
         if(empty($_POST['email'])){
            $errors['email'] = "Email Address is required.";
         }
         else{
            $email = $_POST['email'];
         }
          // check password
          if(empty($_POST['password'])){
            $errors['password'] = "Password is required.";
         }
         else{
            $password = $_POST['password'];
         }
         // check phoneNumber
         if(empty($_POST['phoneNumber'])){
            $errors['phoneNumber'] = "Phone Number is required.";
         }
         if(!array_filter($errors)){
              $userName = mysqli_real_escape_string($connection, $_POST['userName']);
              
              $email = mysqli_real_escape_string($connection, $_POST['email']);
              
              $phoneNumber = mysqli_real_escape_string($connection, $_POST['phoneNumber']);
              $password = mysqli_real_escape_string($connection, $_POST['password']);
             $sql =  "UPDATE members SET username = '$userName' , email ='$email', phone='$phoneNumber', password = '$password' WHERE username = '$user' ";
             echo $sql;
             if(mysqli_query($connection, $sql)){

                $_SESSION['username'] = $userName;
                $_SESSION['email'] = $email;
                header('Location: profile.php');
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
    <title>Edit Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style>
        form {
             max-width: 460px;
             margin: 20px auto;
             padding: 20px;
        }
    </style>
</head>
<body class="grey lighten-4">
    <div class="container">
        <nav class="white z-depth-0">
        <ul id="nav-mobile" class="right hide-on-small-and-down">
                <li>
                    <a href="profile.php" class="btn brand z-depth-0">Profile</a>
                </li>
           </ul>
        </nav>
    </div>
    <section class="container grey-text">
        <h4 class="center">Modify Details</h4>
        <form action="editUserDetails.php" class="white" method="POST">
            <label>Username</label>
            <input type="text" name="userName" value="<?php echo $userName ?>">
            <div class="red-text">
                <?php echo $errors['userName'] ?>
            </div>
            <label>Email Address</label>
            <input type="text" name="email" value="<?php echo $email; ?>">
            <div class="red-text">
                <?php echo $errors['email'] ?>
            </div>
            <label>Phone Number</label>
            <input type="text" name="phoneNumber" value="<?php echo $phoneNumber?>">
            <div class="red-text">
                <?php echo $errors['phoneNumber'] ?>
            </div>
            <label>Password</label>
            <input type="text" name="password" value="<?php echo $password?>">
            <div class="red-text">
                <?php echo $errors['password'] ?>
            </div>

            <div class="center">
                <input type="submit" name="modify" value="Modify" class="btn brand z-depth-0">
            </div>

        </form>
    </section>
</body>
</html>