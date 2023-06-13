<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('day_profit').' ('.$this->sma->hrsd($date).')'; ?></h4>
        </div>
        <div class="modal-body">
            <!-- <p><?= lang('unit_and_net_tip'); ?></p> -->
            <div class="form-group">
                <?php
                $str_guide = 'A-B-C';
                $opts[] = lang('all_warehouses');
                foreach ($warehouses as $warehouse) {
                    $opts[$warehouse->id] = $warehouse->name.' ('.$warehouse->code.')';
                }
                ?>
                <?= form_dropdown('warehouse', $opts, set_value('warehouse', $swh), 'class="form-control select" id="warehouse"'); ?>
            </div>
            <div class="table-responsive">
            <table width="100%" class="stable">
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('products_sale'); ?> (<strong>A</strong>):</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span><?= $this->sma->formatMoney($sale_by_day->grand_total); ?></span></h4>
                            <!-- <span><?= $this->sma->formatMoney($costing->sales).' ('.$this->sma->formatMoney($costing->net_sales).')'; ?></span></h4> -->
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('products_cost'); ?> (<strong>B</strong>):</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                        <span><?= $this->sma->formatMoney($costing->cost); ?></span>
                    </h4></td>
                </tr>

                <tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('shipping'); ?> (<strong>C</strong>):</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span><?php echo $this->sma->formatMoney($sale_by_day->shipping); ?></span>
                        </h4></td>
                </tr>
                <?php
                    if (!empty($bn_sales_by_day)) {
                        $str_guide .= '-D'
                ?>
                <tr>
                    <td>
                        <h4><?= lang('products_cost_bn'); ?> (<strong>D</strong>):</h4>
                    </td>
                    <td style="text-align:right;"><h4>
                        <span><?= $this->sma->formatMoney($bn_costing_amount); ?></span>
                    </h4></td>
                </tr>

                <tr>
                    <td style="border-bottom: 1px solid #EEE;" colspan="2">

                        <?php
                        foreach($bn_sales_by_day as $sale) {
                            echo '&#9734; <a data-target="#myModal2" data-toggle="modal" href="'.admin_url('sales/modal_view/'.$sale->id).'">'.$sale->reference_no.'</a> ';
                        }
                        ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('expenses'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span><?php $expense = $expenses ? $expenses->total : 0; echo $this->sma->formatMoney($expense); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #DDD;" colspan="2">
                        <h4> 
                        <span class="label label-primary" style="margin-right: 0 10px 10px 0; font-size: 16px">
                        <strong><?= lang('total_items_ex'); ?>: <?php $total_items = $total_items ? $total_items->total_items : 0; echo $total_items; ?></strong>
                        </span>
                        </h4>
                    </td>
                </tr>
                <?php
                    if (!empty($best_5)) {
                        //$str_guide .= '-D'
                ?>
                

                <tr>
                    <td style="border-bottom: 1px solid #EEE;" colspan="2">

                    <?php
                        $btns = array();
                        foreach($best_5 as $best) {
                    ?>
                            <!-- <span class="label label-primary" style="margin-right: 0 10px 10px 0; font-size: 15px">
                                <?php echo $best->product_name . '('.$this->sma->formatQuantity($best->quantity).')'?>
                            </span> -->
                    <?php
                            echo '&#9734; ' . $best->product_name . '<strong> ('.$this->sma->formatQuantity($best->quantity).') </strong>' . ' ';
                        }
                    ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><strong><?= lang('profit'); ?></strong> <span style="color:red"> (<strong><?=$str_guide?></strong>)</span>:</h4>
                    </td>
                    <td style="text-align:right;">
                        <h4>
                        <span class="label label-info" style="margin-right: 0 10px 10px 0; font-size: 19px">
                            <strong>
                                <?= $this->sma->formatMoney(($sale_by_day->grand_total - $sale_by_day->shipping) - $costing->cost - $bn_costing_amount - $expense); ?>
                            </strong></span>
                        </h4>
                    </td>
                </tr>
            </table>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('#warehouse').select2({minimumResultsForSearch: 7});
        $('#warehouse').change(function(e) {
            var wh = $(this).val();
            $.get('<?= admin_url('reports/profit/'.$date); ?>/'+wh+'/1', function(data) {
                $('#myModal').empty().html(data);
                $('#warehouse').select2({minimumResultsForSearch: 7});
            });
        });
    });
</script>
