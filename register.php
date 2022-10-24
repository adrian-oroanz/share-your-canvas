<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous" />
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
	<!-- JavaScript Bundle with Popper -->
	<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
	<!-- JQuery -->
	<script defer src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="./css/external.css" />
	<title>Registrarse • ShareYourCanvas</title>
</head>
<body>
	<div class="row">
		<div class="col-sm-12">
			<div class="well">
				<center><h1>ShareYourCanvas</h1></center>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3"></div>
		<div class="col-sm-6">
			<div class="p-md-5 p-2 my-md-4 my-2 shadow">
				<div class="header mb-md-4 mb-2">
					<h2><strong>Únete a Nuestra Comunidad</strong></h2>
					<hr />
				</div>
				<div>
					<form id="signup-form" method="post" onsubmit="return validateForm()">
						<div class="row g-2 mb-3">
							<div class="col-sm">
								<label for="signup-fname" class="form-label">Nombre(s)</label>
								<input type="text" name="fname" id="signup-fname" class="form-control" placeholder="John" required />
							</div>
							<div class="col-sm">
								<label for="signup-lname" class="form-label">Apellido(s)</label>
								<input type="text" name="lname" id="signup-lname" class="form-control" placeholder="Doe" required />
							</div>
						</div>

						<div class="row g-2 mb-3">
							<div class="col-sm">
								<label for="signup-email" class="form-label">Correo Electrónico</label>
								<input type="email" name="email" id="signup-email" class="form-control" placeholder="name@example.com" required aria-describedby="signup-email-help" />
								<div class="form-text" id="signup-email-help">
									Este es el correo electrónico con el que iniciaras sesión.
								</div>
							</div>
							<div class="col-sm">
								<label for="signup-pass" class="form-label">Contraseña</label>
								<input type="password" name="pass" id="signup-pass" class="form-control" placeholder="••••••••" minlength="8" required aria-describedby="signup-pass-help" />
								<div class="form-text" id="signup-pass-help">
									La contraseña debe contener mínimo 8 caracteres.
								</div>
							</div>
						</div>
						
						<div class="row g-2 mb-3">
							<div class="col-sm">
								<label for="signup-bday" class="form-label">Fecha de Nacimiento</label>
								<input type="date" name="bday" id="signup-bday" class="form-control" placeholder="Fecha de nacimiento" required />
							</div>
							<div class="col-sm">
								<label for="signup-gender" class="form-label">Género </label>
								<select name="gender" id="signup-gender" class="form-select" required aria-describedby="signup-gender-help">
									<option value="none" disabled selected>Selecciona una opción</option>
									<option value="dnd">Sin especificar</option>
									<option value="m">Masculino</option>
									<option value="f">Femenino</option>
									<option value="o">Otro</option>
								</select>
								<div class="form-text" id="signup-gender-help">
									Si no quieres mostrar tu género puedes seleccionar la opción "Sin especificar".
								</div>
							</div>
						</div>

						<div class="mb-3">
							<label for="signup-country" class="form-label">País de Residencia</label>
							<select name="country" id="signup-country" class="form-select" required aria-describedby="signup-country-help">
								<option value="none" disabled selected>Selecciona una opción</option>
								<option value="dnd">Sin especificar</option>
								<option value="co">Colombia</option>
								<option value="es">España</option>
								<option value="us">Estados Unidos</option>
								<option value="mx">México</option>
								<option value="pe">Perú</option>
							</select>
							<div class="form-text" id="signup-country-help">
								Si no quieres mostrar tu país de residencia puedes seleccionar la opción "Sin especificar".
							</div>
						</div>

						<div class="row g-2 mb-3">
							<div class="col-sm">
								<label for="signup-question" class="form-label">Pregunta de Seguridad</label>
								<select name="question" id="signup-question" class="form-select" required aria-describedby="signup-question-help">
									<option value="none" disabled selected>Selecciona una opción</option>
									<option value="1">¿Cuál es tu color favorito?</option>
									<option value="2">¿Cuál fue el nombre de tu primer mascota?</option>
									<option value="3">¿Cual fue tu apodo de la infancia?</option>
									<option value="4">¿Cual es tu platillo favorito?</option>
									<option value="5">¿Quién es tu artista favorito?</option>
								</select>
							</div>
							<div class="col-sm">
								<label for="signup-answer" class="form-label">Respuesta</label>
								<input type="text" name="answer" class="form-control" id="signup-answer" required />
							</div>
							<div class="form-text" id="signup-question-help">
								La pregunta que selecciones solo se utilizará para recuperar o cambiar tu contraseña en el futuro.
							</div>
						</div>
						<a href="./login.php" class="float-md-end float-start mb-3">¿Ya tienes cuenta? Inicia sesión</a>
						<center><button class="btn btn-primary btn-lg mt-3 w-75">Registrarse</button></center>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-3"></div>
	</div>
</body>
<script defer>
	function validateForm () {

		const formData = new FormData(document.getElementById("signup-form"));

		$("input").addClass("is-valid");
		$("#signup-gender").addClass(formData.has("gender") ? "is-valid" : "is-invalid");
		$("#signup-country").addClass(formData.has("country") ? "is-valid" : "is-invalid");
		$("#signup-question").addClass(formData.has("question") ? "is-valid" : "is-invalid");

		if (!formData.has("gender") || !formData.has("country") || !formData.has("question")) return false;

		return true;

	}
</script>
</html>
<?php include "include/signup.php"; ?>
