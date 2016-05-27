<?php
/*// Connect to MySQL
    $username = "username"; 
    $password = "password"; 
    $host = "localhost"; 
    $dbname = "databasename"; 
try {
$conn = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password);
}
catch(PDOException $ex) 
    { 
        $msg = "Failed to connect to the database"; 
    } 
*/
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
if (isset($_POST["ForgotPassword"])) {
    
    // Harvest submitted e-mail address
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email = $_POST["email"];
        
    }else{
        echo "email is not valid";
        exit;
    }

/*
    // Check to see if a user exists with this e-mail
    $query = $conn->prepare('SELECT email FROM users WHERE email = :email');
    $query->bindParam(':email', $email);
    $query->execute();
    $userExists = $query->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    */

    $userExists = $map -> login_user($email);

    if ($userExists["email"])
    {
        // Create a unique salt. This will never leave PHP unencrypted.
        $salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

        // Create the unique user password reset key
        $password = hash('sha512', $salt.$userExists["email"]);

        // Create a url which we will direct them to reset their password
        $pwrurl = "http://localhost:8888/ImpactMap/reset_password.php?q=".$password;
        
        // Mail them their key
        $mailbody = "Dear user,\n\nIf this e-mail does not apply to you please ignore it. It appears that you have requested a password reset at our website www.ImpactMap.com\n\nTo reset your password, please click the link below. If you cannot click it, please paste it into your web browser's address bar.\n\n" . $pwrurl . "\n\nThanks,\nThe Administration";
        
        mail($userExists["email"], "www.ImpactMap.com - Password Reset", $mailbody);
        
        echo "Your password recovery key has been sent to your email address. Please follow the instructions in the email to reset your password.";
        
    }
    else
        echo "No user with that e-mail address exists.";
}

echo ' <br>
            <form method="post" action="forgot_password_submit.php">

                <br>
                
                <div class="clearfix"></div><hr />

                <label>Back to <a href="login.php">Login</a></label>
                
            </form>
        </div>

    </center>
    </body>

    </html>';



?>