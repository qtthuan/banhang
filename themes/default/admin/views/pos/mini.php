<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- CSS riêng của MINI POS -->
    <link
        rel="stylesheet"
        type="text/css"
        href="<?=$assets?>pos/css/mini.css?v=20260821-5"
    >
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

        <nav class="mini-header-actions" aria-label="Loại đơn">

            <button type="button"
                    class="mini-header-mode active"
                    data-order-mode="table">
                BÀN
            </button>
            <button type="button"
                    class="mini-header-mode"
                    data-order-mode="takeaway">
                MANG ĐI
            </button>

            <button type="button"
                    class="mini-header-order">
                ĐƠN HÀNG
            </button>

        </nav>

        <div class="mini-user">
            <?php
            if (isset($user) && $user && !empty($user->first_name)) {
                echo html_escape($user->first_name);
            }
            ?>
        </div>

    </header>


    <main class="mini-main row g-0">

        <!-- =================================================
             ORDER / CART
             ================================================= -->
        <section class="mini-order">

            <!-- Thông tin động của đơn -->
            <div class="mini-order-info-panel">

                <div id="mini-order-info-mode"
                     class="mini-order-info-mode">
                    BÀN
                </div>

                <div id="mini-order-info-main"
                     class="mini-order-info-main">
                    Chưa chọn bàn
                </div>

                <div id="mini-order-info-sub"
                     class="mini-order-info-sub">
                    Khách: Khách lẻ
                </div>

            </div>


            <!-- Header cột -->
            <div class="mini-cart-head">
                <span>MÓN</span>
                <span>ĐƠN GIÁ</span>
                <span>SL</span>
                <span>THÀNH TIỀN</span>
            </div>


            <!-- Danh sách món -->
            <div id="mini-cart-list"
                 class="mini-cart-list">

                <div id="mini-cart-empty"
                     class="mini-cart-empty">

                    <div class="mini-empty-icon">🛒</div>

                    <div>Chưa có món nào</div>

                    <small>
                        Chạm vào món bên phải để thêm vào đơn
                    </small>

                </div>

            </div>


            <!-- Khu dưới giỏ -->
            <div class="mini-order-bottom">
                <input type="hidden"
                    id="mini_delivery_fee"
                    name="delivery_fee"
                    value="0">

                <input type="hidden"
                    id="mini_order_discount_value"
                    name="order_discount_value"
                    value="0">

                <input type="hidden"
                    id="mini_order_discount_type"
                    name="order_discount_type"
                    value="amount">

                <input type="hidden"
                    id="mini_order_discount_amount"
                    name="order_discount_amount"
                    value="0">

                <input type="hidden"
                    id="mini_order_note"
                    name="order_note"
                    value="">

                <div class="mini-order-tools">

                    <button type="button" class="mini-tool-btn" data-order-tool="ship">
                        <span>🚚</span>
                        <small>Ship</small>
                    </button>

                    <button type="button" class="mini-tool-btn" data-order-tool="discount">
                        <span>🏷</span>
                        <small>Giảm</small>
                    </button>

                    <button type="button" class="mini-tool-btn" data-order-tool="note">
                        <span>📝</span>
                        <small>Ghi</small>
                    </button>

                    <button type="button" class="mini-tool-btn">
                        <span>🔎</span>
                        <small>Bếp</small>
                    </button>

                    <button type="button" class="mini-tool-btn">
                        <span>•••</span>
                        <small>Thêm</small>
                    </button>

                </div>


                <div class="mini-summary">

                    <div>
                        <span>Số lượng</span>
                        <strong id="mini-total-qty">0</strong>
                    </div>

                    <div>
                        <span>Tạm tính</span>
                        <strong id="mini-subtotal">0đ</strong>
                    </div>

                    <div>
                        <span>Giảm giá</span>
                        <strong id="mini-total-discount">0đ</strong>
                    </div>

                    <div>
                        <span>Phí giao hàng</span>
                        <strong id="mini-delivery-fee">0đ</strong>
                    </div>

                </div>


                <div class="mini-grand-total">

                    <span>TỔNG</span>

                    <strong id="mini-grand-total">
                        0đ
                    </strong>

                </div>


                <div class="mini-order-actions d-flex">

                    <button type="button"
                            id="mini-cancel-order"
                            class="mini-action-cancel btn flex-fill rounded-0">
                        HỦY
                    </button>

                    <button type="button"
                            id="mini-payment"
                            class="mini-action-pay btn flex-fill rounded-0">
                        THANH TOÁN
                    </button>

                </div>

            </div>

        </section>


        <!-- =================================================
             PRODUCTS
             ================================================= -->
        <section class="mini-products">

            <!-- Không còn MÓN / số món theo yêu cầu -->

            <div class="mini-categories-wrap">

                <button type="button"
                        class="mini-category-arrow"
                        data-category-scroll="left"
                        aria-label="Danh mục trước">
                    ‹
                </button>

                <div class="mini-categories">

                    <button type="button"
                            class="mini-category active"
                            data-category="all">                        
                        <span>TẤT CẢ</span>
                    </button>

                    <?php if (!empty($categories)): ?>

                        <?php foreach ($categories as $category): ?>                           

                            <button type="button"
                                    class="mini-category"
                                    data-category="<?= (int) $category->id; ?>">
                                <span>
                                    <?= html_escape($category->name); ?>
                                </span>

                            </button>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

                <button type="button"
                        class="mini-category-arrow"
                        data-category-scroll="right"
                        aria-label="Danh mục tiếp">
                    ›
                </button>

            </div>


            <!-- Search -->
            <div id="mini-search-row" class="mini-search-row d-flex gap-2">

                <div class="mini-search-wrap flex-grow-1">

                    <span class="mini-search-icon">⌕</span>

                    <input
                        type="text"
                        id="mini-product-search"
                        class="mini-search form-control"
                        placeholder="Tìm món..."
                        autocomplete="off">

                    <button
                        type="button"
                        id="mini-search-clear"
                        class="mini-search-clear btn p-0"
                        aria-label="Xóa tìm kiếm">
                        ×
                    </button>

                </div>


                <button
                    type="button"
                    id="mini-quick-add"
                    class="mini-quick-add btn p-0 flex-shrink-0"
                    aria-label="Thêm món nhanh"
                    title="Thêm món nhanh">
                    +
                </button>

            </div>

            


            <!-- Product swiper ngang -->
            <div id="mini-product-grid"
                 class="mini-product-grid">

                <?php if (!empty($products)): ?>

                    <?php
                        function mini_display_product_name($name)
                        {
                            $name = trim((string) $name);

                            /*
                            * Bỏ prefix dạng:
                            * A_Tên món
                            * B_Tên món
                            * C_Tên món
                            *
                            * Chỉ bỏ 1 ký tự chữ + dấu _
                            * ở đầu tên.
                            */
                            return preg_replace(
                                '/^[A-Za-z]_/',
                                '',
                                $name
                            );
                        }
                    ?>

                    <?php foreach ($products as $product): ?>

                        <?php
                        $image = !empty($product->image)
                            ? $product->image
                            : 'no_image.png';
                        ?>

                        <button type="button"
                                class="mini-product-card"
                                data-product-id="<?= (int) $product->id; ?>"
                                data-code="<?= html_escape($product->code); ?>"
                                data-category="<?= (int) $product->subcategory_id; ?>"
                                data-price="<?= (float) $product->price; ?>"
                                data-name="<?= html_escape($product->name); ?>"
                                data-variants="<?= html_escape(json_encode($product->variants ?? [], JSON_UNESCAPED_UNICODE)); ?>">

                            <?php if (!empty($product->is_promo)): ?>
                                <span class="mini-promo">KM</span>
                            <?php endif; ?>

                            <span class="mini-product-edit"
                                  role="button"
                                  tabindex="0"
                                  title="Chi tiết món">
                                ✎
                            </span>

                            <div class="mini-product-image-wrap">

                                <img src="<?= base_url('assets/uploads/thumbs/' . $image); ?>"
                                     alt="<?= html_escape($product->name); ?>"
                                     class="mini-product-image"
                                     loading="lazy">

                            </div>

                            <div class="mini-product-name">
                                <?= html_escape(
                                    mini_display_product_name(
                                        $product->name
                                    )
                                ); ?>
                            </div>

                            <div class="mini-product-price">

                                <?php if (
                                    !empty($product->is_promo) &&
                                    isset($product->original_price) &&
                                    $product->original_price != $product->price
                                ): ?>

                                    <span class="mini-product-old-price">
                                        <?= $this->sma->formatMoney($product->original_price); ?>
                                    </span>

                                <?php endif; ?>

                                <?= $this->sma->formatMoney($product->price); ?>

                            </div>

                        </button>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="mini-no-products">
                        Không có sản phẩm Mini.
                    </div>

                <?php endif; ?>

            </div>


            <!-- Swiper controls -->
            <div class="mini-products-footer">

                <button type="button"
                        id="mini-prev-page"
                        aria-label="Trang trước">
                    ‹
                </button>

                <span id="mini-page-label">
                    1 / 1
                </span>

                <button type="button"
                        id="mini-next-page"
                        aria-label="Trang tiếp">
                    ›
                </button>

            </div>

        </section>

    </main>

</div>


<script>
window.miniOrderComments = <?= json_encode(
    array_map(function ($comment) {
        return [
            'id' => (int) $comment->id,
            'comment' => $comment->comment
        ];
    }, !empty($order_comment_list) ? $order_comment_list : []),
    JSON_UNESCAPED_UNICODE
); ?>;
</script>

    <!-- =========================================================
     GLOBAL MINI ALERT
     ========================================================= -->

    <div
        id="mini-global-alert"
        class="alert alert-danger mini-global-alert d-none"
        role="alert"
        aria-live="polite">
    </div>

    <!-- jQuery -->
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js">
    </script>

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <!-- MINI POS -->
    <script
        type="text/javascript"
        src="<?=$assets?>pos/js/mini.js?v=20260821-6">
    </script>

</body>
</html>