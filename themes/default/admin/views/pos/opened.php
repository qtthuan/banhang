
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* MODAL ĐƠN TẠM */
    .modal-dialog:has(ul.ob.pos-suspended-grid) .modal-content {
        border-radius: 18px !important;
        overflow: hidden;
    }

    /* HEADER */
    .modal-dialog:has(ul.ob.pos-suspended-grid) .modal-header {
        position: relative;
        text-align: center;
        padding: 17px 55px !important;
        border-radius: 18px 18px 0 0;
    }

    .modal-dialog:has(ul.ob.pos-suspended-grid) .modal-title {
        font-size: 21px;
        font-weight: 800;
        text-align: center;
        margin: 0;
    }

    /* NÚT ĐÓNG TRÒN MÀU XANH */
    .modal-dialog:has(ul.ob.pos-suspended-grid) .opened-bills-close {
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 0;
        background: #0a560f;
        color: #fff;
        opacity: 1;
        font-size: 25px;
        line-height: 34px;
        text-align: center;
        padding: 0;
        text-shadow: none;
    }

    .modal-dialog:has(ul.ob.pos-suspended-grid) .opened-bills-close:hover {
        background: #185826;
        color: #fff;
    }

    /* BODY */
    .modal-dialog:has(ul.ob.pos-suspended-grid) .modal-body {
        padding: 15px !important;
    }

    .modal-dialog:has(ul.ob.pos-suspended-grid) .ob {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    /* PHÂN TRANG */
    .opened-bills-footer {
        text-align: center;
        padding: 10px 12px 5px;
        border-top: 1px solid #eee;
    }

    .opened-bills-footer .pagination {
        margin: 5px 0;
    }

    .opened-bills-swipe-hint {
        text-align: center;
        color: #3988cf;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 0 10px;
    }

    .modal-dialog:has(ul.ob.pos-suspended-grid) .html_con {
        touch-action: pan-y;
    }

    /* NÚT XÓA TẤT CẢ */
    .modal-dialog:has(ul.ob.pos-suspended-grid) .opened-bills-delete-all {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        border: 1px solid #e6a0a0;
        border-radius: 7px;
        background: #fff5f5;
        color: #c9302c;
        padding: 7px 11px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .modal-dialog:has(ul.ob.pos-suspended-grid) .opened-bills-delete-all:hover {
        background: #c9302c;
        color: #fff;
    }

    @media (max-width: 600px) {
        .modal-dialog:has(ul.ob.pos-suspended-grid) .opened-bills-delete-all {
            padding: 6px 8px;
        }

        .modal-dialog:has(ul.ob.pos-suspended-grid) .opened-bills-delete-all span {
            display: none;
        }
    }
        /* HỘP XÁC NHẬN XÓA */
    .opened-bills-confirm {
        position: absolute;
        z-index: 10000;
        width: 220px;
        padding: 11px;
        border: 1px solid #ddd;
        border-radius: 9px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,.2);
        text-align: left;
    }

    .opened-bills-confirm-card {
        top: 38px;
        right: 0;
    }

    .opened-bills-confirm-all {
        top: calc(100% + 6px);
        left: 14px;
    }

    .opened-bills-confirm-text {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 9px;
        color: #333;
    }

    .opened-bills-confirm-actions {
        display: flex;
        justify-content: flex-end;
        gap: 7px;
    }

    .opened-bills-confirm-actions button {
        border: 0;
        border-radius: 5px;
        padding: 6px 12px;
        font-size: 12px;
        cursor: pointer;
    }

    .opened-bills-confirm-yes {
        background: #dc2626;
        color: #fff;
    }

    .opened-bills-confirm-no {
        background: #eee;
        color: #333;
    }
    /* THÔNG BÁO XÓA THÀNH CÔNG */
    .opened-bills-success {
        display: none;
        position: fixed;
        top: 42px;
        left: 48%;
        transform: translateX(-50%);
        z-index: 20000;

        padding: 34px 35px;
        border-radius: 7px;

        background: #198754;
        color: #fff;

        font-size: 19px;
        font-weight: 700;
        text-align: center;

        box-shadow: 0 4px 16px rgba(0,0,0,.2);
    }
</style>

<div class="modal-dialog">
    <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header modal-primary">

            <!-- NÚT XÓA TẤT CẢ -->
            <?php if (!empty($total_bills)) { ?>
                <button
                    type="button"
                    class="opened-bills-delete-all"
                    data-count="<?= (int) $total_bills ?>"
                >
                    <i class="fa fa-trash"></i>
                    <span>XÓA TẤT CẢ</span>
                </button>
            <?php } ?>

            <!-- NÚT ĐÓNG -->
            <button
                type="button"
                class="opened-bills-close"
                data-dismiss="modal"
                aria-label="Đóng"
            >
                &times;
            </button>

            <h4 class="modal-title" id="ajaxModalLabel">
                ĐƠN TẠM
            </h4>

        </div>

        <!-- DANH SÁCH -->
        <div class="modal-body">

            <div class="html_con">
                <?= $html ?>
            </div>

            <div class="clearfix"></div>

        </div>

        <!-- PHÂN TRANG -->
        <?php if ($page) { ?>

            <div class="opened-bills-footer">

                <div class="page_con">
                    <?= $page ?>
                </div>

                <div class="opened-bills-swipe-hint">
                    <i class="fa fa-arrows-h"></i>
                    Vuốt trái / phải để chuyển trang
                </div>

            </div>

        <?php } ?>

    </div>
</div>

<script>
$(document).ready(function () {

    
    /* =========================================
       XÓA ĐƠN TẠM BẰNG AJAX
    ========================================= */

    function reloadOpenedBills() {

        $.get(
            "<?= admin_url('pos/opened_bills') ?>",
            function (html) {

                $('#myModal').html(html);

            }
        );

    }


    function deleteSuspendedBill(url, data) {

        data[csrfName] = csrfHash;

        $.ajax({

            type: 'POST',

            url: url,

            data: data,

            dataType: 'json',

            success: function (response) {

                // Cập nhật CSRF mới
                if (response && response.csrf_hash) {
                    csrfHash = response.csrf_hash;
                }

                if (response && response.status) {

                    // XÓA TẤT CẢ: ĐÓNG MODAL, BÁO THÀNH CÔNG, QUAY VỀ POS
                    if (typeof response.deleted !== 'undefined') {

                        var deletedCount = parseInt(response.deleted, 10) || 0;

                        // Đóng modal đơn tạm
                        $('#myModal').modal('hide');

                        // Xóa thông báo cũ nếu có
                        $('.opened-bills-success').remove();

                        // Tạo nội dung thông báo
                        var message = deletedCount > 0
                            ? 'Đã xóa thành công ' + deletedCount + ' đơn tạm'
                            : 'Không có đơn tạm nào cần xóa';

                        // Hiện thông báo
                        var $notice = $('<div class="opened-bills-success"></div>')
                            .text(message);

                        $('body').append($notice);

                        $notice
                            .fadeIn(300)
                            .delay(1000)
                            .fadeOut(500, function () {

                                // Tự động quay về màn hình POS
                                window.location.href =
                                    "<?= admin_url('pos/index') ?>";

                            });

                    } else {

                        // XÓA MỘT ĐƠN: GIỮ NGUYÊN TẢI LẠI DANH SÁCH
                        reloadOpenedBills();

                    }

                } else {

                    bootbox.alert(
                        response && response.message
                            ? response.message
                            : 'Không thể xóa đơn tạm.'
                    );

                }

            },

            error: function (xhr) {

                console.log(
                    'DELETE SUSPENDED ERROR:',
                    xhr.status,
                    xhr.responseText
                );

                bootbox.alert(
                    'Có lỗi kết nối hoặc phiên làm việc đã hết hạn.'
                );

            }

        });

    }

        /* HỘP XÁC NHẬN NGAY CẠNH NÚT XÓA */
    function showOpenedConfirm($container, message, onConfirm, type) {

        $('.opened-bills-confirm').remove();

        var $popup = $('<div class="opened-bills-confirm"></div>');

        $popup.addClass(type === 'all'
            ? 'opened-bills-confirm-all'
            : 'opened-bills-confirm-card'
        );

        $popup.append(
            $('<div class="opened-bills-confirm-text"></div>').text(message)
        );

        var $actions = $('<div class="opened-bills-confirm-actions"></div>');

        var $yes = $('<button type="button">Xóa</button>')
            .addClass('opened-bills-confirm-yes');

        var $no = $('<button type="button">Hủy</button>')
            .addClass('opened-bills-confirm-no');

        $actions.append($yes, $no);
        $popup.append($actions);
        $container.append($popup);

        $yes.on('click', function () {
            $popup.remove();
            onConfirm();
        });

        $no.on('click', function () {
            $popup.remove();
        });
    }


    /* XÓA TỪNG ĐƠN */
    $(document)
        .off('click.openedBillsDelete', '.delete-suspended-bill')
        .on(
            'click.openedBillsDelete',
            '.delete-suspended-bill',
            function (e) {

                e.preventDefault();
                e.stopPropagation();

                var id = parseInt($(this).attr('data-id'), 10);

                if (!id) {
                    return false;
                }

                var $container = $(this).closest('.pos-suspended-item');

                showOpenedConfirm(
                    $container,
                    'Bạn muốn xóa đơn tạm #' + id + '?',
                    function () {

                        deleteSuspendedBill(
                            "<?= admin_url('pos/delete_suspended_bill') ?>",
                            { id: id }
                        );

                    },
                    'card'
                );

                return false;

            }
        );


    /* XÓA TẤT CẢ */
    $(document)
        .off('click.openedBillsDeleteAll', '.opened-bills-delete-all')
        .on(
            'click.openedBillsDeleteAll',
            '.opened-bills-delete-all',
            function (e) {

                e.preventDefault();
                e.stopPropagation();

                var total = parseInt(
                    $(this).attr('data-count'),
                    10
                ) || 0;

                var $container = $(this).closest('.modal-header');

                showOpenedConfirm(
                    $container,
                    'Bạn chắc chắn muốn xóa tất cả ' + total + ' đơn tạm?',
                    function () {

                        deleteSuspendedBill(
                            "<?= admin_url('pos/delete_all_suspended_bills') ?>",
                            {}
                        );

                    },
                    'all'
                );
                return false;

            }
        );


    /* Giữ cơ chế phân trang AJAX hiện tại */
    $('.pagination a').attr('data-toggle', 'ajax');


    /* MỞ LẠI ĐƠN TẠM - GIỮ NGUYÊN NGHIỆP VỤ */
    $('.sus_sale').off('click.openedBills').on(
        'click.openedBills',
        function (e) {

            var sid = $(this).attr("id");

            if (count > 1) {

                bootbox.confirm(
                    "<?= $this->lang->line('leave_alert') ?>",
                    function (gotit) {

                        if (gotit == false) {
                            return true;
                        }

                        window.location.href =
                            "<?= admin_url('pos/index') ?>/" + sid;
                    }
                );

            } else {

                window.location.href =
                    "<?= admin_url('pos/index') ?>/" + sid;

            }

            return false;
        }
    );


    /* VUỐT TRÁI / PHẢI ĐỂ CHUYỂN TRANG */
    var openedBillsStartX = 0;
    var openedBillsStartY = 0;


    $(document)
        .off('touchstart.openedBills', '.html_con')
        .on(
            'touchstart.openedBills',
            '.html_con',
            function (e) {

                var touch = e.originalEvent.touches[0];

                openedBillsStartX = touch.clientX;
                openedBillsStartY = touch.clientY;

            }
        );


    $(document)
        .off('touchend.openedBills', '.html_con')
        .on(
            'touchend.openedBills',
            '.html_con',
            function (e) {

                var touch = e.originalEvent.changedTouches[0];

                var diffX = touch.clientX - openedBillsStartX;
                var diffY = touch.clientY - openedBillsStartY;


                /* Bỏ qua thao tác vuốt dọc */
                if (
                    Math.abs(diffX) < 60 ||
                    Math.abs(diffX) < Math.abs(diffY)
                ) {
                    return;
                }


                var $target = $();


                if (diffX < 0) {

                    /* Vuốt trái: trang tiếp */
                    $target = $('.page_con .pagination a[rel="next"]');

                } else {

                    /* Vuốt phải: trang trước */
                    $target = $('.page_con .pagination a[rel="prev"]');

                }


                /* Dự phòng nếu phân trang không có rel */
                if (!$target.length) {

                    var $active = $('.page_con .pagination li.active');

                    if (diffX < 0) {

                        $target = $active.nextAll('li')
                            .first()
                            .find('a');

                    } else {

                        $target = $active.prevAll('li')
                            .first()
                            .find('a');

                    }

                }


                /* Kích hoạt cơ chế AJAX có sẵn */
                if ($target.length) {
                    $target.trigger('click');
                }

            }
        );

});
</script>
