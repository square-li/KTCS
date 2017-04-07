<!DOCTYPE HTML >
<html>
<head>
    <title> Welcome to mysite </title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
<div id="main">
    <?php
    //Create a user session or resume an existing one
    session_start();
    ?>

    <form name="comments" id="comments" method="post" action="resp.php">
        <table>
            <tr><td></td><td>Feedback ID</td><td>User</td><td>Name</td><td>Date</td><td>Comments</td><td>Response</td></tr>
    <?php
    {

            include_once '../config/connection.php';

            // SELECT query
            $query = "select * from rental_comments NATURAL JOIN  ktcs_members ORDER BY id";

            // prepare query for execution
            $stmt = $con->prepare($query);

            // Execute the query
            $stmt->execute();

            // results
            $result = $stmt->get_result();

            // Row data
            while ($myrow = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><input type='radio' name='id' value=".$myrow['id']."></td>";
                echo "<td>" . $myrow['id'] . "</td>";
                echo "<td>" . $myrow['member_id'] . "</td>";
                echo "<td>" . $myrow['name'] . "</td>";
                echo "<td>" . $myrow['email'] . "</td>";
                echo "<td>" . $myrow['comments'] . "</td>";
                echo "<td>" . $myrow['response'] . "</td>";
                echo "</tr>";
            }



    }
    ?>
        </table>
        <input type="submit" value="Update this response.">
    </form>
    <div id="main" style="max-width: 550px;border:0px">
    <?php


        if (isset($_POST['id'])){
            $_SESSION['id'] = $_POST['id'];
            echo "Response to feedback Id: ".$_SESSION['id'];
           echo  "<form name=\"comments\" id=\"comments\" method=\"post\" action=\"resp.php\"> <textarea name='comments' id='comments'></textarea>";
            echo "<input type='submit'>";
        }
        #echo var_dump($_POST),var_dump( $_SESSION);
        if (isset($_POST['comments'])){
            include_once '../config/connection.php';

            // SELECT query
            $query = "UPDATE rental_comments set response=? where id=?";

            // prepare query for execution
            $stmt = $con->prepare($query);

            $stmt->bind_param('ss', $_POST['comments'], $_SESSION['id']);
            // Execute the query
            $stmt->execute();

            // results
            $result = $stmt->get_result();
            echo "Response was updated.";
            header("Location: resp.php");
            die();
        }
    ?>

    </div>


    Welcome Administrator, <a href="index.php?logout=1">Log Out</a><br/>
    <!-- dynamic content will be here -->

    <button type="button" onclick="javascript:location.href='../admin_home.php'">Back</button>

</div>
</body>
</html>