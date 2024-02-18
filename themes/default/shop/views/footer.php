<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (DEMO && ($m == 'main' && $v == 'index')) {
    ?>
<div class="page-contents padding-top-no">
    <div class="container">
        <div class="alert alert-info margin-bottom-no">
            <p>
                <strong>Shop module is not complete item but add-on to Stock Manager Advance and is available separately.</strong><br>
                This is joint demo for main item (Stock Manager Advance) and add-ons (POS & Shop Module). Please check the item page on codecanyon.net for more info about what's not included in the item and you must read the page there before purchase. Thank you
            </p>
        </div>
    </div>
</div>
<?php
} ?>

<section class="footer">
<div class="container padding-bottom-md">
        <div class="row">
            
            
            
            <div class="col-md-3 col-sm-6">
                <div class="title-footer"><span><?= lang('about_us'); ?></span></div>
                <p>
                    <a href="<?= site_url('page/' . $shop_settings->about_link); ?>"><?= $shop_settings->description; ?></a>
                </p>
                <p>
                    <a href="<?= site_url('page/' . $shop_settings->terms_link); ?>"><?= lang('terms_guide'); ?></a>
                </p>
                <p>
                    <a href="<?= site_url('page/' . $shop_settings->payment_link); ?>"><?= lang('payment_guide'); ?></a>
                </p>
                <p>
                    <a href="<?= site_url('page/' . $shop_settings->delivery_link); ?>"><?= lang('delivery_guide'); ?></a>
                </p>
                <p>
                    <a href="<?= site_url('page/' . $shop_settings->contact_link); ?>"><?= lang('contact_us'); ?></a>
                </p>
                
                <!--<ul class="list-inline">
                    <li><a href="<?= site_url('page/' . $shop_settings->privacy_link); ?>"><?= lang('privacy_policy'); ?></a></li>
                    <li><a href="<?= site_url('page/' . $shop_settings->terms_link); ?>"><?= lang('terms_conditions'); ?></a></li>
                    <li><a href="<?= site_url('page/' . $shop_settings->contact_link); ?>"><?= lang('contact_us'); ?></a></li>
                </ul>-->
            </div>
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-5 col-sm-12">
                <div class="title-footer"><span><?= lang('follow_us'); ?></span></div>
                <div class="fb-page" data-href="https://www.facebook.com/banikidsct" data-width="380" data-hide-cover="false" data-show-facepile="false">
                <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fbanikidsct&tabs=timeline&width=500&height=100&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="395" height="140" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                </div>

            </div>

            <div class="col-md-4 col-sm-6">
            <div class="title-footer"><span><?= lang('company_name'); ?></span></div>
            <p><?= lang('address'); ?>: <?= $shop_settings->shop_address?></p>
            <p><?= lang('phone'); ?>: <?= $shop_settings->phone?></p>
            <p><?= lang('email'); ?>: <?= $shop_settings->email?></p>
            <p><?= lang('company_fullname'); ?></p>
            <p><?= lang('company_taxno'); ?></p>
            <!--<img class="img-responsive" width="70%" src="<?= $assets; ?>/images/bct.png" alt="">-->  
            </div>

        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="copyright line-height-lg">
                &copy; <?= date('Y'); ?> <?= $shop_settings->shop_name; ?> <?= lang('all_rights_reserved'); ?>
            </div>
            <ul class="list-inline pull-right line-height-md">
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-blue" data-color="blue"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-blue-grey" data-color="blue-grey"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-brown" data-color="brown"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-cyan" data-color="cyan"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-green" data-color="green"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-grey" data-color="grey"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-purple" data-color="purple"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-orange" data-color="orange"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-pink" data-color="pink"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-red" data-color="red"><i class="fa fa-square"></i></a>
                </li>
                <li class="padding-x-no text-size-lg">
                    <a href="#" class="theme-color text-teal" data-color="teal"><i class="fa fa-square"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
</section>

<a href="#" class="back-to-top text-center" onclick="$('body,html').animate({scrollTop:0},500); return false">
    <i class="fa fa-angle-double-up"></i>
</a>
</section>
<?php if (!get_cookie('shop_use_cookie') && get_cookie('shop_use_cookie') != 'accepted' && !empty($shop_settings->cookie_message)) {
        ?>
<div class="cookie-warning">
    <div class="bounceInLeft alert alert-info">
        <!-- <a href="<?= site_url('main/cookie/accepted'); ?>" class="close">&times;</a> -->
        <a href="<?= site_url('main/cookie/accepted'); ?>" class="btn btn-sm btn-primary" style="float: right;"><?= lang('i_accept'); ?></a>
        <p>
            <?= $shop_settings->cookie_message; ?>
            <?php if (!empty($shop_settings->cookie_link)) {
            ?>
            <a href="<?= site_url('page/' . $shop_settings->cookie_link); ?>"><?= lang('read_more'); ?></a>
            <?php
        } ?>
        </p>
    </div>
</div>
<?php
    } ?>
<script type="text/javascript">
    var m = '<?= $m; ?>', v = '<?= $v; ?>', products = {}, filters = <?= isset($filters) && !empty($filters) ? json_encode($filters) : '{}'; ?>, shop_color, shop_grid, sorting;
    var cart = <?= isset($cart)                                                          && !empty($cart) ? json_encode($cart) : '{}' ?>;
    var site = {base_url: '<?= base_url(); ?>', site_url: '<?= site_url('/'); ?>', shop_url: '<?= shop_url(); ?>', csrf_token: '<?= $this->security->get_csrf_token_name() ?>', csrf_token_value: '<?= $this->security->get_csrf_hash() ?>', settings: {display_symbol: '<?= $Settings->display_symbol; ?>', symbol: '<?= $Settings->symbol; ?>', decimals: <?= $Settings->decimals; ?>, thousands_sep: '<?= $Settings->thousands_sep; ?>', decimals_sep: '<?= $Settings->decimals_sep; ?>', order_tax_rate: false, products_page: <?= $shop_settings->products_page ? 1 : 0; ?>}, shop_settings: {private: <?= $shop_settings->private ? 1 : 0; ?>, hide_price: <?= $shop_settings->hide_price ? 1 : 0; ?>}}

    var lang = {};
    lang.page_info = '<?= lang('page_info'); ?>';
    lang.cart_empty = '<?= lang('empty_cart'); ?>';
    lang.item = '<?= lang('item'); ?>';
    lang.items = '<?= lang('items'); ?>';
    lang.unique = '<?= lang('unique'); ?>';
    lang.total_items = '<?= lang('total_items'); ?>';
    lang.total_unique_items = '<?= lang('total_unique_items'); ?>';
    lang.tax = '<?= lang('tax'); ?>';
    lang.shipping = '<?= lang('shipping'); ?>';
    lang.shipping_rate_info = '<?= lang('shipping_rate_info'); ?>';
    lang.total_w_o_tax = '<?= lang('total_w_o_tax'); ?>';
    lang.product_tax = '<?= lang('product_tax'); ?>';
    lang.order_tax = '<?= lang('order_tax'); ?>';
    lang.total = '<?= lang('total'); ?>';
    lang.grand_total = '<?= lang('grand_total'); ?>';
    lang.reset_pw = '<?= lang('forgot_password?'); ?>';
    lang.type_email = '<?= lang('type_email_to_reset'); ?>';
    lang.submit = '<?= lang('submit'); ?>';
    lang.error = '<?= lang('error'); ?>';
    lang.add_address = '<?= lang('add_address'); ?>';
    lang.update_address = '<?= lang('update_address'); ?>';
    lang.fill_form = '<?= lang('fill_form'); ?>';
    lang.already_have_max_addresses = '<?= lang('already_have_max_addresses'); ?>';
    lang.send_email_title = '<?= lang('send_email_title'); ?>';
    lang.message_sent = '<?= lang('message_sent'); ?>';
    lang.add_to_cart = '<?= lang('add_to_cart'); ?>';
    lang.view_item = '<?= lang('view_item'); ?>';
    lang.out_of_stock = '<?= lang('out_of_stock'); ?>';
    lang.x_product = '<?= lang('x_product'); ?>';
</script>
<script src="<?= $assets; ?>js/libs.min.js"></script>
<script src="<?= $assets; ?>js/scripts.min.js"></script>
<?php if ($m == 'shop' && $v == 'product') {
        ?>
<script type="text/javascript">
$(document).ready(function ($) {
  $('.rrssb-buttons').rrssb({
    title: '<?= $product->code . ' - ' . $product->name; ?>',
    url: '<?= site_url('product/' . $product->slug); ?>',
    image: '<?= base_url('assets/uploads/' . $product->image); ?>',
    description: '<?= $page_desc; ?>',
    // emailSubject: '',
    // emailBody: '',
  });
});
</script>
<?php
    } ?>
<script type="text/javascript">
<?php if ($message || $warning || $error || $reminder) {
        ?>
$(document).ready(function() {
    <?php if ($message) {
            ?>
        sa_alert('<?=lang('success'); ?>', '<?= trim(str_replace(["\r", "\n", "\r\n"], '', addslashes($message))); ?>');
    <?php
        }
        if ($warning) {
            ?>
        sa_alert('<?=lang('warning'); ?>', '<?= trim(str_replace(["\r", "\n", "\r\n"], '', addslashes($warning))); ?>', 'warning');
    <?php
        }
        if ($error) {
            ?>
        sa_alert('<?=lang('error'); ?>', '<?= trim(str_replace(["\r", "\n", "\r\n"], '', addslashes($error))); ?>', 'error', 1);
    <?php
        }
        if ($reminder) {
            ?>
        sa_alert('<?=lang('reminder'); ?>', '<?= trim(str_replace(["\r", "\n", "\r\n"], '', addslashes($reminder))); ?>', 'info');
    <?php
        } ?>
});
<?php
    } ?>
</script>
</body>
</html>
