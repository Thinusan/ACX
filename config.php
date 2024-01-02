<?php
$con=mysqli_connect("localhost","root","");
if(!$con)
	{
		die("Sever connection failed!".mysqli_connect_error());
	}
$connect_db=mysqli_select_db($con,"ACX");

if(!$connect_db)
	{
		die("Database connection failed!");
	}

?>


