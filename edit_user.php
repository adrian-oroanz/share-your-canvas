<?php
include("includes/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

	$user_name = htmlentities(mysqli_real_escape_string($con, $_POST['u_name']));
	$user_description = htmlentities(mysqli_real_escape_string($con, $_POST['u_desc']));
	$first_name = htmlentities(mysqli_real_escape_string($con,$_POST['first_name']));
	$last_name = htmlentities(mysqli_real_escape_string($con,$_POST['last_name']));
	$email = htmlentities(mysqli_real_escape_string($con,$_POST['u_email']));
	$country = htmlentities(mysqli_real_escape_string($con,$_POST['u_country']));
	$gender = htmlentities(mysqli_real_escape_string($con,$_POST['u_gender']));
	$birthday = htmlentities(mysqli_real_escape_string($con,$_POST['u_birthday']));

	
	$username_check = mysqli_query($con, "SELECT * FROM users WHERE user_name = '$user_name'");
	$result = mysqli_fetch_array($username_check);

	if ($result && $result['user_id'] !== $user_id) {
		echo "<script>alert('El nombre de usuario ya está en uso, selecciona otro')</script>";
		exit();
	}

	$email_check = mysqli_query($con, "SELECT * FROM users WHERE user_email = '$email'");
	$result = mysqli_fetch_array($email_check);

	if ($result && $result['user_id'] !== $user_id) {
		echo "<script>alert('El email ya esta en uso, utiliza otro')</script>";
		exit();
	}

	$update = "UPDATE users SET user_name='$user_name', describe_user='$user_description', f_name='$first_name', l_name='$last_name', user_email='$email', user_country='$country', user_gender='$gender', user_birthday='$birthday' WHERE user_id=$user_id";
	$result = mysqli_query($con, $update);
	
	if ($result) {
		$_SESSION['user_email'] = $email;
		echo "<script>alert('Se ha actualizado la informacion exitosamente')</script>";
		echo "<script>window.open('home.php', '_self')</script>";
		exit();
	}
	else {
		echo "<script>alert('No se ha podido actualizar la información. Intentelo mas tarde')</script>";
		exit();
	}
}
?>