<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
    .fixedHeader-floating {
        position: fixed !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= css('tnh.css') ?>">
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a class="btn btn-info mright5 test pull-right H_action_button">
               <?php echo _l('Export excel'); ?></a>
            <div class="line-sp"></div>
            <a href="<?= base_url('admin/categories/add_machines') ?>" class="btn btn-info pull-right H_action_button tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal">
                <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                <?php echo _l('add'); ?>
            </a>
        </div>
    </div>
    <div class="content tnh-content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="minus-height-more"></div>
                        <div class="table-responsive">
                            <table id="table-machines" class="table dt-tnh table-hover table-bordered table-condensed dataTable table-machines" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><div class="checkbox mass_select_all_wrap text-center"><input type="checkbox" id="mass_select_all" data-to-table="machines"><label for="mass_select_all"></label></div></th>
                                        <th><?= lang('tnh_machine_code') ?></th>
                                        <th><?= lang('tnh_machine_name') ?></th>
                                        <th><?= lang('tnh_product_in_month') ?></th>
                                        <th><?= lang('tnh_status') ?></th>
                                        <th><?= lang('tnh_efficiency_coefficient') ?></th>
                                        <th><?= lang('tnh_capacity_cycle') ?></th>
                                        <th><?= lang('tnh_time_cycle') ?></th>
                                        <th><?= lang('tnh_time_before_produce') ?></th>
                                        <th><?= lang('tnh_time_after_produce') ?></th>
                                        <th><?= lang('tnh_cost_hour') ?></th>
                                        <th><?= lang('tnh_specifications') ?></th>
                                        <th><?= lang('note') ?></th>
                                        <th><?= lang('actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="99"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script type="text/javascript">
    var site = <?= json_encode(array('base_url' => base_url())) ?>;
    var lang_machines = <?= json_encode(array('not_produced' => lang('tnh_not_produced'), 'producing' => lang('tnh_producing'), 'maintenance' => lang('tnh_maintenance'), 'damaged' => lang('tnh_damaged'))) ?>;
    var token = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var hash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var fnserverparams = {};
    var oTable = '';
    $(document).ready(function() {
        oTable = tnhDatatable(
            '#table-machines',
            {
                'order': [[1, 'asc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                // "processing": true,
                // 'fixedHeader': {
                //     header: true,
                //     footer: true
                // },
                scrollY: height_body,
                scrollX: true,
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/categories/getMachines') ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "<?= $this->security->get_csrf_token_name() ?>",
                        "value": "<?= $this->security->get_csrf_hash() ?>"
                    });
                    for (var key in fnserverparams) {
                        aoData.push({
                            "name": key,
                            "value": $(fnserverparams[key]).val()
                        });
                    }
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
                "initComplete": function(settings, json) {
                    var t = this;
                    var $btnReload = $('.btn-dt-reload');
                    $btnReload.attr('data-toggle', 'tooltip');
                    $btnReload.attr('title', app.lang.dt_button_reload);

                    t.parents('.table-loading').removeClass('table-loading');
                    t.removeClass('dt-table-loading');
                    mainWrapperHeightFix();
                },
                "columnDefs": [
                    {
                        "render": function(data, type, row) {
                            return '<div class="checkbox"><input type="checkbox" id="check-item" value="'+ data +'"><label for="check-item"></label></div>';
                        },
                        "targets": 0,
                        "name": 'id',
                        'orderable': false,
                        'width': '50px'
                    },
                    {
                        "render": function(data, type, row) {
                            return '<div>'+ data +'</div>';
                        },
                        "targets": 1,
                        "name": 'code'
                    },
                    {"targets": 2, "name": 'name'},
                    {
                        "render": function(data, type, row) {
                            return formatNumberTnh(data);
                        },
                        "targets": 3, "name": 'product_in_month'
                    },
                    {
                        "render": function(data, type, row) {
                            if (data == "not_produced") {
                                return '<span class="label label-success">'+lang_machines[data]+'</span>';
                            } else if (data == "producing") {
                                return '<span class="label label-primary">'+lang_machines[data]+'</span>';
                            } else if (data == "maintenance") {
                                return '<span class="label label-warning">'+lang_machines[data]+'</span>';
                            } else if (data == "damaged") {
                                return '<span class="label label-danger">'+lang_machines[data]+'</span>';
                            } else {
                                return '';
                            }
                        },
                        "targets": 4, "name": 'status'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        },
                        "targets": 5, "name": 'efficiency_coefficient'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        },
                        "targets": 6, "name": 'capacity_cycle'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        },
                        "targets": 7, "name": 'time_cycle'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        },
                        "targets": 8, "name": 'time_before_produce'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        },
                        "targets": 9, "name": 'time_after_produce'
                    },
                    {
                        "render": function(data) {
                            return '<div class="text-right">'+tnhFormatMoney(data)+'</div>';
                        },
                        "targets": 10, "name": 'cost_hour'
                    },
                    {"targets": 11, "name": 'specifications'},
                    {"targets": 12, "name": 'note'},
                    {"targets": 13, "name": 'actions', 'orderable': false, 'searchable': false, 'width': '100px'}
                ]
            }
        );

        $('#table-machines').on('draw.dt', function(e, settings) {
            $('.tip').tooltip();
        });

        $(document).on('click', '#table-machines_wrapper .btn-dt-reload', function(event) {
            oTable.draw();
        });

        $(document).on('click', '#table-history-machines_wrapper .btn-dt-reload', function(event) {
            oTable_machine.draw();
        });
    });
</script>
<script type="text/javascript" src="<?= js('modal.js?vs=1.1') ?>"></script>

