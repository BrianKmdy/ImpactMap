<?php

require_once "../common/class.map.php";
$map = new Map();

$pid = $_POST['pid'];
if (!empty($pid)) {
    $project = $map->load_project_details($pid);
    echo json_encode($project);
}
?>