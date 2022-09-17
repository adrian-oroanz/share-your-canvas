<!DOCTYPE html>
<?php include("includes/header.php"); ?>
<html>
<head>
	<?php
		$user = $_SESSION['user_email'];
		$get_user = "select * from users where user_email='$user'";
		$run_user = mysqli_query($con,$get_user);
		$row = mysqli_fetch_array($run_user);

		$user_name = $row['user_name'];
	?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style/home_style2.css">
	<link rel="stylesheet" type="text/css" href="style/profile_style.css" />
	<title><?php echo $user_name ?></title>
</head>
<style>
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

	textarea {
		resize: vertical;
	}
</style>
<body>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-12">
		<div class="main-content">
			<?php include("edit_user.php"); ?>
			<div class="header">
				<h3 style="text-align: center;"><strong>Editar Información</strong></h3>
				<hr>
			</div>
			<div class="l-part">
				<form action="" method="POST">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
						<input required type="text" class="form-control" name="u_name" placeholder="Nombre de Usuario" value="<?php echo $user_name; ?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
						<textarea required class="form-control" name="u_desc" placeholder="Descripcion" rows="1" maxlength="255">
							<?php echo "$describe_user"; ?>
						</textarea>
					</div><br>
					<hr>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input required type="text" class="form-control" name="first_name" placeholder="Nombre" value="<?php echo $first_name; ?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input required type="text" class="form-control" name="last_name" placeholder="Apellido" value="<?php echo $last_name; ?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
						<input required type="text" class="form-control" name="u_email" placeholder="Email" value="<?php echo $user_email; ?>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-chevron-down"></i></span>
						<select required class="form-control input-md" name="u_country" required>
							<option disabled>Elige tu pais</option>
							<?php
								$countries = array("Mexico", "Estados Unidos", "Colombia", "Peru", "España");

								foreach ($countries as $country) {
									if ($user_country == $country) {
										echo "<option selected>$user_country</option>";
									} else {
										echo "<option>$country</option>";
									}
								}
							?>
						</select>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-chevron-down"></i></span>
						<select required class="form-control input-md" name="u_gender" required>
							<option disabled>Genero</option>
							<?php
								$gender = array("Masculino", "Femenino");

								foreach ($gender as $gen) {
									if ($user_gender == $gen) {
										echo "<option selected>$gen</option>";
									} else {
										echo "<option>$gen</option>";
									}
								}
							?>
						</select>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						<input required type="date" class="form-control" name="u_birthday" placeholder="Fecha de Nacimiento" value="<?php echo $user_birthday; ?>">
					</div><br><br>
					<button form="" style="float: left;" class="btn" onclick="location.replace('home.php')">Cancelar</button>
					<button type="submit" style="float: right;" class="btn btn-info ">Actualizar</button>
				</form><br>
			</div>
		</div>
	</div>
</div>
</body>
</html>