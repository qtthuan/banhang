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
                    <button id="printLeft" class="floating-print-btn left no-print">
                    🖨️ In tem
                    </button>
                    <button id="printRightTotal" class="floating-print-btn-grey right no-print">
                    <div id="total_items" class="no-print" style="color: white;"></div>
                    </button>
                    <button id="hide_price" class="floating-print-btn-green right no-print">
                    Ẩn/Hiện giá
                    </button>
                    <?php                       
                        $style = '10_1';
                        echo '<button type="button" style="height: 46px; font-size: 22px" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print').'</button>';
                        echo '<div style="text-align: center; margin: 5px 0" class="no-print">';
                        $i = 0;
                        foreach ($sales as $sale) {
                           
                            $customer_name = trim($sale->customer); // bỏ khoảng trắng đầu/cuối
                            if (mb_strlen($customer_name, 'UTF-8') > 31) {
                                // cắt đúng 15 ký tự và nối 3 dấu chấm sát chữ cuối
                                $customer_name = rtrim(mb_substr($customer_name, 0, 31, 'UTF-8')) . '..';
                            }
                        

                            echo '<button type="button" class="btn btn-danger bills" id="'.$sale->id.'" style="height:58px; width: 210px; font-size: 17px; line-height: 16px; margin-top: 10px">';
                            echo '<span id="reference_'.$sale->id.'">#' . substr($sale->reference_no, -3) . '<span>';

                            echo '<br /><span style="font-size: 13px">'. $sale->customer.'</span>';
                            echo '<br /><span style="font-size: 13px">'. $this->sma->formatMoney($sale->grand_total).'</span>';
                            echo '<input type="hidden" class="hidd_customer_id" value="'.$sale->customer_id.'">';
                            echo '<input type="hidden" class="hidd_customer_name" value="'.htmlspecialchars($customer_name).'">';
                            echo '<input type="hidden" class="hidd_sale_id" value="'.$sale->id.'">';
                            echo '<input type="hidden" class="hidd_sale_language" value="'.$sale->sale_language.'">';
                            echo '<input type="hidden" class="hidd_reference_no" value="#'.substr($sale->reference_no, -3).'">';
                            echo '</button>&nbsp;&nbsp;';
                        }
                        echo '</div>';
                        $j = 0;
                        $total_items = 1;
                        foreach($items as $key => $values) {
                            //$total_items++;
                            $display_btn = '';
                            if ($j > 0) {
                                $display_btn = ' style="display: none;"';
                            }
                            //$this->sma->print_arrays($values);
                            echo '<div class="barcode_mini div'.$key.'"'.$display_btn.'>';
                            
                            foreach ($values as $item) {
                                
                                if ($item->product_id != 21121 && $item->product_id != 21122 && $item->product_id != 20354
                                && $item->product_id != 20357 && $item->product_id != 20358 && $item->product_id != 22133
                                && $item->product_id != 22153 && $item->product_id != 22154 && $item->product_id != 22178
                                && $item->product_id != 22179 && $item->product_id != 22180
                                && $item->product_id != 22181 && $item->product_id != 22185 && $item->product_id != 22288) {
                                $total_items++;
                                //echo 'vv: '. $j.'<br />';
                                    $item_qty = $item->quantity;
                                    
                                    if ($item->quantity > 1) {
                                        //echo '-';
                                        $html = '';
                                        for ($i=0; $i < $item_qty; $i++) {
                                            $html .= '<div class="item_1 ..."> ... </div>';
                                        }
                                        for ($i=0; $i < $item_qty; $i++) {
                                            $names = [];
                                            if ($item->comment_name) {
                                                $names = array_map('trim', explode(',', $item->comment_name));
                                            }

                                            //$total_items++;
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
                                                    $str_comment = '<br /><span class="comment" style="font-size: 11px;"><strong>' . $item->comment . '</strong></span>';
                                                }
                                                echo '<span style="position: absolute; top: 0;'.$str_comment_style.'" class="barcode_name '.$increase_size.'">'.$item->product_name;
                                                echo '<span class="label_product_name_en" style="display: none;"><br />' . $item->product_name_en . '</span>';
                                                echo $str_comment;
                                                echo '</span>';
                                            }

                                            // Lấy tên cho vòng lặp hiện tại
                                            $current_name = isset($names[$i]) ? $names[$i] : "";

                                            // In tên nếu có
                                            if ($current_name !== "") {
                                                echo '<span class="circle_text1" style="position: absolute; bottom: 32px; left: 3px; font-size: 16px; font-weight: bold">';
                                                echo '<strong>' . $current_name . '</strong>';
                                                echo '</span>';
                                            }


                                            
                                            

                                            echo '<h4 style="margin: 1px; position: absolute; bottom: 0; font-size: 18px;">';
                                            echo '<span style="font-size: 13px; font-weight: bold" class="reference_no">';   
                                            echo '</span>';
                                            
                                            echo '<span class="text_price">'.$this->sma->formatK($item->unit_price);
                                            echo '</span>';
                                            
                                            echo '</h4>';

                                            echo '</div>';
                                        }
                                    } else {
                                        
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
                                                $str_comment = '<br /><span class="comment" style="font-size: 11px;"><strong>' . $item->comment . '</strong></span>';
                                            }
                                            echo '<span style="position: absolute; top: 0;'.$str_comment_style.'" class="barcode_name '.$increase_size.'">'.$item->product_name;
                                            echo '<span class="label_product_name_en" style="display: none;"><br />' . $item->product_name_en . '</span>';
                                            echo $str_comment;
                                            echo '</span>';
                                        }
                                        if ($item->comment_name) {
                                                
                                            echo '<span class="circle_text1" style="position: absolute; bottom: 32px; left: 3px; font-size: 16px; font-weight: bold">';
                                            echo ' <strong>' . $item->comment_name . '</strong>';
                                            echo '</span>';
                                        }
                                       

                                        $str_span_size = '<span class="circle_text" style="position: absolute; bottom: 22px; right: 3px; font-size: 16px; font-weight: bold">';
                                        $str_span_other = '<span class="circle_text1" style="position: absolute; bottom: 22px; right: 3px; font-size: 16px; font-weight: bold">';
                                        if($item->variant && $item->variant != '' && $item->variant != 'undefined') {
                                            
                                            if (trim(strtolower($item->variant)) == 'size l') {
                                                echo $str_span_size;
                                                echo 'L';
                                            } elseif (trim(strtolower($item->variant)) == 'size m')  {
                                                echo $str_span_size;
                                                echo 'M';
                                            } 
                                            
                                            echo '</span>';
                                        }

                                        echo '<h4 style="margin: 1px; position: absolute; bottom: 0; font-size: 18px;">';
                                        echo '<span style="display: table-cell; font-size: 13px; font-weight: bold" class="reference_no ">';   
                                        echo '</span>';
                                       
                                        echo '<span class="text_price">'.$this->sma->formatK($item->unit_price);
                                        echo '</span>';
                                        echo '</h4>';

                                        echo '</div>';
                                        
                                    }
                                    
                                }
                            }
                            
                            echo '</div>';  
                            $j++;
                            
                        }
                        
                        echo '<button type="button"style="height: 46px; font-size: 22px;" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print') . '</button>';
                    ?>
                    <div id="copyToast" 
                        style="
                            position: fixed;
                            top: 53%;
                            right: 16%;
                            background: rgba(244, 229, 197, 0.75);
                            color: #000000ff;
                            padding: 10px 16px;
                            border-radius: 6px;
                            font-size: 16px;
                            opacity: 0;
                            pointer-events: none;
                            transition: opacity 0.4s ease;
                            z-index: 9999;
                        " class="no-print">
                        Đã copy!
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        <?php if ($this->input->post('print')) { ?>
            $( window ).load(function() {
                $('html, body').animate({
                    scrollTop: ($("#barcode-con").offset().top)-15
                }, 1000);
            });
        <?php } ?>

        

        $('#printRightTotal').on('click', function () {
            let value = $('#total_items').text();   // "Trang: 1-2"
            //let value = txt.split(':')[1].trim();        // "1-2"

            navigator.clipboard.writeText(value).then(function () {
                showCopyToast("Đã copy: " + value);
            }).catch(function(){
                showCopyToast("Không copy được!");
            });
        });

        function showCopyToast(msg) {
            let t = $('#copyToast');
            t.text(msg);
            t.css('opacity', 1);

            setTimeout(() => {
                t.css('opacity', 0);
            }, 1200);
        }


        
        $(document).on('click', '.item_1', function () {
            var total_items = $(this).closest('.barcode_mini').find('.item_1').length - 1;
            //console.log('cccc: ' + $(this).closest('.barcode').find('.item_1').length);
            var id = $(this).attr('id');
            $(this).remove();
            //total_items = total_items -1;
            //$("#total_items").html("<h1><strong>(1-" + Math.round(total_items/2) + ")</strong></h1><h2></h2>")
            fillTotalItems(total_items);
        });
        $('#hide_price').click(function(){
            $('.text_price').toggle();
        });
        $('#hide_customer_name').click(function(){
            $('.customer_name').toggle();
        });

        function fillTotalItems(total) {
            $("#total_items").html("<span style='font-size: 40px;'><strong>1-" + Math.round(total) + "<strong></span>")
        }
        
        // Enable first button
        $('.bills:first').removeClass('btn-danger').addClass('btn-success');
        //$('.reference_no').text($('.hidd_reference_no:first').val());
        $('.reference_no').text($('.hidd_customer_name:first').val());
        if($(".bills:first .hidd_sale_language").val() == 1) {
            $('.barcode:first .label_product_name_en').removeAttr('style');
            $('.barcode:first .style10_1' ).each(function( index ) {
                if($(this).find('.comment').text() != '') {
                    $(this).find('.barcode_name').removeClass('increase_size_1');
                    $(this).find('.barcode_name').addClass('increase_size_2');
                    $(this).find('.label_product_name_en').addClass('increase_size_3');
                } else {
                    $(this).find('.barcode_name').removeClass('increase_size');
                    $(this).find('.barcode_name').addClass('increase_size_1');
                }
            });
            
        }
        fillTotalItems($('.barcode_mini:first>div').length);


        $('.bills').on('click',function() {
            div_id = $(this).attr('id');
            customer_id = $('#' + div_id).find('.hidd_customer_id').val();
            sale_language = $('#' + div_id).find('.hidd_sale_language').val();
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
                //$('.reference_no').text($('#' + div_id).find('.hidd_reference_no').val());
                $('.reference_no').text($('#' + div_id).find('.hidd_customer_name').val());
                //console.log('vvv' + sale_language);
                if(sale_language == 1) {
                    //console.log('vvv');
                    $('.div' + div_id + ' .label_product_name_en').removeAttr('style');

                    $('.div' + div_id + ' .style10_1' ).each(function( index ) {
                        //console.log('fd' + $(this).find('.comment').text());
                        if($(this).find('.comment').text() != '') {
                            $(this).find('.barcode_name').removeClass('increase_size_1');
                            $(this).find('.barcode_name').addClass('increase_size_2');
                            $(this).find('.label_product_name_en').addClass('increase_size_3');
                        } else {
                            $(this).find('.barcode_name').removeClass('increase_size');
                            $(this).find('.barcode_name').addClass('increase_size_1');
                        }
                    });

                    // if($('.div' + div_id + ' .comment').text() != '') {
                    //     $('.div' + div_id + ' .barcode_name').removeClass('increase_size_1');
                    //     $('.div' + div_id + ' .barcode_name').addClass('increase_size_2');
                    //     $('.div' + div_id + ' .label_product_name_en').addClass('increase_size_3');
                    // }
                }

                // if (customer_id == 6533) {
                //     $('.text_price').hide();
                // } else {
                //     $('.text_price').show();
                // }
                
                //console.log($('.div' + div_id +'>div').length);
                fillTotalItems($('.div' + div_id +'>div').length);
                
                //console.log(div_id);
            } 
        });
    });

    

</script>
<script>
document.querySelectorAll('.floating-print-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    window.print(); // hoặc gọi hàm in hiện tại nếu bạn có
  });
});
</script>
