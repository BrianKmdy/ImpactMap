<?php

if (!isset($_GET['pid'])) {
    echo "Project ID not set.";
    exit();
}
$pid = intval($_GET['pid']);

require_once "../../common/class.aws.php";
require_once "../../common/class.map.php";

$a = new AWS();
$m = new Map();

$aws_success = $a->delete_object($pid);
$db_success = $m->delete_picture($pid);

echo $aws_success ? "Successfully uploaded to AWS" : "AWS Upload Failed!";
echo "<br>";
echo $db_success ? "Successfully updated database" : "Database update failed!";
echo "<br>";