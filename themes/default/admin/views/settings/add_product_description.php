<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_product_description'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("system_settings/add_product_description", $attrib); ?>
        <div class="modal-body">

            <div class="form-group">
                <label for="name"><?php echo $this->lang->line("product_description_name"); ?></label>
                <div class="controls"> <?php echo form_input('name', '', 'class="form-control" id="name"'); ?> </div>
            </div>
            <div class="form-group">
                <label for="description"><?php echo $this->lang->line("product_description_details"); ?></label>
                <?= form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : ''), 'class="form-control" id="description"'); ?>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_product_description', lang('add_product_description'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<?= $modal_js ?>