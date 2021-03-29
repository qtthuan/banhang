<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_promotion_list'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("system_settings/add_promotion_list", $attrib); ?>
        <div class="modal-body">

            <div class="form-group">
                <label for="name"><?php echo $this->lang->line("promotion_list_name"); ?></label>

                <div class="controls"> <?php echo form_input('name', '', 'class="form-control" id="name"'); ?> </div>
            </div>
            <div class="form-group">
                <label for="description"><?php echo $this->lang->line("promotion_list_description"); ?></label>
                <?= form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : ''), 'class="form-control" id="description"'); ?>
            </div>
            <div class="form-group">
                <label for="start_date"><?php echo $this->lang->line("promotion_list_start_date"); ?></label>
                <div class="controls">
                    <?= form_input('start_date', set_value('start_date'), 'class="form-control tip date" id="start_date"'); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="end_date"><?php echo $this->lang->line("promotion_list_end_date"); ?></label>
                <div class="controls">
                    <?= form_input('end_date', set_value('end_date'), 'class="form-control tip date" id="end_date"'); ?>
                </div>
            </div>
            <div class="form-group">

                <div class="controls">
                    <input name="active" type="checkbox" class="checkbox" id="active" value="1" <?= isset($_POST['active']) ? 'checked="checked"' : '' ?>/>
                    <label for="active"><?php echo $this->lang->line("promotion_list_active"); ?></label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_promotion_list', lang('add_promotion_list'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<?= $modal_js ?>