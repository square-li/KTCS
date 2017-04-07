<!DOCTYPE HTML >
<html xmlns="http://www.w3.org/1999/html">
<head >
    <title > Welcome to mysite </title >
    <link rel="stylesheet" href="../style/style.css">
</head >
<body >
<div id="main">
<?php


 //Create a user session or resume an existing one
 session_start(); ?>


Welcome Administrator, <a href="../index.php?logout=1">Log Out</a><br/>
<!-- dynamic content will be here -->
<?php
    if (isset($_POST['member_id'])&&$_POST['member_id']!='9001'){
        include_once '../config/connection.php';
        $date = new DateTime('now');
        $date->modify('-1 month');
        $query = "SELECT * FROM KTCS.reservations NATURAL JOIN KTCS.cars where member_id=? and date>? ORDER BY member_id";
        $stmt = $con->prepare($query);

        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $date = $date->format('Y-m-d');
        $stmt->bind_Param("ss", $_POST['member_id'],$date);
        // Execute the query
        $stmt->execute();

        // results
        $result = $stmt->get_result();
        echo "<h3>User history for last 30 days</h3>";
            echo "<table style='width:100%'>";
            echo "<tr>
            <td>Date</td><td>Days</td><td>Make</td><td>Model</td>
            <td>Location</td><td>Fee</td>
            </tr>";
            $total = 0;
            while ($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>".$row['date']."</td>";
                echo "<td>".$row['length_of_reservation']."</td>";
                echo "<td>".$row['make']."</td>";
                echo "<td>".$row['model']."</td>";
                echo "<td>".$row['pick_drop_location']."</td>";
                $temp = (int)$row['length_of_reservation']*(int)$row['daily_rental_fee']*1.13;
                echo "<td>$".$temp."</td>";
                $total= $total + $temp;
                echo "</tr>";

            }
            echo "<tr><td>Total:</td><td>$$total</td></tr></table>";
    }
?>


 <form id="member_id" action="invoice.php"method="post">

     <table>

      <tr><td></td><td>Member ID</td><td>Name</td><td>Address</td><td>Phone Number</td><td>Email</td><td>Driving License</td></tr>
     <?php
     echo "<h3>User List</h3>";
     include_once '../config/connection.php';

     // SELECT query
     $query = "SELECT * FROM KTCS.ktcs_members WHERE member_id!=9001 ORDER BY member_id";

     // prepare query for execution
     $stmt = $con->prepare($query);

     // bind the parameters. This is the best way to prevent SQL injection hacks.

     // Execute the query
     $stmt->execute();

     // results
     $result = $stmt->get_result();

     // Row data
     while ($myrow = $result->fetch_assoc()){
         echo "<tr><td>";

         echo "<input type='radio' name='member_id' value=".$myrow['member_id']."></td>";
         echo "<td>".$myrow['member_id']."</td>";
         echo "<td>".$myrow['name']."</td>";
         echo "<td>".$myrow['address']."</td>";
         echo "<td>".$myrow['phone_number']."</td>";
         echo "<td>".$myrow['email']."</td>";
         echo "<td>".$myrow['driver_license_number']."</td>";
         echo "</tr>";
     }


        echo "<tr><td> <input type=\"submit\" value=\"Select\"> </td></tr>";
     ?>

     </table>
     <br>

 </form>




<button type="button" onclick="javascript:location.href='../admin_home.php'">Back</button>

</div></body></html>