<?php 

    require_once "check_authenticated.php";   

?>

<?php
	/**
	* Called with an array of center IDs, each center is then deleted from the center table
	*/

	require_once "../../common/dbConnect.php";
    require_once "../../common/class.map.php";

    $map = new Map();

	$contacts = json_decode($_POST['data']);

	foreach($contacts as $conid) {
		if (!$map->contact_referred_to($conid))
			$map->remove_contact($conid);
	}
?>
