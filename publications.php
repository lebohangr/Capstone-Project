<?php 
    // Connect to the database
    include("connect_db.php");
    // Select all the research title, description and author in the database
    $sql = "SELECT title,year,publisher, publication_id FROM publications ORDER BY year";
    // Make query and get result
    $result =  mysqli_query($connection,$sql);
    // Fetch research title, descrption and author as an array
    $researches = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
     <link rel="stylesheet" href="css/search.css">

</head>

<body class="grey lighten-4">
<nav class="navigation-bar-parent">
    <a href="index.php"> <!--this should be changed to the homepage-->
        <img class="logo" src="images/logo.svg" alt="logo">
    </a>
    <nav class="navigation-bar-middle">
        <ul class="nav-items">
            <li class="nav-item">
                <a href="#" class="nav-link">Research Groups</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">People</a>
            </li>
            <li class="nav-item">
                <a href="search.php" class="nav-link">Research Publications</a>
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
    <div class="container">
        <nav class="white z-depth-4">
            <ul id="nav-mobile" class="right hide-on-small-and-down">
                <li>
                    <a href="profile.php" class="btn brand z-depth-0">Profile</a>
                </li>
            </ul>
        </nav>
        <?php if(count($researches)==0) : ?>
            <div class="container">
                <h5 class="center grey-text">There are no researches!</h5>
            </div>
        <?php else: ?>
            <h4 class="center grey-text">Research Publications</h4>
            <div class="container">
                <div class="row">
                    <?php foreach($researches as $researches) { ?>
                        <div class="row s6 md3">
                            <div class="card z-depth-0">
                                <h6>
                                    <?php echo htmlspecialchars($researches['year']); ?>
                                </h6>
                                <h6>
                                    <?php  echo htmlspecialchars($researches['publisher'])." .  ".htmlspecialchars($researches['title']) ; ?>
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