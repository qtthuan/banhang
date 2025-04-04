<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
if (!empty($variants)) {
    foreach ($variants as $variant) {
        $vars[] = addslashes($variant->name);
    }
} else {
    $vars = array();
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.gen_slug').change(function(e) {
            getSlug($(this).val(), 'products');
        });
        $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
            placeholder: "<?= lang('select_category_to_load') ?>", data: [
                {id: '', text: '<?= lang('select_category_to_load') ?>'}
            ]
        });
        $('#category').change(function () {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= admin_url('products/getSubCategories') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            scdata.push({id: '', text: '<?= lang('select_subcategory') ?>'});
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                minimumResultsForSearch: 7,
                                data: scdata
                            });
                        } else {
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('no_subcategory') ?>").select2({
                                placeholder: "<?= lang('no_subcategory') ?>",
                                minimumResultsForSearch: 7,
                                data: [{id: '', text: '<?= lang('no_subcategory') ?>'}]
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
                    placeholder: "<?= lang('select_category_to_load') ?>",
                    minimumResultsForSearch: 7,
                    data: [{id: '', text: '<?= lang('select_category_to_load') ?>'}]
                });
            }
            $('#modal-loading').hide();
        });
        $('#code').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('edit_product'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('update_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo admin_form_open_multipart("products/edit/" . $product->id, $attrib)
                ?>
                <div class="col-md-5">
                    <div class="form-group">
                        <?= lang("product_type", "type") ?>
                        <?php
                        $opts = array('standard' => lang('standard'), 'combo' => lang('combo'), 'digital' => lang('digital'), 'service' => lang('service'));
                        echo form_dropdown('type', $opts, (isset($_POST['type']) ? $_POST['type'] : ($product ? $product->type : '')), 'class="form-control" id="type" required="required"');
                        ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("category", "category") ?>
                        <?php
                        $cat[''] = "";
                        foreach ($categories as $category) {
                            $cat[$category->id] = $category->name;
                        ?>
                            <input type="hidden" id="category_code<?=$category->id?>" name="category_code<?=$category->id?>" value="<?=$category->code?>" />
                        <?php
                        }
                        echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ($product ? $product->category_id : '')), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" required="required" style="width:100%"')
                        ?>
                    </div>
                    <div class="form-group">
                        <?= lang("product_code", "code") ?>
                        <div class="input-group">
                            <?= form_input('code', (isset($_POST['code']) ? $_POST['code'] : ($product ? $product->code : '')), 'class="form-control" id="code" readonly="readonly"  required="required"') ?>
                            <input type="hidden" id="code_extra" name="code_extra" value="<?=(isset($_POST['code_extra']) ? $_POST['code_extra'] : ($product ? $product->code_extra : ''))?>" />
                            <button type="button" class="btn btn-warning btn-block" id="generate_code" style="height:37px; font-size: 18px;">
                                <i class="fa fa-barcode" style="margin-right: 5px;"></i><?=lang('generate_new_barcode');?>
                            </button>
                        </div>
                    </div>

                    <div class="form-group all">
                        <?= lang("product_name", "name") ?>
                        <?= form_input('name', (isset($_POST['name']) ? $_POST['name'] : ($product ? $product->name : '')), 'class="form-control gen_slug" id="name" required="required"'); ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_name_en", "name_en") ?>
                        <?= form_input('name_en', (isset($_POST['name_en']) ? $_POST['name_en'] : ($product ? $product->name_en : '')), 'class="form-control" id="name_en"'); ?>
                    </div>
                    <div class="form-group standard">
                        <?= lang("product_cost", "cost") ?>
                        <?= form_input('cost', (isset($_POST['cost']) ? $_POST['cost'] : ($product ? $this->sma->formatDecimal($product->cost) : '')), 'class="form-control tip" id="cost" required="required"') ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_price", "price") ?>
                        <?= form_input('price', (isset($_POST['price']) ? $_POST['price'] : ($product ? $this->sma->formatDecimal($product->price) : '')), 'class="form-control tip" id="price" required="required"') ?>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" class="checkbox" value="1" name="promotion" id="promotion" <?= $this->input->post('promotion') ? 'checked="checked"' : ''; ?>>
                        <label for="promotion" class="padding05">
                            <?= lang('promotion'); ?>
                        </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="featured" type="checkbox" class="checkbox" id="featured" value="1" <?= empty($product->featured) ? '' : 'checked="checked"' ?>/>
                        <label for="featured" class="padding05"><?= lang('featured') ?></label>
                    </div>

                    <div id="promo"<?= $product->promotion ? '' : ' style="display:none;"'; ?>>
                        <div class="well well-sm">
                            <div class="form-group">
                                <?= lang('promo_price', 'promo_price'); ?>
                                <?= form_input('promo_price', set_value('promo_price', $product->promo_price ? $this->sma->formatDecimal($product->promo_price) : ''), 'class="form-control tip" id="promo_price"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('start_date', 'start_date'); ?>
                                <?= form_input('start_date', set_value('start_date', $product->start_date ? $this->sma->hrsd($product->start_date) : ''), 'class="form-control tip date" id="start_date"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('end_date', 'end_date'); ?>
                                <?= form_input('end_date', set_value('end_date', $product->end_date ? $this->sma->hrsd($product->end_date) : ''), 'class="form-control tip date" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_image", "product_image") ?>
                        <input id="product_image" type="file" data-browse-label="<?= lang('browse'); ?>" name="product_image" data-show-upload="false"
                               data-show-preview="false" accept="image/*" class="form-control file">
                    </div>

                    <div class="form-group all">
                        <?= lang("product_gallery_images", "images") ?>
                        <input id="images" type="file" data-browse-label="<?= lang('browse'); ?>" name="userfile[]" multiple="true" data-show-upload="false"
                               data-show-preview="false" class="form-control file" accept="image/*">
                    </div>
                    <div id="img-details"></div>
                    
                    <div class="form-group all">
                        <?= lang('slug', 'slug'); ?>
                        <?= form_input('slug', set_value('slug', ($product ? $product->slug : '')), 'class="form-control tip" id="slug" required="required"'); ?>
                    </div>
                    
                    <div class="form-group standard">
                        <?= lang('product_unit', 'unit'); ?>
                        <?php
                        $pu[''] = lang('select').' '.lang('unit');
                        foreach ($base_units as $bu) {
                            $pu[$bu->id] = $bu->name .' ('.$bu->code.')';
                        }
                        ?>
                        <?= form_dropdown('unit', $pu, set_value('unit', $product->unit), 'class="form-control tip" required="required" id="unit" style="width:100%;"'); ?>
                    </div>

                    <div class="form-group standard">
                        <?= lang("alert_quantity", "alert_quantity") ?>
                        <div
                            class="input-group"> <?= form_input('alert_quantity', (isset($_POST['alert_quantity']) ? $_POST['alert_quantity'] : ($product ? $this->sma->formatDecimal($product->alert_quantity) : '')), 'class="form-control tip" id="alert_quantity"') ?>
                            <span class="input-group-addon">
                            <input type="checkbox" name="track_quantity" id="inlineCheckbox1"
                                value="1" <?= ($product ? (!empty($product->track_quantity) ? 'checked="checked"' : '') : 'checked="checked"') ?>>
                        </span>
                        </div>
                    </div>
                    <div class="form-group all">
                        <?= lang("barcode_symbology", "barcode_symbology") ?>
                        <?php
                        $bs = array('code25' => 'Code25', 'code39' => 'Code39', 'code128' => 'Code128', 'ean8' => 'EAN8', 'ean13' => 'EAN13', 'upca' => 'UPC-A', 'upce' => 'UPC-E');
                        echo form_dropdown('barcode_symbology', $bs, (isset($_POST['barcode_symbology']) ? $_POST['barcode_symbology'] : ($product ? $product->barcode_symbology : 'code128')), 'class="form-control select" id="barcode_symbology" required="required" style="width:100%;"');
                        ?>
                    </div>

                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group all">
                            <?= lang("product_tax", "tax_rate") ?>
                            <?php
                            $tr[""] = "";
                            foreach ($tax_rates as $tax) {
                                $tr[$tax->id] = $tax->name;
                            }
                            echo form_dropdown('tax_rate', $tr, (isset($_POST['tax_rate']) ? $_POST['tax_rate'] : ($product ? $product->tax_rate : $Settings->default_tax_rate)), 'class="form-control select" id="tax_rate" placeholder="' . lang("select") . ' ' . lang("product_tax") . '" style="width:100%"')
                            ?>
                        </div>
                        <div class="form-group all">
                            <?= lang("tax_method", "tax_method") ?>
                            <?php
                            $tm = array('0' => lang('inclusive'), '1' => lang('exclusive'));
                            echo form_dropdown('tax_method', $tm, (isset($_POST['tax_method']) ? $_POST['tax_method'] : ($product ? $product->tax_method : '')), 'class="form-control select" id="tax_method" placeholder="' . lang("select") . ' ' . lang("tax_method") . '" style="width:100%"')
                            ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-6 col-md-offset-1">
                    <div class="standard">
                        <div>
                            <?php
                            if (!empty($warehouses) || !empty($warehouses_products)) {
                                echo '<div class="row"><div class="col-md-12"><div class="well">';
                                echo '<p><strong>'.lang("warehouse_quantity").'</strong></p>';
                                if (!empty($warehouses_products)) {
                                    foreach ($warehouses_products as $wh_pr) {
                                        echo '<span class="bold text-info">' . $wh_pr->name . ': <input type="hidden" value="'.$this->sma->formatDecimal($wh_pr->quantity).'" id="vwh_qty_' . $wh_pr->id . '"><span class="padding05" id="rwh_qty_' . $wh_pr->id . '">' . $this->sma->formatQuantity($wh_pr->quantity) . '</span>' . ($wh_pr->rack ? ' (<span class="padding05" id="rrack_' . $wh_pr->id . '">' . $wh_pr->rack . '</span>)' : '') . '</span><br>';
                                    }
                                }
                                echo '<div class="clearfix"></div></div></div></div>';
                            }
                            ?>
                        </div>
                        <div class="clearfix"></div>

                        <div id="attrs"></div>
                        <div class="well well-sm">
                            <?php
                            if ($product_options) { ?>
                            <table class="table table-bordered table-condensed table-striped"
                                   style="<?= $this->input->post('attributes') || $product_options ? '' : 'display:none;'; ?> margin-top: 10px;">
                                <thead>
                                <tr class="active">
                                    <th><?= lang('name') ?></th>
                                    <!-- <th><?= lang('warehouse') ?></th> -->
                                    <th><?= lang('quantity') ?></th>                                    
                                    <th><?= lang('cost') ?></th>
                                    <th><?= lang('price_addition') ?></th>
                                    <th><?= lang('variant_promo_price') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($product_options as $option) {
                                    $str_promo_style = '';
                                    if ($option->promo_price > 0) {
                                        $str_promo_style = ' style="background-color: #fff8ba"';
                                    }
                                    echo '<tr>
                                    <td class="col-xs-2"><input type="hidden" name="attr_id[]" value="' . $option->id . '"><span>' . $option->name . '</span></td>
                                    <td class="quantity text-center col-xs-2"><span>' . $this->sma->formatQuantity($option->wh_qty) . '</span></td>
                                    <td class="price text-right col-xs-2">' . $this->sma->formatMoney($option->cost) . '</td>
                                    <td class="price text-right col-xs-2">' . $this->sma->formatMoney($option->price) . '</td>
                                    <td class="price text-right col-xs-2" '.$str_promo_style.'>' . $this->sma->formatMoney($option->promo_price) . '</td></tr>';
                                }
                            ?>
                            </tbody>
                            </table>
                            <?php
                            }
                            if ($product_variants) { ?>
                                <h3 class="bold"><?=lang('update_variants');?></h3>
                                <table class="table table-bordered table-condensed table-striped" style="margin-top: 10px;">
                                <thead>
                                <tr class="active">
                                    <th class="col-xs-3"><?= lang('name') ?></th>                                    
                                    <th class="col-xs-3"><?= lang('cost') ?></th>
                                    <th class="col-xs-3"><?= lang('price_addition') ?></th>
                                    <th class="col-xs-3" style="background-color: #008b8b;"><?= lang('variant_promo_price') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                //$this->sma->print_arrays($product_variants);
                                foreach ($product_variants as $pv) {
                                     echo '<tr><td class="col-xs-3"><input type="hidden" name="variant_id_' . $pv->id . '" value="' . $pv->id . '"><input type="text" name="variant_name_' . $pv->id . '" value="' . $pv->name . '" class="form-control"></td><td class="cost text-right col-xs-2"><input type="text" name="variant_cost_' . $pv->id . '" value="' . $this->sma->formatDecimal($pv->cost) . '" class="form-control"></td><td class="price text-right col-xs-2"><input type="text" name="variant_price_' . $pv->id . '" value="' . $this->sma->formatDecimal($pv->price) . '" class="form-control"></td><td class="price text-right col-xs-2"><input type="text" name="variant_promo_price_' . $pv->id . '" value="' . $this->sma->formatDecimal($pv->promo_price) . '" class="form-control"></td></tr>';
                                }
                                ?>
                                </tbody>
                                </table>
                                <?php
                            }
                            ?>
                            <div class="form-group">
                                <input type="checkbox" class="checkbox" name="attributes" id="attributes" <?= $this->input->post('attributes') ? 'checked="checked"' : ''; ?>>
                                <label for="attributes" class="padding05"><?= lang('add_more_variants'); ?></label>
                                <?= lang('eg_sizes_colors'); ?>
                            </div>

                            <div id="attr-con" <?= $this->input->post('attributes') ? '' : 'style="display:none;"'; ?>>
                                <div class="form-group all">
                                    <div class="form-group">
                                        <input type="checkbox" class="checkbox" name="sizegroups" id="sizegroups"><label
                                                for="sizegroups"
                                                class="padding05"><?= lang('choose_size_group'); ?></label>
                                    </div>
                                    <div id="sizegroup-con" style="display: none">
                                        <?php
                                        $size[''] = "";
                                        foreach ($size_groups as $size_group) {
                                            $size[$size_group->description] = $size_group->name;
                                        }
                                        echo form_dropdown('size_group', $size, (isset($_POST['size_group']) ? $_POST['size_group'] : ''), 'class="form-control select" id="size_group" placeholder="' . lang("select") . " " . lang("size_group") . '" required="required" style="width:100%"')
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group" id="ui" style="margin-bottom: 0;">
                                    <div class="input-group">
                                    <?php
                                        echo form_input('attributesInput', '', 'class="form-control select-tags" id="attributesInput" placeholder="' . $this->lang->line("enter_attributes") . '"'); 
                                        echo form_input('attr_cost', '', 'class="form-control" id="attr_cost" placeholder="' . lang('cost') . '"');
                                        echo form_input('added_price', '', 'class="form-control" id="added_price" placeholder="' . lang('price_addition') . '"');
                                    ?>
                                        <div class="input-group-addon" style="padding: 2px 5px;">
                                            <a href="#" id="addAttributes">
                                                <i class="fa fa-4x fa-plus-circle" id="addIcon"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                                <div class="table-responsive">
                                <div id="multipleSizes" style="<?= $this->input->post('attributes') || $product_options ? '' : 'display:none;'; ?>">
                                    <button type="button" class="btn btn-success multiplex" id="0" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i><?= lang('quantity') ?>=0
                                    </button>
                                    <button type="button" class="btn btn-danger multiplex" id="1" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i><?= lang('quantity') ?>=1
                                    </button>
                                    <button type="button" class="btn btn-primary multiplex" id="2" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i>2X
                                    </button>
                                    <button type="button" class="btn btn-warning multiplex" id="3" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i>3X
                                    </button>
                                    <button type="button" class="btn btn-success multiplex" id="4" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i>4X
                                    </button>
                                    <button type="button" class="btn btn-info multiplex" id="5" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i>5X
                                    </button>
                                    <button type="button" class="btn btn-primary multiplex" id="10" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i>10X
                                    </button>
                                    <button type="button" class="btn btn-primary multiplex" id="15" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i>15X
                                    </button>
                                    <button type="button" class="btn btn-primary multiplex" id="20" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i>20X
                                    </button>
                                    <button type="button" class="btn btn-primary multiplex" id="50" style="height:37px; font-size: 18px;">
                                        <i class="fa"></i>50X
                                    </button>
                                </div>
                                    <table id="attrTable" class="table table-bordered table-condensed table-striped" style="margin-bottom: 0; margin-top: 10px;">
                                        <thead>
                                            <tr class="active">
                                                <th><?= lang('name') ?></th>
                                                <th><?= lang('warehouse') ?></th>
                                                <th><?= lang('quantity') ?></th>                                                
                                                <th><?= lang('cost') ?></th>
                                                <th><?= lang('price_addition') ?></th>
                                                <th><i class="fa fa-times attr-remove-all"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody><?php
                                            if ($this->input->post('attributes')) {
                                                $a = sizeof($_POST['attr_name']);
                                                for ($r = 0; $r <= $a; $r++) {
                                                    if (isset($_POST['attr_name'][$r]) && (isset($_POST['attr_warehouse'][$r]) || isset($_POST['attr_quantity'][$r]))) {
                                                        echo '<tr class="attr">
                                                        <td><input type="hidden" name="attr_name[]" value="' . $_POST['attr_name'][$r] . '"><span>' . $_POST['attr_name'][$r] . '</span></td>
                                                        <td class="code text-center"><input type="hidden" name="attr_warehouse[]" value="' . (isset($_POST['attr_warehouse'][$r]) ? $_POST['attr_warehouse'][$r] : '') . '"><input type="hidden" name="attr_wh_name[]" value="' . (isset($_POST['attr_wh_name'][$r]) ? $_POST['attr_wh_name'][$r] : '') . '"><span>' . (isset($_POST['attr_wh_name'][$r]) ? $_POST['attr_wh_name'][$r] : '') . '</span></td>
                                                        <td class="quantity text-center"><input type="hidden" name="attr_quantity[]" value="' . $_POST['attr_quantity'][$r] . '"><span>' . $_POST['attr_quantity'][$r] . '</span></td>
                                                        <td class="cost text-right"><input type="hidden" name="attr_cost[]" value="e' . $_POST['attr_cost'][$r] . '"><span>' . $_POST['attr_cost'][$r] . '</span></span></td><td class="text-center"><i class="fa fa-times delAttr"></i></td>
                                                        <td class="price text-right"><input type="hidden" name="attr_price[]" value="' . $_POST['attr_price'][$r] . '"><span>' . $_POST['attr_price'][$r] . '</span></span></td><td class="text-center"></td>
                                                    </tr>';
                                                }
                                            }
                                        }
                                        ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                    <div class="combo" style="display:none;">

                        <div class="form-group">
                            <?= lang("add_product", "add_item") . ' (' . lang('not_with_variants') . ')'; ?>
                            <?php echo form_input('add_item', '', 'class="form-control ttip" id="add_item" data-placement="top" data-trigger="focus" data-bv-notEmpty-message="' . lang('please_add_items_below') . '" placeholder="' . $this->lang->line("add_item") . '"'); ?>
                        </div>
                        <div class="control-group table-group">
                            <label class="table-label" for="combo"><?= lang("combo_products"); ?></label>
                            <!--<div class="row"><div class="ccol-md-10 col-sm-10 col-xs-10"><label class="table-label" for="combo"><?= lang("combo_products"); ?></label></div>
                            <div class="ccol-md-2 col-sm-2 col-xs-2"><div class="form-group no-help-block" style="margin-bottom: 0;"><input type="text" name="combo" id="combo" value="" data-bv-notEmpty-message="" class="form-control" /></div></div></div>-->
                            <div class="controls table-controls">
                                <table id="prTable"
                                       class="table items table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="col-md-5 col-sm-5 col-xs-5"><?= lang('product') . ' (' . lang('code') .' - '.lang('name') . ')'; ?></th>
                                        <th class="col-md-2 col-sm-2 col-xs-2"><?= lang("quantity"); ?></th>
                                        <th class="col-md-3 col-sm-3 col-xs-3"><?= lang("unit_price"); ?></th>
                                        <th class="col-md-1 col-sm-1 col-xs-1 text-center">
                                            <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="digital" style="display:none;">
                        <?php
                        if (filter_var($product->file, FILTER_VALIDATE_URL) === FALSE) {
                            $file = $product->file;
                            $file_link = '';
                        } else {
                            $file_link = $product->file;
                            $file = '';
                        }
                        ?>
                        <div class="form-group digital">
                            <?= lang("digital_file", "digital_file") ?>
                            <input id="digital_file" type="file" data-browse-label="<?= lang('browse'); ?>" name="digital_file" data-show-upload="false"
                                   data-show-preview="false" class="form-control file">
                        </div>
                        <div class="form-group digital">
                            <?= lang('file_link', 'file_link'); ?>
                            <?= form_input('file_link', $file_link, 'class="form-control" id="file_link"'); ?>
                        </div>
                    </div>

                    <div class="form-group all">
                        <?= lang('brand', 'brand') ?>
                        <?php
                        $br[''] = '';
                        foreach ($brands as $brand) {
                            $br[$brand->id] = $brand->name;
                        }
                        echo form_dropdown('brand', $br, (isset($_POST['brand']) ? $_POST['brand'] : ($product ? $product->brand : '')), 'class="form-control select" id="brand" placeholder="' . lang('select') . ' ' . lang('brand') . '" style="width:100%"')
                        ?>
                    </div>

                    <div class="form-group standard">
                        <div class="form-group">
                            <?= lang("supplier", "supplier") ?>
                            <button type="button" class="btn btn-primary btn-xs" id="addSupplier"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <div class="row" id="supplier-con">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <?php
                                    echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control ' . ($product ? '' : 'suppliers') . '" id="' . ($product && ! empty($product->supplier1) ? 'supplier1' : 'supplier') . '" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%;"');
                                    ?>
                                </div>
                            </div>

                        </div>
                        <div id="ex-suppliers"></div>
                    </div>

                </div>

                <div class="col-md-12">

                    <div class="form-group all">
                        <?= lang("product_details", "product_details") ?>
                        <?= form_textarea('product_details', (isset($_POST['product_details']) ? $_POST['product_details'] : ($product ? $product->product_details : '')), 'class="form-control" id="details"'); ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_details_for_invoice", "details") ?>
                        <?= form_textarea('details', (isset($_POST['details']) ? $_POST['details'] : ($product ? $product->details : '')), 'class="form-control" id="details"'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_submit('edit_product', $this->lang->line("edit_product"), 'class="btn btn-primary"'); ?>
                    </div>

                </div>
                <?= form_close(); ?>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
        var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
        var items = {};
        <?php
        if($combo_items) {
            echo '
                var ci = '.json_encode($combo_items).';
                $.each(ci, function() { add_product_item(this); });
                ';
        }
        ?>
        <?=isset($_POST['cf']) ? '$("#extras").iCheck("check");': '' ?>
        $('#extras').on('ifChecked', function () {
            $('#extras-con').slideDown();
        });
        $('#extras').on('ifUnchecked', function () {
            $('#extras-con').slideUp();
        });

        <?= isset($_POST['promotion']) || $product->promotion ? '$("#promotion").iCheck("check");': '' ?>
        $('#promotion').on('ifChecked', function (e) {
            $('#promo').slideDown();
        });
        $('#promotion').on('ifUnchecked', function (e) {
            $('#promo').slideUp();
        });

        $('.attributes').on('ifChecked', function (event) {
            $('#options_' + $(this).attr('id')).slideDown();
        });
        $('.attributes').on('ifUnchecked', function (event) {
            $('#options_' + $(this).attr('id')).slideUp();
        });

        $('#sizegroups').on('ifChecked', function (event) {
            $('#sizegroup-con').slideDown();
            $(".select-tags").slideUp();
            $("#attributesInput").slideDown();
            $('#attributesInput').val('');
        });

        $('#sizegroups').on('ifUnchecked', function (event) {
            $('#sizegroup-con').slideUp();
            $(".select-tags").slideDown();
            $("#attributesInput").slideUp();
            $('#attributesInput').val('');
        });

        $('#category').change(function () {
            var v = $(this).val();
            if (v) {
                console.log('hghg: ' + v);
                $('#code').val(($('#category_code' + v).val()));
            }

        });
        $('#generate_code').click(function () {
            var v = $('#category').val();
            $('#modal-loading').show();
            if ($('#oldproduct').is(':checked')){
                bootbox.alert('<?= lang('oops_pls_enter_code') ?>');
                $('#code').focus();
                $('#modal-loading').hide();
            }
            else if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= admin_url('products/getMaxCodeByCategory') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        $('#code').val(scdata);
                        var code_extra = scdata.replace(/\'/g, '').split(/(\d+)/).filter(Boolean);
                        $('#code_extra').val(code_extra[1]);
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>' + error);
                        $('#modal-loading').hide();
                    }
                });
                $('#name').focus();
            } else {
                bootbox.alert('<?= lang('pls_select_category') ?>');
            }
            $('#modal-loading').hide();
        });

        //$('#cost').removeAttr('required');
        $('#type').change(function () {
            var t = $(this).val();
            if (t !== 'standard') {
                $('.standard').slideUp();
                $('#cost').attr('required', 'required');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
            } else {
                $('.standard').slideDown();
                $('#cost').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
            }
            if (t !== 'digital') {
                $('.digital').slideUp();
            } else {
                $('.digital').slideDown();
            }
            if (t !== 'combo') {
                $('.combo').slideUp();
                //$('#add_item').removeAttr('required');
                //$('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
            } else {
                $('.combo').slideDown();
                //$('#add_item').attr('required', 'required');
                //$('form[data-toggle="validator"]').bootstrapValidator('addField', 'add_item');
            }
        });

        $("#add_item").autocomplete({
            source: '<?= admin_url('products/suggestions'); ?>',
            minLength: 1,
            autoFocus: false,
            delay: 5,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_product_item(ui.item);
                    if (row) {
                        $(this).val('');
                        $('#add_item').removeAttr('required');
                        $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
                    }
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>');
                }
            }
        });
        $('#add_item').removeAttr('required');
        $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');

        function add_product_item(item) {
            if (item == null) {
                return false;
            }
            item_id = item.id;
            if (items[item_id]) {
                items[item_id].qty = (parseFloat(items[item_id].qty) + 1).toFixed(2);
            } else {
                items[item_id] = item;
            }

            $("#prTable tbody").empty();
            $.each(items, function () {
                var row_no = this.id;
                var newTr = $('<tr id="row_' + row_no + '" class="item_' + this.id + '"></tr>');
                tr_html = '<td><input name="combo_item_id[]" type="hidden" value="' + this.id + '"><input name="combo_item_name[]" type="hidden" value="' + this.name + '"><input name="combo_item_code[]" type="hidden" value="' + this.code + '"><span id="name_' + row_no + '">' + this.code + ' - ' + this.name + '</span></td>';
                tr_html += '<td><input class="form-control text-center rquantity" name="combo_item_quantity[]" type="text" value="' + formatQuantity2(this.qty) + '" data-id="' + row_no + '" data-item="' + this.id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                tr_html += '<td><input class="form-control text-center rprice" name="combo_item_price[]" type="text" value="' + formatDecimal(this.price) + '" data-id="' + row_no + '" data-item="' + this.id + '" id="combo_item_price_' + row_no + '" onClick="this.select();"></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#prTable");
            });
            $('.item_' + item_id).addClass('warning');
            //audio_success.play();
            return true;

        }

        function calculate_price() {
            var rows = $('#prTable').children('tbody').children('tr');
            var pp = 0;
            $.each(rows, function () {
                pp += formatDecimal(parseFloat($(this).find('.rprice').val())*parseFloat($(this).find('.rquantity').val()));
            });
            $('#price').val(pp);
            return true;
        }

        $(document).on('change', '.rquantity, .rprice', function () {
            calculate_price();
        });

        $(document).on('click', '.del', function () {
            var id = $(this).attr('id');
            delete items[id];
            $(this).closest('#row_' + id).remove();
            calculate_price();
        });

        var su = 2;
        $('#addSupplier').click(function () {
            if (su <= 5) {
                $('#supplier_1').select2('destroy');
                $('#supplier_1').select2('destroy');
                var html = '<div style="clear:both;height:5px;"></div><div class="row"><div class="col-xs-12"><div class="form-group"><input type="hidden" name="supplier_' + su + '", class="form-control" id="supplier_' + su + '" placeholder="<?= lang("select") . ' ' . lang("supplier") ?>" style="width:100%;display: block !important;" /></div></div></div>';
                $('#ex-suppliers').append(html);
                var sup = $('#supplier_' + su);
                suppliers(sup);
                su++;
            } else {
                bootbox.alert('<?= lang('max_reached') ?>');
                return false;
            }
        });

        var _URL = window.URL || window.webkitURL;
        $("input#images").on('change.bs.fileinput', function () {
            var ele = document.getElementById($(this).attr('id'));
            var result = ele.files;
            $('#img-details').empty();
            for (var x = 0; x < result.length; x++) {
                var fle = result[x];
                for (var i = 0; i <= result.length; i++) {
                    var img = new Image();
                    img.onload = (function (value) {
                        return function () {
                            ctx[value].drawImage(result[value], 0, 0);
                        }
                    })(i);

                    img.src = 'images/' + result[i];
                }
            }
        });
        var variants = <?=json_encode($vars);?>;
        $(".select-tags").select2({
            tags: variants,
            tokenSeparators: [","],
            multiple: true
        });
        $(document).on('ifChecked', '#attributes', function (e) {
            $('#attr-con').slideDown();
        });
        $(document).on('ifUnchecked', '#attributes', function (e) {
            $(".select-tags").select2("val", "");
            $('.attr-remove-all').trigger('click');
            $('#attr-con').slideUp();
        });
        $('#addAttributes').click(function (e) {
            e.preventDefault();
            var attrs_val = $('#attributesInput').val(), attrs;
            var added_price = $('#added_price').val();
            var attr_cost = $('#attr_cost').val();
            if (added_price && ! is_numeric(added_price)) {
                bootbox.alert('<?= lang('is_not_numeric') ?>');
                return false;
            }
            if (attr_cost && ! is_numeric(attr_cost)) {
                bootbox.alert('<?= lang('is_not_numeric') ?>');
                return false;
            }
            attrs = attrs_val.split(',');
            var rows = 0;
            var current_qty = 0;
            for (var i in attrs) {
                
            <?php
                
                if (! empty($warehouses) && ! empty($warehouses_products)) {
                    //$this->sma->print_arrays($warehouses);
                    foreach ($warehouses_products as $warehouse) {
                        $i = 0;
                        if ($warehouse->quantity > 0) {
                        //echo $warehouses_products[$i]->quantity . 'x';
                            //echo '$(\'#attrTable\').show().append(\'<tr class="attr"><td><input type="hidden" name="attr_name[]" value="\' + attrs[i] + \'"><span>\' + attrs[i] + \'</span></td><td class="code text-center"><input type="hidden" name="attr_warehouse[]" value="' . $warehouse->id . '"><span>' . $warehouse->name . '</span></td><td class="quantity text-center"><input type="hidden" name="attr_quantity[]" value="1"><span>1</span></td><td class="price text-right"><input type="hidden" name="attr_price[]" value="0"><span>0</span></span></td><td class="cost text-right"><input type="hidden" name="attr_cost[]" value="0"><span>0</span></span></td><td class="text-center"><i class="fa fa-times delAttr"></i></td></tr>\');';
                            echo '$(\'#attrTable\').show().append(\'<tr class="attr"><td><input type="hidden" name="attr_name[]" value="\' + attrs[i] + \'"><span>\' + attrs[i] + \'</span></td><td class="code text-center"><input type="hidden" name="attr_warehouse[]" value="'.$warehouse->id.'"><input type="hidden" name="attr_wh_name[]" value="'.$warehouse->name.'"><span>'.$warehouse->name.'</span></td><td class="quantity text-center"><input type="hidden" name="attr_quantity[]" value="1"><span>1</span></td><td class="attr_cost text-right"><input type="hidden" name="attr_cost[]" value="\' + attr_cost + \'"><span> \' + currencyFormat(attr_cost) + \'</span></span></td><td class="price text-right"><input type="hidden" name="attr_price[]" value="\' + added_price + \'"><span> \' + currencyFormat(added_price) + \'</span></span></td><td class="text-center"><i class="fa fa-times delAttr"></i></td></tr>\');';
                        }
                        $i++;
                    }
                } else { ?>
                    $('#attrTable').show().append('<tr class="attr"><td><input type="hidden" name="attr_name[]" value="' + attrs[i] + '"><span>' + attrs[i] + '</span></td><td class="code text-center"><input type="hidden" name="attr_warehouse[]" value=""><span></span></td><td class="quantity text-center"><input type="hidden" name="attr_quantity[]" value="0"><span></span></td><td class="cost text-right"><input type="hidden" name="attr_cost[]" value="0"><span>0</span></span></td><td class="price text-right"><input type="hidden" name="attr_price[]" value="0"><span>0</span></span></td><td class="text-center"><i class="fa fa-times delAttr"></i></td></tr>');
            <?php } ?>
                $('#added_price').val('');
                $('#attr_cost').val('');
                rows++;
            }
        });
        $(document).on('click', '.delAttr', function () {
            $(this).closest("tr").remove();
        });
        $(document).on('click', '.attr-remove-all', function () {
            $('#attrTable tbody').empty();
            $('#attrTable').hide();
        });
        var row, warehouses = <?= json_encode($warehouses); ?>;
        $(document).on('click', '.attr td:not(:last-child)', function () {
            row = $(this).closest("tr");
            $('#aModalLabel').text(row.children().eq(0).find('span').text());
            $('#awarehouse').select2("val", (row.children().eq(1).find('input').val()));
            $('#aquantity').val(row.children().eq(2).find('span').text());
            $('#aprice').val(row.children().eq(4).find('input').val());
            $('#attrCost').val(row.children().eq(3).find('input').val());
            $('#aModal').appendTo('body').modal('show');
        });

        $('#aModal').on('shown.bs.modal', function () {
            $('#aquantity').select().focus();
            $(this).keypress(function( e ) {
                if ( e.which == 13 ) {
                    $('#updateAttr').click();
                }
            });
        });

        $(document).on('click', '#updateAttr', function () {
            var wh = $('#awarehouse').val(), wh_name;
            $.each(warehouses, function () {
                if (this.id == wh) {
                    wh_name = this.name;
                }
            });
            row.children().eq(1).html('<input type="hidden" name="attr_warehouse[]" value="' + wh + '"><input type="hidden" name="attr_wh_name[]" value="' + wh_name + '"><span>' + wh_name + '</span>');
            row.children().eq(2).html('<input type="hidden" name="attr_quantity[]" value="' + ($('#aquantity').val() ? $('#aquantity').val() : 0) + '"><span>' + $('#aquantity').val() + '</span>');
            row.children().eq(3).html('<input type="hidden" name="attr_cost[]" value="' + $('#attrCost').val() + '"><span>' + currencyFormat($('#attrCost').val()) + '</span>');
            row.children().eq(4).html('<input type="hidden" name="attr_price[]" value="' + $('#aprice').val() + '"><span>' + currencyFormat($('#aprice').val()) + '</span>');
            $('#aModal').modal('hide');
        });

        $(".multiplex").click(function() {
            var multi = parseInt($(this).attr('id')); // $(this) refers to button that was clicked
            var new_wh_quantity = 0;

            $('#attrTable tbody tr').each(function() {
                var self = $(this);
                previous_qty = parseInt(self.children().eq(2).find('input').val());
                if (multi == 1) {
                    new_quantity = 1;
                } else {
                    new_quantity = multi;
                }
                new_wh_quantity += new_quantity;
                self.children().eq(2).html('<input type="hidden" name="attr_quantity[]" value="' + new_quantity + '"><span>' + new_quantity + '</span>');
            });

            $.each(warehouses, function () {
                if (!isNaN(parseInt($('#wh_qty_' + this.id).val()))) {
                    if($('#wh_qty_' + this.id).is(':visible')) {
                        $('#wh_qty_' + this.id).val(new_wh_quantity);
                    }
                }
            });
        });
    });

    <?php if ($product) { ?>
    $(document).ready(function () {
        $('#enable_wh').click(function () {
            var whs = $('.wh');
            $.each(whs, function () {
                $(this).val($('#v' + $(this).attr('id')).val());
            });
            $('#warehouse_quantity').val(1);
            $('.wh').attr('disabled', false);
            $('#show_wh_edit').slideDown();
        });
        $('#disable_wh').click(function () {
            $('#warehouse_quantity').val(0);
            $('#show_wh_edit').slideUp();
        });
        $('#show_wh_edit').hide();
        $('.wh').attr('disabled', true);
        var t = "<?=$product->type?>";
        if (t !== 'standard') {
            $('.standard').slideUp();
            $('#cost').attr('required', 'required');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
        } else {
            $('.standard').slideDown();
            $('#cost').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
        }
        if (t !== 'digital') {
            $('.digital').slideUp();
        } else {
            $('.digital').slideDown();
        }
        if (t !== 'combo') {
            $('.combo').slideUp();
        } else {
            $('.combo').slideDown();
        }
        $('#add_item').removeAttr('required');
        $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
        //$("#code").parent('.form-group').addClass("has-error");
        //$("#code").focus();
        $("#product_image").parent('.form-group').addClass("text-warning");
        $("#images").parent('.form-group').addClass("text-warning");
        $.ajax({
            type: "get", async: false,
            url: "<?= admin_url('products/getSubCategories') ?>/" + <?= $product->category_id ?>,
            dataType: "json",
            success: function (scdata) {
                if (scdata != null) {
                    $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                        placeholder: "<?= lang('select_category_to_load') ?>",
                        minimumResultsForSearch: 7,
                        data: scdata
                    });
                } else {
                    $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('no_subcategory') ?>").select2({
                        placeholder: "<?= lang('no_subcategory') ?>",
                        minimumResultsForSearch: 7,
                        data: [{id: '', text: '<?= lang('no_subcategory') ?>'}]
                    });
                }
            }
        });
        <?php if ($product->supplier1) { ?>
        select_supplier('supplier1', "<?= $product->supplier1; ?>");
        $('#supplier_price').val("<?= $this->sma->formatDecimal($product->supplier1price); ?>");
        $('#supplier_part_no').val("<?= $product->supplier1_part_no; ?>");
        <?php } else { ?>
            $('#supplier1').addClass('rsupplier');
        <?php } ?>
        <?php if ($product->supplier2) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_2', "<?= $product->supplier2; ?>");
        $('#supplier_2_price').val("<?= $this->sma->formatDecimal($product->supplier2price); ?>");
        $('#supplier_2_part_no').val("<?= $product->supplier2_part_no; ?>");
        <?php } ?>
        <?php if ($product->supplier3) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_3', "<?= $product->supplier3; ?>");
        $('#supplier_3_price').val("<?= $this->sma->formatDecimal($product->supplier3price); ?>");
        $('#supplier_3_part_no').val("<?= $product->supplier3_part_no; ?>");
        <?php } ?>
        <?php if ($product->supplier4) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_4', "<?= $product->supplier4; ?>");
        $('#supplier_4_price').val("<?= $this->sma->formatDecimal($product->supplier4price); ?>");
        $('#supplier_4_part_no').val("<?= $product->supplier4_part_no; ?>");
        <?php } ?>
        <?php if ($product->supplier5) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_5', "<?= $product->supplier5; ?>");
        $('#supplier_5_price').val("<?= $this->sma->formatDecimal($product->supplier5price); ?>");
        $('#supplier_5_part_no').val("<?= $product->supplier5_part_no; ?>");
        <?php } ?>
        function select_supplier(id, v) {
            $('#' + id).val(v).select2({
                minimumInputLength: 1,
                data: [],
                initSelection: function (element, callback) {
                    $.ajax({
                        type: "get", async: false,
                        url: "<?= admin_url('suppliers/getSupplier') ?>/" + $(element).val(),
                        dataType: "json",
                        success: function (data) {
                            callback(data[0]);
                        }
                    });
                },
                ajax: {
                    url: site.base_url + "suppliers/suggestions",
                    dataType: 'json',
                    quietMillis: 15,
                    data: function (term, page) {
                        return {
                            term: term,
                            limit: 10
                        };
                    },
                    results: function (data, page) {
                        if (data.results != null) {
                            return {results: data.results};
                        } else {
                            return {results: [{id: '', text: 'No Match Found'}]};
                        }
                    }
                }
            });
        }
    });
    <?php } ?>
    $(document).ready(function () {
        $('#enable_wh').trigger('click');
        $('#unit').change(function(e) {
            var v = $(this).val();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= admin_url('products/getSubUnits') ?>/" + v,
                    dataType: "json",
                    success: function (data) {
                        $('#default_sale_unit').select2("destroy").empty().select2({minimumResultsForSearch: 7});
                        $('#default_purchase_unit').select2("destroy").empty().select2({minimumResultsForSearch: 7});
                        $.each(data, function () {
                            $("<option />", {value: this.id, text: this.name+' ('+this.code+')'}).appendTo($('#default_sale_unit'));
                            $("<option />", {value: this.id, text: this.name+' ('+this.code+')'}).appendTo($('#default_purchase_unit'));
                        });
                        $('#default_sale_unit').select2('val', v);
                        $('#default_purchase_unit').select2('val', v);
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                    }
                });
            } else {
                $('#default_sale_unit').select2("destroy").empty();
                $('#default_purchase_unit').select2("destroy").empty();
                $("<option />", {value: '', text: '<?= lang('select_unit_first') ?>'}).appendTo($('#default_sale_unit'));
                $("<option />", {value: '', text: '<?= lang('select_unit_first') ?>'}).appendTo($('#default_purchase_unit'));
                $('#default_sale_unit').select2({minimumResultsForSearch: 7}).select2('val', '');
                $('#default_purchase_unit').select2({minimumResultsForSearch: 7}).select2('val', '');
            }
        });
        $('#digital_file').removeAttr('required');
        $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
    });

    $(document).ready(function() {
        $('#size_group').change(function(e) {
            var v = $(this).val();
            if (v) {
                $('#attributesInput').val(v);

            }
        });
        <?php if (!empty($product)) { ?>
        //$('#drop_select_wh').val(<?=$warehouse_by_product_id->warehouse_id?>);
        <?php } ?>
        $("#box_wh_"+ $('#drop_select_wh').val()).show()
        $("#rack_wh_"+ $('#drop_select_wh').val()).show()
    });
</script>

<div class="modal" id="aModal" tabindex="-1" role="dialog" aria-labelledby="aModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="aModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="awarehouse" class="col-sm-4 control-label"><?= lang('warehouse') ?></label>
                        <div class="col-sm-8">
                            <?php
                            $wh[''] = '';
                            foreach ($warehouses as $warehouse) {
                                $wh[$warehouse->id] = $warehouse->name;
                            }
                            echo form_dropdown('warehouse', $wh, '', 'id="awarehouse" class="form-control"');
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="aquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="aquantity">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="attrCost" class="col-sm-4 control-label"><?= lang('cost') ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="attrCost">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="aprice" class="col-sm-4 control-label"><?= lang('price_addition') ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="aprice">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateAttr"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>
