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
    header("Location: index.php");
    die();
}


?>
<?php
    if (isset($_POST['rating'])){
        include_once '../config/connection.php';

        // SELECT query
        $query = "insert into KTCS.rental_comments (vin, member_id, rating, comments) 
                    VALUES (?,?,?,?)
                  ";

        // prepare query for execution
        if($stmt = $con->prepare($query)){

            // bind the parameters. This is the best way to prevent SQL injection hacks.
            $stmt->bind_Param("ssss",$_SESSION['vin'], $_SESSION['member_id'],$_POST['rating'],$_POST['comments']);

            // Execute the query
            $stmt->execute();
            // results
            $result = $stmt->get_result();
            echo "Your comments have been submit. Thank you!.<br>";
        }
        else{
            echo 'failed.';
        }




    }
?>



Welcome  <?php echo $myrow['name']; ?>, <a href="../index.php?logout=1">Log Out</a><br/>
<!-- dynamic content will be here -->
<form id="feedback" action="feedback.php" method="post" name="feedback" style="align-content: center;align-items: center;text-align: center">
    <textarea id="comments" name="comments" style="width: 400px;height: 300px;align-self: center;margin: 20px auto; font-size: 14px;">
        Thanks for using our service. You can leave your comments here to make our service better!
    </textarea>
    <br>
    Rate our service!
    <span class="starRating">
      <input id="rating5" type="radio" name="rating" value="5">
      <label for="rating5">5</label>
      <input id="rating4" type="radio" name="rating" value="4">
      <label for="rating4">4</label>
      <input id="rating3" type="radio" name="rating" value="3">
      <label for="rating3">3</label>
      <input id="rating2" type="radio" name="rating" value="2">
      <label for="rating2">2</label>
      <input id="rating1" type="radio" name="rating" value="1">
      <label for="rating1">1</label>
    </span>
    <input type="submit" >
</form>
    <button type="button" onclick="javascript:location.href='../user_home.php'">Back</button>
</div></body></html>