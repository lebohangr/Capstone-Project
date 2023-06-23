<?php
//include("header.php");

    // Connect to the database
    $connection = mysqli_connect('eu-cdbr-west-01.cleardb.com','b859a77c620eb1','0b19999d','heroku_d101e05f57fcecb');
    // Check if connection is established
    if(!$connection){
        echo 'Connection error' . mysqli_connect_error();
    }

    //check if record exists in approval_requests table
function checkIfExists($publication_id,$connection){
    $sql = "SELECT COUNT(1) FROM approval_requests WHERE (publication_id = '$publication_id' && approved=1) OR (publication_id = '$publication_id' && approved IS NULL)";
    $result = mysqli_query($connection, $sql);
    $row = $result->fetch_assoc();
    $bin = (int) $row['COUNT(1)'];
    if($bin>0){
        return true;
    }else {return false;}
}

    // Select all the research title, description and author in the database
    session_start();
    $username = $_SESSION['username'];
    $type = $_SESSION['type'];
    $group = $_SESSION['group'];

    if($type=='general_user'){
        $sql = "SELECT first_name, last_name,publications.public, publications.publication_id, contributors.role, publications. publisher, publications.title, publications.year, members.group
                FROM contributors
                JOIN members
                ON members.username=contributors.username
                JOIN publications
                ON contributors.publication_id = publications.publication_id 
                WHERE members.username = '$username' AND contributors.role = 'author'";
        // Make query and get result
        $result =  mysqli_query($connection,$sql);
        // Fetch research title, descrption and author as an array
        $researches = mysqli_fetch_all($result, MYSQLI_ASSOC);

    }
    else if($type=='group_admin'){
        // return papers to which they are group admins to
        $sql = "SELECT *
                FROM contributors
                JOIN members
                ON members.username=contributors.username
                JOIN publications
                ON contributors.publication_id = publications.publication_id
                WHERE members.group = '$group'";
        // Make query and get result
        $result =  mysqli_query($connection,$sql);
        // Fetch research title, descrption and author as an array
        $researches = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else if ($type == 'uni_admin') { //return all research by members from groups within the university
    $sql = "SELECT * FROM contributors 
            JOIN members ON members.username = contributors.username 
            JOIN publications ON contributors.publication_id = publications.publication_id 
            WHERE contributors.role = 'author'AND members.`group` IN (
							SELECT `group` FROM groups WHERE university = (
								SELECT university FROM groups WHERE  `group` = '$group')
						)";
    $result =  mysqli_query($connection,$sql);
        // Fetch research title, descrption and author as an array
        $researches = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else if($type=='super_admin'){
        // return all papers for super admins
        $sql = "SELECT *
                FROM contributors
                JOIN members
                ON members.username=contributors.username
                JOIN publications
                ON contributors.publication_id = publications.publication_id
                WHERE contributors.role='author'";
        // Make query and get result
        $result =  mysqli_query($connection,$sql);
        // Fetch research title, descrption and author as an array
        $researches = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        echo "No papers to manage";
    }
$publishStatus = "";
$statusMessage = "";
$requestStatus="";
       if (isset($_GET['requestApproval'])){
           $tempID = mysqli_real_escape_string($connection,$_GET['id']);
           $sqlRequest = "INSERT INTO approval_requests(publication_id, requester) VALUES('$tempID','$username') ";
           if (!checkIfExists($tempID,$connection)) {
               if (mysqli_query($connection, $sqlRequest)){
               $requestStatus = "success";
               $statusMessage = "Approval requested submitted successfully.";
               #header('Location: manageResearch.php');
           }else {
               $requestStatus = "failure";
               $statusMessage = "Failed. SQL query failed";
           }

       }else {
               $requestStatus = "failure";
               $statusMessage = "Failed. This research is already pending approval";
           }}

       if (isset($_GET['publishStatus'])){
           $publishStatus = $_GET['publishStatus'];
            if ($publishStatus == "success"){
                $statusMessage = "Research successfully published and can now be viewed publically";
            }else if ($publishStatus == "failure"){
                $statusMessage = "Failed. Research not published";
            }
       }

    $sqlAllRequests = "SELECT * FROM approval_requests";
    $result = mysqli_query($connection, $sqlAllRequests);

    $allRequests = [];
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $allRequests[] = $row;
}

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
    <title>Manage Research</title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script type="text/javascript" src="dialogueBox.js"></script>
    <script src="node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body class="grey lighten-4">
    
    <div class="container">
        <nav class="white z-depth-4">
            <ul id="nav-mobile" class="right hide-on-small-and-down">
                <li>
                    <a href="profile.php" class="btn brand z-depth-0">Profile</a>
                </li>
                <li>
                    <a href="newResearch.php" class="btn brand z-depth-0">Add a new Research</a>
                </li>
            </ul>
        </nav>
    </div>
    
        <?php if(count($researches)==0) : ?>
            <div class="container">
                <h5 class="center grey-text">There are no researches!</h5>
            </div>
        <?php else: ?>
            <h4 class="center grey-text">
                <div class="container">
                    <div class="row">
                        <?php foreach($researches as $researches) { ?>
                            <div class="col s6 md3">
                                <div class="card z-depth-0">
                                    <div class="card-content center">
                                        <h6 style="font-weight:bold;">
                                            <?php echo htmlspecialchars($researches['year']); ?>
                                        </h6>
                                        <h6>
                                            <?php echo htmlspecialchars($researches['title']); ?>
                                        </h6>
                                        <h6>
                                            <?php echo htmlspecialchars($researches['publisher']); ?>
                                        </h6>
                                    </div>
                                    <div class="card-action" style="font-size:15px; display:inline-block;flex-direction: row;">
                                        <form action="deleteResearch.php" method="POST"> 
                                            <input type="hidden" name="id_delete_research" value="<?php echo $researches['publication_id']?>">
                                            <input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
                                        </form>
                                        <a class="brand-text btn brand z-depth-0" href="upload.php?id=<?php echo $researches['publication_id'] ?>">Upload</a>
                                        <a class="brand-text btn brand z-depth-0" href="modify.php?id=<?php echo $researches['publication_id'] ?>">Modify</a>
                                        <a class="brand-text btn brand z-depth-0" href="publish.php?id=<?php echo $researches['publication_id'] ?>" <?php if ($researches['public']==1) {echo "disabled";} ?>>Publish</a>
                                        <div style="display: block">
                                            <a class="brand-text btn brand z-depth-0" href="manageResearch.php?id=<?php echo $researches['publication_id'] ?>&requestApproval=" <?php $approved = array_search($researches['publication_id'], array_column($allRequests, 'approved')); if ($approved == 1) {echo "disabled";} ?>>Request Approval</a>
                                        </div>


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

<script>
    if ("<?php echo $publishStatus?>".length > 0 ){
    displayStatus("<?php echo $publishStatus?>","<?php echo $statusMessage?>");
    $(function () {
        setTimeout(function() {
            window.location.replace("manageResearch.php");
        }, 3000);
    });
    }

    if ("<?php echo $requestStatus?>".length > 0 ){
        displayStatus("<?php echo $requestStatus?>","<?php echo $statusMessage?>");
        $(function () {
            setTimeout(function() {
                window.location.replace("manageResearch.php");
            }, 3000);
        });
    }
</script>
