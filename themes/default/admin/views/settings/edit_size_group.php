<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_size_group'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("system_settings/edit_size_group/" . $size_group->id, $attrib); ?>

        <div class="modal-body">
            <p><?= lang('update_info'); ?></p>

            <div class="form-group">
                <?= lang('size_group_name', 'name'); ?>
                <?= form_input('name', $size_group->name, 'class="form-control" id="name" required="required"'); ?>
            </div>

            <div class="form-group">
                <?= lang('size_group_description', 'description'); ?>
                <?= form_input('description', $size_group->description, 'class="form-control" id="description" required="required"'); ?>
            </div>

            <?php echo form_hidden('id', $size_group->id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_size_group', lang('edit_size_group'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>