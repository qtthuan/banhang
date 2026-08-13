<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="vi">
<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Mini POS</title>

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f5f7;
            color: #222;
            overflow: hidden;
        }

        /* =====================================================
         * APP
         * ===================================================== */

        .mini-app {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }


        /* =====================================================
         * HEADER
         * ===================================================== */

        .mini-header {
            height: 60px;
            flex: 0 0 60px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 20px;

            background: #222;
            color: #fff;
        }

        .mini-logo {
            font-size: 21px;
            font-weight: 700;
        }

        .mini-user {
            font-size: 14px;
            opacity: .9;
        }


        /* =====================================================
         * MAIN
         * ===================================================== */

        .mini-main {
            flex: 1;
            min-height: 0;

            display: flex;
        }


        /* =====================================================
         * ORDER PANEL
         * ===================================================== */

        .mini-order {
            width: 38%;
            min-width: 350px;

            background: #fff;

            border-right: 1px solid #e3e5e8;

            display: flex;
            flex-direction: column;

            padding: 18px;
        }

        .mini-order-title {
            font-size: 20px;
            font-weight: 700;

            padding-bottom: 15px;

            border-bottom: 1px solid #eee;
        }

        .mini-order-empty {
            flex: 1;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #999;
            font-size: 15px;
        }


        /* =====================================================
         * PRODUCT AREA
         * ===================================================== */

        .mini-products {
            width: 62%;
            min-width: 0;
            min-height: 0;

            display: flex;
            flex-direction: column;

            padding: 16px 18px;
        }


        /* =====================================================
         * SEARCH
         * ===================================================== */

        .mini-search {
            width: 100%;
            height: 44px;

            flex: 0 0 44px;

            border: 1px solid #ddd;
            border-radius: 10px;

            background: #fff;

            padding: 0 15px;

            font-size: 15px;

            outline: none;

            margin-bottom: 12px;
        }

        .mini-search:focus {
            border-color: #2587d8;
            box-shadow: 0 0 0 2px rgba(37,135,216,.08);
        }


        /* =====================================================
         * CATEGORY
         * ===================================================== */

        .mini-categories {
            flex: 0 0 auto;

            display: flex;
            gap: 8px;

            overflow-x: auto;

            padding-bottom: 12px;

            scrollbar-width: none;
        }

        .mini-categories::-webkit-scrollbar {
            display: none;
        }

        .mini-category {
            flex: 0 0 auto;

            border: 0;

            border-radius: 9px;

            background: #e9ebee;
            color: #333;

            padding: 9px 17px;

            font-size: 14px;
            font-weight: 600;

            cursor: pointer;

            white-space: nowrap;

            transition:
                background .15s ease,
                color .15s ease,
                transform .15s ease;
        }

        .mini-category:hover {
            transform: translateY(-1px);
        }

        .mini-category.active {
            background: #222;
            color: #fff;
        }


        /* =====================================================
         * PRODUCT GRID
         * ===================================================== */

        .mini-product-grid {
            flex: 1;
            min-height: 0;

            display: grid;

            /*
             * Tự động tăng / giảm số cột.
             * Không khóa 4, 5 hay 6 cột.
             */
            grid-template-columns:
                repeat(auto-fill, minmax(155px, 1fr));

            gap: 12px;

            overflow-y: auto;

            padding: 2px 2px 20px;

            align-content: start;
        }


        /* =====================================================
         * PRODUCT CARD
         * ===================================================== */

        .mini-product-card {

            position: relative;

            width: 100%;

            height: 218px;

            border: 1px solid #e1e3e6;

            border-radius: 14px;

            background: #fff;

            padding: 9px;

            cursor: pointer;

            text-align: left;

            overflow: hidden;

            display: flex;
            flex-direction: column;

            transition:
                transform .12s ease,
                box-shadow .15s ease,
                border-color .15s ease;

            /*
             * Quan trọng:
             * Không để button mặc định của browser
             * làm thay đổi layout.
             */
            font-family: inherit;
        }

        .mini-product-card:hover {
            transform: translateY(-2px);

            border-color: #cfd3d8;

            box-shadow:
                0 7px 18px rgba(0,0,0,.08);
        }

        .mini-product-card:active {
            transform: scale(.975);
        }


        /* =====================================================
         * PRODUCT IMAGE
         * ===================================================== */

        .mini-product-image-wrap {
            width: 100%;
            height: 117px;

            flex: 0 0 117px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f7f7f7;
            border-radius: 10px;
            overflow: hidden;
        }

        .mini-product-image {

            width: 100%;
            height: 100%;

            object-fit: contain;

            display: block;
        }


        /* =====================================================
         * PRODUCT NAME
         * ===================================================== */

        .mini-product-name {

            margin-top: 9px;

            font-size: 14px;
            font-weight: 600;

            line-height: 1.25;

            text-align: center;

            height: 36px;
            min-height: 36px;

            display: -webkit-box;

            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;

            overflow: hidden;
        }


        /* =====================================================
         * PRICE
         * ===================================================== */

        .mini-product-price {

            margin-top: 5px;

            min-height: 20px;

            font-size: 15px;
            font-weight: 700;

            color: #1680ca;

            text-align: center;
        }

        .mini-product-old-price {

            color: #999;

            font-size: 11px;

            text-decoration: line-through;

            margin-right: 4px;
        }


        /* =====================================================
         * EDIT BUTTON
         * ===================================================== */

        .mini-product-edit {
            position: absolute;

            top: 7px;
            right: 7px;

            width: 42px;
            height: 42px;

            border: 0;
            border-radius: 50%;

            background: rgba(255,255,255,.96);

            box-shadow:
                0 2px 8px rgba(0,0,0,.18);

            color: #333;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 19px;

            cursor: pointer;

            z-index: 5;

            transition:
                background .15s ease,
                transform .15s ease;
        }

        .mini-product-edit:hover {

            background: #222;
            color: #fff;

            transform: scale(1.06);
        }

        .mini-product-edit:active {
            transform: scale(.92);
        }


        /* =====================================================
         * PROMO
         * ===================================================== */

        .mini-promo {

            position: absolute;

            top: 9px;
            left: 9px;

            z-index: 4;

            padding: 4px 7px;

            border-radius: 7px;

            background: #ff7518;

            color: #fff;

            font-size: 10px;
            font-weight: 700;
        }


        /* =====================================================
         * QUICK ADD FEEDBACK
         * ===================================================== */

        .mini-product-card.quick-added {

            animation: miniQuickAdd .18s ease;
        }

        @keyframes miniQuickAdd {

            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(.96);
            }

            100% {
                transform: scale(1);
            }

        }


        /* =====================================================
         * NO PRODUCT
         * ===================================================== */

        .mini-no-products {

            grid-column: 1 / -1;

            padding: 50px 20px;

            text-align: center;

            color: #999;
        }


        /* =====================================================
         * TABLET
         * ===================================================== */

        @media (max-width: 1100px) {

            .mini-order {
                width: 40%;
                min-width: 320px;
            }

            .mini-products {
                width: 60%;
            }

            .mini-product-grid {
                grid-template-columns:
                    repeat(auto-fill, minmax(140px, 1fr));
            }

            .mini-product-card {
                height: 204px;
            }

            .mini-product-image-wrap {
                height: 132px;
                flex-basis: 132px;
            }

        }


        /* =====================================================
         * MOBILE
         * ===================================================== */

        @media (max-width: 800px) {

            body {
                overflow: auto;
            }

            .mini-app {
                height: auto;
                min-height: 100vh;
            }

            .mini-header {
                height: 54px;
                flex-basis: 54px;

                padding: 0 13px;
            }

            .mini-logo {
                font-size: 18px;
            }

            .mini-main {
                flex-direction: column;
            }

            .mini-products {
                width: 100%;
                min-height: 65vh;

                padding: 12px;
            }

            .mini-order {
                width: 100%;
                min-width: 0;

                min-height: 300px;

                border-right: 0;
                border-top: 1px solid #ddd;

                order: 2;
            }

            .mini-product-grid {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));

                gap: 9px;
            }

            .mini-product-card {
                height: 205px;
            }

            .mini-product-image-wrap {
                height: 122px;
                flex-basis: 122px;
            }

            .mini-product-name {
                font-size: 13px;
            }

            .mini-product-price {
                font-size: 14px;
            }

        }


        /* =====================================================
         * SMALL PHONE
         * ===================================================== */

        @media (max-width: 420px) {

            .mini-product-grid {
                gap: 7px;
            }

            .mini-product-card {
                height: 190px;
                padding: 7px;
                border-radius: 11px;
            }

            .mini-product-image-wrap {
                height: 110px;
                flex-basis: 110px;
            }

            .mini-product-name {
                font-size: 12px;
                height: 32px;
                min-height: 32px;
            }

            .mini-product-price {
                font-size: 13px;
            }

            .mini-product-edit {
                width: 38px;
                height: 38px;
                top: 6px;
                right: 6px;
                font-size: 17px;
            }

        }

        /* =========================================================
         * MINI PRODUCT MODAL
         * ========================================================= */

        .mini-modal { position: fixed; inset: 0; z-index: 99999; display: none; }
        .mini-modal.show { display: flex; align-items: center; justify-content: center; }
        .mini-modal-overlay { position: absolute; inset: 0; background: rgba(0,0,0,.48); backdrop-filter: blur(2px); }
        .mini-modal-dialog { position: relative; z-index: 2; width: min(560px, calc(100vw - 30px)); max-height: calc(100vh - 30px); display: flex; flex-direction: column; background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.25); overflow: hidden; animation: miniModalIn .16s ease; }
        @keyframes miniModalIn { from { opacity: 0; transform: translateY(10px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .mini-modal-header { flex: 0 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 13px 16px; border-bottom: 1px solid #eee; }
        .mini-modal-header-info { min-width: 0; }
        .mini-modal-header-name { font-size: 19px; font-weight: 700; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mini-modal-header-price { margin-top: 3px; color: #1680ca; font-size: 18px; font-weight: 700; }
        .mini-modal-close { width: 42px; height: 42px; flex: 0 0 42px; border: 0; border-radius: 50%; background: #f1f2f4; color: #333; font-size: 27px; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .mini-modal-close:hover { background: #e5e6e8; }
        .mini-modal-body { flex: 1; min-height: 0; overflow-y: auto; padding: 17px 18px; }
        .mini-modal-section { margin-bottom: 16px; }
        .mini-modal-label { margin-bottom: 7px; font-size: 14px; font-weight: 700; }
        .mini-modal-two-col { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,1fr); gap: 14px; margin-bottom: 16px; }
        .mini-modal-field { min-width: 0; }
        .mini-modal-field label { display: block; margin-bottom: 7px; font-size: 14px; font-weight: 700; }
        .mini-input-money { position: relative; }
        .mini-input-money span { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #999; font-size: 13px; pointer-events: none; }
        .mini-input-money .mini-modal-input { padding-right: 30px; }
        .mini-modal-input { width: 100%; height: 44px; border: 1px solid #ddd; border-radius: 9px; background: #fff; padding: 10px 12px; font-family: inherit; font-size: 14px; outline: none; }
        .mini-modal-input:focus, .mini-qty-input:focus { border-color: #2587d8; box-shadow: 0 0 0 2px rgba(37,135,216,.08); }
        .mini-modal-options { display: flex; flex-wrap: wrap; gap: 8px; }
        .mini-modal-option { min-height: 42px; padding: 0 17px; border: 1px solid #ddd; border-radius: 9px; background: #fff; cursor: pointer; font-size: 14px; font-weight: 600; }
        .mini-modal-option.active { border-color: #222; background: #222; color: #fff; }
        .mini-modal-quantity { display: flex; align-items: center; width: fit-content; }
        .mini-qty-btn { width: 46px; height: 44px; border: 1px solid #ddd; background: #f7f7f7; font-size: 22px; cursor: pointer; }
        .mini-qty-btn:first-child { border-radius: 9px 0 0 9px; }
        .mini-qty-btn:last-child { border-radius: 0 9px 9px 0; }
        .mini-qty-input { width: 68px; height: 44px; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; border-left: 0; border-right: 0; text-align: center; font-size: 16px; font-weight: 600; outline: none; }
        .mini-note-grid { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); column-gap: 12px; row-gap: 8px; margin-top: 10px; }
        .mini-note-item { min-height: 42px; display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer; user-select: none; font-size: 14px; font-weight: 600; }
        .mini-note-checkbox { position: absolute; opacity: 0; pointer-events: none; }
        .mini-note-box { width: 30px; height: 30px; flex: 0 0 30px; border: 1px solid #999; border-radius: 4px; background: #fff; display: flex; align-items: center; justify-content: center; color: transparent; font-size: 19px; font-weight: 700; transition: background .12s ease,border-color .12s ease,color .12s ease; }
        .mini-note-item:hover .mini-note-box { border-color: #1680ca; }
        .mini-note-checkbox:checked + .mini-note-box { background: #1680ca; border-color: #1680ca; color: #fff; }
        .mini-note-text { line-height: 1.2; word-break: break-word; }
        .mini-modal-footer { flex: 0 0 auto; display: flex; gap: 10px; padding: 14px 18px; border-top: 1px solid #eee; background: #fff; }
        .mini-modal-btn { flex: 1; height: 46px; border: 0; border-radius: 9px; font-size: 14px; font-weight: 700; cursor: pointer; }
        .mini-modal-btn-cancel { background: #eceef0; color: #333; }
        .mini-modal-btn-primary { background: #222; color: #fff; }
        .mini-modal-btn-primary:hover { background: #111; }
        body.mini-modal-open { overflow: hidden; }
        @media (max-width: 600px) {
            .mini-modal { align-items: flex-end; }
            .mini-modal-dialog { width: 100%; max-height: 92vh; border-radius: 18px 18px 0 0; animation: miniModalMobileIn .18s ease; }
            @keyframes miniModalMobileIn { from { transform: translateY(100%); } to { transform: translateY(0); } }
            .mini-modal-body { padding: 15px; }
            .mini-modal-header { padding: 12px 15px; }
            .mini-modal-footer { padding: 12px 15px; }
            .mini-note-grid { grid-template-columns: repeat(2,minmax(0,1fr)); column-gap: 10px; }
        }



        /* =====================================================
         * MINI MODAL - 2B LAYOUT / DISCOUNT
         * ===================================================== */

        .mini-modal-two-col {
            display: grid;
            grid-template-columns: 65% 35%;
            gap: 0;
            margin-bottom: 16px;
        }

        .mini-modal-two-col > .mini-modal-field:first-child {
            padding-right: 10px;
        }

        .mini-modal-two-col > .mini-modal-field:last-child {
            padding-left: 10px;
        }

        /* Giữ đúng cấu trúc field của POS cũ */
        .box_notes {
            padding: 0;
        }

        .box_notes .form-control.kb-text {
            text-transform: uppercase;
        }

        .box_notes .form-control.kb-text {
            width: 100%;
        }

        .box_comment {
            width: 100%;
        }

        .comment_col {
            display: inline-flex;
            align-items: center;
            margin: 0 12px 8px 0;
        }

        .chkComment {
            margin: 0 5px 0 0;
            width: 28px; 
            height: 28px; 
            vertical-align: bottom;
            margin-right: 5px;
            cursor: pointer;
        }

        .lblComment {
            margin: 0;
            cursor: pointer;
            font-weight: 600;
        }

        .mini-modal-two-col-bottom {
            margin-bottom: 8px;
        }

        .mini-discount-control {
            display: flex;
            gap: 8px;
            align-items: stretch;
        }

        .mini-discount-control .mini-input-money {
            flex: 1;
            min-width: 0;
        }

        .mini-discount-type {
            display: flex;
            flex: 0 0 auto;
            height: 44px;
            border: 1px solid #ddd;
            border-radius: 9px;
            overflow: hidden;
            background: #f7f7f7;
        }

        .mini-discount-type-btn {
            min-width: 42px;
            padding: 0 10px;
            border: 0;
            border-right: 1px solid #ddd;
            background: transparent;
            color: #555;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .mini-discount-type-btn:last-child {
            border-right: 0;
        }

        .mini-discount-type-btn.active {
            background: #222;
            color: #fff;
        }
        .mini-discount-stepper {
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .mini-discount-step-btn {
            width: 42px;
            height: 44px;
            flex: 0 0 42px;
            border: 1px solid #ddd;
            background: #f7f7f7;
            color: #333;
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
        }

        .mini-discount-step-btn:first-child {
            border-radius: 9px 0 0 9px;
        }

        .mini-discount-step-btn:last-child {
            border-radius: 0 9px 9px 0;
        }

        .mini-discount-stepper .mini-modal-input {
            border-radius: 0;
            border-left: 0;
            border-right: 0;
            text-align: center;
            min-width: 0;
        }

        .mini-discount-stepper .mini-input-money {
            flex: 1;
            min-width: 0;
        }

        .mini-discount-stepper .mini-input-money span {
            right: 8px;
        }


        .mini-modal-quick-notes-section {
            margin-top: 2px;
        }

        .mini-modal-quick-notes-section .mini-modal-label {
            color: #666;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .mini-modal-two-col {
                grid-template-columns: 65% 35%;
                gap: 0;
            }

            .mini-modal-two-col > .mini-modal-field:first-child {
                padding-right: 6px;
            }

            .mini-modal-two-col > .mini-modal-field:last-child {
                padding-left: 6px;
            }

            .mini-discount-control {
                gap: 6px;
            }

            .mini-discount-type-btn {
                min-width: 38px;
                padding: 0 8px;
            }
        }

    </style>

</head>


<body>

<div class="mini-app">


    <!-- =====================================================
         HEADER
         ===================================================== -->

    <header class="mini-header">

        <div class="mini-logo">
            🧋 MINI POS
        </div>

        <div class="mini-user">

            <?php
            if (isset($user) && $user) {

                if (!empty($user->first_name)) {
                    echo html_escape($user->first_name);
                }

            }
            ?>

        </div>

    </header>


    <!-- =====================================================
         MAIN
         ===================================================== -->

    <main class="mini-main">


        <!-- =================================================
             ORDER PANEL
             ================================================= -->

        <section class="mini-order">

            <div class="mini-order-title">
                Đơn hàng
            </div>

            <div class="mini-order-empty">

                Chưa có món nào

            </div>

        </section>


        <!-- =================================================
             PRODUCT AREA
             ================================================= -->

        <section class="mini-products">


            <!-- SEARCH -->

            <input
                type="text"
                id="mini-product-search"
                class="mini-search"
                placeholder="🔍 Tìm món..."
                autocomplete="off"
            >


            <!-- CATEGORY -->

            <div class="mini-categories">

                <button
                    type="button"
                    class="mini-category active"
                    data-category="all"
                >
                    Tất cả
                </button>


                <?php if (!empty($categories)): ?>

                    <?php foreach ($categories as $category): ?>

                        <?php

                        /*
                         * Hiện Mini đang dùng category_id = 38.
                         *
                         * Sau này khi chốt cấu trúc category Mini,
                         * mình sẽ mở rộng phần này.
                         */

                        if ((int) $category->id !== 38) {
                            continue;
                        }

                        ?>

                        <button
                            type="button"
                            class="mini-category"
                            data-category="<?= (int) $category->id; ?>"
                        >
                            <?= html_escape($category->name); ?>
                        </button>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>


            <!-- =================================================
                 PRODUCT GRID
                 ================================================= -->

            <div
                id="mini-product-grid"
                class="mini-product-grid"
            >

                <?php if (!empty($products)): ?>


                    <?php foreach ($products as $product): ?>

                        <?php

                        $image = !empty($product->image)
                            ? $product->image
                            : 'no_image.png';

                        ?>


                        <button
                            type="button"
                            class="mini-product-card"
                            data-product-id="<?= (int) $product->id; ?>"
                            data-code="<?= html_escape($product->code); ?>"
                            data-category="<?= (int) $product->category_id; ?>"
                            data-price="<?= (float) $product->price; ?>"
                        >


                            <!-- PROMO -->

                            <?php if (
                                !empty($product->is_promo)
                            ): ?>

                                <span class="mini-promo">
                                    KM
                                </span>

                            <?php endif; ?>


                            <!-- EDIT -->

                            <span
                                class="mini-product-edit"
                                role="button"
                                tabindex="0"
                                title="Chi tiết món"
                                data-product-id="<?= (int) $product->id; ?>"
                            >
                                ✎
                            </span>


                            <!-- IMAGE -->

                            <div class="mini-product-image-wrap">

                                <img
                                    src="<?= base_url('assets/uploads/thumbs/' . $image); ?>"
                                    alt="<?= html_escape($product->name); ?>"
                                    class="mini-product-image"
                                    loading="lazy"
                                >

                            </div>


                            <!-- NAME -->

                            <div class="mini-product-name">

                                <?= html_escape($product->name); ?>

                            </div>


                            <!-- PRICE -->

                            <div class="mini-product-price">


                                <?php if (
                                    !empty($product->is_promo) &&
                                    isset($product->original_price) &&
                                    $product->original_price != $product->price
                                ): ?>

                                    <span class="mini-product-old-price">

                                        <?= $this->sma->formatMoney(
                                            $product->original_price
                                        ); ?>

                                    </span>

                                <?php endif; ?>


                                <?= $this->sma->formatMoney(
                                    $product->price
                                ); ?>


                            </div>


                        </button>

                    <?php endforeach; ?>


                <?php else: ?>

                    <div class="mini-no-products">

                        Không có sản phẩm Mini.

                    </div>

                <?php endif; ?>

            </div>


        </section>

    </main>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const grid = document.getElementById('mini-product-grid');

    if (!grid) {
        return;
    }


    /* =========================================================
     * MINI PRODUCT MODAL
     * ========================================================= */

    let miniModal = null;


    /*
     * ---------------------------------------------------------
     * Tạo modal bằng JS
     * ---------------------------------------------------------
     */

    function createProductModal() {

        if (document.getElementById('mini-product-modal')) {
            miniModal = document.getElementById('mini-product-modal');
            return;
        }

        const modalHtml = `
            <div id="mini-product-modal" class="mini-modal" aria-hidden="true">

                <div class="mini-modal-overlay" data-mini-modal-close></div>

                <div class="mini-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="mini-modal-product-name">

                    <div class="mini-modal-header">
                        <div class="mini-modal-header-info">
                            <div id="mini-modal-product-name" class="mini-modal-header-name">Chi tiết món</div>
                            <div id="mini-modal-product-price" class="mini-modal-header-price">0đ</div>
                        </div>
                        <button type="button" class="mini-modal-close" data-mini-modal-close aria-label="Đóng">×</button>
                    </div>

                    <div class="mini-modal-body">

                        <!-- SỐ LƯỢNG + GIẢM GIÁ -->

                        <div class="mini-modal-two-col mini-modal-two-col-top">

                            <div class="mini-modal-field">

                                <label>
                                    Số lượng
                                </label>

                                <div class="mini-modal-quantity">

                                    <button
                                        type="button"
                                        id="mini-qty-minus"
                                        class="mini-qty-btn"
                                    >
                                        −
                                    </button>

                                    <input
                                        type="number"
                                        id="mini-qty"
                                        class="mini-qty-input"
                                        value="1"
                                        min="1"
                                        inputmode="numeric"
                                    >

                                    <button
                                        type="button"
                                        id="mini-qty-plus"
                                        class="mini-qty-btn"
                                    >
                                        +
                                    </button>

                                </div>

                            </div>


                            <div class="mini-modal-field">

                                <label for="mini-item-discount">
                                    Giảm giá
                                </label>

                                <div class="mini-discount-control">

                                    <div class="mini-discount-stepper">

                                        <button
                                            type="button"
                                            id="mini-discount-minus"
                                            class="mini-discount-step-btn"
                                            aria-label="Giảm mức giảm giá"
                                        >
                                            −
                                        </button>

                                        <div class="mini-input-money">

                                            <input
                                                type="text"
                                                class="form-control kb-pad mini-modal-input"
                                                id="pdiscount"
                                                value="0"
                                                autocomplete="off"
                                                inputmode="numeric"
                                            >

                                            <span id="mini-discount-suffix">đ</span>

                                        </div>

                                        <button
                                            type="button"
                                            id="mini-discount-plus"
                                            class="mini-discount-step-btn"
                                            aria-label="Tăng mức giảm giá"
                                        >
                                            +
                                        </button>

                                    </div>

                                    <div class="mini-discount-type">

                                        <button
                                            type="button"
                                            class="mini-discount-type-btn active"
                                            data-discount-type="amount"
                                        >
                                            đ
                                        </button>

                                        <button
                                            type="button"
                                            class="mini-discount-type-btn"
                                            data-discount-type="percent"
                                        >
                                            %
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- SIZE / OPTION -->

                        <div
                            id="mini-modal-options-section"
                            class="mini-modal-section"
                            style="display:none;"
                        >

                            <div class="mini-modal-label">
                                Size / Option
                            </div>

                            <div
                                id="mini-modal-options"
                                class="mini-modal-options"
                            ></div>

                        </div>


                        <!-- GHI CHÚ + TÊN DÁN LY -->

                        <div class="mini-modal-two-col mini-modal-two-col-bottom box_notes">

                            <div class="mini-modal-field">

                                <label for="icomment">
                                    Ghi chú món
                                </label>

                                <input
                                    type="text"
                                    style="text-transform: uppercase;"
                                    name="comment"
                                    class="form-control kb-text mini-modal-input"
                                    id="icomment"
                                    maxlength="500"
                                    autocomplete="off"
                                    placeholder="Chọn ghi chú hoặc nhập thêm..."
                                >

                            </div>


                            <div class="mini-modal-field">

                                <label for="icommentname">
                                    Tên dán ly
                                </label>

                                <input
                                    type="text"
                                    style="text-transform: uppercase;"
                                    name="commentname"
                                    class="form-control kb-text mini-modal-input"
                                    id="icommentname"
                                    maxlength="100"
                                    autocomplete="off"
                                    placeholder="Tên khách..."
                                >

                            </div>

                        </div>


                        <!-- GHI CHÚ NHANH -->

                        <div class="mini-modal-section mini-modal-quick-notes-section box_comment">

                            <div class="mini-modal-label">
                                Ghi chú nhanh
                            </div>

                            <div class="mini-note-grid">

                                <?php if (!empty($order_comment_list)): ?>

                                    <?php foreach ($order_comment_list as $comment): ?>

                                        <div class="comment_col">

                                            <input
                                                class="chkComment"
                                                type="checkbox"
                                                id="<?= (int) $comment->id; ?>"
                                                value="<?= html_escape($comment->comment); ?>"
                                            >

                                            <label
                                                class="lblComment"
                                                for="<?= (int) $comment->id; ?>"
                                            >
                                                <?= html_escape($comment->comment); ?>
                                            </label>

                                        </div>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                    <div class="mini-modal-footer">
                        <button type="button" class="mini-modal-btn mini-modal-btn-cancel" data-mini-modal-close>HỦY</button>
                        <button type="button" id="mini-modal-add" class="mini-modal-btn mini-modal-btn-primary">THÊM VÀO ĐƠN</button>
                    </div>

                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        miniModal = document.getElementById('mini-product-modal');

        miniModal.querySelectorAll('[data-mini-modal-close]').forEach(function (button) {
            button.addEventListener('click', closeProductModal);
        });

        document.getElementById('mini-qty-minus').addEventListener('click', function () { changeModalQuantity(-1); });
        document.getElementById('mini-qty-plus').addEventListener('click', function () { changeModalQuantity(1); });
        document.getElementById('mini-qty').addEventListener('change', normalizeModalQuantity);
        document.getElementById('mini-discount-minus').addEventListener('click', function () {
            changeMiniDiscount(-1);
        });
        document.getElementById('mini-discount-plus').addEventListener('click', function () {
            changeMiniDiscount(1);
        });
        document.getElementById('mini-modal-add').addEventListener('click', handleModalAdd);

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && miniModal && miniModal.classList.contains('show')) {
                closeProductModal();
            }
        });
    }

    /*
     * ---------------------------------------------------------
     * Open
     * ---------------------------------------------------------
     */


    let miniBasePrice = 0;
    let miniDiscountType = 'amount';


    function formatMiniMoney(value) {

        value = Math.max(0, Math.round(Number(value) || 0));

        return value.toLocaleString('vi-VN') + 'đ';

    }


    function getMiniDiscountValue() {

        const input = document.getElementById(
            'pdiscount'
        );

        if (!input) {
            return 0;
        }

        let value = parseFloat(input.value) || 0;

        if (value < 0) {
            value = 0;
        }

        if (miniDiscountType === 'percent') {
            value = Math.min(100, value);
        }

        return value;

    }


    function updateMiniModalPrice() {

        if (!miniModal) {
            return;
        }

        const discountValue = getMiniDiscountValue();

        let discountAmount = discountValue;

        if (miniDiscountType === 'percent') {
            discountAmount =
                miniBasePrice * discountValue / 100;
        }

        discountAmount =
            Math.min(miniBasePrice, discountAmount);

        const finalPrice =
            Math.max(0, miniBasePrice - discountAmount);

        const priceEl =
            document.getElementById(
                'mini-modal-product-price'
            );

        if (priceEl) {
            priceEl.textContent =
                formatMiniMoney(finalPrice);
        }

        const suffix =
            document.getElementById(
                'mini-discount-suffix'
            );

        if (suffix) {
            suffix.textContent =
                miniDiscountType === 'percent'
                    ? '%'
                    : 'đ';
        }

    }


    function setMiniDiscountType(type) {

        miniDiscountType =
            type === 'percent'
                ? 'percent'
                : 'amount';

        const input =
            document.getElementById('pdiscount');

        if (input) {
            input.value = 0;
            input.max =
                miniDiscountType === 'percent'
                    ? 100
                    : '';
            input.step =
                miniDiscountType === 'percent'
                    ? '1'
                    : '1000';
            input.inputMode = 'numeric';
        }

        const suffix =
            document.getElementById('mini-discount-suffix');

        if (suffix) {
            suffix.textContent =
                miniDiscountType === 'percent' ? '%' : 'đ';
        }

        miniModal
            .querySelectorAll('.mini-discount-type-btn')
            .forEach(function (button) {
                button.classList.toggle(
                    'active',
                    button.dataset.discountType === miniDiscountType
                );
            });

        updateMiniModalPrice();

    }


    function changeMiniDiscount(direction) {

        const input =
            document.getElementById('pdiscount');

        if (!input) {
            return;
        }

        let value = parseFloat(input.value) || 0;

        const step =
            miniDiscountType === 'percent'
                ? 1
                : 1000;

        value += direction * step;

        if (value < 0) {
            value = 0;
        }

        if (miniDiscountType === 'percent' && value > 100) {
            value = 100;
        }

        input.value = value;
        updateMiniModalPrice();

    }


    function updateMiniNoteField() {

        if (!miniModal) {
            return;
        }

        const input =
            document.getElementById('icomment');

        if (!input) {
            return;
        }

        const quickNotes = [];

        miniModal
            .querySelectorAll('.chkComment')
            .forEach(function (checkbox) {
                if (checkbox.checked) {
                    quickNotes.push(
                        checkbox.value.trim()
                    );
                }
            });

        const knownNotes = [];

        miniModal
            .querySelectorAll('.chkComment')
            .forEach(function (checkbox) {
                const value = checkbox.value.trim();

                if (value) {
                    knownNotes.push(value);
                }
            });

        const currentParts =
            input.value
                .split(',')
                .map(function (part) {
                    return part.trim();
                })
                .filter(function (part) {
                    return part !== '';
                });

        const manualParts =
            currentParts.filter(function (part) {
                return knownNotes.indexOf(part) === -1;
            });

        input.value =
            manualParts
                .concat(quickNotes)
                .join(', ');

    }


    function openProductModal(card) {

        createProductModal();


        const productId =
            card.dataset.productId;

        const code =
            card.dataset.code || '';


        const name =
            card.querySelector(
                '.mini-product-name'
            )?.textContent.trim() || '';


        miniBasePrice =
            parseFloat(
                card.dataset.price || 0
            ) || 0;


        /*
         * Lưu product hiện tại
         */

        miniModal.dataset.productId =
            productId;

        miniModal.dataset.code =
            code;


        /*
         * Header
         */

        document.getElementById(
            'mini-modal-product-name'
        ).textContent = name;


        /*
         * Price
         *
         * Lấy lại HTML giá từ card.
         */

        document.getElementById(
            'mini-modal-product-price'
        ).textContent =
            formatMiniMoney(miniBasePrice);


        /*
         * Reset form
         */

        document.getElementById(
            'mini-qty'
        ).value = 1;


        document.getElementById(
            'pdiscount'
        ).value = 0;

        setMiniDiscountType('amount');


        document.getElementById(
            'icommentname'
        ).value = '';


        document.getElementById(
            'icomment'
        ).value = '';


        miniModal
            .querySelectorAll('.chkComment')
            .forEach(function (checkbox) {
                checkbox.checked = false;
            });


        /*
         * Discount realtime
         */

        const discountInput =
            document.getElementById(
                'pdiscount'
            );

        if (discountInput) {
            discountInput.oninput = function () {

                let value = parseFloat(this.value) || 0;

                if (value < 0) {
                    value = 0;
                }

                if (miniDiscountType === 'amount') {
                    value = Math.round(value / 1000) * 1000;
                } else {
                    value = Math.round(value);
                    value = Math.min(100, value);
                }

                this.value = value;
                updateMiniModalPrice();
            };
        }


        /*
         * Discount amount / percent
         */

        miniModal
            .querySelectorAll(
                '.mini-discount-type-btn'
            )
            .forEach(function (button) {

                button.onclick = function () {

                    setMiniDiscountType(
                        this.dataset.discountType
                    );

                };

            });


        /*
         * Quick notes -> text field
         */

        miniModal
            .querySelectorAll(
                '.chkComment'
            )
            .forEach(function (checkbox) {

                checkbox.onchange =
                    updateMiniNoteField;

            });


        updateMiniModalPrice();


        /*
         * Options
         *
         * 2B hiện chưa gọi AJAX.
         *
         * Nếu product có variants được
         * truyền xuống view, mình sẽ dùng
         * data JSON ở bước tiếp theo.
         */

        renderModalOptions(card);


        /*
         * Show
         */

        miniModal.classList.add('show');

        miniModal.setAttribute(
            'aria-hidden',
            'false'
        );


        document.body.classList.add(
            'mini-modal-open'
        );

    }


    /*
     * ---------------------------------------------------------
     * Options
     * ---------------------------------------------------------
     */

    function renderModalOptions(card) {

        const section =
            document.getElementById(
                'mini-modal-options-section'
            );

        const container =
            document.getElementById(
                'mini-modal-options'
            );


        /*
         * Bước 2B trước mắt chưa đưa variants
         * vào data-card.
         *
         * Vì vậy ẩn section.
         *
         * Bước tiếp theo mình sẽ đưa variants
         * từ Pos_model xuống đúng JSON.
         */

        section.style.display = 'none';

        container.innerHTML = '';

    }


    /*
     * ---------------------------------------------------------
     * Close
     * ---------------------------------------------------------
     */

    function closeProductModal() {

        if (!miniModal) {
            return;
        }

        miniModal.classList.remove('show');

        miniModal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'mini-modal-open'
        );

    }


    /*
     * ---------------------------------------------------------
     * Quantity
     * ---------------------------------------------------------
     */

    function changeModalQuantity(amount) {

        const input =
            document.getElementById(
                'mini-qty'
            );

        let qty =
            parseInt(input.value, 10) || 1;


        qty += amount;


        if (qty < 1) {
            qty = 1;
        }


        input.value = qty;

    }


    function normalizeModalQuantity() {

        const input =
            document.getElementById(
                'mini-qty'
            );

        let qty =
            parseInt(input.value, 10) || 1;


        if (qty < 1) {
            qty = 1;
        }


        input.value = qty;

    }


    /*
     * ---------------------------------------------------------
     * Add from modal
     * ---------------------------------------------------------
     */

    function handleModalAdd() {

        if (!miniModal) {
            return;
        }


        const productId =
            miniModal.dataset.productId;


        const qty =
            parseInt(
                document.getElementById(
                    'mini-qty'
                ).value,
                10
            ) || 1;


        const discountInput =
            parseFloat(
                document.getElementById(
                    'pdiscount'
                ).value
            ) || 0;

        const discountAmount =
            miniDiscountType === 'percent'
                ? Math.min(
                    miniBasePrice,
                    miniBasePrice * Math.min(100, Math.max(0, discountInput)) / 100
                )
                : Math.min(
                    miniBasePrice,
                    Math.max(0, discountInput)
                );

        // POS cũ nhận giảm giá theo:
        //   1000   = giảm 1.000đ
        //   1%     = giảm 1%
        const discount =
            miniDiscountType === 'percent'
                ? (Math.min(100, Math.max(0, discountInput)) + '%')
                : Math.round(discountAmount);

        const discountType =
            miniDiscountType;


        const cupName =
            document.getElementById(
                'icommentname'
            ).value.trim();


        const note =
            document.getElementById(
                'icomment'
            ).value.trim();

        console.log(
            'MINI MODAL ADD:',
            {
                product_id: productId,
                quantity: qty,
                discount: discount,
                discount_type: discountType,
                cup_name: cupName,
                note: note
            }
        );


        /*
         * Tìm card tương ứng
         * để feedback.
         */

        const card =
            grid.querySelector(
                '.mini-product-card[data-product-id="' +
                productId +
                '"]'
            );


        if (card) {

            card.classList.remove(
                'quick-added'
            );

            void card.offsetWidth;

            card.classList.add(
                'quick-added'
            );

        }


        /*
         * Chưa add cart thật ở 2B.
         */

        closeProductModal();

    }


    /*
     * =========================================================
     * PRODUCT CARD EVENTS
     * =========================================================
     */

    grid.querySelectorAll(
        '.mini-product-card'
    ).forEach(function (card) {


        /*
         * QUICK ADD
         *
         * Chạm thân card.
         */

        card.addEventListener(
            'click',
            function (event) {


                /*
                 * Nếu click Edit
                 * thì bỏ qua Quick Add.
                 */

                if (
                    event.target.closest(
                        '.mini-product-edit'
                    )
                ) {

                    return;

                }


                const productId =
                    this.dataset.productId;

                const code =
                    this.dataset.code;


                console.log(
                    'MINI QUICK ADD:',
                    productId,
                    code
                );


                /*
                 * Feedback
                 */

                this.classList.remove(
                    'quick-added'
                );

                void this.offsetWidth;

                this.classList.add(
                    'quick-added'
                );


                /*
                 * Cart thật sẽ nối ở bước tiếp theo.
                 */

            }
        );


        /*
         * EDIT
         */

        const editButton =
            card.querySelector(
                '.mini-product-edit'
            );


        if (editButton) {

            editButton.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();


                    openProductModal(card);

                }
            );

        }


    });


    /*
     * =========================================================
     * SEARCH
     * =========================================================
     */

    const search =
        document.getElementById(
            'mini-product-search'
        );


    if (search) {

        search.addEventListener(
            'input',
            function () {

                const keyword =
                    this.value
                        .toLowerCase()
                        .trim();


                grid.querySelectorAll(
                    '.mini-product-card'
                ).forEach(function (card) {

                    const name =
                        card.querySelector(
                            '.mini-product-name'
                        );


                    const text =
                        name
                            ? name.textContent
                                .toLowerCase()
                            : '';


                    card.style.display =
                        (
                            !keyword ||
                            text.indexOf(keyword) !== -1
                        )
                            ? ''
                            : 'none';

                });

            }
        );

    }


    /*
     * =========================================================
     * CATEGORY FILTER
     * =========================================================
     */

    document.querySelectorAll(
        '.mini-category'
    ).forEach(function (button) {

        button.addEventListener(
            'click',
            function () {


                document.querySelectorAll(
                    '.mini-category'
                ).forEach(function (item) {

                    item.classList.remove(
                        'active'
                    );

                });


                this.classList.add(
                    'active'
                );


                const category =
                    this.dataset.category;


                grid.querySelectorAll(
                    '.mini-product-card'
                ).forEach(function (card) {

                    const cardCategory =
                        card.dataset.category;


                    if (
                        category === 'all' ||
                        category === cardCategory
                    ) {

                        card.style.display = '';

                    } else {

                        card.style.display = 'none';

                    }

                });

            }
        );

    });


});
</script>


</body>
</html>

<style id="mini-modal-layout-v2">
/* =========================================================
 * MINI MODAL - TỶ LỆ 2 DÒNG TÁCH BIỆT
 * ========================================================= */

/* Dòng 1: Số lượng 40% - Giảm giá 60% */
.mini-modal-dialog .mini-modal-two-col-top {
    display: grid;
    grid-template-columns: 40% 60%;
    gap: 0;
}

/* Dòng 2: Ghi chú 65% - Tên dán ly 35% */
.mini-modal-dialog .mini-modal-two-col-bottom {
    display: grid;
    grid-template-columns: 65% 35%;
    gap: 0;
}

.mini-modal-dialog .mini-modal-two-col-top > .mini-modal-field:first-child,
.mini-modal-dialog .mini-modal-two-col-bottom > .mini-modal-field:first-child {
    padding-right: 10px;
}

.mini-modal-dialog .mini-modal-two-col-top > .mini-modal-field:last-child,
.mini-modal-dialog .mini-modal-two-col-bottom > .mini-modal-field:last-child {
    padding-left: 10px;
}

/* Checkbox ghi chú nhanh: lớn lại để dễ chạm */
.mini-modal-dialog .mini-note-item {
    min-height: 44px;
    gap: 9px;
}

.mini-modal-dialog .mini-note-box {
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    font-size: 22px;
}

@media (max-width: 600px) {
    .mini-modal-dialog .mini-modal-two-col-top {
        grid-template-columns: 40% 60%;
    }

    .mini-modal-dialog .mini-modal-two-col-bottom {
        grid-template-columns: 65% 35%;
    }

    .mini-modal-dialog .mini-modal-two-col-top > .mini-modal-field:first-child,
    .mini-modal-dialog .mini-modal-two-col-bottom > .mini-modal-field:first-child {
        padding-right: 6px;
    }

    .mini-modal-dialog .mini-modal-two-col-top > .mini-modal-field:last-child,
    .mini-modal-dialog .mini-modal-two-col-bottom > .mini-modal-field:last-child {
        padding-left: 6px;
    }
}
</style>