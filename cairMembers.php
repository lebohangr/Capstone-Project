<?php
   // include("header.php");
    // Connect to the database
    include("connect_db.php");



session_start();

$username = $_SESSION['username'];
$type = $_SESSION['type'];
$group = "";

if($type=='group_admin'){
    $group = $_SESSION['group'];
    // return papers to which they are group admins to
    $sql = "SELECT * FROM members WHERE members.group = '$group'";
    // Make query and get result
    $result =  mysqli_query($connection,$sql);
    // Fetch research title, descrption and author as an array
    $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else if ($type == 'uni_admin') { //return all research by members from groups within the university
    $group = $_SESSION['group'];
    $sql = "SELECT * FROM members 
            WHERE members.`group` IN (
				SELECT `group` FROM groups 
				WHERE university = (
						SELECT university FROM groups WHERE  `group` = '$group'))";
    $result =  mysqli_query($connection,$sql);
    // Fetch research title, descrption and author as an array
    $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else if($type=='super_admin'){
    // return all papers for super admins
    $sql = "SELECT *  FROM members";
    // Make query and get result
    $result =  mysqli_query($connection,$sql);
    // Fetch research title, descrption and author as an array
    $members = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else{
    echo "No users to manage";
}

mysqli_free_result($result);

    
    if(isset($_POST['deleteMember'])){
        $userName =  mysqli_real_escape_string($connection,$_POST['id_delete_member']);
        $sql = "DELETE FROM members WHERE username = $userName";
        
        if(mysqli_query($connection,$sql)){
            header('Location: cairMember.php');
        }
        else{
            echo "Query Error: ". mysqli_error($connection);
        }
    }
    // Close connection
    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="./newResearch.css">
</head>
<body class="grey lighten-4">
    <div class="container">
        <nav class="white z-depth-0">
            <ul class="right hide-on-small-adn-down">
                <a href="profile.php" class="btn brand z-depth-0">Profile</a>
                <a href="newUser.php" class="btn brand z-depth-0">Add new Member</a>
            </ul>
        </nav>
    </div>
    <section class="container grey-text">
        <h4 class="center">Cair Members</h4>
    </section>
    <?php if(count($members)== 0): ?>
        <div class="container">
            <h5 class="center grey-text">There are no members!</h5>
        </div>
    <?php else: ?>
        <h4 class="conatiner grey-text">
            <div class="container">
                <div class="row">
                    <?php foreach($members as $members) { ?>
                        <div class="col s6 md3">
                            <div class="card z-depth-0">
                                <div class="card-content center">
                                    <h6>
                                        <?php echo htmlspecialchars($members['first_name'])." ".htmlspecialchars($members['last_name']); ?>
                                    </h6>
                                    <h6>
                                        <?php echo htmlspecialchars($members['type']);?>
                                    </h6>
                                    <h6>
                                        <?php echo htmlspecialchars($members['group']); ?>
                                    </h6>
                                    <form action="cairMembers.php" method="POST">
                                        <input type="hidden" name="id_delete_member" value="<?php echo $members['username']; ?> ">
                                        <input type="submit" name="deleteMember" value="Delete" class="btn brand z-depth-0">
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </h4>
    <?php endif; ?>   
</body>
</html>