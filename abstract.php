<?php
$conn = new mysqli('eu-cdbr-west-01.cleardb.com', 'b859a77c620eb1', '0b19999d', 'heroku_d101e05f57fcecb');
// Check if the download abstract download is pressed

if(isset($_GET['id'])){

    $id = $_GET['id'];
    $sql = "SELECT* FROM publications WHERE publication_id=$id";
    $result = mysqli_query($conn, $sql);
    $file_details = mysqli_fetch_assoc($result);
    //$file = mysqli_real_escape_string($conn, $file_details['publication_file']);
    //echo $file;
    $filepath = 'uploads/'.$file_details['abstract_file'];
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
} 
?>