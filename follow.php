<?php

include("includes/header.php");

if ($_SERVER["REQUEST_METHOD"] !== "GET") exit();

if (!isset($_GET["u_id"])) {
	echo "<script>window.location.replace('/home.php')</script>";
	exit();
}

$u_id = $_GET["u_id"];

// Checking that the user doesn't try to follow themselves.
if ($user_id === $u_id) {
	echo "<script>window.location.replace('/home.php')</script>";
	exit();
}

$result = mysqli_query($con, "INSERT INTO follows (following_id, follower_id) VALUES ($u_id, $user_id)");

if ($result) {
	echo "<script>window.history.back()</script>";
	exit();
}

echo "<script>alert('Ocurrio un error.')</script>";
echo "<script>window.history.back()</script>";

exit();

?>