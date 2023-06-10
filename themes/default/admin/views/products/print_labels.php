<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header no-print">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('print_barcode_label'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" onclick="window.print();return false;" id="print-icon" class="tip" title="<?= lang('print') ?>">
                        <i class="icon fa fa-print"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                

                
                <div id="barcode-con">
                    <?php                       
                        $style = 10;
                        echo '<button type="button" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print').'</button>';
                        $c = 1;
                        if ($style == 12 || $style == 18 || $style == 24 || $style == 40) {
                            echo '<div class="barcodea4">';
                        } elseif ($style == 55) {
                            echo '<div class="barcodea5">';
                        } elseif ($style != 50) {
                            echo '<div class="barcode">';
                        }
                        $total_items = 0;
                        //$this->sma->print_arrays($barcodes);
                        foreach ($barcodes as $item) {
                            // /echo 'xxx';
                            //for ($r = 1; $r <= $item['quantity']; $r++) {
                                $total_items++;
                                //if($item['increase_size']) {
                                    $increase_size = "increase_size";
                                    if ($item['comment'] || $item['variants'] == 'size L') {
                                        $increase_size .= '_1';
                                    }
                                //} 
                                $str_padding = '';
                                if ($item['variants'] == 'L') {
                                    $str_padding = ' style="padding-top: 10px;"';                                            
                                }                                       
                                echo '<div class="item style' . $style . ' ' . $valign_middle . '" '.
                                ($style == 50 && $this->input->post('cf_width') && $this->input->post('cf_height') ?
                                    'style="width:'.$this->input->post('cf_width').'in;height:'.$this->input->post('cf_height').'in;border:0;"' : '')
                                .'>';
                                
                                if($item['name']) {
                                    echo '<span class="barcode_name '.$increase_size.'">'.$item['name'];
                                    if($item['comment'] && $item['comment'] != '' && $item['comment'] != 'undefined') {
                                        echo ' (<strong>' . $item['comment'] . '</strong>)';
                                    }
                                    echo '</span>';
                                }

                                echo '<span class="text_code"'.$str_padding.'>';
                                if ($item['variants'] == 'size L') {
                                    echo lang('mini_size_l');
                                } elseif ($item['variants'] == 'size M')  {
                                    echo '';
                                } else {
                                    echo $item['variants'];
                                }
                                echo '</span>';
                                
                                if ($item[''])
                                echo '<br />';

                                
                                if ($style == 50) {
                                    echo '</div>';
                                }
                                echo '</div>';
                                
                                $c++;
                            //}
                        }
                        if ($style != 50) {
                            echo '</div>';
                        }
                        echo '<div class="no-print" style="color: green"><h2><strong>(1-'.round($total_items/2).')</strong></h2></div>';
                        echo '<button type="button" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print') . '</button>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var ac = false; bcitems = {};
    if (localStorage.getItem('bcitems')) {
        bcitems = JSON.parse(localStorage.getItem('bcitems'));
    }
    <?php if($items) { ?>
    localStorage.setItem('bcitems', JSON.stringify(<?= $items; ?>));
    <?php } ?>
    $(document).ready(function() {
        <?php if ($this->input->post('print')) { ?>
            $( window ).load(function() {
                $('html, body').animate({
                    scrollTop: ($("#barcode-con").offset().top)-15
                }, 1000);
            });
        <?php } ?>
        
        
        

        $(document).on('click', '.item', function () {
            var id = $(this).attr('id');
            //delete bcitems[id];
            //localStorage.setItem('bcitems', JSON.stringify(bcitems));
            $(this).remove();
        });

        

        var old_row_qty;
        $(document).on("focus", '.quantity', function () {
            old_row_qty = $(this).val();
        }).on("change", '.quantity', function () {
            var row = $(this).closest('tr');
            if (!is_numeric($(this).val())) {
                $(this).val(old_row_qty);
                bootbox.alert(lang.unexpected_value);
                return;
            }
            var new_qty = parseFloat($(this).val()),
            item_id = row.attr('data-item-id');
            bcitems[item_id].qty = new_qty;
            localStorage.setItem('bcitems', JSON.stringify(bcitems));
        });

        if ($('#price').prop('checked') == false) {
            $('#barcode-con .barcode .item').addClass('valign_barcode_middle');
        }
    });

    function add_product_item(item) {
        ac = true;
        if (item == null) {
            return false;
        }
        item_id = item.id;
        if (bcitems[item_id]) {
            bcitems[item_id].qty = parseFloat(bcitems[item_id].qty) + 1;
        } else {
            bcitems[item_id] = item;
            bcitems[item_id]['selected_variants'] = {};
            $.each(item.variants, function () {
                bcitems[item_id]['selected_variants'][this.id] = 1;
            });
        }

        localStorage.setItem('bcitems', JSON.stringify(bcitems));        
        loadItems();
        return true;

    }

    function uncheckMini(code) {
        if (code.includes('GK')) {
            $('#barcode-print-form').find('.chkPrint').each(function() {
                //console.log($(this).val())
                $(this).prop("checked", false);
            });
            $('#product_name').prop("checked", true);  
            $('#product_code').prop("checked", true);           
        }
    }

    function loadItems () {

        if (localStorage.getItem('bcitems')) {
            $("#bcTable tbody").empty();
            bcitems = JSON.parse(localStorage.getItem('bcitems'));

            $.each(bcitems, function () {
                console.log(JSON.stringify(bcitems));
                var item = this;
                uncheckMini(item.code);
                var row_no = item.id;
                var vd = '';
                var comment = '';
                if (item.comment) {
                    comment += ' - <strong>' + item.comment + '</strong>';
                }
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + item.id + '" data-item-id="' + item.id + '"></tr>');
                tr_html = '<td><input name="product[]" type="hidden" value="' + item.id + '"><input name="comment[]" type="hidden" value="' + item.comment + '"><span id="name_' + row_no + '">' + item.name + ' (' + item.code + ') ' + comment + '</span></td>';
                tr_html += '<td><input class="form-control quantity text-center" name="quantity[]" type="text" value="' + formatDecimal(item.qty) + '" data-id="' + row_no + '" data-item="' + item.id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                if(item.variants) {
                    $.each(item.variants, function () {
                        vd += '<input name="vt_'+ item.id +'_'+ this.id +'" type="checkbox" class="checkbox" id="'+this.id+'" data-item-id="'+item.id+'" value="'+this.id+'" '+( item.selected_variants[this.id] == 1 ? 'checked="checked"' : '')+' style="display:inline-block;" /><label for="'+this.id+'" class="padding05">'+this.name+'</label>';
                    });
                }
                tr_html += '<td>'+vd+'</td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.appendTo("#bcTable");
            });
            $('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
            return true;
        }
    }

</script>