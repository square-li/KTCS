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
    header("Location: ../index.php");
    die();
}

?>

<?php
    if (isset($_POST['list']))
    {
        $query = "select max(reservation_number) as reservation,vin from KTCS.reservations where member_id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_Param("s", $_SESSION['member_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        #echo var_dump($row);
        $_SESSION['reservation_number'] = $row['reservation'];
        $_SESSION['vin'] = $row['vin'];

        $query = "select Odometer from KTCS.cars where vin=?";
        $stmt = $con->prepare($query);
        $stmt->bind_Param("s", $_SESSION['vin']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row2 = $result->fetch_assoc();



        if(strcmp ($_POST['list'],'PickUp') ==0){
            $query = "INSERT INTO KTCS.car_rental_history 
                  (vin, pick_up_odometer, drop_off_odometer, status_on_return) 
                  VALUES (?,?,?,'Not returned.')";
            $stmt = $con->prepare($query);
            $stmt->bind_Param("sss", $_SESSION['vin'],$row2['Odometer'],$row2['Odometer']);
            $stmt->execute();
            $query = "SELECT max(id) AS result from car_rental_history where vin=?";
            $stmt = $con->prepare($query);
            $stmt->bind_Param("s", $_SESSION['vin']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $query = "INSERT INTO KTCS.car_rental_history_ktcs_members (car_rental_history, ktcs_members) VALUES (?,?)";
            $stmt = $con->prepare($query);
            $stmt->bind_Param("ss",$row['result'], $_SESSION['member_id']);
            $stmt->execute();
            echo "Pick Up successful.";

        }
        else{
            echo "Please input the Odometer number before your drop-off.";
            echo "<form name='DropOffOdometer' id ='DropOffOdometer' action='pickdrop.php' method='post'>
                    <input type='text' id='odometer' name='odometer'>
                    <input type='submit'>
                </form>";

        }
    }
?>

<?php

if (isset($_SESSION['member_id'])&&isset($_POST['odometer'])) {
    $query = "SELECT max(car_rental_history) as id FROM KTCS.car_rental_history_ktcs_members WHERE ktcs_members=?";
    $stmt = $con->prepare($query);
    $stmt->bind_Param("s", $_SESSION['member_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row=$result->fetch_row();

    $query = "UPDATE KTCS.car_rental_history SET drop_off_odometer=?,status_on_return='Returned' WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_Param("ss",$_POST['odometer'],$row['id']);
    $stmt->execute();

    $query = "UPDATE KTCS.cars SET NumberOfRentals=NumberOfRentals+1 WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_Param("ss",$_SESSION['vin']);
    $stmt->execute();

    $query ="SELECT vin From KTCS.car_rental_history WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_Param("s",$row['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    echo var_dump($row);
    $row=$result->fetch_row();
    echo $row['vin'];

    $query = "UPDATE KTCS.cars SET Odometer=? WHERE vin=?";
    $stmt = $con->prepare($query);
    $stmt->bind_Param("ss",$_POST['odometer'],$row['vin']);
    $stmt->execute();


    header("Location: feedback.php");
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



<br>
Welcome  <?php echo $myrow['name']; ?>, <a href="../index.php?logout=1">Log Out</a>
<!-- dynamic content will be here -->
<p>Please select PickUp or DropOff</p>
<form name="select" id="select" method="post" action="pickdrop.php">
    <input list="selectList" name="list">
        <datalist id="selectList" name="selectList">
        <option value="PickUp"></option>
        <option value="DropOff"></option>
        </datalist>
    <input id='submit' type="submit" value="Select">
</form>


<button  id='submit' type="button" onclick="javascript:location.href='../user_home.php'">Back</button>
    </div></body></html>