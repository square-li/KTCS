<!DOCTYPE HTML >
<html >
<head >
    <title > Welcome to mysite </title >
    <link rel="stylesheet" href="../style/style.css">
</head >
<body >
<div id="main">
<?php
//Create a user session or resume an existing one
session_start();
?>

<?php

if (isset($_POST['backBtn']) && isset($_SESSION['member_id'])) {
    // include database connection
    header("Location: user_home.php");
}

?>

<?php
if (isset($_SESSION['member_id'])) {
    // include database connection
    include_once '../config/connection.php';

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
    header("Location: ../index.php");
    die();
}

?>



Welcome  <?php echo $myrow['name']; ?>, <a href="../index.php?logout=1">Log Out</a><br/>
<!-- dynamic content will be here -->
<table border='0'>
    <col width="180">
    <col width="130">
    <tr>
        <td>Location</td><td>Number of Spaces</td>
    </tr>

    <?php
    if (isset($_SESSION['member_id'])) {
        // include database connection
        include_once '../config/connection.php';

        // SELECT query
        $query = "SELECT * FROM KTCS.parking_locations";

        // prepare query for execution
        $stmt = $con->prepare($query);

        // bind the parameters. This is the best way to prevent SQL injection hacks.

        // Execute the query
        $stmt->execute();

        // results
        $result = $stmt->get_result();

        // Row data
        while ($locations = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$locations['address']."</td>";
            echo "<td>".$locations['number_of_spaces']."</td>";
            echo "</tr>";
        }

    } else {
        //User is not logged in. Redirect the browser to the login index.php page and kill this page.
        header("Location: ../index.php");
        die();
    }

    ?>


</table>
<button type="button" onclick="javascript:location.href='../user_home.php'">Back</button>

</div>
</body>
</html>