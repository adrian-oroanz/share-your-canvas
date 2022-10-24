<?php include "include/auth.php"; ?>
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
	<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="css/home.css" />
	<title>Artistas • ShareYourCanvas</title>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row">
		<div class="col-sm-2"></div>

		<div class="col-sm-8">
			<div class="text-center mb-5">
				<h1><b>Encuentra Más Artistas</b></h1>
				<hr>
			</div>
			<div class="row">
				<div class="col-sm-3"></div>

				<div class="col-sm-6 d-flex flex-column gap-3">
					<form method="get" class="d-flex gap-3 mb-5">
						<input type="search" name="search" id="artist-search" class="form-control" placeholder="Busca un artista..." required />
						<button type="submit" class="btn btn-primary">Buscar</button>
					</form>
					<?php
						global $db;

						$id = $user["id"];
						$per_page = 5;
						$page = 1;

						if (isset($_GET["page"]))
							$page = $_GET["page"];

						$from = ($page - 1) * $per_page;
						$query = "SELECT * FROM `users` WHERE `id` <> $id ORDER BY `username` ASC";

						if (isset($_GET["search"]))
							$query = "SELECT * FROM `users` WHERE `username` LIKE '%" . $_GET["search"] . "%' AND `id` <> $id ORDER BY `username` ASC";

						$result = mysqli_query($db->conn, $query);

						while ($row = mysqli_fetch_array($result)) {
							$artist_id = $row["id"];
							$artist_uname = $row["username"];
							$artist_fname = $row["first_name"];
							$artist_lname = $row["last_name"];
							$artist_pfp = $row["avatar_url"];
							$artist_bio = $row["bio"];
							$artist_cover = $row["cover_url"];

							echo "
								<div class='row'>
									<div class='card shadow text-dark p-0'>
										<img src='$artist_cover' alt='$artist_uname&apos;s cover' height='96px' class='rounded-top' style='object-fit: cover;' />
										<div class='card-body row'>
											<div class='col-sm-3'>
												<a href='/user.php?uid=$artist_id'><img src='$artist_pfp' alt='$artist_uname&apos;s avatar' height='96px' width='96px' class='rounded' /></a>
											</div>
											<div class='col-sm-6'>
												<h5 class='card-title'>
													<a href='/user.php?uid=$artist_id' style='text-decoration: none'>$artist_fname $artist_lname<wbr> <i style='opacity: 0.5; font-size: 0.8rem;'>($artist_uname)</i></a>
												</h5>
												<q><i>$artist_bio</i></q>
											</div>
											<div class='col-sm-3 align-self-center'>
												<a href='/user.php?uid=$artist_id' class='btn btn-outline-primary w-100'>Ver Perfil</a>
											</div>
										</div>
									</div>
								</div>
							";
						}
					
					?>

					<?php
						$current_page = 1;

						if (isset($_GET["page"]))
							$current_page = $_GET["page"];

						echo pagination($query, $current_page, 5);
					?>
				</div>

				<div class="col-sm-3"></div>

			</div>
		</div>

		<div class="col-sm-2"></div>
	</div>	

	<?php include "components/preferences.php"; ?>
</body>
</html>