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

    <script>
var IS_CUSTOMER_SCREEN_CONTROLLER =
<?= $is_customer_screen_controller ? 'true' : 'false' ?>;
</script>
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
            <div class="header-left">
                <a
                    href="#"
                    id="btn_order_list"
                    class="btn bblue pos-tip order-header-button"
                    title="Đơn hàng trong ngày"
                    data-container="body"
                    data-placement="bottom"
                >
                    <i class="fa fa-list-alt"></i>
                    <span class="order-list-title"> ĐƠN HÀNG</span>
                </a>
                
                <a
                    class="btn blightOrange pos-tip order-header-button"
                    id="opened_bills"
                    title="Đơn tạm"
                    data-container="body"
                    data-placement="bottom"
                    href="<?=admin_url('pos/opened_bills')?>"
                    data-toggle="ajax"
                >
                    <i class="fa fa-th"></i>
                    <span class="suspend_list_title">ĐƠN TẠM</span>
                    <span class="pos-header-count pos-header-count-suspended" id="suspended_order_count" style="display:none;">0</span>
                </a>

                
                <!-- MENU TIỆN ÍCH -->
                <div style="display:inline-block;">
                    <a href="#"
                    class="btn bblue"
                    id="btn_pos_utilities">
                        <i class="fa fa-th-large"></i>
                        TIỆN ÍCH
                        <i class="fa fa-caret-down"></i>
                    </a>
                </div>

                <!-- Menu sẽ được JS đưa ra ngoài body để không bị select khách che -->
                <div id="posUtilitiesMenu" class="pos-utilities-menu" style="display:none;">
                    <a href="#" id="btn_open_pos_purchase">
                        <i class="fa fa-truck"></i>
                        <span>NHẬP HÀNG</span>
                    </a>
                </div>


            </div>
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
                    <?php if (($Owner || $Admin || $GP['reports-quantity_alerts'] || $GP['reports-expiry_alerts']) && ($qty_alert_num > 0 || $exp_alert_num > 0 || $shop_sale_alerts)) { ?>
                    <li class="dropdown hidden-sm">
                        <a class="btn blightOrange tip" title="<?= lang('alerts') ?>" data-placement="left" target="_blank" href="<?= admin_url('reports/quantity_alerts') ?>">
                            <i class="fa fa-exclamation-triangle alert-blink-color"></i>
                            <span class="number bred black"><?= $qty_alert_num+(($Settings->product_expiry) ? $exp_alert_num : 0)+$shop_sale_alerts+$shop_payment_alerts; ?></span>
                        </a>                        
                    </li>
                    <?php } ?>
                    <li class="dropdown">
                        <a class="btn borange change_lang" title="Vi/En" data-container="body" data-placement="bottom">VI/EN</a>
                    </li>                    

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

                    <!-- THỜI GIAN -->
                    <li class="dropdown">
                        <a class="btn bblack" style="cursor: default;">
                            <span id="display_time"></span>
                        </a>
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
                                <input name="is_quick_finalize_sale" type="hidden" value="" id="is_quick_finalize_sale">
                                <input name="sale_language" type="hidden" value="0" id="sale_language">
                                    <div class="input-group-addon no-print" style="padding: 2px 16px; border-left: 0;">
                                        <a href="#" id="toogle-customer-read-attr" class="external pos-tip" title="<?=lang('customer_selection')?>">
                                            <i class="fa fa-pencil fa-2x" id="addIcon" style="font-size: 1.7em;"></i>
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
                                    <!-- <div class="input-group-addon no-print" style="padding: 2px 16px; border-left: 0;">
                                        <a href="#" id="sellGiftCard" class="external tip" title="<?=lang('sell_gift_card');?> (<?=$pos_settings->gift_card?>)" data-replacement="bottom">
                                            <i class="fa fa-credit-card addIcon fa-2x" id="addIcon" style="font-size: 1.7em;"></i>
                                        </a>
                                    </div> -->
                                </div>
                                <div style="clear:both;"></div>
                                <div class="form-group customer-type-wrapper">

                                    <div class="customer-name-box">
                                        <?php echo form_input(
                                            'customer_name',
                                            '',
                                            'class="form-control customer_name" id="customer_name" data-placement="top" placeholder="' . $this->lang->line("enter_customer_name") . '" title="" autocomplete="off"'
                                        ); ?>
                                        
                                        <div id="customer_name_suggestions"
                                            style="display:none; position:absolute; z-index:9999;
                                                    background:#fff; border:1px solid #3682c4;
                                                    width:100%; max-height:220px; overflow-y:auto;">
                                        </div>

                                    </div>

                                    <div class="customer-type-buttons">

                                        <button
                                            type="button"
                                            id="btn_mang_di"
                                            class="customer-type-btn"
                                        >
                                            MANG ĐI
                                        </button>

                                        <button
                                            type="button"
                                            id="btn_ban"
                                            class="customer-type-btn"
                                        >
                                            BÀN
                                        </button>

                                    </div>

                                </div>

                                <!-- Dữ liệu loại đơn -->
                                <input type="hidden" name="order_type" id="order_type" value="">
                                <input type="hidden" name="table_id" id="table_id" value="">
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
                                            <th width="52%"><?=lang("product");?></th>
                                            <th width="15%">
                                            <i class="fa fa-edit" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                            </th>
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
                                        <!-- <div class="col-xs-2" style="padding: 0;">
                                            <button type="button" class="btn btn-info btn-block" id="print_bill" style="height:57px; font-size: 20px;">
                                                <i class="fa fa-th" style="margin-right: 5px;"></i><?=lang('bill');?>
                                            </button>
                                        </div> -->
                                        <div class="col-xs-3" style="padding: 0;">
                                            <button type="button" class="btn btn-primary btn-block" id="quick_payment" style="height:57px; font-size: 20px;">
                                                <i class="fa fa-money" style="margin-right: 5px;"></i>TT nhanh (<strong><?=$pos_settings->quick_finalize_sale?></strong>)
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
                                
                                <?php //$this->sma->print_arrays($suspend_sale);?>
                                <input name="shipping" type="hidden" value="<?=$suspend_sale ? $suspend_sale->shipping : ($old_sale ? $old_sale->shipping :  '0');?>" id="posshipping">
                                <input name="discount" type="hidden" value="<?=$suspend_sale ? $suspend_sale->order_discount_id : ($old_sale ? $old_sale->order_discount_id : '');?>" id="posdiscount">
                                <input id="order_discount_percent_for_return_sale" name="order_discount_percent_for_return_sale" type="hidden" value="0">
                                <input name="return_amount" type="hidden" value="<?=$suspend_sale ? $suspend_sale->return_amount : ($old_sale ? $old_sale->return_amount :  '0');?>" id="posreturn">
                                <input type="hidden" name="rpaidby" id="rpaidby" value="cash" style="display: none;"/>
                                <input type="hidden" name="total_items" id="total_items" value="0" style="display: none;"/>
                                <input type="hidden" name="has_out_of_stock_products" id="has_out_of_stock_products" value="0" style="display: none;"/>
                                <input type="submit" id="submit_sale" value="Submit Sale" style="display: none;"/>
                                <span id="out_of_stock_items" style="display: none;"></span>
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
                                            <!-- <div class="btn-group">
                                                <button style="z-index:10003;" class="btn btn-primary pos-tip" type="button" id="sellGiftCard" title="<?=lang('sell_gift_card')?>">
                                                    <i class="fa fa-credit-card" id="addIcon"></i><?=lang('sell_gift_card')?>
                                                </button>
                                            </div> -->
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
<!--<div class="rotate btn-cat-con">
    <button type="button" id="open-brands" class="btn btn-info open-brands"><?= lang('brands'); ?></button>
    <button type="button" id="open-subcategory" class="btn btn-warning open-subcategory"><?= lang('subcategories'); ?></button>
    <button type="button" id="open-category" class="btn btn-primary open-category"><?= lang('categories'); ?></button>
</div>-->
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
                        
                        <div class="clearfir"></div>

                        <div id="payments">
                            <div class="well well-sm well_1">
                                <div class="payment">
                                    <div class="row pos-payment-main-row">
                                        <div class="col-sm-5 pos-payment-method-column">
                                            <div class="form-group">
                                                <?=lang("paying_by", "paid_by_1");?>
                                                <select name="paid_by[]" id="paid_by_1" class="form-control paid_by pos-native-select">
                                                    <?= $this->sma->paid_opts(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 pos-payment-amount-column">
                                            <div class="form-group">
                                                <?=lang("total_paying", "amount_1");?>
                                                <input name="amount[]" type="text" id="amount_1"
                                                       class="pa form-control kb-pad1 amount pos-touch-amount" autocomplete="off" value="0"/>
                                            </div>
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
                        <button type="button" class="btn btn-primary col-md-12 addButton" style="margin-bottom: 15px; height: 44px;"><i
                                class="fa fa-plus"></i> <?=lang('add_more_payments')?></button>
                        <div class="clearfir"></div>
                        <div class="form-group pos-payment-notes" style="margin-top: 8px;">
                            <div class="row">
                                <div class="col-sm-3 pos-delivery-area">
                                    <?=lang("delivery_method", "delivery_method");?>
                                    <select name="delivery" id="delivery" class="form-control delivery pos-native-select">
                                        <?= $this->sma->delivery_method_opts(); ?>
                                    </select>
                                </div>
                                <div class="col-sm-6 pos-invoice-note-area" style="padding-top: 15px;">
                                    <?=form_textarea('sale_note', '', 'id="sale_note" class="form-control kb-text skip" style="height: 85px; color: #006400;" placeholder="' . lang('sale_note') . '" maxlength="500"');?>
                                </div>                               
                            </div>

                        </div>
                        <div class="clearfir"></div>
                        <div class="font16">
                            <table class="table table-bordered table-condensed table-striped" style="margin-bottom: 10px; margin-top: 25px;">
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

                        <div class="btn-group btn-group-vertical quick-cash-group">
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

<!--FORM GHI CHÚ-->
<!-- <div class="modal" id="cmModal" tabindex="-1" role="dialog" aria-labelledby="cmModalLabel" aria-hidden="true">
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
                    <input type="text" style="text-transform: uppercase;" name="comment" class="form-control kb-text" id="icomment">
                </div>
                <div class="box_comment">
                    <?php 
                        foreach ($order_comment_list as $comment) {
                    ?>
                            <div class="comment_col">
                                <input class="chkComment" type="checkbox" id="<?=$comment->id?>" value="<?=$comment->comment?>">
                                <label class="lblComment" for="<?=$comment->id?>"><?=$comment->comment?></label>
                            </div>
                    <?php 
                            
                        } 
                    ?>
                        
                </div>
                <div class="form-group">
                    <?= lang('ordered', 'iordered'); ?>
                    <?php
                    $opts = array(0 => lang('no'), 1 => lang('yes'));
                    ?>
                    <?= form_dropdown('ordered', $opts, '', 'class="form-control" id="iordered" style="width:100%;"'); ?>
                </div>
                <input type="hidden" id="irow_id" value=""/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editComment"><?=lang('submit')?></button>
            </div>
        </div>
    </div>
</div> -->

<!--FORM EDIT, GIẢM GIÁ-->
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
                        <label for="poptions-div" class="col-sm-4 control-label"><?=lang('product_option')?></label>
                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?=lang('quantity')?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="pquantity">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label for="punit" class="col-sm-4 control-label"><?= lang('product_unit') ?></label>
                        <div class="col-sm-8">
                            <div id="punits-div"></div>
                        </div>
                    </div> -->
                    
                <div class="form-group box_notes" style="padding: 0 15px;">
                    <div class="col-sm-8">
                        <?= lang('comment', 'icomment'); ?>
                        <input type="text" style="text-transform: uppercase;" name="comment" class="form-control kb-text" id="icomment">
                    </div>
                    <div class="col-sm-4">
                    <?= lang('comment_name', 'icommentname'); ?>
                    <input type="text" style="text-transform: uppercase;" name="commentname" class="form-control kb-text" id="icommentname">
                    </div>
                    
                </div>
                
                <div class="box_comment">
                <!-- <div class="txtComment" contentEditable>&nbsp;</div>   -->
                
                    <?php 
                        foreach ($order_comment_list as $comment) {
                    ?>
                            <div class="comment_col">
                                <input class="chkComment" type="checkbox" id="<?=$comment->id?>" value="<?=$comment->comment?>">
                                <label class="lblComment" for="<?=$comment->id?>"><?=$comment->comment?></label>
                            </div>
                    <?php 
                            
                        } 
                    ?>
                        
                </div>
                <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?=lang('net_unit_price');?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                        </tr>
                    </table>
                <!--<div class="form-group">
                    <?= lang('ordered', 'iordered'); ?>
                    <?php
                    $opts = array(0 => lang('no'), 1 => lang('yes'));
                    ?>
                    <?= form_dropdown('ordered', $opts, '', 'class="form-control" id="iordered" style="width:100%;"'); ?>
                </div>-->
                <input type="hidden" id="irow_id" value=""/>
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
                    <?= form_input('reference_note', (!empty($reference_note) ? $reference_note : ''), 'class="form-control kb-text" placeholder="' . $this->lang->line("reference_note").'" style="text-transform: uppercase;" id="reference_note"'); ?>
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

<!-- MODAL SƠ ĐỒ BÀN -->
<div class="modal fade" id="tableModal" tabindex="-1" role="dialog" aria-labelledby="tableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header table-floor-header">

                <!-- BÊN TRÁI: NÚT THÊM + FORM -->
                <div class="table-floor-add-wrap">

                    <button type="button" class="btn btn-success" id="btnAddFloorTable">
                        <i class="fa fa-plus"></i> THÊM BÀN
                    </button>

                    <div id="addFloorTableForm" class="add-floor-table-form" style="display:none;">
                        <div class="add-floor-table-title">THÊM BÀN MỚI</div>

                        <input type="text"
                            id="newFloorTableName"
                            class="form-control"
                            maxlength="100"
                            placeholder="Nhập tên bàn...">

                        <div class="add-floor-table-actions">
                            <button type="button" class="btn btn-default btn-sm" id="btnCancelAddFloorTable">
                                Hủy
                            </button>
                            <button type="button" class="btn btn-success btn-sm" id="btnSaveAddFloorTable">
                                Lưu bàn
                            </button>
                        </div>
                    </div>

                </div>

                <!-- CHÍNH GIỮA -->
                <h4 class="modal-title" id="tableModalLabel">SƠ ĐỒ BÀN</h4>

                <!-- BÊN PHẢI -->
                <button type="button"
                        class="close table-floor-close"
                        data-dismiss="modal"
                        aria-label="Đóng">
                    &times;
                </button>

            </div>

            <div class="modal-body">

                <div id="floorTableNotice" class="floor-table-notice"></div>


                <div id="tableList" class="table-floor-canvas">
                    <div class="text-center">Đang tải sơ đồ bàn...</div>
                </div>

                <div class="table-floor-hint">
                    Kéo bàn để sắp xếp vị trí. Vị trí sẽ tự lưu sau khi thả.
                </div>

                <div id="hiddenTableList" class="table-hidden-list" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>
<!-- /MODAL SƠ ĐỒ BÀN -->

<!-- MODAL THAO TÁC BÀN ĐANG PHỤC VỤ -->
<div class="modal fade" id="busyTableModal"
     tabindex="-1" role="dialog"
     aria-labelledby="busyTableModalLabel"
     data-backdrop="false">

    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>

                <h4 class="modal-title" id="busyTableModalLabel">
                </h4>
            </div>

            <div class="modal-body text-center">

                <h4 id="busyTableName"
                    style="color:#2f80c9;font-weight:bold;margin-top:5px;">
                </h4>

                <p style="color:#777;">
                    Bàn này hiện đang có đơn hàng.
                </p>

                <button type="button"
                        class="btn btn-success btn-block"
                        id="btnOpenBusyTableOrder">
                    <i class="fa fa-folder-open"></i>
                    MỞ ĐƠN ĐANG PHỤC VỤ
                </button>

                <button type="button"
                        class="btn btn-default btn-block"
                        data-dismiss="modal">
                    ĐÓNG
                </button>

            </div>

        </div>
    </div>
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
var site = <?=json_encode(array('url' => base_url(), 'base_url' => admin_url(), 'assets' => $assets, 'settings' => $Settings, 'dateFormats' => $dateFormats))?>, pos_settings = <?=json_encode($pos_settings);?>;
var csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
var csrfHash = "<?= $this->security->get_csrf_hash(); ?>";
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
    var currentPaymentIndex = 1;

    function widthFunctions(e) {
        var wh = $(window).height(),
            lth = $('#left-top').height(),
            lbh = $('#left-bottom').height();
        $('#item-list').css("height", wh - 100);
        $('#item-list').css("min-height", 515);
        $('#left-middle').css("height", wh - lth - lbh - 112);
        $('#left-middle').css("min-height", 278);
        $('#product-list').css("height", wh - lth - lbh - 107);
        $('#product-list').css("min-height", 278);
    }
    $(window).bind("resize", widthFunctions);
    function openMini(e) {
        $("#leftdiv").addClass('mini_leftdiv');
        $("#cpinner").addClass('mini_cpinner');
        $("#botbuttons .btn").addClass('mini_buttons');
        $("#cp").show();
        $(".btn-cat-con").show();
        $("#col_return").hide();
    }
    function closeMini(e) {
        $("#leftdiv").removeClass('mini_leftdiv');
        $("#cpinner").removeClass('mini_cpinner');
        $("#botbuttons .btn").removeClass('mini_buttons');
        $("#cp").hide();
        $(".btn-cat-con").hide();
        $("#col_return").show();
    }

    
    function loadTableList() {

        $('#tableList').html(
            '<div class="text-center">Đang tải sơ đồ bàn...</div>'
        );

        $.ajax({
            type: 'GET',
            url: "<?=admin_url('pos/getTablesManage');?>",
            dataType: 'json',

            success: function (data) {

                if (!data || !data.success) {
                    $('#tableList').html(
                        '<div class="text-danger text-center">Không tải được sơ đồ bàn</div>'
                    );
                    return;
                }

                var html = '';

                $.each(data.tables, function (index, table) {

                    // Chỉ hiển thị bàn đang hoạt động
                    if (parseInt(table.is_active, 10) !== 1) {
                        return;
                    }

                    var busy = parseInt(table.is_busy, 10) === 1;
                    var tableName = $('<div>').text(table.table_name).html();

                    var statusText = busy
                        ? 'ĐANG PHỤC VỤ'
                        : 'TRỐNG';

                    var statusClass = busy
                        ? 'floor-table-busy'
                        : 'floor-table-free';

                    // Nếu chưa có tọa độ thì xếp bàn theo thứ tự
                    var x = table.position_x !== null
                        ? parseFloat(table.position_x)
                        : 2 + (index % 6) * 16;

                    var y = table.position_y !== null
                        ? parseFloat(table.position_y)
                        : 3 + Math.floor(index / 6) * 23;

                    html +=
                        '<button type="button" ' +
                        'class="table-select-btn floor-table-btn ' + statusClass + '" ' +
                        'data-id="' + table.id + '" ' +
                        'data-name="' + tableName + '" ' +
                        'data-busy="' + (busy ? 1 : 0) + '" ' +
                        'style="left:' + x + '%;top:' + y + '%;">' +
                        '<span class="floor-table-name">' + tableName + '</span>' +
                        '<small class="floor-table-status">' + statusText + '</small>' +
                        '</button>';
                });

                if (html === '') {
                    html = '<div class="text-center">Chưa có bàn nào đang hoạt động</div>';
                }

                $('#tableList').html(html);
            },

            error: function () {
                $('#tableList').html(
                    '<div class="text-danger text-center">Lỗi kết nối máy chủ</div>'
                );
            }
        });
    }


    
    /* =========================================
    FORM THÊM BÀN VÀ THÔNG BÁO FADE
    ========================================= */

    // Hiện thông báo nhẹ, tự biến mất
    function showFloorTableNotice(message, type) {

        var $notice = $('#floorTableNotice');

        $notice.stop(true, true)
            .removeClass('success error')
            .addClass(type)
            .text(message)
            .fadeIn(200);

        setTimeout(function () {
            $notice.fadeOut(500);
        }, 2200);
    }

    // Mở form thêm bàn
    $(document).on('click', '#btnAddFloorTable', function () {

        $('#addFloorTableForm').stop(true, true).fadeIn(180);
        $('#newFloorTableName').val('').focus();

    });

    // Đóng form
    $(document).on('click', '#btnCancelAddFloorTable', function () {

        $('#addFloorTableForm').fadeOut(150);
        $('#newFloorTableName').val('');

    });

    // Lưu bàn mới
    $(document).on('click', '#btnSaveAddFloorTable', function () {

        var tableName = $.trim($('#newFloorTableName').val());

        if (tableName === '') {
            showFloorTableNotice('Vui lòng nhập tên bàn.', 'error');
            $('#newFloorTableName').focus();
            return;
        }

        if (tableName.length > 100) {
            showFloorTableNotice('Tên bàn không được vượt quá 100 ký tự.', 'error');
            return;
        }

        var $saveButton = $(this);

        $saveButton.prop('disabled', true).text('Đang lưu...');

        var postData = {
            table_name: tableName
        };

        postData[csrfName] = csrfHash;

        $.ajax({
            type: 'POST',
            url: "<?=admin_url('pos/addTable');?>",
            data: postData,
            dataType: 'json',

            success: function (response) {

                if (response && response.csrf_hash) {
                    csrfHash = response.csrf_hash;
                }

                if (response && response.success) {

                    $('#addFloorTableForm').fadeOut(150);
                    $('#newFloorTableName').val('');

                    loadTableList();

                    showFloorTableNotice(
                        'Đã thêm bàn "' + response.table_name + '" thành công!',
                        'success'
                    );

                } else {

                    showFloorTableNotice(
                        response && response.message
                            ? response.message
                            : 'Không thể thêm bàn.',
                        'error'
                    );
                }
            },

            error: function (xhr) {

                console.log('ADD TABLE ERROR:', xhr.responseText);

                showFloorTableNotice(
                    'Lỗi kết nối, không thể thêm bàn.',
                    'error'
                );
            },

            complete: function () {
                $saveButton.prop('disabled', false).text('Lưu bàn');
            }
        });
    });

    // Nhấn Enter để lưu
    $(document).on('keydown', '#newFloorTableName', function (e) {

        if (e.key === 'Enter') {
            e.preventDefault();
            $('#btnSaveAddFloorTable').click();
        }

    });




    $(document).on('click', '.table-select-btn', function () {

        if (floorTableWasDragged) {
            return;
        }
       
        // Bấm bàn đang phục vụ -> mở modal thao tác
        if (parseInt($(this).attr('data-busy'), 10) === 1) {

            var tableId = $(this).data('id');
            var tableName = $(this).data('name');

            var btnRect = this.getBoundingClientRect();

            var $modal = $('#busyTableModal');
            var $dialog = $modal.find('.modal-dialog');

            $modal.data('table-id', tableId);
            $modal.data('table-name', tableName);

            $('#busyTableModalLabel').text(tableName);
            $('#busyTableName').text('BÀN ĐANG PHỤC VỤ');
            // Kích thước popup
            var popupWidth = 300;
            var popupHeight = 230;

            var screenWidth = window.innerWidth;
            var screenHeight = window.innerHeight;

            // Ưu tiên xuất hiện bên phải bàn
            var left = btnRect.right + 10;
            var top = btnRect.top;

            // Nếu không đủ chỗ bên phải thì chuyển qua trái
            if (left + popupWidth > screenWidth - 10) {
                left = btnRect.left - popupWidth - 10;
            }

            // Nếu không đủ chỗ bên trái thì đặt bên phải
            if (left < 10) {
                left = btnRect.right + 10;
            }

            // Nếu không đủ ngang thì nằm dưới nút
            if (left + popupWidth > screenWidth - 10) {
                left = screenWidth - popupWidth - 10;
            }

            // Nếu không đủ chiều cao thì đẩy lên
            if (top + popupHeight > screenHeight - 10) {
                top = screenHeight - popupHeight - 10;
            }

            if (top < 10) top = 10;

            // Định vị TRƯỚC khi mở modal
            $dialog.css({
                position: 'fixed',
                margin: 0,
                left: left + 'px',
                top: top + 'px',
                transform: 'none'
            });

            // Mở ngay tại vị trí đã tính
            $modal.modal('show');

            return;
        }


        var tableId = $(this).data('id');
        var tableName = $(this).data('name');

        // Loại đơn
        $('#order_type').val('TABLE');

        // ID bàn
        $('#table_id').val(tableId);

        // Lưu lại
        localStorage.setItem('order_type', 'TABLE');
        localStorage.setItem('table_id', tableId);

        $('#btn_ban').text(tableName);
        $('#btn_ban').addClass('active');
        $('#btn_mang_di').removeClass('active');

        // HIỆU ỨNG NHẬN BIẾT BÀN VỪA CHỌN
        $('#btn_ban')
            .removeClass('table-selected-effect');

        void $('#btn_ban')[0].offsetWidth;

        $('#btn_ban').addClass('table-selected-effect');

        localStorage.setItem('table_name', tableName);  

        // Đóng modal
        $('#tableModal').modal('hide');
    });


    
    /* =========================================
    KÉO THẢ BÀN - JAVASCRIPT THUẦN
    ========================================= */

    var floorDrag = null;
    var floorTableWasDragged = false;

    var floorCanvas = document.getElementById('tableList');

    // Bắt đầu kéo
    floorCanvas.addEventListener('pointerdown', function (e) {

        var table = e.target.closest('.floor-table-btn');

        if (!table) return;

        // Không kéo bàn đang phục vụ
        if (parseInt(table.getAttribute('data-busy'), 10) === 1) {
            return;
        }

        if (e.pointerType === 'mouse' && e.button !== 0) {
            return;
        }

        var canvasRect = floorCanvas.getBoundingClientRect();
        var tableRect = table.getBoundingClientRect();

        floorDrag = {
            element: table,
            pointerId: e.pointerId,
            startX: e.clientX,
            startY: e.clientY,
            offsetX: e.clientX - tableRect.left,
            offsetY: e.clientY - tableRect.top,
            moved: false
        };

        table.setPointerCapture(e.pointerId);

        e.preventDefault();
    });

    // Di chuyển bàn
    document.addEventListener('pointermove', function (e) {

        if (!floorDrag) return;
        if (e.pointerId !== floorDrag.pointerId) return;

        var dx = e.clientX - floorDrag.startX;
        var dy = e.clientY - floorDrag.startY;

        if (!floorDrag.moved && Math.abs(dx) + Math.abs(dy) < 5) {
            return;
        }

        floorDrag.moved = true;

        var canvas = document.getElementById('tableList');
        var table = floorDrag.element;
        var rect = canvas.getBoundingClientRect();

        var maxLeft = canvas.clientWidth - table.offsetWidth;
        var maxTop = canvas.clientHeight - table.offsetHeight;

        var left = e.clientX - rect.left - canvas.clientLeft
            + canvas.scrollLeft - floorDrag.offsetX;

        var top = e.clientY - rect.top - canvas.clientTop
            + canvas.scrollTop - floorDrag.offsetY;

        left = Math.max(0, Math.min(left, maxLeft));
        top = Math.max(0, Math.min(top, maxTop));

        table.style.left = left + 'px';
        table.style.top = top + 'px';

        e.preventDefault();
    });

    // Kết thúc kéo
    document.addEventListener('pointerup', function (e) {

        if (!floorDrag) return;
        if (e.pointerId !== floorDrag.pointerId) return;

        var drag = floorDrag;
        floorDrag = null;

        if (!drag.moved) return;

        floorTableWasDragged = true;

        var canvas = document.getElementById('tableList');
        var table = drag.element;

        var x = parseFloat(table.style.left) / canvas.clientWidth * 100;
        var y = parseFloat(table.style.top) / canvas.clientHeight * 100;

        var postData = {
            positions: JSON.stringify([{
                id: parseInt(table.getAttribute('data-id'), 10),
                x: x,
                y: y
            }])
        };

        postData[csrfName] = csrfHash;

        $.ajax({
            type: 'POST',
            url: "<?=admin_url('pos/saveTablePositions');?>",
            data: postData,
            dataType: 'json',

            success: function (data) {

                if (data && data.csrf_hash) {
                    csrfHash = data.csrf_hash;
                }

                if (data && data.success) {
                    console.log('Đã lưu vị trí bàn.');
                } else {
                    alert(data && data.message
                        ? data.message
                        : 'Không lưu được vị trí bàn.');

                    loadTableList();
                }
            },

            error: function () {
                alert('Lỗi kết nối, không lưu được vị trí bàn.');
                loadTableList();
            }
        });

        // Ngăn click chọn bàn ngay sau khi kéo
        setTimeout(function () {
            floorTableWasDragged = false;
        }, 300);
    });

    // Hủy thao tác nếu trình duyệt mất pointer
    document.addEventListener('pointercancel', function () {
        if (floorDrag) {
            floorDrag = null;
        }
    });



    $(document).ready(function () {

        $('#view-customer').click(function(){
            $('#myModal').modal({remote: site.base_url + 'customers/view/' + $("input[name=customer]").val()});
            $('#myModal').modal('show');
        });

        /* =====================================================
        MANG ĐI / BÀN
        ===================================================== */

        // Chọn MANG ĐI
        
        
        $('#btn_mang_di').off('click').on('click', function () {
            $('#btn_mang_di').addClass('active');
            $('#btn_ban').removeClass('active').text('BÀN');
            localStorage.removeItem('table_name');

            $('#order_type').val('TAKEAWAY');
            $('#table_id').val('');

            localStorage.setItem('order_type', 'TAKEAWAY');
            localStorage.removeItem('table_id');
            localStorage.removeItem('table_name');

            // Không thay đổi tên khách lẻ
        });




        // Chọn BÀN
        
        $('#btn_ban').off('click').on('click', function () {
            $('#btn_ban').addClass('active');
            $('#btn_mang_di').removeClass('active');

            // Hiện tên bàn đã chọn trước đó
            var savedTableName = localStorage.getItem('table_name');

            if (savedTableName) {
                $(this).text(savedTableName);
            } else {
                $(this).text('BÀN');
            }

            // Lưu trạng thái chọn bàn
            $('#order_type').val('TABLE');
            localStorage.setItem('order_type', 'TABLE');

            // Giữ bàn đã chọn trước đó nếu có
            var savedTableId = localStorage.getItem('table_id');

            if (savedTableId) {
                $('#table_id').val(savedTableId);
            } else {
                $('#table_id').val('');
            }

            $('#tableModal').modal('show');
            loadTableList();
        });


        /* =====================================================
        KHÔI PHỤC MANG ĐI / BÀN
        ===================================================== */

        
        var savedOrderType = localStorage.getItem('order_type');
        var savedTableId = localStorage.getItem('table_id');

        if (savedOrderType === 'TAKEAWAY') {
            $('#order_type').val('TAKEAWAY');
            $('#table_id').val('');

            $('#btn_mang_di').addClass('active');
            $('#btn_ban').removeClass('active').text('BÀN');

        } else if (savedOrderType === 'TABLE' && savedTableId) {
            $('#order_type').val('TABLE');
            $('#table_id').val(savedTableId);

            $('#btn_mang_di').removeClass('active');
            var savedTableName = localStorage.getItem('table_name');

            $('#btn_ban').addClass('active').text(
                savedTableName || 'BÀN'
            );

        } else {
            // Chưa có lựa chọn thì mặc định MANG ĐI
            $('#order_type').val('TAKEAWAY');
            $('#table_id').val('');

            localStorage.setItem('order_type', 'TAKEAWAY');
            localStorage.removeItem('table_id');

            $('#btn_mang_di').addClass('active');
            $('#btn_ban').removeClass('active').text('BÀN');
        }

        // Không gọi AJAX để đưa tên bàn vào #customer_name



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
        if (localStorage.getItem('customer_name')) {
            localStorage.removeItem('customer_name');
        }
        if (localStorage.getItem('order_type')) {
            localStorage.removeItem('order_type');
        }

        if (localStorage.getItem('table_id')) {
            localStorage.removeItem('table_id');
        }

        localStorage.removeItem('table_name');

        // Đưa nút về trạng thái ban đầu
        $('#btn_mang_di').addClass('active');
        $('#btn_ban').removeClass('active').text('BÀN');

        $('#order_type').val('TAKEAWAY');
        // BỔ SUNG: Đồng bộ lại loại đơn vào localStorage
        localStorage.setItem('order_type', 'TAKEAWAY');
        $('#table_id').val('');
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
        localStorage.setItem('customer_group_id', '<?=$customer->customer_group_id;?>');        
        localStorage.setItem('poswarehouse', '<?=$suspend_sale->warehouse_id;?>');
        localStorage.setItem('poscustomer', '<?=$suspend_sale->customer_id;?>');
        localStorage.setItem('customer_name', '<?=$suspend_sale->customer;?>');
        $('#customer_name').val(localStorage.getItem('customer_name'));
        
        var suspendedOrderType = <?= json_encode(
            !empty($suspend_sale->order_type)
                ? $suspend_sale->order_type
                : 'TAKEAWAY'
        ) ?>;

        var suspendedTableId = <?= json_encode(
            !empty($suspend_sale->table_id)
                ? (string) $suspend_sale->table_id
                : ''
        ) ?>;

        // Khôi phục loại đơn và bàn từ đơn tạm trong database
        $('#order_type').val(suspendedOrderType);
        $('#table_id').val(suspendedTableId);

        localStorage.setItem('order_type', suspendedOrderType);

        if (suspendedTableId) {
            localStorage.setItem('table_id', suspendedTableId);
        } else {
            localStorage.removeItem('table_id');
        }

        // Cập nhật giao diện theo đơn tạm vừa gọi lại
        
        if (suspendedOrderType === 'TABLE' && suspendedTableId) {

            // Khôi phục đúng loại đơn và mã bàn
            $('#order_type').val('TABLE');
            $('#table_id').val(suspendedTableId);

            $('#btn_mang_di').removeClass('active');
            $('#btn_ban').addClass('active').text('ĐANG TẢI...');

            // Lấy tên bàn theo ID từ database
            
            $.ajax({
                type: 'GET',
                url: "<?=admin_url('pos/getTables');?>",
                dataType: 'json',

                success: function (data) {
                    if (!data || !data.success || !data.tables) {
                        $('#btn_ban').text('BÀN');
                        console.error('Không tải được danh sách bàn.');
                        return;
                    }

                    // Tìm đúng bàn theo table_id của đơn tạm
                    var selectedTable = data.tables.find(function (table) {
                        return String(table.id) === String(suspendedTableId);
                    });

                    if (selectedTable) {
                        var tableName = selectedTable.table_name;

                        $('#btn_ban')
                            .addClass('active')
                            .text(tableName);

                        $('#btn_mang_di').removeClass('active');

                        localStorage.setItem('order_type', 'TABLE');
                        localStorage.setItem('table_id', suspendedTableId);
                        localStorage.setItem('table_name', tableName);
                    } else {
                        $('#btn_ban').text('BÀN');
                        console.error('Không tìm thấy bàn ID:', suspendedTableId);
                    }
                },

                error: function (xhr) {
                    $('#btn_ban').text('BÀN');
                    console.error('Lỗi tải danh sách bàn:', xhr.status);
                }
            });


        } else {

            $('#order_type').val('TAKEAWAY');
            $('#table_id').val('');

            $('#btn_mang_di').addClass('active');
            $('#btn_ban').removeClass('active').text('BÀN');

            localStorage.setItem('order_type', 'TAKEAWAY');
            localStorage.removeItem('table_id');
            localStorage.removeItem('table_name');
        }


        $()
        localStorage.setItem('posbiller', '<?=$suspend_sale->biller_id;?>');
        localStorage.setItem('posshipping', '<?=$suspend_sale->shipping;?>');
        localStorage.setItem('posreturn', '<?=$suspend_sale->return_amount;?>');
        <?php }
        ?>
        <?php if ($old_sale) {?>
        localStorage.setItem('postax2', '<?=$old_sale->order_tax_id;?>');
        localStorage.setItem('posdiscount', '<?=$old_sale->order_discount_id;?>');
        localStorage.setItem('customer_group_id', '<?=$old_sale->customer_group_id;?>');
        localStorage.setItem('poswarehouse', '<?=$old_sale->warehouse_id;?>');
        localStorage.setItem('poscustomer', '<?=$old_sale->customer_id;?>');
        localStorage.setItem('customer_name', '<?=$old_sale->customer;?>');
        localStorage.setItem('posbiller', '<?=$old_sale->biller_id;?>');
        localStorage.setItem('posshipping', '<?=$old_sale->shipping;?>');
        localStorage.setItem('posreturn', '<?=$old_sale->return_amount;?>');
        <?php }
        ?>
    <?php 
        if ($this->input->get('customer')) {?>
            if (!localStorage.getItem('positems')) {
                localStorage.setItem('poscustomer', <?=$this->input->get('customer');?>);
            } else if (!localStorage.getItem('poscustomer')) {
                localStorage.setItem('poscustomer', <?=$customer->id;?>);
            }
    <?php } else {?>
        if (!localStorage.getItem('poscustomer')) {
            localStorage.setItem('poscustomer', <?=$customer->id;?>);
        }
    <?php } ?>
    
        if (!localStorage.getItem('postax2')) {
            localStorage.setItem('postax2', <?=$Settings->default_tax_rate2;?>);
        }
        $('.select').select2({minimumResultsForSearch: 7});
        // var customers = [{
        //     id: <?=$customer->id;?>,
        //     text: '<?=$customer->company == '-' ? $customer->name : $customer->company;?>'
        // }];
        //$('#cusstomer_name').val(localStorage.getItem('customer_name'));
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

            $(document).keypress(function (event) {
                var keyPressed = event.keyCode;
                //var altPressed = event.altKey;
                if (keyPressed == 9/* key code for 's'*/) {
                    //console.log('ccxxx');
                    //$("#poscustomer").click();            
                }
            });

        }
        // qtthuan: 
        $(document).on('change', '#poscustomer', function () {
            //console.log(localStorage.getItem('poscustomer'));
            $.ajax({
                type: "get",
                async: false,
                url: "<?= admin_url('customers/getCustomerGroupByCustomerID') ?>/" + localStorage.getItem('poscustomer'),
                dataType: "json",
                success: function (cgdata) {
                    if(cgdata['id'] != 3 && cgdata['id'] != 4) {
                        $("#customer_group_id").val(cgdata['id']);
                        var percent = cgdata['customer_group_percent'];
                        if (is_valid_discount(percent)) {
                            var realPercent = (percent*-1) + '%';
                            $('#posdiscount').val(realPercent);
                            
                            if (percent != 0) {
                                //console.log(JSON.stringify(cgdata));
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
            currentPaymentIndex = <?=$i?>;
            calculateTotals();
            $('#amount_val_<?=$i?>').val($(this).val());
        });
        // $('#paymentModal').on('select2-close', '#paid_by_<?=$i?>', function (e) {
        //     console.log('xxxxxxx: ' + $(this).val());
        //     $('#paid_by_val_<?=$i?>').val($(this).val());
        // });
        $('#paymentModal').on('change', '#paid_by_<?=$i?>', function (e) {

            var value = $(this).val();

            $('#paid_by_val_<?=$i?>').val(value);

            console.log(
                'PAYMENT METHOD',
                <?=$i?>,
                value
            );

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
        $('#quick_payment').click(function () {
            $('#is_quick_finalize_sale').val(1);
            $("#payment").trigger('click')
        });
        $('#payment').click(function () {
            <?php if ($sid) {?>
            suspend = $('<span></span>');
            suspend.html('<input type="hidden" name="delete_id" value="<?php echo $sid; ?>" />');
            suspend.appendTo("#hidesuspend");
            <?php }
            ?>
            if ($('#has_out_of_stock_products').val() == 1) {
                out_of_stock_items = $('#out_of_stock_items').text();
                bootbox.alert('<?=lang('update_product_quantity');?>' + '');
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
            //bootbox.alert('vvv');
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
            if ($('#is_quick_finalize_sale').val() == 1) {
                //$('#quick-payable').click();
                $('#amount_1').blur();
                $('#submit-sale').click();
            }

            // Reset cột quick payable và phương thức thanh toán
            $('#clear-cash-notes').click();
            for (var i = 1; i <= 5; i++) {

                if ($("#paid_by_" + i).length) {

                    $("#paid_by_" + i)
                        .val("wait")
                        .trigger("change");

                    $("#amount_" + i).val("");
                }

            } // Reset cột quick payable và phương thức thanh toán

           if (!$('#customer').hasClass('select2-hidden-accessible')) {
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
            }
            console.log("shown paid_by_1 =", $("#paid_by_1").val());

            syncCustomerPaymentScreen();
            refreshPosTouchControls();

        });

        $('#paymentModal').on('hidden.bs.modal', function () {

        // console.log(lastCustomerScreenItems);
        // console.log(grand_total);
            updateCustomerScreen(
                lastCustomerScreenItems,
                grand_total
            );

        });

        function syncCustomerPaymentScreen()
        {
            var paidBy = $("#paid_by_1").val();

            switch (paidBy) {

                case "wait":
                case "cod":

                    updateCustomerPayment("payment_wait", {
                        total: grand_total
                    });

                    break;

                case "cash":

                    updateCustomerPayment("payment_cash", {
                        total: grand_total
                    });

                    break;

                case "cc":

                    updateCustomerPayment("payment_bank", {
                        total: grand_total,
                        bank_amount: grand_total
                    });

            break;
            }
        }


        function updateCustomerPayment(mode, data)
        {
            let postData = {

                payload: JSON.stringify({

                    mode: mode,

                    payment: data

                })

            };
            //console.log("SEND:", mode, data);

            postData[csrfName] = csrfHash;

            $.ajax({

                url: site.base_url + "pos/update_customer_screen",

                type: "POST",

                data: postData

            });
        }

        
        
        var pi = 'amount_1', pa = 2;

        window.posQuickCashUsed = false;

        $(document).on('click', '.quick-cash', function () {
            var $quick_cash = $(this);
            var isQuickPayable = $quick_cash.attr('id') === 'quick-payable';
            var $paymentSelect = $('#paid_by_1');

            // Chi xac nhan khi bam Quick Payable ma chua chon tien mat
            if (isQuickPayable &&
                !$quick_cash.data('cash-confirmed-once') &&
                $paymentSelect.val() === 'wait') {

                bootbox.confirm(
                    'Khách thanh toán tiền mặt đúng không?',
                    function (res) {
                        if (res === true) {
                            // Tu dong chon Tien mat va highlight nut
                            $paymentSelect.val('cash').trigger('change');

                            // Chay lai Quick Payable, khong hoi lan nua
                            $quick_cash
                                .data('cash-confirmed-once', true)
                                .trigger('click');
                        }
                    }
                );

                return false;
            }

            // Xoa co bo qua xac nhan sau lan click lap lai
            if ($quick_cash.data('cash-confirmed-once')) {
                $quick_cash.removeData('cash-confirmed-once');
            }

            // Da su dung Quick Cash trong hoa don
            window.posQuickCashUsed = true;

            // Dong bo lai nut neu dropdown da la Tien mat
            if (isQuickPayable && $paymentSelect.val() === 'cash') {
                renderPosTouchOptions($paymentSelect, 'payment');
            }

            // GIU NGUYEN LOGIC CONG TIEN CU
            var amt = $quick_cash.contents().filter(function () {
                return this.nodeType == 3;
            }).text();

            var th = '.';
            var $pi = $('#' + pi);

            amt = formatDecimal(amt.split(th).join("")) * 1
                + $pi.val() * 1;

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

            $('#' + pi).val('0');

            // Xoa trang thai da dung Quick Cash
            window.posQuickCashUsed = false;

            syncCustomerPaymentScreen();
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
                if ($('#paid_by_1').val() == 'cc') {
                    $("#paid_by_2 option[value='cc']").remove();
                    $("#paid_by_2 option[value='vnpay']").remove();
                } else if ($('#paid_by_1').val() == 'pos') {
                    $("#paid_by_2 option[value='pts']").remove();
                    $("#paid_by_2 option[value='pos']").remove();
                    $("#paid_by_2 option[value='vnpay']").remove();
                } else if ($('#paid_by_1').val() == 'vnpay') {
                    $("#paid_by_2 option[value='pts']").remove();
                    $("#paid_by_2 option[value='pos']").remove();
                    $("#paid_by_2 option[value='vnpay']").remove();
                    $("#paid_by_2 option[value='cc']").remove();
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
        $(document).on('click', '.change_lang', function () {
           if($(".product_name_vi").is(":visible")) {
                $(".product_name_vi").attr("style", "display: none");
                $(".product_name_en").removeAttr("style");
                $("#sale_language").val(1);
           } else  {
            $(".product_name_en").attr("style", "display: none");
            $(".product_name_vi").removeAttr("style");
            $("#sale_language").val(0);
           }
           loadItems();
        });
        $(document).on('focus', '.amount', function () {
            pi = $(this).attr('id');
            currentPaymentIndex = parseInt(pi.replace("amount_", ""));
            calculateTotals();
        }).on('blur', '.amount', function () {
            //alert('xxxxx');
            //calculateTotals();
            //syncCustomerPaymentScreen();
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
            
            <?php 
                } // END ELSE
            ?>

           // ======================================
            // Customer Screen
            // ======================================
            if ($("#paymentModal").is(":visible")) {

                var method = $("#paid_by_1").val();

                if (method == "cash") {

                    var paid = formatCNum($("#amount_1").val());

                    if (isNaN(paid)) {
                        paid = 0;
                    }

                    updateCustomerPayment(
                        "payment_cash",
                        {
                            total  : grand_total,
                            paid   : paid,
                            change : Math.max(0, paid - grand_total)
                        }
                    );

                }

            }
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

        //qtthuan: uppercase text when typing
            $('#customer_name').keyup(function(){
                $(this).val($(this).val().toUpperCase());
            });
            
            
            /* LỊCH SỬ TÊN KHÁCH LẺ */
            function loadCustomerNameHistory(keyword) {
                var names = [];

                try {
                    names = JSON.parse(
                        localStorage.getItem('customer_name_history') || '[]'
                    );
                } catch (e) {
                    names = [];
                }

                var key = $.trim(keyword || '').toUpperCase();
                var $box = $('#customer_name_suggestions');

                // Chưa gõ thì không hiện danh sách
                if (!key) {
                    $box.hide().empty();
                    return;
                }

                // Chỉ lấy tên có chứa ký tự đang gõ
                var matched = names.filter(function(name) {
                    return name.toUpperCase().indexOf(key) !== -1;
                }).slice(0, 10);

                $box.empty();

                if (matched.length === 0) {
                    $box.hide();
                    return;
                }

                matched.forEach(function(name) {
                    
                    $('<div>')
                        .addClass('customer-suggestion')
                        .text(name)
                        .on('mouseenter', function() {
                            $('#customer_name_suggestions .customer-suggestion')
                                .removeClass('active');

                            $(this).addClass('active');

                            selectedCustomerIndex = $(this).index();
                        })
                        .on('mousedown', function(e) {
                            e.preventDefault();

                            $('#customer_name').val(name);
                            $('#customer_name_suggestions').hide().empty();

                            selectedCustomerIndex = -1;
                        })
                        .appendTo($box);

                });

                $box.show();
            }

            // Giữ nguyên hàm lưu tên khách
            function saveCustomerNameHistory() {
                var name = $.trim($('#customer_name').val()).toUpperCase();

                if (!name || name === 'MANG ĐI' ||
                    $('#order_type').val() === 'TABLE') {
                    return;
                }

                var names = [];

                try {
                    names = JSON.parse(
                        localStorage.getItem('customer_name_history') || '[]'
                    );
                } catch (e) {
                    names = [];
                }

                names = names.filter(function(item) {
                    return item !== name;
                });

                names.unshift(name);
                names = names.slice(0, 100);

                localStorage.setItem(
                    'customer_name_history',
                    JSON.stringify(names)
                );
            }

            // Chỉ hiện gợi ý khi nhập ký tự
            
            var selectedCustomerIndex = -1;

            // Gõ chữ để tìm tên khách
            $('#customer_name')
                .off('.nameHistory')
                .on('input.nameHistory', function() {
                    selectedCustomerIndex = -1;
                    loadCustomerNameHistory($(this).val());
                })

                // Dùng phím lên, xuống và Enter
                .on('keydown.nameHistory', function(e) {
                    var $items = $('#customer_name_suggestions .customer-suggestion');

                    if (!$items.length ||
                        !$('#customer_name_suggestions').is(':visible')) {
                        return;
                    }

                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        selectedCustomerIndex =
                            Math.min(selectedCustomerIndex + 1, $items.length - 1);
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        selectedCustomerIndex =
                            Math.max(selectedCustomerIndex - 1, 0);
                    } else if (e.key === 'Enter' && selectedCustomerIndex >= 0) {
                        e.preventDefault();

                        $('#customer_name')
                            .val($items.eq(selectedCustomerIndex).text());

                        $('#customer_name_suggestions').hide().empty();
                        selectedCustomerIndex = -1;
                        return;
                    } else if (e.key === 'Escape') {
                        $('#customer_name_suggestions').hide();
                        selectedCustomerIndex = -1;
                        return;
                    } else {
                        return;
                    }

                    $items.removeClass('active');
                    $items.eq(selectedCustomerIndex).addClass('active');

                    // Giữ mục đang chọn trong vùng nhìn thấy
                    if (selectedCustomerIndex >= 0) {
                        $items[selectedCustomerIndex].scrollIntoView({
                            block: 'nearest'
                        });
                    }
                });


            // Bấm ra ngoài thì đóng danh sách
            $(document).on('mousedown.nameHistory', function(e) {
                if (!$(e.target).closest(
                    '#customer_name, #customer_name_suggestions'
                ).length) {
                    $('#customer_name_suggestions').hide();
                }
            });


            $('#mname').keyup(function(){
                $(this).val($(this).val().toUpperCase());
            });
        
        // qtthuan
        $(document).on('click', '.product', function (e) {
           
            $('#modal-loading').show();

            var clickEvent = e;

            code = $(this).val(),
            wh = $('#poswarehouse').val(),
            cu = $('#poscustomer').val();
            $.ajax({
                type: "get",
                url: "<?=admin_url('pos/getProductDataByCode')?>",
                data: {code: code, warehouse_id: wh, customer_id: cu},
                dataType: "json",
                success: function (data) {
                    e.preventDefault();
                    if (data !== null) {
                        //console.log(' view pos/add lineL 2038: ');
                        //console.log(JSON.stringify(data));
                        //console.log('is_mix=', data.row.is_mix);
                        if (parseInt(data.row.is_mix) > 0) {
                            var clickPos = {
                                x : e.pageX,
                                y : e.pageY
                            };
                            openMixModal(data, clickPos);

                        } else {

                            add_invoice_item(data);

                        }
                        //add_invoice_item(data);
                        $('#modal-loading').hide();
                    } else {
                        bootbox.alert('<?=lang('no_match_found')?>');
                        $('#modal-loading').hide();
                    }
                }
            });
           
        });

        function openMixModal(data, clickEvent)
        {
            var mixCount = parseInt(data.row.is_mix);
            var productName = (data.row.name || '').toUpperCase();

            productName = productName.replace(/^[A-Z]_/i, '');

            var flavors = [
                'CÀ RỐT',
                'CAM',
                'DƯA HẤU',
                'KHÓM',
                'ỔI',
                'TÁO'
            ];

            var html = `
                <style>
                    #mix-flavor-container{
                        display:grid;
                        grid-template-columns:repeat(3,1fr);
                        gap:10px;
                        margin-top:10px;
                    }

                    #mix-flavor-container .mix-item{
                        border:2px solid #d9e3f0;
                        border-radius:10px;
                        padding:12px;
                        text-align:center;
                        cursor:pointer;
                        font-size:16px;
                        font-weight:bold;
                        background:linear-gradient(#ffffff,#f7f9fc);
                        transition:all .15s;
                        color:#34495e;
                    }

                    #mix-flavor-container .mix-item:hover{
                        background:#eef7ff;
                        border-color:#5dade2;
                        transform:scale(1.03);
                    }

                    #mix-flavor-container .mix-item.active{
                        background:#3498db;
                        border-color:#2980b9;
                        color:#fff;
                        box-shadow:0 3px 8px rgba(0,0,0,.15);
                    }

                    #mix-flavor-container .mix-flavor{
                        width:28px;
                        height:28px;
                        margin-bottom:8px;
                    }
                </style>

                

                <div id="mix-flavor-container">
            `;

            $.each(flavors, function(i, flavor){

                html += `
                    <label class="mix-item">
                        <input
                            type="checkbox"
                            class="mix-flavor"
                            value="${flavor}"
                        >
                        <br>
                        ${flavor}
                    </label>
                `;
            });

            html += '</div>';

            var dialog = bootbox.dialog({
                title:  productName,
                message: html,
                size: 'small',
                buttons: {
                    cancel: {
                        label: 'Hủy',
                        className: 'btn-default'
                    },
                    ok: {
                        label: 'OK',
                        className: 'btn-primary',
                        callback: function(){

                            var selected = [];

                            $('.mix-flavor:checked').each(function(){
                                selected.push($(this).val());
                            });

                            if(selected.length != mixCount){

                                bootbox.alert(
                                    'Vui lòng chọn đúng ' +
                                    mixCount +
                                    ' vị'
                                );

                                return false;
                            }

                            var mixText = selected.join(' - ');

                            data.row.mix_flavor = mixText;

                            // thay tên sản phẩm luôn
                            if(data.row.name.indexOf('(') == -1){
                                data.row.name =
                                    data.row.name +
                                    ' (' + mixText + ')';
                            }

                            add_invoice_item(data);
                        }
                    }
                }
            });

            dialog.on('shown.bs.modal', function(){

            var footer = $(this).find('.modal-footer');

            footer.css({
                'text-align':'center'
            });

            footer.find('.btn-default').css({
                'font-size':'20px',
                'padding':'12px 30px',
                'min-width':'120px',
                'font-weight':'bold',
                'margin-right':'10px',
                'background':'#95a5a6',
                'color':'#fff',
                'border':'0'
            });

            footer.find('.btn-primary').css({
                'font-size':'20px',
                'padding':'12px 30px',
                'min-width':'120px',
                'font-weight':'bold',
                'background':'#3498db',
                'border':'0'
            });

        });

            // highlight ô được chọn
            // Khởi tạo check sẵn theo tên sản phẩm
            var selectedOrder = [];
            var initializing = true;

            var productText = (
                (data.row.name || '') + ' ' +
                (data.row.comment || '')
            ).toUpperCase();

            dialog.on('shown.bs.modal', function(){

                // Style button
                var footer = $(this).find('.modal-footer');

                footer.css({
                    'text-align':'center'
                });

                footer.find('.btn-default').css({
                    'font-size':'20px',
                    'padding':'12px 30px',
                    'min-width':'120px',
                    'font-weight':'bold',
                    'margin-right':'10px',
                    'background':'#95a5a6',
                    'color':'#fff',
                    'border':'0'
                });

                footer.find('.btn-primary').css({
                    'font-size':'20px',
                    'padding':'12px 30px',
                    'min-width':'120px',
                    'font-weight':'bold',
                    'background':'#3498db',
                    'border':'0'
                });

                // Check sẵn các vị có trong tên sản phẩm
                $.each(flavors, function(i, flavor){

                    if(productText.indexOf(flavor.toUpperCase()) >= 0){

                        $('.mix-flavor[value="' + flavor + '"]')
                            .prop('checked', true)
                            .closest('.mix-item')
                            .addClass('active');

                        selectedOrder.push(flavor);
                    }
                });

                initializing = false;
            });

            $(document)
            .off('change.mixflavor')
            .on('change.mixflavor', '.mix-flavor', function(){

            if(initializing){
                return;
            }

                var val = $(this).val();

                if(this.checked){

                    if($.inArray(val, selectedOrder) === -1){
                        selectedOrder.push(val);
                    }

                } else {

                    selectedOrder = $.grep(selectedOrder, function(v){
                        return v !== val;
                    });
                }

                $(this).closest('.mix-item')
                    .toggleClass('active', this.checked);

                // Không cho vượt quá số lượng
                if(selectedOrder.length > mixCount){

                    this.checked = false;

                    $(this).closest('.mix-item')
                        .removeClass('active');

                    selectedOrder = $.grep(selectedOrder, function(v){
                        return v !== val;
                    });

                    return;
                }

                // Đủ số lượng => tự động đóng
                if(selectedOrder.length === mixCount){

                    var mixText = selectedOrder.join(' - ');

                    data.row.mix_flavor = mixText;

                    data.row.name = mixText;

                    dialog.modal('hide');

                    setTimeout(function(){
                        add_invoice_item(data);
                    }, 100);
                }
            });

            // hiện gần vị trí click
            if(clickEvent){

                setTimeout(function(){

                    var modal = $('.bootbox.modal:last .modal-dialog');

                    modal.css({
                        position : 'absolute',
                        margin : 0,
                        top : clickEvent.y - 420,
                        left : clickEvent.x - 280
                    });

                },50);
            }
        }

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
           
            $('#clear-cash-notes').click();
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



                // Cho nhập tiền lại
                $("#amount_" + pa_no)
                    .prop("readonly", false)
                    .val(0);

                // Enable Quick Cash
                $(".quick-cash")
                    .removeClass("disabled");

                // Cập nhật màn hình phụ
                updateCustomerPayment(
                    "payment_cash",
                    {
                        total  : grand_total,
                        paid   : 0,
                        change : 0
                    }
                );



                $('#amount_' + pa_no).focus();

                $('#ca_points_' + pa_no).val(0);
                $('#details_pts_' + pa_no).html('');
                $('#paying_points_val_' + pa_no).val(0);
                $('#twt').text(formatMoney(gtotal));

            } else if (p_val == 'cc' || p_val == 'vnpay' || p_val == 'pos') {

                $('.pcheque_' + pa_no).hide();
                $('.pcash_' + pa_no).hide();
                $('.gc_' + pa_no).hide();
                $('.pts_' + pa_no).hide();
                //$('.pcc_' + pa_no).show();

                // Chuyen khoan: tu dong dien dung so tien can thanh toan
                $('#amount_' + pa_no)
                    .val(formatDecimal(gtotal))
                    .trigger('change');

                $('#swipe_' + pa_no).focus();

                $('#ca_points_' + pa_no).val(0);
                $('#details_pts_' + pa_no).html('');
                $('#paying_points_val_' + pa_no).val(0);
                $('#twt').text(formatMoney(gtotal));

                // Sau cùng mới tính
                calculateTotals();
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

            setTimeout(function () {
                syncCustomerPaymentScreen();
            }, 50);
        });
        
        /* =========================================
        GIAO DIỆN THANH TOÁN DÀNH CHO MÀN HÌNH TOUCH
        Giữ nguyên select và logic thanh toán cũ
        ========================================= */

        // CẤU HÌNH HIỆN NÚT ĐIỂM TÍCH LŨY
        // true  = hiện nút
        // false = ẩn nút
        var POS_SHOW_AWARD_POINTS = false;

        function renderPosTouchOptions($select, type) {
            if (!$select.length) {
                return;
            }

            var selectId = $select.attr('id');

            if (!selectId) {
                return;
            }

            var $wrap = $select.next('.pos-touch-options');

            if (
                !$wrap.length ||
                $wrap.attr('data-target') !== selectId
            ) {
                $wrap = $('<div>', {
                    'class': 'pos-touch-options ' +
                        (type === 'delivery'
                            ? 'pos-delivery-options'
                            : 'pos-payment-options')
                });

                $wrap.attr('data-target', selectId);
                $select.after($wrap);
            }

            $wrap.empty();

            $select.find('option').each(function () {
                var $option = $(this);
                var value = $option.val();
                var label = $.trim($option.text());
                var textKey = (label + ' ' + value).toLowerCase();

                if (value === undefined || label === '') {
                    return;
                }

                // Ẩn nút Điểm tích lũy nếu cấu hình là false
                if (
                    type === 'payment' &&
                    !POS_SHOW_AWARD_POINTS &&
                    (
                        textKey.includes('điểm tích lũy') ||
                        textKey.includes('pts') ||
                        textKey.includes('points')
                    )
                ) {
                    return;
                }

                var $button = $('<button>', {
                    type: 'button',
                    'class': 'pos-touch-choice'
                });

                
                $button.attr('data-value', value);

                // Xác định icon theo loại và tên phương thức
                var iconClass = 'fa-ellipsis-h';

                if (type === 'delivery') {
                    if (textKey.includes('shop') || textKey.includes('tại quầy')) {
                        iconClass = 'fa-home';
                    } else if (textKey.includes('giao hàng')) {
                        iconClass = 'fa-truck';
                    } else if (textKey.includes('shipper')) {
                        iconClass = 'fa-motorcycle';
                    }
                } else {
                    if (textKey.includes('chưa tt') || textKey.includes('wait')) {
                        iconClass = 'fa-clock-o';
                    } else if (textKey.includes('tiền mặt') || textKey.includes('cash')) {
                        iconClass = 'fa-money';
                    } else if (textKey.includes('điểm tích lũy') || textKey.includes('pts')) {
                        iconClass = 'fa-star';
                    } else if (textKey.includes('chuyển khoản') || textKey.includes('bank')) {
                        iconClass = 'fa-university';
                    } else if (textKey.includes('thẻ') || textKey.includes('card') || textKey.includes('cc')) {
                        iconClass = 'fa-credit-card';
                    }
                }

                // Tạo icon và chữ riêng để CSS căn giữa
                var $icon = $('<i>', {
                    'class': 'fa ' + iconClass + ' pos-choice-icon',
                    'aria-hidden': 'true'
                });

                var $text = $('<span>', {
                    'class': 'pos-choice-label',
                    text: label
                });

                $button.empty().append($icon, $text);


                if ($option.prop('disabled')) {
                    $button.prop('disabled', true);
                }

                if (
                    String($select.val()) === String(value)
                ) {
                    $button.addClass('active');
                }

                $wrap.append($button);
            });
        }

        function refreshPosTouchControls() {

            // Đồng bộ danh sách phương thức thanh toán.
            $('#paymentModal .paid_by').each(function () {
                renderPosTouchOptions($(this), 'payment');
            });

            // Đồng bộ hình thức nhận.
            var $delivery = $('#paymentModal #delivery');

            if ($delivery.length) {

                var savedDelivery =
                    localStorage.getItem('pos_delivery');

                // Nếu hệ thống đã lưu hình thức nhận,
                // giao diện phải phản ánh đúng lựa chọn đó.
                if (savedDelivery !== null) {
                    var $savedOption = $delivery.find('option')
                        .filter(function () {
                            return String(this.value) ===
                                String(savedDelivery);
                        });

                    if ($savedOption.length) {
                        $delivery.val(savedDelivery);
                    }
                }

                renderPosTouchOptions($delivery, 'delivery');
            }
        }

        // Bấm nút cảm ứng: cập nhật dropdown cũ,
        // sau đó để các event thanh toán hiện tại xử lý.
        $(document).on(
            'click',
            '#paymentModal .pos-touch-choice',
            function () {

                var $button = $(this);
                var $wrap = $button.closest('.pos-touch-options');
                var selectId = $wrap.attr('data-target');
                var value = $button.attr('data-value');

                var $select = $('#' + selectId);

                if (!$select.length) {
                    return;
                }

                
                $select.val(value);

                // Cập nhật màu nút trước khi đồng bộ dropdown.
                $wrap.find('.pos-touch-choice')
                    .removeClass('active');

                $button.addClass('active');

                // Lưu hình thức nhận.
                if (selectId === 'delivery') {
                    localStorage.setItem('pos_delivery', value);
                }

                // Kích hoạt logic thanh toán cũ.
                // Danh sách nút sẽ tự đồng bộ theo giá trị mới.
                $select.trigger('change');

            }
        );

        // Nếu dropdown được thay đổi bởi code cũ,
        // cập nhật lại trạng thái các nút.
        
        $(document).on(
            'change',
            '#paymentModal .paid_by, #paymentModal #delivery',
            function () {
                var $select = $(this);
                var selectId = this.id;

                if (selectId === 'delivery') {
                    localStorage.setItem(
                        'pos_delivery',
                        $select.val()
                    );
                }

                // Chỉ cập nhật nút đang chọn, không tạo lại toàn bộ nút.
                var $wrap = $select.next('.pos-touch-options');

                if (!$wrap.length) {
                    return;
                }

                var selectedValue = String($select.val());

                $wrap.find('.pos-touch-choice').each(function () {
                    var isActive =
                        String($(this).attr('data-value')) === selectedValue;

                    $(this).toggleClass('active', isActive);
                });
            }
        );


        // Khởi tạo sau khi modal mở và các option
        // điểm tích lũy đã được thêm/xóa theo khách hàng.
        // $('#paymentModal').on(
        //     'shown.bs.modal',
        //     function () {
        //         refreshPosTouchControls();
        //     }
        // );



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

        // ==========================================
        // XÁC ĐỊNH LOẠI ĐƠN TRƯỚC KHI POST
        // ƯU TIÊN: KHÁCH SELECT > TÊN NHẬP TAY > BÀN
        // ==========================================
        function checkOrderTypeBeforePost() {

            var customerId = String($('#poscustomer').val() || '');

            var customerName = $.trim(
                $('#customer_name').val() || ''
            );

            customerName = customerName.toLocaleUpperCase('vi-VN');

            // ƯU TIÊN 1: Khách hàng được chọn trong Select
            var hasSelectedCustomer =
                customerId !== '' && customerId !== '1';

            // ƯU TIÊN 2: Tên khách nhập thủ công
            var hasManualCustomerName =
                customerName !== '' &&
                customerName !== 'KHÁCH LẺ' &&
                customerName !== 'MANG ĐI';

            // Có khách Select hoặc tên nhập tay thì chuyển MANG ĐI
            if (hasSelectedCustomer || hasManualCustomerName) {

                $('#order_type').val('TAKEAWAY');
                $('#table_id').val('');

                $('#btn_mang_di').addClass('active');
                $('#btn_ban').removeClass('active').text('BÀN');

                localStorage.setItem('order_type', 'TAKEAWAY');
                localStorage.removeItem('table_id');
                localStorage.removeItem('table_name');

                return 'TAKEAWAY';
            }

            // Không có khách thì ưu tiên giữ bàn đã chọn
            var orderType = $('#order_type').val();
            var tableId = $('#table_id').val() ||
                        localStorage.getItem('table_id') || '';

            if (orderType === 'TABLE' && tableId !== '') {

                $('#order_type').val('TABLE');
                $('#table_id').val(tableId);

                localStorage.setItem('order_type', 'TABLE');
                localStorage.setItem('table_id', tableId);

                return 'TABLE';
            }

            // Không có khách và cũng không có bàn
            $('#order_type').val('TAKEAWAY');
            $('#table_id').val('');

            localStorage.setItem('order_type', 'TAKEAWAY');
            localStorage.removeItem('table_id');
            localStorage.removeItem('table_name');

            return 'TAKEAWAY';
        }

        $(document).on('click', '#submit-sale', function () {

            
            // Da bam menh gia Quick Cash nhung chua chon Tien mat
            if (
                window.posQuickCashUsed &&
                $('#paid_by_1').val() === 'wait'
            ) {
                bootbox.confirm(
                    'Khách thanh toán tiền mặt đúng không?',
                    function (res) {
                        
                        if (res === true) {

                            // 1. Luu lai tien da nhap truoc khi doi phuong thuc
                            var savedAmounts = [];

                            $('#payments input.amount').each(function () {
                                savedAmounts.push({
                                    id: this.id,
                                    value: $(this).val()
                                });
                            });

                            // 2. Tu dong chon Tien mat
                            // Event change cu co the reset amount ve 0
                            $('#paid_by_1')
                                .val('cash')
                                .trigger('change');

                            // 3. Khoi phuc lai toan bo so tien da nhap
                            
                            savedAmounts.forEach(function (item) {
                                $('#' + item.id)
                                    .val(item.value)
                                    .trigger('change');
                            });


                            // 4. Cap nhat lai tong tien, con lai va tien thua
                            calculateTotals();

                            // 5. Cap nhat mau nut Tien mat
                            refreshPosTouchControls();

                            // 6. Tiep tuc quy trinh thanh toan
                            $('#submit-sale').trigger('click');
                        }

                    }
                );

                return false;
            }

            if (total_paid == 0 || total_paid < grand_total - cash_used) {
                bootbox.confirm("<?=lang('paid_l_t_payable');?>", function (res) {
                    if (res == true) {
                        $('#pos_note').val(localStorage.getItem('posnote'));
                        $('#staff_note').val(localStorage.getItem('staffnote'));
                        $('#delivery_method').val(localStorage.getItem('pos_delivery'));
                        $('#submit-sale').text('<?=lang('loading');?>').attr('disabled', true);
                        checkOrderTypeBeforePost();
                        saveCustomerNameHistory();
                        $('#pos-sale-form').submit();
                    }
                });
                return false;
            } else {
                $('#pos_note').val(localStorage.getItem('posnote'));
                $('#staff_note').val(localStorage.getItem('staffnote'));
                $('#delivery_method').val(localStorage.getItem('pos_delivery'));
                $(this).text('<?=lang('loading');?>').attr('disabled', true);
                checkOrderTypeBeforePost();
                saveCustomerNameHistory();
                $('#pos-sale-form').submit();
            }
        });
        $('#suspend').off('click').on('click', function () {
            if (count <= 1) {
                bootbox.alert('<?=lang('x_suspend');?>');
                return false;
            }

            // Kiểm tra loại đơn trước khi tạo dữ liệu lưu tạm
            var orderType = checkOrderTypeBeforePost();

            // Lấy tên khách đã nhập
            var customerName = $.trim($('#customer_name').val() || '');

            // Lấy bàn sau khi đã kiểm tra ưu tiên khách hàng
            var tableId = $('#table_id').val() || '';
            var tableName = localStorage.getItem('table_name') || '';

            // Ưu tiên tên bàn trước tên khách
            var suspendNote = '';

            if (orderType === 'TABLE' && tableId !== '') {

                suspendNote = tableName || $.trim($('#btn_ban').text()) || 'BÀN';

            } else if (
                customerName !== '' &&
                customerName.toLocaleUpperCase('vi-VN') !== 'KHÁCH LẺ'
            ) {

                suspendNote = customerName;

            } else if (orderType === 'TAKEAWAY') {

                suspendNote = 'MANG ĐI';

            } else {

                suspendNote = 'KHÁCH LẺ';
            }

            suspendNote = suspendNote.toLocaleUpperCase('vi-VN');

            // Tạo các trường ẩn để gửi lên server
            $('#hidesuspend').empty();

            var $hiddenFields = $('<span></span>');

            <?php if ($sid) { ?>
            $('<input>', {
                type: 'hidden',
                name: 'delete_id',
                value: '<?= (int)$sid ?>'
            }).appendTo($hiddenFields);
            <?php } ?>

            $('<input>', {
                type: 'hidden',
                name: 'suspend',
                value: 'yes'
            }).appendTo($hiddenFields);

            $('<input>', {
                type: 'hidden',
                name: 'suspend_note',
                value: suspendNote
            }).appendTo($hiddenFields);

            // Gửi loại đơn và ID bàn
            $('#order_type').val(orderType);
            $('#table_id').val(tableId);

            $hiddenFields.appendTo('#hidesuspend');

            $('#total_items').val(count - 1);

            // Đồng bộ ghi chú và dữ liệu hiện tại
            $('#pos_note').val(localStorage.getItem('posnote') || '');
            $('#staff_note').val(localStorage.getItem('staffnote') || '');
            $('#delivery_method').val(localStorage.getItem('pos_delivery') || 'Shop');

            saveCustomerNameHistory();

            // Hiện thông báo rồi tự động lưu
            $('#suspendSuccessNote').text(suspendNote);

            $('#suspendSuccessToast')
                .stop(true, true)
                .fadeIn(200)
                .delay(950)
                .fadeOut(250, function () {
                    $('#pos-sale-form').submit();
                });

            // Tránh bấm liên tục khi đang xử lý
            $('#suspend').prop('disabled', true);

            return false;
        });
    });
 
    $(document).ready(function () {

        function updateCustomerTypeButtons() {
            var warehouseId = parseInt($('#poswarehouse').val(), 10);
            var miniWarehouseId = parseInt(window.APP_CONFIG.MINI_WAREHOUSE_ID, 10);

            if (warehouseId === miniWarehouseId) {
                $('.customer-type-buttons').show();
            } else {
                $('.customer-type-buttons').hide();

                // Xóa loại đơn và bàn cũ để không lưu nhầm đơn Bani
                $('#order_type').val('');
                $('#table_id').val('');

                localStorage.removeItem('order_type');
                localStorage.removeItem('table_id');
                localStorage.removeItem('table_name');
            }
        }

        $('#poswarehouse').change(function () {
            var warehouseId = parseInt($(this).val(), 10);

            updateCustomerTypeButtons();

            if (warehouseId !== parseInt(window.APP_CONFIG.MINI_WAREHOUSE_ID, 10)) {
                closeMini();
            } else {
                openMini();
            }
        });

        // Cập nhật ngay khi mở POS
        updateCustomerTypeButtons();

        $("#poscustomer").change();

        if (parseInt(localStorage.getItem('poswarehouse'), 10) !==
            parseInt(window.APP_CONFIG.MINI_WAREHOUSE_ID, 10)) {
            closeMini();
        } else {
            openMini();
        }

        if ($("#poswarehouse").is(":hidden") &&
            parseInt($("#poswarehouse").val(), 10) ===
            parseInt(window.APP_CONFIG.MINI_WAREHOUSE_ID, 10)) {
            openMini();
        }

        // Giữ nguyên các đoạn xử lý khác bên dưới tại đây
        $("#poscustomer").change();
        if (localStorage.getItem('poswarehouse') != 3) {
            closeMini();
            //console.log('4444');
        } else {
            openMini();
            //console.log('3333');
            //$('.btn-cat-con').toggle('slide', { direction: 'right' }, 100);
        }

        if ($("#poswarehouse").is(":hidden") && $("#poswarehouse").val() == 3) {
            //console.log('open nhe 1');
            openMini();
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
                    $('#editComment').click();
                }
            });

            //qtthuan
            $('.chkComment').removeAttr('checked');
            if ($('#poswarehouse').val() == window.APP_CONFIG.MINI_WAREHOUSE_ID) {
                $('.box_comment').show();
                $('.box_notes').show();
                var selected_comments=[];
                $(".chkComment").change(function() {
                    
                    var ischecked= $(this).is(':checked');
                    if (ischecked) {
                        selected_comments.push($(this).val());
                        
                    } else {
                        for (var i=selected_comments.length-1; i>=0; i--) {
                            if (selected_comments[i] === $(this).val()) 
                            selected_comments.splice(i, 1);
                        }
                    }
                    $('#icomment').val(selected_comments.join(', ')).focus();

                }); 
            } else {
                $('.box_comment').hide();
                $('.box_notes').hide();
            }
            
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
<script>
    window.APP_CONFIG = {
        MINI_WAREHOUSE_ID: <?= (int)$this->config->item('mini_warehouse_id'); ?>,
        BANI_WAREHOUSE_ID: <?= (int)$this->config->item('bani_warehouse_id'); ?>
    };
</script>
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

<!-- =========================================================
     MODAL DANH SÁCH ĐƠN HÀNG
========================================================= -->

<div
    class="modal fade"
    id="orderListModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                >
                    <span>&times;</span>
                </button>

                <h4 class="modal-title text-center">
                    <i class="fa fa-list-alt"></i>
                    ĐƠN HÀNG TRONG NGÀY
                </h4>

            </div>


            <!-- BODY -->
            <div class="modal-body">

                <!-- VÙNG SWIPE -->
                <div id="orderListSwipeArea">

                    <div id="orderListContainer">

                        <div class="text-center">
                            Đang tải đơn hàng...
                        </div>

                    </div>

                </div>


                <!-- PHÂN TRANG -->
                <div class="order-list-footer">

                    <button
                        type="button"
                        id="orderPrevPage"
                        class="order-page-arrow"
                    >
                        <i class="fa fa-chevron-left"></i>
                    </button>


                    <span
                        id="orderPageInfo"
                        class="order-page-info"
                    >
                        1 / 1
                    </span>


                    <button
                        type="button"
                        id="orderNextPage"
                        class="order-page-arrow"
                    >
                        <i class="fa fa-chevron-right"></i>
                    </button>

                </div>


                <div class="order-swipe-hint">
                    Vuốt trái / phải để chuyển trang
                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     MODAL GÁN / ĐỔI BÀN
========================================================= -->

<div
    class="modal fade"
    id="orderTableModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                >
                    <span>&times;</span>
                </button>

                <h4 class="modal-title text-center">
                    GÁN / ĐỔI BÀN
                </h4>

            </div>


            <div class="modal-body">

                <input
                    type="hidden"
                    id="change_table_sale_id"
                    value=""
                >


                <div id="orderTableList">

                    <div class="text-center">
                        Đang tải danh sách bàn...
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =====================================================
     MODAL SỔ NHẬP HÀNG
===================================================== -->

<div class="modal fade" id="posPurchaseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="width:95%;max-width:1100px;">
        <div class="modal-content">

            <div class="modal-header" style="background:#f7f9fc;">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title text-center" style="font-weight:bold;">
                    <i class="fa fa-truck"></i> SỔ NHẬP HÀNG
                </h4>
            </div>

            <div class="modal-body">

                <ul class="nav nav-tabs" role="tablist">
                    <li class="active">
                        <a href="#posPurchaseEntryTab" data-toggle="tab">
                            <i class="fa fa-plus-circle"></i> NHẬP HÀNG
                        </a>
                    </li>
                    <li>
                        <a href="#posPurchaseListTab" data-toggle="tab">
                            <i class="fa fa-list"></i> DANH SÁCH NHẬP HÀNG
                        </a>
                    </li>
                </ul>

                <div class="tab-content" style="padding-top:20px;">

                    <!-- TAB NHẬP HÀNG -->
                    <div class="tab-pane fade in active" id="posPurchaseEntryTab">

                        <form id="posPurchaseForm" enctype="multipart/form-data">

                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Ngày nhập</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            id="purchase_date"
                                            name="purchase_date"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label>Kho</label>
                                        
                                        <select
                                            class="form-control"
                                            id="purchase_warehouse"
                                            name="warehouse_id"
                                        >
                                            <?php
                                            $current_warehouse_id = (int) $this->session->userdata('warehouse_id');

                                            if ($Owner || $Admin || !$current_warehouse_id) {
                                                foreach ($warehouses as $warehouse) {
                                                    $selected = (
                                                        (int) $warehouse->id ===
                                                        (int) $current_warehouse_id
                                                    ) ? 'selected' : '';

                                                    echo '<option value="' . (int) $warehouse->id . '" '
                                                        . $selected . '>'
                                                        . htmlspecialchars($warehouse->name, ENT_QUOTES, 'UTF-8')
                                                        . '</option>';
                                                }
                                            } else {
                                                $current_warehouse = $this->site->getWarehouseByID($current_warehouse_id);

                                                if ($current_warehouse) {
                                                    echo '<option value="' . (int) $current_warehouse->id . '" selected>'
                                                        . htmlspecialchars($current_warehouse->name, ENT_QUOTES, 'UTF-8')
                                                        . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Tên hàng hóa / Nội dung nhập</label>
                                        
                                        <div style="position:relative;">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="purchase_item_name"
                                                name="item_name"
                                                placeholder="Nhập tên hàng..."
                                                autocomplete="off"
                                                required
                                            >

                                            <div
                                                id="purchase_item_suggestions"
                                                style="
                                                    display:none;
                                                    position:absolute;
                                                    top:100%;
                                                    left:0;
                                                    width:100%;
                                                    z-index:12000;
                                                    background:#fff;
                                                    border:1px solid #3682c4;
                                                    border-radius:4px;
                                                    max-height:220px;
                                                    overflow-y:auto;
                                                    box-shadow:0 4px 10px rgba(0,0,0,.15);
                                                "
                                            ></div>
                                        </div>

                                    </div>
                                </div>

                                
                                <div class="row">

                                    <!-- SỐ TIỀN -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="purchase_amount">
                                                <i class="fa fa-money text-success"></i>
                                                Số tiền nhập hàng
                                            </label>

                                            <div class="input-group">
                                                <input
                                                    type="number"
                                                    min="1"
                                                    step="1"
                                                    class="form-control input-lg"
                                                    id="purchase_amount"
                                                    name="amount"
                                                    placeholder="Nhập số tiền..."
                                                    required
                                                >
                                                <span class="input-group-addon">VNĐ</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FILE ĐÍNH KÈM -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="purchase_attachment">
                                                <i class="fa fa-paperclip text-primary"></i>
                                                File đính kèm
                                            </label>

                                            <input
                                                type="file"
                                                class="form-control"
                                                id="purchase_attachment"
                                                name="attachment"
                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx"
                                            >

                                            <p class="help-block">
                                                Có thể đính kèm hình ảnh, PDF hoặc Excel.
                                            </p>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <div class="text-right" style="margin-top:15px;">
                                <button type="reset" class="btn btn-default" id="btn_reset_pos_purchase">
                                    <i class="fa fa-refresh"></i> RESET FORM
                                </button>

                                <button type="submit" class="btn btn-success" id="btn_save_pos_purchase">
                                    <i class="fa fa-save"></i> NHẬP HÀNG
                                </button>
                            </div>

                        </form>
                    </div>


                    <!-- TAB DANH SÁCH -->
                    <div class="tab-pane fade" id="posPurchaseListTab">

                        <div class="row" style="margin-bottom:15px;">

                            <div class="col-sm-3">
                                <label>Từ ngày</label>
                                <input type="date" id="purchase_filter_from" class="form-control">
                            </div>

                            <div class="col-sm-3">
                                <label>Đến ngày</label>
                                <input type="date" id="purchase_filter_to" class="form-control">
                            </div>

                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <button type="button" id="btn_filter_pos_purchase" class="btn btn-primary btn-block">
                                    <i class="fa fa-search"></i> TÌM KIẾM
                                </button>
                            </div>

                        </div>

                        <div class="row" style="margin-bottom:12px;">
                            <div class="col-sm-12 text-right">
                                <div class="dropdown" style="display:inline-block;">
                                    <button
                                        type="button"
                                        class="btn btn-success dropdown-toggle"
                                        data-toggle="dropdown"
                                    >
                                        <i class="fa fa-file-excel-o"></i> XUẤT EXCEL
                                        <span class="caret"></span>
                                    </button>

                                    <div
                                        class="dropdown-menu dropdown-menu-right"
                                        style="padding:15px;width:280px;left:auto;right:0;"
                                        id="purchaseExportBox"
                                    >
                                        <div class="form-group">
                                            <label>Kho hàng</label>
                                            
                                            <select id="purchase_export_warehouse" class="form-control">
                                                <option value="0">Tất cả kho hàng</option>

                                                <?php
                                                if ($Owner || $Admin || !$current_warehouse_id) {
                                                    foreach ($warehouses as $warehouse) {
                                                        echo '<option value="' . (int) $warehouse->id . '">'
                                                            . htmlspecialchars($warehouse->name, ENT_QUOTES, 'UTF-8')
                                                            . '</option>';
                                                    }
                                                } else {
                                                    if ($current_warehouse) {
                                                        echo '<option value="' . (int) $current_warehouse->id . '">'
                                                            . htmlspecialchars($current_warehouse->name, ENT_QUOTES, 'UTF-8')
                                                            . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>

                                        </div>

                                        <div class="form-group">
                                            <label>Kỳ báo cáo</label>
                                            <select id="purchase_export_quarter" class="form-control">
                                                <option value="1">Quý 1 (1-3)</option>
                                                <option value="2">Quý 2 (4-6)</option>
                                                <option value="3">Quý 3 (7-9)</option>
                                                <option value="4">Quý 4 (10-12)</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Năm</label>
                                            <select id="purchase_export_year" class="form-control"></select>
                                        </div>

                                        <button
                                            type="button"
                                            id="btn_export_pos_purchase"
                                            class="btn btn-primary btn-block"
                                        >
                                            <i class="fa fa-download"></i> XUẤT EXCEL
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="posPurchaseListContainer">
                            <div class="text-center text-muted">Chưa tải danh sách.</div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>



<script type="text/javascript">

/* =========================================================
   ĐƠN HÀNG TRONG NGÀY
   15 đơn / trang
   5 x 3
========================================================= */

var currentOrderListPage = 1;

var totalOrderListPages = 1;

var orderSwipeStartX = 0;

var orderSwipeStartY = 0;


/* =========================================================
   MỞ DANH SÁCH ĐƠN
========================================================= */

/* =========================================================
   BADGE SỐ LƯỢNG ĐƠN HÀNG / ĐƠN TẠM
========================================================= */

function refreshPosHeaderCounts() {

    $.ajax({
        type: 'GET',

        url: "<?=admin_url('pos/getPosHeaderCounts');?>",

        data: {
            warehouse_id: $('#poswarehouse').val() || ''
        },

        dataType: 'json',

        cache: false,

        success: function (data) {

            if (!data || !data.status) {
                return;
            }

            var suspendedCount =
                parseInt(data.suspended_orders, 10) || 0;

            $('#suspended_order_count')
                .text(suspendedCount)
                .toggle(suspendedCount > 0);

        }
    });

}


$(document).ready(function () {

    // Cập nhật ngay khi mở POS
    refreshPosHeaderCounts();

    // Cập nhật định kỳ 30 giây
    window.posHeaderCountTimer = window.setInterval(
        refreshPosHeaderCounts,
        30000
    );

    // Đổi kho thì cập nhật lại
    $(document).on(
        'change',
        '#poswarehouse',
        refreshPosHeaderCounts
    );

    // Bấm Đơn tạm thì cập nhật lại
    $(document).on(
        'click',
        '#opened_bills',
        refreshPosHeaderCounts
    );

});

$(document).on('click', '#btn_order_list', function (e) {

    e.preventDefault();

    currentOrderListPage = 1;

    $('#orderListModal').modal('show');

    loadTodayOrders(currentOrderListPage);

});


/* =========================================================
   LOAD ĐƠN HÀNG
========================================================= */

var orderListLoading = false;


function loadTodayOrders(
    page,
    direction
) {

    if (orderListLoading) {
        return;
    }


    orderListLoading = true;


    var $container =
        $('#orderListContainer');


    var hasContent =
        $container.children().length > 0;


    /*
     * Animation OUT
     */
    if (
        direction &&
        hasContent
    ) {

        $container
            .removeClass(
                'order-page-out-next ' +
                'order-page-out-prev ' +
                'order-page-in-next ' +
                'order-page-in-prev'
            )
            .addClass(
                direction === 'next'
                    ? 'order-page-out-next'
                    : 'order-page-out-prev'
            );

    }


    function requestOrders() {

        $.ajax({

            type: 'GET',

            url:
                "<?=admin_url('pos/getTodayOrders');?>",

            data: {

                page: page,
                warehouse_id: $('#poswarehouse').val() || ''

            },

            dataType: 'json',


            success: function (data) {

                if (
                    !data ||
                    !data.status
                ) {

                    $container.html(

                        '<div ' +
                        'class="text-danger text-center" ' +
                        'style="padding:40px 0;">' +

                            'Không lấy được danh sách đơn hàng.' +

                        '</div>'

                    );

                    orderListLoading =
                        false;

                    return;
                }


                currentOrderListPage =
                    parseInt(
                        data.page,
                        10
                    ) || 1;


                totalOrderListPages =
                    parseInt(
                        data.total_pages,
                        10
                    ) || 1;


                renderTodayOrders(
                    data.orders || []
                );


                updateOrderPagination();


                /*
                 * Animation IN
                 */
                if (direction) {

                    var enterClass =
                        direction === 'next'
                            ? 'order-page-in-next'
                            : 'order-page-in-prev';


                    $container
                        .removeClass(
                            'order-page-out-next ' +
                            'order-page-out-prev'
                        )
                        .addClass(
                            enterClass
                        );


                    /*
                     * Ép browser nhận trạng thái
                     * trước khi chạy transition.
                     */
                    void $container[0].offsetWidth;


                    requestAnimationFrame(
                        function () {

                            $container
                                .removeClass(
                                    enterClass
                                );

                        }
                    );

                }


                orderListLoading =
                    false;

            },


            error: function (
                xhr,
                status,
                error
            ) {

                console.log(
                    'AJAX ERROR'
                );

                console.log(
                    'status:',
                    xhr.status
                );

                console.log(
                    'statusText:',
                    status
                );

                console.log(
                    'error:',
                    error
                );

                console.log(
                    'response:',
                    xhr.responseText
                );


                $container
                    .removeClass(
                        'order-page-out-next ' +
                        'order-page-out-prev ' +
                        'order-page-in-next ' +
                        'order-page-in-prev'
                    );


                $container.html(

                    '<div ' +
                    'class="text-center text-danger" ' +
                    'style="padding:30px;">' +

                        '<b>Lỗi máy chủ</b><br>' +

                        'HTTP: ' +
                        xhr.status +

                        '<br>' +

                        '<pre ' +
                        'style="text-align:left; ' +
                        'white-space:pre-wrap; ' +
                        'margin-top:15px;">' +

                            $('<div>')
                                .text(
                                    xhr.responseText
                                )
                                .html() +

                        '</pre>' +

                    '</div>'

                );


                orderListLoading =
                    false;

            }

        });

    }


    /*
     * Cho card cũ chạy ra trước,
     * rồi mới lấy trang mới.
     */
    if (
        direction &&
        hasContent
    ) {

        setTimeout(
            requestOrders,
            150
        );

    }

    else {

        requestOrders();

    }

}


/* =========================================================
   RENDER 16 ĐƠN
========================================================= */
var BANI_WAREHOUSE_ID =
    <?= (int) $this->config->item('bani_warehouse_id') ?>;
function renderTodayOrders(orders) {

    var html = '';

    html += '<div class="order-list-grid">';


    if (!orders.length) {

        html +=
            '<div style="grid-column:1/-1;" ' +
            'class="text-center text-muted">' +
            'Hôm nay chưa có đơn hàng.' +
            '</div>';

    }


    $.each(orders, function (index, order) {

        var saleId = parseInt(order.id, 10);

        var customer =
            order.customer ?
            order.customer :
            'KHÁCH';


        var total =
            parseFloat(order.grand_total || 0);


        var paymentStatus =
            (order.payment_status || '').toLowerCase();


        var paidBy =
            (order.paid_by || '').toLowerCase();


        html +=
            '<div class="order-card" ' +
            'data-sale-id="' + saleId + '">';

        /* ICON XEM NHANH MÓN */

        html +=
            '<button ' +
            'type="button" ' +
            'class="order-items-preview" ' +
            'data-sale-id="' + saleId + '" ' +
            'title="Xem món trong đơn">' +

                '<i class="fa fa-eye"></i>' +

            '</button>';


        /* KHÁCH / BÀN */

        html +=
            '<div ' +
            'class="order-customer order-customer-' + saleId + '" ' +
            'title="' + escapeOrderHtml(customer) + '">' +

                escapeOrderHtml(customer) +

            '</div>';

        /* NGÀY + MÃ HÓA ĐƠN */

        var orderDate = order.date
            ? order.date.substring(11, 16)
            : '';

        var referenceNo = order.reference_no
            ? order.reference_no
            : '';

        html +=
            '<div class="order-meta">' +

                '<span class="order-date">' +
                    escapeOrderHtml(orderDate) +
                '</span>' +

                '<span class="order-reference">' +
                    escapeOrderHtml(referenceNo) +
                '</span>' +

            '</div>';


        /* TỔNG TIỀN */

        html +=
            '<div class="order-total">' +
                formatOrderMoney(total) +
            '</div>';


        /* THANH TOÁN */

        html +=
            '<div class="order-payment-wrap">';


        if (paymentStatus === 'due') {

            html +=

                '<button ' +
                'type="button" ' +
                'class="order-payment-status order-payment-due" ' +
                'data-sale-id="' + saleId + '">' +

                    'Chưa TT' +

                '</button>';


            html +=

                '<div ' +
                'class="order-payment-menu" ' +
                'data-sale-id="' + saleId + '">' +


                    '<button ' +
                    'type="button" ' +
                    'class="order-pay-btn" ' +
                    'data-sale-id="' + saleId + '" ' +
                    'data-paid-by="cash">' +

                        '<i class="fa fa-money"></i> Tiền mặt' +

                    '</button>' +


                    '<button ' +
                    'type="button" ' +
                    'class="order-pay-btn" ' +
                    'data-sale-id="' + saleId + '" ' +
                    'data-paid-by="cc">' +

                        '<i class="fa fa-credit-card"></i> Chuyển khoản' +

                    '</button>' +


                '</div>';

        }


        else {

            var paidText = 'Đã TT';


            if (paidBy === 'cash') {

                paidText = 'Tiền mặt';

            }

            else if (paidBy === 'cc') {

                paidText = 'Chuyển khoản';

            }

            else if (paidBy === 'cod') {

                paidText = 'COD';

            }


            html +=

                '<span ' +
                'class="order-payment-status order-payment-paid">' +

                    escapeOrderHtml(paidText) +

                '</span>';

        }


        html += '</div>';


        /* GÁN / ĐỔI BÀN */

html +=

    '<div class="order-card-actions">';

        
        /* GÁN / ĐỔI BÀN: chỉ hiện ở kho không phải Ba-Ni */

        if (parseInt(order.warehouse_id, 10) !== BANI_WAREHOUSE_ID) {
            html +=
                '<button ' +
                    'type="button" ' +
                    'class="order-table-button change-order-table" ' +
                    'data-sale-id="' + saleId + '">' +
                    '<i class="fa fa-cutlery"></i> ' +
                    'GÁN / ĐỔI BÀN' +
                '</button>';
        }
 
html +=

            '<a ' +
            'class="order-print-button" ' +
            'href="<?=admin_url('pos/view/'); ?>' +
                saleId +
                '?print=' +
                saleId +
            '" ' +
            'target="_blank">' +

                '<i class="fa fa-print"></i> ' +
                'In Bill' +

            '</a>' +

        '</div>';


        html += '</div>';

    });


    html += '</div>';


    $('#orderListContainer').html(html);

}

/* =========================================================
   XEM NHANH MÓN TRONG ĐƠN
========================================================= */

var orderItemsCache = {};

var orderItemsTimer = null;


function loadOrderItemsPreview(
    saleId,
    $button
) {

    if (orderItemsCache[saleId]) {

        showOrderItemsPreview(
            saleId,
            $button,
            orderItemsCache[saleId]
        );

        return;
    }


    showOrderItemsPreview(
        saleId,
        $button,
        null,
        true
    );


    $.ajax({

        type: 'GET',

        url:
            "<?=admin_url('pos/getOrderItems');?>",

        data: {
            sale_id: saleId
        },

        dataType: 'json',

        success: function (data) {

            if (
                !data ||
                !data.status
            ) {

                showOrderItemsPreview(
                    saleId,
                    $button,
                    []
                );

                return;
            }


            orderItemsCache[saleId] =
                data.items || [];


            showOrderItemsPreview(
                saleId,
                $button,
                data.items || []
            );

        },

        error: function () {

            showOrderItemsPreview(
                saleId,
                $button,
                []
            );

        }

    });

}

function showOrderItemsPreview(
    saleId,
    $button,
    items,
    loading
) {

    /*
     * Xóa popup cũ
     */

    $('.order-items-tooltip')
        .remove();


    var html = '';

    html +=
        '<div class="order-items-tooltip">';


    if (loading) {

        html +=
            '<div class="order-items-loading">' +
                '<i class="fa fa-spinner fa-spin"></i> ' +
                'Đang tải món...' +
            '</div>';

    }

    else if (
        !items ||
        !items.length
    ) {

        html +=
            '<div class="order-items-empty">' +
                'Không có món.' +
            '</div>';

    }

    else {

        var totalQty = 0;

        $.each(
            items,
            function (index, item) {

                totalQty +=
                    parseFloat(
                        item.quantity || 0
                    );

            }
        );


        html +=
            '<div class="order-items-title">' +
                '<i class="fa fa-cutlery"></i> ' +
                totalQty +
                ' MÓN' +
            '</div>';


        html +=
            '<div class="order-items-list">';


        $.each(
            items,
            function (index, item) {

                var qty =
                    parseFloat(
                        item.quantity || 0
                    );


                html +=
                    '<div class="order-item-row">' +

                        '<span class="order-item-name">' +
                            escapeOrderHtml(
                                item.product_name
                            ) +
                        '</span>' +

                        '<span class="order-item-qty">' +
                            '× ' +
                            qty +
                        '</span>' +

                    '</div>';

            }
        );


        html += '</div>';

    }


    html += '</div>';


    $('body').append(html);


    positionOrderItemsTooltip(
        $button
    );


    /*
     * Nếu hover desktop:
     * giữ popup khi chuột đi từ icon
     * sang popup.
     */

    $('.order-items-tooltip')
        .data(
            'sale-id',
            saleId
        );

}

function positionOrderItemsTooltip(
    $button
) {

    var $tooltip =
        $('.order-items-tooltip');


    if (
        !$tooltip.length
    ) {
        return;
    }


    var offset =
        $button.offset();


    var buttonWidth =
        $button.outerWidth();


    var buttonHeight =
        $button.outerHeight();


    var tooltipWidth =
        $tooltip.outerWidth();


    var tooltipHeight =
        $tooltip.outerHeight();


    var left =
        offset.left +
        buttonWidth -
        tooltipWidth;


    var top =
        offset.top -
        tooltipHeight -
        8;


    /*
     * Nếu phía trên không đủ chỗ
     * thì mở xuống dưới.
     */

    if (
        top < $(window).scrollTop() + 10
    ) {

        top =
            offset.top +
            buttonHeight +
            8;

    }


    /*
     * Không cho tràn bên phải
     */

    var maxLeft =
        $(window).width() -
        tooltipWidth -
        10;


    if (
        left > maxLeft
    ) {

        left = maxLeft;

    }


    if (left < 10) {

        left = 10;

    }


    $tooltip.css({

        left: left,

        top: top

    });

}
$(document).on(
    'mouseenter',
    '.order-items-preview',
    function () {

        var $button =
            $(this);

        var saleId =
            $button.data(
                'sale-id'
            );


        clearTimeout(
            orderItemsTimer
        );


        loadOrderItemsPreview(
            saleId,
            $button
        );

    }
);
$(document).on(
    'mouseleave',
    '.order-items-preview',
    function () {

        var $button =
            $(this);


        orderItemsTimer =
            setTimeout(
                function () {

                    if (
                        !$('.order-items-tooltip:hover')
                            .length
                    ) {

                        $('.order-items-tooltip')
                            .remove();

                    }

                },
                150
            );

    }
);
$(document).on(
    'mouseleave',
    '.order-items-tooltip',
    function () {

        var $tooltip =
            $(this);


        orderItemsTimer =
            setTimeout(
                function () {

                    $tooltip.remove();

                },
                100
            );

    }
);

$(document).on(
    'click',
    '.order-items-preview',
    function (e) {

        e.preventDefault();

        e.stopPropagation();


        var $button =
            $(this);

        var saleId =
            $button.data(
                'sale-id'
            );


        /*
         * Nếu popup của chính icon này
         * đang mở -> đóng
         */

        var currentSaleId =
            $('.order-items-tooltip')
                .data('sale-id');


        if (
            currentSaleId ==
            saleId
        ) {

            $('.order-items-tooltip')
                .remove();

            return;

        }


        loadOrderItemsPreview(
            saleId,
            $button
        );

    }
);

$(document).on(
    'click',
    function (e) {

        if (
            !$(e.target)
                .closest(
                    '.order-items-preview, .order-items-tooltip'
                )
                .length
        ) {

            $('.order-items-tooltip')
                .remove();

        }

    }
);


/* =========================================================
   FORMAT TIỀN
========================================================= */

function formatOrderMoney(number) {

    number = parseFloat(number || 0);

    return number.toLocaleString('vi-VN') + 'đ';

}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeOrderHtml(text) {

    return $('<div>')
        .text(text || '')
        .html();

}


/* =========================================================
   PHÂN TRANG
========================================================= */

function updateOrderPagination() {

    $('#orderPageInfo').text(
        currentOrderListPage +
        ' / ' +
        totalOrderListPages
    );


    $('#orderPrevPage').prop(
        'disabled',
        currentOrderListPage <= 1
    );


    $('#orderNextPage').prop(
        'disabled',
        currentOrderListPage >= totalOrderListPages
    );

}

function goOrderListPage(
    direction
) {

    if (orderListLoading) {
        return;
    }


    if (
        direction === 'next'
    ) {

        if (
            currentOrderListPage >=
            totalOrderListPages
        ) {
            return;
        }


        currentOrderListPage++;

    }

    else {

        if (
            currentOrderListPage <= 1
        ) {
            return;
        }


        currentOrderListPage--;

    }


    loadTodayOrders(
        currentOrderListPage,
        direction
    );

}


/* =========================================================
   TRANG TRƯỚC
========================================================= */

$(document).on(
    'click',
    '#orderPrevPage',
    function () {

        goOrderListPage(
            'prev'
        );

    }
);


/* =========================================================
   TRANG SAU
========================================================= */

$(document).on(
    'click',
    '#orderNextPage',
    function () {

        goOrderListPage(
            'next'
        );

    }
);


/* =========================================================
   CLICK "CHƯA TT"
========================================================= */

$(document).on(
    'click',
    '.order-payment-due',
    function (e) {

        e.stopPropagation();


        var saleId =
            $(this).data('sale-id');


        $('.order-payment-menu')
            .not('[data-sale-id="' + saleId + '"]')
            .hide();


        $('.order-payment-menu[data-sale-id="' + saleId + '"]')
            .toggle();

    }
);


/* =========================================================
   CHỌN TIỀN MẶT / CHUYỂN KHOẢN
========================================================= */

$(document).on(
    'click',
    '.order-pay-btn',
    function (e) {

        e.stopPropagation();


        var saleId =
            $(this).data('sale-id');


        var paidBy =
            $(this).data('paid-by');


        updateOrderPayment(
            saleId,
            paidBy
        );

    }
);


/* =========================================================
   CẬP NHẬT THANH TOÁN
========================================================= */

function updateOrderPayment(
    saleId,
    paidBy
) {

    var postData = {

        sale_id: saleId,

        paid_by: paidBy

    };

    /* CSRF */
    postData[csrfName] = csrfHash;


    $.ajax({

        type: 'POST',

        url: "<?=admin_url('pos/updateOrderPayment');?>",

        data: postData,

        dataType: 'json',


        success: function (data) {

            /* Cập nhật CSRF mới nếu server trả về */
            if (
                data &&
                data.csrf_hash
            ) {

                csrfHash =
                    data.csrf_hash;

            }


            if (
                !data ||
                !data.status
            ) {

                alert(
                    data && data.message ?
                    data.message :
                    'Không cập nhật được thanh toán.'
                );

                return;
            }


            var text = 'Đã TT';


            if (paidBy === 'cash') {

                text = 'Tiền mặt';

            }

            else if (paidBy === 'cc') {

                text = 'Chuyển khoản';

            }


            var card =
                $('.order-card[data-sale-id="' +
                    saleId +
                '"]');


            card.find('.order-payment-wrap')
                .html(

                    '<span ' +
                    'class="order-payment-status order-payment-paid">' +

                        text +

                    '</span>'

                );

        },


        error: function (xhr) {

            console.log(
                'updateOrderPayment ERROR:',
                xhr.status,
                xhr.responseText
            );

            alert(
                'Lỗi kết nối máy chủ.'
            );

        }

    });

}


/* =========================================================
   CLICK GÁN / ĐỔI BÀN
========================================================= */

$(document).on(
    'click',
    '.change-order-table',
    function () {

        var saleId =
            $(this).data('sale-id');


        $('#change_table_sale_id')
            .val(saleId);


        $('#orderTableModal')
            .modal('show');


        loadOrderTableList();

    }
);


/* =========================================================
   LOAD DANH SÁCH BÀN
   DÙNG LUÔN getTables() ĐANG CÓ
========================================================= */

function loadOrderTableList() {

    $('#orderTableList').html(

        '<div class="text-center" ' +
        'style="grid-column:1/-1;padding:20px;">' +

            '<i class="fa fa-spinner fa-spin"></i>' +
            ' Đang tải...' +

        '</div>'

    );


    $.ajax({

        type: 'GET',

        url: "<?=admin_url('pos/getTables');?>",

        dataType: 'json',


        success: function (data) {

            if (!data || !data.success) {

                $('#orderTableList').html(

                    '<div class="text-danger text-center" ' +
                    'style="grid-column:1/-1;">' +

                        'Không lấy được danh sách bàn.' +

                    '</div>'

                );

                return;
            }


            var html = '';


            $.each(
                data.tables,
                function (index, table) {

                    html +=

                        '<button ' +
                        'type="button" ' +
                        'class="order-change-table-btn" ' +
                        'data-id="' + table.id + '">' +

                            escapeOrderHtml(
                                table.table_name
                            ) +

                        '</button>';

                }
            );


            if (!html) {

                html =
                    '<div class="text-center" ' +
                    'style="grid-column:1/-1;">' +

                        'Chưa có bàn nào.' +

                    '</div>';

            }


            $('#orderTableList')
                .html(html);

        },


        error: function () {

            $('#orderTableList').html(

                '<div class="text-danger text-center" ' +
                'style="grid-column:1/-1;">' +

                    'Lỗi kết nối máy chủ.' +

                '</div>'

            );

        }

    });

}


/* =========================================================
   CHỌN BÀN
========================================================= */

$(document).on(
    'click',
    '.order-change-table-btn',
    function () {

        var saleId =
            $('#change_table_sale_id').val();


        var tableId =
            $(this).data('id');


        var tableName =
            $(this).text().trim();


        var postData = {

            sale_id: saleId,

            table_id: tableId

        };


        /* CSRF */
        postData[csrfName] = csrfHash;


        $.ajax({

            type: 'POST',

            url: "<?=admin_url('pos/changeOrderTable');?>",

            data: postData,

            dataType: 'json',


            success: function (data) {

                /* Cập nhật CSRF mới */
                if (
                    data &&
                    data.csrf_hash
                ) {

                    csrfHash =
                        data.csrf_hash;

                }


                if (
                    !data ||
                    !data.status
                ) {

                    alert(
                        data && data.message ?
                        data.message :
                        'Không cập nhật được bàn.'
                    );

                    return;
                }


                /* Đổi ngay tên trên card */

                $('.order-customer-' + saleId)
                .text(data.table_name)
                .attr('title', data.table_name);


                /* Đóng modal */

                $('#orderTableModal')
                    .modal('hide');

            },


            error: function (xhr) {

                console.log(
                    'changeOrderTable ERROR:',
                    xhr.status,
                    xhr.responseText
                );

                alert(
                    'Lỗi kết nối máy chủ.'
                );

            }

        });

    }
);


/* =========================================================
   CLICK RA NGOÀI -> ĐÓNG MENU THANH TOÁN
========================================================= */

$(document).on('click', function () {

    $('.order-payment-menu').hide();

});


/* =========================================================
   SWIPE TRÁI / PHẢI
========================================================= */

var orderSwipeArea =
    document.getElementById(
        'orderListSwipeArea'
    );


$(document).on(
    'touchstart',
    '#orderListSwipeArea',
    function (e) {

        var touch =
            e.originalEvent.touches[0];


        orderSwipeStartX =
            touch.clientX;


        orderSwipeStartY =
            touch.clientY;

    }
);


$(document).on(
    'touchend',
    '#orderListSwipeArea',
    function (e) {

        var touch =
            e.originalEvent
                .changedTouches[0];


        var endX =
            touch.clientX;


        var endY =
            touch.clientY;


        var diffX =
            endX -
            orderSwipeStartX;


        var diffY =
            endY -
            orderSwipeStartY;


        /*
         * Không phải swipe ngang
         */
        if (
            Math.abs(diffX) < 60 ||
            Math.abs(diffX) <
            Math.abs(diffY)
        ) {

            return;

        }


        /*
         * Vuốt trái
         * => trang sau
         */
        if (
            diffX < 0
        ) {

            goOrderListPage(
                'next'
            );

        }


        /*
         * Vuốt phải
         * => trang trước
         */
        else {

            goOrderListPage(
                'prev'
            );

        }

    }
);


/* =========================================================
   KHI MỞ MODAL -> ĐẢM BẢO MENU ĐÃ ĐÓNG
========================================================= */

$('#orderListModal').on(
    'hidden.bs.modal',
    function () {

        $('.order-payment-menu').hide();

    }
);


/* =========================================================
   TIỆN ÍCH - SỔ NHẬP HÀNG
========================================================= */


// Lấy kho từ select kho đang có trên POS

function loadPosPurchaseWarehouses() {

    // Không lấy option từ #poswarehouse nữa.
    // Kho đã được PHP render trực tiếp trong modal.

    var currentWarehouse = $('#poswarehouse').val();

    if (
        currentWarehouse &&
        $('#purchase_warehouse option[value="' + currentWarehouse + '"]').length
    ) {
        $('#purchase_warehouse').val(currentWarehouse);
    }

    // Đồng bộ select2 nếu đã khởi tạo
    $('#purchase_warehouse').trigger('change');
}




// Mở menu tiện ích nổi trên toàn bộ POS
$(document).on('click', '#btn_pos_utilities', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var $menu = $('#posUtilitiesMenu');
    var rect = this.getBoundingClientRect();

    // Đưa menu ra body để tránh bị các vùng POS che
    if (!$menu.parent().is('body')) {
        $menu.appendTo('body');
    }

    if ($menu.is(':visible')) {
        $menu.hide();
        return;
    }

    $menu.css({
        display: 'block',
        top: (rect.bottom + 5) + 'px',
        left: rect.left + 'px'
    });

    // Nếu sát mép phải thì canh menu vào trong
    var menuWidth = $menu.outerWidth();

    if (rect.left + menuWidth > window.innerWidth - 8) {
        $menu.css('left', (window.innerWidth - menuWidth - 8) + 'px');
    }
});

// Click ngoài menu thì đóng
$(document).on('click', function(e) {
    if (!$(e.target).closest('#posUtilitiesMenu, #btn_pos_utilities').length) {
        $('#posUtilitiesMenu').hide();
    }
});

// Chọn Nhập hàng
$(document).on('click', '#btn_open_pos_purchase', function(e) {
    e.preventDefault();

    $('#posUtilitiesMenu').hide();
    $('#posPurchaseModal').modal('show');
});



// Reset form
$(document).on('click', '#btn_reset_pos_purchase', function () {

    setTimeout(function () {

        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var dd = String(today.getDate()).padStart(2, '0');

        $('#purchase_date').val(yyyy + '-' + mm + '-' + dd);
        $('#purchase_warehouse').val($('#poswarehouse').val());

    }, 0);

});


/* =========================================================
   LỊCH SỬ TÊN HÀNG NHẬP HÀNG
========================================================= */

var selectedPurchaseItemIndex = -1;


// Đọc lịch sử
function getPurchaseItemHistory() {

    try {
        var history = JSON.parse(
            localStorage.getItem('pos_purchase_item_history') || '[]'
        );

        return Array.isArray(history) ? history : [];

    } catch (e) {
        return [];
    }
}


// Hiển thị gợi ý
function loadPurchaseItemHistory(keyword) {

    var key = $.trim(keyword || '').toLocaleLowerCase('vi-VN');

    var $box = $('#purchase_item_suggestions');

    if (!key) {
        $box.hide().empty();
        return;
    }

    var history = getPurchaseItemHistory();

    var matched = history.filter(function (name) {

        return name.toLocaleLowerCase('vi-VN').indexOf(key) !== -1;

    }).slice(0, 10);

    $box.empty();

    if (!matched.length) {
        $box.hide();
        return;
    }

    matched.forEach(function (name) {

        $('<div>')
            .addClass('purchase-item-suggestion')
            .text(name)
            .css({
                padding: '10px 12px',
                cursor: 'pointer',
                borderBottom: '1px solid #eee'
            })
            .on('mouseenter', function () {

                $('#purchase_item_suggestions .purchase-item-suggestion')
                    .removeClass('active')
                    .css('background', '#fff');

                $(this)
                    .addClass('active')
                    .css('background', '#eaf3ff');

                selectedPurchaseItemIndex = $(this).index();

            })
            .on('mousedown', function (e) {

                e.preventDefault();

                $('#purchase_item_name').val(name);

                $box.hide().empty();

                selectedPurchaseItemIndex = -1;

            })
            .appendTo($box);

    });

    $box.show();
}


// Lưu tên hàng sau khi nhập thành công
function savePurchaseItemHistory() {

    var name = $.trim($('#purchase_item_name').val());

    if (!name) {
        return;
    }

    var history = getPurchaseItemHistory();

    // Không lưu trùng
    history = history.filter(function (item) {
        return item.toLocaleLowerCase('vi-VN') !==
            name.toLocaleLowerCase('vi-VN');
    });

    history.unshift(name);

    // Tối đa 100 tên
    history = history.slice(0, 100);

    localStorage.setItem(
        'pos_purchase_item_history',
        JSON.stringify(history)
    );

}


// Gõ tên hàng
$(document)
    .off('.purchaseItemHistory')
    .on(
        'input.purchaseItemHistory',
        '#purchase_item_name',
        function () {

            selectedPurchaseItemIndex = -1;

            loadPurchaseItemHistory($(this).val());

        }
    )
    .on(
        'keydown.purchaseItemHistory',
        '#purchase_item_name',
        function (e) {

            var $items = $(
                '#purchase_item_suggestions .purchase-item-suggestion'
            );

            if (
                !$items.length ||
                !$('#purchase_item_suggestions').is(':visible')
            ) {
                return;
            }

            if (e.key === 'ArrowDown') {

                e.preventDefault();

                selectedPurchaseItemIndex = Math.min(
                    selectedPurchaseItemIndex + 1,
                    $items.length - 1
                );

            } else if (e.key === 'ArrowUp') {

                e.preventDefault();

                selectedPurchaseItemIndex = Math.max(
                    selectedPurchaseItemIndex - 1,
                    0
                );

            } else if (
                e.key === 'Enter' &&
                selectedPurchaseItemIndex >= 0
            ) {

                e.preventDefault();

                $('#purchase_item_name').val(
                    $items.eq(selectedPurchaseItemIndex).text()
                );

                $('#purchase_item_suggestions').hide().empty();

                selectedPurchaseItemIndex = -1;

                return;

            } else if (e.key === 'Escape') {

                $('#purchase_item_suggestions').hide();

                selectedPurchaseItemIndex = -1;

                return;

            } else {

                return;

            }

            $items
                .removeClass('active')
                .css('background', '#fff');

            $items
                .eq(selectedPurchaseItemIndex)
                .addClass('active')
                .css('background', '#eaf3ff');

        }
    );


// Ẩn gợi ý khi click ra ngoài
$(document).on('mousedown', function (e) {

    if (!$(e.target).closest(
        '#purchase_item_name, #purchase_item_suggestions'
    ).length) {

        $('#purchase_item_suggestions').hide();

    }

});


// Lưu nhập hàng
$(document).on('submit', '#posPurchaseForm', function (e) {

    e.preventDefault();

    // Kiểm tra kho
    var warehouseId = $('#purchase_warehouse').val();

    if (!warehouseId) {
        bootbox.alert('Bạn chưa chọn kho nhập hàng.');
        return false;
    }

    var form = this;
    var formData = new FormData(form);

    formData.append(csrfName, csrfHash);

    var $button = $('#btn_save_pos_purchase');

    $button.prop('disabled', true)
        .html('<i class="fa fa-spinner fa-spin"></i> ĐANG LƯU...');

    $.ajax({

        type: 'POST',

        url: "<?=admin_url('pos/savePosPurchase');?>",

        data: formData,

        processData: false,
        contentType: false,
        dataType: 'json',

        success: function (data) {

            if (data && data.csrf_hash) {
                csrfHash = data.csrf_hash;
            }

            if (!data || !data.status) {

                bootbox.alert(
                    data && data.message
                        ? data.message
                        : 'Không lưu được nhập hàng.'
                );

                return;
            }

            // Lưu tên hàng vào lịch sử sau khi server xác nhận thành công
            savePurchaseItemHistory();

            bootbox.alert(data.message);

            form.reset();

            $('#purchase_item_suggestions').hide().empty();
            selectedPurchaseItemIndex = -1;

            var today = new Date();
            var yyyy = today.getFullYear();
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var dd = String(today.getDate()).padStart(2, '0');

            $('#purchase_date').val(yyyy + '-' + mm + '-' + dd);
            $('#purchase_warehouse').val($('#poswarehouse').val());

            $('#purchase_item_name').focus();

            loadPosPurchaseList();

        },

        error: function (xhr) {

            console.log('savePosPurchase:', xhr.responseText);

            bootbox.alert('Lỗi kết nối khi lưu nhập hàng.');

        },

        complete: function () {

            $button.prop('disabled', false)
                .html('<i class="fa fa-save"></i> NHẬP HÀNG');

        }

    });

});


// Tải danh sách
function loadPosPurchaseList() {

    $('#posPurchaseListContainer').html(
        '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Đang tải...</div>'
    );

    $.ajax({

        type: 'GET',

        url: "<?=admin_url('pos/getPosPurchaseList');?>",

        data: {
            warehouse_id: $('#poswarehouse').val(),
            date_from: $('#purchase_filter_from').val(),
            date_to: $('#purchase_filter_to').val(),
        },

        dataType: 'json',

        success: function (data) {

            if (!data || !data.status) {
                $('#posPurchaseListContainer').html(
                    '<div class="text-danger text-center">Không tải được danh sách.</div>'
                );
                return;
            }

            var rows = data.rows || [];

            if (!rows.length) {
                $('#posPurchaseListContainer').html(
                    '<div class="text-center text-muted">Không có dữ liệu nhập hàng.</div>'
                );
                return;
            }

            var html = '';

            html += '<div class="table-responsive">';
            html += '<table class="table table-bordered table-striped">';
            html += '<thead><tr>';
            html += '<th>Ngày</th>';
            html += '<th>Kho</th>';
            html += '<th>Tên hàng / Nội dung</th>';
            html += '<th class="text-right">Số tiền</th>';
            html += '<th>Đính kèm</th>';
            html += '</tr></thead><tbody>';

            var total = 0;

            $.each(rows, function (i, row) {

                var amount = parseFloat(row.amount) || 0;
                total += amount;

                var itemName = $('<div>').text(row.item_name || '').html();
                var warehouse = $('<div>').text(row.warehouse_name || '').html();

                var attachment = '-';

                if (row.attachment) {

                    attachment =
                        '<a target="_blank" href="<?=base_url('assets/uploads/pos_purchases/');?>' +
                        encodeURIComponent(row.attachment) +
                        '"><i class="fa fa-paperclip"></i> Xem file</a>';

                }

                html += '<tr>';
                html += '<td>' + row.purchase_date + '</td>';
                html += '<td>' + warehouse + '</td>';
                html += '<td>' + itemName + '</td>';
                html += '<td class="text-right">' +
                    amount.toLocaleString('vi-VN') + ' đ</td>';
                html += '<td>' + attachment + '</td>';
                html += '</tr>';

            });

            html += '</tbody>';

            html += '<tfoot><tr>';
            html += '<th colspan="3" class="text-right">TỔNG CỘNG</th>';
            html += '<th class="text-right">' +
                total.toLocaleString('vi-VN') + ' đ</th>';
            html += '<th></th>';
            html += '</tr></tfoot>';

            html += '</table></div>';

            $('#posPurchaseListContainer').html(html);

        },

        error: function (xhr) {

            console.log('getPosPurchaseList:', xhr.responseText);

            $('#posPurchaseListContainer').html(
                '<div class="text-danger text-center">Lỗi tải danh sách.</div>'
            );

        }

    });

}


// Khi chuyển sang tab danh sách
$(document).on('shown.bs.tab', 'a[href="#posPurchaseListTab"]', function () {
    loadPosPurchaseList();
});


// Lọc danh sách
$(document).on('click', '#btn_filter_pos_purchase', function () {
    loadPosPurchaseList();
});


// Mở form xuất Excel
$(document).on('click', '#btn_export_pos_purchase', function () {

    var warehouseId = $('#purchase_export_warehouse').val();
    var quarter = $('#purchase_export_quarter').val();
    var year = $('#purchase_export_year').val();

    var url =
        "<?=admin_url('pos/exportPosPurchaseExcel');?>"
        + '?warehouse_id=' + encodeURIComponent(warehouseId)
        + '&quarter=' + encodeURIComponent(quarter)
        + '&year=' + encodeURIComponent(year);

    window.open(url, '_blank');

});


</script>
<style>
#suspendSuccessToast {
    display: none;
    position: fixed;
    top: 27%;
    left: 48%;
    transform: translateX(-50%);
    z-index: 999999;
    min-width: 320px;
    max-width: 90%;
    padding: 22px 30px;
    background: rgba(255, 255, 255, 0.97);
    border: 2px solid #213d25;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
    text-align: center;
}

#suspendSuccessToast .toast-title {
    color: #258746;
    font-size: 20px;
    font-weight: bold;
}

#suspendSuccessToast .toast-note {
    margin-top: 10px;
    color: #fc0000;
    font-size: 20px;
    font-weight: bold;
    overflow-wrap: anywhere;
}
/* HIỆU ỨNG KHI CHỌN BÀN */
#btn_ban.table-selected-effect {
    animation: tableSelectedEffect 1s ease-out;
}

@keyframes tableSelectedEffect {
    0% {
        box-shadow: 0 0 0 0 rgba(40, 130, 220, 0);
        filter: brightness(1);
        transform: scale(1);
    }

    35% {
        box-shadow: 0 0 0 5px rgba(40, 130, 220, 0.28);
        filter: brightness(1.16);
        transform: scale(1.035);
    }

    70% {
        box-shadow: 0 0 0 2px rgba(40, 130, 220, 0.15);
        filter: brightness(1.07);
        transform: scale(1.015);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(40, 130, 220, 0);
        filter: brightness(1);
        transform: scale(1);
    }
}

/* =====================================================
   SỔ NHẬP HÀNG
===================================================== */

#posPurchaseModal .modal-content {
    border-radius: 10px;
    overflow: hidden;
}

#posPurchaseModal .modal-header {
    border-bottom: 1px solid #e5e5e5;
}

#posPurchaseModal .nav-tabs > li > a {
    font-weight: 600;
}

#posPurchaseModal .form-group label {
    font-weight: 600;
    margin-bottom: 6px;
}

#posPurchaseModal .form-control {
    min-height: 40px;
    border-radius: 5px;
}

#posPurchaseModal .table th {
    background: #f3f6fa;
    white-space: nowrap;
}

#posPurchaseModal .table td {
    vertical-align: middle;
}

#posPurchaseModal .dropdown-menu {
    z-index: 11000;
}

#posPurchaseListContainer {
    min-height: 100px;
}

#posPurchaseListContainer .table-responsive {
    max-height: 450px;
    overflow: auto;
}

</style>

<div id="suspendSuccessToast">
    <div class="toast-title">
        <i class="fa fa-check-circle"></i>
        LƯU HÓA ĐƠN TẠM THÀNH CÔNG
    </div>
    <div class="toast-note" id="suspendSuccessNote"></div>
</div>
</body>
</html>
