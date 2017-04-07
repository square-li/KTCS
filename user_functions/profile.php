<!DOCTYPE HTML>
<html>
    <head>
        <title>Welcome to mysite</title>
        <link rel="stylesheet" href="../style/style.css">
    </head>
<body>
<div id="main">
 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 
 <?php
 
 if(isset($_POST['updateBtn']) && isset($_SESSION['member_id'])){
  // include database connection
    include_once '../config/connection.php';
     $flag = 1;

     if (strcmp($_POST['email'],"")==0 ){
        if (strcmp($_POST['password'],"")==0){
            echo "No information was updated.<br>";
            $flag = 0;

        }
        else{
            $query = "UPDATE KTCS.ktcs_members SET password=? WHERE member_id=?";
            $stmt->bind_param('ss', $_POST['password'], $_SESSION['member_id']);
            $stmt = $con->prepare($query);
        }
    }
    elseif (strcmp($_POST['password'],"")==0){
        $query = "UPDATE KTCS.ktcs_members SET email=? WHERE member_id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ss', $_POST['email'], $_SESSION['member_id']);

    }
    else {
        $query = "UPDATE KTCS.ktcs_members SET password=?,email=? WHERE member_id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('sss', $_POST['password'], $_POST['email'], $_SESSION['member_id']);

    }

    if ($flag!=0){
        if (strcmp($_POST['password'], $_POST['re_password']) != 0) {
            echo "Passwords do not match. <br/>";

        } else {
            // Execute the query
            if ($stmt->execute()) {
                echo "Record was updated. <br/>";
            } else {
                echo 'Unable to update record. Please try again. <br/>';
            }
        }
    }

 }
 
 ?>

 <?php

 if(isset($_POST['backBtn']) && isset($_SESSION['member_id'])){
     // include database connection
     header("Location: ../user_home.php");
     die();
 }

 ?>

 <?php
if(isset($_SESSION['member_id'])){
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
 Welcome  <?php echo $myrow['name']; ?>, <a href="../index.php?logout=1">Log Out</a><br/>
<!-- dynamic content will be here -->
<form name='editProfile' id='editProfile' action='profile.php' method='post'>
    <table border='0'>
        <tr>
            <td>Username</td>
            <td> <?php echo "<input type='text' name='name' id='name' value ='".$myrow['name']."' disabled  /></td>"; ?>
        </tr>
        <tr>
            <td>New Password</td>
             <td><input type='password' name='password' id='password' /></td>
        </tr>
        <tr>
            <td>Re-type Your Password</td>
            <td><input type='password' name='re_password' id='re_password' /></td>
        </tr>
		<tr>
            <td>Email</td>
            <td>
                <input type='text' name='email' id='email' value = '<?php echo $myrow['email'];?>'>
            </td>
        </tr>
        <tr>
            <td>
                <input type='submit' name='backBtn' id='backBtn' value='Back' />
            </td>
            <td>
                <input type='submit' name='updateBtn' id='updateBtn' value='Update' /> 
            </td>

            <td></td>
        </tr>
    </table>
</form>
    </div>
</body>
</html>