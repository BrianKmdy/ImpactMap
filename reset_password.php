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
       

echo '
	<form action="reset_password_submit.php" method="POST">
	E-mail Address: <input type="text" name="email" size="20" /><br />
	New Password: <input type="password" name="password" size="20" /><br />
	Confirm Password: <input type="password" name="confirmpassword" size="20" /><br />
	<input type="hidden" name="q" value="';

if (isset($_GET["q"])) {
	echo $_GET["q"];
}



echo '" /><button class="btn btn-block btn-primary" type="submit" name="ResetPasswordForm" value=" Reset Password ">Submit</button>
		</form>
		        </div>

    </center>
    </body>

    </html>';



?>