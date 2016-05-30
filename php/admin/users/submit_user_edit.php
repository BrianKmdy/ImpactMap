<?php 

    require_once "check_authenticated.php"; 

?>

<?php
	/**
	* Submit changes to a user's properties to the database. User attributes are sent over via POST
	*/

	require_once "../../common/dbConnect.php";
	require_once "../../common/class.map.php";

	$map = new Map();

	if (isset($_POST['uid'])) {
		if (isset($_POST['authenticate'])) 
			$map->authenticate(intval($_POST['uid']));
		else if (isset($_POST['promote'])) {
			echo $_POST['uid'];
			$map->promote(intval($_POST['uid']));
		}
	}
?>
