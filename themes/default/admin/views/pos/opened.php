
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
        width: 36px;
        height: 36px;
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
</style>

<div class="modal-dialog">
    <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header modal-primary">

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
