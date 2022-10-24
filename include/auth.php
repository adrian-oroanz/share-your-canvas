<?php

session_start();

if (!isset($_SESSION["user"])) {
	echo "<script>window.location.replace('/index.php')</script>";
	exit();
}

include "db.php";
global $db;

$email = $_SESSION["user"];

$stmt = DB\select(["*"], "users", [ DB\where("email", "=", $email) ]);
$result = $db->exec($stmt);

if ($result == false) {
	session_unset();
	echo "<script>window.location.replace('/index.php')</script>";
	exit();
}

$user = mysqli_fetch_array($result);

?>