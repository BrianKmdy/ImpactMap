<?php

require_once "../common/dbConnect.php";
require_once "../common/class.map.php";
$map = new Map();

$result = $map -> load_centers();

echo json_encode($result);

?>