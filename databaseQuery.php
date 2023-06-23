<?php
include('connect_db.php');


//sql queries for filtering and searching

/*
 * check if there has been a search input or dropdown items selected and filter based off the values obtained from
 * the global arrays. i.e $_GET['...']
 *
 * 'sizeof()' checks size of array
 *
 * isset() checks whether the element has a value assigned to it that has been selected by the user. if it has been then
 *  it will be found in that global variable
 *
 * 'WHERE 1' clause is always true. it's used for concatenating several sql statements to $sql when needed
 */



$sql = "SELECT	members.first_name, members.last_name, publications.publication_id,publications.title, publications.year, publications.journal, publications.pages,
	                publications.month, publications.publisher, publications.address, publications.isbn, publications.url, publications.doi, publications.volume, 
	                publications.issue, contributors.role, members.group FROM contributors INNER JOIN members ON contributors.username = members.username INNER JOIN publications
	                ON contributors.publication_id = publications.publication_id WHERE (contributors.role = 'author') AND public=1";
if ((isset($_GET['search-input'])) or (isset($_GET['type'])) or (isset($_GET['group'])) or (isset($_GET['year'])) or (isset($_GET['sort']))) {
    //check if there's a search input
    if (strlen($_GET['search-input']) > 0) {
        $search_input = mysqli_real_escape_string($connection,$_GET['search-input']);
        $sql .= " AND (members.first_name LIKE '%$search_input%' OR members.last_name LIKE '%$search_input%' OR publications.title LIKE '%$search_input%')";
      #  print_r($sql);
    }
    //check if a type has been selected
    if (isset($_GET['type'])) {
        $types = $_GET['type'];
        $sql .= " AND (publications.type IN('" . implode("','", $types) . "'))";
      #  print_r($sql);

        //check if a group has been selected
    }
    if (isset($_GET['group'])) {
        $groups = $_GET['group'];
        $sql .= " AND (members.group IN('" . implode("','", $groups) . "'))";
       # print_r($sql);

        //check if a year has been selected
    }
    if (isset($_GET['year'])) {
        $years = $_GET['year'];
        $sql .= " AND (publications.year IN('" . implode("','", $years) . "'))";
       # print_r($sql);
    }

    if (isset($_GET['sort'])){
        $sortParam = $_GET['sort'];
        $sql .= " ORDER BY $sortParam";
       # print_r($sql);
    }
}



#print_r($sql);

//execute query and store result



$result = mysqli_query($connection, $sql);
$entries = mysqli_fetch_all($result, MYSQLI_ASSOC);


if (isset($_GET['report'])){

    $_SESSION['entries'] = $entries;
    header('Location: report.php');
}


//free result from memory
mysqli_free_result($result);


//close connection
mysqli_close($connection);

//print bibtex entries to browser
//print_r($entries);


?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="css/search.css">
<div class="container">
    <?php if (($entries)) {
        foreach ($entries as $entry) { ?>
            <div><h2><?php echo htmlspecialchars($entry['year']) ?></h2></div>
            <div class="details">
                <span><?php echo htmlspecialchars($entry['first_name']) ?></span>
                <span><?php echo htmlspecialchars($entry['last_name']) ?>.</span>
                <span><?php echo htmlspecialchars($entry['title']) ?>.</span>
                <span><b><?php echo htmlspecialchars($entry['journal']) ?>.</b></span>
                <span><?php echo htmlspecialchars($entry['year']) ?>.</span>
                <span><?php echo htmlspecialchars($entry['volume']) ?>.</span>
                <span><?php echo htmlspecialchars($entry['issue']) ?>.</span>
                <span><?php echo htmlspecialchars($entry['url']) ?>.</span>
            </div>
            <div class="result-item-buttons">
                <button type="button" class = 'myDiv'><i class="fa fa-expand" onclick='location.href="abstract.php?id=<?php echo $entry['publication_id'] ?>"' title ="<?php 
                	// code for hovering abstract over a button
                	$id =  $entry['publication_id'];
                	$conn = new mysqli('eu-cdbr-west-01.cleardb.com', 'b859a77c620eb1', '0b19999d', 'heroku_d101e05f57fcecb');
                	$sql = "SELECT* FROM publications WHERE publication_id=$id";
                	$result = mysqli_query($conn, $sql);
                	$file_details = mysqli_fetch_assoc($result);
                    $filename = $file_details['abstract_file'];
                	
                    //check if file exists
                	if(!empty($filename)){
                        
                        $filepath = 'uploads/'.$filename;
                        $file = fopen($filepath, 'r');
                		$text = file_get_contents($filepath);
                		echo $text;
                	}
                    else{
                        echo "No abstract uploaded";
                    }
                ?>"> Abstract</i></button>
             
                <button type="button"><i class="fa fa-download" onclick='location.href="download.php?id=<?php echo $entry['publication_id'] ?>"'> Download</i></button>
            </div>
            <?php
        }
    } else {
        ?>
        <div><h2>NO RESULTS FOUND</h2></div>
    <?php } ?>
</div>
<style>
    span {
        padding-right: 10px;
    }
</style>


<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>-->
</html>

