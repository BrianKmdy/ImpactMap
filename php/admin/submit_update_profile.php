<?php
	/**
	* Submit changes to a user's properties to the database. User attributes are sent over via POST
	*/
	echo 'test';
	require_once "../common/class.map.php";


	session_start();

	$map = new Map();
	$row = $map -> login_user($_SESSION['user_email']);

	echo 'test2';

	if (isset($_POST['email'], $_POST['phone'], $_POST['newPassword1'], $_POST['newPassword2'])) {
		echo $row['email'];
		$row2 = $map -> login_user($_POST['email']);

        if($row2 == NULL || strcmp($row2['email'], $row['email']) == 0) {
        	echo 'here';
			if ($map->update_profile($row['email'], $_POST['email'], $_POST['phone'])) {
				$_SESSION['user_email'] = $_POST['email'];
				echo 'blarg';
			}
	
			if (strlen($_POST['newPassword1']) > 0 && strlen($_POST['newPassword2']) > 0 && strcmp($_POST['newPassword1'], $_POST['newPassword2']) == 0) {
				echo 'burritos';
				$map->change_password($row['email'], md5($_POST['newPassword1']));
			}
		}
	}
?>