<?php
    // Connect to the database
    include("Connect_db.php");

    $research = "";
    if(isset($_GET['id'])){
        $publication_id = mysqli_real_escape_string($connection,$_GET['id']);
        $sql = "SELECT * FROM publications WHERE publication_id = $publication_id";
        $result = mysqli_query($connection, $sql);
        $research = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
        mysqli_close($connection);
    }

    $title = $year = $author = $coAuthor = "";
    $errors = array('title'=>"", 'year'=>"", 'author'=>"",'coAuthor'=>"");
    if(isset($_POST['submitModify'])){
        // Retrieve the research ID of the research to be modified
        $modify_id = mysqli_real_escape_string($connection, $_POST['modify_id']);
        // Check title
        if(empty($_POST['title'])){
            $errors['title'] = "A research title is required.";
        }
        else{
            $title = $_POST['title'];
        }
         // Check year
         if(empty($_POST['year'])){
            $errors['year'] = 'A research year is required.';
        }
        else{
            $year = $_POST['year'];
        }

        // check author's name
        if(empty($_POST['author'])){
            $errors['author'] = 'Name of the author is required.';
        }
        else{
            $author = $_POST['author'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $author)){
                $author['author'] = 'Name must be letters only.';
            }
        }
        // Check coauthors's name
        if($_POST['coAuthor']){
            $coAuthor = $_POST['coAuthor'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $coAuthor)){
                $coAuthor['coAuthor'] = 'Name must be letters only.';
            }
        }
        // Check if there are no errors
        if(!array_filter($errors)){
            $title = mysqli_real_escape_string($connection, $_POST['title']);
            $year = mysqli_real_escape_string($connection, $_POST['year']);
            $author = mysqli_real_escape_string($connection, $_POST['author']);
            $coAuthor = mysqli_real_escape_string($connection, $_POST['coAuthor']);

            // Update the values in the database
            // To do add the author name to the contributor table
            $sql =  "UPDATE publications SET title = '$title' , year ='$year', publisher='$author' WHERE publications.publication_id = $modify_id ";

            if(mysqli_query($connection, $sql)){
                header('Location: manageResearch.php');
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
    <title>Modify Research</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="./newResearch.css">
    <style>
        a{
            float: left;
            font-size: 20px;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="bg-light text-center">
            <a class="brand-text btn brand z-depth-0" href="./manageResearch.php" >Manage Research</a>
            <h4> Modify Research..</h4>
        </div>
        <?php if($research): ?>
            <section class="container grey-text">
                <form action="modify.php" class="white" method="POST">
                    <label>Research Title</label>
                    <input type="text" name="title" value="<?php echo $research['title'];?>">
                    <div class="red-text">
                        <?php echo $errors['title']; ?>
                    </div>
                    <label>Author FullName</label>
                    <input type="text" name="author" value="<?php echo $research['publisher']?>">
                    <div class="red-text">
                        <?php echo $errors['author']; ?>
                    </div>
                    <label>Year</label>
                    <input type="text" name="year" value="<?php echo $research['year']; ?>">
                    <div class="red-text">
                        <?php echo $errors['year']; ?>
                    </div>
                    <label>Co-author FullName(s)</label>
                    <input type="text" name="coAuthor" value=<?php //echo $research['coAuthor']?>>
                    <div class="red-text">
                        <?php //echo $errors['coAuthor']; ?>
                    </div>
                    <div class="center">
                        <input type="hidden" name="modify_id" value="<?php echo $research['publication_id']?>">
                        <input type="submit" name="submitModify" value="Modify" class="btn brand z-depth-0">
                    </div>

                </form>
            </section>
        <?php endif; ?>
    </div>
</body>
</html>