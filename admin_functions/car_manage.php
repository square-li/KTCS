<!DOCTYPE HTML >
<html xmlns="http://www.w3.org/1999/html">
<head >
    <title > Welcome to mysite </title >
    <link rel="stylesheet" href="../style/style.css">
</head >
<body >
<div id="main" style="width: 700px">
    <?php
    #echo var_dump($_POST);

    //Create a user session or resume an existing one
    session_start(); ?>


    Welcome Administrator, <a href="../index.php?logout=1">Log Out</a><br/>
    <!-- dynamic content will be here -->
    <?php

    if (isset($_POST['vin'])&&$_POST['vin']!=-1){
        include_once '../config/connection.php';
        $query = "SELECT DISTINCT vin,status_on_return,member_id,date,pick_drop_location,length_of_reservation FROM KTCS.car_rental_history  NATURAL JOIN KTCS.reservations NATURAL join cars WHERE vin=? ORDER BY date ";
        $stmt = $con->prepare($query);

        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("s", $_POST['vin']);
        // Execute the query
        $stmt->execute();

        // results
        $result = $stmt->get_result();

        echo "<table style='width:100%'>";
        echo "<h3>Car rental history</h3>";
        echo "<tr>
        <td>Date</td><td>Length of Reservation</td> <td>Status</td>
        <td>Location</td>
        </tr>";
        while ($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$row['date']."</td>";
            echo "<td>".$row['length_of_reservation']."</td>";
            echo "<td>".$row['status_on_return']."</td>";
            echo "<td>".$row['pick_drop_location']."</td>";

            echo "</tr>";

        }
       echo "</table>";
    }
    ?>


    <form id="member_id" action="car_manage.php"method="post">

        <table>

            <tr><td></td><td>Vin#</td><td>Make</td><td>Model</td><td>Year</td><td>Odometer</td><td>Location</td><td>Fee</td><td>Rental Times</td><td>Damaged</td><td>Available time</td></tr>
            <?php
            echo "<h3>All cars list</h3>";
            include_once '../config/connection.php';

            // SELECT query
            $query = "SELECT * FROM KTCS.cars ORDER BY cars.vin";

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

                echo "<input type='radio' name='vin' value=".$myrow['vin']."></td>";
                echo "<td>".$myrow['vin']."</td>";
                echo "<td>".$myrow['make']."</td>";
                echo "<td>".$myrow['model']."</td>";
                echo "<td>".$myrow['year']."</td>";
                echo "<td>".$myrow['Odometer']."</td>";
                echo "<td>".$myrow['pick_drop_location']."</td>";
                echo "<td>".$myrow['daily_rental_fee']."</td>";
                echo "<td>".$myrow['NumberOfRentals']."</td>";
                echo "<td>".$myrow['Damaged']."</td>";
                echo "<td>".$myrow['available_from']."</td>";
                echo "</tr>";
            }



            ?>

        </table>
        <input type="submit" value="Check history">
        <br>

    </form>

    <p>Please select an operation</p>
    <form name="select" id="select" method="post" action="car_manage.php">
        <input list="selectList" name="list">
        <datalist id="selectList" name="selectList">
            <option value="1">View cars travelled over 5000 km since last maintaince.</option>
            <option value="2">View cars with highest and lowest number of rentals.</option>
            <option value="3">View cars needs repair.</option>
            <option value="4">View cars by parking locations.</option>
            <option value="5">Add new cars.</option>

        </datalist>
        <input type="submit" value="Select">
    </form>


    <?php

        if (isset($_POST['list'])){
            if ($_POST['list']=='1'){
                include_once '../config/connection.php';
                echo "<table style=\"width: 100%\"";
                echo "<h3>Cars travelled over 5000 km since last maintaince</h3>";
                // SELECT query
                $query = "SELECT * FROM KTCS.cars NATURAL JOIN (SELECT vin,max(date),odometer_reading from KTCS.car_maintenance_history GROUP BY vin) AS T WHERE abs(t.odometer_reading-cars.Odometer) >5000 ";

                // prepare query for execution
                $stmt = $con->prepare($query);

                // Execute the query
                $stmt->execute();

                // results
                $result = $stmt->get_result();

                // Row data
                echo "<tr><td>Vin#</td><td>Make</td><td>Model</td><td>Year</td><td>Odometer</td><td>Last Odometer</td><td>Location</td><td>Fee</td><td>Rental Times</td><td>Damaged</td><td>Available time</td></tr>";
                while ($myrow = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>".$myrow['vin']."</td>";
                    echo "<td>".$myrow['make']."</td>";
                    echo "<td>".$myrow['model']."</td>";
                    echo "<td>".$myrow['year']."</td>";
                    echo "<td>".$myrow['Odometer']."</td>";
                    echo "<td>".$myrow['odometer_reading']."</td>";
                    echo "<td>".$myrow['pick_drop_location']."</td>";
                    echo "<td>".$myrow['daily_rental_fee']."</td>";
                    echo "<td>".$myrow['NumberOfRentals']."</td>";
                    echo "<td>".$myrow['Damaged']."</td>";
                    echo "<td>".$myrow['available_from']."</td>";
                    echo "</tr>";
                }



                echo "</table>";


            }elseif ($_POST['list']=='2'){
                include_once '../config/connection.php';


                // SELECT query
                $query = "Select * FROM  cars ORDER BY NumberOfRentals DESC LIMIT 1";

                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters. This is the best way to prevent SQL injection hacks.

                // Execute the query
                $stmt->execute();

                // results
                $result = $stmt->get_result();

                // Row data

                echo "<table><h3>Cars with most rental history</h3><tr><td>Vin#</td><td>Make</td><td>Model</td><td>Year</td><td>Odometer</td><td>Location</td><td>Fee</td><td>Rental Times</td><td>Damaged</td><td>Available time</td></tr>";
                while ($myrow = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>".$myrow['vin']."</td>";
                    echo "<td>".$myrow['make']."</td>";
                    echo "<td>".$myrow['model']."</td>";
                    echo "<td>".$myrow['year']."</td>";
                    echo "<td>".$myrow['Odometer']."</td>";
                    echo "<td>".$myrow['pick_drop_location']."</td>";
                    echo "<td>".$myrow['daily_rental_fee']."</td>";
                    echo "<td>".$myrow['NumberOfRentals']."</td>";
                    echo "<td>".$myrow['Damaged']."</td>";
                    echo "<td>".$myrow['available_from']."</td>";
                    echo "</tr>";
                }

                echo "</table>";
                // SELECT query
                $query = "Select * FROM  cars ORDER BY NumberOfRentals LIMIT 1";

                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters. This is the best way to prevent SQL injection hacks.

                // Execute the query
                $stmt->execute();

                // results
                $result = $stmt->get_result();

                // Row data
                echo "<table><h3>Cars with least rental history</h3><tr><td>Vin#</td><td>Make</td><td>Model</td><td>Year</td><td>Odometer</td><td>Location</td><td>Fee</td><td>Rental Times</td><td>Damaged</td><td>Available time</td></tr>";
                while ($myrow = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>".$myrow['vin']."</td>";
                    echo "<td>".$myrow['make']."</td>";
                    echo "<td>".$myrow['model']."</td>";
                    echo "<td>".$myrow['year']."</td>";
                    echo "<td>".$myrow['Odometer']."</td>";
                    echo "<td>".$myrow['pick_drop_location']."</td>";
                    echo "<td>".$myrow['daily_rental_fee']."</td>";
                    echo "<td>".$myrow['NumberOfRentals']."</td>";
                    echo "<td>".$myrow['Damaged']."</td>";
                    echo "<td>".$myrow['available_from']."</td>";
                    echo "</tr>";
                }

                echo "</table>";


            }elseif ($_POST['list']=='3'){
                include_once '../config/connection.php';


                // SELECT query
                $query = "Select * FROM  KTCS.cars WHERE Damaged='Yes'";

                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters. This is the best way to prevent SQL injection hacks.

                // Execute the query
                $stmt->execute();

                // results
                $result = $stmt->get_result();

                // Row data

                echo "<table><h3>Cars with most rental history</h3><tr><td>Vin#</td><td>Make</td><td>Model</td><td>Year</td><td>Odometer</td><td>Location</td><td>Fee</td><td>Rental Times</td><td>Damaged</td><td>Available time</td></tr>";
                while ($myrow = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>".$myrow['vin']."</td>";
                    echo "<td>".$myrow['make']."</td>";
                    echo "<td>".$myrow['model']."</td>";
                    echo "<td>".$myrow['year']."</td>";
                    echo "<td>".$myrow['Odometer']."</td>";
                    echo "<td>".$myrow['pick_drop_location']."</td>";
                    echo "<td>".$myrow['daily_rental_fee']."</td>";
                    echo "<td>".$myrow['NumberOfRentals']."</td>";
                    echo "<td>".$myrow['Damaged']."</td>";
                    echo "<td>".$myrow['available_from']."</td>";
                    echo "</tr>";
                }

                echo "</table>";

            }elseif ($_POST['list']=='5'){
                include_once '../config/connection.php';

                echo "<br><form name=\"lis\" id=\"lis\" method=\"post\" action=\"car_manage.php\"  style='max-width: 100%'><table style='table-layout: fixed'>";
                echo "<tr><td>Vin#</td><td>Make</td><td>Model</td><td>Year</td></tr>";
                echo "<tr>
                    <td><input id='vin' name='vin'type='text'></td>
                    <td><input id='make' name='make'type='text'></td> 
                    <td><input id='model' name='model'type='text'></td>
                    
                     <td><input id='year' name='year' type='text'></td>
                     </tr><tr><td>Odometer</td><td>Location</td><td>Parking Location</td><td>Daily Rental Fee</td></tr><tr>
                     <td><input id='odometer' name='odometer' type='text'></td>
                     <td><input id='location' name='location' type='text'></td>
                     <td><select id='address' name='address'><option value='1 First St.'>1 First St.</option><option value='2 Fourth St.'>2 Fourth St</option></select></td>
                     <td><input id='daily_rental_fee' name='daily_rental_fee' type='text'></td>
                    </tr>
                    <tr><td><input id='submit' type='submit'></td></tr>";
                 echo "</table></form>";

                }
            elseif ($_POST['list']=='4'){
                    echo "<br><form name=\"lis\" id=\"lis\" method=\"post\" action=\"car_manage.php\"  style='max-width: 100%'><table style='border:0px; table-layout: fixed'>";
                    echo "<tr><td>Please select parking location.</td></tr>";
                    echo "<td><select id='address' name='address'><option value='1 First St.'>1 First St.</option><option value='2 Fourth St.'>2 Fourth St</option></select></td>";
                    echo '<td><input id=\'submit\' type=\'submit\'></td>';
                    echo "</table>";
            }
        }
    if (isset($_POST['make'])){
        include_once '../config/connection.php';


        // SELECT query
        $query = "Select vin from KTCS.cars where vin=?";
        $stmt = $con->prepare($query);

        $stmt->bind_Param("s",$_POST['vin']);
        // Execute the query
        $stmt->execute();
        // results
        $result = $stmt->get_result();
        $num = $result->num_rows;
        $query = "INSERT INTO KTCS.cars (vin, make, model, year, Odometer, pick_drop_location, daily_rental_fee, parking__locations, NumberOfRentals, Damaged)
                                    VALUES  (?,?,?,?,?,?,?,?,0,'No')";

        // prepare query for execution
        $stmt = $con->prepare($query);

        $stmt->bind_Param("ssssssss",$_POST['vin'],$_POST['make'],$_POST['model'],$_POST['year'],$_POST['odometer'],$_POST['location'],$_POST['daily_rental_fee'],$_POST['address']);


        // bind the parameters. This is the best way to prevent SQL injection hacks.

        // Execute the query
        $stmt->execute();

        // results
        $result = $stmt->get_result();
    }
    elseif (isset($_POST['address'])){
        echo $_POST['address'];
        $query = "SELECT * FROM 
  (select * from parking_locations_reservations, reservations  where parking_locations=? and reservation_number=parking_locations_reservations.reservations) as T 
  , cars 
where cars.vin=t.vin";
        $stmt = $con->prepare($query);

        $stmt->bind_Param("s",$_POST['address']);
        // Execute the query
        $stmt->execute();
        // results
        $result = $stmt->get_result();
        echo "<table><h3>Cars in parking location:".$_POST['address']."</h3><tr><td>Vin#</td><td>Make</td><td>Model</td><td>Year</td><td>Reservation Number</td><td>Member ID</td></tr>";
        while ($myrow = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$myrow['vin']."</td>";
            echo "<td>".$myrow['make']."</td>";
            echo "<td>".$myrow['model']."</td>";
            echo "<td>".$myrow['year']."</td>";;
            echo "<td>".$myrow['reservation_number']."</td>";
            echo "<td>".$myrow['member_id']."</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

    ?>

    <button type="button" onclick="javascript:location.href='../admin_home.php'">Back</button>

</div></body></html>