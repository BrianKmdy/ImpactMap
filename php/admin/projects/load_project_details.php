<?php 

	require_once "check_authenticated.php";	

?>

<?php
	/** 
	* Called to load all attributes of a specific project. The project id is passed over and the result is returned encoded in json
	*/

	require_once "../../common/dbConnect.php";
	require_once "../../common/class.map.php";

	$map = new Map();
	$pid = 0;

	if (isset($_POST['pid'])) 
		$pid = intval($_POST['pid']);

	$filtered = $map -> load_project_details($pid);

	echo json_encode($filtered);
?>
