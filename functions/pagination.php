<style>
.pagination a{
	color: black;
	float: left;
	padding: 8px 16px;
	text-decoration: none;
	transition: background-color .3s;
}
.pagination a:hover:not(.active){background-color: #ddd;}
</style>
<?php
	function profile_pagination($u_id) {
		global $con;

		if (isset($_GET['page']))
			$page = $_GET['page'];
		else
			$page = 1;

		$prev = $page - 1;
		$next = $page + 1;

		$query = "SELECT * FROM posts WHERE user_id='$u_id'";
		$result = mysqli_query($con, $query);
		$total = mysqli_num_rows($result);
		$pages = ceil($total / 5);

		echo "<center><div class='pagination'>";

		if ($page > 1 && $page <= $pages)
			echo "<a href='profile.php?page=$prev&u_id=$u_id'>&laquo;</a>";

		for ($i = 1; $i <= $pages; $i++)
			echo "<a href='profile.php?page=$i&u_id=$u_id'>$i</a>";

		if ($page < $pages && $page >= 1)
			echo "<a href='profile.php?page=$next&u_id=$u_id'>&raquo;</a>";

		echo "</div></center>";
	}

	function home_pagination() {
		global $con;

		if (isset($_GET['page']))
			$page = $_GET['page'];
		else
			$page = 1;

		$prev = $page - 1;
		$next = $page + 1;

		$query = "SELECT * FROM posts";
		$result = mysqli_query($con, $query);
		$total = mysqli_num_rows($result);
		$pages = ceil($total / 4);

		echo "<center><div class='pagination'>";

		if ($page > 1 && $page <= $pages)
			echo "<a href='home.php?page=$prev'>&laquo;</a>";

		for ($i = 1; $i <= $pages; $i++)
			echo "<a href='home.php?page=$i'>$i</a>";

		if ($page < $pages && $page >= 1)
			echo "<a href='home.php?page=$next'>&raquo;</a>";

		echo "</div></center>";
	}

	function members_pagination() {
		global $con;

		$per_page = 5;
		$page = 1;

		if (isset($_GET['page']))
			$page = $_GET['page'];

		$prev = $page - 1;
		$next = $page + 1;

		$query = "SELECT * FROM users WHERE posts='yes'";
		$result = mysqli_query($con, $query);
		$total = mysqli_num_rows($result);
		$pages = ceil($total / $per_page);

		echo "<center><div class='pagination'>";

		if ($page > 1 && $page <= $pages)
			echo "<a href='members.php?page=$prev'>&laquo;</a>";

		for ($i = 1; $i <= $pages; $i++)
			echo "<a href='members.php?page=$i'>$i</a>";

		if ($page < $pages && $page >= 1)
			echo "<a href='members.php?page=$next'>&raquo;</a>";

		echo "</div></center>";
	}
?>