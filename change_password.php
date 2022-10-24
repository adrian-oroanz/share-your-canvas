<?php include "include/auth.php"; global $user; ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php
		if (!isset($_SESSION["verified"]) || $_SESSION["verified"] != "true") {
			echo "<script>window.location.assign('/verify.php')</script>";
			exit();
		}
	?>
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
				<h1><b>Cambiar Contraseña</b></h1>
				<hr>
			</div>
			<div class="row">
				<div class="col-sm-2"></div>
				
				<div class="col-sm-8">
					<h5 class="text-center">La contraseña asociada al correo <b><?php echo $user["email"]; ?></b> se cambiará</h5>
					<br />
					<br />
					<form id="password-form" method="post">
						<div class="mb-3">
							<label for="password-new" class="form-label">Nueva Contraseña</label>
							<input type="password" name="password" id="password-new" minlength="8" class="form-control" required aria-describedby="password-new-help" />
							<div class="form-text">
								La contraseña debe contener mínimo 8 caracteres y no puede ser la misma a la anterior.
							</div>
						</div>
						<div class="mb-3">
							<label for="password-confirm" class="form-label">Confirmar Contraseña</label>
							<input type="password" name="confirm" id="password-confirm" class="form-control" required aria-describedby="password-confirm-help" />
							<div class="form-text">
								Vuelve a introducir tu nueva contraseña.
							</div>
						</div>
						<center><button class="btn btn-primary btn-lg mt-3 w-75">Continuar</button></center>
					</form>
				</div>

				<div class="col-sm-2"></div>
			</div>
		</div>

		<div class="col-sm-3"></div>
	</div>

	<?php include "components/preferences.php"; ?>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") exit();

global $db;

$password = htmlentities(mysqli_real_escape_string($db->conn, $_POST["password"]));
$confirm = htmlentities(mysqli_real_escape_string($db->conn, $_POST["confirm"]));

if (strlen($password) < 8) {
	echo "<script>alert('La contraseña debe tener una longitud mayor a 8 caracteres')</script>";
	exit();	
}

if ($password != $confirm) {
	echo "<script>alert('Las contraseñas no coinciden')</script>";
	exit();
}

if (hash("sha256", $password) == $user["password"]) {
	echo "<script>alert('La nueva contraseña no puede ser la misma a la anterior')</script>";
	exit();
}

$stmt = DB\update("users", [ "password" => hash("sha256", $password) ], [ DB\where("id", "=", $user["id"]) ]);
$result = $db->exec($stmt);

if ($result) {
	session_unset();
	echo "<script>alert('Se ha cambiado tu contraseña exitosamente\\nVuelve a iniciar sesión para continuar')</script>";
	echo "<script>window.location.assign('/login.php')</script>";
	exit();
}

echo "<script>alert('Ocurrió un error al cambiar tu contraseña, inténtalo otra vez')</script>";
?>
