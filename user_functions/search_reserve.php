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
    header("Location: ../user_home.php");
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
    header("Location: index.php");
    die();
}

?>
<?php
if(isset($_POST['date']) && isset($_SESSION['member_id'])){
// include database connection
    if ($_POST['date'] == null){
        echo 'Date is not vaild.<br>';
    }
    else
        {

    include_once '../config/connection.php';
    $query = "select * from KTCS.cars where available_from<?";

    $stmt = $con->prepare($query);

    // bind the parameters. This is the best way to prevent SQL injection hacks.
    $stmt->bind_Param("s", $_POST['date']);
    // Execute the query
    $stmt->execute();

    // results
    $result = $stmt->get_result();

    // Row data
    echo "<h2>Search result for cars available after ".$_POST['date']."</h2><div style='width:80%'>";
    echo "<table style='width:100%'>";
    echo "<tr>
    <td>Vin #</td><td>Make</td><td>Model</td>
    <td>Year</td><td>Odometer</td><td>Fee per Day</td>
    <td>Location</td><td>Time of Rentals</td><td>Available From</td>
    </tr>";
    $a = array();
    $b = array();
    while ($cars = $result->fetch_assoc()){
        if ( $cars['Damaged'] == "Yes") continue;
        echo "<tr>";
        echo "<td>".$cars['vin']."</td>";
        echo "<td>".$cars['make']."</td>";
        echo "<td>".$cars['model']."</td>";
        echo "<td>".$cars['year']."</td>";
        echo "<td>".$cars['Odometer']."</td>";
        echo "<td>".$cars['daily_rental_fee']."</td>";
        echo "<td>".$cars['pick_drop_location']."</td>";
        echo "<td>".$cars['NumberOfRentals']."</td>";
        echo "<td>".$cars['available_from']."</td>";
        echo "</tr>";
        array_push($a,$cars['vin']);
        $temp = 'Vin#: '.$cars['vin'].', '.$cars['year'].' '.$cars['make'].' '.$cars['model'].' at '.$cars['pick_drop_location'];
        array_push($b,$temp);
    }

    echo "</div></table>";
    $i = 0;

    echo "<h3>Select your car:</h3>
    <form name='select' method='post' action = 'search_reserve.php'>
    <select name ='vin' id='vin'>";
    foreach ($a as $key => $value){
        echo "<option value='$value'>";
        echo $b[$key];
        echo "</option>";
    }
    echo "</select> 
    <input type='date' name='date_st' id='date_st' readonly value=".$_POST['date'].">
    <input type='date' name='date_ed' id='date_ed' value=".$_POST['date'].">
    <input type='submit' name='submit' value='Select'/>
    </form><br>";
}}
?>

<?php

if(isset($_POST['date_st']) && isset($_SESSION['member_id'])){

    $date1 = new DateTime($_POST['date_st']);
    $date2 = new DateTime($_POST['date_ed']);
    if ($date1>$date2){
        echo "Drop-off time must be later than pick-up time.";
    }
    else{

        include_once '../config/connection.php';
        $query = "select * from KTCS.cars where vin=?";

        $stmt = $con->prepare($query);

        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("s", $_POST['vin']);
        // Execute the query
        $stmt->execute();

        // results
        $result = $stmt->get_result();
        $cars = $result->fetch_assoc();
        echo "<h2>Confirmation</h2>";
        echo "<div style='width:80%'><table style='width:100%'>";
        echo "<tr>
            <td>Vin #</td><td>Make</td><td>Model</td>
            <td>Year</td><td>Odometer</td><td>Fee per Day</td>
            <td>Location</td><td>From</td><td>To</td>
        </tr>";
        echo "<tr>";
        echo "<td>".$cars['vin']."</td>";
        echo "<td>".$cars['make']."</td>";
        echo "<td>".$cars['model']."</td>";
        echo "<td>".$cars['year']."</td>";
        echo "<td>".$cars['Odometer']."</td>";
        echo "<td>".$cars['daily_rental_fee']."</td>";
        echo "<td>".$cars['pick_drop_location']."</td>";
        echo "<td>".$cars['NumberOfRentals']."</td>";
        echo "<td>".$cars['available_from']."</td>";
        echo "<td>".$_POST['date_st']."</td>";
        echo "<td>".$_POST['date_ed']."</td>";
        echo "</tr>";
        echo "</table><br>";
        $diff= (int)($date2->diff($date1)->format("%a")) + 1;
        echo "Total days will be ".$diff." days. <br>";
        echo "Total fees will be ".$diff*(int)$cars['daily_rental_fee']."$ before tax.<br>";
        echo "After tax: ".$diff*(float)$cars['daily_rental_fee']*1.13."$.<br>";
        echo "Your reservation has been made.<br> Thank you!<br>";

        $query = "INSERT INTO KTCS.reservations
          (`reservation_number`,
            `vin`,
            `date`,
            `access_code`,
            `length_of_reservation`,
            `member_id`
            )
        VALUES(NULL, ?, ?, '0001', ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_Param("ssss", $_POST['vin'],$_POST['date_st'],$diff,$_SESSION['member_id']);
        $stmt->execute();

        $query = "select max(reservation_number) as T, vin from KTCS.reservations where member_id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_Param("s", $_SESSION['member_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $_SESSION['reservation_number'] = $row['T'];
        $_SESSION['vin'] = $row['vin'];
        $_SESSION['date'] = $_POST['date_st'];

        echo "</div><br>";
    }

}

?>

Welcome  <?php echo $myrow['name']; ?>, <a href="../index.php?logout=1">Log Out</a><br/>
<!-- dynamic content will be here -->
<form name='search_by_date' id='search_by_date' action='search_reserve.php' method='post'>
    <table border='0'>

        <tr>
            <td>
                <input type='date' name='date' id='date' value='<?php echo date("Y-m-d");?>'/>

            </td>
            <td><input type="submit"  value="Search"></td>
            </tr>
        <tr>
            <td>
                <input type='submit' name='backBtn' id='backBtn' value='Back' />
            </td>
        </tr>
    </table>
</form>
</div>
</body>
</html>