<?php include "include/auth.php"; global $user; ?>
<?php

if (isset($_SESSION["verified"]) && $_SESSION["verified"] == "true") {
	echo "<script>window.location.assign('/change_password.php')</script>";
	exit();
}

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
	<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>

	<title>Cambiar Contraseña • ShareYourCanvas</title>
</head>
<style>
	body {
		overflow-x: hidden;
	}
</style>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row">
		<div class="col-sm-3"></div>

		<div class="col-sm-6">
			<div class="text-center mb-5">
				<h1><b>Verifica Tu Identidad</b></h1>
				<hr>
			</div>
			<div class="mb-3">
				<form method="post" id="verify-form" onsubmit="return validateForm()">
					<h5 class="mb-5">Para garantizar la seguridad de la cuenta, verifica tu identidad contestando la pregunta de seguridad.</h5>
					<div class="row g-2 mb-3">
						<div class="col-sm">
							<label for="verify-question" class="form-label">Pregunta de Seguridad</label>
							<select name="question" id="verify-question" class="form-select" required aria-describedby="verify-question-help">
								<option value="none" disabled selected>Selecciona una opción</option>
								<option value="1">¿Cuál es tu color favorito?</option>
								<option value="2">¿Cuál fue el nombre de tu primer mascota?</option>
								<option value="3">¿Cual fue tu apodo de la infancia?</option>
								<option value="4">¿Cual es tu platillo favorito?</option>
								<option value="5">¿Quién es tu artista favorito?</option>
							</select>
						</div>
						<div class="col-sm">
							<label for="verify-answer" class="form-label">Respuesta</label>
							<input type="text" name="answer" id="verify-answer" class="form-control" required />
						</div>
						<div class="form-text" id="verify-question-help">
							Selecciona y responde la pregunta que utilizaste en el registro de tu cuenta.
						</div>
					</div>
					<center><button type="submit" class="btn btn-primary btn-lg mt-3 w-75">Continuar</button></center>
				</form>
			</div>
		</div>

		<div class="col-sm-3"></div>
	</div>

	<?php include "components/preferences.php"; ?>
</body>
<script defer>
	function validateForm () {
		const formData = new FormData(document.getElementById("verify-form"));

		$("input").addClass("is-valid");
		$("#verify-question").addClass(formData.has("question") ? "is-valid" : "is-invalid");

		if (!formData.has("question")) return false;

		return true;
	}
</script>
</html>
<?php

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["question"])) exit();

global $db;

$answer = htmlentities(mysqli_real_escape_string($db->conn, $_POST["answer"]));
$question = htmlentities(mysqli_real_escape_string($db->conn, $_POST["question"]));

$stmt = DB\select(["*"], "users", [
	DB\where("email", "=", $user["email"]),
	DB\where("recovery_question", "=", "$question:$answer", true)
]);
$result = $db->exec($stmt);

if ($result->num_rows != 0) {
	$_SESSION["verified"] = "true";

	echo "<script>window.location.assign('/change_password.php')</script>";
	exit();
}

echo "<script>alert('Datos no validos.')</script>";

?>
