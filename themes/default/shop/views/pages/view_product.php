<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="page-contents">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-sm-9 col-md-10">

                        <div class="panel panel-default margin-top-lg">
                            <div class="panel-heading text-bold">
                                <i class="fa fa-list-alt margin-right-sm"></i> <?= $product->name; ?>
                                <!--<a href="<?= shop_url('products'); ?>" class="pull-right"><i class="fa fa-share"></i> <?= lang('products'); ?></a>-->
                            </div>
                            <div class="panel-body mprint">
                            <?php 
                                $product_price = isset($product->special_price) ? $product->special_price : $product->price;
                                $product_price_before_promo = 0;
                                $row_promotion = '';

                                if ($product->promotion && !$product->promo_expired) {
                                    $product_price_before_promo = $product_price;
                                    $product_price = $product->promo_price;
                                    $row_promotion = '<tr><td>' . lang('promotion') . '</td><td><strong>' . $this->sma->convertMoney($product->promo_price) . '</strong><br>(' . ($product->end_date && $product->end_date != '0000-00-00' ? lang('end_date') . ': <strong>' . $this->sma->hrsd($product->end_date) . '</strong>' : '') . ')</td></tr>';
                                }
                                
                                
                            ?>
                                <div class="row">                                
                                    <div class="col-sm-5">

                                        <div class="photo-slider">
                                            <div class="carousel slide article-slide" id="photo-carousel">

                                                <div class="carousel-inner cont-slider">
                                                    <div class="item active">
                                                        <a href="#" data-toggle="modal" data-target="#lightbox">
                                                            <img src="<?= base_url() ?>assets/uploads/<?= $product->image ?>" alt="<?= $product->name ?>" class="img-responsive img-thumbnail"/>
                                                        </a>
                                                    </div>
                                                    <?php
                                                    if (!empty($images)) {
                                                        foreach ($images as $ph) {
                                                            echo '<div class="item"><a href="#" data-toggle="modal" data-target="#lightbox"><img class="img-responsive img-thumbnail" src="' . base_url('assets/uploads/' . $ph->photo) . '" alt="' . $ph->photo . '" /></a></div>';
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                                <ol class="carousel-indicators">
                                                    <li class="active" data-slide-to="0" data-target="#photo-carousel">
                                                        <img class="img-thumbnail" alt="" src="<?= base_url() ?>assets/uploads/thumbs/<?= $product->image ?>">
                                                    </li>
                                                    <?php
                                                    $r = 1;
                                                    if (!empty($images)) {
                                                        foreach ($images as $ph) {
                                                            echo '<li class="" data-slide-to="' . $r . '" data-target="#photo-carousel"><img class="img-thumbnail" alt="" src="' . base_url('assets/uploads/thumbs/' . $ph->photo) . '"></li>';
                                                            $r++;
                                                        }
                                                    }
                                                    ?>

                                                </ol>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>

                                    <?php

                                    $category_display_size_table = array(2, 3, 4, 5, 6, 7, 8, 32, 33, 36, 37);
                                    if (in_array($category->id, $category_display_size_table)) { 
                                        if ($brand->id == 6) { // Hàng Quảng Châu ?>
                                            <div class="form-group">                                        
                                                <a href="#" data-toggle="modal" data-target="#lightbox" class="pull-left" style="text-decoration: none; font-size: 16px; margin-bottom: 10px;">
                                                    <i class="fa fa-table"></i> <?= lang('size_instruction'); ?>
                                                    <img src="<?= base_url() ?>assets/uploads/bangsize_qc.jpg" style="display: none" class="img-responsive img-thumbnail"> 
                                                </a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-group">                                     
                                                <a href="#" data-toggle="modal" data-target="#lightbox" class="pull-left" style="text-decoration: none; font-size: 16px; margin-bottom: 10px;">
                                                <i class="fa fa-table"></i> <?= lang('size_instruction'); ?>
                                                    <img src="<?= base_url() ?>assets/uploads/bangsize_vn.jpg" style="display: none" class="img-responsive img-thumbnail"> 
                                                </a>
                                            </div>
                                <?php
                                        }
                                    } 
                                ?>

                                        <?php if (!$shop_settings->hide_price) {
                                                        ?>
                                        <?php if (($product->type != 'standard' || $warehouse->quantity > 0) || $Settings->overselling) {
                                                            ?>
                                        <?= form_open('cart/add/' . $product->id, 'class="validate"'); ?>
                                        <div class="form-group">


                                        <?php
                                            $is_in_stock = false;
                                            //echo $warehouse->quantity;
                                            //$this->sma->print_arrays($product, $variants);
                                            if ($variants) {
                                                //cho 'yyy';
                                                foreach ($variants as $variant) {
                                                    if ($variant->quantity > 0) {
                                                       $is_in_stock = true;
                                                        //$product_price =
                                                        //$str_variant = $variant->name . ($variant->price > 0 ? ' (' . $this->sma->convertMoney($product_price + $variant->price, true, false) . ')' : ($variant->price == 0 ? '' : $this->sma->convertMoney($product_price + $variant->price, true, false)));
                                                        if ($product->promotion && !$product->promo_expired) {
                                                            $str_variant = $variant->name . ' (' . $this->sma->convertMoney($product_price_before_promo + $variant->price) . ' &#8594;' . $this->sma->convertMoney($product_price + $variant->price, true, false) . ')';
                                                            //$str_variant .= '<span style="text-decoration: line-through"> ' .  . '<span></del>)';
                                                        } else if ($variant->promo_price > 0 && !$product->promotion) { 
                                                            $str_variant = $variant->name . ' (' . $this->sma->convertMoney($product->price + $variant->price) . ' &#8594;' . $this->sma->convertMoney($variant->promo_price, true, false) . ')';

                                                        } else {
                                                            $str_variant = $variant->name . ' (' . $this->sma->convertMoney($product_price + $variant->price, true, false) . ')';
                                                        }
                                                        
                                                        $opts[$variant->id] = $str_variant;
                                                    }
                                                }
                                                if ($is_in_stock) {
                                                    echo form_dropdown('option', $opts, '', 'class="form-control selectpicker mobile-device" required="required"');
                                                } 
                                            } elseif ($warehouse->quantity > 0 || $product->quantity > 0) {
                                                $is_in_stock = true;
                                            }
                                        ?>
                                        </div>
                                        <input type="hidden" name="quantity" class="form-control text-center" value="1">

                                        <div class="form-group">
                                            <div class="btn-group" role="group" aria-label="...">
                                                <!--<button class="btn btn-info btn-lg add-to-wishlist" data-id="<?= $product->id; ?>"><i class="fa fa-heart-o"></i></button>-->
                                            <?php if ($is_in_stock) { ?>
                                                <button type="submit" class="btn btn-theme btn-lg"><i class="fa fa-shopping-cart padding-right-md"></i> <?= lang('add_to_cart'); ?></button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-warning btn-block btn-lg"></i> <?= lang('out_of_stock'); ?></button>
                                            <?php } ?>
                                            </div>
                                        </div>
                                        <?= form_close(); ?>
                                        <?php
                                                } else {
                                                    echo '<div class="well well-sm"><strong>' . lang('item_out_of_stock') . 'ccc</strong></div>';
                                                } ?>
                                        <?php
                                            } ?>
                                    </div>

                                    <div class="col-sm-7">
                                        <div class="clearfix"></div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped dfTable table-right-left">
                                                <tbody>
                                                    <tr>
                                                        <td width="30%"><?= lang('name'); ?></td>
                                                        <td width="70%"><?= $product->name; ?></td>
                                                    </tr>
                                                    <?php if (!empty($product->second_name)) {
                                                        ?>
                                                    <tr>
                                                        <td width="30%"><?= lang('secondary_name'); ?></td>
                                                        <td width="70%"><?= $product->second_name; ?></td>
                                                    </tr>
                                                    <?php
                                                    } ?>
                                                    <tr>
                                                        <td width="30%"><?= lang('code'); ?></td>
                                                        <td width="70%"><?= $product->code; ?></td>
                                                    </tr>
                                                    <!--<tr>
                                                        <td><?= lang('type'); ?></td>
                                                        <td><?= lang($product->type); ?></td>
                                                    </tr>-->
                                                    <tr>
                                                        <td><?= lang('brand'); ?></td>
                                                        <td><?= $brand ? '<a href="' . site_url('brand/' . $brand->slug) . '" class="line-height-lg">' . $brand->name . '</a>' : ''; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?= lang('category'); ?></td>
                                                        <td><?= '<a href="' . site_url('category/' . $category->slug) . '" class="line-height-lg">' . $category->name . '</a>'; ?></td>
                                                    </tr>
                                                    <?php if ($product->subcategory_id) {
                                                        ?>
                                                    <tr>
                                                        <td><?= lang('subcategory'); ?></td>
                                                        <td><?= '<a href="' . site_url('category/' . $category->slug . '/' . $subcategory->slug) . '" class="line-height-lg">' . $subcategory->name . '</a>'; ?></td>
                                                    </tr>
                                                    <?php
                                                    } ?>

                                                    <?php if (!$shop_settings->hide_price) {
                                                        ?>
                                                    <tr>
                                                        <td><?= lang('price'); ?></td>
                                                        <td>
                                                        <?php
                                                        if ($product->promotion && !$product->promo_expired) {
                                                            echo $this->sma->convertMoney(isset($product->special_price) && !empty($product->special_price) ? $product->special_price : $product_price_before_promo);
                                                        } else {
                                                            echo $this->sma->convertMoney(isset($product->special_price) && !empty($product->special_price) ? $product->special_price : $product_price);
                                                        }
                                                        ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    } ?>

                                                    <?php                                                    
                                                        echo $row_promotion;
                                                    ?>

                                                    <?php if ($product->tax_rate) {
                                                        ?>
                                                    <tr>
                                                        <td><?= lang('tax_rate'); ?></td>
                                                        <td><?= $tax_rate->name; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?= lang('tax_method'); ?></td>
                                                        <td><?= $product->tax_method == 0 ? lang('inclusive') : lang('exclusive'); ?></td>
                                                    </tr>
                                                    <?php
                                                    } ?>

                                                    <!--<tr>
                                                        <td><?= lang('unit'); ?></td>
                                                        <td><?= $unit ? $unit->name : ''; ?></td>
                                                    </tr>
                                                    <?php if (!empty($warehouse) && $product->type == 'standard') {
                                                        ?>
                                                    <tr>
                                                        <td><?= lang('in_stock'); ?></td>
                                                        <td><?= $this->sma->formatQuantity($warehouse->quantity); ?><?= lang('in_stock_desc'); ?></td>
                                                    </tr>
                                                    <?php
                                                    } ?>-->

                                                    <?php if ($variants && $is_in_stock) {
                                                        ?>
                                                    <tr>
                                                        <td><?= lang('product_variants'); ?></td>
                                                        <td>
                                                    <?php
                                                        $i = 0; 
                                                        foreach ($variants as $variant) {
                                                            $i++;
                                                            if ($variant->quantity > 0) {
                                                                echo '<span class="label label-primary">' . $variant->name . '</span> ';
                                                            } else {
                                                                echo '<span style="background: linear-gradient(to top right, #70747f calc(50% - 1px), black , #70747f calc(50% + 1px));" class="label label-primary">' . $variant->name . '</span> ';
                                                            }
                                                        } 
                                                        if ($i == 0) {
                                                            echo '<span class="label label-warning">' . lang('out_of_stock') . '</span> ';
                                                        }
                                                        ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    } ?>

                                                    <?php if (!empty($options)) {
                                                        foreach ($options as $option) {
                                                            if ($option->wh_qty != 0) {
                                                                /*echo '<tr><td colspan="2" class="bg-primary">' . $option->name . '</td></tr>';
                                                                echo '<td>' . lang('in_stock') . ': ' . $this->sma->formatQuantity($option->wh_qty) . '</td>';
                                                                echo '<td>' . lang('price') . ': ' . $this->sma->convertMoney(($product->special_price ? $product->special_price : $product->price) + $option->price) . '</td>';
                                                                echo '</tr>';*/
                                                            }
                                                        }
                                                    } ?>

                                                    <?php if ($product->cf1 || $product->cf2 || $product->cf3 || $product->cf4 || $product->cf5 || $product->cf6) {
                                                        if ($product->cf1) {
                                                            echo '<tr><td>' . lang('pcf1') . '</td><td>' . $product->cf1 . '</td></tr>';
                                                        }
                                                        if ($product->cf2) {
                                                            echo '<tr><td>' . lang('pcf2') . '</td><td>' . $product->cf2 . '</td></tr>';
                                                        }
                                                        if ($product->cf3) {
                                                            echo '<tr><td>' . lang('pcf3') . '</td><td>' . $product->cf3 . '</td></tr>';
                                                        }
                                                        if ($product->cf4) {
                                                            echo '<tr><td>' . lang('pcf4') . '</td><td>' . $product->cf4 . '</td></tr>';
                                                        }
                                                        if ($product->cf5) {
                                                            echo '<tr><td>' . lang('pcf5') . '</td><td>' . $product->cf5 . '</td></tr>';
                                                        }
                                                        if ($product->cf6) {
                                                            echo '<tr><td>' . lang('pcf6') . '</td><td>' . $product->cf6 . '</td></tr>';
                                                        }
                                                    } ?>


                                                </tbody>
                                            </table>
                                            <?php if ($product->type == 'combo') {
                                                        ?>
                                            <strong><?= lang('combo_items') ?></strong>
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
                                        <?php
                                                    } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-xs-12">
                                    <?= $product->product_details ? '<div class="panel panel-warning"><div class="panel-heading">' . $product->product_details . '</div></div>' : ''; ?>
                                    <?= $product->details ? '<div class="panel panel-info"><div class="panel-heading">' . lang('product_details_for_invoice') . '</div><div class="panel-body">' . $product->details . '</div></div>' : ''; ?>
                                    

                                </div>
                            </div>

                            <?php //include 'share.php'; ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3 col-md-2">
                    <?php include 'sidebar2.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="featured-products">
    <div class="row">
        <?php
        if (!empty($other_products)) {
            ?>
            <div class="col-xs-12">
                <h3 class="margin-top-no text-size-lg">
                    <?= lang('other_products'); ?>
                </h3>
            </div>
            <div class="row">
            <div class="col-xs-12">
                <?php
                foreach ($other_products as $fp) {
                    ?>
                    <div class="col-sm-6 col-md-3">
                        <div class="product" style="z-index: 1;">
                            <div class="details" style="transition: all 100ms ease-out 0s;">
                                <?php
                                if ($fp->promotion) {
                                    ?>
                                    <span class="badge badge-right theme"><?= lang('promo'); ?></span>
                                    <?php
                                } ?>
                                <img src="<?= base_url('assets/uploads/' . $fp->image); ?>" alt="">
                                <?php if (!$shop_settings->hide_price) {
                                    ?>
                                <div class="image_overlay"></div>
                                <div class="btn"><i class="fa fa-server"></i> <a href="<?= site_url('product/' . $fp->slug); ?>"><?= lang('view_item'); ?></a></div>
                                <?php
                                } ?>
                                <div class="stats-container">
                                    <?php if (!$shop_settings->hide_price) {
                                    ?>
                                    <span class="product_price">
                                        <?php
                                        if ($fp->promotion) {
                                            echo '<del class="text-red">' . $this->sma->convertMoney(isset($fp->special_price) && !empty($fp->special_price) ? $fp->special_price : $fp->price) . '</del><br>';
                                            echo $this->sma->convertMoney($fp->promo_price);
                                        } else {
                                            echo $this->sma->convertMoney(isset($fp->special_price) && !empty($fp->special_price) ? $fp->special_price : $fp->price);
                                        } ?>
                                    </span>
                                    <?php
                                } ?>
                                    <span class="product_name">
                                        <a href="<?= site_url('product/' . $fp->slug); ?>"><?= $fp->name; ?></a>
                                    </span>
                                    <a href="<?= site_url('category/' . $fp->category_slug); ?>" class="link"><?= $fp->category_name; ?></a>
                                    <?php
                                    if ($fp->brand_name) {
                                        ?>
                                        <span class="link">-</span>
                                        <a href="<?= site_url('brand/' . $fp->brand_slug); ?>" class="link"><?= $fp->brand_name; ?></a>
                                        <?php
                                    } ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
            <?php
        }
        ?>
    </div>
    </div>
</div>
</section>

<div id="lightbox" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-middle">
        <div class="modal-content">
            <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="modal-body">
                <img src="" alt="" />
            </div>
        </div>
    </div>
</div>
