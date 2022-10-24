<?php
include "functions.php";


echo "
<script defer>
	function confirmPostDeletion (postId) {
		const response = confirm('¿De verdad quieras eliminar este post?\\nEsta acción es permanente, ¡piénsalo bien!');

		if (response)
			window.location.assign(`delete.php?id=\${postId}`);
	}

	function confirmExchange (postId) {
		const response = confirm('Recibirás un post similar a este y el autor de dicho post recibirá el tuyo, ¿quieres continuar?');

		if (response)
			window.location.assign(`exchange.php?id=\${postId}`);
	}
</script>
";


/**
 * Inserts a new post in the database.
 */
function create_post (): void
{
	if ($_SERVER["REQUEST_METHOD"] != "POST" || !key_exists("content", $_POST)) return;
	if (!isset($_FILES) || count($_FILES) == 0) return;

	if (count($_FILES["images"]["name"]) > 4) {
		echo "<script>alert('Un post solo puede contener un máximo de 4 imágenes')</script>";
		return;
	}

	global $db;
	global $user;

	$content = htmlentities($_POST["content"]);
	$regex = "/\B#([a-z0-9_]{2,31})(?![~!@#$%^&*()=+_`\-\|\/'\[\]\{\}]|[?.,]*\w)/i";
	$tags = array_unique(preg_grep($regex, explode(" ", $content)));
	$description = join(" ", preg_grep($regex, explode(" ", $content), PREG_GREP_INVERT));

	if (strlen($description) == 0) {
		echo "<script>alert('¡Dale una descripción a tu post!')</script>";
		return;
	}

	if (count($tags) < 3 || count($tags) > 5) {
		echo "<script>alert('Un post debe tener mínimo 3 tags y un máximo de 5 tags diferentes')</script>";
		return;
	}

	$stmt = DB\insert_into("posts", [
		"author_id" => $user["id"],
		"description" => $description,
		"tags" => join(";", $tags),
	]);
	$result_insert = $db->exec($stmt);

	if (!$result_insert) {
		echo "<script>alert('¡Ocurrió un error procesando tu publicación!\\nInténtalo otra vez más tarde')</script>";
		return;
	}

	$stmt = DB\select(["*"], "posts", [
		DB\order_by("created_date", "DESC"),
		DB\limit(1) 
	]);
	$result_get = $db->exec($stmt);

	$post = mysqli_fetch_array($result_get);
	$post_id = $post["id"];

	$images = $_FILES["images"];
	$total = count($images["name"]);
	$path = "cdn/posts/" . $post["id"];

	if (!file_exists($path))
		mkdir($path);

	for ($i = 0; $i < $total; $i++) {
		$tmp_path = $images["tmp_name"][$i];
		$new_path = $path . "/" . $images["name"][$i];

		if (move_uploaded_file($tmp_path, $new_path)) {
			$stmt = DB\insert_into("images", [
				"post_id" => $post_id,
				"url" => $new_path,
			]);
			$db->exec($stmt);
		}
	}

	$query = "SELECT `users`.* FROM `follows` INNER JOIN `users` ON `follows`.`follower_id` = `users`.`id`
		WHERE `following_id` = " . $user["id"];
	$result_notify = $db->exec(new DB\Stmt($query));

	while ($row = $result_notify->fetch_assoc()) {
		$email = $row["email"];
		$fname = $row["first_name"];

		try {
			mail(
				$email,
				"¡Nueva publicación de " . $user["first_name"] . " " . $user["last_name"] . "!",
				"<h1><strong>ShareYourCanvas</strong></h1><br /><br />
				<h2>¡Hola $fname!</h2><br />
				<h3><strong>" . $user["first_name"] . " " . $user["last_name"] . "</strong> ha hecho una nueva publicación, ¡compruébalo!<br>
				<a href='https://academo.app/post.php?id=$post_id'>¡Utiliza este enlace para ir directo a la publicación!</a></h3>",
				[
					"From" => "shareyourcanvas@academo.app",
					"MIME-Version" => "1.0",
					"Content-Type" => "text/html"
				]
			);
		}
		catch (Exception $error) {	}
	}

	echo "<script>alert('¡Has publicado exitosamente!')</script>";
}

/**
 * Displays all the posts retrieved with the results that match $query.
 * @param string $query The query to compare the posts against.
 * @param int $per_page (Optional) The amount of posts to display per page.
 */
function get_posts (string $query, int $per_page = 1): void
{
	global $db;
	global $user;

	$pagination_query = $query;
	$current_page = 1;

	if (isset($_GET["page"]))
		$current_page = $_GET["page"];

	$from = ($current_page - 1) * $per_page;
	$query .= ($per_page > 0) ? " LIMIT $from, $per_page" : "";
	$result_posts = mysqli_query($db->conn, $query);

	if ($result_posts->num_rows == 0) {
		echo "<div class='my-5 text-center'><h3 style='color: grey;'>Nada que mostrar aquí...</h3></div>";
		return;
	}

	while ($row_post = $result_posts->fetch_assoc()) {
		if (!isset($row_post["id"])) {
			echo "<div class='my-5 text-center'><h3 style='color: grey;'>Nada que mostrar aquí...</h3></div>";
			return;
		}

		$post_id = $row_post["post_id"];
		$post_desc = $row_post["description"];
		$post_tags = join(" ", explode(";", $row_post["tags"]));
		$post_date = $row_post["created_date"];
		$author_id = $row_post["author_id"];
		$author_pfp = $row_post["avatar_url"];
		$author_uname = $row_post["username"];
		$author_fname = $row_post["first_name"];
		$author_lname = $row_post["last_name"];
		$author_exchange = $row_post["exchange"];

		$btn_delete = "";
		$btn_change = "";
		$btn_like = ""; 
		$btn_prev = "";
		$btn_next = "";
		$btn_comments = ($per_page > 0) ? "<a class='btn btn-outline-success' href='/post.php?id=$post_id'><i class='bi bi-chat-fill'></i> Comentarios</a>" : "";

		$stmt = DB\select(["*"], "images", [ DB\where("post_id", "=", $post_id) ]);
		$result_imgs = $db->exec($stmt);

		if ($result_imgs->num_rows > 1) {
			$btn_prev = "
			<button class='carousel-control-prev' type='button' data-bs-target='#carousel-$post_id' data-bs-slide='prev'>
				<span class='carousel-control-prev-icon' aria-hidden='true'></span>
				<span class='visually-hidden'>Previous</span>
			</button>
			";

			$btn_next = "
			<button class='carousel-control-next' type='button' data-bs-target='#carousel-$post_id' data-bs-slide='next'>
				<span class='carousel-control-next-icon' aria-hidden='true'></span>
				<span class='visually-hidden'>Next</span>
			</button>
			";
		}

		if ($author_id == $user["id"]) {
			global $page;
			$btn_delete = "<button class='btn btn-outline-danger ms-auto me-3' onclick='confirmPostDeletion($post_id)'><i class='bi bi-trash-fill'></i> Eliminar</button>";
			$btn_change = $author_exchange ? "<button class='btn btn-outline-secondary' onclick='confirmExchange($post_id)'><i class='bi bi-arrow-left-right'></i> Intercambio</button>" : "";
		}
		else {
			$stmt = DB\select(["*"], "likes", [
				DB\where("post_id", "=", $post_id),
				DB\where("user_id", "=", $user["id"], true)
			]);
			$result_like = $db->exec($stmt);

			if ($result_like->num_rows == 0)
				$btn_like = "<a href='/like.php?id=$post_id' class='btn btn-outline-danger'><i class='bi bi-heart-fill'></i> Me Gusta</a>";
			else
				$btn_like = "<a href='/like.php?id=$post_id' class='btn btn-outline-danger'><i class='bi bi-heartbreak-fill'></i> No Me Gusta</a>";
		}

		echo "
		<div class='row w-100'>
			<div class='col-sm-1'></div>

			<div class='col-sm-10 card text-dark shadow p-0'>
				<div class='card-body'>
					<div class='d-flex align-items-center'>
						<a href='/user.php?uid=$author_id'><img src='$author_pfp' alt='$author_uname&apos;s avatar' height='96px' width='96px' class='rounded' /></a>
						<div class='mx-3'>
							<h5 class='card-title'>
								<a href='/user.php?uid=$author_id' style='text-decoration: none'>$author_fname $author_lname<wbr> <i style='opacity: 0.5; font-size: 0.8rem;'>($author_uname)</i></a>
							</h5>
							<i style='color: grey'>Publicado: $post_date</i>
						</div>
						$btn_delete
					</div>
					<br>
					<div>
						<p>$post_desc</p>
						<br>
						<div id='carousel-$post_id' class='carousel slide' data-bs-ride='true'>
							<h5 class='position-absolute text-white bottom-0 end-0 m-2' style='z-index: 9999;'><i>@$author_uname</i></h6>
							<div class='carousel-indicators'>
		";

		for ($i = 0; $i < $result_imgs->num_rows; $i++) {
			$extra = ($i == 0) ? "class='active' aria-current='true'" : "";

			echo "<button type='button' data-bs-target='#carousel-$post_id' $extra data-bs-slide-to='$i' aria-label='Image $i'></button>";
		}

		echo "
							</div>
							<div class='carousel-inner'>
		";

		$active = "active";

		while ($row_img = $result_imgs->fetch_assoc()) {
			$img_url = $row_img["url"];

			echo "
			<div class='carousel-item $active'>
				<img src='$img_url' class='d-block w-100 h-100' style='object-fit: contain; max-height: 480px; max-width: 100%;' alt='$img_url'>
			</div>
			";

			$active = "";
		}

		echo "
							</div>
							$btn_prev
							$btn_next
						</div>
						<br>
						<p style='color: grey;'>$post_tags</p>
					</div>
					<div class='float-end'>
						$btn_change
						$btn_like
						$btn_comments
					</div>
				</div>
			</div>

			<div class='col-sm-1'></div>
		</div>
		";
	}

	if ($per_page > 0)
		echo pagination($pagination_query, $current_page, $per_page);
}

?>
