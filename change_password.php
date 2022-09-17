<!DOCTYPE html>
<?php include("includes/header.php"); ?>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<title>Cambiar Contraseña • ShareYourCanvas</title>
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
		<div class="main-content">
			<div class="header">
				<h3 style="text-align: center;"><strong>Cambiar Contraseña</strong></h3>
				<hr>
			</div>
			<div class="l-part">
				<center><p><?php echo "⚠ La contraseña asociada al correo <b>".$user_email."</b> se cambiara ⚠" ?></p></center><br>

				<?php
					if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
						if (!isset($_GET['r_question']) || !isset($_GET['r_answer'])) {
							echo "
							<form action='' method='get'>
								<div class='input-group'>
									<span class='input-group-addon'><i class='glyphicon glyphicon-chevron-down'></i></span>
									<select name='r_question' class='form-control input-md' required>
										<option disabled value='0'>Selecciona una pregunta de seguridad</option>
										<option value='1'>¿Cual es tu color favorito?</option>
										<option value='2'>¿Cual fue el nombre de tu primera mascota?</option>
										<option value='3'>¿Cual es tu comida favorita?</option>
										<option value='4'>¿Cual fue tu apodo en la infancia?</option>
										<option value='5'>¿En que ciudad naciste?</option>
									</select>
									<span class='input-group-addon'><i class='glyphicon glyphicon-pencil'></i></span>
									<input type='text' class='form-control' name='r_answer' required placeholder='Escribe tu respuesta aqui...'>
								</div><br><br>

								<center>
									<button style='float: left;' form='' class='btn' onclick='window.open(`home.php`, `_self`)'>Regresar</button>
									<button style='float: right;' type='submit' class='btn btn-info'>Continuar</button>
								</center><br>
							</form>
							";
							exit();
						}

						$r_question = $_GET['r_question'];
						$r_answer = $_GET['r_answer'];
						
						$exploded = explode(':', $recovery_account, 2);

						if ($exploded[0] !== $r_question || $exploded[1] !== $r_answer) {
							echo "<script>alert('La pregunta seleccionada o respuesta no coinciden')</script>";
							echo "<script>window.open('change_password.php', '_self')</script>";
							exit();
						}
					}
				?>

				<form action="" method="post">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" class="form-control" name="r_password" placeholder="Nueva Contraseña" required>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" name="r_password_confirm" class="form-control" placeholder="Confirmar Contraseña" required>
					</div><br><br>

					<center>
						<button form="" style="float: left;" onclick="location.replace('home.php')" class="btn">Cancelar</button>
						<button type="submit" style="float: right;" class="btn btn-info">Continuar</button>
					</center><br>
				</form>
				<?php
					if ($_SERVER['REQUEST_METHOD'] === 'POST') {
						$r_password = $_POST['r_password'];
						$r_password_confirm = $_POST['r_password_confirm'];

						if ($r_password === $r_password_confirm) {
							$query = "SELECT user_pass FROM users WHERE user_email='$user_email'";
							$result = mysqli_query($con, $query);
							$row = mysqli_fetch_array($result);

							if ($row['user_pass'] === $r_password) {
								echo "<script>alert('La contraseña no puede ser la misma a la anterior. Utiliza otra contraseña')</script>";
								exit();
							}

							$query = "UPDATE users SET user_pass='$r_password' WHERE user_email='$user_email'";
							$result = mysqli_query($con, $query);

							if ($result) {
								unset($_SESSION['user_email']);
								echo "<script>alert('Contraseña cambiada con exito. Vuelve a iniciar sesión para continuar')</script>";
								echo "<script>window.open('signin.php', '_self')</script>";
							} else {
								echo "<script>alert('Error al cambiar contraseña')</script>";
							}
						} else {
							echo "<script>alert('Las contraseñas no coinciden')</script>";
						}
					}
				?>
			</div>
		</div>
	</div>
</div>
</body>
</html>