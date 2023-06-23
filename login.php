<?php
    //connect to the database
    $conn = mysqli_connect('eu-cdbr-west-01.cleardb.com', 'b859a77c620eb1', '0b19999d', 'heroku_d101e05f57fcecb');
    // check connection
    if(!$conn){
        echo "connectiom error: ". mysqli_connect_error();
    }

    if(isset($_GET['submit'])){
        session_start();
        $_SESSION['username'] = $_GET['username'];
        //get query results
        $name = mysqli_real_escape_string($conn, $_GET['username']);
        $sql = "SELECT* FROM members WHERE username='$name' ";
        $results = mysqli_query($conn, $sql);
        // fetch the results
        $details = mysqli_fetch_assoc($results);

        mysqli_free_result($results);
        if($details){
            //mysql_close($conn);
            //print_r($details);
            $password=$details['password'];
            $email = $details['email'];
            $group = $details['group'];
            $_SESSION['email'] = $email;
            $type = $details['type'];
            $_SESSION['type'] = $type;
            $_SESSION['group'] = $group;
            //check if password marches
            if($_GET['pswd']==$password){

                echo "<script>alert('Login was successfully');</script>";
                echo "<script> location.href='profile.php'; </script>";
            }
            else{
                echo "<script>alert('Login was unsuccessfully');</script>"; 
            }
        }
        else{
            echo "<script>alert('Invalid username!!');</script>";
        }
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700' rel='stylesheet' type='text/css'>
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
                <a href="researchGroups.php" class="nav-link">Research Groups</a>
            </li>
            <li class="nav-item">
                <a href="people.php" class="nav-link">People</a>
            </li>
            <li class="nav-item">
                <a href="search.php" class="nav-link">Research Publications</a>
            </li>
            <li class="nav-item">
                <a href="about.php" class="nav-link">About</a>
            </li>
            <li class="nav-item">
                <a href="contact.php" class="nav-link">Contact</a>
            </li>
        </ul>
    </nav>
</nav>
     
    <div class="login">    
    <form id="login" action = "login.php" method="GET">        
        </b>      
        <input type="text" name="username" id="name" placeholder="Username">    
        <br><br>      
        <input type="Password" name="pswd" id="pswd" placeholder="Password">    
        <br><br>    
        <input type="submit" name="submit" id="sign" value="Sign In">       
        <br><br>  
        <div>
            <a href="forgot.php">Forgot Password?</a>
        </div>     
    </form>     
</div>  

  <footer>
     


  </footer>

  <!-- jQuery (Bootstrap JS plugins depend on it) -->
  <script src="js/jquery-2.1.4.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="login.js"></script>
</body>
</html>
