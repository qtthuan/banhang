<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('addresses') . " (" . $company->name . ")"; ?> change</h4>
        </div>

        <div class="modal-body">
            <!--<p><?= lang('list_results'); ?></p>-->

            <div class="table-responsive">
                <table id="CSUData" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-condensed table-hover table-striped">
                    <tbody>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="control-label" for="customer_group"><?php echo $this->lang->line("customer_group"); ?></label>
                                <?php
                                foreach ($customer_groups as $customer_group) {
                                    $cgs[$customer_group->id] = $customer_group->name;
                                }
                                echo form_dropdown('customer_group', $cgs, $customer->customer_group_id, 'class="form-control select" id="customer_group" style="width:100%;" required="required"');
                                ?>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>


        </div>
        <div class="modal-footer">
            <a href="<?= admin_url('customers/add_address/'.$company->id); ?>" class="btn btn-primary pull-left" data-toggle='modal' data-target='#myModal2'><?= lang('add_address'); ?></a>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
        </div>
    </div>
    <?= $modal_js ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.tip').tooltip();
        });
    </script>

