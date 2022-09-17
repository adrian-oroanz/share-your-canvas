<!DOCTYPE html>
<html>
<head>
	<title>ShareYourCanvas</title>
	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>
	body{
		overflow-x: hidden;
	}
	#centered1{
		position: absolute;
		font-size: 10vw;
		top: 30%;
		left: 30%;
		transform: translate(-50%, -50%);
	}
	#centered2{
		position: absolute;
		font-size: 10vw;
		top: 50%;
		left: 40%;
		transform: translate(-50%, -50%);
	}
	#centered3{
		position: absolute;
		font-size: 10vw;
		top: 70%;
		left: 30%;
		transform: translate(-50%, -50%);
	}
	#signup{
		width: 60%;
		border-radius: 30px;
	}
	#login{
		width: 60%;
		background-color: #fff;
		border: 1px solid #293462;
		color: #293462;
		border-radius: 30px;
	}
	#login:hover{
		width: 60%;
		background-color: #fff;
		color: #112B3C;
		border: 2px solid #112B3C;
		border-radius: 30px;
	}
	.well{
		background-color: #293462;
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
		<div class="col-sm-6" style="left:0.5%;">
			<img src="images/ImagenMain.jpg" class="img-rounded" title="ShareYourCanvas" width="1000px" height="750px">
		</div>
		<div class="col-sm-6" style="left:8%">
			<img src="images/Logo.jpg" class="img-rounded" title="Logo" width="80px" height="80px">
			<h2><strong>Encuentra a diferentes<br>artistas con tus mismos gustos</strong></h2><br><br>
			<h4><strong>Unete a la comunidad</strong></h4>
			<form method="post" action="">
				<a href="signup.php" id="signup" class="btn btn-info btn-lg" name="signup">Registrarse</a href="signup.php"><br><br>
				
				<a href="signin.php" id="login" class="btn btn-info btn-lg" name="login">Iniciar Sesion</a><br><br>
				
			</form>
		</div>
	</div>
</body>
</html>