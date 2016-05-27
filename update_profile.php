
















<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <!-- necessary scripts for the dialog -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  
  </head>

<body>


  <form class="form-vertical" role="form" method="post" action="update_user.php">
<!--div class="modal fade" id="contact_dialog" role="dialog"> -->
       <div class="form-group">
                   
                
                    <?php 
                       echo '<p>Current Email: ' . $_SESSION['user_email'] . '</p>';           
                    ?>
                    <label>New Email: </label>           
                      <input type="text" class="form-control" id="email" name="newemail">
                    <br>
                    <?php 
                       echo '<p>Current Phone: ' . $_SESSION['user_phone'] . '</p>';           
                    ?>
                    <label>New Phone: </label>
                      <input type="text" class="form-control" id="phone" name="newphone">
                    <br>
                <!--<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#password">Change password</button>
                <div id="password" class="collapse">-->
                    <h5>Please enter current password to change your info:</h5>
                    <br>
                    <br>
                    <label>Old password: (required)</label>  
                      <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                    
                    <label>New Password: (optional) </label>
                      <input type="password" class="form-control" id="newPassword1" name="newPassword1">
                   
                    <label>Confirm new Password: (optional) </label>
                      <input type="password" class="form-control" id="newPassword2" name="newPassword2">
                      <form action="updated_user.php" method = "POST">     
                    <!--</div> -->
          </form>
        </div> <!-- form body -->
        
        
                  <div class="form-group">
                      <button type="button" class="btn btn-default" onclick=window.open("admin.php")>Close</button>
                      <button type="submit" id="submitForm" class="btn btn-primary" name="save-changes" onclick= window.open("admin.php")>Save Changes</button>
                  </div> <!-- footer -->

        
      </form>


 <!-- </div> modal fade -->



</body>
</html>