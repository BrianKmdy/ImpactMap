<?php

session_start();

if(isset($_SESSION['logged_in']))
{
 //header("Location: admin.php");
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/imm/php/common/dbConnect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/imm/php/common/class.map.php";


//if(isset($_POST['save-changes']))
//{

 $oldemail = $_SESSION['user_email'];
 $newemail = ($_POST['newemail']);
 $oldphone = $_SESSION['user_phone'];
 $newphone = ($_POST['newphone']);
 $oldpass = md5($_POST['oldPassword']);
 $newpass1 = md5($_POST['newPassword1']);
 $newpass2 = md5($_POST['newPassword2']);
 
    //get info for logged in user
    $res = $map -> login_user($_SESSION['user_email']);
     
     if ( $res['password'] != $oldpass ) 
     {
      ?>
            <script>alert('Old password is incorrect.');</script>
            <?php
            echo '<form method="get" action="/imm/admin.php"> <button type="submit" class="btn btn-default">Return to Admin</button> </form>';
     }
     else if ( $newpass1 != $newpass2 ) 
     {
      ?>
            <script>alert('New passwords do not match.');</script>
            <?php
            echo '<form method="get" action="/imm/admin.php"> <button type="submit" class="btn btn-default">Return to Admin</button> </form>';
     }
     else if( $res['password'] == $oldpass )
     {
        
          $uid = $res['uid'];

     	  if(!isset( $_POST['newPassword1'] )){

     	  	$newpass1 = $res['password'];

     	  }//don't reset password
     	  if(!isset( $_POST['newphone'] )){

     	  	$newphone = $oldphone;

     	  }//don't reset password
          
          if(!isset( $_POST['newemail'] )){

     	  	$newemail = $oldemail;

     	  }//don't reset password


          //$map -> update_userinfo($uid, $newemail, $newphone, $newpass1);
        $sql = "UPDATE Users SET
              $email = $newemail,
              $phone = $newphone,
              $password = $newpass1
            WHERE $uid = $uid LIMIT 1
            ";
          $map->query($sql);
        /*try {
        $stmt = $this->_db->prepare($sql);
        $stmt -> bindParam(":uid", $uid, PDO::PARAM_INT);
        $stmt -> bindParam(":email2",  $email2, PDO::PARAM_STR);
        $stmt -> bindParam(":phone", $phone, PDO::PARAM_STR);
        $stmt -> bindParam(":password", $password, PDO::PARAM_STR);
        $stmt -> execute();
          
        } catch(PDOException $e) {
        echo $e -> getMessage();
      }*/
        if(isset($newemail)){

            unset($_SESSION['user_email']);
            $_SESSION['user_email'] = $newemail;
          }//if new email is set


          echo '<html>
				<head>
					<!-- necessary scripts for the dialog -->
					<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
					<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
				
				</head>';
		  echo '<center>';		
          echo '<h2>User info updated successfully!</h2><br><br>';
          echo '<form method="get" action="/imm/admin.php"> <button type="submit" class="btn btn-default">Return to Admin</button> </form>';
          echo '</center';
          echo '</html>';
          //header("Location: admin.php");
     }
 
//}//if save-changes button is clicked

?>