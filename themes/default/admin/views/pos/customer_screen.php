<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">

    <title>Customer Screen</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="<?= base_url() ?>"/>

    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>

    <script src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>

    <!-- Customer Screen -->
    <link rel="stylesheet"
          href="<?=$assets?>pos/css/customer_screen.css">

    <script>

        var customerScreen = {

            getUrl : "<?= admin_url('pos/get_customer_screen') ?>",

            logo : "<?= base_url('assets/uploads/logos/logo3.png') ?>",

            imageUrl : "<?= base_url('assets/uploads/thumbs/') ?>",

            shopName : "TIỆM NƯỚC MINI",

            currency : "đ",

            qrBase : "https://img.vietqr.io/image/vietcombank-NP82502685068552VCB-qr_only.jpg",

            accountName : "Ho%20Kinh%20Doanh%20Bani%20Mini",

            bankInfo : "",

            bankInfoText : <?=json_encode(lang('bank_info_vcb'))?>

        };

    </script>

</head>

<body>

<div id="customer-screen">

    <!-- HEADER -->

    <header id="cs-header">

        <div class="logo-area">

            <div class="logo">

                🧋

            </div>

            <div class="shop">

                <div class="shop-name">

                    TIỆM NƯỚC MINI

                </div>

                <div class="shop-slogan">

                    Cần Giải Khát Có Mini, Hương Trái Cây Vị Mê Ly

                </div>

            </div>

        </div>

        <div class="clock-area">

            <div id="clock-time">

                00:00:00

            </div>

            <div id="clock-date">

                01/01/2026

            </div>

        </div>

    </header>

    <!-- CONTENT -->

    <main id="cs-content">

        <div id="screen-wrapper">

            <!-- ADS / WAITING -->
            <div id="waiting-screen" class="screen-layer active">

                <div class="waiting-icon">
                    🧋
                </div>

                <div class="waiting-title">
                    MINI DRINK
                </div>

                <div class="waiting-text">
                    Đang chờ đơn hàng...
                </div>

            </div>

            <!-- BILL -->
            <div id="bill-screen" class="screen-layer">

                <div id="bill-list"></div>

            </div>

            <!-- PAYMENT -->
            <div id="payment-screen" class="screen-layer">

                <div class="payment-box">

                    <div id="payment-content">

                        <!-- JS render -->

                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- FOOTER -->

    <footer id="cs-footer">

        <div class="footer-title">

            TẠM TÍNH

        </div>

        <div id="grand-total">

            0đ

        </div>

    </footer>

</div>

<script src="<?= $assets?>pos/js/customer_screen.js"></script>

</body>

</html>