<?php 

	require_once "check_authenticated.php";	

?>

<?php
	/**
	* Called when the root user wants to restore only certain entries from the History table. The IDs of each element
	* in the history table are sent over as an array encoded in json
	*/

	require_once "../../common/dbConnect.php";
    require_once "../../common/class.map.php";

    $map = new Map();

	$projects = json_decode($_POST['data']);

	foreach($projects as $project) {
		$map->restore_history($project);
	}
?>
