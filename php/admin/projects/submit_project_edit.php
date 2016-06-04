<?php 

	require_once "check_authenticated.php";	

?>

<?php
	/**
	* Called when the user submits the information entered into the edit project dialog. Information is sent over via POST, as is the id of the project being edited.
	* If pid = -1 that indicates that a project is being added rathre54 than edited.
	*/

	require_once "../../common/dbConnect.php";
	require_once "../../common/class.map.php";
	require_once "../../common/class.aws.php";

	session_start();

	$map = new Map();
	$aws = new AWS();
	$uid = intval($_SESSION['uid']);
	if (isset($_POST['pid'], $_POST['cid'], $_POST['title'], $_POST['status'], $_POST['startDate'], $_POST['endDate'], $_POST['buildingName'], $_POST['address'], $_POST['zip'], $_POST['type'], $_POST['summary'], $_POST['results'], $_POST['link'], $_POST['conid'], $_POST['fundedBy'], $_POST['keywords'], $_POST['stemmedSearchText'], $_POST['visible'], $_POST['lat'], $_POST['lng'])) {
		if (intval($_POST['pid']) == -1) {
			$map -> add_project($uid);
			if (isset($_FILES['pic'])) {
				$url = $aws->upload_object($_POST['pid']);
				$map->save_picture($_POST['pid'], $url);
			}
			echo "Databse updated";
		} else {
            if (isset($_POST['pic'])) {
                $aws -> delete_object($_POST['pid']);
                $aws -> upload_object($_POST['pid']);
            }
		    $map -> update_project($uid);
		    echo "Databse updated";
		}

		$map->generate_prefetch();
	}
?>
