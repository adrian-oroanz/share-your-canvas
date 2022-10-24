<?php include "include/auth.php"; global $user; ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php

	if (!isset($_GET["uid"])) {
		echo "<script>window.history.back()</script>";
		exit();
	}

	$uid = $_GET["uid"];

	if ($uid == $user["id"]) {
		echo "<script>window.location.replace('/me.php')</script>";
		exit();
	}

	global $db;
	$stmt = DB\select(["*"], "users", [ DB\where("id", "=", $uid) ]);
	$result = $db->exec($stmt);

	if ($result->num_rows == 0) {
		echo "<script>window.history.back()</script>";
		exit();
	}

	$other_user = $result->fetch_assoc();

	?>
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
	<title><?php echo $other_user["username"]; ?> • ShareYourCanvas</title>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row mb-3">
		<div class="col-sm-2"></div>

		<div class="col-sm-8">
			<div class="container-fluid">
				<div class="w-100 position-relative">
					<img src="<?php echo $other_user['cover_url']; ?>" alt="Cover Picture" class="rounded w-100 cover" />
					<div class="position-absolute bottom-0 start-0 mx-md-5 m-3">
						<img src="<?php echo $other_user['avatar_url']; ?>" alt="Profile Picture" class="rounded avatar me-auto">
						<br>
						<form action="" method="POST">
							<?php
								$stmt = DB\select(["*"], "follows", [
									DB\where("following_id", "=", $uid),
									DB\where("follower_id", "=", $user["id"], true)
								]);
								$result = $db->exec($stmt);

								if ($result->num_rows)
									echo "<button class='btn btn-danger w-100 mt-3'><i class='bi bi-x'></i> Dejar de Seguir</button>";
								else
									echo "<button class='btn btn-primary w-100 mt-3'><i class='bi bi-plus'></i> Seguir</button>";
							?>
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
			<div class="bg-light text-dark py-3 px-1 rounded">
				<?php
					$fname = $other_user["first_name"];
					$lname = $other_user["last_name"];
					$bio = $other_user["bio"];
					$country = $other_user["country"];
					$gender = $other_user["gender"];
					$registered = date("r", strtotime($other_user["registered_time"]));
					$bday = date("j M Y", strtotime($other_user["birthday"]));;

					echo "
						<h2 class='text-center mb-3'><b>Acerca De</b></h2>
						<h3 class='text-center'>$fname $lname</h3>
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
			<?php
				get_posts(
					"SELECT `posts`.`id` AS `post_id`, `posts`.*, `users`.*
					FROM `posts` INNER JOIN `users` ON `posts`.`author_id` = `users`.`id`
					WHERE `posts`.`author_id` = " . $_GET["uid"],
					5
				);
			?>
		</div>

		<div class="col-sm-2"></div>
	</div>

	<?php include "components/preferences.php"; ?>
</body>
</html>
<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") exit();

$stmt = DB\select(["*"], "follows", [
	DB\where("follower_id", "=", $user["id"]),
	DB\where("following_id", "=", $uid, true)
]);
$result = $db->exec($stmt);

if ($result->num_rows) {
	$stmt = DB\delete("follows", [ DB\where("id", "=", $result->fetch_assoc()["id"]) ]);
	$db->exec($stmt);

	echo "<script>window.history.back()</script>";
	exit();
}

$stmt = DB\insert_into("follows", [
	"follower_id" => $user["id"],
	"following_id" => $uid
]);
$db->exec($stmt);

echo "<script>window.history.back()</script>";

?>
