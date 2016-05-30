<?php 

    require_once "check_authenticated.php"; 

?>

<?php
    /** 
    * Called when the root admin wants to edit the properties of a user. The id of the user to edit is sent over via POST.
    * If uid == -1 that indicates that a new user is being added to the system.
    */

    require_once "../../common/dbConnect.php";
    require_once "../../common/class.map.php";

    $map = new Map();
    $uid = -1;
    if (isset($_POST['uid']))
        $uid = intval($_POST['uid']);

    $user = $map -> load_user($uid);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">View user</h4>
        </div>
        <div class="modal-body">
            <?php
                echo '<label>First: </label><input type="text" class="form-control" disabled="disabled" id="firstName" name="firstName" value="' . $user['firstName'] . '">';
                echo '<label>Last: </label><input type="text" class="form-control" disabled="disabled" id="lastName" name="lastName" value="' . $user['lastName'] . '">';
                echo '<label>Email: </label><input type="text" class="form-control" disabled="disabled" id="email" name="email" value="' . $user['email'] . '">';
                echo '<label>Phone: </label><input type="text" class="form-control" disabled="disabled" id="phone" name="phone" value="' . $user['phone'] . '">';
                echo '<br>';
                echo '<div class="span7 text-center">';
                if ($user['authenticated'] == TRUE) {
                    echo '<button type="button" class="btn btn-warning" onclick="warnPromotion(' . $uid . ')">Promote to admin</button>';
                } else {
                    echo '<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="authenticateUser(' . $uid . ')">Authenticate user</button>';
                }
                echo '</div>';
            ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
