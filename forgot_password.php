<?php
	session_start();

	if (isset($_SESSION["user"]))
		echo "<script>location.replace('/home.php')</script>";
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous" />
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
	<!-- JavaScript Bundle with Popper -->
	<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
	<!-- JQuery -->
	<script defer src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="./css/external.css" />
	<title>Recuperar Contraseña • ShareYourCanvas</title>
</head>
<style>
	body {
		overflow-x: hidden;
	}

	.cover {
		object-fit: cover;
		width: 100%;
	}
</style>
<body>
	<div class="row mb-3">
		<div class="col-sm-12">
			<div class="well">
				<center><h1>Share Your Canvas</h1></center>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3"></div>

		<div class="col-sm-6">
			<div class="p-md-5 p-2 my-md-4 my-2 shadow">
				<div class="header mb-md-4 mb-2">
					<h2><strong>Recuperar Contraseña</strong></h2>
					<hr />
				</div>
				<div>
					<form id="recovery-form" method="post" onsubmit="return validateForm()">
						<div class="mb-3">
							<label for="recovery-email" class="form-label">Correo Electrónico</label>
							<input type="email" name="email" id="recovery-email" class="form-control" required aria-describedby="recovery-email-help" />
							<div class="form-text">
								Este es el correo electrónico asociado con tu cuenta.
							</div>
						</div>
						<div class="row g-2 mb-3">
							<div class="col-sm">
								<label for="recovery-question" class="form-label">Pregunta de Seguridad</label>
								<select name="question" id="recovery-question" class="form-select" required aria-describedby="recovery-question-help">
									<option value="none" disabled selected>Selecciona una opción</option>
									<option value="1">¿Cuál es tu color favorito?</option>
									<option value="2">¿Cuál fue el nombre de tu primer mascota?</option>
									<option value="3">¿Cual fue tu apodo de la infancia?</option>
									<option value="4">¿Cual es tu platillo favorito?</option>
									<option value="5">¿Quién es tu artista favorito?</option>
								</select>
							</div>
							<div class="col-sm">
								<label for="recovery-answer" class="form-label">Respuesta</label>
								<input type="text" name="answer" id="recovery-answer" class="form-control" required />
							</div>
							<div class="form-text" id="recovery-question-help">
								Selecciona y responde la pregunta que utilizaste en el registro de tu cuenta.
							</div>
						</div>
						<center><button type="submit" class="btn btn-primary btn-lg mt-3 w-75">Continuar</button></center>
					</form>
				</div>
			</div>
		</div>

		<div class="col-sm-3"></div>
	</div>
</body>
<script defer>
	function validateForm () {
		const formData = new FormData(document.getElementById("recovery-form"));

		$("input").addClass("is-valid");
		$("#recovery-question").addClass(formData.has("question") ? "is-valid" : "is-invalid");

		if (!formData.has("question")) return false;

		return true;
	}
</script>
</html>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["question"])) {
	include "include/db.php";
	global $db;

	$email = htmlentities(mysqli_real_escape_string($db->conn, $_POST["email"]));
	$answer = htmlentities(mysqli_real_escape_string($db->conn, $_POST["answer"]));
	$question = htmlentities(mysqli_real_escape_string($db->conn, $_POST["question"]));

	$stmt = DB\select(["*"], "users", [
		DB\where("email", "=", $email),
		DB\where("recovery_question", "=", "$question:$answer", true)
	]);
	$result = $db->exec($stmt);

	if ($result->num_rows != 0) {
		$user = $result->fetch_assoc();
		$_SESSION["user"] = $user["email"];
		$_SESSION["verified"] = "true";
		$fname = $user["first_name"];

		echo "<script>alert('Ha iniciado sesión, a continuación, cambie su contraseña.')</script>";
		echo "<script>window.location.assign('/change_password.php')</script>";
		exit();
	}

	echo "<script>alert('Datos no validos.')</script>";
}

?>
