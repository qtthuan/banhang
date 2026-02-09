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
