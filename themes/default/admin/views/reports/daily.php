<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .table th {
        text-align: center;
    }

    .table td {
        padding: 2px;
    }

    .table td .table td:nth-child(odd) {
        text-align: left;
    }

    .table td .table td:nth-child(even) {
        text-align: right;
    }

    .table a:hover {
        text-decoration: none;
    }

    .cl_wday {
        text-align: center;
        font-weight: bold;
    }

    .cl_equal {
        width: 14%;
    }

    td.day {
        width: 14%;
        padding: 0 !important;
        vertical-align: top !important;
    }

    .day_num {
        width: 100%;
        text-align: left;
        cursor: pointer;
        margin: 0;
        padding: 8px;
    }

    .day_num:hover {
        background: #F5F5F5;
    }

    .content {
        width: 100%;
        text-align: left;
        color: #428bca;
        padding: 8px;
    }

    .highlight {
        color: #0088CC;
        font-weight: bold;
    }
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-calendar"></i><?= lang('daily_sales').' ('.(isset($sel_warehouse) ? $sel_warehouse->name : lang('all_warehouses')).')'; ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <?php if (!empty($warehouses) && !$this->session->userdata('warehouse_id')) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?=admin_url('reports/daily_sales/0/'.$year.'/'.$month)?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
                            <li class="divider"></li>
                            <?php
                                foreach ($warehouses as $warehouse) {
                                        echo '<li><a href="' . admin_url('reports/daily_sales/'.$warehouse->id.'/'.$year.'/'.$month) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                                    }
                                ?>
                        </ul>
                    </li>
                <?php } ?>
                <li class="dropdown">
                    <a href="#" id="image" class="tip" title="<?= lang('save_image') ?>">
                        <i class="icon fa fa-file-picture-o"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a id="export_excel_btn" class="tip" title="<?= lang('export_to_excel') ?>">
                        <i class="icon fa fa-file-excel-o"></i>
                    </a>
                </li>
                <div id="export_excel_popup" class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title">Xuất Excel</h4>
                        </div>

                        <div class="modal-body">

                            <label>Kho hàng</label>
                            <select id="excel_warehouse" class="form-control">
                                <option value="all">Tất cả kho hàng</option>
                                <option value="bani">Kho Ba-Ni</option>
                                <option value="mini">Tiệm Nước Mini</option>
                            </select>

                            <br>

                            <label>Kỳ báo cáo</label>
                            <select id="excel_period" class="form-control">
                                <option value="1">1 tháng</option>
                                <option value="3">3 tháng</option>
                                <option value="6">6 tháng</option>
                                <option value="12">12 tháng</option>
                            </select>

                        </div>

                        <div class="modal-footer">
                            <button id="export_excel_confirm" class="btn btn-primary">
                                Xuất Excel
                            </button>
                            <button class="btn btn-default" data-dismiss="modal">Đóng</button>
                        </div>

                        </div>
                    </div>
                    </div>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('get_day_profit').' '.lang("reports_calendar_text") ?></p>

                <div>
                    <?php echo $calender; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {


        $('#export_excel_btn').on('click', function () {
            $('#export_excel_popup').modal('show');
        });

        $('#export_excel_confirm').on('click', function () {

            let warehouse = $('#excel_warehouse').val();
            let period    = $('#excel_period').val();

            // Redirect sang controller xuất excel
            window.location.href = "<?= admin_url('reports/xuat_s2a_excel/') ?>" 
                + warehouse + "/" + period;

            $('#export_excel_popup').modal('hide');
        });

        $('.table .day_num').click(function () {
            var day = $(this).html();
            var date = '<?= $year.'-'.$month.'-'; ?>'+day;
            var href = '<?= admin_url('reports/profit'); ?>/'+date+'/<?= ($warehouse_id ? $warehouse_id : ''); ?>';
            $.get(href, function( data ) {
                $("#myModal").html(data).modal();
            });

        });
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=admin_url('reports/daily_sales/'.($warehouse_id ? $warehouse_id : 0).'/'.$year.'/'.$month.'/pdf')?>";
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>
