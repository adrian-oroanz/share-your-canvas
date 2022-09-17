<?php
	
	include("includes/connection.php");
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$email = htmlentities(mysqli_real_escape_string($con,$_POST['r_email']));
		$answer = htmlentities(mysqli_real_escape_string($con,$_POST['r_answer']));
		$question = htmlentities(mysqli_real_escape_string($con,$_POST['r_question']));

		if ($question != "0") {
			$check_email = "select * from users where user_email='$email'";
			$run_email = mysqli_query($con, $check_email);
			$result = mysqli_num_rows($run_email);

			if ($result == 1) {
				$result = mysqli_fetch_array($run_email, MYSQLI_ASSOC);
				$recovery = $result['recovery_account'];
				$exploded = explode(':', $recovery, 2);
	
				if ($question == $exploded[0] && $answer == $exploded[1]) {
					$pass = $result['user_pass'];
					
					echo "<br><br><center><h4>Su contraseña es <b>$pass</b></h4><br><br><button class='btn btn-info btn-lg' onclick='location.replace(\"signin.php\")'>Regresar</button></center>";
					exit();
				}
				
				echo "<script>alert('Datos incorrectos')</script>";
				exit();
			}
			
			echo "<script>alert('Datos incorrectos')</script>";
			exit();
		}

		echo"<script>alert('Por favor seleccione una pregunta de seguridad')</script>";
		exit();
	}
?>
