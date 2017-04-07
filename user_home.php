<!DOCTYPE HTML>
<html>
<head>
    <title>Welcome to mysite</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<div id="main">

<?php


  //Create a user session or resume an existing one
 session_start();
 ?>

<?php

if(isset($_POST['backBtn']) && isset($_SESSION['member_id'])){
    // include database connection
    header("Location: user_home.php");
}

?>

<?php

if(isset($_SESSION['member_id'])){
    // include database connection
    include_once 'config/connection.php';

    // SELECT query
    $query = "SELECT member_id,name, password, email FROM KTCS.ktcs_members WHERE member_id=?";

    // prepare query for execution
    $stmt = $con->prepare($query);

    // bind the parameters. This is the best way to prevent SQL injection hacks.
    $stmt->bind_Param("s", $_SESSION['member_id']);

    // Execute the query
    $stmt->execute();

    // results
    $result = $stmt->get_result();

    // Row data
    $myrow = $result->fetch_assoc();

} else {
    //User is not logged in. Redirect the browser to the login index.php page and kill this page.
    header("Location: index.php");
    die();
}

?>

Welcome  <?php echo $myrow['name']; ?>, <a href="index.php?logout=1">Log Out</a><br/>
<!-- dynamic content will be here -->
<ol>
    <li><a href="user_functions/profile.php">Update Profile</a></li>
    <li><a href="user_functions/locations.php">Find KTCS locations</a></li>
    <li><a href="user_functions/search_reserve.php">Find & Reserve cars</a></li>
    <li><a href="user_functions/pickdrop.php">Pick-up & Drop-off</a></li>
    <li><a href="user_functions/history.php">History</a></li>

</ol>
    </div>
</body>
</html>
