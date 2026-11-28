<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
/* KHU VỰC TAB IN TEM */
.label-tab-wrapper {
    display: flex;
    gap: 8px;
    padding: 8px;
    margin: 12px 0 15px;
    background: #D5E0EC;
    border-radius: 12px;
    border: 1px solid #B8C8DA;
}

.label-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    padding: 13px 18px;
    border: none;
    border-radius: 9px;
    background: transparent;
    color: #64748b;
    font-size: 20px;
    font-weight: 700;
    transition: all .25s ease;
    cursor: pointer;
}

.label-tab:hover {
    background: #e5ebf2;
    color: #334155;
}

.label-tab.active {
    color: #fff;
    background: linear-gradient(135deg, #2878bd, #1763a5);
    box-shadow: 0 3px 9px rgba(35, 105, 165, .25);
}

.label-tab .badge {
    background: rgba(255,255,255,.22);
    color: inherit;
    border-radius: 20px;
    padding: 3px 9px;
    font-size: 16px;
}

.label-tab-content {
    display: none;
    opacity: 0;
    transform: translateY(8px);
}

.label-tab-content.active {
    display: block;
    animation: labelTabFade .28s ease forwards;
}

@keyframes labelTabFade {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.label-bill-list {
    text-align: center;
    margin: 5px 0 12px;
    min-height: 65px;
}

.label-bill-list .bills,
.label-bill-list .suspended-bills {
    margin: 5px 3px;
    border-radius: 5px;
    transition: transform .18s ease, box-shadow .18s ease;
}

.label-bill-list .bills:hover,
.label-bill-list .suspended-bills:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,.15);
}

@media print {
    .label-tab-wrapper,
    .label-bill-list {
        display: none !important;
    }
}
</style>
<script type="text/javascript">
    $(document).ready(function() {
        
    });
</script>
<div class="box">
    <!-- <div class="box-header no-print">
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
    </div> -->
    
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
                        //echo '<button type="button" style="height: 46px; font-size: 22px" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print').'</button>';
                        echo '
                            <div class="label-tab-wrapper no-print">

                                <button type="button"
                                        class="label-tab active"
                                        data-label-tab="current">
                                    <i class="fa fa-receipt"></i>
                                    IN TEM HIỆN TẠI
                                    <span class="badge">'.count($sales).'</span>
                                </button>

                                <button type="button"
                                        class="label-tab"
                                        data-label-tab="suspended">
                                    <i class="fa fa-clock-o"></i>
                                    IN TEM ĐƠN TẠM
                                    <span class="badge">'.count($suspended_bills).'</span>
                                </button>

                            </div>

                            <div id="labelTabCurrent" class="label-tab-content active">
                                <div class="label-bill-list no-print">
                            ';
                        $i = 0;
                        $hide_price_by_sale = [];
                        foreach ($sales as $sale) {

                        $hide_price_by_sale['sale_'.$sale->id] =
                                ((int)$sale->customer_group_id === (int)$customer_group_id_app);
                           
                            $customer_name = trim($sale->customer); // bỏ khoảng trắng đầu/cuối
                            if (mb_strlen($customer_name, 'UTF-8') > 31) {
                                // cắt đúng 15 ký tự và nối 3 dấu chấm sát chữ cuối
                                $customer_name = rtrim(mb_substr($customer_name, 0, 31, 'UTF-8')) . '..';
                            }
                            if ($customer_name == $default_customer_name) {
                                $customer_name = $default_customer_name_replace;
                            }
                        

                            echo '<button type="button" class="btn btn-danger bills bill-selector" id="sale_'.$sale->id.'" data-bill-id="'.$sale->id.'" data-bill-type="sale" style="height:58px; width: 210px; font-size: 17px; line-height: 16px; margin-top: 10px">';
                            echo '<span id="reference_'.$sale->id.'">#' . substr($sale->reference_no, -3) . '<span>';

                            echo '<br /><span style="font-size: 13px">'. $customer_name.'</span>';
                            echo '<br /><span style="font-size: 13px">'. $this->sma->formatMoney($sale->grand_total).'</span>';
                            echo '<input type="hidden" class="hidd_customer_id" value="'.$sale->customer_id.'">';
                            echo '<input type="hidden" class="hidd_customer_name" value="'.htmlspecialchars($customer_name).'">';
                            echo '<input type="hidden" class="hidd_sale_id" value="'.$sale->id.'">';
                            echo '<input type="hidden" class="hidd_sale_language" value="'.$sale->sale_language.'">';
                            echo '<input type="hidden" class="hidd_reference_no" value="#'.substr($sale->reference_no, -3).'">';
                            echo '</button>&nbsp;&nbsp;';
                        }
                        echo '
                            </div>
                        </div>

                        <div id="labelTabSuspended" class="label-tab-content">
                            <div class="label-bill-list no-print">
                        ';

                        foreach ($suspended_bills as $bill) {

                            $hide_price_by_sale['suspend_'.$bill->id] =
                                ((int)$bill->customer_group_id === (int)$customer_group_id_app);
                            $customer_name = trim($bill->suspend_note ?: $bill->customer);

                            if ($customer_name == $default_customer_name) {
                                $customer_name = $default_customer_name_replace;
                            }

                            echo '<button type="button"
                                class="btn btn-danger suspended-bills bill-selector"
                                id="suspend_'.$bill->id.'"
                                data-bill-id="'.$bill->id.'"
                                data-bill-type="suspend"
                                data-customer-name="'.htmlspecialchars($customer_name, ENT_QUOTES, 'UTF-8').'"
                                style="height:58px; width:210px; font-size:17px; line-height:16px; margin-top:10px">';

                            echo '<strong>#'.$bill->id.'</strong>';
                            echo '<br><span style="font-size:13px">'
                                .htmlspecialchars($customer_name, ENT_QUOTES, 'UTF-8')
                                .'</span>';

                            echo '<br><span style="font-size:13px">'
                                .$this->sma->formatMoney($bill->total)
                                .'</span>';

                            echo '</button>&nbsp;&nbsp;';
                        }

                        echo '
                            </div>
                        </div>
                        ';
                        $j = 0;
                        $total_items = 1;
                        foreach($items as $key => $values) {
                            //$total_items++;
                            $display_btn = ' style="display: none;"';
                            //$this->sma->print_arrays($values);
                            $hide_price = !empty($hide_price_by_sale[$key]);
                            echo '<div class="barcode_mini div'.$key.'"'.$display_btn.' data-hide-price="' . ($hide_price ? '1' : '0') . '">';
                            //echo '<div class="barcode_mini div'.$key.'"'.$display_btn.'>';
                            
                            foreach ($values as $item) {
                                
                                if ($item->product_id != 21121 && $item->product_id != 21122 && $item->product_id != 20354
                                && $item->product_id != 20357 && $item->product_id != 20358 && $item->product_id != 22133
                                && $item->product_id != 22153 && $item->product_id != 22154 && $item->product_id != 22178
                                && $item->product_id != 22179 && $item->product_id != 22180 && $item->product_id != 22997
                                && $item->product_id != 22181 && $item->product_id != 22185 && $item->product_id != 22288
                                && $item->product_id != 23170) {
                                $total_items++;
                                    $item_qty = $item->quantity;
                                    
                                    if ($item->quantity > 1) {
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
                                            $str_span_size = '<span class="circle_text" style="position: absolute; bottom: 22px; right: 3px; font-size: 16px; font-weight: bold">';
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

                                        echo '<h4 style="margin: 1px 1px 3px 0px; position: absolute; bottom: 0; font-size: 18px;">';
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
                        
                        //echo '<button type="button"style="height: 46px; font-size: 22px;" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print') . '</button>';
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

        function applyPriceVisibility(div_id) {
            var container = $('.div' + div_id);

            if (container.attr('data-hide-price') == '1') {
                container.find('.text_price').hide();
            } else {
                container.find('.text_price').show();
            }
        }

        function fillTotalItems(total) {
            $("#total_items").html("<span style='font-size: 40px;'><strong>1-" + Math.round(total) + "<strong></span>")
        }
        
    
        // CHUYỂN TAB IN TEM
        $(document).on('click', '.label-tab', function() {

            var tab = $(this).data('label-tab');

            if ($(this).hasClass('active')) {
                return;
            }

            $('.label-tab').removeClass('active');
            $(this).addClass('active');

            var $content = tab === 'current'
                ? $('#labelTabCurrent')
                : $('#labelTabSuspended');

            $('.label-tab-content').removeClass('active');

            $content.addClass('active');

            // Chọn đơn đầu tiên trong tab vừa mở
            var $firstBill = $content.find('.bill-selector').first();

            if ($firstBill.length) {
                $firstBill.trigger('click');
            } else {
                $('.bill-selector')
                    .removeClass('btn-success')
                    .addClass('btn-danger');

                $('.barcode_mini').hide();

                fillTotalItems(0);
            }
        });


        // CHỌN ĐƠN VÀ HIỂN THỊ TEM
        $(document).on('click', '.bill-selector', function() {

            var $button = $(this);

            var billId = $button.attr('id');
            var billType = $button.data('bill-type');

            var customerName = '';
            var saleLanguage = 0;

            if (billType === 'sale') {
                customerName = $button.find('.hidd_customer_name').val() || '';
                saleLanguage = $button.find('.hidd_sale_language').val() || 0;
            } else {
                customerName = $button.attr('data-customer-name') || '';
            }

            // Bỏ trạng thái chọn của tất cả đơn
            $('.bill-selector')
                .removeClass('btn-success')
                .addClass('btn-danger');

            // Ẩn tất cả vùng tem
            $('.barcode_mini').hide();

            // Đánh dấu đơn đang chọn
            $button.removeClass('btn-danger').addClass('btn-success');

            // Hiện vùng tem tương ứng
            var $label = $('.div' + billId);

            $label.stop(true, true).fadeIn(180);

            // Áp dụng trạng thái ẩn/hiện giá
            applyPriceVisibility(billId);

            // Hiển thị tên khách ở cuối tem
            $label.find('.reference_no').text(customerName);

            if (customerName === "<?= addslashes($default_customer_name) ?>") {
                $label.find('.reference_no').text(
                    "<?= addslashes($default_customer_name_replace) ?>"
                );
            }

            // Xử lý tên tiếng Anh nếu hóa đơn có ngôn ngữ tiếng Anh
            if (saleLanguage == 1) {

                $label.find('.label_product_name_en').show();

                $label.find('.style10_1').each(function() {

                    if ($(this).find('.comment').text() !== '') {

                        $(this).find('.barcode_name')
                            .removeClass('increase_size_1')
                            .addClass('increase_size_2');

                        $(this).find('.label_product_name_en')
                            .addClass('increase_size_3');

                    } else {

                        $(this).find('.barcode_name')
                            .removeClass('increase_size')
                            .addClass('increase_size_1');
                    }
                });
            }

            // Cập nhật tổng tem
            fillTotalItems($label.find('.item_1').length);

        });


        // CHỌN ĐƠN ĐẦU TIÊN MẶC ĐỊNH
        var $firstBill = $('#labelTabCurrent .bill-selector').first();

        if (!$firstBill.length) {
            $firstBill = $('#labelTabSuspended .bill-selector').first();

            if ($firstBill.length) {
                $('.label-tab').removeClass('active');
                $('.label-tab[data-label-tab="suspended"]').addClass('active');

                $('.label-tab-content').removeClass('active');
                $('#labelTabSuspended').addClass('active');
            }
        }

        if ($firstBill.length) {
            $firstBill.trigger('click');
        } else {
            fillTotalItems(0);
        }
    });

    

</script>
<script>
document.querySelectorAll('.floating-print-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    window.print(); // hoặc gọi hàm in hiện tại nếu bạn có
  });
});
</script>
