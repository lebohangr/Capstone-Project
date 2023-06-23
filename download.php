<?php
$conn = new mysqli('eu-cdbr-west-01.cleardb.com', 'b859a77c620eb1', '0b19999d', 'heroku_d101e05f57fcecb');
// Check if the download button is pressed
session_start();
if(isset($_GET['id']) or isset($_POST['submitAbstract']) or isset($_POST['submitReview']) or isset($_POST['submitResearch'])){

    $id = $_GET['id'];

    $sql = "SELECT* FROM publications WHERE publication_id=$id";
    $result = mysqli_query($conn, $sql);
    $file_details = mysqli_fetch_assoc($result);


    //$file = mysqli_real_escape_string($conn, $file_details['publication_file']);
    //echo $file;

    if (isset($_GET['submitAbstract'])){
        $id = $_SESSION['currentID'];
        $filepath = 'uploads/'.$file_details['abstract_file'];
    }
    if (isset($_GET['submitResearch'])){
        $id = $_SESSION['currentID'];
        $filepath = 'uploads/'.$file_details['publication_file'];
    }
    if (isset($_GET['submitReview'])){
        $id = $_SESSION['currentID'];
        $filepath = 'uploads/'.$file_details['peer_review_file'];
    }else {
        $filepath = 'uploads/'.$file_details['publication_file'];
        }

    if(file_exists($filepath)){
        
        header('Content-Type: application/octet-stream');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:' .filesize($filepath));
        ob_clean(); 
        flush();
        readfile('uploads/'.$filepath);
        exit();
        
    }
    else {

        echo "File not found";
    }
} 
?>
