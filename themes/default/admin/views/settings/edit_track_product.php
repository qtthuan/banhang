<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
    $(document).ready( function() {
        $("#max_id").focus();
    });
</script>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_track_product'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open_multipart("system_settings/edit_track_product/" . $track_product->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('update_info'); ?></p>

            <div class="form-group">
                <?= lang('track_product_code', 'code'); ?>
                <?= form_input('code', $track_product->code, 'class="form-control" id="code" required="required" readonly'); ?>
            </div>

            <div class="form-group">
                <?= lang('track_product_max_id', 'max_id'); ?>
                <?= form_input('max_id', $track_product->max_id, 'class="form-control" id="max_id" required="required" autofocus="true"'); ?>
            </div>

            <?php echo form_hidden('id', $track_product->id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_track_product', lang('edit_track_product'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>


