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
	<title>Mensajes • ShareYourCanvas</title>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row">
		<div class="col-sm-2"></div>

		<div class="col-sm-8">
			<div class="text-center mb-5">
				<h1><b>Mensajes</b></h1>
				<hr />
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="text-center mb-4">
						<h2>Conversaciones</h2>
					</div>
					<ul class="list-group">
						<?php
							$query = "SELECT * FROM `follows` INNER JOIN `users` ON `users`.`id` = `follows`.`following_id`
								WHERE `follower_id` = " . $user["id"] . " AND `following_id` IN
								(SELECT `follower_id` FROM `follows` WHERE `following_id` = " . $user["id"] . ") ORDER BY `username` ASC";
							$result =	$db->exec(new DB\Stmt($query));

							if ($result->num_rows != 0) {
								while ($row = $result->fetch_assoc()) {
									$id = $row["following_id"];
									$username = $row["username"];
									$fname = $row["first_name"];
									$lname = $row["last_name"];

									$active = (isset($_GET["uid"]) && $_GET["uid"] == $id) ? "active' aria-current='true'" : "'";

									echo "
									<a href='?uid=$id' class='list-group-item list-group-item-action d-flex justify-content-between $active>
										<span>$fname $lname</span>
										<i>$username</i>
									</a>
									";
								}
							}
							else {
								echo "
								<div class='m-auto p-3'>
									<h3 class='text-center' style='color: grey;'>No tienes seguidores mutuos... :(</h3>
								</div>
								";
							}
						?>
					</ul>
				</div>
				<div class="col-sm-8">
					<div class="d-flex bg-light text-dark flex-column rounded w-100 border" style="min-height: 540px;">
						<?php
							include "include/messages.php";
							global $db;
							
							if (isset($_GET["uid"])) {
								$stmt = DB\select(["*"], "users", [ DB\where("id", "=", $_GET["uid"]) ]);
								$result_get = $db->exec($stmt);
								$row_user = $result_get->fetch_assoc();

								$user_id = $row_user["id"];
								$user_uname = $row_user["username"];
								$user_fname = $row_user["first_name"];
								$user_lname = $row_user["last_name"];
								$user_bday = $row_user["birthday"];
								$user_gender = $row_user["gender"];
								$user_bio = $row_user["bio"];

								$age = floor((time() - strtotime($user_bday)) / 31557600) . " años";
								$gender = "";

								if (strlen($user_gender) > 0 && $user_gender != "o") {
									$gender = " • " . strtoupper($user_gender) . " ";

									switch ($user_gender) {
									case "m": $gender .= "<i class='bi bi-gender-male'></i>"; break;
									case "f": $gender .= "<i class='bi bi-gender-female'></i>"; break;
									}
								}

								echo "
								<div class='d-flex m-3 mb-0 align-items-center justify-content-between'>
									<h4><a style='text-decoration: none;' href='/user.php?uid=$user_id'>$user_fname $user_lname</a></h4>
									<q><i> $user_bio </i></q>
									<span>$age$gender</span>
								</div>
								<hr />
								<ul class='list-group list-group-flush' style='display: block; height: 540px; overflow-y: auto;'>
								";

								get_messages($_GET["uid"]);

								echo "
								</ul>
								<hr />
								<form method='POST' class='mx-auto d-flex m-3 align-items-center justify-content-evenly w-75 gap-3'>
									<input class='form-control' name='message' type='text' placeholder='Escribe un mensaje para $user_fname $user_lname...' required />
									<button type='submit' class='btn btn-primary flex-shrink-0'><i class='bi bi-arrow-return-right'></i> Enviar</button>
								</form>
								";
							}
							else {
								echo "
								<div class='m-auto p-3'>
									<h2 class='text-center' style='color: grey;'>¡Selecciona un chat de la lista de seguidores mutuos!</h2>
								</div>
								";
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-2"></div>
	</div>

	<?php include "components/preferences.php"; ?>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_GET["uid"])) exit();

$content = htmlentities(mysqli_real_escape_string($db->conn, $_POST["message"]));

$stmt = DB\insert_into("messages", [
	"content" => $content,
	"sender_id" => $user["id"],
	"receiver_id" => $_GET["uid"]
]);
$result_create = $db->exec($stmt);

echo "<script>window.location.assign(window.location.href)</script>";

?>
