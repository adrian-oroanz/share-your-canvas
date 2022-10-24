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

	<title>Top 10 • ShareYourCanvas</title>
</head>
<style>
	body {
		overflow-x: hidden;
	}
</style>
<body>
	<?php include "components/navbar.php"; ?>
	<?php include "components/preferences.php"; ?>
	<div class="row">
		<div class="col-sm-3"></div>

		<div class="col-sm-6">
			<div class="text-center mb-5">
				<h1><b>Top 10</b></h1>
				<hr />
			</div>

			<div class="d-flex flex-column gap-3">
				<?php
					global $db;

					$current_date = date("Y-M-D H:i:s", time() - 604800);

					$query = "SELECT COUNT(`likes`.`id`) AS `num_likes`, `posts`.`id` AS `post_id`, `posts`.*, `users`.*
					FROM ((`posts` INNER JOIN `users` ON `posts`.`author_id` = `users`.`id`)
					INNER JOIN `likes` ON `likes`.`post_id` = `posts`.`id`) WHERE `posts`.`created_date` BETWEEN '$current_date' AND CURTIME()
					GROUP BY `posts`.`id` ORDER BY `num_likes` DESC, `created_date` ASC";
					$result_posts = $db->exec(new DB\Stmt($query));
					$num_posts = $result_posts->num_rows;

					for ($i = 1, $row_post = $result_posts->fetch_assoc(); $i <= $num_posts; $i++, $row_post = $result_posts->fetch_assoc()) {
						if (!isset($row_post["id"])) {
							echo "<div class='my-5 text-center'><h3 style='color: grey;'>Nada que mostrar aquí...</h3></div>";
							return;
						}

						$num_likes = $row_post["num_likes"];
						$post_id = $row_post["post_id"];
						$post_desc = $row_post["description"];
						$post_tags = join(" ", explode(";", $row_post["tags"]));
						$author_id = $row_post["author_id"];
						$author_uname = $row_post["username"];
						$author_pfp = $row_post["avatar_url"];

						$desc = substr($post_desc, 0, 32);

						if ($desc != $post_desc) $desc .= "...";

						echo "
						<div class='row w-100'>
							<div class='col-sm-1'></div>
							<div class='col-sm-2 d-flex flex-column align-items-center justify-content-center'>
								<h2>#$i</h2>
								<button class='btn btn-danger disabled' disabled><i class='bi bi-heart-fill'> $num_likes</i></button>
							</div>
							<div class='col-sm-8 card shadow p-0'>
								<div class='card-body row'>
									<div class='col-sm-2'>
										<img src='$author_pfp' alt='$author_uname&apos;s avatar' class='rounded w-100' />
									</div>
									<div class='col-sm-7'>
										<h5><a href='/user.php?uid=$author_id' style='text-decoration: none;'>$author_uname</a></h5>
										<q><i> $desc </i></q>
										<p style='color: grey' class='mt-2'>$post_tags</p>
									</div>
									<div class='col-sm-3 align-self-center'>
										<a href='/post.php?id=$post_id' class='btn btn-outline-primary'>Ir a la Publicación</a>
									</div>
								</div>
							</div>
							<div class='col-sm-1'></div>
						</div>
						";
					}
				?>
			</div>
		</div>

		<div class="col-sm-3"></div>
	</div>
</body>
</html>