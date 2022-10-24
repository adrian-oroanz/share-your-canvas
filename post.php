<?php include "include/auth.php"; global $user; ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php
		if (!isset($_GET["id"])) {
			echo "<script>window.location.replace('/home.php')</script>";
			exit();
		}

		global $db;

		$query = "SELECT `posts`.`id` AS `post_id`, `posts`.*, `users`.* FROM `posts` INNER JOIN `users`
		ON `posts`.`author_id` = `users`.`id` WHERE `posts`.`id` = " . $_GET["id"];
		$result_post = $db->exec(new DB\Stmt($query));

		if ($result_post->num_rows == 0) {
			echo "<script>window.location.replace('/home.php')</script>";
			exit();
		}

		$post = $result_post->fetch_assoc();
	?>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous" />
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="css/home.css" />
	<title>Publicación de <?php echo $post["username"]; ?> • ShareYourCanvas</title>

	<script defer>
		/** @param {number|string} commentId */
		function confirmCommentDeletion (commentId) {
			const res = confirm("¿De verdad quieres eliminar tu comentario?\nEsta acción es permanente, ¡piénsalo bien!");

			if (res)
				window.location.assign(`/delete_comment.php?id=${commentId}`);
		}
	</script>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row">
		<div class="col-sm-3"></div>

		<div class="col-sm-6">
			<h1 class="text-center"><strong>Publicación</strong></h1>
			<hr>

			<div class="mt-5">
				<?php
					get_posts(
						"SELECT `posts`.`id` AS `post_id`, `posts`.*, `users`.*
						FROM `posts` INNER JOIN `users` ON `posts`.`author_id` = `users`.`id`
						WHERE `posts`.`id` = " . $post["post_id"],
						0
					);
				?>
			</div>

			<div class="my-5">
				<h2>Comentarios</h2>
				<hr>
				<form class="d-flex mb-3" method="POST">
					<textarea name="comment" id="post-comment" rows="1" class="form-control" placeholder="Escribe un comentario..." maxlength="255"></textarea>
					<button type="submit" class="btn btn-primary m-3">Comentar</button>
				</form>
				<br />
				<div class="row">
					<div class="col-sm-1"></div>
					<div class="col-sm-10">
						<div class="d-flex flex-column gap-3">
							<?php
								$current_page = 1;

								if (isset($_GET["page"]))
									$current_page = $_GET["page"];

								$from = ($current_page - 1) * 5;

								$query = "SELECT `comments`.`id` AS `comment_id`, `users`.*, `comments`.* FROM `comments`
								INNER JOIN `users` ON `comments`.`author_id` = `users`.`id` WHERE `post_id` = " .
								$_GET["id"] . " ORDER BY `created_date` DESC LIMIT $from, 5
								";
								$result_comments = $db->exec(new DB\Stmt($query));

								if ($result_comments->num_rows > 0) {
									while ($row_comment = $result_comments->fetch_assoc()) {
										$comment_id = $row_comment["comment_id"];
										$comment_content = $row_comment["content"];
										$comment_date = $row_comment["created_date"];
										$author_avatar = $row_comment["avatar_url"];
										$author_id = $row_comment["author_id"];
										$author_uname = $row_comment["username"];
										$author_fname = $row_comment["first_name"];
										$author_lname = $row_comment["last_name"];

										$btn_delete = ($user["id"] == $author_id) ? "<button onclick='confirmCommentDeletion($comment_id)' class='btn btn-outline-danger float-end'><i class='bi bi-trash-fill'></i> Eliminar</button>" : "";

										echo "
										<div class='row shadow bg-light text-dark p-3 rounded'>
											<div class='col-sm-2'>
												<img src='$author_avatar' class='rounded w-100' style='object-fit: cover;' />
											</div>
											<div class='col-sm-8'>
												<div class='mb-4'>
													<h5><a href='/user.php?uid=$author_id' style='text-decoration: none'>$author_fname $author_lname<wbr> <i style='opacity: 0.5; font-size: 0.8rem;'>($author_uname)</i></a></h4>
													<i style='color: grey'>Comentó: $comment_date</i>
												</div>
												<p>$comment_content</p>
											</div>
											<div class='col-sm-2'>
												$btn_delete
											</div>
										</div>
										";
									}

									echo pagination(
										"SELECT * FROM `comments` INNER JOIN `users` ON `comments`.`author_id` = `users`.`id` WHERE `post_id` = " . $_GET["id"],
										$current_page,
										5
									);
								}
								else
									echo "<div class='my-5 text-center'><h3 style='color: grey;'>No hay comentarios...</h3></div>";
							?>
						</div>
					</div>
					<div class="col-sm-1"></div>
				</div>
			</div>
		</div>

		<div class="col-sm-3"></div>
	</div>

	<?php include "components/preferences.php"; ?>
</body>
</html>
<?php 

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["comment"])) exit();

global $db;
global $user;

$comment = htmlentities(mysqli_real_escape_string($db->conn, $_POST["comment"]));

$stmt = DB\insert_into("comments", [
	"author_id" => $user["id"],
	"post_id" => $_GET["id"],
	"content" => $comment,
]);
$db->exec($stmt);

echo "<script>window.location.assign(window.location.href)</script>";

?>
