<?php

include "include/auth.php";

if (!isset($_GET["id"])) {
	echo "<script>window.history.back()</script>";
	exit();
}

global $db;
global $user;

$stmt = DB\select(["*"], "posts", [ DB\where("author_id", "=", $user["id"]), DB\where("id", "=", $_GET["id"], true) ]);
$result_get = $db->exec($stmt);

if ($result_get->num_rows == 0) {
	echo "<script>window.history.back()</script>";
	exit();
}

$stmt = DB\select(["url"], "images", [ DB\where("post_id", "=", $_GET["id"]) ]);
$result_imgs = $db->exec($stmt);

while ($row = $result_imgs->fetch_assoc()) {
	if (file_exists($row["url"]))
		unlink($row["url"]);
}

if (is_dir("cdn/posts/" . $_GET["id"]))
	rmdir("cdn/posts/" . $_GET["id"]);

$stmt = DB\delete("posts", [ DB\where("id", "=", $result_get->fetch_assoc()["id"]) ]);
$result_del = $db->exec($stmt);

echo "<script>window.history.back()</script>";

?>
