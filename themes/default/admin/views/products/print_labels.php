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
                        echo '<div id="total_items" class="no-print" style="color: green"><h2><strong>(1-'.round($total_items/2).')</strong></h2></div>';
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
        
        $(document).on('click', '.item', function () {
            var id = $(this).attr('id');
            $(this).remove();
            total_items = total_items -1;
            $("#total_items").html("<h2><strong>(1-" + Math.round(total_items/2) + ")</strong></h2>")
        });
    });

</script>