<?php

require_once "common/dbConnect.php";
require_once "common/class.map.php";

$map = new Map();

if (isset($_POST['cid'], $_POST['name'], $_POST['acronym'], $_POST['color'])) {
	if (intval($_POST['cid']) == -1) {
		$map -> add_center();
		echo "Databse updated";
	} else {
	    $map -> update_center();
	    echo "Databse updated";
	}
}

?>