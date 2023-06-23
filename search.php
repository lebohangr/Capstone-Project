<!-- start session to store entries returned from sql query in databaseQuery.php so the array can be used in the report.php file-->
<?php session_start(); ?>
<?php

//includes databaseQuery.php file but doesn't display it. this is to avoid issues with headers not being able to be changed
ob_start();
include 'databaseQuery.php';
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://jqueryui.com/jquery-wp-content/themes/jqueryui.com/style.css">


</head>
<body>
<nav class="navigation-bar-parent">
    <a href="index.php"> <!--this should be changed to the homepage-->
        <img class="logo" src="images/logo.svg" alt="logo">
    </a>
    <nav class="navigation-bar-middle">
        <ul class="nav-items">
            <li class="nav-item">
                <a href="researchGroups.php" class="nav-link">Research Groups</a>
            </li>
            <li class="nav-item">
                <a href="people.php" class="nav-link">People</a>
            </li>
            <li class="nav-item">
                <a href="search.php" class="nav-link">Research Publications</a>
            </li>
            <li class="nav-item">
                <a href="about.php" class="nav-link">About</a>
            </li>
            <li class="nav-item">
                <a href="contact.php" class="nav-link">Contact</a>
            </li>
            <?php //session_start();
            if(isset($_SESSION['username'])) : ?>
                
                <li class="nav-item">
                    <a href="profile.php" class="nav-link"> <?php echo $_SESSION['username']?> </a>
                </li>
                <div class="nav-item" id = "circle">
                </div>
            <?php else:?>
                    <li class="nav-item">
                    <a href="login.php" class="nav-link">Login</a>
                </li>
            <?php endif  ?>
        </ul>
    </nav>

</nav>
<div class="breadcrum">

</div>


<form class="search-form" action="search.php" method="GET">
    <h1 class="page-heading">Research Publications</h1>
    <div class="search-container">
        <!--   <div class="left-container"> -->
        <div class="search-bar-container">

            <!-- check if search bar has input. if true, keep the text there when the form is submitted or search-button/filter-button is clicked-->
            <input type="text" placeholder="Search..." class="search-bar" name="search-input"
                   value="<?php echo (isset($_GET['search-input'])) ? htmlspecialchars($_GET['search-input']) : ''; ?>"
            >
            <!-- fa fa-.... is a class type that can allows icons on things like buttons -->
            <button type="submit"><i class="fa fa-search" id="search-button"></i></button>
        </div>
        <div class="selectors-container">
            <div class="checkbox-dropdown" id="year-dropdown">
                Select Year
                <ul class="checkbox-dropdown-list">
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2021" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2021", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2021</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2020" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2020", $_GET['year'])) echo "checked='checked'";
                            } ?> />2020</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2019" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2019", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2019</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2018" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2018", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2018</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2017" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2017", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2017</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2016" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2016", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2016</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2015" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2015", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2015</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2014" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2014", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2014</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2013" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2013", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2013</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2012" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2012", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2012</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2011" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2011", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2011</label>
                    </li>
                    <li>
                        <label>
                            <input class="yearItem" type="checkbox" value="2010" name="year[]" <?php if (isset($_GET['year'])) {
                                if (in_array("2010", $_GET['year'])) echo "checked='checked'";
                            } ?>/>2010</label>
                    </li>
                </ul>
            </div>

            <div class="checkbox-dropdown" id="group-dropdown">
                Select Group
                <ul class="checkbox-dropdown-list">
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="Adaptive and Cognitive Systems Lab"
                                   name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("Adaptive and Cognitive Systems Lab", $_GET['group'])) echo "checked='checked'";
                            } ?>/>Adaptive and Cognitive Systems Lab
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="Knowledge Representation and Reasoning"
                                   name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("Knowledge Representation and Reasoning", $_GET['group'])) echo "checked='checked'";
                            } ?>/>Knowledge Representation and Reasoning
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="CAIR@SU" name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("CAIR@SU", $_GET['group'])) echo "checked='checked'";
                            } ?>/>CAIR@SU
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="CAIR@NWU" name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("CAIR@NWU", $_GET['group'])) echo "checked='checked'";
                            } ?>/>CAIR@NWU
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="AI for Development &amp; Innovation"
                                   name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("AI for Development & Innovation", $_GET['group'])) echo "checked='checked'";
                            } ?>/>AI for Development &amp; Innovation
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="CAIR@UKZN" name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("CAIR@UKZN", $_GET['group'])) echo "checked='checked'";
                            } ?>/>CAIR@UKZN
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="Ethics of AI" name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("Ethics of AI", $_GET['group'])) echo "checked='checked'";
                            } ?>/>Ethics of AI
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="Statistics@CAIR-UP" name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("Statistics@CAIR-UP", $_GET['group'])) echo "checked='checked'";
                            } ?>/>Statistics@CAIR-UP
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="AI and Cybersecurity" name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("AI and Cybersecurity", $_GET['group'])) echo "checked='checked'";
                            } ?>/>AI and Cybersecurity
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="Swarm Intelligence Lab" name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("Swarm Intelligence Lab", $_GET['group'])) echo "checked='checked'";
                            } ?>/>Swarm Intelligence Lab</label>
                    </li>
                    <li>
                        <label>
                            <input class="groupItem" type="checkbox" value="Speech Technologies" name="group[]" <?php if (isset($_GET['group'])) {
                                if (in_array("Speech Technologies", $_GET['group'])) echo "checked='checked'";
                            } ?>/>Speech Technologies
                        </label>
                    </li>

                </ul>
            </div>

            <div class="checkbox-dropdown" id="type-dropdown">
                Select Document Type
                <ul class="checkbox-dropdown-list">
                    <li>
                        <label> <!-- the added php is to make sure the checkboxes stay checkde after submitting the form -->
                            <input class="typeItem" type="checkbox" value="Book Chapter" name="type[]" <?php if (isset($_GET['type'])) {
                                if (in_array("Book Chapter", $_GET['type'])) echo "checked='checked'";
                            } ?>/>Book Chapter
                        </label>
                    </li>
                    <li>
                        <label>
                            <input class="typeItem" type="checkbox" value="Conference Paper" name="type[]" <?php if (isset($_GET['type'])) {
                                if (in_array("Conference Paper", $_GET['type'])) echo "checked='checked'";
                            } ?>/>Conference Paper</label>
                    </li>
                    <li>
                        <label>
                            <input class="typeItem" type="checkbox" value="Journal Paper" name="type[]" <?php if (isset($_GET['type'])) {
                                if (in_array("Journal Paper", $_GET['type'])) echo "checked='checked'";
                            } ?>/>Journal Paper</label>
                    </li>
                    <li>
                        <label>
                            <input class="typeItem" type="checkbox" value="Other" name="type[]" <?php if (isset($_GET['type'])) {
                                if (in_array("Other", $_GET['type'])) echo "checked='checked'";
                            } ?>/>Other</label>
                    </li>
                    <li>
                        <label>
                            <input class="typeItem" type="checkbox" value="Thesis" name="type[]" <?php if (isset($_GET['type'])) {
                                if (in_array("Thesis", $_GET['type'])) echo "checked='checked'";
                            } ?>/>Thesis</label>
                    </li>
                    <li>
                        <label>
                            <input class="typeItem" type="checkbox" value="Workshop Paper" name="type[]" <?php if (isset($_GET['type'])) {
                                if (in_array("Workshop Paper", $_GET['type'])) echo "checked='checked'";
                            } ?>/>Workshop Paper</label>
                    </li>
                </ul>


            </div>
            <button type="submit" class="fa fa-filter" id="filter-button"> Filter</button>


            <div class="dropdown">
                <button type="button" class="fa fa-sort" id="sort-button"> Sort <?php if (isset($_GET['sort'])) echo ": ". $_GET['sort']?></button>
                <div class="dropdown-content">
                    <button class="sort fa fa-caret-up" type="submit" name="sort" value="first_name ASC, last_name ASC" >Author Asc</button>
                    <button class="sort fa fa-caret-down" type="submit" name="sort" value="first_name DESC, last_name DESC">Author Desc</button>
                    <button class="sort fa fa-caret-up" type="submit" name="sort" value="month ASC">Month Asc</button>
                    <button class="sort fa fa-caret-down" type="submit" name="sort" value="month DESC">Month Desc</button>
                    <button class="sort fa fa-caret-up" type="submit" name="sort" value="title ASC" >Title Asc</button>
                    <button class="sort fa fa-caret-down" type="submit" name="sort" value="title DESC">Title Desc</button>
                </div>
            </div>


            <button type="submit" class="fa fa-external-link" name="report" id="generate-report-button" onclick='location.href="report.php"'"> Report</button>
        </div>
<!-- the inline php code just makes sure the checkboxes stay checked after pressing the search/filter buttons-->
        <div class="checkAllCheckBox">
            <label><input type="checkbox" id="checkAllYears" name="checkAllYears" <?php if(isset($_GET['checkAllYears'])) echo "checked='checked'"; ?>/>
                Check all Years
            </label>
            <label><input type="checkbox" id="checkAllGroups" name="checkAllGroups" <?php if(isset($_GET['checkAllGroups'])) echo "checked='checked'"; ?>/>
                Check all Groups
            </label>
            <label><input type="checkbox" id="checkAllTypes" name="checkAllTypes" <?php if(isset($_GET['checkAllTypes'])) echo "checked='checked'"; ?>/>
                Check all Types
            </label>
        </div>
    </div>

    <div class="search-results">
        <!-- Testing -->
        <?php include 'databaseQuery.php'; ?>
        <!-- Testing -->

    </div>
</form>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="js/scipt.js"></script>


</body>
<footer>

</footer>
</html>
