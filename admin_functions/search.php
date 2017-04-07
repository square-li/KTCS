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

    if (isset($_POST['date'])){
        include_once '../config/connection.php';

        $query = "select date,length_of_reservation,vin,make,model,pick_drop_location,member_id,name 
                  from  KTCS.ktcs_members NATURAL Join reservations NATURAL JOIN cars 
                  WHERE date<=? and Date_add(date,interval length_of_reservation DAY)  >=? ORDER BY date";
        $stmt = $con->prepare($query);

        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("ss", $_POST['date'],$_POST['date']);
        // Execute the query
        $stmt->execute();

        // results
        $result = $stmt->get_result();
        $num = $result->num_rows;
        echo  "<h3>Reservations on ".$_POST['date']."</h3>";
        if ($num == 0){
            echo "There's no reservation on that day.<br>";
        }else {
            echo "<table style='width:100%'>";
            echo "<tr>
            <td>Reservation starts from</td><td>Days</td><td>Vin#</td><td>Make</td><td>Model</td> <td>User Name</td> <td>User ID</td>
            <td>Location</td>
            </tr>";
            $total = 0;


            while ($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>".$row['date']."</td>";
                echo "<td>".$row['length_of_reservation']."</td>";
                echo "<td>".$row['vin']."</td>";
                echo "<td>".$row['make']."</td>";
                echo "<td>".$row['model']."</td>";
                echo "<td>".$row['pick_drop_location']."</td>";
                echo "<td>".$row['member_id']."</td>";
                echo "<td>".$row['name']."</td>";


                echo "</tr>";

            }
        }


    }
    ?>








    <br>
    Welcome  Administrator, <a href="../index.php?logout=1">Log Out</a>
    <!-- dynamic content will be here -->
    <form name='search_by_date' id='search_by_date' action='search.php' method='post'>
        <table style="border: 0px;" >

            <tr style="border: 0px;">
                <td style="border: 0px;">
                    <input type='date' name='date' id='date' value='<?php echo date("Y-m-d");?>'/>

                </td>
                <td style="border: 0px;"><input type="submit"  value="Search"></td>
            </tr>
        </table>
    </form>


    <button  id='submit' type="button" onclick="javascript:location.href='../admin_home.php'">Back</button>
</div></body></html>