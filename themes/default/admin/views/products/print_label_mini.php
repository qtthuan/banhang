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
                        foreach ($sales as $sale) {
                            echo '<button type="button" class="btn btn-danger" id="'.$sale->id.'" style="height:40px; width: 140px; font-size: 18px;">';
                            echo $sale->reference_no;
                            echo '</button>&nbsp;&nbsp;';
                        }
                        echo '</div>';
                        foreach($items as $key => $values) {
                            //echo $key;
                            echo '<div class="barcode div'.$key.'">';
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
                                    
                                    
                                 
                            }
                            echo '</div>';
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
    });

</script>