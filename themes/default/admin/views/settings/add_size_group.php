<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('add_size_group'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("system_settings/add_size_group", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?= lang('size_group_name', 'name'); ?>
                <?= form_input('name', '', 'class="form-control" id="code" required="required" placeholder="1-8"'); ?>
            </div>

            <div class="form-group">
                <?= lang('size_group_description', 'description'); ?>
                <?= form_input('description', '', 'class="form-control" id="name" required="required" placeholder="sz 1, sz 2, sz 3..."'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?= form_submit('add_size_group', lang('add_size_group'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<?= $modal_js ?>