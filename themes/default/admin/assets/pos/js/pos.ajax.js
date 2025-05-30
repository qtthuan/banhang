$(document).ready(function() {
    $('body a, body button').attr('tabindex', -1);
    check_add_item_val();
    $(document).on('keypress', '.rquantity', function (e) {
        if (e.keyCode == 13) {
            $('#add_item').focus();
        }
    });

    $('#toogle-customer-read-attr').click(function () {
        var nst = $('#poscustomer').is('[readonly]') ? false : true;
        $('#poscustomer').select2("readonly", nst);
        return false;
    });
    $(".open-brands").click(function () {
        $('#brands-slider').toggle('slide', { direction: 'right' }, 700);
    });
    $(".open-category").click(function () {
        $('#category-slider').toggle('slide', { direction: 'right' }, 700);
    });
    $(".open-subcategory").click(function () {
        $('#subcategory-slider').toggle('slide', { direction: 'right' }, 700);
    });
    $(document).on('click', function(e){
        if (!$(e.target).is(".open-brands, .cat-child") && !$(e.target).parents("#brands-slider").size() && $('#brands-slider').is(':visible')) {
            $('#brands-slider').toggle('slide', { direction: 'right' }, 700);
        }
        if (!$(e.target).is(".open-category, .cat-child") && !$(e.target).parents("#category-slider").size() && $('#category-slider').is(':visible')) {
            $('#category-slider').toggle('slide', { direction: 'right' }, 700);
        }
        if (!$(e.target).is(".open-subcategory, .cat-child") && !$(e.target).parents("#subcategory-slider").size() && $('#subcategory-slider').is(':visible')) {
            $('#subcategory-slider').toggle('slide', { direction: 'right' }, 700);
        }
    });
    $('.po').popover({html: true, placement: 'right', trigger: 'click'}).popover();
    $('#inlineCalc').calculator({layout: ['_%+-CABS','_7_8_9_/','_4_5_6_*','_1_2_3_-','_0_._=_+'], showFormula:true});
    $('.calc').click(function(e) { e.stopPropagation();});
    $(document).on('click', '[data-toggle="ajax"]', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        $.get(href, function( data ) {
            $("#myModal").html(data).modal();
        });
    });
    $(document).on('click', '.sname', function(e) {
        var row = $(this).closest('tr');
        var itemid = row.find('.rid').val();
        $('#myModal').modal({remote: site.base_url + 'products/modal_view/' + itemid});
        $('#myModal').modal('show');
    });
});
$(document).ready(function () {
// Order level shipping and discount localStorage
if (posdiscount = localStorage.getItem('posdiscount')) {
    $('#posdiscount').val(posdiscount);
}
$(document).on('change', '#ppostax2', function () {
    localStorage.setItem('postax2', $(this).val());
    $('#postax2').val($(this).val());
});

if (postax2 = localStorage.getItem('postax2')) {
    $('#postax2').val(postax2);
}

$(document).on('blur', '#sale_note', function () {
    localStorage.setItem('posnote', $(this).val());
    $('#sale_note').val($(this).val());
});

if (posnote = localStorage.getItem('posnote')) {
    $('#sale_note').val(posnote);
}

$(document).on('change', '.delivery', function () {
    localStorage.setItem('pos_delivery', $(this).val());
    $('#delivery').val($(this).val());
});

if (pos_delivery = localStorage.getItem('pos_delivery')) {
    $('#delivery').val(pos_delivery);
}

$(document).on('blur', '#staffnote', function () {
    localStorage.setItem('staffnote', $(this).val());
    $('#staffnote').val($(this).val());
});

if (staffnote = localStorage.getItem('staffnote')) {
    $('#staffnote').val(staffnote);
}

if (posshipping = localStorage.getItem('posshipping')) {
    $('#posshipping').val(posshipping);
    shipping = parseFloat(posshipping);
}
$("#pshipping").click(function(e) {
    e.preventDefault();
    shipping = $('#posshipping').val() ? $('#posshipping').val() : shipping;
    $('#shipping_input').val(shipping);
    $('#sModal').modal();
});
$('#sModal').on('shown.bs.modal', function() {
    $(this).find('#shipping_input').select().focus();
});
$(document).on('click', '#updateShipping', function() {
    var s = parseFloat($('#shipping_input').val() ? $('#shipping_input').val() : '0');
    if (is_numeric(s)) {
        $('#posshipping').val(s);
        localStorage.setItem('posshipping', s);
        shipping = s;
        loadItems();
        $('#sModal').modal('hide');
    } else {
        bootbox.alert(lang.unexpected_value);
    }
});

if (posreturn = localStorage.getItem('posreturn')) {
    $('#posreturn').val(posreturn);
    preturn = parseFloat(posreturn);
}
$("#preturn").click(function(e) {
    e.preventDefault();
    preturn = $('#posreturn').val() ? $('#posreturn').val() : preturn;
    $('#return_input').val(preturn);
    $('#txt_return_amount').text(formatMoney(preturn));
    $('#rModal').modal();
});
$('#rModal').on('shown.bs.modal', function() {
    $(this).find('#return_code_input').select().focus();
});
$(document).on('click', '#updateReturn', function() {
    var s = parseFloat($('#return_input').val() ? $('#return_input').val() : '0');
    if (is_numeric(s)) {
        $('#posreturn').val(s);
        localStorage.setItem('posreturn', s);
        preturn = s;
        loadItems();
        $('#rModal').modal('hide');
    } else {
        bootbox.alert(lang.unexpected_value);
    }
});

$(document).on('click', '#findReturnInvoice', function () {
    var code_val = $('#return_code_input').val() ? $('#return_code_input').val() : '';

    if (code_val != '') {
        if (code_val.indexOf('#') > -1) {
            $('#return_input').val(code_val.substring(1, code_val.length));
            $('#txt_return_amount').text(formatMoney(code_val.substring(1, code_val.length)));
        } else {
            $.ajax({
                type: "get", async: false,
                url: site.base_url + "sales/get_return_sale/" + code_val,
                dataType: "json",
                success: function (data) {
                    if (data === false) {
                        bootbox.alert(lang.not_found_return_invoice);
                        $('#return_input').val(0);
                        $('#txt_return_amount').text(0);
                    } else {
                        $('#return_input').val(data.grand_total * -1);
                        $('#txt_return_amount').text(formatMoney(data.grand_total * -1));
                    }
                }
            });
        }
    }
});

/* ----------------------
     * Order Discount Handler
     * ---------------------- */
     $("#ppdiscount").click(function(e) {
        e.preventDefault();
        var dval = $('#posdiscount').val() ? $('#posdiscount').val() : '0';
        $('#order_discount_input').val(dval);
        $('#dsModal').modal();
     });
     $('#dsModal').on('shown.bs.modal', function() {
        $(this).find('#order_discount_input').select().focus();
        $('#order_discount_input').bind('keypress', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                var ds = $('#order_discount_input').val();
                if (is_valid_discount(ds)) {
                    $('#posdiscount').val(ds);
                    localStorage.removeItem('posdiscount');
                    localStorage.setItem('posdiscount', ds);
                    loadItems();
                } else {
                    bootbox.alert(lang.unexpected_value);
                }
                $('#dsModal').modal('hide');
            }
        });
     });
     $(document).on('click', '#updateOrderDiscount', function() {
        var ds = $('#order_discount_input').val() ? $('#order_discount_input').val() : '0';
        if (is_valid_discount(ds)) {
            $('#posdiscount').val(ds);
            localStorage.removeItem('posdiscount');
            localStorage.setItem('posdiscount', ds);
            loadItems();
        } else {
            bootbox.alert(lang.unexpected_value);
        }
        $('#dsModal').modal('hide');
     });
/* ----------------------
     * Order Tax Handler
     * ---------------------- */
     $("#pptax2").click(function(e) {
        e.preventDefault();
        var postax2 = localStorage.getItem('postax2');
        $('#order_tax_input').select2('val', postax2);
        $('#txModal').modal();
     });
     $('#txModal').on('shown.bs.modal', function() {
        $(this).find('#order_tax_input').select2('focus');
     });
     $('#txModal').on('hidden.bs.modal', function() {
        var ts = $('#order_tax_input').val();
        $('#postax2').val(ts);
        localStorage.setItem('postax2', ts);
        loadItems();
     });
     $(document).on('click', '#updateOrderTax', function () {
        var ts = $('#order_tax_input').val();
        $('#postax2').val(ts);
        localStorage.setItem('postax2', ts);
        loadItems();
        $('#txModal').modal('hide');
     });


     $(document).on('change', '.rserial', function () {
        var item_id = $(this).closest('tr').attr('data-item-id');
        positems[item_id].row.serial = $(this).val();
        localStorage.setItem('positems', JSON.stringify(positems));
     });

// If there is any item in localStorage
if (localStorage.getItem('positems')) {
    loadItems();
}

    // clear localStorage and reload
    $('#reset').click(function (e) {
        if (protect_delete == 1) {
            var boxd = bootbox.dialog({
                title: "<i class='fa fa-key'></i> Pin Code",
                message: '<input id="pos_pin" name="pos_pin" type="password" placeholder="Pin Code" class="form-control"> ',
                buttons: {
                    success: {
                        label: "<i class='fa fa-tick'></i> OK",
                        className: "btn-success verify_pin",
                        callback: function () {
                            var pos_pin = md5($('#pos_pin').val());
                            if(pos_pin == pos_settings.pin_code) {
                                
                                if (localStorage.getItem('positems')) {
                                    localStorage.removeItem('positems');
                                }
                                if (localStorage.getItem('posdiscount')) {
                                    localStorage.removeItem('posdiscount');
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
                                if (localStorage.getItem('posref')) {
                                    localStorage.removeItem('posref');
                                }
                                if (localStorage.getItem('poswarehouse')) {
                                    localStorage.removeItem('poswarehouse');
                                }
                                if (localStorage.getItem('posnote')) {
                                    localStorage.removeItem('posnote');
                                }
                                if (localStorage.getItem('posinnote')) {
                                    localStorage.removeItem('posinnote');
                                }
                                if (localStorage.getItem('poscustomer')) {
                                    localStorage.removeItem('poscustomer');
                                }
                                if (localStorage.getItem('poscurrency')) {
                                    localStorage.removeItem('poscurrency');
                                }
                                if (localStorage.getItem('posdate')) {
                                    localStorage.removeItem('posdate');
                                }
                                if (localStorage.getItem('posstatus')) {
                                    localStorage.removeItem('posstatus');
                                }
                                if (localStorage.getItem('posbiller')) {
                                    localStorage.removeItem('posbiller');
                                }

                                $('#modal-loading').show();
                                window.location.href = site.base_url+"pos";

                            } else {
                                bootbox.alert('Wrong Pin Code');
                            }
                        }
                    }
                }
            });
        } else {
            bootbox.confirm(lang.r_u_sure, function (result) {
                if (result) {
                    if (localStorage.getItem('positems')) {
                        localStorage.removeItem('positems');
                    }
                    if (localStorage.getItem('posdiscount')) {
                        localStorage.removeItem('posdiscount');
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
                    if (localStorage.getItem('posref')) {
                        localStorage.removeItem('posref');
                    }
                    if (localStorage.getItem('poswarehouse')) {
                        localStorage.removeItem('poswarehouse');
                    }
                    if (localStorage.getItem('posnote')) {
                        localStorage.removeItem('posnote');
                    }
                    if (localStorage.getItem('posinnote')) {
                        localStorage.removeItem('posinnote');
                    }
                    if (localStorage.getItem('poscustomer')) {
                        localStorage.removeItem('poscustomer');
                    }
                    if (localStorage.getItem('poscurrency')) {
                        localStorage.removeItem('poscurrency');
                    }
                    if (localStorage.getItem('posdate')) {
                        localStorage.removeItem('posdate');
                    }
                    if (localStorage.getItem('posstatus')) {
                        localStorage.removeItem('posstatus');
                    }
                    if (localStorage.getItem('posbiller')) {
                        localStorage.removeItem('posbiller');
                    }

                    $('#modal-loading').show();
                    window.location.href = site.base_url+"pos";
                }
            });
        }
    });

// save and load the fields in and/or from localStorage

$('#poswarehouse').change(function (e) {
    localStorage.setItem('poswarehouse', $(this).val());
});
if (poswarehouse = localStorage.getItem('poswarehouse')) {
    $('#poswarehouse').select2('val', poswarehouse);
}

    //$(document).on('change', '#posnote', function (e) {
        $('#posnote').redactor('destroy');
        $('#posnote').redactor({
            buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
            formattingTags: ['p', 'pre', 'h3', 'h4'],
            minHeight: 100,
            changeCallback: function (e) {
                var v = this.get();
                localStorage.setItem('posnote', v);
            }
        });
        if (posnote = localStorage.getItem('posnote')) {
            $('#posnote').redactor('set', posnote);
        }

        $('#poscustomer').change(function (e) {
            localStorage.setItem('poscustomer', $(this).val());
            loadItems();
        });


// prevent default action upon enter
$('body').not('textarea').bind('keypress', function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
});

// Order tax calculation
if (site.settings.tax2 != 0) {
    $('#postax2').change(function () {
        localStorage.setItem('postax2', $(this).val());
        loadItems();
        return;
    });
}

// Order discount calculation
var old_posdiscount;
$('#posdiscount').focus(function () {
    old_posdiscount = $(this).val();
}).change(function () {
    var new_discount = $(this).val() ? $(this).val() : '0';
    if (is_valid_discount(new_discount)) {
        localStorage.removeItem('posdiscount');
        localStorage.setItem('posdiscount', new_discount);
        loadItems();
        return;
    } else {
        $(this).val(old_posdiscount);
        bootbox.alert(lang.unexpected_value);
        return;
    }

});

    /* ----------------------
     * Delete Row Method
     * ---------------------- */
     var pwacc = false;
     $(document).on('click', '.posdel', function () {
        var row = $(this).closest('tr');
        var item_id = row.attr('data-item-id');
        if(protect_delete == 1) {
            var boxd = bootbox.dialog({
                title: "<i class='fa fa-key'></i> Pin Code",
                message: '<input id="pos_pin" name="pos_pin" type="password" placeholder="Pin Code" class="form-control"> ',
                buttons: {
                    success: {
                        label: "<i class='fa fa-tick'></i> OK",
                        className: "btn-success verify_pin",
                        callback: function () {
                            var pos_pin = md5($('#pos_pin').val());
                            if(pos_pin == pos_settings.pin_code) {
                                delete positems[item_id];
                                row.remove();
                                if(positems.hasOwnProperty(item_id)) { } else {
                                    localStorage.setItem('positems', JSON.stringify(positems));
                                    loadItems();
                                }
                            } else {
                                bootbox.alert('Wrong Pin Code');
                            }
                        }
                    }
                }
            });
            boxd.on("shown.bs.modal", function() {
                $( "#pos_pin" ).focus().keypress(function(e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        $('.verify_pin').trigger('click');
                        return false;
                    }
                });
            });
        } else {
            delete positems[item_id];
            row.remove();
            if(positems.hasOwnProperty(item_id)) { } else {
                localStorage.setItem('positems', JSON.stringify(positems));
                loadItems();
            }
        }
        return false;
     });

    /* -----------------------
     * Edit Row Modal Hanlder
     ----------------------- */
    $(document).on('click', '.edit', function () {
        var row = $(this).closest('tr');
        var row_id = row.attr('id');
        item_id = row.attr('data-item-id');
        item = positems[item_id];
        //console.log(JSON.stringify(item));
        var qty = row.children().children('.rquantity').val(),
        product_option = row.children().children('.roption').val(),
        unit_price = formatDecimal(row.children().children('.ruprice').val()),
        discount = row.children().children('.rdiscount').val();
        original_price = parseFloat(row.children().children('.roprice').val());
        is_promo = row.children().children('.is_promo').val();
        comment = item.row.comment;
        comment_name = item.row.comment_name;
        if (comment == '') {
            $('.chkComment').removeAttr('checked');
        }

        //console.log('bef: ' + unit_price + ' promo: ' + is_promo);

        if(item.options !== false) {
            $.each(item.options, function () {
                if(this.id == item.row.option && this.price != 0 && this.price != '' && this.price != null) {
                    unit_price = parseFloat(item.row.real_unit_price)+parseFloat(this.price);
                }
            });
        }

        var real_unit_price = item.row.real_unit_price;
        var net_price = unit_price;
        var start_date = item.row.start_date;
        var end_date = item.row.end_date;
        $('#prModalLabel').text(item.row.code + ' - ' + item.row.name + ' / ' + original_price + ' / ' + discount);
        if ($('#poswarehouse').val() == 3) {
            //$('#mini_comment').text('tesst');
            //console.log('abc: ' + $('#poswarehouse').val());
        }

        /*if (item.row.promotion && checkValidPromotionDate(start_date, end_date)) {
            $('#pdiscount').attr('readonly', true);
            $('#price_after_discount').attr('readonly', true);
            $('#pprice').attr('readonly', true);
            $('#prModalLabel').append('<br /><span style="color: green">' + lang.this_is_promotion_product + '</span>');
        } else {
            $('#pdiscount').attr('readonly', false);
            $('#price_after_discount').attr('readonly', false);
            $('#pprice').attr('readonly', false);
        }*/

        if (site.settings.tax1) {
            $('#ptax').select2('val', item.row.tax_rate);
            $('#old_tax').val(item.row.tax_rate);
            var item_discount = 0, ds = discount ? discount : '0';
            
            
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    item_discount = formatDecimal(parseFloat(((unit_price) * parseFloat(pds[0])) / 100), 4);
                } else {
                    item_discount = parseFloat(ds);
                }
            } else {
                item_discount = parseFloat(ds);
            }
            net_price -= item_discount;
            
            var pr_tax = item.row.tax_rate, pr_tax_val = 0;
            if (pr_tax !== null && pr_tax != 0) {
                $.each(tax_rates, function () {
                    if(this.id == pr_tax){
                        if (this.type == 1) {

                            if (positems[item_id].row.tax_method == 0) {
                                pr_tax_val = formatDecimal((((net_price) * parseFloat(this.rate)) / (100 + parseFloat(this.rate))), 4);
                                pr_tax_rate = formatDecimal(this.rate) + '%';
                                net_price -= pr_tax_val;
                            } else {
                                pr_tax_val = formatDecimal((((net_price) * parseFloat(this.rate)) / 100), 4);
                                pr_tax_rate = formatDecimal(this.rate) + '%';
                            }

                        } else if (this.type == 2) {

                            pr_tax_val = parseFloat(this.rate);
                            pr_tax_rate = this.rate;

                        }
                    }
                });
            }
        }
        if (site.settings.product_serial !== 0) {
            $('#pserial').val(row.children().children('.rserial').val());
        }
        var opt = '<p style="margin: 12px 0 0 0;">n/a</p>';
        //console.log(JSON.stringify(item.options));
        if(item.options !== false) {
            var o = 1;
            opt = $("<select id=\"poption\" name=\"poption\" class=\"form-control select\" />");
            $.each(item.options, function () {
                if(o == 1) {
                    if(product_option == '') { product_variant = this.id; } else { product_variant = product_option; }
                }
                $("<option />", {value: this.id, text: this.name}).appendTo(opt);
                o++;
            });
        } else {
            product_variant = 0;
        }
        if (item.units !== false) {
            uopt = $("<select id=\"punit\" name=\"punit\" class=\"form-control select\" />");
            $.each(item.units, function () {
                if(this.id == item.row.unit) {
                    $("<option />", {value: this.id, text: this.name, selected:true}).appendTo(uopt);
                } else {
                    $("<option />", {value: this.id, text: this.name}).appendTo(uopt);
                }
            });
        } else {
            uopt = '<p style="margin: 12px 0 0 0;">n/a</p>';
        }

        $('#poptions-div').html(opt);
        $('#punits-div').html(uopt);
        $('select.select').select2({minimumResultsForSearch: 7});
        $('#pquantity').val(qty);
        $('#old_qty').val(qty);
        
        $('#punit_price').val(formatDecimal(parseFloat(unit_price)+parseFloat(pr_tax_val)));
        $('#poption').select2('val', item.row.option);
        $('#old_price').val(unit_price);
        $('#row_id').val(row_id);
        $('#item_id').val(item_id);
        $('#pserial').val(row.children().children('.rserial').val());
        // if (is_promo == 1) {
        //     //console.log('xxx:  ' + original_price + ' - ' + unit_price);
        //     $('#pdiscount').val(original_price - unit_price);
        //     $('#pprice').val(original_price);
        //     //discount = original_price - unit_price;
        //     //ds = original_price - unit_price;
        //    // unit_price = original_price;
        //     //console.log('af: ' + ds);
        // } else {
        //     $('#pdiscount').val(discount);
        //     $('#pprice').val(unit_price);
        // }
        $('#pdiscount').val(discount);
        $('#pprice').val(unit_price);
        
        $('#net_price').val(formatMoney(net_price));
        $('#price_after_discount').val(net_price);
        $('#pro_tax').text(formatMoney(pr_tax_val));
        $('#icomment').val(comment);
        $('#icommentname').val(comment_name);
        $('#prModal').appendTo("body").modal('show');

    });

    $(document).on('click', '.comment', function () {
        var row = $(this).closest('tr');
        var row_id = row.attr('id');
        item_id = row.attr('data-item-id');
        item = positems[item_id];
        $('#irow_id').val(row_id);
        $('#icomment').val(item.row.comment);
        $('#iordered').val(item.row.ordered);
        $('#iordered').select2('val', item.row.ordered);
        $('#cmModalLabel').text(item.row.code + ' - ' + item.row.name);
        $('#cmModal').appendTo("body").modal('show');
    });

    $(document).on('click', '#editComment', function () {
        var row = $('#' + $('#irow_id').val());
        var item_id = row.attr('data-item-id');
        positems[item_id].row.order = parseFloat($('#iorders').val()),
        positems[item_id].row.comment = $('#icomment').val() ? $('#icomment').val().toUpperCase() : '';
        positems[item_id].row.comment_name = $('#icommentname').val() ? $('#icommentname').val().toUpperCase() : '';
        localStorage.setItem('positems', JSON.stringify(positems));
        $('#cmModal').modal('hide');
        loadItems();
        return;
    });

    $('#prModal').on('shown.bs.modal', function (e) {
        if($('#poption').select2('val') != '') {
            $('#poption').select2('val', product_variant);
            product_variant = 0;
        }        
        $(this).find('#price_after_discount').select().blur();
        $(this).find('#pdiscount').select().focus();
    });


    $(document).on('blur', '#price_after_discount', function () {
        var row = $('#' + $('#row_id').val());
        var item_id = row.attr('data-item-id');
        var unit_price = parseFloat($('#pprice').val());
        var item = positems[item_id];
        var price_after_discount = $('#price_after_discount').val() ? $('#price_after_discount').val() : '0';
        if (parseFloat(price_after_discount) > 0) {
            item_discount = parseFloat(price_after_discount);
            ds = unit_price - item_discount;
            $('#pdiscount').val(ds);
        }
    });

    $(document).on('blur', '#pprice, #price_after_discount, #ptax, #pdiscount', function () {
        var row = $('#' + $('#row_id').val());
        var item_id = row.attr('data-item-id');
        var unit_price = parseFloat($('#pprice').val());
        var item = positems[item_id];
        var ds = $('#pdiscount').val() ? $('#pdiscount').val() : '0';
        if (ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            if (!isNaN(pds[0])) {
                item_discount = parseFloat(((unit_price) * parseFloat(pds[0])) / 100);
            } else {
                item_discount = parseFloat(ds);
            }
        } else {
            item_discount = parseFloat(ds);
        }

        unit_price -= item_discount;

        $('#net_price').text(formatMoney(unit_price));
        //console.log(formatMoney(unit_price));
        $('#price_after_discount').val(unit_price);
    });

    $(document).on('change', '#punit', function () {
        var row = $('#' + $('#row_id').val());
        var item_id = row.attr('data-item-id');
        var item = positems[item_id];
        if (!is_numeric($('#pquantity').val()) || parseFloat($('#pquantity').val()) < 0) {
            $(this).val(old_row_qty);
            bootbox.alert(lang.unexpected_value);
            return;
        }
        var opt = $('#poption').val(), unit = $('#punit').val(), base_quantity = $('#pquantity').val(), aprice = 0;
        if(item.options !== false) {
            $.each(item.options, function () {
                if(this.id == opt && this.price != 0 && this.price != '' && this.price != null) {
                    aprice = parseFloat(this.price);
                }
            });
        }
        if(item.units && unit != positems[item_id].row.base_unit) {
            $.each(item.units, function(){
                if (this.id == unit) {
                    base_quantity = unitToBaseQty($('#pquantity').val(), this);
                    $('#pprice').val(formatDecimal(((parseFloat(item.row.base_unit_price+aprice))*unitToBaseQty(1, this)), 4)).change();
                }
            });
        } else {
            $('#pprice').val(formatDecimal(item.row.base_unit_price+aprice)).change();
        }
    });

    /* -----------------------
     * Edit Row Method
     ----------------------- */
    $(document).on('click', '#editItem', function () {
        var row = $('#' + $('#row_id').val());

        var item_id = row.attr('data-item-id'), new_pr_tax = $('#ptax').val(), new_pr_tax_rate = false;
        if (new_pr_tax) {
            $.each(tax_rates, function () {
                if (this.id == new_pr_tax) {
                    new_pr_tax_rate = this;
                }
            });
        }
        var price = parseFloat($('#pprice').val());
        if(item.options !== false) {
            var opt = $('#poption').val();
            $.each(item.options, function () {
                if(this.id == opt && this.price != 0 && this.price != '' && this.price != null) {
                    price = price-parseFloat(this.price);
                }
            });
        }
        if (site.settings.product_discount == 1 && $('#pdiscount').val()) {
            if(!is_valid_discount($('#pdiscount').val())) {
                bootbox.alert(lang.unexpected_value);
                return false;
            }
        }
        if (!is_numeric($('#pquantity').val()) || parseFloat($('#pquantity').val()) < 0) {
            $(this).val(old_row_qty);
            bootbox.alert(lang.unexpected_value);
            return;
        }
        var unit = $('#punit').val();
        var base_quantity = parseFloat($('#pquantity').val());
        //console.log(unit);
        if(unit != positems[item_id].row.base_unit) {
            $.each(positems[item_id].units, function(){
                if (this.id == unit) {
                    base_quantity = unitToBaseQty($('#pquantity').val(), this);
                    
                }
            });
        }
        positems[item_id].row.fup = 1,
        positems[item_id].row.qty = parseFloat($('#pquantity').val()),
        positems[item_id].row.base_quantity = parseFloat(base_quantity),
        positems[item_id].row.real_unit_price = price,
        positems[item_id].row.unit = unit,
        positems[item_id].row.tax_rate = new_pr_tax,
        positems[item_id].tax_rate = new_pr_tax_rate,
        positems[item_id].row.discount = $('#pdiscount').val() ? $('#pdiscount').val() : '',
        positems[item_id].row.option = $('#poption').val() ? $('#poption').val() : '',
        positems[item_id].row.serial = $('#pserial').val();
        positems[item_id].row.comment = $('#icomment').val().toUpperCase();
        positems[item_id].row.comment_name = $('#icommentname').val().toUpperCase();
        localStorage.setItem('positems', JSON.stringify(positems));
        //console.log(JSON.stringify(positems));
        $('#prModal').modal('hide');

        loadItems();
        return;
    });

    /* -----------------------
     * Edit Row Method Mini
     ----------------------- */
     $(document).on('click', '#editItemMini', function () {
        var row = $('#' + $('#row_id').val());
        var timeout = null;
        var item_id = row.attr('data-item-id'), new_pr_tax = $('#ptax').val(), new_pr_tax_rate = false;
        if (new_pr_tax) {
            $.each(tax_rates, function () {
                if (this.id == new_pr_tax) {
                    new_pr_tax_rate = this;
                }
            });
        }
        var price = parseFloat($('#pprice').val());
        if(item.options !== false) {
            var opt = $('#poption').val();
            $.each(item.options, function () {
                if(this.id == opt && this.price != 0 && this.price != '' && this.price != null) {
                    price = price-parseFloat(this.price);
                }
            });
        }
        if (site.settings.product_discount == 1 && $('#pdiscount').val()) {
            if(!is_valid_discount($('#pdiscount').val())) {
                bootbox.alert(lang.unexpected_value);
                return false;
            }
        }
        if (!is_numeric($('#pquantity').val()) || parseFloat($('#pquantity').val()) < 0) {
            $(this).val(old_row_qty);
            bootbox.alert(lang.unexpected_value);
            return;
        }
        var unit = $('#punit').val();
        var base_quantity = parseFloat($('#pquantity').val());
        //console.log(JSON.stringify(positems[item_id]));
        if(unit != positems[item_id].row.base_unit) {
            $.each(positems[item_id].units, function(){
                if (this.id == unit) {
                    base_quantity = unitToBaseQty($('#pquantity').val(), this);
                }
            });
        }
        positems[item_id].row.fup = 1,
        positems[item_id].row.qty = parseFloat($('#pquantity').val()),
        positems[item_id].row.base_quantity = parseFloat(base_quantity),
        positems[item_id].row.real_unit_price = price,
        positems[item_id].row.unit = unit,
        positems[item_id].row.tax_rate = new_pr_tax,
        positems[item_id].tax_rate = new_pr_tax_rate,
        positems[item_id].row.discount = $('#pdiscount').val() ? $('#pdiscount').val() : '',
        positems[item_id].row.option = $('#poption').val() ? $('#poption').val() : '',
        positems[item_id].row.serial = $('#pserial').val();
        localStorage.setItem('positems', JSON.stringify(positems));
        $('#prModalMini').modal('hide');
        loadItems();
        return;
    });

    /* -----------------------
     * Product option change
     ----------------------- */
    $(document).on('change', '#poption', function () {
        var row = $('#' + $('#row_id').val()), opt = $(this).val();
        var item_id = row.attr('data-item-id');
        var item = positems[item_id];
        var unit = $('#punit').val(), base_quantity = parseFloat($('#pquantity').val()), base_unit_price = item.row.base_unit_price;
        if(unit != positems[item_id].row.base_unit) {
            $.each(positems[item_id].units, function(){
                if (this.id == unit) {
                    base_unit_price = formatDecimal((parseFloat(item.row.base_unit_price)*(unitToBaseQty(1, this))), 4)
                    base_quantity = unitToBaseQty($('#pquantity').val(), this);
                }
            });
        }
        $('#pprice').val(parseFloat(base_unit_price)).trigger('change');
        $('#pquantity').select().focus();
        if(item.options !== false) {
            $.each(item.options, function () {
                if(this.id == opt && this.price != 0 && this.price != '' && this.price != null) {
                    $('#pprice').val(parseFloat(base_unit_price)+(parseFloat(this.price))).trigger('change');
                }
            });
        }
    });


     /* ------------------------------
     * Sell Gift Card modal
     ------------------------------- */
     $(document).on('click', '#sellGiftCard', function (e) {
        if (count == 1) {
            positems = {};
            if ($('#poswarehouse').val() && $('#poscustomer').val()) {
                $('#poscustomer').select2("readonly", true);
                $('#poswarehouse').select2("readonly", true);
            } else {
                bootbox.alert(lang.select_above);
                item = null;
                return false;
            }
        }
        $('.gcerror-con').hide();
        $('#gcModal').appendTo("body").modal('show');
        return false;
     });

     $('#gccustomer').select2({
        minimumInputLength: 1,
        ajax: {
            url: site.base_url+"customers/suggestions",
            dataType: 'json',
            quietMillis: 15,
            data: function (term, page) {
                return {
                    term: term,
                    limit: 10
                };
            },
            results: function (data, page) {
                if(data.results != null) {
                    return { results: data.results };
                } else {
                    return { results: [{id: '', text: 'No Match Found'}]};
                }
            }
        }
     });

     $('#genNo').click(function(){
        var no = generateCardNo();
        $(this).parent().parent('.input-group').children('input').val(no);
        return false;
     });
     $('.date').datetimepicker({format: site.dateFormats.js_sdate, fontAwesome: true, language: 'sma', todayBtn: 1, autoclose: 1, minView: 2 });
     $(document).on('click', '#addGiftCard', function (e) {
        var mid = (new Date).getTime(),
        gccode = $('#gccard_no').val(),
        gcname = $('#gcname').val(),
        gcvalue = $('#gcvalue').val(),
        gccustomer = $('#gccustomer').val(),
        gcexpiry = $('#gcexpiry').val() ? $('#gcexpiry').val() : '',
        gcprice = formatMoney($('#gcprice').val());
        if(gccode == '' || gcvalue == '' || gcprice == '' || gcvalue == 0 || gcprice == 0) {
            $('#gcerror').text('Please fill the required fields');
            $('.gcerror-con').show();
            return false;
        }

        var gc_data = new Array();
        gc_data[0] = gccode;
        gc_data[1] = gcvalue;
        gc_data[2] = gccustomer;
        gc_data[3] = gcexpiry;
        //if (typeof positems === "undefined") {
        //    var positems = {};
        //}

        $.ajax({
            type: 'get',
            url: site.base_url+'sales/sell_gift_card',
            dataType: "json",
            data: { gcdata: gc_data },
            success: function (data) {
                if(data.result === 'success') {
                    positems[mid] = {"id": mid, "item_id": mid, "label": gcname + ' (' + gccode + ')', "row": {"id": mid, "code": gccode, "name": gcname, "quantity": 1, "base_quantity": 1, "price": gcprice, "real_unit_price": gcprice, "tax_rate": 0, "qty": 1, "type": "manual", "discount": "0", "serial": "", "option":""}, "tax_rate": false, "options":false};
                    localStorage.setItem('positems', JSON.stringify(positems));
                    loadItems();
                    $('#gcModal').modal('hide');
                    $('#gccard_no').val('');
                    $('#gcvalue').val('');
                    $('#gcexpiry').val('');
                    $('#gcprice').val('');
                } else {
                    $('#gcerror').text(data.message);
                    $('.gcerror-con').show();
                }
            }
        });
        return false;
    });

    /* ------------------------------
     * Show manual item addition modal
     ------------------------------- */
     $(document).on('click', '#addManually', function (e) {
        if (count == 1) {
            positems = {};
            if ($('#poswarehouse').val() && $('#poscustomer').val()) {
                $('#poscustomer').select2("readonly", true);
                $('#poswarehouse').select2("readonly", true);
            } else {
                bootbox.alert(lang.select_above);
                item = null;
                return false;
            }
        }
        $('#mnet_price').val('0.00');
        $('#mpro_tax').text('0.00');
        $('#mModal').appendTo("body").modal('show');
        return false;
    });

     // qtthuan: hien thi modal giam gia
    $('#mModal').on('shown.bs.modal', function() {
        $(this).find('#order_discount_input').select().focus();
        $('#order_discount_input').bind('keypress', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                var ds = $('#order_discount_input').val();
                if (is_valid_discount(ds)) {
                    $('#posdiscount').val(ds);
                    localStorage.removeItem('posdiscount');
                    localStorage.setItem('posdiscount', ds);
                    loadItems();
                } else {
                    bootbox.alert(lang.unexpected_value);
                }
                $('#dsModal').modal('hide');
            }
        });
    });

     $(document).on('click', '#addItemManually', function (e) {
        var mid = (new Date).getTime(),
        mcode = $('#mcode').val(),
        mname = $('#mname').val(),
        mtax = parseInt($('#mtax').val()),
        mqty = parseFloat($('#mquantity').val()),
        mdiscount = $('#mdiscount').val() ? $('#mdiscount').val() : '0',
        unit_price = parseFloat($('#mprice').val()),
        mtax_rate = {};
        if (mcode && mname && mqty && unit_price) {
            $.each(tax_rates, function () {
                if (this.id == mtax) {
                    mtax_rate = this;
                }
            });

            positems[mid] = {"id": mid, "item_id": mid, "label": mname + ' (' + mcode + ')', "row": {"id": mid, "code": mcode, "name": mname, "quantity": mqty, "base_quantity": mqty, "price": unit_price, "unit_price": unit_price, "real_unit_price": unit_price, "tax_rate": mtax, "tax_method": 0, "qty": mqty, "type": "manual", "discount": mdiscount, "serial": "", "option":""}, "tax_rate": mtax_rate, 'units': false, "options":false};
            localStorage.setItem('positems', JSON.stringify(positems));
            loadItems();
        }
        $('#mModal').modal('hide');
        $('#mcode').val('');
        $('#mname').val('');
        $('#mtax').val('');
        $('#mquantity').val('');
        $('#mdiscount').val('');
        $('#mprice').val('');
        return false;
    });

    $(document).on('change', '#mprice, #mtax, #mdiscount', function () {
        var unit_price = parseFloat($('#mprice').val());
        var ds = $('#mdiscount').val() ? $('#mdiscount').val() : '0';
        if (ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            if (!isNaN(pds[0])) {
                item_discount = parseFloat(((unit_price) * parseFloat(pds[0])) / 100);
            } else {
                item_discount = parseFloat(ds);
            }
        } else {
            item_discount = parseFloat(ds);
        }
        unit_price -= item_discount;
        var pr_tax = $('#mtax').val(), item_tax_method = 0;
        var pr_tax_val = 0, pr_tax_rate = 0;
        if (pr_tax !== null && pr_tax != 0) {
            $.each(tax_rates, function () {
                if(this.id == pr_tax){
                    if (this.type == 1) {

                        if (item_tax_method == 0) {
                            pr_tax_val = formatDecimal(((unit_price) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                            pr_tax_rate = formatDecimal(this.rate) + '%';
                            unit_price -= pr_tax_val;
                        } else {
                            pr_tax_val = formatDecimal(((unit_price) * parseFloat(this.rate)) / 100);
                            pr_tax_rate = formatDecimal(this.rate) + '%';
                        }

                    } else if (this.type == 2) {

                        pr_tax_val = parseFloat(this.rate);
                        pr_tax_rate = this.rate;

                    }
                }
            });
        }

        $('#mnet_price').val(formatMoney(unit_price));
        $('#mpro_tax').text(formatMoney(pr_tax_val));
    });

    function makeDelay(ms) {
        var timer = 0;
        return function(callback){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    };

    // var delay = (function(){
    //     var timer = 0;
    //     return function(callback, ms){
    //       clearTimeout (timer);
    //       timer = setTimeout(callback, ms);
    //     };
    //   })();

    /* --------------------------
     * Edit Row Quantity Method
    qtthuan: Thay đổi số lượng
    --------------------------- */
    var old_row_qty;
    var timeout = null;
    $(document).on("focus", '.rquantity', function () {
        old_row_qty = $(this).val();
    }).on("keyup", '.rquantity', function () {
        var row = $(this).closest('tr');
            if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
                $(this).val(old_row_qty);
                bootbox.alert(lang.unexpected_value);
                return;
            }
            var new_qty = parseFloat($(this).val()),
            item_id = row.attr('data-item-id');
            positems[item_id].row.base_quantity = new_qty;
            if(positems[item_id].row.unit != positems[item_id].row.base_unit) {
                $.each(positems[item_id].units, function(){
                    if (this.id == positems[item_id].row.unit) {
                        positems[item_id].row.base_quantity = unitToBaseQty(new_qty, this);
                    }
                });
            }
            positems[item_id].row.qty = new_qty;
            localStorage.setItem('positems', JSON.stringify(positems));
            clearTimeout(timeout)
            timeout = setTimeout(function() {        
                loadItems();
            }, 600)
            
        
        
    });


// end ready function
});

/* -----------------------
 * Load all items
 ----------------------- */

//localStorage.clear();
function loadItems() {

    if (localStorage.getItem('positems')) {
        total = 0;
        total_extra = 0;
        total_with_no_points = 0;
        percent_detail = '';
        order_discount_percent_for_return_sale = 0;
        customer_group_id = $("#customer_group_id").val();
        count = 1;
        an = 1;
        product_tax = 0;
        invoice_tax = 0;
        product_discount = 0;
        order_discount = 0;
        total_discount = 0;
        order_data = {};
        bill_data = {};

        $("#posTable tbody").empty();
        var time = ((new Date).getTime())/1000;
        if (pos_settings.remote_printing != 1) {
            store_name = (biller && biller.company != '-' ? biller.company : biller.name);
            order_data.store_name = store_name;
            bill_data.store_name = store_name;
            order_data.header = "\n"+lang.order+"\n\n";
            bill_data.header = "\n"+lang.bill+"\n\n";

            var pos_customer = 'C: '+$('#select2-chosen-1').text()+ "\n";
            var hr = 'R: '+$('#reference_note').val()+ "\n";
            var user = 'U: '+username+ "\n";
            var pos_curr_time = 'T: '+date(site.dateFormats.php_ldate, time)+ "\n";
            var ob_info = pos_customer+hr+user+pos_curr_time+ "\n";
            order_data.info = ob_info;
            bill_data.info = ob_info;
            var o_items = '';
            var b_items = '';

        } else {
            $("#order_span").empty(); $("#bill_span").empty();
            var styles = '<style>table, th, td { border-collapse:collapse; border-bottom: 1px solid #CCC; } .no-border { border: 0; } .bold { font-weight: bold; }</style>';
            var pos_head1 = '<span style="text-align:center;"><h4 style="text-transform: uppercase;">'+site.settings.site_name+'</h4><h5 style="text-transform: uppercase;">';
            var customer_name = '';
            if ($('#select2-chosen-1').text().indexOf('#') > -1) {
                customer_name = $('#select2-chosen-1').text().split('#')[0] + '<br>' + $('#select2-chosen-1').text().split('#')[1];
            } else {
                customer_name = $('#select2-chosen-1').text();
            }
            var pos_head2 = '</h5><p class="text-left">'+customer_name+'<br>'+date(site.dateFormats.php_ldate, time)+'</p></span>';
            $("#order_span").prepend(styles + pos_head1+' '+lang.order+' '+pos_head2);
            $("#bill_span").prepend(styles + pos_head1+' '+lang.bill+' '+pos_head2);
            $("#order-table").empty(); $("#bill-table").empty();
        }
        positems = JSON.parse(localStorage.getItem('positems'));

        if (pos_settings.item_order == 1) {
            sortedItems = _.sortBy(positems, function(o) { return [parseInt(o.category), parseInt(o.order)]; });
        } else if (site.settings.item_addition == 1) {
            sortedItems = _.sortBy(positems, function(o) { return [parseInt(o.order)]; });
        } else {
            sortedItems = positems;
        }
        var category = 0, print_cate = false;
        // var itn = parseInt(Object.keys(sortedItems).length);
        //console.log(JSON.stringify(sortedItems));
        $.each(sortedItems, function () {
            var item = this;            
            var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
            positems[item_id] = item;
            item.order = item.order ? item.order : new Date().getTime();
            var product_id = item.row.id, item_image = item.row.image;
            var item_type = item.row.type, combo_items = item.combo_items;
            var is_promo = item.row.promotion, item_original_price = item.row.original_price, item_original_price_for_suspend = item.row.original_price;
            var start_date = item.row.start_date, end_date = item.row.end_date;
            var item_price = item.row.price, item_qty = item.row.qty, item_aqty = item.row.quantity; 
            var item_tax_method = item.row.tax_method, item_ds = item.row.discount, item_discount = 0;
            var item_option = item.row.option, item_code = item.row.code, item_serial = item.row.serial;
            var item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;"), item_name_en = item.row.name_en;
            var product_unit = item.row.unit, base_quantity = item.row.base_quantity;
            var unit_price = item.row.real_unit_price, item_product_details = item.row.product_details;
            var item_comment = item.row.comment ? item.row.comment : '';
            var item_comment_name = item.row.comment_name ? item.row.comment_name : '';
            var item_ordered = item.row.ordered ? item.row.ordered : 0;
            var discount_class = '';
            var item_name_en_display = item_name_en;

            if(!item_image) {
                item_image = 'no_image.png';
            }

            if($(".product_name_vi").is(":visible")) {
                item_name_en_display = '';
            }
            
            if(item.units && item.row.fup != 1 && product_unit != item.row.base_unit) {
                $.each(item.units, function() {
                    if (this.id == product_unit) {
                        base_quantity = formatDecimal(unitToBaseQty(item.row.qty, this), 4);
                        unit_price = formatDecimal((parseFloat(item.row.base_unit_price)*(unitToBaseQty(1, this))), 4);
                    }
                });
            }
            if(item.options !== false) {
                $.each(item.options, function () {
                    if(this.id == item.row.option && this.price != 0 && this.price != '' && this.price != null) {
                        //console.log('item.row.option_big_size: ' + item.row.option_big_size);
                        if (item.row.big_size_price && item.row.big_size_price != 0) {
                            item_price = parseFloat(item.row.big_size_price);
                        } else {
                            item_price = parseFloat(unit_price)+(parseFloat(this.price));
                        }
                        unit_price = item_price;
                        item_original_price = parseFloat(item_original_price) + parseFloat(this.price);
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
                 item_discount = formatDecimal(ds);
            }
            product_discount += formatDecimal(item_discount * item_qty);
            if (item_discount > 0) {
                discount_class = ' success';
            }

            unit_price = formatDecimal(unit_price-item_discount);
            var pr_tax = item.tax_rate;
            var pr_tax_val = 0, pr_tax_rate = 0;
            if (site.settings.tax1 == 1) {
                if (pr_tax !== false && pr_tax != 0) {
                    if (pr_tax.type == 1) {

                        if (item_tax_method == '0') {
                            pr_tax_val = formatDecimal(((unit_price) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate)), 4);
                            pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                        } else {
                            pr_tax_val = formatDecimal(((unit_price) * parseFloat(pr_tax.rate)) / 100, 4);
                            pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                        }

                    } else if (pr_tax.type == 2) {

                        pr_tax_val = formatDecimal(pr_tax.rate);
                        pr_tax_rate = pr_tax.rate;

                    }
                    product_tax += pr_tax_val * item_qty;
                }
            }
            item_price = item_tax_method == 0 ? formatDecimal((unit_price-pr_tax_val), 4) : formatDecimal(unit_price);
            unit_price = formatDecimal((unit_price+item_discount), 4);
            var sel_opt = '';
            var sel_opt_promo_price = 0;
            $.each(item.options, function () {
                if(this.id == item_option) {
                    sel_opt = this.name;
                    sel_opt_promo_price = this.variant_promo_price;
                    sel_opt_price = parseFloat(this.price);
                }
            });

            //console.log('is_promo: ' + is_promo + ' - date valid: ' + checkValidPromotionDate(start_date, end_date))

            

            if (pos_settings.item_order == 1 && category != item.row.category_id) {
                category = item.row.category_id;
                print_cate = true;
                var newTh = $('<tr></tr>');
                newTh.html('<td colspan="100%"><strong>'+item.row.category_name+'</strong></td>');
                newTh.appendTo("#posTable");
            } else {
                print_cate = false;
            }
            if ($('#leftdiv').attr('class') == 'mini_leftdiv') {
                item_code = '';
            } else {
                item_code += '- ';
            }
            if (item_name.indexOf('_') != -1) {
                item_name = item_name.substring(2, item_name.length);
            }
            var row_no = setTimeout((new Date).getTime(), 500);
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + discount_class + '" data-item-id="' + item_id + '"></tr>');
            tr_html = '<td>' +
                        '<input name="product_id[]" type="hidden" class="rid" value="' + product_id + '">' +
                        '<input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '">' +
                        '<input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '">' +
                        '<input name="product_image[]" type="hidden" class="rcode" value="' + item_image + '">' +
                        '<input name="product_name[]" type="hidden" class="rname" value="' + item_name + '">' +
                        '<input name="product_name_en[]" type="hidden" class="rname" value="' + item_name_en + '">' +
                        '<input name="product_option[]" type="hidden" class="roption" value="' + item_option + '">' +
                        '<input name="product_comment[]" type="hidden" class="rcomment" value="' + item_comment + '">' +
                        '<input name="product_comment_name[]" type="hidden" class="rcomment" value="' + item_comment_name + '">' +
                        '<span class="sname" id="name_' + row_no + '"><img src="' + site.url + '/assets/uploads/' + item_image + '" width="40">' + item_code + ' ' + item_name +(sel_opt != '' ? ' ('+sel_opt+')' : '') + '</span>' +
                        '<br /><span class="sname_en" id="nam_en_' + row_no + '">' + (item_name_en_display ? '# ' + item_name_en_display : '') + '</span>' +
                        (item_comment ? '<span class="scomment" style="font-weight: bold;"> (' + item_comment + ')  </span>' : '') +
                        (item_comment_name ? '<span class="scomment" style="font-weight: bold;"> - ' + item_comment_name + '  </span>' : '') +
                        '<span class="lb"></span>' +
                '</td>';

            tr_html += '<td class="text-center">' +
                        '<i class="pull-center fa fa-edit fa-3x fa-bx tip pointer edit" id="' + row_no + '" data-item="' + item_id + '" title="Cập nhật Size/Giá" style="cursor:pointer;"></i>' +
                '</td>';

               
            tr_html += '<td class="text-right">';
            if (site.settings.product_serial == 1) {
                tr_html += '<input class="form-control input-sm rserial" name="serial[]" type="hidden" id="serial_' + row_no + '" value="'+item_serial+'">';
            }
            if (site.settings.product_discount == 1) {
                tr_html += '<input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item_ds + '">';
            }
            if (site.settings.tax1 == 1) {
                tr_html += '<input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><input type="hidden" class="sproduct_tax" id="sproduct_tax_' + row_no + '" value="' + formatMoney(pr_tax_val * item_qty) + '">';
            }
            
            if (item_discount > 0) {
                //console.log('11111');
                //console.log(JSON.stringify(sortedItems));
                var display_price = '<span style="font-weight: bold">' + formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val)) + '</span>(<span style="text-decoration: line-through">' + unit_price + '</span>)';
                tr_html += '<input name="is_promo[]" type="hidden" class="is_promo" value="' + (is_promo && checkValidPromotionDate(start_date, end_date) ? is_promo : 0) + '">' +
                            '<input name="promo_original_price[]" type="hidden" class="roprice" value="' + item_original_price + '">' + 
                            '<input name="promo_original_price_for_suspend[]" type="hidden" class="roprice" value="' + item_original_price_for_suspend + '">' + 
                            '<input class="rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + item_price + '">' +
                            '<input class="ruprice" name="unit_price[]" type="hidden" value="' + unit_price + '">' + 
                            '<input class="realuprice" name="real_unit_price[]" type="hidden" value="' + item.row.real_unit_price + '">' + 
                            '<input class="no_points" name="no_points[]" type="hidden" value="' + item.no_points + '"><span class="text-right sprice" id="sprice_' + row_no + '">' + display_price + '</span></td>';
                    total_with_no_points += item_price * item_qty;
            } else {
                
                if (sel_opt_promo_price > 0 && !is_promo) { // Giá KM trên một size cụ thể
                    //console.log('222222');
                    //console.log(JSON.stringify(sortedItems));
                    display_price = '<span style="font-weight: bold">' + formatMoney(parseFloat(sel_opt_promo_price) + parseFloat(pr_tax_val)) + '</span>';
                    display_price += '(<span style="text-decoration: line-through">' + formatMoney(item_price) + '</span>)';
                    tr_html += '<input name="is_promo[]" type="hidden" class="is_promo" value="1">';
                    tr_html += '<input name="promo_original_price[]" type="hidden" class="roprice" value="' + item_price + '">';
                    tr_html += '<input name="promo_original_price_for_suspend[]" type="hidden" class="roprice" value="' + item_original_price_for_suspend + '">'; 
                    tr_html += '<input class="rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + sel_opt_promo_price + '">';
                    tr_html += '<input class="ruprice" name="unit_price[]" type="hidden" value="' + sel_opt_promo_price + '">';
                    tr_html += '<input class="realuprice" name="real_unit_price[]" type="hidden" value="' + sel_opt_promo_price + '">';
                    tr_html += '<input class="no_points" name="no_points[]" type="hidden" value="1"><span class="text-right sprice" id="sprice_' + row_no + '">' + display_price + '</span></td>';
                    //console.log('ggg: ' + item_price);
                    total_with_no_points += sel_opt_promo_price * item_qty;
                }
                else if (is_promo && checkValidPromotionDate(start_date, end_date) && customer_group_id != 4) { // Loại nhóm BAEMIN
                    //console.log('33333');
                    //console.log(JSON.stringify(sortedItems));
                    display_price = '<span style="font-weight: bold">' + formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val)) + '</span>';
                    display_price += '(<span style="text-decoration: line-through">' + formatMoney(item_original_price) + '</span>)';
                    tr_html += '<input name="is_promo[]" type="hidden" class="is_promo" value="' + (is_promo && checkValidPromotionDate(start_date, end_date) ? is_promo : 0) + '">';
                    tr_html += '<input name="promo_original_price[]" type="hidden" class="roprice" value="' + item_original_price + '">';
                    tr_html += '<input name="promo_original_price_for_suspend[]" type="hidden" class="roprice" value="' + item_original_price_for_suspend + '">';
                    tr_html += '<input class="rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + item_price + '">';
                    tr_html += '<input class="ruprice" name="unit_price[]" type="hidden" value="' + unit_price + '">';
                    tr_html += '<input class="realuprice" name="real_unit_price[]" type="hidden" value="' + item.row.real_unit_price + '">';
                    tr_html += '<input class="no_points" name="no_points[]" type="hidden" value="' + item.no_points + '"><span class="text-right sprice" id="sprice_' + row_no + '">' + display_price + '</span></td>';
                    total_with_no_points += item_price * item_qty;
                } else {
                    //console.log('444444');
                    //console.log(JSON.stringify(sortedItems));
                    display_price = formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val));
                    tr_html += '<input name="is_promo[]" type="hidden" class="is_promo" value="' + (is_promo && checkValidPromotionDate(start_date, end_date) ? is_promo : 0) + '">';
                    tr_html += '<input name="promo_original_price[]" type="hidden" class="roprice" value="' + item_original_price + '">';
                    tr_html += '<input name="promo_original_price_for_suspend[]" type="hidden" class="roprice" value="' + item_original_price_for_suspend + '">';
                    tr_html += '<input class="rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + item_price + '">';
                    tr_html += '<input class="ruprice" name="unit_price[]" type="hidden" value="' + unit_price + '">';
                    tr_html += '<input class="realuprice" name="real_unit_price[]" type="hidden" value="' + item.row.real_unit_price + '">';
                    tr_html += '<input class="no_points" name="no_points[]" type="hidden" value="' + item.no_points + '"><span class="text-right sprice" id="sprice_' + row_no + '">' + display_price + '</span></td>';
                }
                
                
                
            }
           
            //console.log('total_with_no_point: ' + total_with_no_points);
            tr_html += '<td><input class="form-control input-sm kb-pad text-center rquantity" tabindex="'+((site.settings.set_focus == 1) ? an : (an+1))+'" name="quantity[]" type="text" value="' + formatQuantity2(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"><input name="product_unit[]" type="hidden" class="runit" value="' + product_unit + '"><input name="product_base_quantity[]" type="hidden" class="rbase_quantity" value="' + base_quantity + '"></td>';
            if (sel_opt_promo_price > 0 && !is_promo) {
                tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(sel_opt_promo_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</span></td>';
            } else {
                tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</span></td>';
            }
            tr_html += '<td class="text-center"><i class="fa fa-times fa-2x tip pointer posdel" id="' + row_no + '" title="Xóa" style="cursor:pointer;"></i></td>';
            newTr.html(tr_html);
            if (pos_settings.item_order == 1) {
                newTr.appendTo("#posTable");
            } else {
                newTr.prependTo("#posTable");
            }
            if (sel_opt_promo_price > 0 && !is_promo) {
                total += formatDecimal(((parseFloat(sel_opt_promo_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)), 4);
            } else {
                total += formatDecimal(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)), 4);
            }
            if (item_discount == 0 || sel_opt_promo_price > 0) {
                //console.log('nnn: ' + item_price + ' - ');
                total_extra += formatDecimal(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)), 4);
            }
            //console.log('total_extra: ' + total_extra);
            count += parseFloat(item_qty);
            an++;
            out_of_stock_items = '';
            if (item_type == 'standard' && item.options !== false) {
                
                $('#has_out_of_stock_products').val(0);
                //console.log(JSON.stringify(item.options));
                $.each(item.options, function () {
                    if(this.id == item_option && base_quantity > this.total_quantity) {
                        if (!$('#out_of_stock_items').text().includes(item_code + ' (' + this.name + ')')) {
                            $('#out_of_stock_items').append(item_code + ' (' + this.name + '), ');
                        }
                        $('#has_out_of_stock_products').val(1);
                        $('#row_' + row_no).addClass('danger');
                    }
                });
            } else if(item_type == 'standard' && base_quantity > item_aqty) {
                $('#row_' + row_no).addClass('danger');
            } else if (item_type == 'combo') {
                if(combo_items === false) {
                    $('#row_' + row_no).addClass('danger');
                } else {
                    $.each(combo_items, function(){
                        if(parseFloat(this.quantity) < (parseFloat(this.qty)*base_quantity) && this.type == 'standard') {
                            $('#row_' + row_no).addClass('danger');
                        }
                    });
                }
            }

            var comments = item_comment.split(/\r?\n/g);
            if (pos_settings.remote_printing != 1) {

                b_items += product_name("#"+(an-1)+" "+ item_code + " - " + item_name) + "\n";
                for (var i = 0, len = comments.length; i < len; i++) {
                    b_items += (comments[i].length > 0 ? "   * "+comments[i]+"\n" : "");
                }
                b_items += printLine("   "+formatDecimal(item_qty) + " x " + formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val))+": "+ formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)))) + "\n";
                o_items += printLine(product_name("#"+(an-1)+" "+ item_code + " - " + item_name) + ": [ "+ (item_ordered != 0 ? 'xxxx' : formatDecimal(item_qty))) + " ]\n";
                for (var i = 0, len = comments.length; i < len; i++) {
                    o_items += (comments[i].length > 0 ? "   * "+comments[i]+"\n" : "");
                }
                o_items += "\n";

            } else {
                if (pos_settings.item_order == 1 && print_cate) {
                    var bprTh = $('<tr></tr>');
                    bprTh.html('<td colspan="100%" class="no-border"><strong>'+item.row.category_name+'</strong></td>');
                    var oprTh = $('<tr></tr>');
                    oprTh.html('<td colspan="100%" class="no-border"><strong>'+item.row.category_name+'</strong></td>');
                    $("#order-table").append(oprTh);
                    $("#bill-table").append(bprTh);
                }
                var bprTr = '<tr class="row_' + item_id + '" data-item-id="' + item_id + '"><td colspan="5" class="no-border">'+(an-1)+'&#8594;'+' '  + item_name + '';
                for (var i = 0, len = comments.length; i < len; i++) {
                    bprTr += (comments[i] ? '<br> <b>*</b> <small>'+comments[i]+'</small>' : '');
                }
                bprTr += '</td></tr>';
                bprTr += '<tr class="row_' + item_id + '" data-item-id="' + item_id + '"><td colspan="2">'+item_code+'</td><td>'+formatDecimal(item_qty)+'</td><td>'+formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val)) + (item_discount != 0 ? '<del>('+formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val) + item_discount)+'</del>)' : '') + '</td><td style="text-align:right;">'+ formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) +'</td></tr>';
                var oprTr = '<tr class="row_' + item_id + '" data-item-id="' + item_id + '"><td>#'+(an-1)+' ' + item_code + " - " + item_name + '';
                for (var i = 0, len = comments.length; i < len; i++) {
                    oprTr += (comments[i] ? '<br> <b>*</b> <small>'+comments[i]+'</small>' : '');
                }
                oprTr += '</td><td>[ ' + (item_ordered != 0 ? 'xxxx' : formatDecimal(item_qty)) +' ]</td></tr>';
                $("#order-table").append(oprTr);
                $("#bill-table").append(bprTr);
            }
        });  // End Foreach loop

        // Order level discount calculations
        if (posdiscount = localStorage.getItem('posdiscount')) {
            var ds = posdiscount;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {            
                    order_discount = formatDecimal((parseFloat(((total - total_with_no_points) * parseFloat(pds[0])) / 100)), 4);
                    percent_detail = '= ' + formatMoney(total - total_with_no_points) + ' x ' + parseFloat((pds[0]) / 100);
                } else {
                    order_discount = parseFloat(ds);
                }
            } else {
                order_discount = parseFloat(ds);
                order_discount_percent_for_return_sale = Number((parseFloat(ds) / parseFloat(total_extra - total_with_no_points)) * 100).toFixed(4);
                //console.log('order_discount_percent_for_return_sale: ' + order_discount_percent_for_return_sale);
            }
            //total_discount += parseFloat(order_discount);
        }

        // Order level tax calculations
        if (site.settings.tax2 != 0) {
            if (postax2 = localStorage.getItem('postax2')) {
                $.each(tax_rates, function () {
                    if (this.id == postax2) {
                        if (this.type == 2) {
                            invoice_tax = formatDecimal(this.rate);
                        }
                        if (this.type == 1) {
                            invoice_tax = formatDecimal((((total - order_discount) * this.rate) / 100), 4);
                        }
                    }
                });
            }
        }
        //console.log($('#leftdiv').attr('class') + '/');
        if ($('#leftdiv').attr('class') == 'mini_leftdiv') {
            $('.sname').css("font-size", "14px");
            $('.scomment').css("font-size", "14px");
            //console.log('xxxx');
        }
        //$('.comment').css("font-size", "31px");
        //$('.edit').css("font-size", "31px");

        total = formatDecimal(total);
        product_tax = formatDecimal(product_tax);
        total_discount = formatDecimal(order_discount + product_discount);
        // Totals calculations after item addition
        var gtotal = parseFloat(((total + invoice_tax) - order_discount - preturn) + parseFloat(shipping));

        $('#titems').text((an - 1) + ' (' + formatQty(parseFloat(count) - 1) + ')');
        $('#total_items').val((parseFloat(count) - 1));
        $('#tds').text('('+formatMoney(product_discount)+') ' + formatMoney(order_discount));
        $('#tds_extra').text(percent_detail);
        if (site.settings.tax2 != 0) {
            $('#ttax2').text(formatMoney(invoice_tax));
        }
        $('#tship').text(parseFloat(shipping) > 0 ? formatMoney(shipping) : '');
        $('#treturn').text(parseFloat(preturn) > 0 ? formatMoney(preturn) : '');
        console.log('total: ' + total + ' - gtotal: ' + gtotal);
        $('#total').text(formatMoney(total));
        $('#gtotal').text(formatMoney(gtotal));
        $('#order_discount_percent_for_return_sale').val(parseFloat(order_discount_percent_for_return_sale));
        if (pos_settings.remote_printing != 1) {

            order_data.items = o_items;
            bill_data.items = b_items;
            var b_totals = '';
            b_totals += printLine(lang.total+': '+ formatMoney(total)) +"\n";
            if(order_discount > 0 || product_discount > 0) {
                b_totals += printLine(lang.discount+': '+ formatMoney(order_discount+product_discount)) +"\n";
            }
            if (site.settings.tax2 != 0 && invoice_tax != 0) {
                b_totals += printLine(lang.order_tax+': '+ formatMoney(invoice_tax)) +"\n";
            }
            b_totals += printLine(lang.grand_total+': '+ formatMoney(gtotal)) +"\n";
            if(pos_settings.rounding != 0) {
                round_total = roundNumber(gtotal, parseInt(pos_settings.rounding));
                var rounding = formatDecimal(round_total - gtotal);
                b_totals += printLine(lang.rounding+': '+ formatMoney(rounding)) +"\n";
                b_totals += printLine(lang.total_payable+': '+ formatMoney(round_total)) +"\n";
            }
            b_totals += "\n"+ lang.items+': '+ (an - 1) + ' (' + (parseFloat(count) - 1) + ')' +"\n";
            bill_data.totals = b_totals;
            bill_data.footer = "\n"+ lang.merchant_copy+"\n";

        } else {
            var bill_totals = '';
            bill_totals += '<tr class="bold"><td width="25%">&nbsp;</td><td>'+lang.items+'</td><td style="text-align:right;">'+ (parseFloat(count) - 1) + '</td></tr>';
            bill_totals += '<tr class="bold"><td width="25%">&nbsp;</td><td>'+lang.total+'</td><td style="text-align:right;">'+formatMoney(total)+'</td></tr>';

            //$('#tship').text(parseFloat(shipping) > 0 ? formatMoney(shipping) : '');
            //$('#treturn').text(parseFloat(preturn) > 0 ? formatMoney(preturn) : '');

            if (shipping > 0) {
                bill_totals += '<tr class="bold"><td width="25%">&nbsp;</td><td>'+lang.shipping+'</td><td style="text-align:right;">'+formatMoney(shipping)+'</td></tr>';
            }

            if (preturn > 0) {
                bill_totals += '<tr class="bold"><td width="25%">&nbsp;</td><td>'+lang.return_amount+'</td><td style="text-align:right;">-'+formatMoney(preturn)+'</td></tr>';
            }

            if(order_discount > 0) {
                bill_totals += '<tr class="bold"><td width="25%">&nbsp;</td><td>'+lang.discount+'</td><td style="text-align:right;">-'+formatMoney(order_discount)+'</td></tr>';
            }
            if (site.settings.tax2 != 0 && invoice_tax != 0) {
                bill_totals += '<tr class="bold"><td width="25%">&nbsp;</td><td>'+lang.order_tax+'</td><td style="text-align:right;">'+formatMoney(invoice_tax)+'</td></tr>';
            }
            bill_totals += '<tr class="bold"><td width="25%">&nbsp;</td><td>'+lang.grand_total+'</td><td style="text-align:right;">'+formatMoney(gtotal)+'</td></tr>';
            // if(pos_settings.rounding != 0) {
            //     round_total = roundNumber(gtotal, parseInt(pos_settings.rounding));
            //     var rounding = formatDecimal(round_total - gtotal);
            //     bill_totals += '<tr class="bold"><td>'+lang.rounding+'</td><td style="text-align:right;">'+formatMoney(rounding)+'</td></tr>';
            //     bill_totals += '<tr class="bold"><td>'+lang.total_payable+'</td><td style="text-align:right;">'+formatMoney(round_total)+'</td></tr>';
            // }
            $('#bill-total-table').empty();
            $('#bill-total-table').append(bill_totals);
            $('#bill_footer').append('<p class="text-center"><br>'+lang.footer_thanks+'</p>');
        }
        if(count > 1) {
            $('#poscustomer').select2("readonly", true);
            $('#poswarehouse').select2("readonly", true);
        } else {
            $('#poscustomer').select2("readonly", false);
            $('#poswarehouse').select2("readonly", false);
        }
        if (KB) { display_keyboards(); }
        // qtthuan: focus
        if (site.settings.set_focus == 1 && (localStorage.getItem('poswarehouse') == 3) 
            || (site.settings.set_focus == 1 && $("#poswarehouse").is(":hidden") && $("#poswarehouse").val() == 3)) { 
            // Focus vào ô điều chỉnh số lượng sau khi scan sản phẩm (Kho Tiệm Nước)
            $('#add_item').attr('tabindex', an);
            $('[tabindex='+(an-1)+']').focus().select();
        } else { // Focus vào ô scan mã vạch (Kho Ba-Ni)
            $('#add_item').attr('tabindex', 1);
            $('#add_item').focus();
        }
    }
}

function printLine(str) {
    var size = pos_settings.char_per_line;
    var len = str.length;
    var res = str.split(":");
    var newd = res[0];
    for(i=1; i<(size-len); i++) {
        newd += " ";
    }
    newd += res[1];
    return newd;
}

/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */

 function add_invoice_item(item) {
    //console.log(JSON.stringify(item));



    /*
    if ($this->sma->isPromo($row)) {
                    $row->original_price = $row->price;
                    $row->price = $row->promo_price;
                } elseif ($customer->price_group_id) {
                    if ($pr_group_price = $this->site->getProductGroupPrice($row->id, $customer->price_group_id)) {
                        $row->original_price = $row->price;
                        $row->price = $pr_group_price->price;
                    }
                } elseif ($warehouse->price_group_id) {
                    if ($pr_group_price = $this->site->getProductGroupPrice($row->id, $warehouse->price_group_id)) {
                        $row->original_price = $row->price;
                        $row->price = $pr_group_price->price;
                    }
                }*/
    if (count == 1) {
        positems = {};
        if ($('#poswarehouse').val() && $('#poscustomer').val()) {
            $('#poscustomer').select2("readonly", true);
            $('#poswarehouse').select2("readonly", true);
        } else {
            bootbox.alert(lang.select_above);
            item = null;
            return;
        }
    }
    if (item == null)
        return;

    var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
    if (positems[item_id]) {
 
        var new_qty = parseFloat(positems[item_id].row.qty) + 1;
        positems[item_id].row.base_quantity = new_qty;
        if(positems[item_id].row.unit != positems[item_id].row.base_unit) {
            $.each(positems[item_id].units, function(){
                if (this.id == positems[item_id].row.unit) {
                    positems[item_id].row.base_quantity = unitToBaseQty(new_qty, this);
                }
            });
        }
        positems[item_id].row.qty = new_qty;

    } else {
        positems[item_id] = item;
    }
    positems[item_id].order = new Date().getTime();
    localStorage.setItem('positems', JSON.stringify(positems));
    //console.log(JSON.stringify(positems));
    loadItems();
    return true;
 }


 if (typeof (Storage) === "undefined") {
    $(window).bind('beforeunload', function (e) {
        if (count > 1) {
            var message = "You will loss data!";
            return message;
        }
    });
 }

 function display_keyboards() {

    $('.kb-text').keyboard({
        autoAccept: true,
        alwaysOpen: false,
        openOn: 'focus',
        usePreview: false,
        layout: 'custom',
        //layout: 'qwerty',
        display: {
            'bksp': "\u2190",
            'accept': 'return',
            'default': 'ABC',
            'meta1': '123',
            'meta2': '#+='
        },
        customLayout: {
            'default': [
            'q w e r t y u i o p {bksp}',
            'a s d f g h j k l {enter}',
            '{s} z x c v b n m , . {s}',
            '{meta1} {space} {cancel} {accept}'
            ],
            'shift': [
            'Q W E R T Y U I O P {bksp}',
            'A S D F G H J K L {enter}',
            '{s} Z X C V B N M / ? {s}',
            '{meta1} {space} {meta1} {accept}'
            ],
            'meta1': [
            '1 2 3 4 5 6 7 8 9 0 {bksp}',
            '- / : ; ( ) \u20ac & @ {enter}',
            '{meta2} . , ? ! \' " {meta2}',
            '{default} {space} {default} {accept}'
            ],
            'meta2': [
            '[ ] { } # % ^ * + = {bksp}',
            '_ \\ | &lt; &gt; $ \u00a3 \u00a5 {enter}',
            '{meta1} ~ . , ? ! \' " {meta1}',
            '{default} {space} {default} {accept}'
            ]}
        });
    $('.kb-pad').keyboard({
        restrictInput: true,
        preventPaste: true,
        autoAccept: true,
        alwaysOpen: false,
        openOn: 'click',
        usePreview: false,
        layout: 'custom',
        display: {
            'b': '\u2190:Backspace',
        },
        customLayout: {
            'default': [
            '1 2 3 {b}',
            '4 5 6 . {clear}',
            '7 8 9 0 %',
            '{accept} {cancel}'
            ]
        }
    });
    var cc_key = (site.settings.decimals_sep == ',' ? ',' : '{clear}');
    $('.kb-pad1').keyboard({
        restrictInput: true,
        preventPaste: true,
        autoAccept: true,
        alwaysOpen: false,
        openOn: 'click',
        usePreview: false,
        layout: 'custom',
        display: {
            'b': '\u2190:Backspace',
        },
        customLayout: {
            'default': [
            '1 2 3 {b}',
            '4 5 6 . '+cc_key,
            '7 8 9 0 %',
            '{accept} {cancel}'
            ]
        }
    });

 }

/*$(window).bind('beforeunload', function(e) {
    if(count > 1){
    var msg = 'You will loss the sale data.';
        (e || window.event).returnValue = msg;
        return msg;
    }
});
*/
if(site.settings.auto_detect_barcode == 1) {
    $(document).ready(function() {
        var pressed = false;
        var chars = [];
        $(window).keypress(function(e) {
            if(e.key == '%') { pressed = true; }
            chars.push(String.fromCharCode(e.which));
            if (pressed == false) {
                setTimeout(function(){
                    if (chars.length >= 8) {
                        var barcode = chars.join("");
                        $( "#add_item" ).focus().autocomplete( "search", barcode );
                    }
                    chars = [];
                    pressed = false;
                },200);
            }
            pressed = true;
        });
    });
}

$(document).ready(function() {
    read_card();
});

function generateCardNo(x) {
    if(!x) { x = 16; }
    chars = "1234567890";
    no = "";
    for (var i=0; i<x; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        no += chars.substring(rnum,rnum+1);
    }
    return no;
}
function roundNumber(number, toref) {
    switch(toref) {
        case 1:
            var rn = formatDecimal(Math.round(number * 20)/20);
            break;
        case 2:
            var rn = formatDecimal(Math.round(number * 2)/2);
            break;
        case 3:
            var rn = formatDecimal(Math.round(number));
            break;
        case 4:
            var rn = formatDecimal(Math.ceil(number));
            break;
        default:
            var rn = number;
    }
    return rn;
}
function getNumber(x) {
    return accounting.unformat(x);
}
function formatQuantity(x) {
    return (x != null) ? '<div class="text-center">'+formatNumber(x, site.settings.qty_decimals)+'</div>' : '';
}
function formatQuantity2(x) {
    return (x != null) ? formatQuantityNumber(x, site.settings.qty_decimals) : '';
}
function formatQuantityNumber(x, d) {
    if (!d) { d = site.settings.qty_decimals; }
    return parseFloat(accounting.formatNumber(x, d, '', '.'));
}
function formatQty(x) {
    return (x != null) ? formatNumber(x, site.settings.qty_decimals) : '';
}
function formatNumber(x, d) {
    if(!d && d != 0) { d = site.settings.decimals; }
    if(site.settings.sac == 1) {
        return formatSA(parseFloat(x).toFixed(d));
    }
    return accounting.formatNumber(x, d, site.settings.thousands_sep == 0 ? ' ' : site.settings.thousands_sep, site.settings.decimals_sep);
}
function formatMoney(x, symbol) {
    if(!symbol) { symbol = ""; }
    if(site.settings.sac == 1) {
        return symbol+''+formatSA(parseFloat(x).toFixed(site.settings.decimals));
    }
    return accounting.formatMoney(x, symbol, site.settings.decimals, site.settings.thousands_sep == 0 ? ' ' : site.settings.thousands_sep, site.settings.decimals_sep, "%s%v");
}
function formatCNum(x) {
    if (site.settings.decimals_sep == ',') {
        var x = x.toString();
        var x = x.replace(",", ".");
        return parseFloat(x);
    }
    return x;
}
function formatDecimal(x, d) {
    if (!d) { d = site.settings.decimals; }
    return parseFloat(accounting.formatNumber(x, d, '', '.'));
}
function hrsd(sdate) {
    return moment().format(site.dateFormats.js_sdate.toUpperCase())
}

function hrld(ldate) {
    return moment().format(site.dateFormats.js_sdate.toUpperCase()+' H:mm')
}
function is_valid_discount(mixed_var) {
    return (is_numeric(mixed_var) || (/([0-9]%)/i.test(mixed_var))) ? true : false;
}
function is_numeric(mixed_var) {
    var whitespace =
    " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
        1)) && mixed_var !== '' && !isNaN(mixed_var);
}
function is_float(mixed_var) {
    return +mixed_var === mixed_var && (!isFinite(mixed_var) || !! (mixed_var % 1));
}
function currencyFormat(x) {
    return formatMoney(x != null ? x : 0);
}
function formatSA (x) {
    x=x.toString();
    var afterPoint = '';
    if(x.indexOf('.') > 0)
       afterPoint = x.substring(x.indexOf('.'),x.length);
    x = Math.floor(x);
    x=x.toString();
    var lastThree = x.substring(x.length-3);
    var otherNumbers = x.substring(0,x.length-3);
    if(otherNumbers != '')
        lastThree = ',' + lastThree;
    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;

    return res;
}

function unitToBaseQty(qty, unitObj) {
    switch(unitObj.operator) {
        case '*':
            return parseFloat(qty)*parseFloat(unitObj.operation_value);
            break;
        case '/':
            return parseFloat(qty)/parseFloat(unitObj.operation_value);
            break;
        case '+':
            return parseFloat(qty)+parseFloat(unitObj.operation_value);
            break;
        case '-':
            return parseFloat(qty)-parseFloat(unitObj.operation_value);
            break;
        default:
            return parseFloat(qty);
    }
}

function baseToUnitQty(qty, unitObj) {
    switch(unitObj.operator) {
        case '*':
            return parseFloat(qty)/parseFloat(unitObj.operation_value);
            break;
        case '/':
            return parseFloat(qty)*parseFloat(unitObj.operation_value);
            break;
        case '+':
            return parseFloat(qty)-parseFloat(unitObj.operation_value);
            break;
        case '-':
            return parseFloat(qty)+parseFloat(unitObj.operation_value);
            break;
        default:
            return parseFloat(qty);
    }
}

function read_card() {
    var typingTimer;

    $('.swipe').keyup(function (e) {
        e.preventDefault();
        var self = $(this);
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function() {
            var payid = self.attr('id');
            var id = payid.substr(payid.length - 1);
            var v = self.val();
            var p = new SwipeParserObj(v);

            if(p.hasTrack1) {
                var CardType = null;
                var ccn1 = p.account.charAt(0);
                if(ccn1 == 4)
                    CardType = 'Visa';
                else if(ccn1 == 5)
                    CardType = 'MasterCard';
                else if(ccn1 == 3)
                    CardType = 'Amex';
                else if(ccn1 == 6)
                    CardType = 'Discover';
                else
                    CardType = 'Visa';

                $('#pcc_no_'+id).val(p.account).change();
                $('#pcc_holder_'+id).val(p.account_name).change();
                $('#pcc_month_'+id).val(p.exp_month).change();
                $('#pcc_year_'+id).val(p.exp_year).change();
                $('#pcc_cvv2_'+id).val('');
                $('#pcc_type_'+id).val(CardType).change();
                self.val('');
                $('#pcc_cvv2_'+id).focus();
            } else {
                $('#pcc_no_'+id).val('');
                $('#pcc_holder_'+id).val('');
                $('#pcc_month_'+id).val('');
                $('#pcc_year_'+id).val('');
                $('#pcc_cvv2_'+id).val('');
                $('#pcc_type_'+id).val('');
            }
        }, 100);
    });

    $('.swipe').keydown(function (e) {
        clearTimeout(typingTimer);
    });
}

function check_add_item_val() {
    $('#add_item').bind('keypress', function (e) {
        if (e.keyCode == 13 || e.keyCode == 9) {
            e.preventDefault();
            $(this).autocomplete("search");
        }
    });
}
function nav_pointer() {
    var pp = p_page == 'n' ? 0 : p_page;
    (pp == 0) ? $('#previous').attr('disabled', true) : $('#previous').attr('disabled', false);
    ((pp+pro_limit) > tcp) ? $('#next').attr('disabled', true) : $('#next').attr('disabled', false);
}

function product_name(name, size) {
    if (!size) { size = 42; }
    return name.substring(0, (size-7));
}

$.extend($.keyboard.keyaction, {
    enter : function(base) {
        if (base.$el.is("textarea")){
            base.insertText('\r\n');
        } else {
            base.accept();
        }
    }
});

$(document).ajaxStart(function(){
  $('#ajaxCall').show();
}).ajaxStop(function(){
  $('#ajaxCall').hide();
});

$(document).ready(function(){
    nav_pointer();
    $('#myModal').on('hidden.bs.modal', function() {
        $(this).find('.modal-dialog').empty();
        $(this).removeData('bs.modal');
    });
    $('#myModal2').on('hidden.bs.modal', function () {
        $(this).find('.modal-dialog').empty();
        $(this).removeData('bs.modal');
        $('#myModal').css('zIndex', '1050');
        $('#myModal').css('overflow-y', 'scroll');
    });
    $('#myModal2').on('show.bs.modal', function () {
        $('#myModal').css('zIndex', '1040');
    });
    $('.modal').on('hidden.bs.modal', function() {
        $(this).removeData('bs.modal');
    });
    $('.modal').on('show.bs.modal', function () {
        $('#modal-loading').show();
        $('.blackbg').css('zIndex', '1041');
        $('.loader').css('zIndex', '1042');
    }).on('hide.bs.modal', function () {
        $('#modal-loading').hide();
        $('.blackbg').css('zIndex', '3');
        $('.loader').css('zIndex', '4');
    });
    $('#clearLS').click(function(event) {
        bootbox.confirm("Are you sure?", function(result) {
        if(result == true) {
            localStorage.clear();
            location.reload();
        }
        });
        return false;
    });
});

// qtthuan: hàm chuyển đổi tiền thành chữ
var NUMBERTOSTRING=function(){var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){var o="",a=Math.floor(r/10),e=r%10;return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" bốn":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},n=function(n,o){var a="",e=Math.floor(n/100),n=n%100;return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},o=function(t,r){var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);var e=Math.floor(t/1e3),t=t%1e3;return e>0&&(o+=n(e,r)+" ngàn",r=!0),t>0&&(o+=n(t,r)),o};return{read:function(r){if(0==r)return t[0];var n="",a="";do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";while(r>0);return n.trim()}}}();

// qtthuan: hàm viết hoa kí tự đầu tiên trong chuỗi
function upperFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// qtthuan: hàm so sánh ngày hiện tại có thuộc 2 ngày đã cho không
function checkValidPromotionDate(date1, date2) {
    if (!date1) {
        return false;
    }
    D_1 = moment(date1).format('DD-MM-YYYY').split("-");
    D_2 = moment(date2).format('DD-MM-YYYY').split("-");
    D_3 = moment(new Date()).format('DD-MM-YYYY').split("-");

    var d1 = new Date(D_1[2], parseInt(D_1[1]) - 1, D_1[0]);
    var d2 = new Date(D_2[2], parseInt(D_2[1]) - 1, D_2[0]);
    var d3 = new Date(D_3[2], parseInt(D_3[1]) - 1, D_3[0]);

    if (d3 >= d1 && d3 <= d2) {
        return true;
    } else {
        return false;
    }
}
// Shortcut keys
//$.ajaxSetup ({ cache: false, headers: { "cache-control": "no-cache" } });
if(pos_settings.focus_add_item != '') { shortcut.add(pos_settings.focus_add_item, function() { $("#add_item").focus(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.add_manual_product != '') { shortcut.add(pos_settings.add_manual_product, function() { $("#addManually").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.customer_selection != '') { shortcut.add(pos_settings.customer_selection, function() { $("#poscustomer").select2("open"); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.add_customer != '') { shortcut.add(pos_settings.add_customer, function() { $("#add-customer").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.customers_info != '') { shortcut.add(pos_settings.customers_info, function() { $("#view-customer").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.toggle_category_slider != '') { shortcut.add(pos_settings.toggle_category_slider, function() { $("#open-category").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.toggle_brands_slider != '') { shortcut.add(pos_settings.toggle_brands_slider, function() { $("#open-brands").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.toggle_subcategory_slider != '') { shortcut.add(pos_settings.toggle_subcategory_slider, function() { $("#open-subcategory").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.cancel_sale != '') { shortcut.add(pos_settings.cancel_sale, function() { $("#reset").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.suspend_sale != '') { shortcut.add(pos_settings.suspend_sale, function() { $("#suspend").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.return_sale != '') { shortcut.add(pos_settings.return_sale, function() { $("#preturn").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.print_items_list != '') { shortcut.add(pos_settings.print_items_list, function() { $("#print_btn").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.finalize_sale != '') { shortcut.add(pos_settings.finalize_sale, function() { if ($('#paymentModal').is(':visible')) { $("#submit-sale").click(); } else { $('#is_quick_finalize_sale').val(0); $("#payment").trigger('click'); } }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.quick_finalize_sale != '') { shortcut.add(pos_settings.quick_finalize_sale, function() { if ($('#paymentModal').is(':visible')) { $("#submit-sale").click(); } else { $('#is_quick_finalize_sale').val(1); $("#payment").trigger('click'); } }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.today_sale != '') { shortcut.add(pos_settings.today_sale, function() { $("#today_sale").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.open_hold_bills != '') { shortcut.add(pos_settings.open_hold_bills, function() { $("#opened_bills").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.close_register != '') { shortcut.add(pos_settings.close_register, function() { $("#close_register").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.gift_card != '') { shortcut.add(pos_settings.gift_card, function() { $("#sellGiftCard").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.bill_shipping != '') { shortcut.add(pos_settings.bill_shipping, function() { $("#pshipping").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }

if(pos_settings.bill_discount != '') { shortcut.add(pos_settings.bill_discount, function() { $("#ppdiscount").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
shortcut.add("ESC", function() { $("#cp").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} );

if (site.settings.set_focus != 1) {
    $(document).ready(function(){
        $('#add_item').focus();
    });
}
