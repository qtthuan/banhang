<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_promotion_list'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("system_settings/edit_promotion_list/" . $id, $attrib); ?>
        <div class="modal-body">

            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("promotion_list_name"); ?></label>
                <div
                    class="controls"> <?php echo form_input('name', $promotion_list->name, 'class="form-control" id="name"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="description"><?php echo $this->lang->line("promotion_list_description"); ?></label>
                <div class="controls">
                    <?= form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : ($promotion_list ? $promotion_list->description : '')), 'class="form-control" id="description"'); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="start_date"><?php echo $this->lang->line("promotion_list_start_date"); ?></label>
                <div class="controls">
                    <?= form_input('start_date', set_value('start_date', $promotion_list->start_date ? $this->sma->hrsd($promotion_list->start_date) : ''), 'class="form-control tip date" id="start_date"'); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="end_date"><?php echo $this->lang->line("promotion_list_end_date"); ?></label>
                <div class="controls">
                    <?= form_input('end_date', set_value('end_date', $promotion_list->end_date ? $this->sma->hrsd($promotion_list->end_date) : ''), 'class="form-control tip date" id="end_date"'); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="price"><?php echo $this->lang->line("promotion_list_price"); ?></label>
                <div class="controls">
                    <?php echo form_input('price', $promotion_list->price, 'class="form-control" id="price"'); ?><br />
                    <input name="for_discount" type="checkbox" class="checkbox" id="for_discount" value="1" <?= empty($promotion_list->for_discount) ? '' : 'checked="checked"' ?>/>
                    <label for="for_discount" class="padding05"><?= lang('promotion_list_for_discount') ?></label>
                </div>
            </div>

            <div class="form-group">
                <div class="controls">
                    <input name="active" type="checkbox" class="checkbox" id="active" value="1" <?= empty($promotion_list->active) ? '' : 'checked="checked"' ?>/>
                    <label for="active" class="padding05"><?= lang('promotion_list_active') ?></label>
                </div>
            </div>

            <div class="form-group">
                <div class="controls">
                    <input name="promo_cut" type="checkbox" class="checkbox" id="promo_cut" value="1" <?= empty($promotion_list->promo_cut) ? '' : 'checked="checked"' ?>/>
                    <label for="promo_cut" class="padding05"><?= lang('promotion_list_promo_cut') ?></label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_promotion_list', lang('edit_promotion_list'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>