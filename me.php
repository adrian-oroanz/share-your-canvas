<?php include "include/auth.php"; global $user; ?>
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

	<link rel="stylesheet" href="./css/profile.css" />
	<script defer>
		/** @param {string|number} postId */
		function confirmPostDeletion (postId) {
			const response = confirm("¿De verdad quieras eliminar este post?\nEsta acción es permanente, ¡piénsalo bien!");

			if (response)
				window.location.assign(`/delete.php?id=${postId}`);
		}

		/** @param {HTMLInputElement} input */
		function validateFile (input) {
			if (input.files.length === 0) return;

			const file = input.files[0];

			if (file.size > 10485760)
				return alert("¡La imagen es muy grande!\nUtiliza una imagen que sea menor a los 10MB");

			input.form.submit();
		}
	</script>
	<title><?php echo $user["username"]; ?> • ShareYourCanvas</title>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row mb-3">
		<div class="col-sm-2"></div>

		<div class="col-sm-8">
			<div class="container-fluid">
				<div class="w-100 position-relative">
					<img src="<?php echo $user['cover_url']; ?>" alt="Cover Picture" class="rounded w-100 cover" />
					<div class="position-absolute top-0 end-0 m-3">
						<form method="POST" id="me-cover-form" enctype="multipart/form-data">
							<label for="me-cover" class="btn btn-outline-light">Cambiar Portada</label>
							<input type="file" id="me-cover" name="cover" hidden accept="image/jpeg, image/png" onchange="validateFile(this)" />
						</form>
					</div>
					<div class="position-absolute bottom-0 start-0 mx-md-5 m-3">
						<img src="<?php echo $user['avatar_url']; ?>" alt="Profile Picture" class="rounded avatar me-auto">
						<br />
						<form method="POST" id="me-pfp-form" enctype="multipart/form-data">
							<label for="me-pfp" class="btn btn-primary w-100 mt-4">Cambiar Foto</label>
							<input type="file" id="me-pfp" name="avatar" hidden accept="image/jpeg, image/png" onchange="validateFile(this)" />
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-2"></div>
	</div>
	<div class="row">
		<div class="col-sm-2"></div>
		
		<div class="col-sm-2 mx-md-4 p-0">
			<div class="bg-light text-dark py-3 px-1 rounded shadow">
				<?php
					$fname = $user["first_name"];
					$lname = $user["last_name"];
					$bio = $user["bio"];
					$country = $user["country"];
					$gender = $user["gender"];
					$registered = date("r", strtotime($user["registered_time"]));
					$bday = date("j M Y", strtotime($user["birthday"]));;

					echo "
						<h2 class='text-center mb-3'><b>Acerca De</b></h2>
						<h3 class='text-center'>$fname $lname</h3>
						<br  />
						<center><a href='/edit.php' class='btn btn-outline-secondary w-75'>Editar</a></center>
						<br>
						<q class='my-2 text-center d-block' style='color: grey;'><i>
							$bio
						</i></q>
						<br>
					";

					if (strlen($gender)) {
						switch ($gender) {
						case "m": $gender = "Masculino";
							break;
						case "f": $gender = "Femenino";
							break;
						default: $gender = "Otro";
							break;
						}

						echo "<div class='mb-3 px-2'><b>Género</b> <p class='float-end'>$gender</p></div>";
					}

					if (strlen($country)) {
						switch ($country) {
						case "co": $country = "Colombia";
							break;
						case "es": $country = "España";
							break;
						case "mx": $country = "México";
							break;
						case "pe": $country = "Perú";
							break;
						case "us": $country = "Estados Unidos";
							break;
						default: $country = "???";
							break;
						}

						echo "<div class='mb-3 px-2'><b>Ubicación</b> <p class='float-end'>$country</p></div>";
					}

					echo "
						<div class='mb-3 px-2'><b>Cumpleaños</b> <p class='float-end'>$bday</p></div>
						<br>
						<p class='text-center' style='color: grey'><i>$registered</i></p>
					";
				?>
			</div>
		</div>
		<div class="col-sm-6 d-flex flex-column align-items-center gap-3">
			<div class="text-center mb-3 w-100">
				<h1><b>Galería</b></h1>
			</div>
			<form method="get" class="d-flex gap-3 mb-5 w-75">
				<input type="search" name="q" id="me-search" class="form-control" placeholder="Buscar publicaciones por su descripción o #tags" />
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

					$query = "SELECT `posts`.`id` AS `post_id`, `posts`.*, `users`.* FROM `posts`
						INNER JOIN `users` ON `posts`.`author_id` = `users`.`id` WHERE `users`.`id` = " . $user["id"];

					if (strlen($description) > 0)
						$query .= " AND `description` LIKE '%$description%'";

					$num_tags = count($tags);

					if ($num_tags > 0) {
						$query = substr($query, 7);

						foreach ($tags as $i => $tag) {
							$tag = $tags[$i];
							$query = "LOCATE('$tag', `tags`) AS `tag_$i`, $query";									
						}

						$query = "SELECT $query";
					}

					get_posts($query . " ORDER BY `created_date` DESC", 5);
				}
				else {
					get_posts(
						"SELECT `posts`.`id` AS `post_id`, `posts`.*, `users`.*
						FROM `posts` INNER JOIN `users` ON `posts`.`author_id` = `users`.`id`
						WHERE `posts`.`author_id` = " . $user["id"] . " ORDER BY `created_date` DESC",
						5
					);
				}
			?>
		</div>

		<div class="col-sm-2"></div>
	</div>

	<?php include "components/preferences.php"; ?>
</body>
</html>
<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") exit();
if (!isset($_FILES) || count($_FILES) == 0) exit();

global $user;
global $db;

$id = $user["id"];

if (!file_exists("cdn/users/$id"))
	mkdir("cdn/users/$id");

if (key_exists("avatar", $_FILES)) {
	$avatar = $_FILES["avatar"];
	$path = "cdn/users/$id/avatar";

	if (move_uploaded_file($avatar["tmp_name"], $path)) {
		$stmt = DB\update("users", [ "avatar_url" => $path ], [ DB\where("id", "=", $id) ]);
		$result = $db->exec($stmt);

		if ($result) {
			echo "<script>alert('¡Tu foto de perfil se actualizó exitosamente!')</script>";
			echo "<script>window.location.assign('/me.php')</script>";
			exit();
		}
	}
}
elseif (key_exists("cover", $_FILES)) {
	$cover = $_FILES["cover"];
	$path = "cdn/users/$id/cover";

	if (move_uploaded_file($cover["tmp_name"], $path)) {
		$stmt = DB\update("users", [ "cover_url" => $path ], [ DB\where("id", "=", $id) ]);
		$result = $db->exec($stmt);

		if ($result) {
			echo "<script>alert('¡Tu foto de portada se actualizó exitosamente!')</script>";
			echo "<script>window.location.assign('/me.php')</script>";
			exit();
		}
	}
}

?>
