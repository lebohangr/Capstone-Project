<?php
//include("header.php");
     // Connect to the database
     include("connect_db.php");
    
    $title = $publication_type = $year = $authorFirstName = $authorLastName = $coAuthorFirstName = $coAuthorLastName  = $coAuthor = "";
    $errors = array('title'=>"", 'publication_type'=>"",'year'=>"",'authorFirstName'=>"", 'authorLastName'=>"", 'coAuthorFirstName'=>"",'coAuthorLastName'=>"");

    if(isset($_POST['submit'])){
        // Check there's a value for title in the form 
        // Otherwise return a text "A research title is required."
        if(empty($_POST['title'])){
            $errors['title'] = 'A research title is required.';
        }
        else{
            $title = $_POST['title'];
        }
        // Check there's a value for publication_type in the form 
        // Otherwise return a text "A publication type is required."
        if(empty($_POST['type'])){
            $errors['publication_type'] = 'A publication type is required.';
        }
        else{
            $publication_type = mysqli_real_escape_string($connection, $_POST['type']);
        }
        // Check there's a value for a year in the form 
        // Otherwise return a text "A research year is required."
        if(empty($_POST['year'])){
            $errors['year'] = 'A research year is required.';
        }
        else{
            $year = $_POST['year'];
        }
        // Check there's a value for a authorFirstName in the form 
        // And if the name is just letters, no special case characters
        // Otherwise return a text "FirstName of the author is required. or FirstName must be letters only."
        if(empty($_POST['authorFirstName'])){
            $errors['authorFirstName'] = 'FirstName of the author is required.';
        }
        else{
            $authorFirstName = $_POST['authorFirstName'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $authorFirstName)){
                $authorFirstName['authorFirstName'] = 'FirstName must be letters only.';
            }
        }
        // Check there's a value for a authorLastName in the form 
        // And if the name is just letters, no special case characters
        // Otherwise return a text "LastName of the author is required. or LastName of the author is required."
        if(empty($_POST['authorLastName'])){
            $errors['authorLastName'] = 'LastName of the author is required.';
        }
        else{
            $authorLastName = $_POST['authorLastName'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $authorLastName)){
                $authorLastName['authorLastName'] = 'LastName must be letters only.';
            }
        }
        // Check there's a value for a coAuthorFirstName in the form 
        // And if the name is just letters, no special case characters
        // Otherwise return a text "FirstName must be letters only."
        if(!empty($_POST['coAuthorFirstName'])){
            $coAuthorFirstName = $_POST['coAuthorFirstName'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $coAuthorFirstName)){
                $coAuthorFirstName['coAuthorFirstName'] = 'FirstName must be letters only.';
            }
        }
        
        // Check there's a value for a coAuthorLastName in the form 
        // And if the name is just letters, no special case characters
        // Otherwise return a text "LastName must be letters only."
        if(!empty($_POST['coAuthorLastName'])){
            $coAuthorLastName = $_POST['coAuthorLastName'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $coAuthorLastName)){
                $coAuthorLastName['coAuthorLastName'] = 'LastName must be letters only.';
            }
        }
        // Check if there no errors in the array called errors
        if(!array_filter($errors)){
            $title = mysqli_real_escape_string($connection, $_POST['title']);
            $publication_type = mysqli_real_escape_string($connection, $_POST['type']);
            $year = mysqli_real_escape_string($connection, $_POST['year']);
            $authorFirstName = mysqli_real_escape_string($connection, $_POST['authorFirstName']);
            $authorLastName = mysqli_real_escape_string($connection, $_POST['authorLastName']);
            $coAuthorFirstName = mysqli_real_escape_string($connection, $_POST['coAuthorFirstName']);
            $coAuthorLastName = mysqli_real_escape_string($connection, $_POST['coAuthorLastName']);

            // Insert  data about the research into the publications table in the database
            $sql = "INSERT INTO publications(title, year,type) VALUES('$title','$year','$publication_type')";
            // Retrieve the username of a member whose names match the names of the author
            $sql2 = "SELECT username FROM members WHERE first_name = '$authorFirstName' AND last_name = '$authorLastName' ";
            // Retrieve the key of the last inserted research in the database
            $sql3 = "SELECT LAST_INSERT_ID() FROM publications";
            $id = "";

            // Check if there's a coAuthor name and then retrieve the username of a member whose names match the names of the coauthor
            $sql_coAuthor = "";
            if(!empty($_POST['coAuthorLastName'])){
                $sql_coAuthor = " SELECT username FROM members WHERE first_name = '$coAuthorFirstName' AND last_name = '$coAuthorLastName' ";
            }
            
            if(mysqli_query($connection, $sql)){               
            }
            if(mysqli_query($connection, $sql3)){   
                $result_id =  mysqli_query($connection,$sql3);
                $new_publication = mysqli_fetch_assoc($result_id); 
                $id = $new_publication['LAST_INSERT_ID()'];
            }
            if( mysqli_query($connection,$sql2) ){
                // Get the details of the author
                // what type of user they are(role), their username, publication id of their research
                $result =  mysqli_query($connection,$sql2);
                $user = mysqli_fetch_assoc($result);

                $type = "author";
                $username = $user['username'];
                // Insert the username , role and publication id of the author into the contributors table of the database
                $sql4 = "INSERT INTO contributors(username,publication_id ,role) VALUES('$username',$id,'$type')";

                // Get the details of the coauthor
                // what type of user they are(role), their username, publication id of their research
                $result_cA =  mysqli_query($connection,$sql_coAuthor);
                $user_cA = mysqli_fetch_assoc($result_cA);
                $username_cA = $user_cA['username'];
                $type_cA = "co-author";

                // Check if the co author is a CAIR Member
                $member = false;
                if($username_cA){
                   $member = true;
                }

                // If co author is not a cair member , add them to the nonmebers_contributors table
                // Of the database otherwise add them to the contributors table
                if($member === false){
                    $sql_NonMeber = "INSERT INTO nonmember_contributors(publication_id,first_name, last_name ,role) VALUES('$id','$coAuthorFirstName','$coAuthorLastName','$type_cA') ";
                    if(mysqli_query($connection,$sql_NonMeber)){
                    }
                }
                else{
                    if(!empty($_POST['coAuthorLastName'])){
                        $sql_cA = "INSERT INTO contributors(username,publication_id ,role) VALUES('$username_cA',$id,'$type_cA')";
                        if(mysqli_query($connection,$sql_cA)){
                        }
                    }
                }
                // If sql statement executed correctly , redirect to the manageResearch page
                // Otherwise return a Query Error
                if( mysqli_query($connection,$sql4) ){
                    header('Location: manageResearch.php');
                }
                else{
                    echo "Query Error: ". mysqli_error($connection);
                }
            }
           
            else{
                echo "Query Error: ". mysqli_error($connection);
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
    <div class="container grey-text">
        <h4 class="center">Add a new Research</h4>
        <form  class="white" action="newResearch.php" method="POST">
            <label>Research Title</label>
            <input type="text" name="title" value="<?php echo $title?>">
            <div class="red-text">
                <?php echo $errors['title'] ?>
            </div>
           
            <label>Select Publication Type</label>
            <select name="type" class="browser-default">
                <option value="" disabled selected>Choose your option</option>
                <option value="Thesis">Thesis</option>
                <option value="Journal Paper">Journal Paper</option>
                <option value="Conference Paper">Conference Paper</option>
                <option value="Book Chapter">Book Chapter</option>
                <option value="Workshop Paper">Workshop Paper</option>
                <option value="Other">Other</option>
            </select>
            <div class="red-text">
                <?php echo $errors['publication_type'] ?>
            </div>
          
            <label>Year</label>
            <input type="text" name="year" value="<?php echo $year?>">
            <div class="red-text">
                <?php echo $errors['year'] ?>
            </div> 
            <label>Author FirstName</label>
            <input type="text" name="authorFirstName" value="<?php echo $authorFirstName?>">
            <div class="red-text">
                <?php echo $errors['authorFirstName'] ?>
            </div>
            <label>Author LastName</label>
            <input type="text" name="authorLastName" value="<?php echo $authorLastName?>">
            <div class="red-text">
                <?php echo $errors['authorLastName'] ?>
            </div>
            <label >Co-author FirstName</label>
            <input type="text" name="coAuthorFirstName" value="<?php echo $coAuthorFirstName?>">
            <div class="red-text">
                <?php echo $errors['coAuthorFirstName']?>
            </div>
            <label >Co-author LastName</label>
            <input type="text" name="coAuthorLastName" value="<?php echo $coAuthorLastName?>">
            <div class="red-text">
                <?php echo $errors['coAuthorLastName']?>
            </div>
            <div class="center">
                <input type="submit" name="submit" class="btn brand z-depth-0">
            </div>

        </form>
        
    </div>
    
</body>
</html>