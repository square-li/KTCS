<!DOCTYPE HTML >
<html >
<head >
    <title > Welcome to mysite </title >
    <link rel="stylesheet" href="style/style.css">
</head >
<body >
<div id="main">
<?php
//Create a user session or resume an existing one
session_start();
?>

    Welcome Administrator, <a href="index.php?logout=1">Log Out</a><br/>
    <ol>
        <li><a href="admin_functions/invoice.php">Invoices</a></li>
        <li><a href="admin_functions/car_manage.php">Cars Management</a></li>
        <li><a href="admin_functions/resp.php">Response feedbacks</a></li>
        <li><a href="admin_functions/search.php">Search Reservation by date</a></li>


    </ol>

</div></body></html>