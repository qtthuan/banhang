<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-body">

            <div class="row">
                <div class="col-xs-5">
                    <img id="pr-image" src="<?= base_url() ?>assets/uploads/<?= $product->image ?>"
                    alt="<?= $product->name ?>" class="img-responsive img-thumbnail"/>

                    <div id="multiimages" class="padding10">
                        <?php if (!empty($images)) {
                            echo '<a class="img-thumbnail change_img" href="' . base_url() . 'assets/uploads/' . $product->image . '" style="margin-right:5px;"><img class="img-responsive" src="' . base_url() . 'assets/uploads/thumbs/' . $product->image . '" alt="' . $product->image . '" style="width:71px; height:71px;" /></a>';
                            foreach ($images as $ph) {
                                echo '<div class="gallery-image"><a class="img-thumbnail change_img" href="' . base_url() . 'assets/uploads/' . $ph->photo . '" style="margin-right:5px;"><img class="img-responsive" src="' . base_url() . 'assets/uploads/thumbs/' . $ph->photo . '" alt="' . $ph->photo . '" style="width:71px; height:71px;" /></a>';
                                if ($Owner || $Admin || $GP['products-edit']) {
                                    echo '<a href="#" class="delimg" data-item-id="'.$ph->id.'"><i class="fa fa-2x fa-times"></i></a>';
                                }
                                echo '</div>';
                            }
                        }
                        ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-xs-7">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped dfTable table-right-left">
                            <tbody>

                                <tr>
                                    <td style="width:30%;"><?= lang("name"); ?></td>
                                    <td style="width:70%; color: red"><?= $product->name; ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang("code"); ?></td>
                                    <td><?= $product->code; ?></td>
                                </tr>
                                <!--<tr>
                                    <td><?= lang("category"); ?></td>
                                    <td><?= $category->name; ?></td>
                                </tr>-->
                                <?php if ($product->subcategory_id) { ?>
                                    <tr>
                                        <td><?= lang("subcategory"); ?></td>
                                        <td><?= $subcategory->name; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if ($Owner || $Admin || $this->session->userdata('show_cost')) {
                                        //if ($this->session->userdata('show_cost')) {
                                            echo '<tr><td>' . lang("cost") . '</td><td class="cost_hover"><span class="cost_click">*****</span><span class="cost_details">' . $this->sma->formatMoney($product->cost) . '</span></td></tr>';
                                        //  }
                                        echo '<tr><td>' . lang("price") . '</td><td>' . $this->sma->formatMoney($product->price) . '</td></tr>';
                                        if ($product->promotion) {
                                            echo '<tr><td>' . lang("promotion") . '</td><td>' . $this->sma->formatMoney($product->promo_price) . ' ('.$this->sma->hrsd($product->start_date).' - '.$this->sma->hrsd($product->end_date).')</td></tr>';
                                        }
                                    } else {
                                        if ($this->session->userdata('show_cost')) {
                                            //echo '<tr><td>' . lang("cost") . '</td><td>' . $this->sma->formatMoney($product->cost) . '</td></tr>';
                                        }
                                        if ($this->session->userdata('show_price')) {
                                            echo '<tr><td>' . lang("price") . '</td><td>' . $this->sma->formatMoney($product->price) . '</td></tr>';
                                            if ($product->promotion) {
                                                echo '<tr><td>' . lang("promotion") . '</td><td>' . $this->sma->formatMoney($product->promo_price) . ' ('.$this->sma->hrsd($product->start_date).' - '.$this->sma->hrsd($product->start_date).')</td></tr>';
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?= lang("created_date"); ?></td>
                                        <td><?= $this->sma->hrld($product->created_date); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= lang("suppliers"); ?></td>
                                        <td>
                                        <?php
                                        foreach ($suppliers as $supplier) {
                                            if ($supplier->id) {
                                                echo '<span class="padding05">'. $supplier->company . '</span>';
                                            }
                                        } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= lang("brand"); ?></td>
                                        <td>
                                            <span class="padding05"><?= $brand->name; ?></span>
                                        </td>
                                    </tr>

                                <a href="<?= $customer->fb_link; ?>" target="_blank">
                                    <span style="color: green; font-size: 14px;"><?= $customer->fb_link; ?></span>
                                </a>



                                    <?php if ($product->tax_rate) { ?>
                                    <tr>
                                        <td><?= lang("tax_rate"); ?></td>
                                        <td><?= $tax_rate->name; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= lang("tax_method"); ?></td>
                                        <td><?= $product->tax_method == 0 ? lang('inclusive') : lang('exclusive'); ?></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if ($product->alert_quantity != 0) { ?>
                                    <tr>
                                        <td><?= lang("alert_quantity"); ?></td>
                                        <td><?= $this->sma->formatQuantity($product->alert_quantity); ?></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if ($variants) { ?>
                                    <tr>
                                        <td><?= lang("product_variants"); ?></td>
                                        <td><?php foreach ($variants as $variant) {
                                            echo '<span class="label label-primary">' . $variant->name . '</span> ';
                                        } ?></td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-5">
                                <?php if ($product->cf1 || $product->cf2 || $product->cf3 || $product->cf4 || $product->cf5 || $product->cf6) { ?>
                                <h3 class="bold"><?= lang('custom_fields') ?></h3>
                                <div class="table-responsive">
                                    <table
                                    class="table table-bordered table-striped table-condensed dfTable two-columns">
                                    <thead>
                                        <tr>
                                            <th><?= lang('custom_field') ?></th>
                                            <th><?= lang('value') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($product->cf1) {
                                            echo '<tr><td>' . lang("pcf1") . '</td><td>' . $product->cf1 . '</td></tr>';
                                        }
                                        if ($product->cf2) {
                                            echo '<tr><td>' . lang("pcf2") . '</td><td>' . $product->cf2 . '</td></tr>';
                                        }
                                        if ($product->cf3) {
                                            echo '<tr><td>' . lang("pcf3") . '</td><td>' . $product->cf3 . '</td></tr>';
                                        }
                                        if ($product->cf4) {
                                            echo '<tr><td>' . lang("pcf4") . '</td><td>' . $product->cf4 . '</td></tr>';
                                        }
                                        if ($product->cf5) {
                                            echo '<tr><td>' . lang("pcf5") . '</td><td>' . $product->cf5 . '</td></tr>';
                                        }
                                        if ($product->cf6) {
                                            echo '<tr><td>' . lang("pcf6") . '</td><td>' . $product->cf6 . '</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>

                            <?php if ((!$Supplier || !$Customer) && !empty($warehouses) && $product->type == 'standard') { ?>
                            <h3 class="bold"><?= lang('warehouse_quantity') ?><a id="btn_original" class="btn tip" title="<?= lang('lbl_original_quantity') ?>" data-placement="bottom">
                                <i class="fa fa-list"></i>
                            </a></h3>
                            <div class="table-responsive">
                                <table
                                class="table table-bordered table-striped table-condensed dfTable three-columns">
                                <thead>
                                    <tr>
                                        <th><?= lang('warehouse_name') ?></th>
                                        <th><?= lang('quantity') . ' (' . lang('rack') . ')'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($warehouses as $warehouse) {
                                        if ($warehouse->quantity != 0) {
                                            echo '<tr><td title="' . $warehouse->name . '">' . $warehouse->name . '</td><td><strong>' . $this->sma->formatQuantity($warehouse->quantity) . '</strong>' . ($warehouse->rack ? ' (' . $warehouse->rack . ')' : '') . '</td></tr>';
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-xs-7">
                        <?php if ($product->type == 'combo') { ?>
                        <h3 class="bold"><?= lang('combo_items') ?></h3>
                        <div class="table-responsive">
                            <table
                            class="table table-bordered table-striped table-condensed dfTable two-columns">
                            <thead>
                                <tr>
                                    <th><?= lang('product_name') ?></th>
                                    <th><?= lang('quantity') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($combo_items as $combo_item) {
                                    echo '<tr><td>' . $combo_item->name . ' (' . $combo_item->code . ') </td><td>' . $this->sma->formatQuantity($combo_item->qty) . '</td></tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                    <?php if (!empty($options)) { ?>
                    <h3 class="bold"><?= lang('product_variants_quantity'); ?></h3>
                    <div class="table-responsive">
                        <table
                        class="table table-bordered table-striped table-condensed dfTable">
                        <thead>
                            <tr>
                                <th><?= lang('warehouse_name') ?></th>
                                <th><?= lang('product_variant'); ?></th>
                                <th><?= lang('quantity') . ' (' . lang('rack') . ')'; ?></th>
                                <?php if ($Owner || $Admin || $this->session->userdata('show_cost')) {
                                    echo '<th>' . lang('cost') . '</th>';
                                } ?>
                                <?php if ($Owner || $Admin || $this->session->userdata('show_price')) {
                                    echo '<th>' . lang('price_addition') . '</th>';
                                } ?>
                                <?php if ($Owner || $Admin || $this->session->userdata('show_price')) {
                                    echo '<th>' . lang('variant_promo_price') . '</th>';
                                } ?>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($options as $option) {
                                $str_promo_style = '';
                                    if ($option->promo_price > 0) {
                                        $str_promo_style = ' style="background-color: #fff8ba"';
                                    }
                                //if ($option->wh_qty != 0) {
                                    echo '<tr><td>' . $option->wh_name . '</td><td>' . $option->name . '</td><td class="text-center">' . $this->sma->formatQuantity($option->wh_qty) .'<sup class="col_original" style="display: none; font-weight: bold; color: green; font-size: 14px;">' . $this->sma->formatQuantity($option->wh_original_qty) . '</sup></td>';
                                    if ($Owner || $Admin || $this->session->userdata('show_cost')) {
                                        echo '<td class="text-right">' . $this->sma->formatMoney($option->cost) . '</td>';
                                    }
                                    if ($Owner || $Admin || $this->session->userdata('show_price')) {
                                        echo '<td class="text-right">' . $this->sma->formatMoney($option->price) . '</td>';
                                    }
                                    if ($Owner || $Admin || $this->session->userdata('show_price')) {
                                        echo '<td class="text-right"' . $str_promo_style . '>' . $this->sma->formatMoney($option->promo_price) . '</td>';
                                    }
                                    echo '</tr>';
                                //}

                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-xs-12">

        <?= $product->details ? '<div class="panel panel-success"><div class="panel-heading">' . lang('product_details_for_invoice') . '</div><div class="panel-body">' . $product->details . '</div></div>' : ''; ?>
        <?= $product->product_details ? '<div class="panel panel-primary"><div class="panel-heading">' . lang('product_details') . '</div><div class="panel-body">' . $product->product_details . '</div></div>' : ''; ?>

    </div>
</div>
<?php if (!$Supplier || !$Customer) { ?>
    <div class="buttons">
        <div class="btn-group btn-group-justified">
            <div class="btn-group">
                <a href="<?= admin_url('products/print_barcodes/' . $product->id) ?>" class="tip btn btn-primary" title="<?= lang('print_barcode_label') ?>">
                    <i class="fa fa-print"></i>
                    <span class="hidden-sm hidden-xs"><?= lang('print_barcode_label') ?></span>
                </a>
            </div>
            <div class="btn-group">
                <a href="<?= admin_url('products/pdf/' . $product->id) ?>" class="tip btn btn-primary" title="<?= lang('pdf') ?>">
                    <i class="fa fa-download"></i>
                    <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                </a>
            </div>
            <div class="btn-group">
                <a href="<?= admin_url('products/edit/' . $product->id) ?>" class="tip btn btn-warning tip" title="<?= lang('edit_product') ?>">
                    <i class="fa fa-edit"></i>
                    <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                </a>
            </div>
            <div class="btn-group">
                <a href="#" class="tip btn btn-danger bpo" title="<b><?= lang("delete_product") ?></b>"
                    data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= admin_url('products/delete/' . $product->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                    data-html="true" data-placement="top">
                    <i class="fa fa-trash-o"></i>
                    <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
                </a>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function () {
        $('.tip').tooltip();
    });
    </script>
<?php } ?>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.cost_details').hide();
    $('.change_img').click(function(event) {
        event.preventDefault();
        var img_src = $(this).attr('href');
        $('#pr-image').attr('src', img_src);
        return false;
    });
   


    $( '.cost_hover' ).hover(
        function() {
            $('.cost_click').hide();
            $('.cost_details').show();
        }, function() {
            $('.cost_click').show();
        $('.cost_details').hide();
        }
    );


    $("#btn_original").click(function(){
        $(".col_original").slideToggle();
        $(".wh_col_original").slideToggle();
    });
});
</script>
