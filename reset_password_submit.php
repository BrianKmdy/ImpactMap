<?php

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reset Password</title>
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
        <h2>Password Reset</h2>
        
  ';         

require_once "php/common/dbConnect.php";
require_once "php/common/class.map.php";

$map = new Map();

// Was the form submitted?
if (isset($_POST["ResetPasswordForm"]))
{
	// Gather the post data
	$email = $_POST["email"];
	$password = $_POST["password"];
	$confirmpassword = $_POST["confirmpassword"];
	$hash = $_POST["q"];

	// Use the same salt from the forgot_password.php file
	$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

	// Generate the reset key
	$resetkey = hash('sha512', $salt.$email);

	// Does the new reset key match the old one?
	if ($resetkey == $hash)
	{
		if ($password == $confirmpassword)
		{
			//has and secure the password
			//$password = hash('sha512', $salt.$password);
			$newpassword = md5($password);

			$result = $map -> change_password($email, $newpassword);
			
			/*
			// Update the user's password
				$query = $conn->prepare('UPDATE users SET password = :password WHERE email = :email');
				$query->bindParam(':password', $password);
				$query->bindParam(':email', $email);
				$query->execute();
				$conn = null;
			*/

			echo "Your password has been successfully reset! <br><br>";
			echo '<label>Return to <a href="login.php">Login</a></label>';
		}
		else
			echo "Your password's do not match.";
	}
	else
		echo "Your password reset key is invalid.";
}

echo '</div>

    </center>
    </body>

    </html>';


?>
