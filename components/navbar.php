<?php

$page = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);


function anchor (string $href, string $name) {
	global $page;

	$current = ($page == $href);

	if ($current)
		return "<a href='#' class='nav-link active' aria-current='page'>$name</a>";
	
	return "<a href='/$href' class='nav-link'>$name</a>";
}

?>

<nav class="navbar navbar-expand-lg bg-light shadow mb-4">
	<div class="container-fluid">
		<a href="/home.php" class="navbar-brand">ShareYourCanvas</a>
		<button class="navbar-toggler" type="button"
			data-bs-toggle="collapse"
			data-bs-target="#navbarSupportedContent"
			aria-controls="navbarSupportedContent"
			aria-expanded="false"
			aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mb-2 me-auto mb-lg-0">
				<li class="nav-item dropdown">
					<a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">
						<?php global $user; echo $user["first_name"]; ?>
					</a>
					<ul class="dropdown-menu">
						<li><a href="/me.php" class="dropdown-item">Perfil</a></li>
						<li><a href="/gallery.php" class="dropdown-item">Galería</a></li>
						<li><a href="/chat.php" class="dropdown-item">Mensajes</a></li>
						<?php
							$stmt = DB\select(["COUNT(*)"], "exchanges", [ DB\where("receiver_id", "=", $user["id"]) ]);
							$result = $db->exec($stmt);

							echo $user["exchange"] ? "<li><a href='/exchange.php' class='dropdown-item'>Intercambios <span class='badge bg-secondary'>$result->num_rows</span></a></li>" : ""
						?>
						<li><hr class="dropdown-divider"></li>
						<li><a href="/edit.php" class="dropdown-item">Editar Información</a></li>
						<li><a href="/verify.php" class="dropdown-item">Cambiar Contraseña</a></li>
						<li><hr class="dropdown-divider"></li>
						<li><a href="/logout.php" class="dropdown-item">Cerrar Sesión</a></li>
					</ul>
				</li>
				<li class="nav-item">
					<?php echo anchor("home.php", "Inicio") ?>
				</li>
				<li class="nav-item">
					<?php echo anchor("artists.php", "Artistas") ?>
				</li>
				<li class="nav-item">
					<?php echo anchor("explore.php", "Explorar") ?>
				</li>
				<li class="nav-item">
					<?php echo anchor("top.php", "Top"); ?>
				</li>
			</ul>
			<form class="d-flex" action="explore.php" method="GET" role="search">
				<input type="search" name="q" class="form-control me-2" placeholder="Búsqueda" required aria-label="Buscar" />
				<button class="btn btn-primary" type="submit">Buscar</button>
			</form>
			<div class="d-flex mt-2 mt-lg-0 ms-md-2">
				<button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#post-modal"><i class="bi bi-plus"></i> Post</button>
			</div>
		</div>
	</div>
</nav>

<div class="modal fade" id="post-modal"
	data-bs-backdrop="static"
	data-bs-keyboard="false"
	tabindex="-1"
	aria-labelledby="post-modal-label"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-dark" id="post-modal-label">Nueva publicación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form method="POST" enctype="multipart/form-data" id="post-modal-form">
					<textarea name="content" id="post-modal-content" rows="4" class="form-control" style="max-height: 70vh" placeholder="Agrega una descripción y #tags a tu obra" required></textarea>
					<br>
					<input type="file" name="images[]" class="form-control" accept="image/png, image/jpeg" multiple required />
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" type="submit" form="post-modal-form">Publicar</button>
			</div>
		</div>
	</div>
</div>
<?php include "include/posts.php"; create_post(); ?>
