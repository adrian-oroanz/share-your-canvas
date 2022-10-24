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

	<link rel="stylesheet" href="css/home.css" />
	<title>Editar Información • ShareYourCanvas</title>
</head>
<body>
	<?php include "components/navbar.php"; ?>
	<div class="row">
		<div class="col-sm-3"></div>

		<div class="col-sm-6">
			<div class="text-center mb-5">
				<h1><b>Editar Información</b></h1>
				<hr />
			</div>
			<form method="post" class="mb-3" id="edit-form" onsubmit="return validateForm()">
				<div class="row g-2 mb-3">
					<div class="col-sm">
						<label for="edit-username" class="form-label">Nombre de Usuario</label>
						<input type="text"
							name="username"
							class="form-control"
							id="edit-username"
							pattern="^(?!.*\.\.)(?!.*\.$)[^\W][\w]{3,29}$"
							placeholder="cookie_lover_3000"
							aria-describedby="edit-username-help"
							value="<?php echo $user['username'] ?>"
							required />
						<div class="form-text" id="edit-username-help">
							El nombre de usuario debe tener un mínimo de 4 caracteres y un máximo de 28 caracteres.
							Solo puede contener caracteres alfanuméricos y guion bajo.
						</div>
					</div>
					<div class="col-sm">
						<label for="edit-email" class="form-label">Correo Electrónico</label>
						<input type="email"
							name="email"
							id="edit-email"
							class="form-control"
							placeholder="name@example.com"
							aria-describedby="edit-email-help"
							value="<?php echo $user['email']; ?>"
							required />
						<div class="form-text" id="edit-email-help">
							Cambiar tu correo electrónico también cambiara la forma en la que inicias sesión.
						</div>
					</div>
				</div>
				<div class="row g-2 mb-3">
					<div class="col-sm">
						<label for="edit-fname" class="form-label">Nombre(s)</label>
						<input type="text" name="fname" class="form-control" id="edit-fname" placeholder="John" value="<?php echo $user['first_name']; ?>" required />
					</div>
					<div class="col-sm">
						<label for="edit-lname" class="form-label">Apellido(s)</label>
						<input type="text" name="lname" class="form-control" id="edit-lname" placeholder="Doe" value="<?php echo $user['last_name']; ?>" required />
					</div>
				</div>
				<div class="mb-3">
					<label for="edit-bio" class="form-label">Biografía</label>
					<textarea name="bio" id="edit-bio" rows="2" class="form-control" maxlength="255" placeholder="Hola, ¡me gusta el arte!" aria-describedby="edit-bio-help"><?php echo $user['bio']; ?></textarea>
					<div class="form-text" id="edit-bio-help">
						Una breve descripción de quien eres puede ayudar a otros a identificarte, puedes incluir links a otras redes sociales.
					</div>
				</div>
				<div class="row g-3 mb-3">
					<div class="col-sm">
						<label for="edit-bday" class="form-label">Fecha de Nacimiento</label>
						<input type="date" name="bday" id="edit-bday" class="form-control" value="<?php echo $user['birthday']; ?>" required />
					</div>
					<div class="col-sm">
						<label for="edit-gender" class="form-label">Género</label>
						<select name="gender" id="edit-gender" class="form-select" required aria-describedby="edit-gender-help">
							<option value="none" disabled>Selecciona una opción</option>
							<?php
								$genders = array("dnd" => "Sin especificar", "m" => "Masculino", "f" => "Femenino", "o" => "Otro");

								foreach ($genders as $k => $gender) {
									if ($k == $user["gender"])
										echo "<option value='$k' selected>$gender</option>";
									else
										echo "<option value='$k'>$gender</option>";
								}
							?>
						</select>
						<div class="form-text" id="edit-gender-help">
							Si no quieres mostrar tu género puedes seleccionar la opción "Sin especificar".
						</div>
					</div>
					<div class="col-sm">
						<label for="edit-country" class="form-label">País de Residencia</label>
						<select name="country" id="edit-country" class="form-select" required aria-describedby="edit-country-help">
							<option value="none" disabled>Selecciona una opción</option>
							<?php
								$countries = array("dnd" => "Sin especificar", "co" => "Colombia", "es" => "España", "mx" => "México", "pe" => "Perú", "us" => "Estados Unidos");

								foreach ($countries as $k => $country) {
									if ($k == $user["country"])
										echo "<option value='$k' selected>$country</option>";
									else
										echo "<option value='$k'>$country</option>";
								}
							?>
						</select>
						<div class="form-text" id="edit-country-help">
							Si no quieres mostrar tu país de residencia puedes seleccionar la opción "Sin especificar".
						</div>
					</div>
				</div>
				<div class="mb-3 form-check">
					<input type="checkbox" name="exchange" id="edit-exchange" <?php echo $user["exchange"] ? "checked" : "" ?> class="form-check-input" />
					<label for="edit-exchange" class="form-check-label">¿Permitir intercambios?</label>
				</div>
				<center><button type="submit" class="btn btn-primary btn-lg mt-3 w-75">Actualizar</button></center>
			</form>
		</div>

		<div class="col-sm-3"></div>
	</div>

	<?php include "components/preferences.php"; ?>
</body>
<script defer>
	function validateForm () {
		const formData = new FormData(document.getElementById("edit-form"));

		$("*[required]").addClass("is-valid");
		$("textarea").addClass("is-valid");

		if (!formData.has("gender") || !formData.has("country")) return false;

		return true;
	}
</script>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] != "POST") exit();

global $db;

$uname = htmlentities(mysqli_real_escape_string($db->conn, $_POST["username"]));
$fname = htmlentities(mysqli_real_escape_string($db->conn, $_POST["fname"]));
$lname = htmlentities(mysqli_real_escape_string($db->conn, $_POST["lname"]));
$bio = htmlentities(mysqli_real_escape_string($db->conn, $_POST["bio"]));
$bday = htmlentities(mysqli_real_escape_string($db->conn, $_POST["bday"]));
$gender = htmlentities(mysqli_real_escape_string($db->conn, $_POST["gender"]));
$country = htmlentities(mysqli_real_escape_string($db->conn, $_POST["country"]));
$exchange = isset($_POST["exchange"]) ? 1 : 0;

$stmt = DB\select(["*"], "users", [ DB\where("username", "=", $uname) ]);
$result_get_uname = $db->exec($stmt);

if ($result_get_uname->num_rows != 0 && $uname != $user["username"]) {
	echo "<script>alert('El nombre de usuario proporcionado ya esta en uso, utiliza otro')</script>";
	exit();
}

$stmt = DB\select(["*"], "users", [ DB\where("email", "=", $email) ]);
$result_get_email = $db->exec($stmt);

if ($result_get_email->num_rows != 0 && $email != $user["email"]) {
	echo "<script>alert('El correo electrónico proporcionado ya esta en uso, utiliza otro')</script>";
	exit();
}

$stmt = DB\update("users", [
	"username" => $uname,
	"first_name" => $fname,
	"last_name" => $lname,
	"bio" => $bio,
	"birthday" => $bday,
	"gender" => $gender,
	"country" => $country,
	"exchange" => $exchange
], [ DB\where("id", "=", $user["id"]) ]);
$result_update = $db->exec($stmt);

if ($result_update) {
	$_SESSION["user"] = $email;
	echo "<script>alert('Se ha actualizado tu información exitosamente')</script>";
	echo "<script>window.location.assign('/home.php?d=recent')</script>";
	exit();
}

echo "<script>alert('Ocurrió un error al actualizar la información, inténtalo de nuevo')</script>";
?>
