<?php include "include/auth.php"; global $user; ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php
		if (!isset($_GET["d"])) {
			echo "<script>window.location.replace('/home.php?d=recent')</script>";
			exit();
		}
	?>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous" />
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="./css/home.css" />
	<title>ShareYourCanvas</title>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row">
		<div id="l" class="col-sm-3"></div>

		<div class="col-sm-6">
			<ul class="nav nav-pills mb-5">
				<li class="nav-item">
					<a href="?d=recommended" class="nav-link <?php echo $_GET['d'] == 'recommended' ? 'active' : '' ?>">Para Tí</a>
				</li>
				<li class="nav-item">
					<a href="?d=recent" class="nav-link <?php echo $_GET['d'] == 'recent' ? 'active' : '' ?>">Reciente</a>
				</li>
			</ul>
			<h1 class="text-center"><strong><?php echo $_GET["d"] == "recent" ? "Lo Más Reciente" : "Para Tí" ?></strong></h1>
			<hr>
			<div class="d-flex flex-column gap-3 mt-5">
				<?php
					$display = $_GET["d"];

					if ($display == "recent") {
						get_posts(
							"SELECT `posts`.`id` AS `post_id`, `posts`.*, `users`.*
							FROM `posts` INNER JOIN `users` ON `posts`.`author_id` = `users`.`id`
							WHERE `users`.`id` <> " . $user["id"] . " ORDER BY `created_date` DESC",
							4
						);
					}
					else
						get_posts(
							"SELECT `posts`.`id` AS `post_id`, `posts`.*, `users`.*
							FROM `posts` INNER JOIN `users` ON `posts`.`author_id` = `users`.`id`
							WHERE `posts`.`author_id` IN (SELECT `following_id` FROM `follows`
							WHERE `follower_id` = " . $user["id"] . ") ORDER BY `created_date` DESC",
							4
						);
				?>
			</div>
		</div>

		<div class="col-sm-3"></div>
	</div>

	<?php include "components/preferences.php"; ?>
</body>
</html>
