<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo $_POST['title']; ?></h4>
        </div>
        <div class="modal-body">
            <?php echo $_POST['body']; ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick=<?php echo '"' . $_POST['func'] . '"'; ?>><?php echo $_POST['button']; ?></button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->