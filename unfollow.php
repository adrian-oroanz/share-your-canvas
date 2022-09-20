<?php

include("includes/header.php");

if ($_SERVER["REQUEST_METHOD"] !== "GET") exit();

if (!isset($_GET["u_id"])) {
	echo "<script>window.location.replace('/home.php')</script>";
	exit();
}

$u_id = $_GET["u_id"];

if ($user_id === $u_id) {
	echo "<script>window.location.replace('/home.php')</script>";
	exit();
}

$result = mysqli_query($con, "DELETE FROM follows WHERE following_id = $u_id AND follower_id = $user_id");

if ($result) {
	echo "<script>window.history.back()</script>";
	exit();
}

echo "<script>alert('Ocurrio un error.')</script>";
echo "<script>window.history.back()</script>";

exit();

?>