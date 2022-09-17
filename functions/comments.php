<?php
	$get_id = $_GET['post_id'];

	$get_com = "SELECT comments.comment, comments.date, users.user_name, users.user_id FROM comments INNER JOIN users ON comments.comment_author = users.user_id WHERE post_id='$get_id' ORDER BY 1 DESC";

	$run_com = mysqli_query($con, $get_com);

	while ($row = mysqli_fetch_array($run_com)){
		$com = $row['comment'];
		$com_name = $row['user_name'];
		$date = $row['date'];
		$id = $row['user_id'];

		echo "
		<div class='row'>
		<div class='col-md-6 col-md-offset-3'>
			<div class='panel panel-info'>
				<div class='panel-body'>
					<div>
						<h4><a href='/user_profile.php?u_id=$id'><strong class='text-primary'>$com_name</strong></a> <i>comentó el $date</i></h4>
						<p style='margin-left:5px ; font-size: 20px;'>$com</p>
					</div>
				</div>
			</div>
		</div>
	</div>
		";
	}

?>