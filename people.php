<?php 
      // Connect to the database
      include("connect_db.php");
      // Select all the research title, description and author in the database
      $sql = "SELECT * FROM members";
      // Make query and get result
      $result =  mysqli_query($connection,$sql);
      // Fetch research title, descrption and author as an array
      $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
      // Free result memory
      mysqli_free_result($result);
      // Close the connection 
      mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

</head>
<body class="grey lighten-4">
    <div class="container">
        <nav class="white z-depth-4">
            <ul id="nav-mobile" class="right hide-on-small-and-down">
                <li>
                    <a href="index.php" class="btn brand z-depth-0">Home</a>
                </li>
            </ul>
        </nav>
        <?php if(count($members)==0) : ?>
            <div class="container">
                <h5 class="center grey-text">There are no members!</h5>
            </div>
        <?php else: ?>
            <h4 class="center grey-text">People</h4>
            <div class="container">
                <div class="row">
                    <?php foreach($members as $members) { ?>
                        <div class="row s6 md3">
                            <div class="card z-depth-0">
                                <h6>
                                    <?php echo htmlspecialchars($members['first_name']) ." ". htmlspecialchars($members['last_name']) ; ?>
                                </h6>
                                <h6>
                                    <?php  echo htmlspecialchars($members['group']) ; ?>
                                </h6>
                                <h6>
                                    <?php  echo htmlspecialchars($members['type']) ; ?>
                                </h6>
                                <h6>
                                    <?php  echo htmlspecialchars($members['email']) ; ?>
                                </h6>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
</body>
</html>