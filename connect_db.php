<?php
    // Connect to the database
    $connection = mysqli_connect('eu-cdbr-west-01.cleardb.com','b859a77c620eb1','0b19999d','heroku_d101e05f57fcecb');
    // Check if connection is established
    if(!$connection){
        echo 'Connection error' . mysqli_connect_error();
    }
?>