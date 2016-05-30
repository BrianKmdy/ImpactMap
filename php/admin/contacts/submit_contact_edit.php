<?php 

    require_once "check_authenticated.php";   

?>

<?php

	/**
	* Information is POSTed when a user clicks submit on the edit center dialog, it is then stored in the database here.
	*/

	require_once "../../common/dbConnect.php";
	require_once "../../common/class.map.php";

	$map = new Map();

	if (isset($_POST['conid'], $_POST['name'], $_POST['email'], $_POST['phone'])) {
		if (intval($_POST['conid']) == -1) {
			$map -> add_contact();
			echo "Databse updated";
		} else {
		    $map -> update_contact();
		    echo "Databse updated";
		}
	}

?>
