<?php
 //   include("header.php");
     // Connect to the database
     include("connect_db.php");
     // Include the encryption file
     include("encryption.php");
     
     $firstName = $lastName = $userName = $userType = $email = 
     $phoneNumber = $researchGroup = $password = $confirmPassword = "";

     $errors = array('firstName'=>"",'lastName'=>"",'userName'=>"",'userType'=>"",'email'=>"",
     'phoneNumber'=>"",'researchGroup'=>"",'password'=>"",'confirmPassword'=>"");
     
     // Use the mysqli_real_escape_string method 
     // To safely retrieve the data entered by the user in the form
     if(isset($_POST['submitRegister'])){
        // Check there's a value for firstname in the form 
        // Otherwise return a text "First Name is required."
         if(empty($_POST['firstName'])){
             $errors['firstName'] = "First Name is required.";
         }
         else{
             $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
         }
         // Check there's a value for lastname in the form
         // Otherwise return a text "Last Name is required."
         if(empty($_POST['lastName'])){
            $errors['lastName'] = "Last Name is required.";
         }
         else{
            $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
         }
         // Check there's a value for userName in the form
         // Otherwise return a text "Username is required."
         if(empty($_POST['userName'])){
            $errors['userName'] = "Username is required.";
         }
         else{
            $userName = mysqli_real_escape_string($connection, $_POST['userName']);
         }
         // Check there's a value for  userType in the form
         // Otherwise return a text "User Type is required."
         if(empty($_POST['userType'])){
            $errors['userType'] = "User Type is required.";
         }
         else{
            $userType = mysqli_real_escape_string($connection, $_POST['userType']);
         }
         // Check there's a value for  email in the form
         // And also check if an email has the correct format
         // Otherwise return a text "Email is required. or Email format is incorrect "
         if(empty($_POST['email'])){
            $errors['email'] = "Email Address is required.";
         }
         else{
            if( filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) ){
                $email = mysqli_real_escape_string($connection, $_POST['email']);
            }
            if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) ){
                $errors['email'] = "Email format is incorrect";
            }
            
         }
         // Check there's a value for  password in the form
         // Otherwise return a text "Password is required."
         if(empty($_POST['password'])){
            $errors['password'] = "Password is required.";
         }
         else{
            $password = mysqli_real_escape_string($connection, $_POST['password']);
         }
         // Check there's a value for  phoneNumber in the form
         // Otherwise return a text "Phone Number is required."
         if(empty($_POST['phoneNumber'])){
            $errors['phoneNumber'] = "Phone Number is required.";
         }
         else{
            $phoneNumber = mysqli_real_escape_string($connection, $_POST['phoneNumber']);
         }
         // Check there's a value for confirmPassword in the form
         // And check if password is the same as the confirmPassword
         // Otherwise return a text "Your Password doesn't match."
         if(empty($_POST['confirmPassword'])){
            $errors['confirmPassword'] = "Your Password doesn't match.";
         }
         else{
             if( $_POST['confirmPassword'] === $_POST['password'] ){
                $confirmPassword = mysqli_real_escape_string($connection, $_POST['confirmPassword']);
             }
         }
         // Check there's a value for researchGroup in the form
         // Otherwise return a text "Research Group is required"
         if(empty($_POST['researchGroup'])){
            $errors['researchGroup'] = "Research Group is required.";
         }
         else{
            $researchGroup = mysqli_real_escape_string($connection, $_POST['researchGroup']);
         }
         
         // Check if there no errors in the array called errors
         if(!array_filter($errors)){
            //  Encrypt the user's data using the md5 encrptyion method
                // $firstName  = encryptData($firstName);
                // $lastName  = encryptData($lastName);
                // $userName  = encryptData($userType);
                // $email  = encryptData($email);
                // $phoneNumber  =encryptData($phoneNumber);
                // $password  = encryptData($password);

             // Insert the data entered by the user into the database
             $sql = "INSERT INTO members(username, email, password,first_name,last_name,phone,type,`group`)
             VALUES('$userName','$email','$password','$firstName','$lastName','$phoneNumber','$userType', '$researchGroup') ";
             
             // Check if the data is inserted into the database 
             // Otherwise return a Query Error
             if(mysqli_query($connection, $sql)){
                header('Location: cairMembers.php');
             }
             else{
                 echo "Query Error: ". mysqli_error($connection);
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
    <title>Add newUser</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
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
                    <a href="cairMembers.php" class="btn brand z-depth-0">Cair Members</a>
                </li>
           </ul>
        </nav>
    </div>
    <section class="container grey-text">
        <h4 class="center">Add a new User</h4>
        <form action="newUser.php" class="white" method="POST">
            <label>FirstName</label>
            <input type="text" name="firstName" placeholder="Enter your FirstName" value="<?php echo $firstName ?>">
            <div class="red-text">
                <?php echo $errors['firstName'] ?>
            </div>
            <label>LastName</label>
            <input type="text" name="lastName" placeholder="Enter your LastName" value="<?php echo $lastName ?>">
            <div class="red-text">
                <?php echo $errors['lastName'] ?>
            </div>
            <label>Username</label>
            <input type="text" name="userName" placeholder="Enter your UserName" value="<?php echo $userName ?>">
            <div class="red-text">
                <?php echo $errors['userName'] ?>
            </div>
            <label>Select User Type</label>
            <select name="userType" class="browser-default">
                <option value="" disabled selected>Choose your option</option>
                <option value="general_user">General User</option>
                <option value="group_admin">Group Admin</option>
                <option value="uni_admin">University Admin</option>
                <option value="super_admin">Super Admin</option>
            </select>
            <div class="red-text">
                <?php echo $errors['userType'] ?>
            </div>
            <label>Select Research Group</label>
            <select name="researchGroup" class="browser-default">
                <option value="" disabled selected>Choose your option</option>
                <option value="CAIR Deep Learning">CAIR Deep Learning</option>
                <option value="Swarm Intelligence Lab">Swarm Intelligence Lab</option>
                <option value="CAIR@SU">CAIR At SU</option>
                <option value="Adaptive and Cognitive Systems Lab">Adaptive and Cognitive Systems Lab</option>
                <option value="Knowledge Representation and Reasoning">Knowledge Representation and Reasoning</option>
                <option value="CAIR@UKZN">CAIR At UKZN</option>
                <option value="Speech Technologies">Speech Technologies</option>
                <option value="AI for Development & Innovation">AI for Development & Innovation</option>
                <option value="Ethics of AI">Ethics of AI</option>
                <option value="Statistics@CAIR-UP">Statistics AT CAIR-UP </option>
                <option value="AI and Cybersecurity">AI and Cybersecurity</option>
            </select>
            <div class="red-text">
                <?php echo $errors['researchGroup'] ?>
            </div>
            <label>Email Address</label>
            <input type="text" name="email" placeholder="Enter your Email Address" value="<?php echo $email; ?>">
            <div class="red-text">
                <?php echo $errors['email'] ?>
            </div>
            <label>Phone Number</label>
            <input type="text" name="phoneNumber" placeholder="Enter your Phone Number" value="<?php echo $phoneNumber?>">
            <div class="red-text">
                <?php echo $errors['phoneNumber'] ?>
            </div>
            <label>Password</label>
            <input type="text" name="password" placeholder="Enter your Password" value="<?php echo $password?>">
            <div class="red-text">
                <?php echo $errors['password'] ?>
            </div>
            <label>Confirm Password</label>
            <input type="text" name="confirmPassword" placeholder="Confirm your Password" value="<?php echo $confirmPassword?>" >
            <div class="red-text">
                <?php echo $errors['confirmPassword'] ?>
            </div>
            <div class="center">
                <input type="submit" name="submitRegister" value="Register" class="btn brand z-depth-0">
            </div>
        </form>
    </section>
</body>
</html>