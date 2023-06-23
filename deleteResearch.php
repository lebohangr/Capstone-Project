<?php 
    include "connect_db.php";

    $research ="";
    if(isset($_POST['delete'])){
      $id_delete_research = mysqli_real_escape_string($connection, $_POST['id_delete_research']);
      $sql = "DELETE FROM publications WHERE publication_id = $id_delete_research";

      if(mysqli_query($connection, $sql)){
            header('Location: manageResearch.php');
      }
      else{
        echo "Query Error: ". mysqli_error($connection);
      }  
      
      mysqli_close($connection);

    } 

?>