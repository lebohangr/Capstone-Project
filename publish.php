<?php

include("connect_db.php");
$research = "";
$publication_id = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $publication_id = mysqli_real_escape_string($connection, $_GET['id']);
    $sqlresearch = "SELECT * FROM contributors INNER JOIN members ON contributors.username = members.username INNER JOIN publications
	                ON contributors.publication_id = publications.publication_id WHERE (contributors.role = 'author') AND publications.publication_id = $publication_id";
    $sqlCoauthors = "
        SELECT concat(members.first_name,' ', members.last_name) AS 'co-author'
        FROM members INNER JOIN contributors ON members.username = contributors.username 
        WHERE contributors.role = 'co-author' 
	    AND contributors.publication_id = '$publication_id'";

    $result = mysqli_query($connection, $sqlresearch);
    $research = mysqli_fetch_assoc($result);

    mysqli_free_result($result);

    $result = mysqli_query($connection, $sqlCoauthors);
    # print_r($coAuthors);

    //associative 2-d array of co-authors with the column heading
    $coAuthors = [];
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $coAuthors[] = $row;
    }

    //indexed array of co-authors with just the names
    $list = [];
    foreach ($coAuthors as $coAuthor) {
        array_push($list, $coAuthor['co-author']);
    }

    #print_r($list);


}

$title = $year = $author = "";
$title = $research['title'];
$year = $research['year'];
$author = $research['first_name'] . ' ' . $research['last_name'];
//comma seperated list of co-authors
$listofCoAuthors = implode(', ', $list);
//print_r($coAuthors);

//$publishStatus = '';
//when publish button is clicked
if (isset($_POST['publish'])) {
    $sqlPublish = "UPDATE publications SET public = 1 WHERE publication_id = '$publication_id'";
    if (mysqli_query($connection, $sqlPublish)) {
       // $publishStatus = "Research successfully published and can now be viewed publically";
        header("Location: manageResearch.php?id=$publication_id&publishStatus=success");
    }else {
       // $publishStatus = "Failed. Research not published";
        header("Location: manageResearch.php?id=$publication_id&publishStatus=failure");
    }


}
mysqli_close($connection);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Research</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="./newResearch.css">

</head>
<body class="grey lighten-4">
<div class="container">
    <nav class="white z-depth-0">
        <ul id="nav-mobile" class="right hide-on-small-and-down">
            <li>
                <a href="manageResearch.php" class="btn brand z-depth-0">Go To Manage Research</a>
            </li>
        </ul>
    </nav>
</div>
<section class="container grey-text">
    <h4 class="center">Publish</h4>
    <form class="white" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
        <label>Research Title</label>
        <h5><?php echo $title ?></h5>

        <label>Author FullName</label>
        <h5><?php echo $author ?></h5>

        <label>Year</label>
        <h5><?php echo $year ?></h5>

        <label>Co-author FullName(s)</label>
        <h5><?php echo $listofCoAuthors ?></h5>

        <div class="center">
            <input type="submit" name="publish" id="submit-button" class="btn brand z-depth-0" value="Publish"
                   onclick="return confirm('Are you sure you would like to publish?')" <?php if ($research['public']==1) {echo "disabled";} ?>>
            <div class="green-text">
                <?php if ($research['public']==1) echo "Already published" ?>
            </div>
        </div>




    </form>
</section>

</body>

</html>

