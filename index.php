<?php
session_start();

if (isset($_SESSION['user_email']))
	echo "<script>location.replace('/home.php')</script>";

include("main.php");
?>