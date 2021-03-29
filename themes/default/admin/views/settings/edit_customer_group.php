<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_customer_group'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("system_settings/edit_customer_group/" . $id, $attrib); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name"><?php echo $this->lang->line("group_name"); ?></label>
                        <div class="controls">
                            <?php echo form_input('name', $customer_group->name, 'class="form-control" id="name" required="required"'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="percent"><?php echo $this->lang->line("group_percentage"); ?></label>
                        <div class="controls">
                            <?php
                            $data = array(
                                'name' => 'percent',
                                'id'   => 'percent',
                                'class'=> 'form-control',
                                'type' => 'number',
                                'value' => $customer_group->percent,
                                'required' => 'required',
                                'placeholder' => $this->lang->line('group_percentage_txt')
                            );
                            echo form_input($data); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="points_required"><?php echo $this->lang->line("group_points_required"); ?></label>
                        <div class="controls">
                            <?php
                            $data = array(
                                'name' => 'points_required',
                                'id'   => 'points_required',
                                'class'=> 'form-control',
                                'type' => 'number',
                                'value' => $customer_group->points_required,
                                'required' => 'required'
                            );
                            echo form_input($data); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="reset_points_percent"><?php echo $this->lang->line("group_reset_points_percent"); ?></label>
                        <div class="controls">
                            <?php
                            $data = array(
                                'name' => 'reset_points_percent',
                                'id'   => 'reset_points_percent',
                                'class'=> 'form-control',
                                'type' => 'number',
                                'value' => $customer_group->reset_points_percent,
                                'required' => 'required',
                                'placeholder' => $this->lang->line('group_reset_points_percent_txt')
                            );
                            echo form_input($data); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="maintain_sales"><?php echo $this->lang->line("group_maintain_sales"); ?></label>
                        <div class="controls">
                            <?php
                            $data = array(
                                'name' => 'maintain_sales',
                                'id'   => 'maintain_sales',
                                'class'=> 'form-control',
                                'type' => 'number',
                                'value' => $customer_group->maintain_sales,
                                'required' => 'required',
                                'placeholder' => $this->lang->line('group_maintain_sales_txt')
                            );
                            echo form_input($data); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="note"><?php echo $this->lang->line("group_note"); ?></label>
                        <div class="controls">
                            <?php echo form_input('note', $customer_group->note, 'class="form-control" id="note"'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_customer_group', lang('edit_customer_group'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>