<!DOCTYPE html>
<?php include("includes/header.php"); ?>
<html>
<head>
	<?php
		$user = $_SESSION['user_email'];
		$get_user = "SELECT * FROM users WHERE user_email='$user'";
		$run_user = mysqli_query($con,$get_user);
		$row = mysqli_fetch_array($run_user);

		$user_name = $row['user_name'];
	?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<title>Artistas • ShareYourCanvas</title>
</head>
<style>
	body {
		overflow-x: hidden;
	}

	.member {
		border: 5px solid #e6e6e6;
		padding: 40px 50px;
	}
</style>
<body>
<div class="row">
	<div class="col-sm-12">
		<center><h2><strong>Encuentra Más Artistas</strong></h2></center>
		<hr><br><br>
		<?php get_members(); ?>
	</div>
</div>
</body>
</html>