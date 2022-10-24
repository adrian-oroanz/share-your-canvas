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
	<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="./css/home.css" />
	<title>Intercambio • ShareYourCanvas</title>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<?php include "components/preferences.php"; ?>
	<div class="row">
		<div id="l" class="col-sm-3"></div>

		<div class="col-sm-6">
			<div class="text-center mb-5">
				<h1><b>Intercambios</b></h1>
				<hr />
			</div>
			<?php
				if (isset($_GET["id"])) {
					global $db;

					$stmt = DB\select(["*"], "posts", [ DB\where("id", "=", $_GET["id"]) ]);
					$result_get = $db->exec($stmt);

					if ($result_get->num_rows == 0) {
						echo "<script>window.history.back()</script>";
						exit();
					}

					$row_post = $result_get->fetch_assoc();
					$query = "SELECT `posts`.`id` AS `post_id`, `posts`.*, `users`.* FROM
						`posts` INNER JOIN `users` ON `posts`.`author_id` = `users`.`id`
						WHERE `users`.`id` <> " . $user["id"] . " AND `users`.`exchange` = 1 AND (0";

					$tags = explode(";", $row_post["tags"]);
					$num_tags = count($tags);

					foreach ($tags as $i => $tag) {
						$tag = $tags[$i];
						$query .= " OR LOCATE('$tag', `tags`) <> 0";
					}

					$query .= ") ORDER BY RAND() LIMIT 1";
					$result_find = $db->exec(new DB\Stmt($query));
					
					if ($result_find->num_rows != 0) {
						$row_find = $result_find->fetch_assoc();

						$stmt = DB\select(["*"], "exchanges", [
							DB\where("exchanged_id", "=", $_GET["id"]),
							DB\where("received_id", "=", $row_find["post_id"], true)
						]);
						$result_check = $db->exec($stmt);

						if ($result_check->num_rows == 0) {
							$stmt = DB\insert_into("exchanges", [
								"exchanged_id" => $_GET["id"],
								"received_id" => $row_find["post_id"],
								"initiator_id" => $user["id"],
								"receiver_id" => $row_find["author_id"]
							]);
							$db->exec($stmt);

							$stmt = DB\insert_into("exchanges", [
								"exchanged_id" => $row_find["post_id"],
								"received_id" => $_GET["id"],
								"initiator_id" => $row_find["author_id"],
								"receiver_id" => $user["id"]
							]);
							$db->exec($stmt);

							echo "<div class='text-center mb-3'><h3>Recibiste...</h3></div>";
							get_posts($query, 0);
							echo "<hr class='mb-5' />";
						}
						else {
							echo "
							<div class='text-center mb-5'>
								<h3>No se encontraron posts similares para intercambiar</h3>
								<hr />
							</div>";
						}
					}
					else {
						echo "
						<div class='text-center mb-5'>
							<h3>No se encontraron posts similares para intercambiar</h3>
							<hr />
						</div>";
					}
				}
			?>
			<?php
				$query = "SELECT `posts`.`id` AS `post_id`, `posts`.*, `users`.* FROM ((`exchanges`
					INNER JOIN `posts` ON `exchanges`.`exchanged_id` = `posts`.`id`) INNER JOIN `users`
					ON `posts`.`author_id` = `users`.`id`) WHERE `exchanges`.`receiver_id` = " . $user["id"];
				
				get_posts($query, 5);
			?>
		</div>

		<div class="col-sm-3"></div>
	</div>
</body>
</html>