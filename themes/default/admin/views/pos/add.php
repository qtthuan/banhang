<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=lang('pos_module') . " | " . $Settings->site_name;?></title>
    <script type="text/javascript">if(parent.frames.length !== 0){top.location = '<?=admin_url('pos')?>';}</script>
    <base href="<?=base_url()?>"/>
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <link rel="shortcut icon" href="<?=$assets?>images/icon.png"/>
    <link rel="stylesheet" href="<?=$assets?>styles/theme.css" type="text/css"/>
    <link rel="stylesheet" href="<?=$assets?>styles/style.css" type="text/css"/>
    <link rel="stylesheet" href="<?=$assets?>pos/css/posajax.css" type="text/css"/>
    <link rel="stylesheet" href="<?=$assets?>pos/css/print.css" type="text/css" media="print"/>
    <script type="text/javascript" src="<?=$assets?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?=$assets?>js/jquery-migrate-1.2.1.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?=$assets?>js/jquery.js"></script>
    <![endif]-->
    <?php if ($Settings->user_rtl) {?>
        <link href="<?=$assets?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
        <link href="<?=$assets?>styles/style-rtl.css" rel="stylesheet"/>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.pull-right, .pull-left').addClass('flip');
            });
        </script>
    <?php }
    ?>
</head>
<body>
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>

<div id="wrapper">
    <header id="header" class="navbar">
        <div class="container">
            <a class="navbar-brand" href="<?=admin_url()?>"><span class="logo"><span class="pos-logo-lg"><?=$Settings->site_name?></span><span class="pos-logo-sm"><?=lang('pos')?></span></span></a>

            <div class="header-nav">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="btn account dropdown-toggle" data-container="body" data-toggle="dropdown" href="#">
                            <img alt="" src="<?=$this->session->userdata('avatar') ? base_url() . 'assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png';?>" class="mini_avatar img-rounded">

                            <div class="user">
                                <span><?=lang('welcome')?>! <?=$this->session->userdata('username');?></span>
                            </div>
                        </a>
                        <ul class="dropdown-menu pull-right" style="z-index: 99; position: absolute">
                            <li>
                                <a href="<?=admin_url('auth/profile/' . $this->session->userdata('user_id'));?>">
                                    <i class="fa fa-user"></i> <?=lang('profile');?>
                                </a>
                            </li>
                            <li>
                                <a href="<?=admin_url('auth/profile/' . $this->session->userdata('user_id') . '/#cpassword');?>">
                                    <i class="fa fa-key"></i> <?=lang('change_password');?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?=admin_url('auth/logout');?>">
                                    <i class="fa fa-sign-out"></i> <?=lang('logout');?>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="btn bblue pos-tip" title="<?=lang('dashboard')?>" data-container="body" data-placement="bottom" href="<?=admin_url('welcome')?>">
                            <i class="fa fa-dashboard"></i>
                        </a>
                    </li>
                    <?php if ($Owner) {?>
                        <li class="dropdown hidden-sm">
                            <a class="btn pos-tip" title="<?=lang('settings')?>" data-container="body" data-placement="bottom" href="<?=admin_url('pos/settings')?>">
                                <i class="fa fa-cogs"></i>
                            </a>
                        </li>
                    <?php }
                    ?>
<!--                    <li class="dropdown hidden-xs">-->
<!--                        <a class="btn pos-tip" title="--><?//=lang('calculator')?><!--" data-placement="bottom" href="#" data-toggle="dropdown">-->
<!--                            <i class="fa fa-calculator"></i>-->
<!--                        </a>-->
<!--                        <ul class="dropdown-menu pull-right calc">-->
<!--                            <li class="dropdown-content">-->
<!--                                <span id="inlineCalc"></span>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
                    <li class="dropdown hidden-sm">
                        <a class="btn pos-tip" title="<?=lang('shortcuts')?>" data-container="body" data-placement="bottom" href="#" data-toggle="modal" data-target="#sckModal">
                            <i class="fa fa-key"></i>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a class="btn pos-tip" title="<?=lang('view_bill_screen')?>" data-container="body" data-placement="bottom" href="<?=admin_url('pos/view_bill')?>" target="_blank">
                            <i class="fa fa-laptop"></i>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a class="btn blightOrange pos-tip" id="opened_bills" title="<span><?=lang('suspended_sales')?></span>" data-container="body" data-placement="bottom" data-html="true" href="<?=admin_url('pos/opened_bills')?>" data-toggle="ajax">
                            <i class="fa fa-th"></i>
                        </a>
                    </li>
                    <?php if ($Owner) {?>
                    <li class="dropdown">
                        <a class="btn bdarkGreen pos-tip" id="register_details" title="<span><?=lang('register_details')?></span>" data-container="body" data-placement="bottom" data-html="true" href="<?=admin_url('pos/register_details')?>" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    </li>
                    <?php }
                    ?>
                    <li class="dropdown">
                        <a class="btn borange pos-tip" id="close_register" title="<span><?=lang('close_register')?></span>" data-container="body" data-placement="bottom" data-html="true" data-backdrop="static" href="<?=admin_url('pos/close_register')?>" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-times-circle"></i>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a class="btn borange pos-tip" id="add_expense" title="<span><?=lang('add_expense')?></span>" data-container="body" data-placement="bottom" data-html="true" href="<?=admin_url('purchases/add_expense')?>" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-dollar"></i>
                        </a>
                    </li>
                    <?php if ($Owner) {?>
                        <li class="dropdown">
                            <a class="btn bdarkGreen pos-tip" id="today_profit" title="<span><?=lang('today_profit')?></span>" data-container="body" data-placement="bottom" data-html="true" href="<?=admin_url('reports/profit')?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-hourglass-half"></i>
                            </a>
                        </li>
                    <?php }
                    ?>
                    <?php if ($Owner || $Admin) {?>
                        <li class="dropdown">
                            <a class="btn bdarkGreen pos-tip" id="today_sale" title="<span><?=lang('today_sale')?></span>" data-container="body" data-placement="bottom" data-html="true" href="<?=admin_url('pos/today_sale')?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-heart"></i>
                            </a>
                        </li>
                        <li class="dropdown hidden-xs">
                            <a class="btn bblue pos-tip" title="<?=lang('list_open_registers')?>" data-container="body" data-placement="bottom" href="<?=admin_url('pos/registers')?>">
                                <i class="fa fa-list"></i>
                            </a>
                        </li>
                        <li class="dropdown hidden-xs">
                            <a class="btn bred pos-tip" title="<?=lang('clear_ls')?>" data-container="body" data-placement="bottom" id="clearLS" href="#">
                                <i class="fa fa-eraser"></i>
                            </a>
                        </li>
                    <?php }
                    ?>
                </ul>

                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="btn bblack" style="cursor: default;"><span id="display_time"></span></a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div id="content">
        <div class="c1">
            <div class="pos">
                <?php
                	if ($error) {
                	    echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close fa-2x\" data-dismiss=\"alert\">&times;</button>" . $error . "</div>";
                	}
                ?>
                <?php
                	if ($message) {
                	    echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close fa-2x\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
                	}
                ?>
                <div id="pos">
                    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'pos-sale-form');
                    echo admin_form_open("pos", $attrib);?>
                    <div id="leftdiv">
                        <div id="printhead">
                            <h4 style="text-transform:uppercase;"><?php echo $Settings->site_name; ?></h4>
                            <?php
                            	echo "<h5 style=\"text-transform:uppercase;\">" . $this->lang->line('order_list') . "</h5>";
                            	echo $this->lang->line("date") . " " . $this->sma->hrld(date('Y-m-d H:i:s'));
                            ?>
                        </div>
                        <div id="left-top">
                            <div
                                style="position: absolute; <?=$Settings->user_rtl ? 'right:-9999px;' : 'left:-9999px;';?>"><?php echo form_input('test', '', 'id="test" class="kb-pad"'); ?></div>
                            <div class="form-group">
                                <div class="input-group">
                                <?php
                                	echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="poscustomer" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer") . '" required="required" class="form-control pos-input-tip" style="width:100%;"');
                                ?>
                                <input name="customer_group_id" type="hidden" value="" id="customer_group_id">
                                    <div class="input-group-addon no-print" style="padding: 2px 16px; border-left: 0;">
                                        <a href="#" id="toogle-customer-read-attr" class="external pos-tip" title="<?=lang('customer_selection')?>">
                                            <i class="fa fa-pencil" id="addIcon" style="font-size: 1.7em;"></i>
                                        </a>
                                    </div>
                                    <div class="input-group-addon no-print" style="padding: 2px 15px; border-left: 0;">
                                        <a href="#" id="view-customer" class="external pos-tip" title="<?=lang('customers_info');?> (<?=$pos_settings->customers_info?>)" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-eye" id="addIcon" style="font-size: 1.7em;"></i>
                                        </a>
                                    </div>
                                <?php if ($Owner || $Admin || $GP['customers-add']) { ?>
                                    <div class="input-group-addon no-print" style="padding: 2px 16px;">
                                        <a href="<?=admin_url('customers/add');?>" id="add-customer" class="external pos-tip" title="<?=lang('add_customer')?> (<?=$pos_settings->add_customer?>)" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-plus-circle" id="addIcon" style="font-size: 1.9em;"></i>
                                        </a>
                                    </div>
                                <?php } ?>
                                    <div class="input-group-addon no-print" style="padding: 2px 16px; border-left: 0;">
                                        <a href="#" id="sellGiftCard" class="external tip" title="<?=lang('sell_gift_card');?> (<?=$pos_settings->gift_card?>)" data-replacement="bottom">
                                            <i class="fa fa-credit-card addIcon fa-2x" id="addIcon" style="font-size: 1.7em;"></i>
                                        </a>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                            <div class="no-print">
                                <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) {
                                    ?>
                                    <div class="form-group">
                                        <?php
                                        	$wh[''] = '';
                                        	    foreach ($warehouses as $warehouse) {
                                        	        $wh[$warehouse->id] = $warehouse->name;
                                        	    }
                                        	    echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="poswarehouse" class="form-control pos-input-tip" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required" style="width:100%;" ');
                                            ?>
                                    </div>
                                <?php } else {

                                	    $warehouse_input = array(
                                	        'type' => 'hidden',
                                	        'name' => 'warehouse',
                                	        'id' => 'poswarehouse',
                                	        'value' => $this->session->userdata('warehouse_id'),
                                	    );

                                	    echo form_input($warehouse_input);
                                	}
                                ?>
                                <div class="form-group" style="display: none">
                                    <div class="input-group">
                                        <div class="no-print col-xs-1" style="padding: 1px 2px; border-left: 0;">
                                            <button type="button" class="btn btn-danger" id="ag" style="height:35px; font-size: 15px;">
                                                AG
                                            </button>
                                        </div>
                                        <div class="no-print col-xs-1" style="padding: 1px 2px; border-left: 0;">
                                            <button type="button" class="btn btn-warning" id="at" style="height:35px; font-size: 15px;">
                                                AT
                                            </button>
                                        </div>
                                        <div class="no-print col-xs-1" style="padding: 1px 2px; border-left: 0;">
                                            <button type="button" class="btn btn-info" id="bg" style="height:35px; font-size: 15px;">
                                                BG
                                            </button>
                                        </div>
                                        <div class="no-print col-xs-1" style="padding: 1px 2px; border-left: 0;">
                                            <button type="button" class="btn btn-success" id="bt" style="height:35px; font-size: 15px;">
                                                BT
                                            </button>
                                        </div>
                                        <div class="no-print col-xs-1" style="padding: 1px 2px; border-left: 0;">
                                            <button type="button" class="btn btn-danger" id="dg" style="height:35px; font-size: 15px;">
                                                DG
                                            </button>
                                        </div>
                                        <div class="no-print col-xs-1" style="padding: 1px 2px; border-left: 0;">
                                            <button type="button" class="btn btn-warning" id="qg" style="height:35px; font-size: 15px;">
                                                QG
                                            </button>
                                        </div>
                                        <div class="no-print col-xs-1" style="padding: 1px 2px; border-left: 0;">
                                            <button type="button" class="btn btn-info" id="qt" style="height:35px; font-size: 15px;">
                                                QT
                                            </button>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" id="ui">
                                    <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                    <div class="input-group">
                                    <?php } ?>
                                    <?php echo form_input('add_item', '', 'class="form-control pos-tip" id="add_item" data-placement="top" data-trigger="focus" placeholder="' . $this->lang->line("search_product_by_name_code") . $pos_settings->focus_add_item . '" title=""'); ?>
                                    <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                        <div class="input-group-addon" style="padding: 0 22px;">
                                            <a href="#" id="addManually" class="tip" title="<?=lang('add_product_manually')?> (<?=$pos_settings->add_manual_product?>)">
                                                <i class="fa fa-plus-circle" id="addIcon" style="font-size: 2.2em;"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>
                        </div>
                        <div id="print">
                            <div id="left-middle">
                                <div id="product-list">
                                    <table class="table items table-striped table-bordered table-condensed table-hover sortable_table"
                                           id="posTable" style="margin-bottom: 0;">
                                        <thead>
                                        <tr>
                                            <th width="67%"><?=lang("product");?></th>
                                            <th width="9%"><?=lang("price");?></th>
                                            <th width="8%"><?=lang("qty");?></th>
                                            <th width="11%"><?=lang("subtotal");?></th>
                                            <th style="width: 5%; text-align: center;">
                                                <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>
                            <div style="clear:both;"></div>
                            <div id="left-bottom">
                                <table id="totalTable"
                                       style="width:100%; float:right; padding:5px; color:#000; background: #FFF;">
                                    <tr>
                                        <td style="width: 11%; padding-left: 15px; border-top: 1px solid #DDD; font-size: 16px;">
                                            <?=lang('total_items');?>: <span id="titems" style="font-weight: bold">0</span>
                                        </td>
                                        <td style="width: 12%; border-top: 1px solid #DDD; font-size: 16px;">
                                            <?=lang('total');?>: <span id="total" style="font-weight: bold">0.00</span>
                                        </td>
                                        <td class="text-left" style="width: 12%; border-top: 1px solid #DDD; font-size: 16px;">
                                            <?=lang('shipping');?>
                                            <a href="#" id="pshipping" class="external pos-tip" style="vertical-align: -webkit-baseline-middle" title="<?=$pos_settings->bill_shipping?>">
                                                <i class="fa fa-2x fa-plus-square"></i>
                                            </a>
                                            <span id="tship" style="font-weight: bold"></span>
                                        </td>
                                        <td id="col_return"  class="text-left" style="width: 15%; border-top: 1px solid #DDD; font-size: 16px;">
                                            <?=lang('return_amount');?>
                                            <a href="#" id="preturn" class="external pos-tip" style="vertical-align: -webkit-baseline-middle" title="<?=$pos_settings->return_sale?>">
                                                <i class="fa fa-2x fa-minus-square"></i>
                                            </a>
                                            <span id="treturn" style="font-weight: bold"></span>
                                        </td>
                                        <td class="text-left" style="width: 30%; border-top: 1px solid #DDD; font-size: 16px;">
                                            <?=lang('discount');?>
                                            <?php if ($Owner || $Admin || $this->session->userdata('allow_discount')) { ?>
                                                <a href="#" id="ppdiscount" class="external pos-tip" style="vertical-align: -webkit-baseline-middle" title="<?=$pos_settings->bill_discount?>">
                                                    <i class="fa fa-2x fa-edit"></i>
                                                </a>
                                            <?php } ?>
                                            <span id="tds" style="font-weight: bold; color:red">0.00</span>
                                            <span id="tds_extra" style="font-size: 16px; color:darkblue">&nbsp;</span>
                                        </td>
                                        <td class="text-right" style="width: 8%; padding-right: 7px; font-size: 16px;border-top: 1px solid #666; border-bottom: 1px solid #333; font-weight:bold; background:#333; color:#FFF;">
                                            <?=lang('total_payable1');?>:
                                        </td>
                                        <td class="text-right" style="width: 12%; padding-right: 7px; border-top: 1px solid #666; border-bottom: 1px solid #333; font-weight:bold; background:#333; color:#f0ff00;">
                                            <span id="gtotal" style="font-size: 22px">0.00</span>
                                        </td>
                                    </tr>
                                </table>

                                <div class="clearfix"></div>
                                <div id="botbuttons" class="col-xs-12 text-center">
                                    <input type="hidden" name="biller" id="biller" value="<?= ($Owner || $Admin || !$this->session->userdata('biller_id')) ? $pos_settings->default_biller : $this->session->userdata('biller_id')?>"/>
                                    <div class="row">
                                        <div class="col-xs-3" style="padding: 0;">
                                            <button type="button" class="btn btn-danger btn-block" id="reset" style="height:57px; font-size: 20px;">
                                                <i class="fa fa-times-circle" style="margin-right: 5px;"></i><?=lang('cancel_sale');?> (<strong><?=$pos_settings->cancel_sale?></strong>)
                                            </button>
                                        </div>
                                        <div class="col-xs-3" style="padding: 0;">
                                            <button type="button" class="btn btn-warning btn-block" id="suspend" style="height:57px; font-size: 20px;">
                                                <i class="fa fa-sticky-note" style="margin-right: 5px;"></i><?=lang('suspend');?> (<strong><?=$pos_settings->suspend_sale?></strong>)
                                            </button>
                                        </div>
                                        <div class="col-xs-3" style="padding: 0;">
                                            <button type="button" class="btn btn-info btn-block" id="print_bill" style="height:57px; font-size: 20px;">
                                                <i class="fa fa-th" style="margin-right: 5px;"></i><?=lang('bill');?>
                                            </button>
                                        </div>
                                        <div class="col-xs-3" style="padding: 0;">
                                            <button type="button" class="btn btn-success btn-block" id="payment" style="height:57px; font-size: 20px;">
                                                <i class="fa fa-money" style="margin-right: 5px;"></i><?=lang('payment');?> (<strong><?=$pos_settings->finalize_sale?></strong>)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div style="height:20px;"></div>
                                <div id="num">
                                    <div id="icon"></div>
                                </div>
                                <span id="hidesuspend"></span>
                                <input type="hidden" name="pos_note" value="" id="pos_note">
                                <input type="hidden" name="staff_note" value="" id="staff_note">
                                <input type="hidden" name="delivery_method" value="Shop" id="delivery_method">

                                <div id="payment-con">
                                    <?php for ($i = 1; $i <= 5; $i++) {?>
                                        <input type="hidden" name="amount[]" id="amount_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="balance_amount[]" id="balance_amount_<?=$i?>" value=""/>
                                        <input type="hidden" name="paid_by[]" id="paid_by_val_<?=$i?>" value="cash"/>
                                        <input type="hidden" name="cc_no[]" id="cc_no_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="paying_gift_card_no[]" id="paying_gift_card_no_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="paying_points[]" id="paying_points_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="cc_holder[]" id="cc_holder_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="cheque_no[]" id="cheque_no_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="cc_month[]" id="cc_month_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="cc_year[]" id="cc_year_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="cc_type[]" id="cc_type_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="cc_cvv2[]" id="cc_cvv2_val_<?=$i?>" value=""/>
                                        <input type="hidden" name="payment_note[]" id="payment_note_val_<?=$i?>" value=""/>
                                    <?php }
                                    ?>
                                </div>
                                <input name="order_tax" type="hidden" value="<?=$suspend_sale ? $suspend_sale->order_tax_id : ($old_sale ? $old_sale->order_tax_id : $Settings->default_tax_rate2);?>" id="postax2">
                                <input name="discount" type="hidden" value="<?=$suspend_sale ? $suspend_sale->order_discount_id : ($old_sale ? $old_sale->order_discount_id : '');?>" id="posdiscount">
                                <input name="shipping" type="hidden" value="<?=$suspend_sale ? $suspend_sale->shipping : ($old_sale ? $old_sale->shipping :  '0');?>" id="posshipping">
                                <input id="order_discount_percent_for_return_sale" name="order_discount_percent_for_return_sale" type="hidden" value="0">
                                <input name="return_amount" type="hidden" value="<?=$suspend_sale ? $suspend_sale->return_amount : ($old_sale ? $old_sale->return_amount :  '0');?>" id="posreturn">
                                <input type="hidden" name="rpaidby" id="rpaidby" value="cash" style="display: none;"/>
                                <input type="hidden" name="total_items" id="total_items" value="0" style="display: none;"/>
                                <input type="hidden" name="has_out_of_stock_products" id="has_out_of_stock_products" value="0" style="display: none;"/>
                                <input type="submit" id="submit_sale" value="Submit Sale" style="display: none;"/>
                            </div>
                        </div>

                    </div>
                    <?php echo form_close(); ?>
                    <div id="cp">
                        <div id="cpinner">
                            <div class="quick-menu">
                                <div id="proContainer">
                                    <div id="ajaxproducts">
                                        <div id="item-list">
                                            <?php echo $products; ?>
                                        </div>
                                        <div class="btn-group btn-group-justified pos-grid-nav">
                                            <div class="btn-group">
                                                <button style="z-index:10002;" class="btn btn-primary pos-tip" title="<?=lang('previous')?>" type="button" id="previous">
                                                    <i class="fa fa-chevron-left"></i>
                                                </button>
                                            </div>
                                            <?php if ($Owner || $Admin || $GP['sales-add_gift_card']) {?>
                                            <div class="btn-group">
                                                <button style="z-index:10003;" class="btn btn-primary pos-tip" type="button" id="sellGiftCard" title="<?=lang('sell_gift_card')?>">
                                                    <i class="fa fa-credit-card" id="addIcon"></i><?=lang('sell_gift_card')?>
                                                </button>
                                            </div>
                                            <?php } ?>
                                            <div class="btn-group">
                                                <button style="z-index:10004;" class="btn btn-primary pos-tip" title="<?=lang('next')?>" type="button" id="next">
                                                    <i class="fa fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
</div>
<div class="rotate btn-cat-con">
    <button type="button" id="open-brands" class="btn btn-info open-brands"><?= lang('brands'); ?></button>
    <button type="button" id="open-subcategory" class="btn btn-warning open-subcategory"><?= lang('subcategories'); ?></button>
    <button type="button" id="open-category" class="btn btn-primary open-category"><?= lang('categories'); ?></button>
</div>
<div id="brands-slider">
    <div id="brands-list">
        <?php
            foreach ($brands as $brand) {
                echo "<button id=\"brand-" . $brand->id . "\" type=\"button\" value='" . $brand->id . "' class=\"btn-prni brand\" ><img src=\"assets/uploads/thumbs/" . ($brand->image ? $brand->image : 'no_image.png') . "\" class='img-rounded img-thumbnail' /><span>" . $brand->name . "</span></button>";
            }
        ?>
    </div>
</div>
<div id="category-slider">
    <!--<button type="button" class="close open-category"><i class="fa fa-2x">&times;</i></button>-->
    <div id="category-list">
        <?php
        	//for ($i = 1; $i <= 40; $i++) {
        	foreach ($categories as $category) {
        	    echo "<button id=\"category-" . $category->id . "\" type=\"button\" value='" . $category->id . "' class=\"btn-prni category\" ><img src=\"assets/uploads/thumbs/" . ($category->image ? $category->image : 'no_image.png') . "\" class='img-rounded img-thumbnail' /><span>" . $category->name . "</span></button>";
        	}
        	//}
        ?>
    </div>
</div>
<div id="subcategory-slider">
    <!--<button type="button" class="close open-category"><i class="fa fa-2x">&times;</i></button>-->
    <div id="subcategory-list">
        <?php
        	if (!empty($subcategories)) {
        	    foreach ($subcategories as $category) {
        	        echo "<button id=\"subcategory-" . $category->id . "\" type=\"button\" value='" . $category->id . "' class=\"btn-prni subcategory\" ><img src=\"assets/uploads/thumbs/" . ($category->image ? $category->image : 'no_image.png') . "\" class='img-rounded img-thumbnail' /><span>" . $category->name . "</span></button>";
        	    }
        	}
        ?>
    </div>
</div>
<div class="modal fade in" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h2 class="modal-title" id="payModalLabel"><?=lang('finalize_sale');?></h2>
            </div>
            <div class="modal-body" id="payment_content">
                <div class="row">
                    <div class="col-md-10 col-sm-9">
                        <?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
                            <div class="form-group">
                                <?=lang("biller", "biller");?>
                                <?php
                                	foreach ($billers as $biller) {
                                        $btest = ($biller->company && $biller->company != '-' ? $biller->company : $biller->name);
                            	        $bl[$biller->id] = $btest;
                                        $posbillers[] = array('logo' => $biller->logo, 'company' => $btest);
                                        if ($biller->id == $pos_settings->default_biller) {
                                            $posbiller = array('logo' => $biller->logo, 'company' => $btest);
                                        }
                                	}
                                	echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $pos_settings->default_biller), 'class="form-control" id="posbiller" required="required"');
                                ?>
                            </div>
                        <?php } else {
                        	    $biller_input = array(
                        	        'type' => 'hidden',
                        	        'name' => 'biller',
                        	        'id' => 'posbiller',
                        	        'value' => $this->session->userdata('biller_id'),
                        	    );

                        	    echo form_input($biller_input);

                                foreach ($billers as $biller) {
                                    $btest = ($biller->company && $biller->company != '-' ? $biller->company : $biller->name);
                                    $posbillers[] = array('logo' => $biller->logo, 'company' => $btest);
                                    if ($biller->id == $this->session->userdata('biller_id')) {
                                        $posbiller = array('logo' => $biller->logo, 'company' => $btest);
                                    }
                                }
                        	}
                        ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?=form_textarea('sale_note', '', 'id="sale_note" class="form-control kb-text skip" style="height: 85px; color: #006400;" placeholder="' . lang('sale_note') . '" maxlength="500"');?>
                                </div>
                                <div class="col-sm-6">
                                    <?=form_textarea('staffnote', '', 'id="staffnote" class="form-control kb-text skip" style="height: 85px; color: #006400;" placeholder="' . lang('staff_note') . '" maxlength="500"');?>
                                </div>
                            </div>

                        </div>
                        <div class="clearfir"></div>

                        <div id="payments">
                            <div class="well well-sm well_1">
                                <div class="payment">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <?=lang("paying_by", "paid_by_1");?>
                                                <select name="paid_by[]" id="paid_by_1" class="form-control paid_by">
                                                    <?= $this->sma->paid_opts(); ?>
                                                    <?=$pos_settings->paypal_pro ? '<option value="ppp">' . lang("paypal_pro") . '</option>' : '';?>
                                                    <?=$pos_settings->stripe ? '<option value="stripe">' . lang("stripe") . '</option>' : '';?>
                                                    <?=$pos_settings->authorize ? '<option value="authorize">' . lang("authorize") . '</option>' : '';?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <?=lang("total_paying", "amount_1");?>
                                                <input name="amount[]" type="text" id="amount_1"
                                                       class="pa form-control kb-pad1 amount" value="0"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <?=lang("delivery_method", "delivery_method");?>
                                            <select name="delivery" id="delivery" class="form-control delivery">
                                                <?= $this->sma->delivery_method_opts(); ?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-11">
                                            <div class="form-group gc_1" style="display: none;">
                                                <?=lang("gift_card_no", "gift_card_no_1");?>
                                                <input name="paying_gift_card_no[]" type="text" id="gift_card_no_1"
                                                       class="pa form-control kb-pad gift_card_no"/>

                                                <div id="gc_details_1"></div>
                                            </div>

                                            <div  class="form-group pts_1" style="display: none;">
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <?= lang("award_points1", "award_points1") ?>: <span id="award_points_1" style="color: red; font-weight: bold"></span>
                                                        <input name="ca_points[]" type="text" id="ca_points_1"
                                                               class="pa form-control kb-pad1 ca_points"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="input-group">
                                                        <button type="button" class="btn btn-warning btn-block change_points" id="change_points_1" style="padding: 6px 0; height:35px; font-size: 18px; margin-top: 28px;">
                                                            <i class="fa fa-money" style="margin-right: 5px;"></i><?=lang('change_points');?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group pts_1" style="display: none;">
                                                <div class="col-sm-3" id="details_pts_1"></div>
                                            </div>

                                            <div class="pcc_1" style="display:none;">
                                                <div class="form-group">
                                                    <input type="hidden" id="swipe_1" class="form-control swipe"
                                                           placeholder="<?=lang('swipe')?>"/>
                                                </div>
                                                <div class="row">
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input name="cc_no[]" type="hidden" value="1" id="pcc_no_1"
                                                                   class="form-control"
                                                                   placeholder="<?=lang('cc_no')?>"/>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" style="display: none">
                                                        <div class="form-group">
                                                            <select name="cc_type[]" id="pcc_type_1"
                                                                    class="form-control pcc_type"
                                                                    placeholder="<?=lang('card_type')?>">
                                                                <option value="Visa"><?=lang("Visa");?></option>
                                                                <option
                                                                    value="MasterCard"><?=lang("MasterCard");?></option>
                                                                <option value="Amex"><?=lang("Amex");?></option>
                                                                <option
                                                                    value="Discover"><?=lang("Discover");?></option>
                                                            </select>
                                                            <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?=lang('card_type')?>" />-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" style="display: none">
                                                        <div class="form-group">
                                                            <input name="cc_month[]" type="text" id="pcc_month_1"
                                                                   class="form-control"
                                                                   placeholder="<?=lang('month')?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" style="display: none">
                                                        <div class="form-group">

                                                            <input name="cc_year" type="text" id="pcc_year_1"
                                                                   class="form-control"
                                                                   placeholder="<?=lang('year')?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" style="display: none">
                                                        <div class="form-group">

                                                            <input name="cc_cvv2" type="text" id="pcc_cvv2_1"
                                                                   class="form-control"
                                                                   placeholder="<?=lang('cvv2')?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pcheque_1" style="display:none;">
                                                <div class="form-group"><?=lang("cheque_no", "cheque_no_1");?>
                                                    <input name="cheque_no[]" type="text" id="cheque_no_1"
                                                           class="form-control cheque_no"/>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div id="multi-payment"></div>
                        <button type="button" class="btn btn-primary col-md-12 addButton"><i
                                class="fa fa-plus"></i> <?=lang('add_more_payments')?></button>
                        <div class="font16">
                            <table class="table table-bordered table-condensed table-striped" style="margin-bottom: 0;">
                                <tbody>
                                <tr>
                                    <td width="25%"><?=lang("total_items");?></td>
                                    <td width="25%" class="text-right"><span id="item_count" style="font-size: 22px;color: #f52929;">0.00</span></td>
                                    <td width="25%"><?=lang("total_payable");?></td>
                                    <td width="25%" class="text-right"><span id="twt" style="font-size: 22px;color: #f52929;">0.00</span></td>
                                </tr>
                                <tr>
                                    <td><?=lang("total_paying");?></td>
                                    <td class="text-right"><span id="total_paying" style="font-size: 19px;color: #00aa9a">0.00</span></td>
                                    <td><?=lang("balance");?></td>
                                    <td class="text-right"><span id="balance" style="font-size: 20px;color: #0a68b4;">0.00</span></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">

                                <span id="number_to_string_1" style="color:#008000; font-size: 16px;"></span>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-3 text-center">
                        <span style="font-size: 1.2em; font-weight: bold;"><?=lang('quick_cash');?></span>

                        <div class="btn-group btn-group-vertical">
                            <button type="button" class="btn btn-lg btn-info quick-cash" id="quick-payable">0.00
                            </button>
                            <?php
                            foreach (lang('quick_cash_notes') as $cash_note_amount) {
                                echo '<button type="button" class="btn btn-warning quick-cash">' . number_format($cash_note_amount, 0, '.', '.') . '</button>';
                            }
                            ?>
                            <button type="button" class="btn btn-lg btn-danger"
                                    id="clear-cash-notes"><?=lang('clear');?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-block btn-lg btn-primary" id="submit-sale"><?=lang('submit');?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="cmModal" tabindex="-1" role="dialog" aria-labelledby="cmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">
                    <i class="fa fa-2x">&times;</i></span>
                    <span class="sr-only"><?=lang('close');?></span>
                </button>
                <h4 class="modal-title" id="cmModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <div class="form-group">
                    <?= lang('comment', 'icomment'); ?>
                    <input type="text" name="comment" class="form-control kb-text" id="icomment">
                </div>
                <!--<div class="form-group">
                    <?= lang('ordered', 'iordered'); ?>
                    <?php
                    $opts = array(0 => lang('no'), 1 => lang('yes'));
                    ?>
                    <?= form_dropdown('ordered', $opts, '', 'class="form-control" id="iordered" style="width:100%;"'); ?>
                </div>-->
                <input type="hidden" id="irow_id" value=""/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editComment"><?=lang('submit')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>

            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <?php if ($Settings->tax1) {
                        ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?=lang('product_tax')?></label>
                            <div class="col-sm-8">
                                <?php
                                	$tr[""] = "";
                                	    foreach ($tax_rates as $tax) {
                                	        $tr[$tax->id] = $tax->name;
                                	    }
                                	    echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');
                                    ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="pserial" class="col-sm-4 control-label"><?=lang('serial_no')?></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-text" id="pserial">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) { ?>

                    <div class="form-group">
                        <label for="pdiscount" class="col-sm-4 control-label"><?=lang('product_discount')?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="pdiscount">
                            <span class="help-block"><?= lang('input_discount_help') ?></span>
                        </div>
                    </div><div class="form-group">
                        <label for="price_after_discount" class="col-sm-4 control-label"><?=lang('price_after_discount')?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="price_after_discount">
                        </div>
                    </div>

                    <?php } ?>
                    <div class="form-group">
                        <label for="pprice" class="col-sm-4 control-label"><?=lang('unit_price')?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="pprice" <?= ($Owner || $Admin || $GP['edit_price']) ? '' : 'readonly'; ?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?=lang('quantity')?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="pquantity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="punit" class="col-sm-4 control-label"><?= lang('product_unit') ?></label>
                        <div class="col-sm-8">
                            <div id="punits-div"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?=lang('product_option')?></label>
                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?=lang('net_unit_price');?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_price" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_price" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?=lang('submit')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel"><?=lang('sell_gift_card');?></h4>
            </div>
            <div class="modal-body">
                <p><?=lang('enter_info');?></p>

                <div class="alert alert-danger gcerror-con" style="display: none;">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <span id="gcerror"></span>
                </div>
                <div class="form-group">
                    <?=lang("card_no", "gccard_no");?> *
                    <div class="input-group">
                        <?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no"'); ?>
                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                            <a href="#" id="genNo"><i class="fa fa-cogs"></i></a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="gcname" value="<?=lang('gift_card')?>" id="gcname"/>

                <div class="form-group">
                    <?=lang("value", "gcvalue");?> *
                    <?php echo form_input('gcvalue', '', 'class="form-control" id="gcvalue"'); ?>
                </div>
                <div class="form-group">
                    <?=lang("price", "gcprice");?> *
                    <?php echo form_input('gcprice', '', 'class="form-control" id="gcprice"'); ?>
                </div>
                <div class="form-group">
                    <?=lang("customer", "gccustomer");?>
                    <?php echo form_input('gccustomer', '', 'class="form-control" id="gccustomer"'); ?>
                </div>
                <div class="form-group">
                    <?=lang("expiry_date", "gcexpiry");?>
                    <?php echo form_input('gcexpiry', $this->sma->hrsd(date("Y-m-d", strtotime("+2 year"))), 'class="form-control date" id="gcexpiry"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="addGiftCard" class="btn btn-primary"><?=lang('sell_gift_card')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="mModalLabel"><?=lang('add_product_manually')?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?=lang('product_code')?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-text" id="mcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?=lang('product_name')?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-text" id="mname">
                        </div>
                    </div>
                    <?php if ($Settings->tax1) {
                        ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?=lang('product_tax')?> *</label>

                            <div class="col-sm-8">
                                <?php
                                	$tr[""] = "";
                                	    foreach ($tax_rates as $tax) {
                                	        $tr[$tax->id] = $tax->name;
                                	    }
                                	    echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control pos-input-tip" style="width:100%;"');
                                    ?>
                            </div>
                        </div>
                    <?php }
                    ?>
                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?=lang('unit_price')?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="mprice">
                        </div>
                    </div>
                    <?php if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) {?>
                        <div class="form-group">
                            <label for="mdiscount"
                                   class="col-sm-4 control-label"><?=lang('product_discount')?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="mdiscount">
                            </div>
                        </div>
                    <?php }
                    ?>
                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?=lang('quantity')?> *</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="mquantity" value="1">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?=lang('net_unit_price');?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?=lang('product_tax');?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItemManually"><?=lang('submit')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="sckModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">
                <i class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span>
                </button>
                <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                    <i class="fa fa-print"></i> <?= lang('print'); ?>
                </button>
                <h4 class="modal-title" id="mModalLabel"><?=lang('shortcut_keys')?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <table class="table table-bordered table-striped table-condensed table-hover"
                       style="margin-bottom: 0px;">
                    <thead>
                    <tr>
                        <th><?=lang('shortcut_keys')?></th>
                        <th><?=lang('actions')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?=$pos_settings->focus_add_item?></td>
                        <td><?=lang('focus_add_item')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->add_manual_product?></td>
                        <td><?=lang('add_manual_product')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->customer_selection?></td>
                        <td><?=lang('customer_selection')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->add_customer?></td>
                        <td><?=lang('add_customer')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->customers_info?></td>
                        <td><?=lang('customers_info')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->return_sale?></td>
                        <td><?=lang('return_sale')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->cancel_sale?></td>
                        <td><?=lang('cancel_sale')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->suspend_sale?></td>
                        <td><?=lang('suspend_sale')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->finalize_sale?></td>
                        <td><?=lang('finalize_sale')?></td>
                    </tr>


                    <tr>
                        <td><?=$pos_settings->today_sale?></td>
                        <td><?=lang('today_sale')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->open_hold_bills?></td>
                        <td><?=lang('open_hold_bills')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->close_register?></td>
                        <td><?=lang('close_register')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->gift_card?></td>
                        <td><?=lang('gift_card')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->bill_discount?></td>
                        <td><?=lang('bill_discount')?></td>
                    </tr>
                    <tr>
                        <td><?=$pos_settings->bill_shipping?></td>
                        <td><?=lang('bill_shipping')?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="dsModal" tabindex="-1" role="dialog" aria-labelledby="dsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-2x">&times;</i>
                </button>
                <h4 class="modal-title" id="dsModalLabel"><?=lang('edit_order_discount');?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?=lang("order_discount", "order_discount_input");?>
                    <?php echo form_input('order_discount_input', '', 'class="form-control kb-pad" id="order_discount_input"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="updateOrderDiscount" class="btn btn-primary"><?=lang('update')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="posInfoModal" tabindex="-1" role="dialog" aria-labelledby="posInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="posInfoModalLabel" style="text-align: center; text-decoration: underline"></h1>
                <div style="text-align: center; padding-top: 10px;">
                    <button type="button" id="posInfoCustomer" class="btn btn-primary" data-dismiss="modal"><?= lang('close'); ?></button>
                </div>
            </div>
            <div class="modal-body">
            </div>

        </div>
    </div>
</div>

<div class="modal fade in" id="sModal" tabindex="-1" role="dialog" aria-labelledby="sModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-2x">&times;</i>
                </button>
                <h4 class="modal-title" id="sModalLabel"><?=lang('shipping');?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?=lang("shipping", "shipping_input");?>
                    <?php echo form_input('shipping_input', '', 'class="form-control kb-pad" id="shipping_input"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="updateShipping" class="btn btn-primary"><?=lang('update')?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="rModal" tabindex="-1" role="dialog" aria-labelledby="rModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-2x">&times;</i>
                </button>
                <h4 class="modal-title" id="rModalLabel"><?=lang('return_amount');?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group all">
                    <div class="input-group">
                        <?= form_input('return_code_input', '', 'class="form-control" id="return_code_input" placeholder="' . $this->lang->line("return_amount_note").'"  required="required"') ?>
                        <span class="input-group-addon pointer" id="findReturnInvoice" style="padding: 1px 10px;">
                            <i class="fa fa-2x fa-search"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo form_input(array('name' => 'return_input', 'type'=>'hidden', 'id' =>'return_input')); ?>
                </div>
                <div class="form-group">
                    <span style="padding-left: 5px; font-weight: bold; font-size: 20px;" id="txt_return_amount">0</span>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="updateReturn" class="btn btn-primary"><?=lang('update')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="txModal" tabindex="-1" role="dialog" aria-labelledby="txModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="txModalLabel"><?=lang('edit_order_tax');?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?=lang("order_tax", "order_tax_input");?>
<?php
	$tr[""] = "";
	foreach ($tax_rates as $tax) {
	    $tr[$tax->id] = $tax->name;
	}
	echo form_dropdown('order_tax_input', $tr, "", 'id="order_tax_input" class="form-control pos-input-tip" style="width:100%;"');
?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="updateOrderTax" class="btn btn-primary"><?=lang('update')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="susModal" tabindex="-1" role="dialog" aria-labelledby="susModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="susModalLabel"><?=lang('suspend_sale');?></h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <?= form_input('reference_note', (!empty($reference_note) ? $reference_note : ''), 'class="form-control kb-text" placeholder="' . $this->lang->line("reference_note").'" id="reference_note"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="suspend_sale" class="btn btn-primary"><?=lang('submit')?></button>
            </div>
        </div>
    </div>
</div>
<div id="order_tbl"><span id="order_span"></span>
    <table id="order-table" class="prT table table-striped" style="margin-bottom:0;" width="100%"></table>
</div>
<div id="bill_tbl"><span id="bill_span"></span>
    <table id="bill-table" width="100%" class="prT table table-striped" style="margin-bottom:0; font-size: 11px;"></table>
    <table id="bill-total-table" class="prT table" style="margin-bottom:0; font-size: 11px;" width="100%"></table>
    <span id="bill_footer"></span>
</div>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true"></div>
<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2"
     aria-hidden="true"></div>
<div id="modal-loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
</div>
<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification, $Settings->protocol, $Settings->mailpath, $Settings->smtp_crypto, $Settings->corn, $Settings->customer_group, $Settings->envato_username, $Settings->purchase_code);?>
<script type="text/javascript">
var site = <?=json_encode(array('url' => base_url(), 'base_url' => admin_url('/'), 'assets' => $assets, 'settings' => $Settings, 'dateFormats' => $dateFormats))?>, pos_settings = <?=json_encode($pos_settings);?>;
var lang = {
    unexpected_value: '<?=lang('unexpected_value');?>', 
    select_above: '<?=lang('select_above');?>', 
    r_u_sure: '<?=lang('r_u_sure');?>', 
    bill: '<?=lang('bill');?>', 
    order: '<?=lang('order');?>', 
    total: '<?=lang('total');?>',
    items: '<?=lang('items');?>',
    discount: '<?=lang('discount');?>',
    shipping: '<?=lang('bill_shipping');?>',
    return_amount: '<?=lang('return_amount');?>',
    order_tax: '<?=lang('order_tax');?>',
    grand_total: '<?=lang('grand_total');?>',
    total_payable: '<?=lang('total_payable');?>',
    rounding: '<?=lang('rounding');?>',
    footer_thanks: '<?=lang('footer_thanks');?>',
    not_found_return_invoice: '<?=lang('not_found_return_invoice')?>',
    this_is_promotion_product: '<?=lang('this_is_promotion_product')?>',
    update_product_quantity: '<?=lang('update_product_quantity')?>'
};
</script>

<script type="text/javascript">
    var product_variant = 0, cash_used = 0, shipping = 0, preturn = 0, p_page = 0, per_page = 0, tcp = "<?=$tcp?>", pro_limit = <?= $pos_settings->pro_limit; ?>,
        brand_id = 0, obrand_id = 0, cat_id = "<?=$pos_settings->default_category?>", ocat_id = "<?=$pos_settings->default_category?>", sub_cat_id = 0, osub_cat_id,
        count = 1, an = 1, DT = <?=$Settings->default_tax_rate?>,
        product_tax = 0, invoice_tax = 0, product_discount = 0, order_discount = 0, total_discount = 0, total = 0, total_paid = 0, grand_total = 0,
        KB = <?=$pos_settings->keyboard?>, tax_rates =<?php echo json_encode($tax_rates); ?>;
    var protect_delete = <?php if (!$Owner && !$Admin) {echo $pos_settings->pin_code ? '1' : '0';} else {echo '0';} ?>, billers = <?= json_encode($posbillers); ?>, biller = <?= json_encode($posbiller); ?>;
    var username = '<?=$this->session->userdata('username');?>', order_data = '', bill_data = '';
    var customer_points = 0;

    function widthFunctions(e) {
        var wh = $(window).height(),
            lth = $('#left-top').height(),
            lbh = $('#left-bottom').height();
        $('#item-list').css("height", wh - 140);
        $('#item-list').css("min-height", 515);
        $('#left-middle').css("height", wh - lth - lbh - 102);
        $('#left-middle').css("min-height", 278);
        $('#product-list').css("height", wh - lth - lbh - 107);
        $('#product-list').css("min-height", 278);
    }
    $(window).bind("resize", widthFunctions);
    function openMini(e) {
        $("#leftdiv").addClass('mini_leftdiv');
        $("#cpinner").addClass('mini_cpinner');
        $("#reset").addClass('font_size_16');
        $("#suspend").addClass('font_size_16');
        $("#print_bill").addClass('font_size_16');
        $("#payment").addClass('font_size_16');
        $("#cp").show();
        $(".btn-cat-con").show();
        $("#col_return").hide();
    }
    function closeMini(e) {
        $("#leftdiv").removeClass('mini_leftdiv');
        $("#cpinner").removeClass('mini_cpinner');
        $("#reset").removeClass('font_size_16');
        $("#suspend").removeClass('font_size_16');
        $("#print_bill").removeClass('font_size_16');
        $("#payment").removeClass('font_size_16');
        $("#cp").hide();
        $(".btn-cat-con").hide();
        $("#col_return").show();
    }
    $(document).ready(function () {
        

        $('#view-customer').click(function(){
            $('#myModal').modal({remote: site.base_url + 'customers/view/' + $("input[name=customer]").val()});
            $('#myModal').modal('show');
        });
        $('textarea').keydown(function (e) {
            if (e.which == 13) {
               var s = $(this).val();
               $(this).val(s+'\n').focus();
               e.preventDefault();
               return false;
            }
        });
        <?php if ($sid) { ?>
        localStorage.setItem('positems', JSON.stringify(<?=$items;?>));
        <?php } ?>

        <?php if ($oid) { ?>
        localStorage.setItem('positems', JSON.stringify(<?=$items;?>));
        <?php } ?>

<?php if ($this->session->userdata('remove_posls')) {?>
        if (localStorage.getItem('positems')) {
            localStorage.removeItem('positems');
        }
        if (localStorage.getItem('posdiscount')) {
            localStorage.removeItem('posdiscount');
        }
        if (localStorage.getItem('customer_group_id')) {
            localStorage.removeItem('customer_group_id');
        }
        if (localStorage.getItem('postax2')) {
            localStorage.removeItem('postax2');
        }
        if (localStorage.getItem('posshipping')) {
            localStorage.removeItem('posshipping');
        }
        if (localStorage.getItem('posreturn')) {
            localStorage.removeItem('posreturn');
        }
        if (localStorage.getItem('poswarehouse')) {
            localStorage.removeItem('poswarehouse');
        }
        if (localStorage.getItem('posnote')) {
            localStorage.removeItem('posnote');
        }
        if (localStorage.getItem('poscustomer')) {
            localStorage.removeItem('poscustomer');
        }
        if (localStorage.getItem('posbiller')) {
            localStorage.removeItem('posbiller');
        }
        if (localStorage.getItem('poscurrency')) {
            localStorage.removeItem('poscurrency');
        }
        if (localStorage.getItem('posnote')) {
            localStorage.removeItem('posnote');
        }
        if (localStorage.getItem('staffnote')) {
            localStorage.removeItem('staffnote');
        }
        if (localStorage.getItem('pos_delivery')) {
            localStorage.removeItem('pos_delivery');
        }
        <?php $this->sma->unset_data('remove_posls');}
        ?>
        widthFunctions();
        <?php if ($suspend_sale) {?>
        localStorage.setItem('postax2', '<?=$suspend_sale->order_tax_id;?>');
        localStorage.setItem('posdiscount', '<?=$suspend_sale->order_discount_id;?>');        
        localStorage.setItem('posdiscount', '<?=$suspend_sale->customer_group_id;?>');        
        localStorage.setItem('poswarehouse', '<?=$suspend_sale->warehouse_id;?>');
        localStorage.setItem('poscustomer', '<?=$suspend_sale->customer_id;?>');
        localStorage.setItem('posbiller', '<?=$suspend_sale->biller_id;?>');
        localStorage.setItem('posshipping', '<?=$suspend_sale->shipping;?>');
        localStorage.setItem('posreturn', '<?=$suspend_sale->return_amount;?>');
        <?php }
        ?>
        <?php if ($old_sale) {?>
        localStorage.setItem('postax2', '<?=$old_sale->order_tax_id;?>');
        localStorage.setItem('posdiscount', '<?=$old_sale->order_discount_id;?>');
        localStorage.setItem('posdiscount', '<?=$old_sale->customer_group_id;?>');
        localStorage.setItem('poswarehouse', '<?=$old_sale->warehouse_id;?>');
        localStorage.setItem('poscustomer', '<?=$old_sale->customer_id;?>');
        localStorage.setItem('posbiller', '<?=$old_sale->biller_id;?>');
        localStorage.setItem('posshipping', '<?=$old_sale->shipping;?>');
        localStorage.setItem('posreturn', '<?=$old_sale->return_amount;?>');
        <?php }
        ?>
<?php if ($this->input->get('customer')) {?>
        if (!localStorage.getItem('positems')) {
            localStorage.setItem('poscustomer', <?=$this->input->get('customer');?>);
        } else if (!localStorage.getItem('poscustomer')) {
            localStorage.setItem('poscustomer', <?=$customer->id;?>);
        }
        <?php } else {?>
        if (!localStorage.getItem('poscustomer')) {
            localStorage.setItem('poscustomer', <?=$customer->id;?>);
        }
        <?php }
        ?>
        if (!localStorage.getItem('postax2')) {
            localStorage.setItem('postax2', <?=$Settings->default_tax_rate2;?>);
        }
        $('.select').select2({minimumResultsForSearch: 7});
        // var customers = [{
        //     id: <?=$customer->id;?>,
        //     text: '<?=$customer->company == '-' ? $customer->name : $customer->company;?>'
        // }];
        $('#poscustomer').val(localStorage.getItem('poscustomer')).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) { // Hiển thị Khách Ba-Ni mặc định
                $.ajax({
                    type: "get", async: false,
                    url: "<?=admin_url('customers/getCustomer')?>/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
        if (KB) {
            display_keyboards();

            var result = false, sct = '';
            $('#poscustomer').on('select2-opening', function () {
                sct = '';
                $('.select2-input').addClass('kb-text');
                display_keyboards();
                $('.select2-input').bind('change.keyboard', function (e, keyboard, el) {
                    if (el && el.value != '' && el.value.length > 0 && sct != el.value) {
                        sct = el.value;
                    }
                    if(!el && sct.length > 0) {
                        $('.select2-input').addClass('select2-active');
                        setTimeout(function() {
                            $.ajax({
                                type: "get",
                                async: false,
                                url: "<?=admin_url('customers/suggestions')?>/?term=" + sct,
                                dataType: "json",
                                success: function (res) {
                                    if (res.results != null) {
                                        $('#poscustomer').select2({data: res}).select2('open');
                                        $('.select2-input').removeClass('select2-active');
                                    } else {
                                        // bootbox.alert('no_match_found');
                                        $('#poscustomer').select2('close');
                                        $('#test').click();
                                    }
                                }
                            });
                        }, 500);
                    }
                });
            });

            $('#poscustomer').on('select2-close', function () {
                $('.select2-input').removeClass('kb-text');
                $('#test').click();
                $('select, .select').select2('destroy');
                $('select, .select').select2({minimumResultsForSearch: 7});
            });
            $(document).bind('click', '#test', function () {
                var kb = $('#test').keyboard().getkeyboard();
                kb.close();
                //kb.destroy();
                $('#add-item').focus();
            });

        }
        $(document).on('change', '#poscustomer', function () {
            //console.log(localStorage.getItem('poscustomer'));
            $.ajax({
                type: "get",
                async: false,
                url: "<?= admin_url('customers/getCustomerGroupByCustomerID') ?>/" + localStorage.getItem('poscustomer'),
                dataType: "json",
                success: function (cgdata) {
                    $("#customer_group_id").val(cgdata['id']);
                    var percent = cgdata['customer_group_percent'];
                    if (is_valid_discount(percent)) {
                        var realPercent = (percent*-1) + '%';
                        $('#posdiscount').val(realPercent);

                        if (percent != 0) {
                            var str = cgdata['customer_group_name'] + '<?= lang('vip_discount') ?>' + realPercent;
                            $('#posInfoModalLabel').text(str);
                            $('#posInfoModal').modal();
                        } else {
                            realPercent = 0;
                        }
                        localStorage.removeItem('posdiscount');
                        localStorage.setItem('posdiscount', realPercent);
                        localStorage.removeItem('customer_group_id');
                        localStorage.setItem('customer_group_id', cgdata['id']);
                        loadItems();
                    }
                },
                error: function () {
                    bootbox.alert('<?= lang('ajax_error') ?>');
                }
            });

        });
        $(document).on('change', '#posbiller', function () {
            var sb = $(this).val();
            $.each(billers, function () {
                if(this.id == sb) {
                    biller = this;
                }
            });
            $('#biller').val(sb);
        });

        <?php for ($i = 1; $i <= 5; $i++) {?>
        $('#paymentModal').on('change', '#amount_<?=$i?>', function (e) {
            $('#amount_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('blur', '#amount_<?=$i?>', function (e) {
            //bootbox.alert('xxff' + $(this).val());
            $('#amount_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('keyup', '#amount_<?=$i?>', function (e) {
            calculateTotals();
            $('#amount_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('select2-close', '#paid_by_<?=$i?>', function (e) {
            $('#paid_by_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_no_<?=$i?>', function (e) {
            $('#cc_no_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_holder_<?=$i?>', function (e) {
            $('#cc_holder_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#gift_card_no_<?=$i?>', function (e) {
            $('#paying_gift_card_no_val_<?=$i?>').val($(this).val());
        });

        $('#paymentModal').on('change', '#pcc_month_<?=$i?>', function (e) {
            $('#cc_month_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_year_<?=$i?>', function (e) {
            $('#cc_year_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_type_<?=$i?>', function (e) {
            $('#cc_type_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_cvv2_<?=$i?>', function (e) {
            $('#cc_cvv2_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#cheque_no_<?=$i?>', function (e) {
            $('#cheque_no_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#payment_note_<?=$i?>', function (e) {
            $('#payment_note_val_<?=$i?>').val($(this).val());
        });
        <?php }
        ?>
        $('#payment').click(function () {
            <?php if ($sid) {?>
            suspend = $('<span></span>');
            suspend.html('<input type="hidden" name="delete_id" value="<?php echo $sid; ?>" />');
            suspend.appendTo("#hidesuspend");
            <?php }
            ?>
           
            if ($('#has_out_of_stock_products').val() == 1) {
                bootbox.alert('<?=lang('update_product_quantity');?>');
                return false;
            }
              
            var twt = formatDecimal((total + invoice_tax) - order_discount - preturn + shipping);
            if (count == 1) {
                bootbox.alert('<?=lang('x_total');?>');
                return false;
            }
            gtotal = formatDecimal(twt);
            <?php if ($pos_settings->rounding) {?>
            round_total = roundNumber(gtotal, <?=$pos_settings->rounding?>);
            var rounding = formatDecimal(0 - (gtotal - round_total));
            $('#twt').text(formatMoney(round_total) + ' (' + formatMoney(rounding) + ')');
            $('#quick-payable').text(formatMoney(round_total));
            $('#amount_1').val(0);
            <?php } else {?>
            $('#twt').text(formatMoney(gtotal));
            $('#quick-payable').text(formatMoney(gtotal));
            $('#amount_1').val(0);
            <?php }
            ?>
            $('#item_count').text(count - 1);
            $('#paymentModal').appendTo("body").modal('show');
            $('#amount_1').select().focus();
        });
        $('#paymentModal').on('show.bs.modal', function(e) {
            $('#submit-sale').text('<?=lang('submit1');?>').attr('disabled', false);
            if ($('#poscustomer').val() == '1') { // Neu khach le thi remove hinh thuc thanh toan diem tich luy
                $("#paid_by_1 option[value='pts']").remove();
            } else {
                if ( $("#paid_by_1 option[value='pts']").length == 0 ){

                    $('#paid_by_1').append($('<option>', {
                        value: 'pts',
                        text: '<?=lang('Pts');?>'
                    }));
                    $('#paid_by_1 option[value="pts"]').insertAfter('#paid_by_1 option[value="cash"]');
                }
            }
        });
        $('#paymentModal').on('shown.bs.modal', function(e) {
            $('#amount_1').focus();
            $('#customer').select2({
                minimumInputLength: 1,
                ajax: {
                    url: site.base_url + "customers/suggestions",
                    dataType: 'json',
                    quietMillis: 15,
                    data: function (term, page) {
                        return {
                            term: term,
                            limit: 10
                        };
                    },
                    results: function (data, page) {
                        if (data.results != null) {
                            return {results: data.results};
                        } else {
                            return {results: [{id: '', text: 'No Match Found'}]};
                        }
                    }
                }
            });

        });



        var pi = 'amount_1', pa = 2;
        $(document).on('click', '.quick-cash', function () {
            var $quick_cash = $(this);
            var amt = $quick_cash.contents().filter(function () {
                return this.nodeType == 3;
            }).text();
            var th = '.';
            var $pi = $('#' + pi);
            amt = formatDecimal(amt.split(th).join("")) * 1 + $pi.val() * 1;
            $pi.val(formatDecimal(amt)).focus();
            var note_count = $quick_cash.find('span');
            if (note_count.length == 0) {
                $quick_cash.append('<span class="badge">1</span>');
            } else {
                note_count.text(parseInt(note_count.text()) + 1);
            }
        });

        $(document).on('click', '#clear-cash-notes', function () {
            $('.quick-cash').find('.badge').remove();
            $('#' + pi).val('0').focus();
        });


        $(document).on('change', '.gift_card_no', function () {
            var cn = $(this).val() ? $(this).val() : '';
            var payid = $(this).attr('id'),
                id = payid.substr(payid.length - 1);
            if (cn != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_gift_card/" + cn,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#gift_card_no_' + id).parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('incorrect_gift_card')?>');
                        } else {
                            $('#gc_details_' + id).html('<small>Mã phiếu: ' + data.card_no + '<br>Số tiền: ' + formatMoney(data.value) + '</small>');
                            $('#gift_card_no_' + id).parent('.form-group').removeClass('has-error');
                            //calculateTotals();
                            $('#amount_' + id).val(gtotal).focus();
                            $('#twt').text(formatMoney(gtotal-data.value));


                            //calculateTotals();
                            //gtotal = formatDecimal(twt);




                        }
                    }
                });
            }
        });

        $(document).on('click', '.addButton', function () {
            if (pa <= 2) {

                $('#paid_by_1, #pcc_type_1').select2('destroy');
                var phtml = $('#payments').html(),
                    update_html = phtml.replace(/_1/g, '_' + pa);
                pi = 'amount_' + pa;
                $('#multi-payment').append('<button type="button" class="close close-payment" style="margin: -10px 0px 0 0;"><i class="fa fa-2x">&times;</i></button>' + update_html);
                $('#paid_by_1, #pcc_type_1, #paid_by_' + pa + ', #pcc_type_' + pa).select2({minimumResultsForSearch: 7});
                read_card();
                if ($('#poscustomer').val() == '1') { // Neu khach le thi remove hinh thuc thanh toan diem tich luy
                    $("#paid_by_2 option[value='pts']").remove();
                }
                if ($('#paid_by_1').val() == 'CC') {
                    $("#paid_by_2 option[value='CC']").remove();
                    $("#paid_by_2 option[value='vnpay']").remove();
                } else if ($('#paid_by_1').val() == 'pos') {
                    $("#paid_by_2 option[value='pts']").remove();
                    $("#paid_by_2 option[value='pos']").remove();
                    $("#paid_by_2 option[value='vnpay']").remove();
                } else if ($('#paid_by_1').val() == 'vnpay') {
                    $("#paid_by_2 option[value='pts']").remove();
                    $("#paid_by_2 option[value='pos']").remove();
                    $("#paid_by_2 option[value='vnpay']").remove();
                    $("#paid_by_2 option[value='CC']").remove();
                } else if ($('#paid_by_1').val() == 'pts') {
                    $("#paid_by_2 option[value='pts']").remove();
                    $("#paid_by_2 option[value='pos']").remove();
                    $("#paid_by_2 option[value='vnpay']").remove();
                } else if ($('#paid_by_1').val() == 'cash'){
                    $("#paid_by_2 option[value='cash']").remove();
                }
                $("#paid_by_2").prop("selectedIndex", 0).change();
                $("#paid_by_val_2").val($('#paid_by_2').val());
                pa++;
            } else {
                bootbox.alert('<?=lang('max_reached')?>');
                return false;
            }
            //display_keyboards();
            $('#paymentModal').css('overflow-y', 'scroll');
        });

        $(document).on('click', '.close-payment', function () {
            $(this).next().remove();
            $(this).remove();
            pa--;
        });

        $(document).on('focus', '.amount', function () {
            pi = $(this).attr('id');
            calculateTotals();
        }).on('blur', '.amount', function () {
            calculateTotals();
        });

        function calculateTotals() {

            var total_paying = 0;
            var ia = $(".amount");
            $.each(ia, function (i) {
                var this_amount = formatCNum($(this).val() ? $(this).val() : 0);
                total_paying += parseFloat(this_amount);
            });
            $('#total_paying').text(formatMoney(total_paying));
            //console.log(' - cash_used: ' + cash_used);
            $('#number_to_string_1').text(upperFirst(NUMBERTOSTRING.read(total_paying)) + ' đồng')
            <?php if ($pos_settings->rounding) {?>
            $('#balance').text(formatMoney((total_paying - round_total) + cash_used));
            $('#balance_' + pi).val(formatDecimal((total_paying - round_total) + cash_used));
            total_paid = total_paying;
            grand_total = round_total;
            <?php } else {?>
            $('#balance').text(formatMoney(total_paying - gtotal));
            $('#balance_' + pi).val(formatDecimal(total_paying - gtotal));
            total_paid = total_paying;
            grand_total = gtotal;
            
            <?php }
            ?>
        }

        $("#add_item").autocomplete({
            source: function (request, response) {


                if (!$('#poscustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?=admin_url('sales/suggestions');?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#poswarehouse").val(),
                        customer_id: $("#poscustomer").val()
                    },
                    success: function (data) {
                        $(this).removeClass('ui-autocomplete-loading');
                        //console.log(JSON.stringify(data));
                        response(data);
                    }
                });
            },
            minLength: 2,
            autoFocus: false,
            delay: 6000,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    bootbox.alert('<?=lang('no_match_found')?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?=lang('no_match_found')?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    //console.log(JSON.stringify(ui.item));
                    if (row)
                        $(this).val('');
                } else {
                    bootbox.alert('<?=lang('no_match_found')?>');
                }
            }
        });

        <?php if ($pos_settings->tooltips) {echo '$(".pos-tip").tooltip();';}
        ?>
        // $('#posTable').stickyTableHeaders({fixedOffset: $('#product-list')});
        $('#posTable').stickyTableHeaders({scrollableArea: $('#product-list')});
        $('#product-list, #category-list, #subcategory-list').perfectScrollbar({suppressScrollX: true});
        $('select, .select').select2({minimumResultsForSearch: 7});

        $(document).on('click', '.product', function (e) {
           
            $('#modal-loading').show();
            code = $(this).val(),
            wh = $('#poswarehouse').val(),
            cu = $('#poscustomer').val();

            //alert(code + '-' + wh + '-' + cu);
            $.ajax({
                type: "get",
                url: "<?=admin_url('pos/getProductDataByCode')?>",
                data: {code: code, warehouse_id: wh, customer_id: cu},
                dataType: "json",
                success: function (data) {
                    e.preventDefault();
                    if (data !== null) {
                        //console.log(JSON.stringify(data));
                        add_invoice_item(data);
                        $('#modal-loading').hide();
                    } else {
                        bootbox.alert('<?=lang('no_match_found')?>');
                        $('#modal-loading').hide();
                    }
                }
            });
        });

        $(document).on('click', '.category', function () {
            if (cat_id != $(this).val()) {
                $('#open-category').click();
                $('#modal-loading').show();
                cat_id = $(this).val();
                $.ajax({
                    type: "get",
                    url: "<?=admin_url('pos/ajaxcategorydata');?>",
                    data: {category_id: cat_id},
                    dataType: "json",
                    success: function (data) {
                        $('#item-list').empty();
                        var newPrs = $('<div></div>');
                        newPrs.html(data.products);
                        newPrs.appendTo("#item-list");
                        $('#subcategory-list').empty();
                        var newScs = $('<div></div>');
                        newScs.html(data.subcategories);
                        newScs.appendTo("#subcategory-list");
                        tcp = data.tcp;
                        nav_pointer();
                    }
                }).done(function () {
                    p_page = 'n';
                    $('#category-' + cat_id).addClass('active');
                    $('#category-' + ocat_id).removeClass('active');
                    ocat_id = cat_id;
                    $('#modal-loading').hide();
                    nav_pointer();
                });
            }
        });
        $('#category-' + cat_id).addClass('active');

        $(document).on('click', '.brand', function () {
            if (brand_id != $(this).val()) {
                $('#open-brands').click();
                $('#modal-loading').show();
                brand_id = $(this).val();
                $.ajax({
                    type: "get",
                    url: "<?=admin_url('pos/ajaxbranddata');?>",
                    data: {brand_id: brand_id},
                    dataType: "json",
                    success: function (data) {
                        $('#item-list').empty();
                        var newPrs = $('<div></div>');
                        newPrs.html(data.products);
                        newPrs.appendTo("#item-list");
                        tcp = data.tcp;
                        nav_pointer();
                    }
                }).done(function () {
                    p_page = 'n';
                    $('#brand-' + brand_id).addClass('active');
                    $('#brand-' + obrand_id).removeClass('active');
                    obrand_id = brand_id;
                    $('#category-' + cat_id).removeClass('active');
                    $('#subcategory-' + sub_cat_id).removeClass('active');
                    cat_id = 0; sub_cat_id = 0;
                    $('#modal-loading').hide();
                    nav_pointer();
                });
            }
        });

        $(document).on('click', '.subcategory', function () {
            if (sub_cat_id != $(this).val()) {
                $('#open-subcategory').click();
                $('#modal-loading').show();
                sub_cat_id = $(this).val();
                $.ajax({
                    type: "get",
                    url: "<?=admin_url('pos/ajaxproducts');?>",
                    data: {category_id: cat_id, subcategory_id: sub_cat_id, per_page: p_page},
                    dataType: "html",
                    success: function (data) {
                        $('#item-list').empty();
                        var newPrs = $('<div></div>');
                        newPrs.html(data);
                        newPrs.appendTo("#item-list");
                    }
                }).done(function () {
                    p_page = 'n';
                    $('#subcategory-' + sub_cat_id).addClass('active');
                    $('#subcategory-' + osub_cat_id).removeClass('active');
                    $('#modal-loading').hide();
                });
            }
        });

        $('#next').click(function () {
            if (p_page == 'n') {
                p_page = 0
            }
            p_page = p_page + pro_limit;
            if (tcp >= pro_limit && p_page < tcp) {
                $('#modal-loading').show();
                $.ajax({
                    type: "get",
                    url: "<?=admin_url('pos/ajaxproducts');?>",
                    data: {category_id: cat_id, subcategory_id: sub_cat_id, per_page: p_page},
                    dataType: "html",
                    success: function (data) {
                        $('#item-list').empty();
                        var newPrs = $('<div></div>');
                        newPrs.html(data);
                        newPrs.appendTo("#item-list");
                        nav_pointer();
                    }
                }).done(function () {
                    $('#modal-loading').hide();
                });
            } else {
                p_page = p_page - pro_limit;
            }
        });

        $('#previous').click(function () {
            if (p_page == 'n') {
                p_page = 0;
            }
            if (p_page != 0) {
                $('#modal-loading').show();
                p_page = p_page - pro_limit;
                if (p_page == 0) {
                    p_page = 'n'
                }
                $.ajax({
                    type: "get",
                    url: "<?=admin_url('pos/ajaxproducts');?>",
                    data: {category_id: cat_id, subcategory_id: sub_cat_id, per_page: p_page},
                    dataType: "html",
                    success: function (data) {
                        $('#item-list').empty();
                        var newPrs = $('<div></div>');
                        newPrs.html(data);
                        newPrs.appendTo("#item-list");
                        nav_pointer();
                    }

                }).done(function () {
                    $('#modal-loading').hide();
                });
            }
        });

        $(document).on('change', '.paid_by', function () {
            //$('#clear-cash-notes').click();
            var p_val = $(this).val(),
                id = $(this).attr('id'),
                pa_no = id.substr(id.length - 1);
            $('#rpaidby').val(p_val);
            if (p_val == 'cash' || p_val == 'other') {
                $('.pcheque_' + pa_no).hide();
                $('.pcc_' + pa_no).hide();
                $('.gc_' + pa_no).hide();
                $('.pts_' + pa_no).hide();
                $('.pcash_' + pa_no).show();
                $('#amount_' + pa_no).focus();

                $('#ca_points_' + pa_no).val(0);
                $('#details_pts_' + pa_no).html('');
                $('#paying_points_val_' + pa_no).val(0);
                $('#twt').text(formatMoney(gtotal));

            } else if (p_val == 'CC' || p_val == 'vnpay' || p_val == 'pos') {
                $('.pcheque_' + pa_no).hide();
                $('.pcash_' + pa_no).hide();
                $('.gc_' + pa_no).hide();
                $('.pts_' + pa_no).hide();
                $('.pcc_' + pa_no).show();
                $('#swipe_' + pa_no).focus();

                $('#ca_points_' + pa_no).val(0);
                $('#details_pts_' + pa_no).html('');
                $('#paying_points_val_' + pa_no).val(0);
                $('#twt').text(formatMoney(gtotal));
            }
            if (p_val == 'gift_card') {
                $('.gc_' + pa_no).show();
                $('.pts_' + pa_no).hide();
                $('.ngc_' + pa_no).hide();
                $('.pcc_' + pa_no).hide();
                $('#gift_card_no_' + pa_no).focus();

                $('#ca_points_' + pa_no).val(0);
                $('#details_pts_' + pa_no).html('');
                $('#paying_points_val_' + pa_no).val(0);
                $('#twt').text(formatMoney(gtotal));
            } else if (p_val == 'pts') {
                $('.pts_' + pa_no).show();
                var selected_customer = $('#poscustomer').val();
                if (selected_customer != 1) {
                    $.ajax({
                        type: "get", async: false,
                        url: site.base_url + "customers/get_award_points/" + selected_customer,
                        dataType: 'json',
                        success: function (data) {
                            if (data != null) {
                                customer_points = parseFloat(data.ca_points).toFixed(2);
                                $('#award_points_' + pa_no).html(data.ca_points);
                                if (grand_total/1000 <= data.ca_points) {
                                    $('#ca_points_' + pa_no).val(grand_total/1000);
                                    //console.log(data.ca_points + ' xxx');
                                } else {
                                    $('#ca_points_' + pa_no).val(data.ca_points);
                                    //console.log(data.ca_points + ' yyy');
                                }
                                $('#ca_points_' + pa_no).select().focus();
                                //console.log(data.ca_points);
                            }
                        }
                    });
                }



                $('.gc_' + pa_no).hide();
                $('.pcc_' + pa_no).hide();
                $('.ngc_' + pa_no).hide();
                $('#pts_' + pa_no).focus();
            }
            else {
                $('.ngc_' + pa_no).show();
                $('.gc_' + pa_no).hide();
                $('#gc_details_' + pa_no).html('');

                $('#ca_points_' + pa_no).val(0);
                $('#details_pts_' + pa_no).html('');
                $('#paying_points_val_' + pa_no).val(0);
                $('#twt').text(formatMoney(gtotal));
            }
        });


        $(".ca_points").on('keyup', function (e) {
            if (e.keyCode == 13) {
                var payid = $(this).attr('id'),
                    id = payid.substr(payid.length - 1);
                $('#change_points_' + id).click();
            }
        });

        $(document).on('click', '.change_points', function () {

            var payid = $(this).attr('id'),
                id = payid.substr(payid.length - 1);
            var points_used = parseFloat($('#ca_points_' + id).val()).toFixed(2);
            cash_used = points_used*1000;


            var remaining_points = customer_points - points_used;

            remaining_points = remaining_points.toFixed(2);
            //console.log(points_used + ' - ' + customer_points);

            $('#paying_points_val_' + id).val(cash_used);
            $('#details_pts_' + id).html('<span style="color: #fb8e41"> Điểm: <strong>' + points_used + '</strong><br />Số tiền: <strong>-' + formatMoney(cash_used) + '</strong><br />Điểm còn lại: <strong>' + remaining_points + '</strong></span>');
            //$('#gift_card_no_' + id).parent('.form-group').removeClass('has-error');
            //calculateTotals();
            //$('#amount_' + id).val(gtotal).focus();
            $('#twt').text(formatMoney(gtotal-cash_used));
            $('#quick-payable').text(formatMoney(round_total-cash_used));
            $('#amount_1').select().focus();

            calculateTotals();
        });

        $(document).on('click', '#submit-sale', function () {
            if (total_paid == 0 || total_paid < grand_total - cash_used) {
                bootbox.confirm("<?=lang('paid_l_t_payable');?>", function (res) {
                    if (res == true) {
                        $('#pos_note').val(localStorage.getItem('posnote'));
                        $('#staff_note').val(localStorage.getItem('staffnote'));
                        $('#delivery_method').val(localStorage.getItem('pos_delivery'));
                        $('#submit-sale').text('<?=lang('loading');?>').attr('disabled', true);
                        $('#pos-sale-form').submit();
                    }
                });
                return false;
            } else {
                $('#pos_note').val(localStorage.getItem('posnote'));
                $('#staff_note').val(localStorage.getItem('staffnote'));
                $('#delivery_method').val(localStorage.getItem('pos_delivery'));
                $(this).text('<?=lang('loading');?>').attr('disabled', true);
                $('#pos-sale-form').submit();
            }
        });
        $('#suspend').click(function () {
            if (count <= 1) {
                bootbox.alert('<?=lang('x_suspend');?>');
                return false;
            } else {
                $('#susModal').modal();
            }
        });
        $('#suspend_sale').click(function () {
            ref = $('#reference_note').val();
            if (!ref || ref == '') {
                bootbox.alert('<?=lang('type_reference_note');?>');
                return false;
            } else {
                suspend = $('<span></span>');
                <?php if ($sid) {?>
                suspend.html('<input type="hidden" name="delete_id" value="<?php echo $sid; ?>" /><input type="hidden" name="suspend" value="yes" /><input type="hidden" name="suspend_note" value="' + ref + '" />');
                <?php } else {?>
                suspend.html('<input type="hidden" name="suspend" value="yes" /><input type="hidden" name="suspend_note" value="' + ref + '" />');
                <?php }
                ?>
                suspend.appendTo("#hidesuspend");
                $('#total_items').val(count - 1);
                $('#pos-sale-form').submit();

            }
        });
    });
 
    $(document).ready(function () {
       
        $('#poswarehouse').change(function () {
            var v = $(this).val();
            if (v != 3) {
                closeMini();
            } else {
                openMini();
                console.log('1111');
            }

        });
        $("#poscustomer").change();
        if (localStorage.getItem('poswarehouse') != 3) {
            closeMini();
        } else {
            openMini();
            //console.log('222');
            //$('.btn-cat-con').toggle('slide', { direction: 'right' }, 100);
        }
        
        $('#print_order').click(function () {
            if (count == 1) {
                bootbox.alert('<?=lang('x_total');?>');
                return false;
            }
            <?php if ($pos_settings->remote_printing != 1) { ?>
                printOrder();
            <?php } else { ?>
                Popup($('#order_tbl').html());
            <?php } ?>
        });
        $('#print_bill').click(function () {
            if (count == 1) {
                bootbox.alert('<?=lang('x_total');?>');
                return false;
            }
            <?php if ($pos_settings->remote_printing != 1) { ?>
                printBill();
            <?php } else { ?>
                Popup($('#bill_tbl').html());
            <?php } ?>
        });
        $('#mModal').on('shown.bs.modal', function() {
            $(this).find('#mcode').val('BN');
            $(this).find('#mcode').prop('readonly', true);
            $(this).find('#mname').select().focus();
            $(this).find('#mquantity').val('1');
            $(this).keypress(function( e ) {
                if ( e.which == 13 ) {
                    $('#addItemManually').click();
                }
            });
        });
        $('#susModal').on('shown.bs.modal', function() {
            $(this).find('#reference_note').select().focus();
            $(this).keypress(function( e ) {
                if ( e.which == 13 ) {
                    $('#suspend_sale').click();
                }
            });
        });
        $('#cmModal').on('shown.bs.modal', function() {
            $(this).find('#icomment').select().focus();
            $(this).keypress(function( e ) {
                if ( e.which == 13 ) {
                    $('#editComment').click();
                }
            });
        });
        $('#prModal').on('shown.bs.modal', function() {
            $(this).find('#pdiscount').select().focus();

            $(this).keypress(function( e ) {
                if ( e.which == 13 ) {
                    $('#editItem').click();
                }
            });
        });

        $('#sModal').on('shown.bs.modal', function() {
            $(this).keypress(function( e ) {
                if ( e.which == 13 ) {
                    $('#updateShipping').click();
                }
            });
        });
        $('#rModal').on('shown.bs.modal', function() {
            $(this).keypress(function( e ) {
                if ( e.which == 13 ) {
                    $('#findReturnInvoice').click();
                }
            });
        });

    });

    $(function () {
        $(".alert").effect("shake");
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 15000);
        <?php if ($pos_settings->display_time) {?>
        var now = new moment();
        $('#display_time').text(now.format((site.dateFormats.js_sdate).toUpperCase() + " HH:mm"));
        setInterval(function () {
            var now = new moment();
            $('#display_time').text(now.format((site.dateFormats.js_sdate).toUpperCase() + " HH:mm"));
        }, 1000);
        <?php }
        ?>
    });
    <?php if ($pos_settings->remote_printing == 1) { ?>
    function Popup(data) {
        var mywindow = window.open('', 'sma_pos_print', 'height=500,width=300');
        mywindow.document.write('<html><head><title>Print</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?=$assets?>styles/helpers/bootstrap.min.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.print();
        mywindow.close();
        return true;
    }
    <?php }
    ?>
</script>
<?php
	$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
	foreach (lang('select2_lang') as $s2_key => $s2_line) {
	    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
	}
	$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
?>
<script type="text/javascript" src="<?=$assets?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/select2.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/custom.js"></script>
<script type="text/javascript" src="<?=$assets?>js/jquery.calculator.min.js"></script>
<script type="text/javascript" src="<?=$assets?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?=$assets?>pos/js/plugins.min.js"></script>
<script type="text/javascript" src="<?=$assets?>pos/js/parse-track-data.js"></script>
<script type="text/javascript" src="<?=$assets?>pos/js/pos.ajax.js"></script>
<?php
if ( ! $pos_settings->remote_printing) {
    ?>
    <script type="text/javascript">
        var order_printers = <?= json_encode($order_printers); ?>;
        function printOrder() {
            $.each(order_printers, function() {
                var socket_data = { 'printer': this, 
                'logo': (biller && biller.logo ? biller.logo : ''), 
                'text': order_data };
                $.get('<?= admin_url('pos/p/order'); ?>', {data: JSON.stringify(socket_data)});
            });
            return false;
        }

        function printBill() {
            var socket_data = {
                'printer': <?= json_encode($printer); ?>,
                'logo': (biller && biller.logo ? biller.logo : ''),
                'text': bill_data
            };
            $.get('<?= admin_url('pos/p'); ?>', {data: JSON.stringify(socket_data)});
            return false;
        }
    </script>
    <?php
} elseif ($pos_settings->remote_printing == 2) {
    ?>
    <script src="<?= $assets ?>js/socket.io.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        socket = io.connect('http://localhost:6440', {'reconnection': false});

        function printBill() {
            if (socket.connected) {
                var socket_data = {'printer': <?= json_encode($printer); ?>, 'text': bill_data};
                socket.emit('print-now', socket_data);
                return false;
            } else {
                bootbox.alert('<?= lang('pos_print_error'); ?>');
                return false;
            }
        }

        var order_printers = <?= json_encode($order_printers); ?>;
        function printOrder() {
            if (socket.connected) {
                $.each(order_printers, function() {
                    var socket_data = {'printer': this, 'text': order_data};
                    socket.emit('print-now', socket_data);
                });
                return false;
            } else {
                bootbox.alert('<?= lang('pos_print_error'); ?>');
                return false;
            }
        }
    </script>
    <?php

} elseif ($pos_settings->remote_printing == 3) {

    ?>
    <script type="text/javascript">
        try {
            socket = new WebSocket('ws://127.0.0.1:6441');
            socket.onopen = function () {
                console.log('Connected');
                return;
            };
            socket.onclose = function () {
                console.log('Not Connected');
                return;
            };
        } catch (e) {
            console.log(e);
        }

        var order_printers = <?= $pos_settings->local_printers ? "''" : json_encode($order_printers); ?>;
        function printOrder() {
            if (socket.readyState == 1) {

                if (order_printers == '') {

                    var socket_data = { 'printer': false, 'order': true,
                    'logo': (biller && biller.logo ? site.base_url+'assets/uploads/logos/'+biller.logo : ''), 
                    'text': order_data };
                    socket.send(JSON.stringify({type: 'print-receipt', data: socket_data}));

                } else {

                $.each(order_printers, function() {
                    var socket_data = { 'printer': this, 
                    'logo': (biller && biller.logo ? site.base_url+'assets/uploads/logos/'+biller.logo : ''), 
                    'text': order_data };
                    socket.send(JSON.stringify({type: 'print-receipt', data: socket_data}));
                });

            }
                return false;
            } else {
                bootbox.alert('<?= lang('pos_print_error'); ?>');
                return false;
            }
        }

        function printBill() {
            if (socket.readyState == 1) {
                var socket_data = {
                    'printer': <?= $pos_settings->local_printers ? "''" : json_encode($printer); ?>,
                    'logo': (biller && biller.logo ? site.base_url+'assets/uploads/logos/'+biller.logo : ''),
                    'text': bill_data
                };
                socket.send(JSON.stringify({type: 'print-receipt', data: socket_data}));
                return false;
            } else {
                bootbox.alert('<?= lang('pos_print_error'); ?>');
                return false;
            }
        }
    </script>
    <?php
}
?>
<script type="text/javascript">
$('.sortable_table tbody').sortable({
    containerSelector: 'tr'
});
</script>
<script type="text/javascript" charset="UTF-8"><?=$s2_file_date?></script>
<div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>
<?php 
if (isset($print) && !empty($print)) {
    /* include FCPATH.'themes'.DIRECTORY_SEPARATOR.$Settings->theme.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'pos'.DIRECTORY_SEPARATOR.'remote_printing.php'; */
    include 'remote_printing.php';
}
?>
</body>
</html>
