<?php
	session_start();

	if (isset($_SESSION["user"]))
		echo "<script>location.replace('/home.php')</script>";
?>

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
	<title>ShareYourCanvas</title>
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
	
	<div class="row g-4 px-3">
		<div class="col-sm-6">
			<img src="./cdn/img/main_cover.jpg" alt="" class="rounded cover h-auto" />
		</div>
		<div class="col-sm-1"></div>
		<div class="col-sm-4">
			<img src="./cdn/img/logo.jpg" alt="ShareYourCanvas" class="rounded" width="80px" height="80px" />
			<h2>
				<strong>Encuentra a diferentes<br />artistas con tus mismos gustos</strong>
			</h2>
			<br /><br />
			<h3><strong>Únete a la comunidad</strong></h3>
			<br />
			<a href="./register.php" class="btn btn-primary btn-lg w-100">Registrarse</a>
			<br /><br />
			<a href="./login.php" class="btn btn-outline-secondary btn-lg w-100">Iniciar Sesión</a>
		</div>
		<div class="col-sm-1"></div>
	</div>
</body>
</html>