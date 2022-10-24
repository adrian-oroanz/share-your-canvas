<?php session_start(); ?>
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
	<title>Iniciar Sesión • ShareYourCanvas</title>
</head>
<body>
	<div class="row mb-3">
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
				<div class="header mb-md-5 mb-2">
					<h2><strong>Iniciar Sesión</strong></h2>
					<hr />
				</div>
				<div>
					<form method="POST">
						<div class="form-floating mb-3">
							<input type="email" name="email" id="signin-email" class="form-control" placeholder="name@example.com" required />
							<label for="signin-email">Correo electrónico</label>
						</div>
						<div class="form-floating mb-3">
							<input type="password" name="pass" id="signin-pass" class="form-control" placeholder="password" required />
							<label for="signin-pass">Contraseña</label>
						</div>
						<div class="row g-2 pb-3 my-3">
							<div class="col-sm">
								<a href="./forgot_password.php">Recuperar contraseña</a>
							</div>
							<div class="col-sm">
								<a href="./register.php" class="float-md-end">¿No tienes cuenta? ¡Crea una!</a>
							</div>
						</div>
						<center><button class="btn btn-primary btn-lg mt-3 w-75">Iniciar Sesión</button></center>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-3"></div>
	</div>
</body>
</html>
<?php include "include/signin.php"; ?>
