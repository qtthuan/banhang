<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>

    var pb = <?= json_encode($pb); ?>;
    function paid_by(x) {
        return (x != null) ? (pb[x] ? pb[x] : x) : x;
    }

    function paidBy(x, row) {
        let badge = '';
        let sale_id = row[0];

        if (x === 'cash') {
            badge = '<span>💵 Tiền mặt</span>';
        } else if (x === 'CC') {
            badge = '<span >💳 Chuyển khoản</span>';
        }

        return `
            <div class="no-receipt-click">
                ${badge}
                <a href="#"
                class="edit-paidby"
                data-id="${sale_id}"
                data-value="${x}"
                style="margin-left:5px">
                    <i class="fa fa-pencil"></i>
                </a>
            </div>
        `;
    }





    $(document).ready(function () {
        oTable = $('#POSData').dataTable({
            "aaSorting": [[1, "desc"], [2, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
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
                {"mRender": delivery_method}, // 4 Hình thức nhận 
                {"mRender": function (data, type, row) { return paidBy(data, row); } }, // 5 Thanh toán bằng
                {"mRender": currencyFormat}, // 6 Tổng tiền
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
                nCells[6].innerHTML = currencyFormat(gtotal);
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[năm-tháng-ngày]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text"},
            {column_number: 4, filter_default_label: "[<?=lang('delivery_method');?>]", filter_type: "text"},
            {
                column_number: 5,
                filter_type: "select",
                filter_default_label: "[Tất cả]",
                data: [
                    { value: "cash", label: "Tiền mặt" },
                    { value: "cc", label: "Chuyển khoản" }
                ]
            },
            {column_number: 7, filter_default_label: "[<?=lang('warehouse');?>]", filter_type: "text"},

        ], "footer");

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


    $(document).on('click', '.edit-paidby', function (e) {
        e.preventDefault();

        let sale_id = $(this).data('id');
        let current = $(this).data('value');

        let html = `
            <select id="new_paid_by" class="form-control">
                <option value="cash" ${current === 'cash' ? 'selected' : ''}>Tiền mặt</option>
                <option value="CC" ${current === 'CC' ? 'selected' : ''}>Chuyển khoản</option>
            </select>
        `;

        bootbox.confirm({
            title: "Phương thức thanh toán",
            message: html,
            callback: function (result) {
                if (!result) return;

                let newVal = $('#new_paid_by').val();

                $.ajax({
                    type: "POST",
                    url: "<?= admin_url('pos/updatePaidBy') ?>",
                    data: {
                        sale_id: sale_id,
                        paid_by: newVal,
                        <?= $this->security->get_csrf_token_name(); ?>:
                        "<?= $this->security->get_csrf_hash(); ?>"
                    },
                    success: function () {
                        oTable.fnDraw(false);
                    }
                });
            }
        });
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
                            <th><?= lang("delivery_method"); ?></th>
                            <th><?= lang("paid_by"); ?></th> 
                            <th><?= lang("grand_total"); ?></th>
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
