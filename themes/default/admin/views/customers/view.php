<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= lang("customers_info"); ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="margin-bottom:0;">
                    <tbody>
                    <tr>
                        <td><strong><?= lang("customer_no"); ?></strong></td>
                        <td>
                            <span style='color: green'>
                                <?php
                                if ($customer->customer_no != '') {
                                    echo $customer->customer_no;
                                } else {
                                    echo '---';
                                }
                                ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("cname"); ?></strong></td>
                        <td>
                            <span style='color: green'><strong><?= $customer->name; ?></strong></span>

                                <a id="btn_history" class="btn tip" title="<?= lang('history') ?>" data-placement="bottom">
                                    <i class="fa fa-list"></i>
                                </a>

                        </td>
                    </tr>
                    <tr id="row_history" style="display: none;">
                        <td colspan="2">
                        <?php
                            $total_amount_per_year = $customer->each_spent*$customer->points_per_year;
                            if ($total_amount_per_year > 0) {
                        ?>

                            <strong><?= lang("total_amount_per_year"); ?>: </strong>
                            <span style="color: green"><strong>
                                <?=$this->sma->formatMoney($total_amount_per_year); ?>
                            </strong></span><br />
                        <?php } ?>

                        <?php
                            if($customer->history) {
                        ?>
                            <strong><?= lang("history"); ?>: </strong><br />
                            <span style="color: green"><?=$customer->history;?></span>
                        <?php

                            }
                        ?>


                        </td>
                    </tr>

                    <tr>
                        <td><strong><?= lang("customer_group"); ?></strong></td>
                        <td><strong><span style='color: green'><?= $customer->customer_group_name; ?></span></strong></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("address"); ?></strong></td>
                        <td><span style='color: green'><?= $customer->address; ?></span></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("phone"); ?></strong></td>
                        <td><span style='color: green'><?= $customer->phone; ?></span></td>
                    </tr>
                    <?php
                        if ($customer->id != 1) {
                    ?>
                    <tr>
                        <td><strong><?= lang("award_points"); ?></strong></td>
                        <td><strong><span style='color: green'><?= $customer->award_points; ?></span></strong></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("customer_created_date"); ?></strong></td>
                        <td><span style='color: green'><?= $customer->created_date; ?></span></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("customer_last_bill_date"); ?></strong></td>
                        <td>
                            <strong><?php echo "<span style='color: green'>" . $customer->last_bill_date . "</span>"; ?></strong>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>

                    <tr>
                        <td><strong><?= lang("fb_link"); ?></strong></td>
                        <td>
                            <a href="<?= $customer->fb_link; ?>" target="_blank">
                                <span style="color: green; font-size: 14px;"><?= $customer->fb_link; ?></span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("ccf1"); ?></strong></td>
                        <td><span style='color: green'><?= $customer->cf1; ?></span></td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer no-print">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= lang('close'); ?></button>
                <?php if ($Owner || $Admin || $GP['reports-customers']) { ?>
                    <a href="<?=admin_url('reports/customer_report/'.$customer->id);?>" target="_blank" class="btn btn-primary"><?= lang('customers_report'); ?></a>
                <?php } ?>
                <?php if ($Owner || $Admin || $GP['customers-edit']) { ?>
                    <a href="<?=admin_url('customers/edit/'.$customer->id);?>" data-toggle="modal" data-target="#myModal2" class="btn btn-primary"><?= lang('edit_customer'); ?></a>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#btn_history").click(function(){
            $("#row_history").slideToggle();
        });
    });
</script>