<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($modal) { ?>
<div class="modal-dialog no-modal-header" role="document"><div class="modal-content">
    <div class="modal-body" style="padding: 0 0 10px 0  !important; font-family: Tahoma, Geneva, sans-serif !important;">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
    <?php
} else {
    ?><!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title><?=$page_title . " " . lang("no") . " " . $inv->id;?></title>
        <base href="<?=base_url()?>"/>
        <meta http-equiv="cache-control" content="max-age=0"/>
        <meta http-equiv="cache-control" content="no-cache"/>
        <meta http-equiv="expires" content="0"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <link rel="shortcut icon" href="<?=$assets?>images/icon.png"/>
        <link rel="stylesheet" href="<?=$assets?>styles/theme.css" type="text/css"/>
        <link rel="stylesheet" href="<?=$assets?>styles/style.css" type="text/css"/>
        <style type="text/css" media="all">
            body { color: #000; }
            #wrapper { max-width: 490px; margin: 0 auto; padding-top: 5px; }
            .text-right { text-align: right !important; }

            .btn { border-radius: 0; margin-bottom: 5px; }
            .bootbox .modal-footer { border-top: 0; text-align: center; }
            h3 { margin: 5px 0; }
            .order_barcodes img { float: none !important; margin-top: 5px; }
            @media print {
                .no-print { display: none; }
                #wrapper { max-width: 480px; width: 100%; min-width: 250px; margin: 0 auto; }
                .no-border { border: none !important; }
                .border-bottom { border-bottom: 1px solid #ddd !important; }
                table tfoot { display: table-row-group; }
            }
        </style>
    </head>

    <body>
        <?php
    } ?>
    <div id="wrapper">

        <div id="receiptData">
            <div class="no-print">
                <?php
                if ($message) {
                    ?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <?=is_array($message) ? print_r($message, true) : $message;?>
                    </div>
                    <?php
                } ?>
            </div>
            <div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">

                <?php
                if ($modal) {
                    ?>
                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close'); ?></button>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <span class="col-xs-12">
                   
                        <a class="btn btn-block btn-warning" href="<?= admin_url('pos'); ?>"><?= lang("back_to_pos"); ?></a>
                   

                    <?php
                } ?>
                <div style="clear:both;"></div>
            </div>
            <div id="receipt-data" style="margin-right: 20px;">
                <div class="text-center">
                <?php if ($inv->warehouse_id != 3) { ?>
                    <strong><span style="font-size: 17px; text-transform: uppercase;"><?=$this->Settings->site_name?></span></strong><br />
                <?php } else { ?>
                    <strong><span style="font-size: 17px; text-transform: uppercase;"><?=lang("tiem_nuoc_mini");?></span></strong><br />
                <?php } ?>
                    <?php //$this->sma->print_arrays($inv);
                    echo "<p style='padding-top: 5px;'>" . $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country .
                    "<br> " . $biller->phone . " - " . $biller->cf1 . "<br />";
                    ?>
                    <br /><strong><span style="font-size: 15px; text-transform: uppercase"><?=lang('pos_bill');?></span><br>
                    
                    <span style="font-size: 15px;"><?=$inv->reference_no?></span></strong><br />
                    <?php

                    // comment or remove these extra info if you don't need

                    
                    if (!empty($biller->cf4) && $biller->cf4 != "-") {
                        echo "<br>" . lang("bcf4") . ": " . $biller->cf4;
                    }
                    if (!empty($biller->cf5) && $biller->cf5 != "-") {
                        echo "<br>" . lang("bcf5") . ": " . $biller->cf5;
                    }
                    if (!empty($biller->cf6) && $biller->cf6 != "-") {
                        echo "<br>" . lang("bcf6") . ": " . $biller->cf6;
                    }
                    // end of the customer fields

                    echo "<br>";
                    if ($pos_settings->cf_title1 != "" && $pos_settings->cf_value1 != "") {
                        echo $pos_settings->cf_title1 . ": " . $pos_settings->cf_value1 . "<br>";
                    }
                    if ($pos_settings->cf_title2 != "" && $pos_settings->cf_value2 != "") {
                        echo $pos_settings->cf_title2 . ": " . $pos_settings->cf_value2 . "<br>";
                    }
                    echo '</p>';
                    ?>
                </div>
                <?php
                if ($Settings->invoice_view == 1) {
                    ?>
                    <div class="col-sm-12 text-center">
                        <h4 style="font-weight:bold;"><?=lang('tax_invoice');?></h4>
                    </div>
                    <?php
                }
                echo "<p>" .lang("date") . ": " . $this->sma->hrld($inv->date) . "<br>";
                if (!empty($inv->return_sale_ref)) {
                    echo '<p>'.lang("return_ref").': '.$inv->return_sale_ref;
                    if ($inv->return_id) {
                        echo ' <a data-target="#myModal2" data-toggle="modal" href="'.admin_url('sales/modal_view/'.$inv->return_id).'"><i class="fa fa-external-link no-print"></i></a><br>';
                    } else {
                        echo '</p>';
                    }
                }
                echo "<p>";
                ?>
            <?php if ($inv->warehouse_id != 3 || ($inv->warehouse_id == 3 && $inv->customer != 'KHÁCH LẺ')) { ?>
                <!--Nếu kho Ba-Ni hoặc (Tiệm Nước Mini và Có nhập tên) => Hiện Tên Khách-->
                <?=lang("customer") . ': <strong>' .  $inv->customer?></strong><span style="color:red; font-size: 13px; margin-left: 15px;" class="no-print"><a href="#" id="view-customer" class="external pos-tip" title="<?=lang('customers_info');?>" data-toggle="modal" data-target="#myModal2"><i class="fa fa-bars" id="addIcon" style="font-size: 1.7em;"></i></a></span>
                <br>
            <?php } ?>
                <?=$customer->customer_group_percent != 0 ?  lang("customers_group_txt") . ': <strong>' . $customer->customer_group_name . '</strong><br>' : ''?>

                <?php
                //$this->sma->print_arrays($customer);
                //echo lang("customer") . ": " . ($customer->company && $customer->company != '-' ? $customer->company : $customer->name) . "<br>";
                if ($pos_settings->customer_details && $customer->id != 1) {
                    if ($customer->vat_no != "-" && $customer->vat_no != "") {
                        echo "<br>" . lang("vat_no") . ": " . $customer->vat_no;
                    }
                    if ($customer->customer_group_id != 4) { // Nhóm Baemin không hiển thị thông tin này
                        if ($customer->address != 'TP Cần Thơ') {
                            echo lang("address") . ": " . $customer->address . "<br>";
                        }
                        if (strpos($customer->phone, '0000') === false) {
                            echo lang("tel") . ": " . $customer->phone . "<br>";
                            //echo str_starts_with($customer->phone, "000");
                       }
                    }
                    if (!empty($customer->cf1) && $customer->cf1 != "-") {
                        echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                    }
                   
                    if (!empty($customer->cf3) && $customer->cf3 != "-") {
                        echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                    }
                    if (!empty($customer->cf4) && $customer->cf4 != "-") {
                        echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                    }
                    if (!empty($customer->cf5) && $customer->cf5 != "-") {
                        echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                    }
                    if (!empty($customer->cf6) && $customer->cf6 != "-") {
                        echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                    }
                }
                echo "</p>";
                ?>

                <div style="clear:both;"></div>
                <table class="table table-hover table-striped table-condensed" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th colspan="5" class="text-center" style="border-top: 1px solid black"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $r = 1; $category = 0;
                    $tax_summary = array();
                    $is_saleoff = 0;
                    $has_discount = 0;
                    $total_with_no_points = 0;
                    $qr_pay = 0;
                    //$this->sma->print_arrays($inv, $rows);
                    //$this->sma->print_arrays($rows);

                    foreach ($rows as $row) {

                        if (strpos($row->product_name, 'ale') !== false || strpos($row->product_name, 'giảm') !== false) { // sản phẩm có chữ sale hoặc Sale
                            $is_saleoff++;
                        }
                    ?>
                    <tr>
                        <td colspan="5">  
                                       
                        <?php 
                            // Nước ngọt có thêm chữ Z_, khi xem bill thì cắt bỏ
                            $product_name = $row->product_name;
                            if (strpos($row->product_name, '_') !== false) {
                                $product_name = substr($row->product_name, 2, strlen($row->product_name));
                            } 
                        ?>
                            <span><?=$r?></span>&#8594;&nbsp;<?=product_name($product_name, ($printer ? $printer->char_per_line : null))?>
                            <?php 
                                if (!empty($row->comment)) { echo ' <strong>(' . $row->comment . ')</strong>'; } 
                                if (!empty($row->comment_name)) { echo ' <strong> - ' . $row->comment_name . '</strong>'; } 
                            ?>
                            <?php if($inv->sale_language) {
                                echo '<br /><span style="font-size: 10px">&nbsp;&nbsp;# ' . $row->product_name_en . '</span>';
                            }?>
                        </td>
                    </tr>
                    <tr>
                        <?php
                            $str_product_code_class = '';
                            $str_variants = ''; 
                            if ($row->variant) {
                                if ($inv->warehouse_id == 3) { // Tiệm nước mini
                                    $str_product_code_class = 'circle_text2';
                                    $row->product_code = '';
                                    if ($this->sma->getSizeNumber($row->variant) == 'M') {
                                        $str_variants = ' <strong>Size M</strong>';
                                    } elseif ($this->sma->getSizeNumber($row->variant) == 'L') {
                                        $str_variants = ' <strong>Size L</strong>';
                                    } else {
                                        $str_variants = ' <strong>' . $row->variant . '</strong>';
                                    }
                                } else {
                                        $str_variants = '-' . $this->sma->getSizeNumber($row->variant);
                                }
                            }
                        ?>
                        <td colspan="2" style="font-size: 11px;">
                            <?php if ($str_variants != '') { ?>
                                <span class="<?=$str_product_code_class;?>" style="font-size: 11px; font-weight: bold"> 
                                    <strong><?=$row->product_code . $str_variants ?></strong>
                                </span>
                            <?php } else { echo "&nbsp;"; } ?>
                            
                        </td>
                        <td class="text-right"><?=$this->sma->formatQuantity($row->quantity)?></td>
                        <td class="text-right">
                            <?php

                                echo $this->sma->formatMoney($row->unit_price);
                                if ($row->item_discount != 0 || $row->is_promo == 1) {
                                    $is_saleoff++;
                                    $has_discount++;
                                    if ($row->is_promo == 1) {
                                        echo ' <span style="text-decoration: line-through">' . $this->sma->formatMoney($row->promo_original_price) . '</span>';
                                    } elseif($row->item_discount != 0) {
                                        echo ' <span style="text-decoration: line-through">' . $this->sma->formatMoney($row->real_unit_price + $row->added_price) . '</span>';
                                    }
                                }
                                if ($row->no_points == 1) {
                                    $is_saleoff++;
                                }
                            ?>
                        </td>
                        <td class="text-right"><?=$this->sma->formatMoney($row->subtotal)?></td>
                    </tr>

                    <?php
                        if ($row->no_points == 1 && $row->item_discount == 0) {
                            $total_with_no_points += $row->unit_price;
                        }
                        //echo '<tr><td colspan="2" class="no-border">#' . $r . ': &nbsp;&nbsp;' . product_name($row->product_name, ($printer ? $printer->char_per_line : null)) . ($row->variant ? ' (' . $row->variant . ')' : '') . '<span class="pull-right">' . ($row->tax_code ? '*'.$row->tax_code : '') . '</span></td></tr>';
                        //echo '<tr><td class="no-border border-bottom">' . $this->sma->formatQuantity($row->quantity) . ' x '.$this->sma->formatMoney($row->unit_price).'</td><td class="no-border border-bottom text-right">' . $this->sma->formatMoney($row->subtotal) . '</td></tr>';

                        $r++;
                    }
                    if ($return_rows) {
                        echo '<tr class="warning"><td colspan="100%" class="no-border"><strong>'.lang('returned_items').'</strong></td></tr>';


                        foreach ($return_rows as $row) {
                    ?>
                            <tr>
                                <td colspan="5"><span><?=$r?></span>&#8594;&nbsp;<?=product_name($row->product_name, ($printer ? $printer->char_per_line : null))?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-size: 11px;"><?=$row->product_code . ($row->variant ? '-' . $this->sma->getSizeNumber($row->variant)  : '')?></td>
                                <td class="text-right"><?=$this->sma->formatQuantity($row->quantity)?></td>
                                <td class="text-right">
                                    <?php
                                        echo $this->sma->formatMoney($row->unit_price);
                                    if ($row->item_discount != 0 || $row->is_promo == 1) {
                                        $is_saleoff++;
                                        $has_discount++;
                                        if ($row->is_promo == 1) {
                                            echo '(<span style="text-decoration: line-through">' . $this->sma->formatMoney($row->promo_original_price + $row->added_price) . '</span>)';
                                        } elseif($row->item_discount != 0) {
                                            echo '(<span style="text-decoration: line-through">' . $this->sma->formatMoney($row->real_unit_price + $row->added_price) . '</span>)';
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="text-right"><?=$this->sma->formatMoney($row->subtotal)?></td>
                            </tr>
                    <?php
                            $r++;
                        }
                    }

                    ?>

                    </tbody>
                    <tfoot>
                    <?php 
                        $mini_count_rows = 0;
                        if($inv->warehouse_id != 3) { 
                            $total_amount = lang("total_amount");
                            $total = lang("total");
                                
                         } else {
                            $total_amount = lang("total_amount_mini");
                            $total = lang("total_mini");
                        } 
                    ?>
                        <tr>
                            <th colspan="2">
                                <span style="font-size: 13px"><?=lang("total_items");?>:&nbsp;<?=$inv->total_items;?></span>
                            </th>
                            <th colspan="2"><div style="margin-left: 2px;">
                                <?=$total_amount;?>
                            </div></th>
                            <th class="text-right amount" colspan="2">
                                <?=$this->sma->formatMoney($return_sale ? (($inv->total + $inv->product_tax)+($return_sale->total + $return_sale->product_tax)) : ($inv->total + $inv->product_tax));?>
                                <?php $qr_pay = return_sale ? (($inv->total + $inv->product_tax)+($return_sale->total + $return_sale->product_tax)) : ($inv->total + $inv->product_tax); ?>
                            </th>
                        </tr>
                        <?php
                            if ($inv->shipping != 0) {
                                $qr_pay += $inv->shipping;
                                $mini_count_rows++;
                        ?>
                            <tr>
                                <th style="border-top: none" colspan="2">&nbsp;</th>
                                <th style="border-top: none" colspan="2"><div style="margin-left: 2px;"><?= lang("shipping"); ?></div></th>
                                <th style="border-top: none" class="text-right"
                                    colspan="2"><?= $this->sma->formatMoney($inv->shipping); ?></th>
                            </tr>

                        <?php
                            }
                        ?>
                        <?php

                        if ($inv->order_discount != 0) {
                            $mini_count_rows++;
                            //if ($has_discount > 0) {
                            $is_saleoff++;
                            //}
                            $str_discount = '';
                            if (strpos($inv->order_discount_id, '%') !== false) {
                                $str_discount = ' (' . $inv->order_discount_id . ')';
                            }
                        ?>
                            <tr>
                                <th style="border-top: none" class="text-right" colspan="2">
                                <?php
                                    $opos = strpos($inv->order_discount_id, '%');
                                    if ($opos !== FALSE) {
                                        $ods = explode("%", $inv->order_discount_id);
                                ?>
                                    <span style="color:red; font-size: 13px;" class="no-print">                                        
                                    <?php if ($inv->sale_status == 'returned') { ?>
                                        <?= $this->sma->formatMoney(($inv->grand_total_extra) * -1 + $inv->order_discount);?> x <?= $ods[0] / 100 ?> &#129136;
                                    <?php } else { ?>
                                        <?= $this->sma->formatMoney($inv->grand_total_extra + $inv->order_discount);?> x <?= $ods[0] / 100 ?> &#129136;
                                    <?php } ?>
                                    </span>
                                <?php
                                    }
                                ?>
                                </th>
                                <th style="border-top: none" colspan="2"><div style="margin-left: 2px;"><?=lang("order_discount") . $str_discount;?></div></th>
                                <th style="border-top: none" class="text-right" colspan="2">
                                <?php
                                    if ($inv->sale_status == 'returned') {
                                        echo $this->sma->formatMoney($inv->order_discount);
                                    } else {
                                        echo '-' . $this->sma->formatMoney($inv->order_discount);
                                    }
                                    $qr_pay -= $inv->order_discount;
                                    //$this->sma->print_arrays($inv);
                                ?>

                                </th>
                            </tr>

                        <?php
                        }
                        ?>
                        <?php
                        if ($inv->change_points > 0) {
                            $mini_count_rows++;
                            $qr_pay -= $inv->change_points;
                            ?>
                            <tr>
                                <th style="border-top: none" colspan="2">&nbsp;</th>
                                <th style="border-top: none" colspan="2"><div style="margin-left: 2px;"><?= lang("pay_by_points"); ?> (<?=$inv->change_points/1000?>đ)</div></th>
                                <th style="border-top: none" class="text-right"
                                    colspan="2">-<?= $this->sma->formatMoney($inv->change_points); ?></th>
                            </tr>
                        <?php
                        }
                        ?>

                        <?php
                        if ($inv->return_amount != 0) {
                            $mini_count_rows++;
                            $qr_pay -= $inv->return_amount;
                        ?>
                            <tr>
                                <th style="border-top: none" colspan="2">&nbsp;</th>
                                    <th style="border-top: none" colspan="2"><div style="margin-left: 2px;"><?= lang("return_amount"); ?></div></th>
                                <th style="border-top: none" class="text-right"
                                    colspan="2">-<?= $this->sma->formatMoney($inv->return_amount); ?></th>
                            </tr>
                        <?php
                        }
                        ?>
                        <?php
                        if ($return_sale) {
                            $mini_count_rows++;
                            if ($return_sale->surcharge != 0) {
                        ?>
                                <tr>
                                    <th style="border-top: none" colspan="2">&nbsp;</th>
                                    <th style="border-top: none" colspan="2"><div style="margin-left: 2px;"><?=lang("order_discount");?></div></th>
                                    <th style="border-top: none" class="text-right" colspan="2"><?=$this->sma->formatMoney($return_sale->surcharge);?></th>
                                </tr>
                        <?php
                            }
                        }

                        ?>
                        <?php if ($mini_count_rows > 0) { ?>
                        <tr>
                            <th colspan="2">&nbsp;</th>
                            <th colspan="2"><div style="margin-left: 2px;"><?=$total;?></div></th>
                            <th class="text-right thanhtoan" colspan="2">
                                <?=$this->sma->formatMoney($return_sale ? (($inv->grand_total + $inv->rounding)+$return_sale->grand_total) : ($inv->grand_total + $inv->rounding));?>
                                
                            </th>
                        </tr>
                        <?php } ?>
                        <?php if ($inv->warehouse_id == 3) { ?>
                            <?php $qr_pay = $return_sale ? (($inv->grand_total + $inv->rounding)+$return_sale->grand_total) : ($inv->grand_total + $inv->rounding); ?>
                        <?php } ?>
                        <?php if (($inv->paid) + $inv->change_points < $inv->grand_total) {?>
                            <tr>
                                <th style="border-top: none" colspan="2">&nbsp;</th>
                                <th colspan="2"><div style="margin-left: 2px;"><?=lang("paid_amount");?></div></th>
                                <th class="text-right" colspan="2">
                                    <?=$this->sma->formatMoney($return_sale ? ($inv->paid+$return_sale->paid) : $inv->paid);?>
                                </th>
                            </tr>
                            <tr>
                                <th style="border-top: none" colspan="2">&nbsp;</th>
                                <th style="border-top: none" colspan="2"><div style="margin-left: 2px;"><?=lang("due_amount");?></div></th>
                                <th style="border-top: none" class="text-right" colspan="2">
                                    <?=$this->sma->formatMoney(($return_sale ? (($inv->grand_total + $inv->rounding)+$return_sale->grand_total) : ($inv->grand_total + $inv->rounding)) - ($return_sale ? ($inv->paid+$return_sale->paid) : $inv->paid));?>
                                </th>
                            </tr>
                        <?php } ?>
                            
                        <?php
                        //if (($payments && $customer->customer_group_id != 4 && $inv->warehouse_id != 3)) { // Nhóm Baemin không hiển thị nội dung này

                            foreach ($payments as $payment) {
                        ?>       
                            
                            <?php if (($payment->paid_by == 'cash' || $payment->paid_by == 'pts' || $payment->paid_by == 'deposit') && $payment->pos_paid) { ?> 
                            <tr style="border-top: dotted 1px black;">
                                <th colspan="2" style="border-top: none;">&nbsp;</th>
                                <th colspan="2" style="border-top: none;"><div style="margin-left: 2px; color: red"><?=lang("total_paying1");?></div></th>
                                <th class="text-right" colspan="2" style="border-top:none; color: red">
                                <?php echo $this->sma->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : ''); ?>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" style="border-top: none;">&nbsp;</th>
                                <th colspan="2" style="border-top: none;"><div style="margin-left: 2px; color: red;"><?=lang("change");?></div></th>
                                <th class="text-right" colspan="2" style="border-top: none; color:red;">
                                <?php echo ($payment->pos_balance > 0 ? $this->sma->formatMoney($payment->pos_balance) : 0); ?>
                                </th>
                            </tr>
                            <?php } elseif (($payment->paid_by == 'cc' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe')) { ?>
                                
                            <tr>
                                <th colspan="2" style="border-top: none;">&nbsp;</th>
                                <th colspan="2" style="border-top: none;">
                                    <div style="margin-left: 2px; color: red;"><?=lang($payment->paid_by);?></div>
                                </th>
                                <th class="text-right" colspan="2" style="border-top: none; color:red;">
                                <?php echo $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : ''); ?>
                                </th>
                            </tr>
                            <?php } elseif ($payment->paid_by == 'pos') {?>
                            <tr>
                                <th colspan="2" style="border-top: none;">&nbsp;</th>
                                <th colspan="2" style="border-top: none;">
                                    <div style="margin-left: 2px; color: red;"><?=lang('pos_txt');?></div>
                                </th>
                                <th class="text-right" colspan="2" style="border-top: none; color:red;">
                                <?php echo $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : ''); ?>
                                </th>
                            </tr>
                            <?php } ?>
                            <?php } // end foreach ?>
                        <?php //} // end if payments ?>
                    </tfoot>
                </table>
                <?php
                if ($payments) {
                    echo '<table class="table table-striped table-condensed" style="display: none; margin-bottom: 10px; margin-top: 5px;"><tfoot>';
                    foreach ($payments as $payment) {
                        echo '<tr>';
                        if (($payment->paid_by == 'cash' || $payment->paid_by == 'pts' || $payment->paid_by == 'deposit') && $payment->pos_paid) {
                            //echo '<th colspan="3" style="color: red;">' . lang("total_paying1") . ': <strong>' . $this->sma->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</strong></td>';
                           // echo '<th colspan="2" class="text-right" style="color: red;">' . lang("change") . ': <strong>' . ($payment->pos_balance > 0 ? $this->sma->formatMoney($payment->pos_balance) : 0) . '</strong></td>';
                        } elseif (($payment->paid_by == 'cc' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe')) {
                            /*echo '<th colspan="2">&nbsp;</th>
                                  <th colspan="2" style="color: red;"> <div style="margin-left: 2px;">' 
                                    . lang($payment->paid_by) . ' 
                                    </div></th>' 
                                . '<th class="text-right">
                                    <strong>' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</strong>
                                  </th>';*/
                            //echo '<th colspan="3" class="text-left">' . ': ' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</th>';
                            //echo '<th>' . lang("name") . ': ' . $payment->cc_holder . '</th>';
                        } elseif ($payment->paid_by == 'pos') {
                            //echo '<th colspan="5" style="color: red;">
                        //</button> ' . lang('pos_txt') . ': <strong>' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</strong></th>';

                        } elseif ($payment->paid_by == 'vnpay') {
                            echo '<th colspan="5" style="color: red;">
                        </button> ' . lang($payment->paid_by) . ': <strong>' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</strong></th>';

                        } elseif ($payment->paid_by == 'Cheque' && $payment->cheque_no) {
                            /*echo '<th><button type="button" class="btn btn-primary btn-xs" id="addSupplier"><i class="fa fa-dollar"></i>
                        </button> ' . lang($payment->paid_by) . '</th>';
                            echo '<th>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</th>';
                            echo '<th>' . lang("cheque_no") . ': ' . $payment->cheque_no . '</th>';*/
                        } elseif ($payment->paid_by == 'gift_card' && $payment->pos_paid) {
                            /*echo '<th><button type="button" class="btn btn-primary btn-xs" id="addSupplier"><i class="fa fa-dollar"></i>
                        </button> ' . lang($payment->paid_by) . '</th>';
                            echo '<th>' . lang("no") . ': ' . $payment->cc_no . '</th>';
                            echo '<th>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</th>';
                            echo '<th>' . lang("balance") . ': ' . ($payment->pos_balance > 0 ? $this->sma->formatMoney($payment->pos_balance) : 0) . '</th>';*/
                        }
                        echo '</tr>';
                    }
                    echo '</tfoot></table>';
                }

                if ($return_payments) {
                    //echo '<strong>'.lang('return_payments').'</strong>';
                    echo '<table class="table table-striped table-condensed" style="display: none;"><tbody>';
                    foreach ($return_payments as $payment) {
                        $payment->amount = (0-$payment->amount);
                        echo '<tr>';
                        if (($payment->paid_by == 'cash' || $payment->paid_by == 'deposit') && $payment->pos_paid) {
                            echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                            echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                            echo '<td>' . lang("change") . ': ' . ($payment->pos_balance > 0 ? $this->sma->formatMoney($payment->pos_balance) : 0) . '</td>';
                        } elseif (($payment->paid_by == 'cc' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe') && $payment->cc_no) {
                            echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                            echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                            echo '<td>' . lang("no") . ': ' . 'xxxx xxxx xxxx ' . substr($payment->cc_no, -4) . '</td>';
                            echo '<td>' . lang("name") . ': ' . $payment->cc_holder . '</td>';
                        } elseif ($payment->paid_by == 'Cheque' && $payment->cheque_no) {
                            echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                            echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                            echo '<td>' . lang("cheque_no") . ': ' . $payment->cheque_no . '</td>';
                        } elseif ($payment->paid_by == 'gift_card' && $payment->pos_paid) {
                            echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                            echo '<td>' . lang("no") . ': ' . $payment->cc_no . '</td>';
                            echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                            echo '<td>' . lang("balance") . ': ' . ($payment->pos_balance > 0 ? $this->sma->formatMoney($payment->pos_balance) : 0) . '</td>';
                        } elseif ($payment->paid_by == 'other' && $payment->amount) {
                            echo '<td>' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
                            echo '<td>' . lang("amount") . ': ' . $this->sma->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . ($payment->return_id ? ' (' . lang('returned') . ')' : '') . '</td>';
                            echo $payment->note ? '</tr><td colspan="2">' . lang("payment_note") . ': ' . $payment->note . '</td>' : '';
                        }
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                }

                if ($Settings->invoice_view == 1) {
                    if (!empty($tax_summary)) {
                        echo '<h4 style="font-weight:bold;">' . lang('tax_summary') . '</h4>';
                        echo '<table class="table table-condensed"><thead><tr><th>' . lang('name') . '</th><th>' . lang('code') . '</th><th>' . lang('qty') . '</th><th>' . lang('tax_excl') . '</th><th>' . lang('tax_amt') . '</th></tr></td><tbody>';
                        foreach ($tax_summary as $summary) {
                            echo '<tr><td>' . $summary['name'] . '</td><td class="text-center">' . $summary['code'] . '</td><td class="text-center">' . $this->sma->formatQuantity($summary['items']) . '</td><td class="text-right">' . $this->sma->formatMoney($summary['amt']) . '</td><td class="text-right">' . $this->sma->formatMoney($summary['tax']) . '</td></tr>';
                        }
                        echo '</tbody></tfoot>';
                        echo '<tr><th colspan="4" class="text-right">' . lang('total_tax_amount') . '</th><th class="text-right">' . $this->sma->formatMoney($return_sale ? $inv->product_tax+$return_sale->product_tax : $inv->product_tax) . '</th></tr>';
                        echo '</tfoot></table>';
                    }
                }
                ?>

                <?php if ($is_saleoff > 0) { $str_point_note = '<br><br>' . lang('award_points_sale_notes'); } ?>
                <?php
                $str_this_sale = round(($inv->grand_total_extra/$Settings->each_spent)*$Settings->ca_point, 1);
                if ($inv->grand_total_extra > 0) {
                    $str_this_sale = round(($inv->grand_total_extra/$Settings->each_spent)*$Settings->ca_point, 1);

                }
                ?>
                <?php if ($inv->warehouse_id != 3) { // Không hiển thị thông tin điểm đối với Tiệm nước Mini ?>
                <?= $customer->award_points != 0 && $Settings->each_spent > 0 ? '<div class="text-center" style="text-decoration: underline"><strong>' . lang('award_points_title') . '</strong></div><p class="text-center" style="font-size: 12px;"><br>'.lang('this_sale').': <strong>'.$str_this_sale.'</strong><br>'.
                lang('total').' '.lang('award_points').': <strong>'. $customer->award_points . '</strong><span style="font-size:11px">' . $str_point_note . '</span></p>' : ''; ?>
                <?php } ?>
                <?= $inv->note ? '<p class="text-center" style="font-size: 17px; font-weight: bold; color: #006400; margin-right: 15px;">' . $this->sma->decode_html($inv->note) . '</p>' : ''; ?>
                <?= $inv->staff_note ? '<p class="no-print" style="font-size: 13px; color: #006400;"><strong>' . lang('staff_note') . ':</strong> ' . $this->sma->decode_html($inv->staff_note) . '</p>' : ''; ?>
                <?php
                    $inv_date = date('Y-m-d', strtotime($inv->date));
                    $display_promo_cut = 0;
                    $promo_bill_price = 0;
                    if ($promo->for_discount == 1) {
                        $promo_bill_price = $inv->grand_total;
                    } else {
                        $promo_bill_price = $inv->grand_total_extra;
                    }

                    //echo '<h1>' . $promo_bill_price . '</h1>';
                    if ($inv_date >= $promo->start_date && $inv_date <= $promo->end_date
                        && $promo_bill_price >= $promo->price && $customer->id != 1) {

                        $display_promo_cut = 1;
                        echo '<p style="border-top: 1px solid black;">';
                        echo '<p class="text-center"><strong>'.$promo->name.'</strong>
                            <p>'.$promo->description.'</p>';
                        echo '</p>';
                    }
                ?>
            </div>
            <div style="clear:both;"></div>
            <div style="font-size: 12px; text-align: center;margin-right: 25px">
                <img src="https://img.vietqr.io/image/mb-VQRQAGEUL2470-qr_only.jpg?amount=<?=$qr_pay?>&accountName=Lu%20Nguyet%20Binh" style="width: 130px">
                <br /><?=lang('bank_info_mb')?>
            <?php //if ($inv->warehouse_id == 3) { ?>
                <!-- <img src="https://img.vietqr.io/image/vietcombank-NP82501394269214VCB-qr_only.jpg?amount=<?=$qr_pay?>&accountName=Lu%20Nguyet%20Binh" style="width: 130px"> -->
                 
                <!-- <br /><?=lang('bank_info_vcb')?> -->
            <?php //} else { ?>
                <!-- <img src="https://img.vietqr.io/image/vietinbank-663614169999-qr_only.jpg?amount=<?=$qr_pay?>&accountName=Lu%20Nguyet%20Binh" style="width: 130px"> -->
                <!-- <br /><?=lang('bank_info_vietin')?> -->
            <?php //} ?>
                <!-- <img src="https://img.vietqr.io/image/techcombank-MS00P00000000605480-qr_only.jpg?amount=<?=$qr_pay?>&accountName=Lu%20Nguyet%20Binh" style="width: 105px">
                <br /><?=lang('bank_info_tech')?> -->
                
            </div>
            <div style="padding: 0 0 10px; margin-right: 30px;">
                <p style="border-top: 1px solid black;">
                <?php if ($biller->cf3) { ?>
                    <div class="text-center" style="padding-bottom: 7px; font-size: 14px; font-style: italic"><?= $biller->cf3 ? $this->sma->decode_html($biller->cf3) : ''; ?></div>
                <?php } ?>
                
                <?php if ($inv->warehouse_id == 1) { ?>
                    <div style="font-size: 11px;">                    
                        <?= $biller->invoice_footer ? $this->sma->decode_html($biller->invoice_footer) : ''; ?>
                    </div>
                <?php } elseif ($inv->warehouse_id == 3) { ?>
                    <div style="font-size: 11px; text-align: center;">               
                        <?= $biller->invoice_footer ? $this->sma->decode_html($biller->invoice_footer) : ''; ?>
                    </div>
                <?php } ?>    
                
                <?php if ($biller->cf2) { ?>
                    <div style="margin: 10px 0; font-size: 14px;"><?= $biller->cf2 ? '<p class="text-center">'.$this->sma->decode_html($biller->cf2).'</p>' : ''; ?></div>
                <?php } ?>
                </p>
            </div>
            <?php if ($display_promo_cut == 1 && $promo->promo_cut == 1 && $customer->id != 1) { ?>
            <div style="clear:both;"></div>
            <div style="padding: 0 10px 10px;">
                <p style="border-top: 1px dotted black;">
                <div class="text-center" style="padding-bottom: 7px; font-size: 14px;">
                    <?=lang('promo_header')?><br />
                <?= $this->sma->save_barcode($inv->reference_no, 'code128', 25, false); ?><br />
                <strong><?=$inv->reference_no?></strong>
                </div>
                <div style="font-size: 12px;">
                    <?=lang("customer") . ': ' . ($customer->company && $customer->company != '-' ? $customer->company : $customer->name)?>
                    <br>
                    <?=$customer->customer_group_percent != 0 ?  lang("customers_group_txt") . ': <strong>' . $customer->customer_group_name . '</strong><br>' : ''?>
                    <?php if ($pos_settings->customer_details && $customer->id != 1) { ?>
                        <?=lang("address") . ": " . $customer->address?> <br>
                        <?=lang("tel") . ": " . $customer->phone?><br>
                    <?php } ?>
                    <?=lang("grand_total");?>: <strong><?=$this->sma->formatMoney($promo_bill_price);?></strong>
                </div>
                </p>
                <p style="border-top: 1px dotted black;"></p>
            </div>
            <?php } ?>
            <div style="clear:both;"></div>
        </div>

        <div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
            <?php
            if ($message) {
                ?>
                <div class="alert alert-success">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <?=is_array($message) ? print_r($message, true) : $message;?>
                </div>
                <?php
            } ?>
            <?php
            if ($modal) {
                ?>
                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group" role="group">
                        <?php
                        if ($pos->remote_printing == 1) {
                            echo '<button onclick="window.print();" class="btn btn-block btn-primary">'.lang("print").'</button>';
                            if ($inv->warehouse_id == 3) {
                                echo '</div><div class="btn-group" role="group"><button type="button" id="printLabel" data-action="labels" name="printLabel" class="btn btn-block btn-success" style="text-transform:uppercase;"><li class="fa fa-barcode" style="margin-right: 10px;"></li>'.lang("print_labels").'</button>';
                            }
                        } else {
                            echo '<button onclick="return printReceipt()" class="btn btn-block btn-primary">'.lang("print").'</button>';
                        }

                        ?>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="text-transform:uppercase;"><?= lang('close'); ?></button>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <span class="pull-right col-xs-12">
                    <?php
                    if ($pos->remote_printing == 1) {                      
                        echo '<button onclick="window.print();" class="btn btn-block btn-primary">'.lang("print").'</button>';
                        if ($inv->warehouse_id == 3) {
                            echo '<button type="button" id="printLabel" data-action="labels" name="printLabel" class="btn btn-block btn-success" style="text-transform:uppercase;"><li class="fa fa-barcode" style="margin-right: 10px;"></li>'.lang("print_labels").'</button>';
                        }
                    ?>
                        
                    <?php
                    } else {
                        echo '<button onclick="return printReceipt()" class="btn btn-block btn-primary">'.lang("print").'</button>';
                        echo '<button onclick="return openCashDrawer()" class="btn btn-block btn-default">'.lang("open_cash_drawer").'</button>';
                    }
                    ?>
                </span>
                <span class="col-xs-12">
                
                    <a class="btn btn-block btn-warning" href="<?= admin_url('pos'); ?>"><?= lang("back_to_pos"); ?></a>
                
                </span>
                <?php
            }
            if ($pos->remote_printing == 1) {
                ?>
                <div style="clear:both;"></div>
                <div class="col-xs-12" style="background:#F5F5F5; padding:10px;">
                    <p style="font-weight:bold;">
                        Please don't forget to disble the header and footer in browser print settings.
                    </p>
                    <p style="text-transform: capitalize;">
                        <strong>FF:</strong> File &gt; Print Setup &gt; Margin &amp; Header/Footer Make all --blank--
                    </p>
                    <p style="text-transform: capitalize;">
                        <strong>chrome:</strong> Menu &gt; Print &gt; Disable Header/Footer in Option &amp; Set Margins to None
                    </p>
                </div>
                <?php
            } ?>
            <div style="clear:both;"></div>
        </div>
    </div>
<?php if ($Owner || $GP['bulk_actions']) {
    echo admin_form_open('products/product_actions'.($warehouse_id ? '/'.$warehouse_id : ''), 'id="label-form"');
        //$this->sma->print_arrays($inv);
    foreach ($rows as $row) {
        // KHÔNG IN MÃ VẠCH KEM CHUỐI, YAOURT TRÁI CÂY BỊCH VÀ SINH TỐ ĐẬU BỊCH, ĐÁ, TOPPING, MỨT THÊM
        if($row->product_code != 'GK3031' 
            && $row->product_code != 'GK3030' 
            && $row->product_code != 'GK3027'
            && $row->product_code != 'GK3069'
            && $row->product_code != 'GK3070'
            && $row->product_code != 'GK3071') { 
?>
            <input name="name[]" type="hidden" value="<?=$row->product_name?>">
            <input name="code[]" type="hidden" value="<?=$row->product_code?>">
            <input name="val[]" type="hidden" value="<?=$row->product_id?>">
            <input name="qty[]" type="hidden" value="<?=$row->quantity?>">    
            <input name="options[]" type="hidden" value="<?=$row->option_id?>">  
            <input name="variant[]" type="hidden" value="<?=$row->variant?>">  
            <input name="comment[]" type="hidden" value="<?=$row->comment?>">   
            <input name="price[]" type="hidden" value="<?=$row->unit_price?>">   
            <input name="customer_name[]" type="hidden" value="<?=$customer->name?>">   
            <input name="customer_id[]" type="hidden" value="<?=$customer->id?>">   
            <input name="sale_note[]" type="hidden" value="<?=$inv->note?>">   
            <input name="reference_no[]" type="hidden" value="<?=$inv->reference_no?>"> 
<?php
        }
    }
} ?>
<?php if ($Owner || $GP['bulk_actions']) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="labels_extra" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="label-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>

    <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true"></div>

    <?php
    if( ! $modal) {
        ?>
        <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
        <?php
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {           
            $('body').on('click', '#printLabel', function(e) {            
                e.preventDefault();                
                $('#label-form-submit').trigger('click');
            });
            $('#view-customer').click(function(){
                console.log('customers/view/<?=$customer->id?>');
                $('#myModal2').modal({remote: site.base_url + 'customers/view/<?=$customer->id?>'});
                $('#myModal2').modal('show');
                $('#myModal').modal('hide');
            });

            $('#email').click(function () {
                bootbox.prompt({
                    title: "<?= lang("email_address"); ?>",
                    inputType: 'email',
                    value: "<?= $customer->email; ?>",
                    callback: function (email) {
                        if (email != null) {
                            $.ajax({
                                type: "post",
                                url: "<?= admin_url('pos/email_receipt') ?>",
                                data: {<?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email, id: <?= $inv->id; ?>},
                                dataType: "json",
                                success: function (data) {
                                    bootbox.alert({message: data.msg, size: 'small'});
                                },
                                error: function () {
                                    bootbox.alert({message: '<?= lang('ajax_request_failed'); ?>', size: 'small'});
                                    return false;
                                }
                            });
                        }
                    }
                });
                return false;
            });
        });

        <?php
        if ($pos_settings->remote_printing == 1) {
            ?>
            $(window).load(function () {
                window.print();
                return false;
            });
            <?php
        }
        ?>

    </script>
    <?php /* include FCPATH.'themes'.DIRECTORY_SEPARATOR.$Settings->theme.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'pos'.DIRECTORY_SEPARATOR.'remote_printing.php'; */ ?>
    <?php include 'remote_printing.php'; ?>
    <?php
    if($modal) {
        ?>
    </div>
</div>
</div>
<?php
} else {
    ?>
</body>
</html>
<?php
}
?>