<?php 

	require_once "check_authenticated.php";	

?>

<?php
    /**
    * The contents of the popup dialog for viewing a project in the History table. Nothing can be edited from here, only viewed.
    * The attributes of the project are loaded from the database and then displayed in the same format as the add/edit project dialog.
    */

    require_once "../../common/dbConnect.php";
    require_once "../../common/class.map.php";

    $map = new Map();
    $hid = -1;
    if (isset($_POST['hid']))
        $hid = intval($_POST['hid']);

    $history = $map -> load_history_details($hid);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">View history</h4>
        </div>
        <div class="modal-body">
            <?php
                echo '<label>Title: </label><input type="text" class="form-control" id="title" name="title" value="' . $history['title'] . '" disabled="disabled">';
                echo '<label>Center: </label><select type="text" class="form-control" id="cid" name="cid" disabled="disabled">';
                $center = $map->load_center($history['cid']);
                echo "<option value='" . $center['cid'] ."' selected='selected'>" . $center['name'] . " (" . $center['acronym'] . ")</option></select>";
                echo '<label>Status: </label><select type="text" class="form-control" id="status" name="status" disabled="disabled">';
                echo "<option value='" . $i . "' selected='selected'>" . $STATUS[$history['status']] . "</option>";
                echo "</select>";
                echo '<label>Start Date: </label><input type="text" class="form-control" id="startDate" name="startDate" value="' . $history['startDate'] . '" disabled="disabled">';
                echo '<label>End Date: </label><input type="text" class="form-control" id="endDate" name="endDate" value="' . $history['endDate'] . '" disabled="disabled">';
                echo '<label>Building Name: </label><input type="text" class="form-control" id="buildingName" name="buildingName" value="' . $history['buildingName'] . '" disabled="disabled">';
                echo '<label>Address: </label><input type="text" class="form-control" id="address" name="address" value="' . $history['address'] . '" disabled="disabled">';
                echo '<label>Zip Code: </label><input type="text" class="form-control" id="zip" name="zip" value="' . $history['zip'] . '" disabled="disabled">';
                echo '<div id="projectPickerMap"></div>';
                echo '<label>Type: </label><select type="text" class="form-control" id="type" name="type" disabled="disabled" value="' . $history['type'] . '"></select>';
                echo '<label>Summary: </label><textarea class="form-control" id="summary"  name="summary" rows="10" disabled="disabled">' . $history['summary'] . '</textarea>';
                echo '<label>Results: </label><textarea class="form-control" id="results"  name="results" rows="10" disabled="disabled">' . $history['results'] . '</textarea>';
                echo '<label>Link: </label><input type="text" class="form-control" id="link" name="link" value="' . $history['link'] . '" disabled="disabled">';
                echo '<label>Picture: </label><input type="text" class="form-control" id="pic" name="pic" value="' . $history['pic'] . '" disabled="disabled">';
                echo '<label>Contact: </label><select type="text" class="form-control" id="conid" name="conid" disabled="disabled">';
                $contact = $map->load_contact($history['conid']);
                echo "<option value='" . $contact['conid'] ."' selected='selected'>" . $contact['name'] . "</option></select>";
                echo '<label>Funded by: </label><input type="text" class="form-control" id="fundedBy" name="fundedBy" value="' . $history['fundedBy'] . '" disabled="disabled">';
                echo '<label>Keywords: </label><input type="text" class="form-control" id="keywords" name="keywords" value="' . $history['keywords'] . '" disabled="disabled">';
                echo '<label>Visibility: </label><select type="text" class="form-control" id="visible" name="visible" disabled="disabled">';
                if ($history['visible'] == 1) {
                    echo "<option value='1' selected='selected'>Shown</option>";
                    echo "<option value='0'>Hidden</option>";
                } else {
                    echo "<option value='1'>Shown</option>";
                    echo "<option value='0' selected='selected'>Hidden</option>";
                }
                echo "</select>";

                // Set the position of the marker on the map
                if ($hid != -1)
                    echo '<script>position = new google.maps.LatLng(' . $history['lat'] . ', ' . $history['lng'] . ')</script>';
            ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
