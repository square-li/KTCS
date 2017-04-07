<!DOCTYPE HTML>
<html>
<head>
    <title>Register for KTCS</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<div id="main">

<?php


//check if the login form has been submitted
if(isset($_POST['registerBtn'])){

    // include database connection
    include_once 'config/connection.php';

    // SELECT query
    $query = "Insert into KTCS.ktcs_members (name, address, phone_number, email, driver_license_number, annual_membership_fee, password)  
  VALUES (?,?,?,?,?,50,?)";

    if (strcmp($_POST['password'], $_POST['re_password']) != 0) {
    echo "Passwords do not match. <br/>";}
    // prepare query for execution
    else {
    if($stmt = $con->prepare($query)){

        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("ssssss", $_POST['name'], $_POST['address'],$_POST['phone'],$_POST['email'],$_POST['number'],$_POST['password']);

        // Execute the query
        if ($stmt->execute()){
            if (isset($_SESSION[$_POST['name']])){
                echo 'User name has been taken';

            }
            else{
            echo "Registration success.";
                $_SESSION[$_POST['name']] =1;

            }
        }

        /* resultset */
        $result = $stmt->get_result();

        // Get the number of rows returned





    } else {
        echo "failed to prepare the SQL";
    }
    }
}



?>
<h1>Welcome to KTCS</h1>
<form name='login' id='login' action='register.php' method='post'>
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
            <td>Re-type Your Password</td>
            <td><input type='password' name='re_password' id='re_password' /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td>
                <input type='email' name='email' id='email' >
            </td>
        </tr>
        <tr>
            <td>Phone</td>
            <td>
                <input type='text' name='phone' id='phone' >
            </td>
        </tr>
        <tr>
            <td>Address</td>
            <td>
                <input type='text' name='address' id='address' >
            </td>
        </tr>

        <tr>
            <td>Diving license number</td>
            <td>
                <input type='text' name='number' id='number' >
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='registerBtn' name='registerBtn' value='Register' />
            </td>
        </tr>
    </table>
</form>
    <button type="button" onclick="javascript:location.href='index.php'">Back</button>
</div>
</body>
</html>
