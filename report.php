<?php

//include('databaseQuery.php');
//$conn = new mysqli('eu-cdbr-west-01.cleardb.com', 'b859a77c620eb1', '0b19999d', 'heroku_d101e05f57fcecb');
//
//$sql = "SELECT	members.first_name, members.last_name, publications.publication_id,publications.title, publications.abstract, publications.year, publications.journal, publications.pages,
//	                publications.month, publications.publisher, publications.address, publications.isbn, publications.url, publications.doi, publications.volume,
//	                publications.issue, contributors.role FROM contributors INNER JOIN members ON contributors.username = members.username INNER JOIN publications
//	                ON contributors.publication_id = publications.publication_id";
//if ((isset($_GET['search-input'])) or (isset($_GET['type'])) or (isset($_GET['group'])) or (isset($_GET['year']))) {
//    //check if there's a search input
//    if (strlen($_GET['search-input']) > 0) {
//        $search_input = mysqli_real_escape_string($conn,$_GET['search-input']);
//        $sql .= " WHERE (members.first_name = '$search_input' OR members.last_name = '$search_input' OR publications.title LIKE '%$search_input%')";
//        #  print_r($sql);
//    } else {
//        $sql .= " WHERE (1)";
//
//    }
//    //check if a type has been selected
//    if (isset($_GET['type'])) {
//        $types = $_GET['type'];
//        $sql .= " AND (publications.type IN('" . implode("','", $types) . "'))";
//        #  print_r($sql);
//
//        //check if a group has been selected
//    }
//    if (isset($_GET['group'])) {
//        $types = $_GET['group'];
//        $sql .= " AND (members.group IN('" . implode("','", $types) . "'))";
//        # print_r($sql);
//
//        //check if a year has been selected
//    }
//    if (isset($_GET['year'])) {
//        $types = $_GET['year'];
//        $sql .= " AND (publications.year IN('" . implode("','", $types) . "'))";
//        # print_r($sql);
//    }
//}
//
//
//#print_r($sql);
//
////execute query and store result
//
//
//
//$result = mysqli_query($conn, $sql);
//$entries = mysqli_fetch_all($result, MYSQLI_ASSOC);
//
//
//
////free result from memory
//mysqli_free_result($result);
//
//
////close connection
//mysqli_close($conn);
//
//

session_start();
$entries = $_SESSION['entries'];

function array2csv(array &$array)
{
    if (count($array) == 0) {
        return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    fputcsv($df, array_keys(reset($array)));
    foreach ($array as $row) {
        fputcsv($df, $row);
    }
    fclose($df);
    return ob_get_clean();
}

function download_send_headers($filename) {
//    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2022 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}
download_send_headers("data_export_" . date("Y-m-d") . ".csv");
echo array2csv($entries);
die();
