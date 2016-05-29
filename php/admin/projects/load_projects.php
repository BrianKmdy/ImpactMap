<?php
	/**
	* Called to load all the projects that meet the specified filters. Results are returned as a json encoded array
	*/

	require_once "../../common/dbConnect.php";
	require_once "../../common/class.map.php";

	$map = new Map();

	$center = isset($_POST['center']) ? intval($_POST['center']) : -1;
	$type = isset($_POST['type']) ? intval($_POST['type']) : -1;
	$status = isset($_POST['status']) ? intval($_POST['status']) : -1;
	$start = isset($_POST['start']) ? intval($_POST['start']) : "";
	$end = isset($_POST['end']) ? intval($_POST['end']) : "";
	$filters = array(
		'center' => $center,
		'type' => $type,
		'status' => $status,
		'start' => $start,
		'end' => $end
	);

	$filtered = $map -> load_projects($filters);

	echo json_encode($filtered);

?>