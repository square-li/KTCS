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
if (isset($_SESSION['member_id'])) {
    // include database connection
    include_once '../config/connection.php';

    // SELECT query
    $query = "SELECT * FROM KTCS.reservations NATURAL JOIN KTCS.cars WHERE member_id=?";

    // prepare query for execution
    $stmt = $con->prepare($query);

    // bind the parameters. This is the best way to prevent SQL injection hacks.
    $stmt->bind_Param("s", $_SESSION['member_id']);

    // Execute the query
    $stmt->execute();

    // results
    $result = $stmt->get_result();

    echo "<h1>History</h1><div style='width:80%'>";
    echo "<table style='width:100%'>";
    echo "<tr>
    <td>Date</td><td>Length of Reservation</td><td>Make</td><td>Model</td>
    <td>Location</td><td>Total Paid</td>
    </tr>";
    while ($row = $result->fetch_assoc()){
        echo "<tr>";
        echo "<td>".$row['date']."</td>";
        echo "<td>".$row['length_of_reservation']."</td>";
        echo "<td>".$row['make']."</td>";
        echo "<td>".$row['model']."</td>";
        echo "<td>".$row['pick_drop_location']."</td>";
        $temp = (int)$row['length_of_reservation']*(int)$row['daily_rental_fee']*1.13;
        echo "<td>".$temp."</td>";
        echo "</tr>";

    }






} else {
    //User is not logged in. Redirect the browser to the login index.php page and kill this page.
    header("Location: ../index.php");
    die();
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


<button type="button" onclick="javascript:location.href='../user_home.php'">Back</button>
</div></body></html>