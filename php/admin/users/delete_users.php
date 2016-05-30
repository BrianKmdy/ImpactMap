<?php 

    require_once "check_authenticated.php"; 

?>

<?php
	/**
	* Called when the root admin wishes to delete other users from the system. The user IDs are sent over via POST as a json array
	*/

	require_once "../../common/dbConnect.php";
    require_once "../../common/class.map.php";

    $map = new Map();

	$users = json_decode($_POST['data']);

	foreach($users as $uid) {
		$map->remove_user($uid);
	}
?>
