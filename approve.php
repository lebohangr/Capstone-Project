<?php
include("connect_db.php");

//include("header.php");
// Connect to the database
$connection = mysqli_connect('eu-cdbr-west-01.cleardb.com', 'b859a77c620eb1', '0b19999d', 'heroku_d101e05f57fcecb');
// Check if connection is established
if (!$connection) {
    echo 'Connection error' . mysqli_connect_error();
}
// Select all the research title, description and author in the database
session_start();
$username = $_SESSION['username'];
$type = $_SESSION['type'];
$group = $_SESSION['group'];
if ($type == "group_admin") { //return all research by members from groups within the university
    $sql = "SELECT * FROM contributors 
            JOIN members ON members.username = contributors.username 
            JOIN publications ON contributors.publication_id = publications.publication_id 
            JOIN approval_requests ON contributors.publication_id = approval_requests.publication_id
            WHERE members.`group` = '$group' AND contributors.role = 'author' AND approved IS NULL";
    // Make query and get result
    $result = mysqli_query($connection, $sql);
    // Fetch research title, descrption and author as an array
    $researches = mysqli_fetch_all($result, MYSQLI_ASSOC);

} else if ($type == 'uni_admin') { //return all research by members from groups within the university
    $sql = "SELECT * FROM contributors 
            JOIN members ON members.username = contributors.username 
            JOIN publications ON contributors.publication_id = publications.publication_id 
            JOIN approval_requests ON contributors.publication_id = approval_requests.publication_id
            WHERE contributors.role = 'author' AND approved IS NULL AND members.`group` IN (
							SELECT `group` FROM groups WHERE university = (
								SELECT university FROM groups WHERE  `group` = '$group')
						)";
    $result = mysqli_query($connection, $sql);

    $researches = [];
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $researches[] = $row;
    }

} else if ($type == 'super_admin'){
    $sql = "SELECT * FROM contributors 
            JOIN members ON members.username = contributors.username 
            JOIN publications ON contributors.publication_id = publications.publication_id 
            JOIN approval_requests ON contributors.publication_id = approval_requests.publication_id
            WHERE contributors.role = 'author' AND approved IS NULL";
    $result = mysqli_query($connection, $sql);

    $researches = [];
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $researches[] = $row;
    }
}else {
    echo "You are not authorized to view this  page";
    exit();
}

$approver = $_SESSION['username'];
$tempPublicationID = "";
$approveStatus="";
$statusMessage="";
if (isset($_POST['accept'])) {
    $tempPublicationID = mysqli_real_escape_string($connection,$_POST['id_approve_research']);
    $sql = "UPDATE approval_requests SET approved = 1, approver = '$approver' WHERE publication_id = '$tempPublicationID' ";

    if(mysqli_query($connection, $sql)){
       // echo "Approval request accepted";
        $approveStatus="success";
        $statusMessage="Success! Approval request accepted";
    }else{
        $approveStatus="failure";
        $statusMessage="Failed. Approval request not accepted";
    }
}


if (isset($_POST['reject'])) {
    $tempPublicationID = mysqli_real_escape_string($connection,$_POST['id_approve_research']);
    $sql = "UPDATE approval_requests SET approved = 0, approver = '$approver' WHERE publication_id = '$tempPublicationID' ";

    if(mysqli_query($connection, $sql)){
      //  echo "Approval request rejected";
        $approveStatus="success";
        $statusMessage="Success! Approval request rejected";
    }else {
        $approveStatus="failure";
        $statusMessage="Failed. Approval request rejection failed";
    }
}

if (isset($_POST['comment-button'])){
    $tempPublicationID = mysqli_real_escape_string($connection,$_POST['id_approve_research']);
    $comment = mysqli_real_escape_string($connection,$_POST['comment']);
    $sql = "UPDATE approval_requests SET comment = '$comment' WHERE publication_id = '$tempPublicationID' ";

    if(mysqli_query($connection, $sql)){
        echo "Comment added";
    }
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
    <link rel="stylesheet" href="css/approve.css">
</head>
<body class="grey lighten-4">

<div class="container">
    <nav class="white z-depth-4">
        <ul id="nav-mobile" class="right hide-on-small-and-down">
            <li>
                <a href="profile.php" class="btn brand z-depth-0">Profile</a>
            </li>
        </ul>
    </nav>
</div>

<?php if (count($researches) == 0) : ?>
    <div class="container">
        <h5 class="center grey-text">There are no approval requests for you to handle.</h5>
    </div>
<?php else: ?>
    <h4 class="center grey-text">
        <div class="container">
            <div class="row">
                <?php foreach ($researches as $research) { ?>
                    <div class="col s6 md3">
                        <div class="card z-depth-0">
                            <div class="card-content center" disabled="1">
                                <h6 style="font-weight:bold;">
                                    <?php echo htmlspecialchars($research['year']); ?>
                                </h6>
                                <h6>
                                    <?php echo htmlspecialchars($research['title']); ?>
                                </h6>
                                <h6>
                                    <?php echo htmlspecialchars($research['publisher']); ?>
                                </h6>
                                <h6>
                                    <?php echo htmlspecialchars($research['first_name'] . ' ' . $research['last_name']); ?>
                                </h6>
                            </div>
                            <div class="card-action" style="font-size:15px; display:inline-block;flex-direction: row;">
                                <form action="approve.php" method="POST">
                                    <input type="hidden" name="id_approve_research" value="<?php echo $research['publication_id'] ?>">
                                    <a class="brand-text btn brand z-depth-0"
                                       href="upload.php?id=<?php echo $research['publication_id'] ?>">Review</a>
                                    <input type="submit" name="accept" value="Accept" class="btn brand z-depth-0"
                                           onclick="return confirm('Are you sure you would like to ACCEPT this approval request ?')">
                                    <input type="submit" name="reject" value="Reject" class="btn brand z-depth-0"
                                           onclick="return confirm('Are you sure you would like to REJECT this approval request?')">


                                    <div class="comment-section">
                                        <label for="textarea">Comment</label>
                                        <textarea class="textarea" name="comment" id="textarea" oninput='this.style.height = "";this.style.height = this.scrollHeight + 3 + "px"'> <?php if(isset($_POST['comment'])) {echo $_POST['comment'];}else echo $research['comment'] ?></textarea>
                                        <input type="submit" name="comment-button" value="Comment" class="btn brand z-depth-0">
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="container" style="justify-items: right">
                <label>
                    <input name="showAccepted" type="checkbox" class="filled-in"/>
                    <span>Show Accepted</span>
                </label>

                <label>
                    <input name="showRejected" type="checkbox" class="filled-in"/>
                    <span>Show Rejected</span>
                </label>

            </div>
        </div>
    </h4>
<?php endif; ?>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="js/approve.js"></script>


<script>
    if ("<?php echo $approveStatus?>".length > 0 ){
        displayStatus("<?php echo $approveStatus?>","<?php echo $statusMessage ?>");
        $(function () {
            setTimeout(function() {
                window.location.replace("approve.php");
            }, 3000);
        });
    }

</script>
</html>