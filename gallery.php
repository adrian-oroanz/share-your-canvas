<?php include "include/auth.php"; global $user; ?>
<!DOCTYPE html>
<html lang="es">
<head>
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
	<title>Galería • ShareYourCanvas</title>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row">
		<div class="col-sm-2"></div>

		<div class="col-sm-8">
			<div class="text-center mb-5">
				<h1><b>Galería</b></h1>
				<hr />
			</div>
			<div class="container d-flex flex-column gap-3">
				<?php
					global $db;

					$query = "SELECT `url` FROM `images` INNER JOIN `posts` ON `images`.`post_id` = `posts`.`id`
						WHERE `images`.`post_id` IN (SELECT `id` FROM `posts` WHERE `posts`.`author_id` = " . $user["id"] . ") 
						ORDER BY `created_date` DESC";
					$result_get = $db->exec(new DB\Stmt($query));
					$num_imgs = $result_get->num_rows;

					$per_row = 3;
					$item_span = round(12 / $per_row);

					if ($num_imgs != 0) {
						for ($i = 0, $row = $result_get->fetch_assoc(); $i < $num_imgs; $i++, $row = $result_get->fetch_assoc()) {
							$url = $row["url"];

							if ($i % $per_row == 0) echo "<div class='row'>";

							echo "
							<div class='col-sm-$item_span'>
								<a href='$url'><img src='$url' class='rounded w-100' style='object-fit: cover' /></a>
							</div>
							";

							if (($i + 1) % $per_row == 0) echo "</div>";
						}
					}
					else {
						echo "
						<div class='text-center'>
							<h3 style='color: grey;'>No tienes publicaciones...</h3>
						</div>
						";
					}
				?>
			</div>
		</div>

		<div class="col-sm-2"></div>
	</div>
	
	<?php include "components/preferences.php"; ?>
</body>
</html>