<?php 
/*

This file checks a user's authenification settings;
4 access levels:
 1) Non-user (people that are just viewing the map for informational purposes)
 2) Non-authenicated user (User account is created, but not yet verified by admin; they can only view and change their user settings)
 3) Authenticated user (Can change/view profile, access/create/modify projects and contacts)
 4) Admin User (has same access as level 3; can also verify new users, remove users/projects, and can promote others to admin)

*/


session_start();

require_once "../../common/dbConnect.php";
require_once "../../common/class.map.php";

$newmap = new Map();
$row = $newmap -> login_user($_SESSION['user_email']);
$admin = $row['root'];
$authenticated = $row['authenticated'];

if(!isset($_SESSION['logged_in']))
{

	header("Location: ../../../login.php");
	//echo 'Please Log in.';

}//check if user is logged in

else if(isset($_SESSION['logged_in']) && !($authenticated)) {

	header("Location:  ../../../admin.php");
	echo 'You must be authenticated before using this feature. Please contact site admin for details.';	

}//check if authenticated

else if(isset($_SESSION['logged_in']) && ($authenticated) && !($admin) ){

	header("Location:  ../../../admin.php");
	//header('refresh:5, url=../../../admin.php');
	echo 'You must be an admin to use this feature. Please contact site admin for details.';	

}//check if user is admin

?>
