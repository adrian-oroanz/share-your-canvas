<?php

include "include/auth.php";

if (!isset($_GET["id"])) {
	echo "<script>window.history.back()</script>";
	exit();
}

global $db;
global $user;

$stmt = DB\select(["*"], "comments", [ DB\where("author_id", "=", $user["id"]), DB\where("id", "=", $_GET["id"], true) ]);
$result_get = $db->exec($stmt);

if ($result_get->num_rows == 0) {
	echo "<script>window.history.back()</script>";
	exit();
}

$stmt = DB\delete("comments", [ DB\where("id", "=", $result_get->fetch_assoc()["id"]) ]);
$result_del = $db->exec($stmt);

echo "<script>window.history.back()</script>";

?>