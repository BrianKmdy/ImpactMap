<?php 

	require_once "check_authenticated.php";	

?>

<?php
    /**
    * The edit project dialog. Everything is contained in the #popup div. It contains text fields and drop downs to specify all the data attributes for a project.
    * The project id of the project being edited is passed over as $_POST['pid']. If the pid is -1 that indicates that a projected is being added rather than edited.
    * The position variable in admin.js is set here, so that the map in the dialog will show the position of the chosen project.
    */

    require_once "../../common/dbConnect.php";
    require_once "../../common/class.map.php";

    $map = new Map();
    $pid = -1;
    if (isset($_POST['pid']))
        $pid = intval($_POST['pid']);

    $project = $map -> load_project_details($pid);
    $centers = $map->load_centers();
    $contacts = $map->load_contacts();
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add/Edit Project</h4>
        </div>
        <div class="modal-body">
            <div id="invalidInputWarning"></div>
            <label>Center: </label>
            <select type="text" class="form-control" id="cid" name="cid">
                <?php for ($i = 0; $i < count($centers); $i++): ?>
                    <option value=<?php echo '"' . $centers[$i]['cid'] . '"'; if ($centers[$i]['cid'] == $project['cid']) echo ' selected="selected"'; ?>>
                        <?php echo $centers[$i]['name'] . " (" . $centers[$i]['acronym'] . ")"; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <div class="form-group" id="titleGroup">
                <label>Title: </label>
                <input type="text" class="form-control" id="title" name="title" value=<?php echo '"' . $project['title'] . '"'; ?>>
            </div>
            <label>Status: </label>
            <select type="text" class="form-control" id="status" name="status">
                <?php for ($i = 0; $i < count($STATUS); $i++): ?>
                    <option value=<?php echo '"' . $i . '"'; if ($i == $project['status']) echo "selected='selected'"; ?>>
                        <?php echo $STATUS[$i]; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <div class="form-group" id="startDateGroup">
                <label>Start Date: </label>
                <input type="text" class="form-control" id="startDate" name="startDate" value=<?php echo '"' . $project['startDate'] . '"'; ?>>
            </div>
            <div class="form-group" id="endDateGroup">
                <label>End Date: </label>
                <input type="text" class="form-control" id="endDate" name="endDate" value=<?php echo '"' . $project['endDate'] . '"'; ?>>
            </div>
            <label>Building Name: </label>
            <input type="text" class="form-control" id="buildingName" name="buildingName" value=<?php echo '"' . $project['buildingName'] . '"'; ?>>
            <div class="form-group" id="addressGroup">
                <label>Address: </label>
                <input type="text" class="form-control" id="address" name="address" value=<?php echo '"' . $project['address'] . '"'; ?>>
            </div>
            <div class="form-group" id="zipGroup">
                <label>Zip Code: </label>
                <input type="text" class="form-control" id="zip" name="zip" value=<?php echo '"' . $project['zip'] . '"'; ?>>
            </div>
            <div id="projectPickerMap"></div>
            <label>Type: </label>
            <select type="text" class="form-control" id="type" name="type">
                <?php for ($i = 0; $i < count($TYPE); $i++): ?>
                    <option value=<?php echo '"' . $i . '"'; if ($i == $project['type']) echo "selected='selected'"; ?>>
                        <?php echo $TYPE[$i]; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <div class="form-group" id="summaryGroup">
                <label>Summary: </label>
                <textarea class="form-control" id="summary"  name="summary" rows="10"><?php echo $project['summary']; ?></textarea>
            </div>
            <div class="form-group" id="resultsGroup">
                <label>Results: </label>
                <textarea class="form-control" id="results"  name="results" rows="10"><?php echo $project['results']; ?></textarea>
            </div>
            <label>Link: </label>
            <input type="text" class="form-control" id="link" name="link" value=<?php echo '"' . $project['link'] . '"'; ?>>
            <label>Contact: </label>
            <select type="text" class="form-control" id="conid" name="conid">
                <?php for ($i = 0; $i < count($contacts); $i++): ?>
                    <option value=<?php echo '"' . $contacts[$i]['conid'] . '"'; if ($contacts[$i]['conid'] == $project['conid']) echo "selected='selected'"; ?>>
                        <?php echo $contacts[$i]['name']; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <div class="form-group" id="fundedByGroup">
                <label>Funded by: </label>
                <input type="text" class="form-control" id="fundedBy" name="fundedBy" value=<?php echo '"' . $project['fundedBy'] . '"'; ?>>
            </div>
            <div class="form-group" id="pictureGroup">
                <?php if (!empty($project['pic'])): ?> 
                    <a class="btn btn-primary" target="_blank" href=<?php echo '"' . $project['pic'] . '"'; ?>>View Picture</a>
                    <a class="btn btn-primary" href=<?php echo '"delete_picture.php?pid=' . $pid . '"'; ?>>Delete Picture</a>
                <?php endif; ?>
                <label>Picture upload: </label>
                <input type="file" class="form-control-file" id="pic" name="pic">
            </div>
            <div class="col-md-12">
                <img id="upload-preview" src="#" style="display:none; max-width:100%; max-height:100%;">
            </div>
            <label>Keywords: </label>
            <input type="text" class="form-control" id="keywords" name="keywords" value=<?php echo '"' . $project['keywords'] . '"'; ?>>
            <label>Visibility: </label>
            <select type="text" class="form-control" id="visible" name="visible">
                <?php if ($project['visible'] == 1): ?>
                    <option value='1' selected='selected'>Shown</option>
                    <option value='0'>Hidden</option>
                <?php else: ?>
                    <option value='1'>Shown</option>
                    <option value='0' selected='selected'>Hidden</option>
                <?php endif; ?>
            </select>

            <?php if ($pid != -1): ?>
                <script>position = new google.maps.LatLng(<?php echo $project['lat']; ?>, <?php echo $project['lng']; ?>);</script>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick=submitEditProject(<?php echo $pid; ?>)>Save changes</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
