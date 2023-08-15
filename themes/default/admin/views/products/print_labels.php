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
                        
                            echo '<div class="barcode">';
                            
                        
                        $total_items = 0;
                        //$this->sma->print_arrays($barcodes);
                        foreach ($barcodes as $item) {
                            // /echo 'xxx';
                            //for ($r = 1; $r <= $item['quantity']; $r++) {
                                $total_items++;
                                //if($item['increase_size']) {
                                    $increase_size = "increase_size";
                                    if ($item['comment']) {
                                        $increase_size .= '_1';
                                    }
                                //} 
                                $str_padding = '';
                                if ($item['variants'] == 'L') {
                                    $str_padding = ' style="padding-top: 10px;"';                                            
                                }                                       
                                echo '<div class="item_1 style' . $style . ' ' . $valign_middle . '" '.
                                ($style == 50 && $this->input->post('cf_width') && $this->input->post('cf_height') ?
                                    'style="width:'.$this->input->post('cf_width').'in;height:'.$this->input->post('cf_height').'in;border:0;"' : '')
                                .'>';
                                
                                if($item['name']) {
                                    $str_comment = '';
                                    $str_comment_style = '';
                                    if($item['comment'] && $item['comment'] != '' && $item['comment'] != 'undefined') {
                                        //echo '<h5 style="border-top: 2px dotted black; padding-top: 4px; margin: 0; font-size: 11px;">';
                                        $str_comment = '<br /><span style="font-size: 10px;"><strong>' . $item['comment'] . '</strong></span>';
                                        $str_comment_style = 'border-bottom: 2px dotted black;';
                                        //echo '</h5>';
                                    }
                                    echo '<span style="position: absolute; top: 0;'.$str_comment_style.'" class="barcode_name '.$increase_size.'">'.$item['name'];
                                    echo $str_comment;
                                    echo '</span>';
                                }
                                /*if($item['comment'] && $item['comment'] != '' && $item['comment'] != 'undefined') {
                                    echo '<h5 style="border-top: 2px dotted black; padding-top: 4px; margin: 0; font-size: 11px;">';
                                    echo ' <strong>' . $item['comment'] . '</strong>';
                                    echo '</h5>';
                                }*/
                                echo '<h4 style="margin: 1px; position: absolute; bottom: 0; font-size: 16px;">';
                                //echo '<span class="text_size"'.$str_padding.'>';
                                echo '<strong>';
                                    
                                if ($item['variants'] == 'size L') {
                                    echo 'L';
                                } elseif ($item['variants'] == 'size M')  {
                                    echo 'M';
                                } else {
                                    echo $item['variants'];
                                }
                                echo '</strong>';
                                //echo '</span>';
                                echo '<span class="text_price">' . $this->sma->formatMoney($item['price']);
                                echo '</span>';
                                echo '</h4>';
                                
                                
                                
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
                        echo '<div id="total_items" class="no-print" style="color: green"><h1><strong>(1-'.round($total_items/2).')</strong></h1><h2> "Click vào tem để xóa"</h2></div>';
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
            $("#total_items").html("<h1><strong>(1-" + Math.round(total_items/2) + ")</strong></h1><h2>""Click vào tem để xóa""</h2>")
        });
    });

</script>