<div class="span7 text-center">
	<?php
		
		require_once "../common/class.map.php";

		session_start();
		
		$map = new Map();
		$row = $map -> login_user($_SESSION['user_email']);

		$authenticated = $row['authenticated'];

		if (!$authenticated) {
			echo "<div class='alert alert-danger'>";
			echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
			echo "Awaiting authentication";
			echo "</div>";
		}

		$fname = $row['firstName'];
		$lname = $row['lastName'];
		$email = $row['email'];
		$phone = $row['phone'];

		echo "<h2>" . $fname . " ";
		echo "" . $lname . "</h2>";
		echo "<h2><small>" . $email . "</small></h2>";
		echo "<h2><small>" . $phone . "</small></h2><br>";
	?>
	<button type="button" class="btn btn-primary" onclick=window.open("update_profile.php")>Update information</button>
</div>