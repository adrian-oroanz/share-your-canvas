<?php
include("includes/connection.php");

	if(isset($_POST['sign_up'])){

		$first_name = htmlentities(mysqli_real_escape_string($con,$_POST['first_name']));
		$last_name = htmlentities(mysqli_real_escape_string($con,$_POST['last_name']));
		$pass = htmlentities(mysqli_real_escape_string($con,$_POST['u_pass']));
		$email = htmlentities(mysqli_real_escape_string($con,$_POST['u_email']));
		$country = htmlentities(mysqli_real_escape_string($con,$_POST['u_country']));
		$gender = htmlentities(mysqli_real_escape_string($con,$_POST['u_gender']));
		$birthday = htmlentities(mysqli_real_escape_string($con,$_POST['u_birthday']));
		$question = htmlentities(mysqli_real_escape_string($con,$_POST['u_question']));
		$recovery = htmlentities(mysqli_real_escape_string($con,$_POST['u_recovery']));
		$status = "verified";
		$posts = "no";
		$newgid = sprintf('%05d', rand(0, 999999));

		$username = strtolower($first_name . "_" . $last_name . "_" . $newgid);
		$check_username_query = "select user_name from users where user_email='$email'";
		$run_username = mysqli_query($con,$check_username_query);

		if (strlen($pass) < 8) {
			echo "<script>alert('La contraseña debe tener al menos 8 caracteres')</script>";
			exit();
		}

		if($question == "0") {
			echo"<script>alert('Por favor seleccione una pregunta de seguridad')</script>";
			exit();
		}

		$check_email = "select * from users where user_email='$email'";
		$run_email = mysqli_query($con, $check_email);

		$check = mysqli_num_rows($run_email);

		if($check == 1){
			echo "<script>alert('Ese Correo ya esta siendo usado, intenta otro')</script>";
			echo "<script>window.open('signup.php', '_self')</script>";
			exit();
		}

		$rand = rand(0, 4);
		$profile_pic = "default_pfp_$rand.png"; // Se selecciona una imagen de perfil por defecto.

		$rand = rand(0, 4);
		$cover_photo = "default_cover_$rand.jpg";

		$insert = "insert into users (f_name,l_name,user_name,describe_user,Relationship,user_pass,user_email,user_country,user_gender,user_birthday,user_image,user_cover,user_reg_date,status,posts,recovery_account)
		values('$first_name','$last_name','$username','Hola, me gusta el arte!','...','$pass','$email','$country','$gender','$birthday','$profile_pic','$cover_photo',NOW(),'$status','$posts','$question:$recovery')";
		
		$query = mysqli_query($con, $insert);

		if($query){
			echo "<script>alert('Bienvenido $first_name, ¡A crear arte!.')</script>";
			echo "<script>window.open('signin.php', '_self')</script>";
		}
		else{
			echo "<script>alert('Algo mal ocurrio con tu registro, intenta de nuevo :(')</script>";
			echo "<script>window.open('signup.php', '_self')</script>";
		}
	}
?>