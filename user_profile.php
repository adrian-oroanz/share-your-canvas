<!DOCTYPE html>
<?php include("includes/header.php"); ?>
<html lang="en">
<head>
	<?php
		if (!isset($_GET["u_id"])) {
			echo "<script>window.location.replace('/home.php')</script>";
			exit();
		}

		$u_id = $_GET["u_id"];

		if ($user_id == $u_id) {
			echo "<script>window.location.replace('/profile.php?u_id=$user_id')</script>";
			exit();
		}

		$res = mysqli_query($con, "SELECT * FROM users WHERE user_id = $u_id");
		$row = mysqli_fetch_array($res);
		$u_name = $row["user_name"];
		$u_image = $row["user_image"];
		$u_cover = $row["user_cover"];
		$u_first = $row["f_name"];
		$u_last = $row["l_name"];
		$u_describe = $row["describe_user"];
		$u_country = $row["user_country"];
		$u_gender = $row["user_gender"];
		$u_reg_date = $row["user_reg_date"];
		$u_birthday = $row["user_birthday"];
	?>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="style/profile_style.css" />
	<title><?php echo "$u_name • ShareYourCanvas"; ?></title>
	<style>
		body {
			overflow-x: hidden;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8">
		<div>
			<img src="cover/<?php echo $u_cover; ?>" alt="<?php echo $u_name; ?>'s Cover" class="img-rounded" style="width: 100%;">
		</div>
		<div>
			<div style="position:absolute; bottom:45px; left:40px">
				<img src="users/<?php echo $u_image; ?>" alt="<?php echo $u_name; ?>'s Image" class="img-circle" style="width:150px; height:150px;">
				<br>
				<?php
					$result = mysqli_query($con, "SELECT * FROM follows WHERE following_id = $u_id AND follower_id = $user_id");

					if (mysqli_num_rows($result)) {
						echo "<a href='/unfollow.php?u_id=$u_id' class='btn btn-danger' style='width: 100%; margin-top: 15px;'><i class='glyphicon glyphicon-remove'></i> Siguiendo</a>";
					}
					else {
						echo "<a href='/follow.php?u_id=$u_id' class='btn btn-info' style='width: 100%; margin-top: 15px;'><i class='glyphicon glyphicon-plus'></i> Seguir</a>";
					}
				?>
			</div>
			<br>
		</div>
	</div>
	<div class="col-sm-2"></div>
</div>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-2" style="background-color: #e6e6e6;left: 0.7%;border-radius: 5px;">
		<?php echo "
			<center><h2><strong>Acerca De</strong></h2></center>
			<center><h4><strong>$u_first $u_last</strong></h4></center>
			<center><p><strong><i style='color:grey;'>$u_describe</i></strong></p><br></center>
			<p><strong>Ubicación: </strong> $u_country</p><br>
			<p><strong>Miembro Desde: </strong> $u_reg_date</p><br>
			<p><strong>Género: </strong> $u_gender</p><br>
			<p><strong>Fecha de Nacimiento: </strong> $u_birthday</p><br>
		";
		?>
	</div>
	<div class="col-sm-6">
		<?php
			$page = 1;

			if (isset($_GET["page"]))
				$page = $_GET["page"];
			
			get_user_posts($u_id, $page);

			include("functions/pagination.php");
			paginate("SELECT * FROM posts WHERE user_id = $u_id", $page);
		?>
	</div>
	<div class="col-sm-2"></div>
</div>
</body>
</html>