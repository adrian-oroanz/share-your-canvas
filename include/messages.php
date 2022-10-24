<?php

function get_messages (int|string $user_id)
{
	global $user;
	global $db;

	$query = "SELECT * FROM `messages` INNER JOIN `users` ON `messages`.`sender_id` = `users`.`id` WHERE `sender_id` = " . $user["id"] . " AND `receiver_id` = $user_id ORDER BY `sent_date` ASC";
	$result_sent = $db->exec(new DB\Stmt($query));
	$sent_messages = $result_sent->fetch_all(MYSQLI_ASSOC);

	$query = "SELECT * FROM `messages` INNER JOIN `users` ON `messages`.`sender_id` = `users`.`id` WHERE `sender_id` = $user_id AND `receiver_id` = " . $user["id"] . " ORDER BY `sent_date` ASC";
	$result_received = $db->exec(new DB\Stmt($query));
	$received_messages = $result_received->fetch_all(MYSQLI_ASSOC);

	$messages = array_merge($sent_messages, $received_messages);
	usort($messages, function (array $m1, array $m2) { return strtotime($m1["sent_date"]) < strtotime($m2["sent_date"]); });

	foreach ($messages as $k => $msg) {
		$content = $msg["content"];
		$username = $msg["username"];
		$bg_color = $msg["sender_id"] == $user["id"] ? "list-group-item-primary" : "list-group-item-light";

		$sent_at = date("Y-m-d H:i:s", strtotime($msg["sent_date"]));

		echo "
		<li class='list-group-item $bg_color p-3 m-1 flex-shrink-0'>
			<div class='d-flex w-100 justify-content-between'>
				<h5 class='mb-1'>$username</h5>
				<i>$sent_at</i>
			</div>
				$content
		</li>
		";
	}
}

?>
