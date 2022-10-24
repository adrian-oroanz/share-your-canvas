<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") exit();

include "db.php";
global $db;

$email = htmlentities(mysqli_real_escape_string($db->conn, $_POST["email"]));
$pass = htmlentities(mysqli_real_escape_string($db->conn, $_POST["pass"]));

$hashed_pwd = hash("sha256", $pass);

$stmt = DB\select([ "id" ], "users", [
	DB\where("email", "=", $email),
	DB\where("password", "=", $hashed_pwd, true)
]);
$result = $db->exec($stmt);

if (mysqli_num_rows($result) != 0) {
	$_SESSION["user"] = $email;
	echo "<script>window.location.assign('/home.php')</script>";
	exit();
}

echo "<script>alert('El correo electrónico y la contraseña no coinciden')</script>";

?>