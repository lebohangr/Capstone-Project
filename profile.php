<?php //include("header.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="./profile.css">
    <link rel="stylesheet" href="css/search.css">
</head>
<body>
<nav class="navigation-bar-parent">
    <a href="index.php"> <!--this should be changed to the homepage-->
        <img class="logo" src="images/logo.svg" alt="logo">
    </a>
    <nav class="navigation-bar-middle">
        <ul class="nav-items">
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
    <?php 
        session_start();
        $type = $_SESSION['type'];
    ?>
    <div class="container">
        <div class="top-task-bar">
           <a href="logout.php">Logout</a>
            <a href="manageResearch.php" >Manage Research</a> 
            <a href="submittedRequests.php" >Submitted Requests</a>
            <?php if($type== 'group_admin' ||$type== 'uni_admin' || $type== 'super_admin' ) : ?>
                <a href="approve.php" >Approve Papers</a>
                <a href="cairMembers.php">Cair Members</a>>
            <?php endif  ?>
        </div>
        <div class="row">
            <div class="col-md-4 mt-1">
                <div class="side-bar">
                    <img src="./image/profile.jpg" alt="">
                    <div class="mt-3">
                        <h2 style="color:white;"><?php //session_start();
                            echo htmlspecialchars($_SESSION['username']);
                          ?></h2>
                           <h2 style="color:white;"><?php
                            echo htmlspecialchars($_SESSION['email']);
                          ?></h2>
                        <a href="editUserDetails.php">Edit Profile</a>
                        <a href="">Help</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
