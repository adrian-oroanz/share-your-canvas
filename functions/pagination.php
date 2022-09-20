<style>
	.pagination a {
		color: black;
		float: left;
		padding: 8px 16px;
		text-decoration: none;
		transition: background-color .3s;
	}

	.pagination a:hover:not(.active) {
		background-color: #ddd;
	}
</style>

<?php

	function paginate ($query, $page = 1, $per_page = 5) {

		global $con;

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

		$result = mysqli_query($con, $query);
		$total = mysqli_num_rows($result);
		$total_pages = ceil($total / $per_page);

		echo "<center><div class='pagination'>";

		if ($page > 1 && $page <= $total_pages)
			echo "<a href='$location?page=$prev_page$params'>&laquo;</a>";

		for ($i = 1; $i <= $total_pages; $i++)
			echo "<a href='$location?page=$i$params'>$i</a>";

		if ($page < $total_pages && $page >= 1)
			echo "<a href='$location?page=$next_page$params'>&raquo;</a>";

		echo "</div></center>";

	}

?>