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

//get all approval requests
    $sql = "SELECT * FROM approval_requests 
            INNER JOIN members ON members.username = approval_requests.requester
            INNER JOIN publications ON approval_requests.publication_id = publications.publication_id 
            INNER JOIN `contributors` ON approval_requests.publication_id = `contributors`.publication_id
			WHERE requester = '$username' GROUP BY request_id";
    $result = mysqli_query($connection, $sql);

    $requests = [];
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $requests[] = $row;
    }

function checkIfExists($publication_id){
    $sql = "SELECT COUNT(1) FROM approval_requests WHERE (publication_id = '$publication_id' && approved=1) OR (publication_id = '$publication_id' && approved IS NULL)";
    $result = mysqli_query($connection, $sql);
        if($result==1){
            return true;
        }else return false;
}

$tempPublicationID = "";
$approveStatus="";
$statusMessage="";


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

<?php if (count($requests) == 0) : ?>
    <div class="container">
        <h5 class="center grey-text">You have not made any approval requests</h5>
    </div>
<?php else: ?>
    <h4 class="center grey-text">
        <div class="container">
            <div class="row">
                <?php foreach ($requests as $research) { ?>
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
                                    <input type="hidden" name="id_approve_research" value="<?php echo $research['publication_id'] ?>">
                                    <div class="container">
                                        <h6 class="teal-text"><?php if ($research['approved']=="0"){echo "Rejected";}elseif ($research['approved']==1) {echo "Accepted";}else echo "Pending Approval..."  ?></h6>
                                    </div>


                                    <div class="comment-section">
                                        <label for="textarea">Comment</label>
                                        <textarea readonly class="textarea" name="comment" id="textarea" oninput='this.style.height = "";this.style.height = this.scrollHeight + 3 + "px"'> <?php echo $research['comment'] ?></textarea>
                                    </div>
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

</html>