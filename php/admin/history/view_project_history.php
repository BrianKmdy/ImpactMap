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
    $center = $map->load_center($history['cid']);
    $contact = $map->load_contact($history['conid']);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">View history</h4>
        </div>
        <div class="modal-body">
            <label>Center: </label>
            <select type="text" class="form-control" id="cid" name="cid" disabled="disabled">
                <option value=<?php echo '"' . $center['cid'] . '"'; ?> selected="selected">
                    <?php echo $center['name'] . " (" . $center['acronym'] . ")"; ?>
                </option>
            </select>
            <div class="form-group" id="titleGroup">
                <label>Title: </label>
                <input type="text" class="form-control" id="title" name="title" value=<?php echo '"' . $history['title'] . '"'; ?> disabled="disabled">
            </div>
            <label>Status: </label>
            <select type="text" class="form-control" id="status" name="status" disabled="disabled">
                <option value=<?php echo '"' . $history['status'] . '"'; ?> selected='selected'>
                    <?php echo $STATUS[$history['status']]; ?>
                </option>
            </select>
            <div class="form-group" id="startDateGroup">
                <label>Start Date: </label>
                <input type="text" class="form-control" id="startDate" name="startDate" value=<?php echo '"' . $history['startDate'] . '"'; ?> disabled="disabled">
            </div>
            <div class="form-group" id="endDateGroup">
                <label>End Date: </label>
                <input type="text" class="form-control" id="endDate" name="endDate" value=<?php echo '"' . $history['endDate'] . '"'; ?> disabled="disabled">
            </div>
            <label>Building Name: </label>
            <input type="text" class="form-control" id="buildingName" name="buildingName" value=<?php echo '"' . $history['buildingName'] . '"'; ?> disabled="disabled">
            <div class="form-group" id="addressGroup">
                <label>Address: </label>
                <input type="text" class="form-control" id="address" name="address" value=<?php echo '"' . $history['address'] . '"'; ?> disabled="disabled">
            </div>
            <div class="form-group" id="zipGroup">
                <label>Zip Code: </label>
                <input type="text" class="form-control" id="zip" name="zip" value=<?php echo '"' . $history['zip'] . '"'; ?> disabled="disabled">
            </div>
            <div id="projectPickerMap"></div>
            <label>Type: </label>
            <select type="text" class="form-control" id="type" name="type" disabled="disabled">
                <option value=<?php echo '"' . $history['type'] . '"'; ?> selected='selected'>
                    <?php echo $TYPE[$history['type']]; ?>
                </option>
            </select>
            <div class="form-group" id="summaryGroup">
                <label>Summary: </label>
                <textarea class="form-control" id="summary"  name="summary" rows="10" disabled="disabled"><?php echo $history['summary']; ?></textarea>
            </div>
            <div class="form-group" id="resultsGroup">
                <label>Results: </label>
                <textarea class="form-control" id="results"  name="results" rows="10" disabled="disabled"><?php echo $history['results']; ?></textarea>
            </div>
            <label>Link: </label>
            <input type="text" class="form-control" id="link" name="link" value=<?php echo '"' . $history['link'] . '"'; ?> disabled="disabled">
            <label>Contact: </label>
            <select type="text" class="form-control" id="conid" name="conid" disabled="disabled">
                <option value=<?php echo '"' . $contact['conid'] . '"'; ?> selected='selected'>
                    <?php echo $contact['name']; ?>
                </option>
            </select>
            <div class="form-group" id="fundedByGroup">
                <label>Funded by: </label>
                <input type="text" class="form-control" id="fundedBy" name="fundedBy" value=<?php echo '"' . $history['fundedBy'] . '"'; ?> disabled="disabled">
            </div>
            <div class="form-group" id="pictureGroup">
                <?php if (!empty($history['pic'])): ?> 
                    <a class="btn btn-primary" target="_blank" href=<?php echo '"' . $history['pic'] . '"'; ?>>View Picture</a>
                <?php endif; ?>
            </div>
            <div class="col-md-12">
                <img id="upload-preview" src="#" style="display:none; max-width:100%; max-height:100%;">
            </div>
            <label>Keywords: </label>
            <input type="text" class="form-control" id="keywords" name="keywords" value=<?php echo '"' . $history['keywords'] . '"'; ?> disabled="disabled">
            <label>Visibility: </label>
            <select type="text" class="form-control" id="visible" name="visible" disabled="disabled">
                <?php if ($history['visible'] == 1): ?>
                    <option value='1' selected='selected'>Shown</option>
                    <option value='0'>Hidden</option>
                <?php else: ?>
                    <option value='1'>Shown</option>
                    <option value='0' selected='selected'>Hidden</option>
                <?php endif; ?>
            </select>

            <?php if ($hid != -1): ?>
                <script>position = new google.maps.LatLng(<?php echo $history['lat']; ?>, <?php echo $history['lng']; ?>);</script>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
