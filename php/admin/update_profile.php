<?php 
    require_once "../common/class.map.php";

    session_start();
    
    $map = new Map();
    $row = $map -> login_user($_SESSION['user_email']);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">User profile</h4>
        </div>
        <div class="modal-body">
        <div id="invalidInputWarning">
        </div>
            <div class="form-group" id="emailGroup">
            <label>Email: </label><input type="text" class="form-control" id="email" name="email" value=<?php echo '"' . $row['email'] . '"'; ?>>
            </div>

            <div class="form-group" id="phoneGroup">
            <label>Phone: </label><input type="text" class="form-control" id="phone" name="phone" value=<?php echo '"' . $row['phone'] . '"'; ?>>
            </div>

            <br>
            <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#password">Change password</button>

            <div id="password" class="collapse">
                <br>
                <div class="form-group" id="newPassword1Group">
                <label>New Password: </label><input type="password" class="form-control" id="newPassword1" name="newPassword1">
                </div>

                <div class="form-group" id="newPassword2Group">
                <label>Confirm new Password: </label><input type="password" class="form-control" id="newPassword2" name="newPassword2">
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitUpdateProfile()" data-dismiss="modal">Save changes</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->