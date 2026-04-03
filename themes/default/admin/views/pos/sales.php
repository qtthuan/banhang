<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>

    var pb = <?= json_encode($pb); ?>;
    function paid_by(x) {
        return (x != null) ? (pb[x] ? pb[x] : x) : x;
    }
    

    $(document).ready(function () {
        oTable = $('#POSData').dataTable({
            "aaSorting": [[1, "desc"], [2, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= admin_url('pos/getSales'.($warehouse_id ? '/'.$warehouse_id : '')) ?>',

            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "receipt_link";
                return nRow;
            },
            "aoColumns": [
                {"bSortable": false,"mRender": checkbox}, // 0 checkbox
                {"mRender": fld}, // 1 date
                null, // 2 Mã
                null, // 3 Tên KH
                
                {"mRender": function (data, type, row) { return paidBy(data, row); } }, // 5 Thanh toán bằng
                {"mRender": currencyFormat}, // 6 Tổng tiền
                {
                    "mRender": function (data, type, row) {
                        let value = data ? parseFloat(data) : 0;
                        let formatted = value.toLocaleString('vi-VN') + 'đ';

                        return '<span class="actual_shipping" data-id="' + row[0] + '" data-value="' + value + '">' + formatted + '</span>';
                    }
                },
                null, // 7 Kho
                {"bSortable": false} // 8 Tác vụ
            ],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0;

                for (var i = 0; i < aiDisplay.length; i++) {
                    gtotal += parseFloat(aaData[aiDisplay[i]][6]) || 0;
                }

                var nCells = nRow.getElementsByTagName('th');

                // Cột tổng tiền (index 6)
                //nCells[6].innerHTML = currencyFormat(gtotal);
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[năm-tháng-ngày]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text"},
            
            {
                column_number: 4,
                filter_type: "select",
                filter_default_label: "[Tất cả]",
                data: [
                    { value: "cash", label: "Tiền mặt" },
                    { value: "cc", label: "Chuyển khoản" },
                    { value: "cod", label: "COD" }
                ]
            },
            {column_number: 5, filter_default_label: "[<?=lang('total');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('actual_shipping');?>]", filter_type: "text"},
            {
                column_number: 7,
                filter_type: "select",
                filter_default_label: "[<?= lang('warehouse'); ?>]",
                data: [
                    <?php foreach ($warehouses as $w): ?>
                    {
                        value: "<?= $w->name ?>",
                        label: "<?= addslashes($w->name) ?>"
                    },
                    <?php endforeach; ?>
                ]
            },


        ], "footer");

        // chặn click
        $(document).on('click', '.actual_shipping, .actual_shipping *', function (e) {
            e.stopPropagation();
        });

        // dblclick edit
        $(document).on('dblclick', '.actual_shipping', function (e) {
            e.stopPropagation();

            var id = $(this).data('id');
            var value = $(this).data('value'); // 🔥 lấy giá trị gốc

            $(this).replaceWith(`
                <span class="actual_shipping_edit" data-id="${id}">
                    <input type="text" class="ship_input" value="${value}" style="width:80px;">
                    <button type="button" class="btn_save_ship">✔</button>
                </span>
            `);
            // 🔥 focus + select toàn bộ
            var input = $('.actual_shipping_edit[data-id="' + id + '"]').find('.ship_input');
            input.focus().select();
        });
        // $(document).on('dblclick', '.actual_shipping', function (e) {
        //     e.stopPropagation();
        //     e.preventDefault();
        //     var span = $(this);
        //     var id = span.data('id');
        //     var value = span.text().trim();

        //     var html = `
        //         <div class="ship_edit_box">
        //             <input type="number" class="form-control input-sm ship_input" value="${value}" style="width:80px; display:inline-block;">
        //             <button type="button" class="btn_save_ship">✔</button>
        //         </div>
        //     `;

        //     span.replaceWith('<span class="actual_shipping_edit" data-id="' + id + '">' + html + '</span>');
        // });

        $(document).on('click', '.btn_save_ship', function () {
            var box = $(this).closest('.actual_shipping_edit');
            var id = box.data('id');
            var value = box.find('.ship_input').val();

            if (value === '') {
                alert('Nhập phí ship');
                return;
            }

            updateShipping(id, value, box); // 🔥 truyền box vào
        });

       $(document).on('keypress', '.ship_input', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                e.stopPropagation();

                $(this).closest('.actual_shipping_edit').find('.btn_save_ship').click();
            }
        });

        function updateShipping(id, value, box) {
            $.ajax({
                url: '<?= admin_url("sales/update_actual_shipping") ?>',
                type: 'POST',
                data: {
                    id: id,
                    actual_shipping: value,
                    <?= json_encode($this->security->get_csrf_token_name()) ?>: '<?= $this->security->get_csrf_hash() ?>'
                },
                success: function (res) {

                    // 👉 format lại nếu cần
                    var display = parseFloat(value).toLocaleString('vi-VN') + 'đ';

                    // 👉 replace lại span
                    // box.replaceWith(
                    //     '<span class="actual_shipping" data-id="' + id + '">' + display + '</span>'
                    // );
                    box.replaceWith(
                        '<span class="actual_shipping" data-id="' + id + '" data-value="' + value + '">' + formatMoney(value) + '</span>'
                    );
                },
                error: function () {
                    alert('Cập nhật thất bại');
                }
            });
        }

        // $(document).on('dblclick', '.actual_shipping', function () {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     var span = $(this);
        //     var id = span.data('id');
        //     var value = span.text().trim();

        //     var html = `
        //         <div class="ship_edit_box">
        //             <input type="number" class="form-control input-sm ship_input" value="${value}" style="width:80px; display:inline-block;">
        //             <button class="btn btn-xs btn-primary btn_save_ship">✔</button>
        //         </div>
        //     `;

        //     span.replaceWith('<span class="actual_shipping_edit" data-id="' + id + '">' + html + '</span>');
        // });
       
        $(document).on('click', '.duplicate_pos', function (e) {
            e.preventDefault();
            var link = $(this).attr('href');
            if (localStorage.getItem('positems')) {
                bootbox.confirm("<?= $this->lang->line('leave_alert') ?>", function (gotit) {
                    if (gotit == false) {
                        return true;
                    } else {
                        window.location.href = link;
                    }
                });
            } else {
                window.location.href = link;
            }
        });

        $(document).on('click', '.email_receipt', function () {
            var sid = $(this).attr('data-id');
            var ea = $(this).attr('data-email-address');
            var email = prompt("<?= lang("email_address"); ?>", ea);
            if (email != null) {
                $.ajax({
                    type: "post",
                    url: "<?= admin_url('pos/email_receipt') ?>/" + sid,
                    data: { <?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email, id: sid },
                    dataType: "json",
                        success: function(data) {
                        bootbox.alert(data.msg);
                    },
                    error: function() {
                        bootbox.alert('<?= lang('ajax_request_failed'); ?>');
                        return false;
                    }
                });
            }
        });
    });

    function paidBy(x, row) {
        let sale_id = row[0];
        let label = '';
        let icon  = '';

        if (x === 'cash') {
            label = 'Tiền mặt';
            icon  = '💵';
        } else if (x === 'cc') {
            label = 'Chuyển khoản';
            icon  = '💳';
        } else if (x === 'cod') {
            label = 'COD';
            icon  = '📦';
        } else {
            x = 'wait';
            label = 'Chưa TT';
            icon  = '⏳';
        } 

        return `
            <span class="edit-paidby badge-paidby ${x === 'wait' ? 'badge-wait' : ''}"
                data-id="${sale_id}"
                data-value="${x}"
                title="Thay đổi phương thức thanh toán">
                ${icon} ${label}
            </span>
        `;
    }

    $(document).on('click', '.edit-paidby', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $('.paidby-pop').remove();

        let $el = $(this);
        let sale_id = $el.data('id');
        let current = $el.data('value'); // cash | cc | cod
        let offset  = $el.offset();

        let pop = `
            <div class="paidby-pop" data-id="${sale_id}">
                <div class="paidby-btn-group">
                    <button type="button"
                        class="paidby-btn ${current === 'cash' ? 'active' : ''}"
                        data-value="cash">💵 Tiền mặt</button>

                    <button type="button"
                        class="paidby-btn ${current === 'cc' ? 'active' : ''}"
                        data-value="cc">💳 Chuyển khoản</button>

                    <button type="button"
                        class="paidby-btn ${current === 'cod' ? 'active' : ''}"
                        data-value="cod">📦 COD</button>
                </div>
            </div>
        `;

        $('body').append(pop);

        let popWidth = $('.paidby-pop').outerWidth();
        let elWidth  = $el.outerWidth();

        $('.paidby-pop').css({
            top: offset.top + $el.outerHeight() + 6, // nằm dưới
            left: offset.left + (elWidth / 2) - (popWidth / 2) // canh giữa
        });


        // $('.paidby-pop').css({
        //     top: offset.top - 6,
        //     left: offset.left + $el.outerWidth() + 8
        // });
    });

    $(document).on('click', '.paidby-btn', function (e) {
        e.stopPropagation();

        let $btn  = $(this);
        let $pop  = $btn.closest('.paidby-pop');
        let sale_id = $pop.data('id');
        let paid_by = $btn.data('value');
        let current = $('.edit-paidby[data-id="'+sale_id+'"]').data('value');
        let url = '';

        if (current === 'wait' && paid_by !== 'wait') {
            url = '<?= admin_url('pos/addPaymentFromWait') ?>';
        } else {
            url = '<?= admin_url('pos/updatePaidBy') ?>';
        }

        // UI: active button
        $btn.addClass('active')
            .siblings().removeClass('active');

        $.ajax({
            type: 'POST',
            url: url,
            data: {
                sale_id: sale_id,
                paid_by: paid_by,
                <?= $this->security->get_csrf_token_name(); ?>:
                "<?= $this->security->get_csrf_hash(); ?>"
            },
            success: function () {

                let icon  = '💵';
                let label = 'Tiền mặt';

                if (paid_by === 'cc') {
                    icon = '💳'; label = 'Chuyển khoản';
                } else if (paid_by === 'cod') {
                    icon = '📦'; label = 'COD';
                }

                let $badge = $('.edit-paidby[data-id="' + sale_id + '"]');
                $badge.data('value', paid_by);

                $badge.fadeOut(120, function () {
                    $badge.html(`${icon} ${label}`).fadeIn(120);
                });

                $pop.fadeOut(250, function () {
                    $(this).remove();
                });
            }
        });
    });

    $(document).on('click', function () {
        $('.paidby-pop').remove();
    });

    $(document).on('click', '.paidby-pop', function (e) {
        e.stopPropagation();
    });

</script>

<?php if ($Owner || $GP['bulk_actions']) {
    echo admin_form_open('sales/sale_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-barcode"></i><?= lang('pos_sales') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"  data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= admin_url('pos') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_sale') ?></a></li>
                        <li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="bpo" title="<b><?= $this->lang->line("delete_sales") ?></b>" data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_sales') ?></a></li>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= admin_url('pos/sales') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                                echo '<li><a href="' . admin_url('pos/sales/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="POSData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?= lang("date"); ?></th>
                            <th><?= lang("reference_no"); ?></th>
                            <th><?= lang("customer"); ?></th>
                            
                            <th><?= lang("paid_by"); ?></th> 
                            <th><?= lang("grand_total"); ?></th>
                            <th><?= lang("actual_shipping"); ?></th>
                            <th><?= lang("warehouse"); ?></th>     
                            <th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="10" class="dataTables_empty"><?= lang("loading_data"); ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>                            
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($Owner || $GP['bulk_actions']) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
<script>
    $(document).ready(function () {
        $('#PRData_wrapper :input[type="text"]').first().focus();
    });
</script>
