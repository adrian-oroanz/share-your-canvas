<?php
declare(strict_types=1);


function pagination (string $query, int $page = 1, int $per_page = 5): string
{
	global $db;

	$comp = "
	<nav aria-label='page navigation' class='mt-5'>
		<ul class='pagination justify-content-center'>
	";

	$request_uri = htmlspecialchars($_SERVER["REQUEST_URI"], ENT_QUOTES, "UTF-8");
	$uri = explode("?", $request_uri, 2);
	$location = $uri[0];
	$params = "";

	// There are other params in the URL, cleanse it and save the params.
	if (isset($uri[1])) {
		if (isset($_GET["page"])) {
			$cleanse = explode("page=$page", $uri[1], 2);
			$params = $cleanse[1];
		}
		else $params = "&$uri[1]";
	}

	$prev_page = $page - 1;
	$next_page = $page + 1;

	$result = mysqli_query($db->conn, $query);
	$total = mysqli_num_rows($result);
	$total_pages = ceil($total / $per_page);

	$prev_en = ($page > 1 && $page <= $total_pages);
	$next_en = ($page < $total_pages && $page >= 1);

	// Previous button.
	$comp .= "<li class='page-item " . (!$prev_en ? "disabled" : "") . "'>
		<a href='$location?page=$prev_page$params' class='page-link' aria-label='previous'>
			<span aria-hidden='true'>&laquo;</span>
		</a>
	</li>
	";

	// Numbered buttons.
	for ($i = 1; $i <= $total_pages; $i++) {
		$comp .= "
			<li class='page-item'>
				<a href='$location?page=$i$params' class='page-link'>$i</a>
			</li>
		";
	}

	// Next button.
	$comp .= "<li class='page-item " . (!$next_en ? "disabled" : "") . "'>
		<a href='$location?page=$next_page$params' class='page-link' aria-label='next'>
			<span aria-hidden='true'>&raquo;</span>
		</a>
	</li>
	</ul>
	</nav>
	";

	return $comp;
}

function array_map_assoc (array $arr, callable $mapper)
{
	$res_arr = array();

	foreach ($arr as $k => $v) {
		$res_arr[] = $mapper($k, $v);
	}

	return $res_arr;
}


?>
