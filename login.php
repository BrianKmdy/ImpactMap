<?php
session_start();

if(isset($_SESSION['logged_in']))
{
 header("Location: admin.php");
}

require_once "php/common/dbConnect.php";
require_once "php/common/class.map.php";

$map = new Map();

if(isset($_POST['btn-login']))
{

 $email = ($_POST['email']);
 $upass = ($_POST['pass']);

	$res = $map -> login_user($email);

	 if( $res['password']==md5($upass) )
	 {
	  //$_SESSION['user'] = $res['uid'];
  	  //$username = $res['firstName'] + $res['lastName'];
	  $_SESSION['logged_in'] = $res['firstName'];
	  $_SESSION['logged_in2'] = $res['lastName'];
	  $_SESSION['user_email'] = $res['email'];
	 //$_SESSION['logged_in'] = $username;

	  header("Location: admin.php");
	 }
	 else
	 {
	  ?>
	        <script>alert('Wrong email or password!');</script>
	        <?php
	 }

 
}//if login-button is clicked


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">
	<link rel="stylesheet"  type="text/css" href="css/login.css">
	<link rel="stylesheet" href="style.css" type="text/css" />
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	 <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>

<body>
<center>
	<div class="form" id="login-form">
		<h2>Login</h2>
		<form method="post">

		<div class="form-group">	
			<input type="text" class="form-control" name="email" placeholder="Your Email" required />
		</div>
		<div class="form-group">		
			<input type="password" class="form-control" name="pass" placeholder="Your Password" required />
		</div>
			<button class="btn btn-block btn-primary" type="submit" name="btn-login"><i class="glyphicon glyphicon-log-in"></i>&nbsp;Sign In
			</button>
			
			<div class="clearfix"></div><hr />

			<label>Don't have an account? <a href="register.php">Sign Up</a></label>
			<label>Forgot Password? <a href="forgot_password.php">Click Here</a></label>

			
		</form>
	</div>

</center>
</body>

</html>
