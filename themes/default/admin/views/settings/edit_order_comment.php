<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_product_description'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("system_settings/edit_product_description/" . $id, $attrib); ?>
        <div class="modal-body">

            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("product_description_name"); ?></label>
                <div
                    class="controls"> <?php echo form_input('name', $product_description_list->name, 'class="form-control" id="name"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="description"><?php echo $this->lang->line("product_description_details"); ?></label>
                <div class="controls">
                    <?= form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : ($product_description_list ? $product_description_list->description : '')), 'class="form-control" id="description"'); ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_product_description', lang('edit_product_description'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>