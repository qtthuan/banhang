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
                        $style = '10_1';
                        echo '<button type="button" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print').'</button>';
                        echo '<div style="text-align: center; margin: 5px 0" class="no-print">';
                        //$i = 0;
                        foreach ($sales as $sale) {
                            
                            echo '<button type="button" class="btn btn-danger bills" id="'.$sale->id.'" style="height:41px; width: 145px; font-size: 18px; line-height: 16px;">';
                            echo '<span id="reference_'.$sale->id.'">' . $sale->reference_no . '<span>';
                            echo '<br /><span style="font-size: 11px">'. $sale->customer.'</span>';
                            echo '<input type="hidden" class="hidd_customer_id" value="'.$sale->customer_id.'">';
                            echo '<input type="hidden" class="hidd_sale_id" value="'.$sale->id.'">';
                            echo '<input type="hidden" class="hidd_reference_no" value="'.$sale->reference_no.'">';
                            echo '</button>&nbsp;&nbsp;';
                            
                            //$i++;
                        }
                        echo '</div>';
                        $j = 0;
                        foreach($items as $key => $values) {
                            $display_btn = '';
                            if ($j > 0) {
                                $display_btn = ' style="display: none;"';
                            }
                            echo '<div class="barcode div'.$key.'"'.$display_btn.'>';
                            $total_items = 0;
                       
                            foreach ($values as $item) {
                                //echo $item->product_name.'<br />';
                                $total_items++;
                                $increase_size = "increase_size";
                                if ($item->comment) {
                                    $increase_size .= '_1';
                                }
                                
                                $str_padding = '';
                                if ($item->variants == 'L') {
                                    $str_padding = ' style="padding-top: 10px;"';                                            
                                }                                       
                                echo '<div class="item_1 style' . $style . ' ' . $valign_middle . '" >';
                                if($item->product_name) {
                                    $str_comment = '';
                                    $str_comment_style = '';
                                    if($item->comment && $item->comment != '' && $item->comment != 'undefined') {
                                        $str_comment = '<br /><span style="font-size: 10px;"><strong>' . $item->comment . '</strong></span>';
                                    }
                                    echo '<span style="position: absolute; top: 0;'.$str_comment_style.'" class="barcode_name '.$increase_size.'">'.$item->product_name;
                                    echo $str_comment;
                                    echo '</span>';
                                }


                                if($item->variant && $item->variant != '' && $item->variant != 'undefined') {
                                    echo '<span class="circle_text" style="position: absolute; bottom: 16px; right: 3px; font-size: 16px; font-weight: bold">';
                                    
                                    if (trim($item->variant) == 'size L') {
                                        echo 'L';
                                    } elseif (trim($item->variant) == 'size M')  {
                                        echo 'M';
                                    } else {
                                        echo $item->variant;
                                    } 
                                    echo '</span>';
                                }

                                    echo '<h4 style="margin: 1px; position: absolute; bottom: 0; font-size: 18px;">';
                                    echo '<span style="font-size: 16px; font-weight: bold" class="reference_no ">';   
                                    echo '</span>';
                                    //if ($item->customer_id != 6533) { // Không in giá Cô Ngọc LQĐ
                                        echo '<span class="text_price">'.$this->sma->formatMoney($item->unit_price);
                                        echo '</span>';
                                    //}
                                    echo '</h4>';

                                    echo '</div>';
                            }
                            echo '</div>';  
                            $j++;
                        }
                        
                        echo '<div class="no-print">&nbsp;&nbsp;
                                <button type="button" class="btn btn-success" id="hide_price" style="height:37px; font-size: 18px;">
                                    <i class="fa"></i>Ẩn/Hiện giá
                                </button></div>';
                               
                        echo '<div id="total_items" class="no-print" style="color: green"><h1><strong>(1-'.round($total_items/2).')</strong></h1><h2>&#8220;Click vào tem để xóa&#8221;</h2></div>';
                        echo '<button type="button" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print') . '</button>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var total_items = <?=$total_items?>;
        <?php if ($this->input->post('print')) { ?>
            $( window ).load(function() {
                $('html, body').animate({
                    scrollTop: ($("#barcode-con").offset().top)-15
                }, 1000);
            });
        <?php } ?>
        
        $(document).on('click', '.item_1', function () {
            var id = $(this).attr('id');
            $(this).remove();
            total_items = total_items -1;
            $("#total_items").html("<h1><strong>(1-" + Math.round(total_items/2) + ")</strong></h1><h2>&#8220;Click vào tem để xóa&#8221;</h2>")
        });
        $('#hide_price').click(function(){
            $('.text_price').toggle();
        });
        $('#hide_customer_name').click(function(){
            $('.customer_name').toggle();
        });
        
        // Enable first button
        $('.bills:first').removeClass('btn-danger').addClass('btn-success');
        $('.reference_no').text($('.hidd_reference_no:first').val());
        if ($('.hidd_customer_id:first').val() == 6533) {
            $('.text_price').hide();
        }


        $('.bills').on('click',function() {
            div_id = $(this).attr('id');
            customer_id = $('#' + div_id).find('.hidd_customer_id').val();
            $('.bills' ).each(function( index ) {
                all_div_ids = $(this).attr('id');
                $(this).addClass('btn-danger');
                $(this).removeClass('btn-success');
                $('.div' + all_div_ids).hide();
            });
            if(!$(this).hasClass('btn-success')) {
                
                //reference_no = $('.div' + id).find('.hidd_reference_no').val();
                $(this).removeClass('btn-danger');
                $('.div' + div_id).show();
                $(this).addClass('btn-success');
                $('.reference_no').text($('#' + div_id).find('.hidd_reference_no').val());

                if (customer_id == 6533) {
                    $('.text_price').hide();
                } else {
                    $('.text_price').show();
                }
                
                console.log(customer_id);
                
                console.log(div_id);
            } 
        });
    });

</script>