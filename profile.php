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
	<title><?php echo "$user_name"; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style/home_style2.css">
	<link rel="stylesheet" type="text/css" href="style/profile_style.css" />
</head>
<style>
	#update_profile{
		position: relative;
		top: -33px;
		cursor: pointer;
		left: 93px;
		border-radius: 4px;
		background-color: rgba(0,0,0,0.1);
		transform: translate(-50%, -50%);
	}
	#button_profile{
		position: absolute;
		top: 82%;
		left: 50%;
		cursor: pointer;
		transform: translate(-50%, -50%);
	}
	#own_posts{
		border: 5px solid #e6e6e6;
		padding: 40px 50px;
	}
</style>
<body>
<script>
	/**
	 * @param {number | string} postId
	 */
	function confirmDeletion(postId) {
		const res = confirm("¿Estas seguro que deseas eliminar este post?\n¡Esta accion es permanente!");
		
		if (res)
			window.open(`functions/delete_post.php?post_id=${postId}`, "_self");
	}
</script>
<div class="row">
	<div class="col-sm-2">	
	</div>
	<div class="col-sm-8">
		<div>
			<div><img id="cover-img" class="img-rounded" src="cover/<?php echo $user_cover; ?>" alt="cover"></div>
			<form action="profile.php?u_id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">

			<ul class="nav pull-left" style="position:absolute;top:10px;left:40px;">
				<li class="dropdown">
					<button class="dropdown-toggle btn btn-default" data-toggle="dropdown">Cambiar Portada</button>
					<div class="dropdown-menu">
						<center>
						<p>Sube una imagen en <strong>Seleccionar Portada</strong> y después <strong>Actualizar Portada</strong></p>
						<label class="btn btn-info"> Seleccionar Portada
						<input type="file" name="u_cover" size="60" />
						</label><br><br>
						<button name="submit" class="btn btn-info">Actualizar Portada</button>
						</center>
					</div>
				</li>
			</ul>

			</form>
		</div>
		<div id="profile-img">
			<img src="users/<?php echo $user_image; ?>" alt="Profile" class="img-circle" width="180px" height="185px">
			<form action="profile.php?u_id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">

			<label id="update_profile"> Cambiar Foto
			<input type="file" name="u_image" size="60" />
			</label><br><br>
			<button id="button_profile" name="update" class="btn btn-info">Actualizar Foto</button>
			</form>
		</div><br>
		<?php

			if(isset($_POST['submit'])){

				$u_cover = $_FILES['u_cover']['name'];
				$image_tmp = $_FILES['u_cover']['tmp_name'];
				$random_number = rand(1,100);

				if ($u_cover == '') {
					echo "<script>alert('Selecciona una imagen de portada usando el botón \"Seleccionar Portada\".')</script>";
					echo "<script>window.open('profile.php?u_id=$user_id' , '_self')</script>";

					exit();
				}
				else {
					move_uploaded_file($image_tmp, "cover/$u_cover.$random_number");
					$update = "update users set user_cover='$u_cover.$random_number' where user_id='$user_id'";

					$run = mysqli_query($con, $update);

					if($run){
					echo "<script>alert('Se actualizó tu foto de portada.')</script>";
					echo "<script>window.open('profile.php?u_id=$user_id' , '_self')</script>";
					}
				}

			}

		?>
	</div>


	<?php
		if (isset($_POST['update'])) {

				$u_image = $_FILES['u_image']['name'];
				$image_tmp = $_FILES['u_image']['tmp_name'];
				$random_number = rand(1,100);

				if ($u_image == ''){
					echo "<script>alert('Selecciona una imagen usando el botón de \"Cambiar Foto\".')</script>";
					echo "<script>window.open('profile.php?u_id=$user_id' , '_self')</script>";
					exit();
				}
				else {
					move_uploaded_file($image_tmp, "users/$u_image.$random_number");
					$update = "update users set user_image='$u_image.$random_number' where user_id='$user_id'";

					$run = mysqli_query($con, $update);

					if($run){
					echo "<script>alert('Your Profile Updated')</script>";
					echo "<script>window.open('profile.php?u_id=$user_id' , '_self')</script>";
					}
				}

			}
	?>
	<div class="col-sm-2">
	</div>
</div>
<div class="row">
	<div class="col-sm-2">
	</div>
	<div class="col-sm-2" style="background-color: #e6e6e6;left: 0.7%;border-radius: 5px;">
		<?php
		echo"
			<center><h2><strong>Acerca De</strong></h2></center>
			<center><h4><strong>$first_name $last_name</strong></h4></center>
			<center><p><strong><i style='color:grey;'>$describe_user</i></strong></p><br></center>
			<p><strong>Ubicación: </strong> $user_country</p><br>
			<p><strong>Miembro Desde: </strong> $register_date</p><br>
			<p><strong>Género: </strong> $user_gender</p><br>
			<p><strong>Fecha de Nacimiento: </strong> $user_birthday</p><br>
		";
		?>
	</div>
	<div class="col-sm-6">
		<!--DISPLAY USERS POSTS-->
		<?php 
		$page = 1;

		if (isset($_GET["page"]))
			$page = $_GET["page"];

		get_user_posts($user_id, $page);

		include("functions/delete_post.php");
		include("functions/pagination.php");

		paginate("SELECT * FROM posts WHERE user_id = $user_id", $page);
		?>
	</div>
	<div class='col-sm-2'>
	</div>
</div>
</body>
</html>