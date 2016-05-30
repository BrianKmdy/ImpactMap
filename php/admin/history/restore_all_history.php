<?php 

	require_once "check_authenticated.php";	

?>

<?php
	/**
	* Called when the root user wants to restore the entire project table to a given point in time.
	*/

	require_once "../../common/dbConnect.php";
    require_once "../../common/class.map.php";

    $map = new Map();

	$timestamp = $_POST['data'];

	$map->restore_all_history($timestamp);
?>
