<?php 

    require_once "check_authenticated.php";   

?>

<?php
    /**
    * Populates a popup dialog with information about the chosen contact
    */

    require_once "../../common/dbConnect.php";
    require_once "../../common/class.map.php";

    $map = new Map();
    $conid = -1;
    if (isset($_POST['conid']))
        $conid = intval($_POST['conid']);

    $contact = $map -> load_contact($conid);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add/Edit Contact</h4>
        </div>
        <div class="modal-body">
            <div id="invalidInputWarning"></div>
            <div class="form-group" id="nameGroup">
                <label>Name: </label>
                <input type="text" class="form-control" id="name" name="name" value=<?php echo '"' . $contact['name'] . '"'; ?>>
            </div>
            <div class="form-group" id="emailGroup">
                <label>Email: </label>
                <input type="text" class="form-control" id="email" name="email" value=<?php echo '"' . $contact['email'] . '"'; ?>>
            </div>
            <div class="form-group" id="phoneGroup">
                <label>Phone: </label>
                <input type="text" class="form-control" id="phone" name="phone" value=<?php echo '"' . $contact['phone'] . '"'; ?>>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick=submitEditContact(<?php echo $conid; ?>)>Save changes</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
