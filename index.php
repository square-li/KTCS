<!DOCTYPE HTML>
<html>
    <head>
        <title>Welcome to mysite</title>
        <link rel="stylesheet" href="style/style.css">
    </head>
<body>
<div id="main">
    <h1> Welcome to KTCS</h1>
 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>

 <?php
 //check if the user clicked the logout link and set the logout GET parameter
if(isset($_GET['logout'])){
	//Destroy the user's session.
	$_SESSION['member_id']=null;
	session_destroy();
}
 ?>
 
 
 <?php
 //check if the user is already logged in and has an active session
if(isset($_SESSION['member_id'])){
	//Redirect the browser to the profile editing page and kill this page.

    if (strcmp($_SESSION['member_id'],'9001') != 0)
    {
        header("Location: user_home.php");
	    die();
    }
    else{
        header("Location: admin_home.php");
        die();
    }
}
 ?>
 
 <?php

 
//check if the login form has been submitted
if(isset($_POST['loginBtn'])){

    // include database connection
    include_once 'config/connection.php'; 
        if (strcmp($_POST['name'],'admin') ==0) {
            header("Location: admin_home.php");
            $query = "SELECT name, password, FROM KTCS.admins WHERE name=? AND password=?";
            die();

        }


	// SELECT query
        $query = "SELECT member_id,name, password, email FROM KTCS.ktcs_members WHERE name=? AND password=?";
 
        // prepare query for execution
        if($stmt = $con->prepare($query)) {

            // bind the parameters. This is the best way to prevent SQL injection hacks.
            $stmt->bind_Param("ss", $_POST['name'], $_POST['password']);


            // Execute the query
            $stmt->execute();

            /* resultset */
            $result = $stmt->get_result();

            // Get the number of rows returned
            $num = $result->num_rows;;

            if ($num > 0) {
                //If the username/password matches a user in our database
                //Read the user details
                $myrow = $result->fetch_assoc();
                //Create a session variable that holds the user's id
                $_SESSION['member_id'] = $myrow['member_id'];
                //Redirect the browser to the profile editing page and kill this page.
                if (strcmp($_SESSION['name'], 'admin') != 0) {
                    header("Location: user_home.php");
                    die();
                } else {
                    header("Location: admin_home.php");
                    die();
                }

            } else {
                //If the username/password doesn't matche a user in our database
                // Display an error message and the login form
                echo "Failed to login";
            }
        }
        else {
            echo "failed to prepare the SQL";
        }


 }
 
?>

<!-- dynamic content will be here -->
 <form name='login' id='login' action='index.php' method='post'>
    <table border='0'>
        <tr>
            <td>Username</td>
            <td><input type='text' name='name' id='name' /></td>
        </tr>
        <tr>
            <td>Password</td>
             <td><input type='password' name='password' id='password' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='loginBtn' name='loginBtn' value='Log In' /> 
            </td>
        </tr>
    </table>
</form>
<a href="register.php">register</a>
</div>
</body>
</html>