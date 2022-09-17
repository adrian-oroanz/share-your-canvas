<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<title>Forgot Password</title>
</head>
<style>
	body{
		overflow-x: hidden;
	}
	.main-content{
		width: 50%;
		height: 40%;
		margin: 10px auto;
		background-color: #fff;
		border: 2px solid #e6e6e6;
		padding: 40px 50px;
	}
	.header{
		border: 0px solid #000;
		margin-bottom: 5px;
	}
	.well{
		background-color: #000;
	}
</style>
<body>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<center><h1 style="color: white;">Share Your Canvas</h1></center>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="main-content">
			<div class="header">
				<h3 style="text-align: center;"><strong>Recuperar Contraseña</strong></h3>
				<hr>
			</div>
			<div class="l-part">
				<?php include("validate_question.php"); ?>
				<form action="" method="post">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
						<input type="email" class="form-control" name="r_email" placeholder="Ingresa tu correo" required>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-chevron-down"></i></span>
						<select name="r_question" class="form-control input-md" required>
							<option disabled value="0">Selecciona una pregunta de seguridad</option>
							<option value="1">¿Cual es tu color favorito?</option>
							<option value="2">¿Cual fue el nombre de tu primera mascota?</option>
							<option value="3">¿Cual es tu comida favorita?</option>
							<option value="4">¿Cual fue tu apodo en la infancia?</option>
							<option value="5">¿En que ciudad naciste?</option>
						</select>
						<span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
						<input type="text" class="form-control" name="r_answer" required placeholder="Escribe tu respuesta aqui...">
					</div><br><br>

					<center>
						<button style="float: left;" form="" class="btn" onclick="location.replace('signin.php')">Regresar</button>
						<button style="float: right;" class="btn btn-info">Continuar</button>
					</center><br>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>
