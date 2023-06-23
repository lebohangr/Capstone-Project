<?php
      // Connect to the database
      include("connect_db.php");
      session_start();
      $research ="";
      $id ="";
      if(isset($_GET['id']) ){
        $publication_id = mysqli_real_escape_string($connection, $_GET['id']);
        $_SESSION['currentID'] = $publication_id;
        $sql = "SELECT * FROM publications WHERE publication_id = $publication_id";
        $result = mysqli_query($connection, $sql);
        $research =  mysqli_fetch_assoc($result);
    
        mysqli_free_result($result);
        mysqli_close($connection);

      } 
    //   $title = $year = $author = $coAuthor = "";
    //   $errors = array('title'=>"", 'year'=>"", 'author'=>"",'coAuthor'=>"");
         $researchError = $reviewError = $abstractError = "";
         $errors = array('researchError'=>"",'reviewError'=>"",'abstractError'=>"");

          if(isset($_POST['submitResearch']) ){
              //$research_id = $_POST['research_id'];
              $research_name =  $_SESSION['currentID']."_research_".$_FILES['researchPaper']['name'];
              $research_size =  $_FILES['researchPaper']['size'];
              $temp_research_name =  $_FILES['researchPaper']['tmp_name'];
              $research_error =  $_FILES['researchPaper']['error'];
              
              $research_id = mysqli_real_escape_string($connection, $_POST['research_id']);
        
              if($research_error === 0){
                    $research_extension = pathinfo($research_name , PATHINFO_EXTENSION);
                    $research_ext_lower =  strtolower($research_extension);
    
                    if($research_ext_lower === "pdf"){
                        $new_research_name =  rand(1,100)."-".$research_name;
                        $research_upload_path = "uploads/".$research_name;
                        move_uploaded_file($temp_research_name, $research_upload_path);
                        
                        $sql = "UPDATE publications SET publication_file='$research_name' WHERE publications.publication_id = $research_id";  
                        
                        if(mysqli_query($connection, $sql)){
                            echo "Research Paper is successfully uploaded.";
                        }
                    }
                    else{
                        $errors['researchError'] = "Research Paper must be a PDF!";
                    }
                    
              }
              header("Location: "."upload.php?id=".$_SESSION['currentID']);
          }
          if(isset($_POST['submitReview']) ){
            
              $review_name =  $_SESSION['currentID']."_review_".$_FILES['review']['name'];
              $review_size =  $_FILES['review']['size'];
              $temp_review_name =  $_FILES['review']['tmp_name'];
              $review_error =  $_FILES['review']['error'];

              $review_id = mysqli_real_escape_string($connection, $_POST['review_id']);
              if($review_error === 0){
                    $review_extension = pathinfo($review_name , PATHINFO_EXTENSION);
                    $review_ext_lower =  strtolower($review_extension);

                    if($review_ext_lower === "pdf"){
                        $review_upload_path = "uploads/".$review_name;
                        move_uploaded_file($temp_review_name, $review_upload_path);
                        
                        $sql = "UPDATE publications SET peer_review_file ='$review_name' WHERE publications.publication_id = $review_id";  
            
                        if(mysqli_query($connection, $sql)){
                                echo "Peer Review is Successfully Uploaded";
                        } 
                    }
                    else{
                        echo "Peer Review must be a PDF!";
                    }
              }
              header("Location: "."upload.php?id=".$_SESSION['currentID']);
          }
          if(isset($_POST['submitAbstract'])){
            
            $abstract_name =  $_SESSION['currentID']."_abstract_".$_FILES['abstract']['name'];
            $abstract_size =  $_FILES['abstract']['size'];
            $temp_abstract_name =  $_FILES['abstract']['tmp_name'];
            $abstract_error =  $_FILES['abstract']['error'];

            $abstract_id = mysqli_real_escape_string($connection, $_POST['abstract_id']);
            if($abstract_error === 0){
                $abstract_extension = pathinfo($abstract_name , PATHINFO_EXTENSION);
                $abstract_ext_lower =  strtolower($abstract_extension);
                
                if($abstract_ext_lower === "txt"){
                    $abstract_upload_path = "uploads/".$abstract_name;
                    move_uploaded_file($temp_abstract_name, $abstract_upload_path);

                    $sql = "UPDATE publications  SET abstract_file = '$abstract_name' WHERE publications.publication_id = $abstract_id";
                      
                    if(mysqli_query($connection, $sql)){
                            echo "Abstract is Successfully Uploaded";
                    } 
                }
                else{
                    echo "Abstract file must be a text file!";
                }
            }
              header("Location: "."upload.php?id=".$_SESSION['currentID']);
          }

function split2($string, $needle, $nth) { //returns the string argument split into an array from the nth occurance of a character
    $max = strlen($string);
    $n = 0;
    for ($i=0; $i<$max; $i++) {
        if ($string[$i] == $needle) {
            $n++;
            if ($n >= $nth) {
                break;
            }
        }
    }
    $arr[] = substr($string, 0, $i);
    $arr[] = substr($string, $i+1, $max);

    return $arr;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Documents</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style>
        a{
            float: left;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="bg-light text-center">
            <a class="brand-text btn brand z-depth-0" href="./manageResearch.php" >Manage Research</a>
            <h4> Upload Documents..</h4>
        </div>

        <?php if($research): ?>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="w-70 p-3 container my-5 bg-light">
                    <div class="col-md-12 text-center">
                        <!-- Upload a research paper -->
                        <label >Upload Research Paper</label>
                        <input type="file" name="researchPaper" accept="application/pdf">
                        <a href="download.php?id=<?php echo $research['publication_id'] ?>&submitResearch="> <?php echo split2($research['publication_file'],'_',2)[1] ?> </a>
                        <input type="hidden" name="research_id" value="<?php echo $research['publication_id']?>">
                        <input type="submit" name="submitResearch" value="Upload" class="btn brand z-depth-0">
                    </div>
                </div> 
                <div class="red-text">
                    <?php echo $errors['researchError'] ?>
                </div>
                <!-- Upload for a peer review -->
                <div class="w-70 p-3 container my-5 bg-light">
                    <div class="col-md-12 text-center">
                        <label >Upload Peer Review</label>
                        <input type="file" name="review" accept="application/pdf">
                        <a href="download.php?id=<?php echo $research['publication_id'] ?>&submitReview="> <?php echo split2($research['peer_review_file'],'_',2)[1] ?> </a>
                        <input type="hidden" name="review_id" value="<?php echo $research['publication_id']?>">
                        <input type="submit" name="submitReview" value="Upload" class="btn brand z-depth-0">
                    </div>
                </div> 
                <!-- Upload for an abstract -->
                <div class="w-70 p-3 container my-5 bg-light">
                    <div class="col-md-12 text-center">
                        <label >Upload Abstract</label>
                        <input type="file" name="abstract" accept="text/plain">
                        <a href="download.php?id=<?php echo $research['publication_id'] ?>&submitAbstract="> <?php echo split2($research['abstract_file'],'_',2)[1] ?> </a>
                        <input type="hidden" name="abstract_id" value="<?php echo $research['publication_id']?>">
                        <input type="submit" name="submitAbstract" value="Upload" class="btn brand z-depth-0">
                    </div>
                </div> 
            </form>
        <?php endif; ?>

    </div>
    
</body>
</html>