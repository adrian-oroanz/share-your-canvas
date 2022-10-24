<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") exit();

include "db.php";
global $db;

$fname = htmlentities(mysqli_real_escape_string($db->conn, $_POST["fname"]));
$lname = htmlentities(mysqli_real_escape_string($db->conn, $_POST["lname"]));
$email = htmlentities(mysqli_real_escape_string($db->conn, $_POST["email"]));
$pass = htmlentities(mysqli_real_escape_string($db->conn, $_POST["pass"]));
$bday = htmlentities(mysqli_real_escape_string($db->conn, $_POST["bday"]));
$gender = htmlentities(mysqli_real_escape_string($db->conn, $_POST["gender"]));
$country = htmlentities(mysqli_real_escape_string($db->conn, $_POST["country"]));
$question = htmlentities(mysqli_real_escape_string($db->conn, $_POST["question"]));
$answer = htmlentities(mysqli_real_escape_string($db->conn, $_POST["answer"]));

if (strlen($pass) < 8) {
	echo "<script>alert('La contraseña debe contener mínimo 8 caracteres')</script>";
	exit();
}

$random_id = sprintf("%05d", rand(0, 999999));
$username = strtolower(explode(" ", $fname)[0] . "_" . explode(" ", $lname)[0] . "_$random_id");

$stmt = DB\select([ "id" ], "users", [ DB\where("email", "=", $email) ]);
$result_email_check = $db->exec($stmt);

if (mysqli_num_rows($result_email_check) != 0) {
	echo "<script>alert('El correo electrónico proporcionado ya está en uso, utiliza otro')</script>";
	exit();
}

$random = rand(1, 3);
$default_pfp = "cdn/users/default_pfp_$random.png";
$random = rand(1, 3);
$default_cover = "cdn/users/default_cover_$random.jpg";
$default_bio = "Hola, ¡me gusta el arte!";

$hashed_pwd = hash("sha256", $pass);

$stmt = DB\insert_into("users", [
	"first_name" => $fname,
	"last_name" => $lname,
	"username" => $username,
	"email" => $email,
	"password" => $hashed_pwd,
	"bio" => $default_bio,
	"birthday" => $bday,
	"gender" => $gender == "dnd" ? null : $gender,
	"country" => $country == "dnd" ? null : $country,
	"avatar_url" => $default_pfp,
	"cover_url" => $default_cover,
	"recovery_question" => "$question:$answer"
]);
$result_insert = $db->exec($stmt);

if ($result_insert) {
	echo "<script>alert('Tu cuenta se ha creado exitosamente, ¡bienvenido/a!')</script>";
	echo "<script>window.location.assign('/login.php')</script>";
	exit();
}

echo "<script>alert('Ocurrió un error al crear tu cuenta, inténtalo de nuevo')</script>";

?>
