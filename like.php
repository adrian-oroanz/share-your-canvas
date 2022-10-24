<?php

include "include/auth.php";

if (!isset($_GET["id"])) {
	echo "<script>window.history.back()</script>";
	exit();
}

global $db;
global $user;

$stmt = DB\select(["*"], "likes", [
	DB\where("post_id", "=", $_GET["id"]),
	DB\where("user_id", "=", $user["id"], true)
]);
$result_get = $db->exec($stmt);

if ($result_get->num_rows == 0) {
	$stmt = DB\insert_into("likes", [
		"user_id" => $user["id"],
		"post_id" => $_GET["id"]
	]);
	$db->exec($stmt);
}
else {
	$stmt = DB\delete("likes", [ DB\where("id", "=", $result_get->fetch_assoc()["id"]) ]);
	$db->exec($stmt);
}

echo "<script>window.history.back()</script>";

?>
