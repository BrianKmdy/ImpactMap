<?php 

	require_once "check_authenticated.php";	

?>

<?php
	/**
	* Called when a user wants to delete projects from the table. The project IDs are sent over as a json encoded array
	*/

	require_once "../../common/dbConnect.php";
    require_once "../../common/class.map.php";

    $map = new Map();
    if (isset($_POST['func'], $_POST['data'])) {
		$projects = json_decode($_POST['data']);

		if ($_POST['func'] == 'hide') {
			foreach($projects as $project) {
				$map->set_project_visible($project, FALSE);
			}
		} else if ($_POST['func'] == 'show') {
			foreach($projects as $project) {
				$map->set_project_visible($project, TRUE);
			}
		} else if ($_POST['func'] == 'delete') {
			foreach($projects as $project) {
				$map->remove_project($project);
			}
		}

		$map->generate_prefetch();
	}
?>
