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
	<title>Explorar • ShareYourCanvas</title>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row">
		<div id="l" class="col-sm-3"></div>

		<div class="col-sm-6">
			<div class="text-center mb-5">
				<h1><b>Explorar</b></h1>
				<hr />
			</div>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10 d-flex flex-column gap-3">
					<form method="GET" class="d-flex gap-3 mb-5" role="search">
						<input type="search" name="q" id="explore-search" class="form-control" placeholder="Busca publicaciones por su descripción o #tags" />
						<button type="submit" class="btn btn-primary">Buscar</button>
					</form>
					<?php
						if (isset($_GET["q"]) && strlen($_GET["q"])) {
							echo "
							<div class='text-center mb-3'>
								<h3>Búsqueda: <i>" . $_GET['q'] . "</i></h3>
							</div>
							";

							$search = htmlentities($_GET["q"]);
							$regex = "/\B#([a-z0-9_]{2,31})(?![~!@#$%^&*()=+_`\-\|\/'\[\]\{\}]|[?.,]*\w)/i";
							$tags = array_unique(preg_grep($regex, explode(" ", $search)));
							$description = join(" ", preg_grep($regex, explode(" ", $search), PREG_GREP_INVERT));

							$query = "SELECT COUNT(`likes`.`id`) AS `num_likes`, `posts`.`id` AS `post_id`,
								`posts`.*, `users`.* FROM ((`posts`
								INNER JOIN `users` ON `posts`.`author_id` = `users`.`id`)
								INNER JOIN `likes` ON `likes`.`post_id` = `posts`.`id`)
								WHERE `users`.`id` <> " . $user["id"];

							if (strlen($description) > 0)
								$query .= " AND `description` LIKE '%$description%'";

							$num_tags = count($tags);

							if ($num_tags > 0) {
								$query .= " AND (0 ";

								foreach ($tags as $i => $tag) {
									$tag = $tags[$i];
									$query .= " OR LOCATE('$tag', `tags`) <> 0";
								}

								$query .= ")";
							}

							get_posts($query . " GROUP BY `posts`.`id` ORDER BY `num_likes` DESC", 4);
						}
						else {
							$query = "SELECT`posts`.`id` AS `post_id`, `posts`.*, `users`.* FROM `posts`
								INNER JOIN `users` ON `posts`.`author_id` = `users`.`id` WHERE `users`.`id` <> " . $user["id"];

							get_posts($query . " ORDER BY RAND()", 4);
						}
					?>

				</div>
				<div class="col-sm-1"></div>
			</div>
		</div>

		<div class="col-sm-3"></div>
	</div>

	<?php include "components/preferences.php"; ?>
</body>
</html>