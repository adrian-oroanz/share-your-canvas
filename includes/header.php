<?php
session_start();

if (!isset($_SESSION['user_email']))
	echo "<script>location.replace('/')</script>";

include("includes/connection.php");
include("functions/functions.php");
?>
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="home.php">Share Your Canvas</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">

			<?php 
				$user = $_SESSION['user_email'];
				$get_user = "SELECT * FROM users WHERE user_email='$user'"; 
				$run_user = mysqli_query($con,$get_user);
				$row = mysqli_fetch_array($run_user);

				if (!$row) {
					unset($_SESSION['user_email']);
					echo "<script>window.open('/', '_self')</script>";
					exit();
				}
				
				$user_id = $row['user_id']; 
				$user_name = $row['user_name'];
				$first_name = $row['f_name'];
				$last_name = $row['l_name'];
				$describe_user = $row['describe_user'];
				$Relationship_status = $row['Relationship'];
				$user_pass = $row['user_pass'];
				$user_email = $row['user_email'];
				$user_country = $row['user_country'];
				$user_gender = $row['user_gender'];
				$user_birthday = $row['user_birthday'];
				$user_image = $row['user_image'];
				$user_cover = $row['user_cover'];
				$recovery_account = $row['recovery_account'];
				$register_date = $row['user_reg_date'];
				
				
				$user_posts = "SELECT * FROM posts WHERE user_id='$user_id'"; 
				$run_posts = mysqli_query($con,$user_posts); 
				$posts = mysqli_num_rows($run_posts);
			?>

			<li><a href='profile.php?<?php echo "u_id=$user_id" ?>'><?php echo "$first_name"; ?></a></li>
			<li><a href="home.php">Inicio</a></li>
			<li><a href="members.php">Artistas</a></li>
			<li><a href="explore.php">Explorar</a></li>
			<li><a href='messages.php?<?php echo "u_id=$user_id" ?>'>Mensajes</a></li>

			<!-- Menú Desplegable -->
			<?php
				echo"
				<li class='dropdown'>
					<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><span><i class='glyphicon glyphicon-chevron-down'></i></span></a>
					<ul class='dropdown-menu'>
						<li>
							<a href='my_post.php?u_id=$user_id'>Mi Arte <span class='badge badge-secondary'>$posts</span></a>
						</li>
						<li>
							<a href='edit_profile.php?u_id=$user_id'>Editar Informacion</a>
						</li>
						<li>
							<a href='change_password.php'>Cambiar Contraseña</a>
						</li>
						<li role='separator' class='divider'></li>
						<li>
							<a href='logout.php'>Cerrar Sesion</a>
						</li>
					</ul>
				</li>
				";
			?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<form class="navbar-form navbar-left" method="get" action="results.php">
						<div class="form-group">
							<input type="text" class="form-control" name="user_query" placeholder="Buscar">
						</div>
						<button type="submit" class="btn btn-info" name="search">Buscar</button>
					</form>
				</li>
			</ul>
		</div>
	</div>
</nav>