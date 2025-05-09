<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    var count = 1, an = 1, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, surcharge = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>;

    $(document).ready(function () {
        <?php if ($inv) { ?>
        //localStorage.setItem('redate', '<?= $this->sma->hrld($inv->date) ?>');
        localStorage.setItem('reref', '<?= $reference ?>');
        localStorage.setItem('renote', '<?= $this->sma->decode_html($inv->note); ?>');
        localStorage.setItem('reitems', JSON.stringify(<?= $inv_items; ?>));
        //console.log(JSON.stringify(<?= $inv_items; ?>));
            <?php if ($inv->order_discount_percent_for_return_sale > 0) { ?>
            console.log(<?=$inv->order_discount_percent_for_return_sale?>);
            localStorage.setItem('rediscount', '<?= $inv->order_discount_percent_for_return_sale ?>%');
            <?php } else { ?>
            console.log('order_discount_id');
            localStorage.setItem('rediscount', '<?= $inv->order_discount_id ?>');
            <?php } ?>
        localStorage.setItem('original_discount', '<?= $inv->order_discount_id ?>');
        localStorage.setItem('retax2', '<?= $inv->order_tax_id ?>');
        localStorage.setItem('return_surcharge', '0');
        <?php } ?>
        <?php if ($Owner || $Admin) { ?>
        if (!localStorage.getItem('redate')) {
            $("#redate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
        $(document).on('change', '#redate', function (e) {
            localStorage.setItem('redate', $(this).val());
        });
        if (redate = localStorage.getItem('redate')) {
            $('#redate').val(redate);
        }
        <?php } ?>
        if (reref = localStorage.getItem('reref')) {
            $('#reref').val(reref);
        }
        if (rediscount = localStorage.getItem('rediscount')) {
            $('#rediscount').val(rediscount);
        }
        if (retax2 = localStorage.getItem('retax2')) {
            $('#retax2').val(retax2);
        }
        if (return_surcharge = localStorage.getItem('return_surcharge')) {
            $('#return_surcharge').val(return_surcharge);
        }
        /*$(window).bind('beforeunload', function (e) {
         //localStorage.setItem('remove_resl', true);
         if (count > 1) {
         var message = "You will loss data!";
         return message;
         }
         });
         $('#add_return').click(function () {
         $(window).unbind('beforeunload');
         $('form.edit-resl-form').submit();
         });*/
        if (localStorage.getItem('reitems')) {
            loadItems();
        }
        $(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            //localStorage.setItem('paid_by', p_val);
            $('#rpaidby').val(p_val);
            if (p_val == 'cash') {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                //$('#amount_1').focus();
            } else if (p_val == 'CC') {
                $('.pcheque_1').hide();
                $('.pcash_1').hide();
                $('.pcc_1').show();
                $('#pcc_no_1').focus();
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                $('#cheque_no_1').focus();
            } else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
            }
            if (p_val == 'gift_card') {
                $('.gc').show();
                $('.ngc').hide();
                $('#gift_card_no').focus();
            } else {
                $('.ngc').show();
                $('.gc').hide();
                $('#gc_details').html('');
            }
        });
        /* ------------------------------
         * Sell Gift Card modal
         ------------------------------- */

        $(document).on('click', '#sellGiftCard', function (e) {
            $('#gcvalue').val($('#amount_1').val());
            $('#gccard_no').val(generateCardNo());
            $('#gcModal').appendTo("body").modal('show');
            return false;
        });
        $('#gccustomer').val(<?=$inv->customer_id?>).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: "<?= admin_url('customers/getCustomer') ?>/" + $(element).val(),
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

        $(document).on('click', '#noCus', function (e) {
            e.preventDefault();
            $('#gccustomer').select2('val', '');
            return false;
        });

        $('#genNo').click(function () {
            var no = generateCardNo();
            $(this).parent().parent('.input-group').children('input').val(no);
            return false;
        });

        $(document).on('click', '#addGiftCard', function (e) {
            var mid = (new Date).getTime(),
                gccode = $('#gccard_no').val(),
                gcname = $('#gcname').val(),
                gcvalue = $('#gcvalue').val(),
                gccustomer = $('#gccustomer').val(),
                gcexpiry = $('#gcexpiry').val() ? $('#gcexpiry').val() : '',
                gcprice = parseFloat($('#gcprice').val());
            if (gccode == '' || gcvalue == '' || gcprice == '' || gcvalue == 0 || gcprice == 0) {
                $('#gcerror').text('Please fill the required fields');
                $('.gcerror-con').show();
                return false;
            }

            var gc_data = new Array();
            gc_data[0] = gccode;
            gc_data[1] = gcvalue;
            gc_data[2] = gccustomer;
            gc_data[3] = gcexpiry;
            if (typeof slitems === "undefined") {
                var slitems = {};
            }

            $.ajax({
                type: 'get',
                url: site.base_url + 'sales/sell_gift_card',
                dataType: "json",
                data: {gcdata: gc_data},
                success: function (data) {
                    if (data.result === 'success') {
                        $('#gift_card_no').val(gccode);
                        $('#gc_details').text('<?=lang('gift_card_added')?>');
                        $('#gcModal').modal('hide');
                    } else {
                        $('#gcerror').text(data.message);
                        $('.gcerror-con').show();
                    }
                }
            });
            return false;
        });
        var old_row_qty;
        $(document).on("focus", '.rquantity', function () {
            old_row_qty = $(this).val();
        }).on("change", '.rquantity', function () {
            var row = $(this).closest('tr');
            var new_qty = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');
            if (!is_numeric(new_qty) || (new_qty > reitems[item_id].row.oqty)) {
                $(this).val(old_row_qty);
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                return false;
            }
            if(new_qty > reitems[item_id].row.oqty) {
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                $(this).val(old_row_qty);
                return false;
            }
            reitems[item_id].row.base_quantity = new_qty;
            if(reitems[item_id].row.unit != reitems[item_id].row.base_unit) {
                $.each(reitems[item_id].units, function(){
                    if (this.id == reitems[item_id].row.unit) {
                        reitems[item_id].row.base_quantity = unitToBaseQty(new_qty, this);
                    }
                });
            }
            reitems[item_id].row.qty = new_qty;
            localStorage.setItem('reitems', JSON.stringify(reitems));
            loadItems();
        });
        var old_surcharge;
        $(document).on("focus", '#return_surcharge', function () {
            old_surcharge = $(this).val() ? $(this).val() : '0';
        }).on("change", '#return_surcharge', function () {
            var new_surcharge = $(this).val() ? $(this).val() : '0';
            if (!is_valid_discount(new_surcharge)) {
                $(this).val(new_surcharge);
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                return;
            }
            localStorage.setItem('return_surcharge', JSON.stringify(new_surcharge));
            loadItems();
        });
        $(document).on('click', '.redel', function () {
            var row = $(this).closest('tr');
            var item_id = row.attr('data-item-id');
            delete reitems[item_id];
            row.remove();
            if(reitems.hasOwnProperty(item_id)) { } else {
                localStorage.setItem('reitems', JSON.stringify(reitems));
                loadItems();
                return;
            }
        });
    });
    //localStorage.clear();
    function loadItems() {

        if (localStorage.getItem('reitems')) {
            total = 0;
            total_extra = 0;
            total_with_no_points = 0;
            count = 1;
            an = 1;
            product_tax = 0;
            invoice_tax = 0;
            product_discount = 0;
            order_discount = 0;
            total_discount = 0;
            surcharge = 0;

            $("#reTable tbody").empty();
            reitems = JSON.parse(localStorage.getItem('reitems'));
            //console.log(JSON.stringify((reitems)));
            $.each(reitems, function () {
                var item = this;
                var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
                console.log(JSON.stringify((item.row)));
                var item_type = item.row.type, product_id = item.row.id, combo_items = item.combo_items, is_promo = item.row.is_promo, item_original_price = item.row.original_price, sale_item_id = item.row.sale_item_id, item_option = item.row.option, item_price = item.row.price, item_qty = item.row.qty, item_oqty = item.row.oqty, item_aqty = item.row.quantity, item_tax_method = item.row.tax_method, item_ds = item.row.discount, item_discount = 0, item_option = item.row.option, item_code = item.row.code, item_serial = item.row.serial, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
                var unit_price = item.row.unit_price;
                var product_unit = item.row.unit, base_quantity = item.row.base_quantity;
                if(product_unit != item.row.base_unit) {
                    $.each(item.units, function(){
                        if (this.id == product_unit) {
                            base_quantity = formatDecimal(unitToBaseQty(item.row.qty, this), 4);
                        }
                    });
                }

                var ds = item_ds ? item_ds : '0';
                if (ds.indexOf("%") !== -1) {
                    var pds = ds.split("%");
                    if (!isNaN(pds[0])) {
                        item_discount = formatDecimal((parseFloat(((unit_price) * parseFloat(pds[0])) / 100)), 4);
                    } else {
                        item_discount = formatDecimal(ds);
                    }
                } else {
                     item_discount = parseFloat(ds);
                }
                product_discount += formatDecimal((item_discount * item_qty), 4);

                unit_price = formatDecimal(unit_price-item_discount);
                var pr_tax = item.tax_rate;
                var pr_tax_val = 0, pr_tax_rate = 0;
                if (site.settings.tax1 == 1) {
                    if (pr_tax !== false) {
                        if (pr_tax.type == 1) {

                            if (item_tax_method == '0') {
                                pr_tax_val = formatDecimal(((unit_price) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate)), 4);
                                pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                            } else {
                                pr_tax_val = formatDecimal(((unit_price) * parseFloat(pr_tax.rate)) / 100, 4);
                                pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                            }

                        } else if (pr_tax.type == 2) {

                            pr_tax_val = parseFloat(pr_tax.rate);
                            pr_tax_rate = pr_tax.rate;

                        }
                        product_tax += pr_tax_val * item_qty;
                    }
                }
                item_price = item_tax_method == 0 ? formatDecimal((unit_price-pr_tax_val), 4) : formatDecimal(unit_price);
                unit_price = formatDecimal((unit_price+item_discount), 4);
                var sel_opt = '';
                $.each(item.options, function () {
                    if(this.id == item_option) {
                        sel_opt = this.name;
                    }
                });

                if (is_promo == 1 || (item.no_points == 1 && item_discount == 0) || item_discount > 0) {
                    //total_with_no_points += item_price * item_qty;
                    if (item_original_price > 0) {
                        item_discount = item_original_price - item_price;
                    }
                }

                //console.log('total_with_no_points: ' + total_with_no_points);

                //'+(item_option != 0 ? ' - '+item.option.name : '')+'
                var row_no = (new Date).getTime();
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');

                // Column Name
                tr_html = '<td>' +
                        '<input name="sale_item_id[]" type="hidden" class="rsiid" value="' + sale_item_id + '">' +
                        '<input name="no_points[]" type="hidden" class="no_pts" value="' + item.no_points + '">' +
                        '<input name="product_id[]" type="hidden" class="rid" value="' + product_id + '">' +
                        '<input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '">' +
                        '<input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '">' +
                        '<input name="product_option[]" type="hidden" class="roption" value="' + item_option + '">' +
                        '<input name="product_name[]" type="hidden" class="rname" value="' + item_name + '">' +
                        '<input name="promo_original_price[]" type="hidden" class="roprice" value="' + (is_promo != 0 ? item_original_price: 0) + '">' +
                        '<input name="is_promo[]" type="hidden" class="is_promo" value="' + is_promo + '">' +
                        '<span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')'+(sel_opt != '' ? ' ('+sel_opt+')' : '')+'</span></td>';

                // Column Price
                if ((is_promo == 1 && item_original_price > 0) || item_discount > 0) {
                    var display_price = '<span class="text-right sprice" id="sprice_' + row_no + '">' + formatMoney(item_price) + '</span>';
                    display_price += '(<span style="text-decoration: line-through">' + formatMoney(item_original_price) + '</span>)';
                } else {
                    display_price = formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val));
                }
                tr_html += '<td class="text-right">' +
                            '<input class="form-control input-sm text-right rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + item_price + '">' +
                            '<input class="ruprice" name="unit_price[]" type="hidden" value="' + unit_price + '">' +
                            '<input class="realuprice" name="real_unit_price[]" type="hidden" value="' + item.row.real_unit_price + '">' + display_price +
                        '</td>';

                // Column Quantity
                tr_html += '<td class="text-center"><span>' + formatDecimal(item_oqty) + '</span></td>';

                // Column Return Quantity
                tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"><input name="product_unit[]" type="hidden" class="runit" value="' + product_unit + '"><input name="product_base_quantity[]" type="hidden" class="rbase_quantity" value="' + base_quantity + '"></td>';

                if (site.settings.product_serial == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rserial" name="serial[]" type="text" id="serial_' + row_no + '" value="' + item_serial + '"></td>';
                }

                // Column Return Discount
                if (site.settings.product_discount == 1) {
                    tr_html += '<td class="text-right">' +
                                '<input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item_ds + '">' +
                                '<span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney(0 - (item_discount * item_qty)) + '</span>' +
                            '</td>';
                }

                if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
                }

                // Column Return SubTotal
                tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip pointer redel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#reTable");
                total += parseFloat((item_price + parseFloat(pr_tax_val)) * parseFloat(item_qty));
                if (item_discount == 0) {
                    total_extra += formatDecimal(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)), 4);
                    console.log('item_price: ' + item_price);
                }
                count += parseFloat(item_qty);
                an++;

            });
            console.log('total extra: ' + total_extra);
            // Order level discount calculations
            if (rediscount = localStorage.getItem('rediscount')) {
                var ds = rediscount;
                if (ds.indexOf("%") !== -1) {
                    var pds = ds.split("%");
                    if (!isNaN(pds[0])) {
                        order_discount = parseFloat(((total_extra - total_with_no_points + product_tax) * parseFloat(pds[0])) / 100);
                        console.log('xxx: ' + order_discount);
                    } else {
                        order_discount = parseFloat(ds);
                    }
                } else {
                    order_discount = parseFloat(ds);
                }

            }

            // Order level tax calculations
            if (site.settings.tax2 != 0) {
                if (retax2 = localStorage.getItem('retax2')) {
                    $.each(tax_rates, function () {
                        if (this.id == retax2) {
                            if (this.type == 2) {
                                invoice_tax = parseFloat(this.rate);
                            }
                            if (this.type == 1) {
                                invoice_tax = parseFloat(((total + product_tax - order_discount) * this.rate) / 100);
                            }
                        }
                    });
                }
            }
            total_discount = parseFloat(order_discount + product_discount);

            // Totals calculations after item addition
            var gtotal = parseFloat(((total + invoice_tax) - order_discount));

            if (return_surcharge = localStorage.getItem('return_surcharge')) {
                var rs = return_surcharge.replace(/"/g, '');
                if (rs.indexOf("%") !== -1) {
                    var prs = rs.split('%');
                    var percentage = parseFloat(prs[0]);
                    if (!isNaN(prs[0])) {
                        surcharge = parseFloat((gtotal * percentage) / 100);
                    } else {
                        surcharge = parseFloat(rs);
                    }
                } else {
                    surcharge = parseFloat(rs);
                }
            }
            //console.log(surcharge);
            gtotal -= surcharge;

            $('#total').text(formatMoney(total));
            $('#titems').text((an - 1) + ' (' + (parseFloat(count) - 1) + ')');
            $('#total_items').val((parseFloat(count) - 1));
            $('#tdis').text(formatMoney(order_discount));
            if (site.settings.tax1) {
                $('#ttax1').text(formatMoney(product_tax));
            }
            if (site.settings.tax2 != 0) {
                $('#ttax2').text(formatMoney(invoice_tax));
            }
            $('#gtotal').text(formatMoney(gtotal));
            <?php echo "$('#amount_1').val(formatDecimal(gtotal));"; ?>
            if (an > site.settings.bc_fix && site.settings.bc_fix != 0) {
                $("html, body").animate({scrollTop: $('#reTable').offset().top - 150}, 500);
                $(window).scrollTop($(window).scrollTop() + 1);
            }
            if (count > 1) {
                $('#add_item').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
            }
            //audio_success.play();
        }
    }
</script>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-minus-circle"></i><?= lang('return_sale'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-resl-form');
                echo admin_form_open_multipart("sales/return_sale/" . $inv->id, $attrib)
                ?>

                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "redate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="redate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "reref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ''), 'class="form-control input-tip" id="reref"'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("return_surcharge", "return_surcharge"); ?>
                                <?php echo form_input('return_surcharge', (isset($_POST['return_surcharge']) ? $_POST['return_surcharge'] : ''), 'class="form-control input-tip" id="return_surcharge" required="required"'); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("document", "document") ?>
                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?></label> (<?= lang('return_tip'); ?>)

                                <div class="controls table-controls">
                                    <table id="reTable"
                                           class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                        <tr>
                                            <th class="col-md-4"><?= lang("product_name") . " (" . $this->lang->line("product_code") . ")"; ?></th>
                                            <th class="col-md-1"><?= lang("net_unit_price"); ?></th>
                                            <th class="col-md-1"><?= lang("quantity"); ?></th>
                                            <th class="col-md-1"><?= lang("return_quantity"); ?></th>
                                            <?php
                                            if ($Settings->product_serial) {
                                                echo '<th class="col-md-2">' . $this->lang->line("serial_no") . '</th>';
                                            }
                                            ?>
                                            <?php
                                            if ($Settings->product_discount) {
                                                echo '<th class="col-md-1">' . $this->lang->line("discount") . '</th>';
                                            }
                                            ?>
                                            <?php
                                            if ($Settings->tax1) {
                                                echo '<th class="col-md-1">' . $this->lang->line("product_tax") . '</th>';
                                            }
                                            ?>
                                            <th><?= lang("subtotal"); ?> (<span
                                                    class="currency"><?= $default_currency->code ?></span>)
                                            </th>
                                            <th style="width: 30px !important; text-align: center;"><i
                                                    class="fa fa-trash-o"
                                                    style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
                                <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
                                    <tr class="warning">
                                        <td>
                                            <?= lang('items') ?>
                                            <span class="totals_val pull-right" id="titems">0</span>
                                        </td>
                                        <td>
                                            <?= lang('total') ?>
                                            <span class="totals_val pull-right" id="total">0.00</span>
                                        </td>
                                        <?php if ($Settings->tax1) { ?>
                                        <td>
                                            <?= lang('product_tax') ?>
                                            <span class="totals_val pull-right" id="ttax1">0.00</span>
                                        </td>
                                        <?php } ?>
                                        <td>
                                            <?= lang('discount') ?>
                                            <span class="totals_val pull-right" id="tdis">0.00</span>
                                        </td>
                                        <?php if ($Settings->tax2) { ?>

                                        <?php } ?>
                                        <td>
                                            <?= lang('return_amount') ?>
                                            <span class="totals_val pull-right" id="gtotal">0.00</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div style="height:15px; clear: both;"></div>
                        <div class="col-md-12">
                            <?php
                            $sst = array('pending' => lang('pending'), 'paid' => lang('paid'), 'completed' => lang('completed'));
                            if ($inv->payment_status == 'paid') {
                                echo '<div class="alert alert-success">' . lang('payment_status') . ': <strong>' . $sst[$inv->payment_status] . '</strong> & ' . lang('paid_amount') . ' <strong>' . $this->sma->formatMoney($inv->paid) . '</strong></div>';
                            } else {
                                echo '<div class="alert alert-warning">' . lang('payment_status_not_paid') . ' ' . lang('payment_status') . ': <strong>' . $inv->payment_status . '</strong> & ' . lang('paid_amount') . ' <strong>' . $this->sma->formatMoney($inv->paid) . '</strong></div>';
                            }
                            ?>
                        </div>
                        <?php if (($Owner || $Admin || $GP['sales-payments']) && ($inv->payment_status == 'paid' || $inv->payment_status == 'partial')) { ?>
                        <div id="payments">
                            <div class="col-md-12">
                                <div class="well well-sm well_1">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <?= lang("payment_reference_no", "payment_reference_no"); ?>
                                                    <?= form_input('payment_reference_no', (isset($_POST['payment_reference_no']) ? $_POST['payment_reference_no'] : $payment_ref), 'class="form-control tip" id="payment_reference_no"'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="payment">
                                                    <div class="form-group">
                                                        <?= lang("amount", "amount_1"); ?>
                                                        <input name="amount-paid" type="text" id="amount_1"
                                                               class="pa form-control kb-pad amount"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <?= lang("paying_by", "paid_by_1"); ?>
                                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by">
                                                        <?= $this->sma->paid_opts(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="pcc_1" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_no" type="text" id="pcc_no_1"
                                                               class="form-control" placeholder="<?= lang('cc_no') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_holder" type="text" id="pcc_holder_1"
                                                               class="form-control"
                                                               placeholder="<?= lang('cc_holder') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="pcc_type" id="pcc_type_1"
                                                                class="form-control pcc_type"
                                                                placeholder="<?= lang('card_type') ?>">
                                                            <option value="Visa"><?= lang("Visa"); ?></option>
                                                            <option
                                                                value="MasterCard"><?= lang("MasterCard"); ?></option>
                                                            <option value="Amex"><?= lang("Amex"); ?></option>
                                                            <option value="Discover"><?= lang("Discover"); ?></option>
                                                        </select>
                                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_month" type="text" id="pcc_month_1"
                                                               class="form-control" placeholder="<?= lang('month') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_year" type="text" id="pcc_year_1"
                                                               class="form-control" placeholder="<?= lang('year') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_ccv" type="text" id="pcc_cvv2_1"
                                                               class="form-control" placeholder="<?= lang('cvv2') ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pcheque_1" style="display:none;">
                                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                                <input name="cheque_no" type="text" id="cheque_no_1"
                                                       class="form-control cheque_no"/>
                                            </div>
                                        </div>
                                        <div class="gc" style="display: none;">
                                            <div class="form-group">
                                                <?= lang("gift_card_no", "gift_card_no"); ?>
                                                <div class="input-group">

                                                    <input name="gift_card_no" type="text" id="gift_card_no"
                                                           class="pa form-control kb-pad"/>

                                                    <div class="input-group-addon"
                                                         style="padding-left: 10px; padding-right: 10px; height:25px;">
                                                        <a href="#" id="sellGiftCard" class="tip"
                                                           title="<?= lang('sell_gift_card') ?>"><i
                                                                class="fa fa-credit-card"></i></a></div>
                                                </div>
                                            </div>
                                            <div id="gc_details"></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>
                        <input type="hidden" name="order_tax" value="" id="retax2" required="required"/>
                        <input type="hidden" name="discount" value="" id="rediscount" required="required"/>

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("return_note", "renote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="renote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_return', $this->lang->line("submit"), 'id="add_return" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?></div>
                        </div>
                    </div>
                </div>


                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<div class="modal" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel"><?= lang('sell_gift_card'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?= lang('enter_info'); ?></p>

                <div class="alert alert-danger gcerror-con" style="display: none;">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <span id="gcerror"></span>
                </div>
                <div class="form-group">
                    <?= lang("card_no", "gccard_no"); ?> *
                    <div class="input-group">
                        <?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no" onClick="this.select();"'); ?>
                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                                                                                           id="genNo"><i
                                    class="fa fa-cogs"></i></a></div>
                    </div>
                </div>
                <input type="hidden" name="gcname" value="<?= lang('gift_card') ?>" id="gcname"/>

                <div class="form-group">
                    <?= lang("value", "gcvalue"); ?> *
                    <?php echo form_input('gcvalue', '', 'class="form-control" id="gcvalue"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("customer", "gccustomer"); ?>
                    <div class="input-group">
                        <?php echo form_input('gccustomer', '', 'class="form-control" id="gccustomer"'); ?>
                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                                                                                           id="noCus"
                                                                                                           class="tip"
                                                                                                           title="<?= lang('unselect_customer') ?>"><i
                                    class="fa fa-times"></i></a></div>
                    </div>
                </div>
                <div class="form-group">
                    <?= lang("expiry_date", "gcexpiry"); ?>
                    <?php echo form_input('gcexpiry', '', 'class="form-control date" id="cgexpiry"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="addGiftCard" class="btn btn-primary"><?= lang('sell_gift_card') ?></button>
            </div>
        </div>
    </div>
</div>
