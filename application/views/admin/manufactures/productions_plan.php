<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style class="">
    table tr td {
        vertical-align: middle !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= css('tnh.css') ?>">
<?php echo form_open(); ?>
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a href="<?= base_url('admin/manufactures/add_productions_plan') ?>" class="btn btn-info pull-right H_action_button">
                <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                <?php echo _l('add'); ?>
            </a>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo $this->load->view('admin/alert') ?>
                        <div class="clearfix"></div>
                        <div role="tabpanel">
                            <ul class="nav nav-tabs status-table" role="tablist">
                                <?php foreach (status_productions_plan() as $key => $value): ?>
                                    <li role="presentation">
                                        <a href="#<?= $key ?>" aria-controls="plan" role="<?= $key ?>" value="<?= $key ?>" data-toggle="tab"><?= $value ?></a>
                                    </li>
                                <?php endforeach ?>
                                <li role="presentation" class="active">
                                    <a href="#all" aria-controls="all" role="tab" value="" data-toggle="tab"><?= lang('all') ?></a>
                                </li>
                            </ul>
                            <input type="hidden" name="status_table" id="status_table" class="form-control status_table" value="">
                        </div>
                        <div class="table-responsive">
                            <table id="table-productions-plan" class="table dt-tnh table-hover table-bordered table-condensed table-productions-plan" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><?= lang('id') ?></th>
                                        <th><?= lang('tnh_numbers') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('tnh_reference_productions_plan') ?></th>
                                        <th><?= lang('tnh_planning_cycle') ?></th>
                                        <th><?= lang('tnh_applied_standard') ?></th>
                                        <th><?= lang('tnh_options') ?></th>
                                        <th><?= lang('note') ?></th>
                                        <th><?= lang('tnh_created_by') ?></th>
                                        <th><?= lang('tnh_status') ?></th>
                                        <th><?= lang('tnh_user_agree') ?></th>
                                        <th><?= lang('tnh_reference_productions_orders') ?></th>
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
<?php echo form_close(); ?>
<?php init_tail(); ?>

<?php $this->load->view('loader')?>
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/bootbox/bootbox.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/bootbox/bootbox.locales.min.js') ?>"></script>
<script type="text/javascript">
	var site = <?= json_encode(array('base_url' => base_url())) ?>;
	var token = '<?php echo $this->security->get_csrf_token_name(); ?>';
	var hash = '<?php echo $this->security->get_csrf_hash(); ?>';
	var fnserverparams = {status_table: '#status_table'};
	var oTable = '';
</script>
<script type="text/javascript">
	$(document).ready(function() {
		oTable = tnhDatatable(
            '#table-productions-plan',
            {
                'order': [[2, 'desc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "processing": true,
                scrollY: height_body,
                scrollX: true,
                // stateSave: true,
                autoWidth: true,
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/manufactures/getProductionsPlan') ?>',
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
                "drawCallback": function(settings, nRow) {
                },
                'fnRowCallback': function (nRow, aData, iDisplayIndex) {

                },
                "initComplete": function(settings, json) {
                    var t = this;
                    t.parents('.table-loading').removeClass('table-loading');
                    t.removeClass('dt-table-loading');
                    mainWrapperHeightFix();
                },
                "footerCallback": function( tfoot, data, start, end, display ) {
                },
                "columnDefs": [
                    {"targets": 0, "name": 'id', 'visible': false},
                    {"targets": 1, "name": 'number_records'},
                    {
                        "render": function(data, type, row) {
                            return fld(data);
                        },
                        "targets": 2, "name": 'date', 'searchable': false
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a class="tnh-modal" data-tnh="modal" href="'+site.base_url+'admin/manufactures/view_productions_plan/'+row[0]+'" data-toggle="modal" data-target="#myModal">'+data+'</a>';
                        },
                        "targets": 3, "name": 'reference_no'
                    },
                    {"targets": 4, "name": 'planning_cycle'},
                    {
                        "render": function(data, type, row) {
                            str = '';
                            if (data == 1) {
                                str = '<span class="label label-success"><?= lang('tnh_safe_inventory') ?></span>';
                            } else {
                                str = '<span class="label label-danger"><?= lang('tnh_not') ?></span>';
                            }
                            return str;
                        },
                        "targets": 5, "name": 'safe_inventory'
                    },
                    {
                        "render": function(data, type, row) {
                            str = '';
                            if (data) {
                                data = data.split('-');
                                if (data[0] == 1)
                                {
                                    str+= '<span class="label label-primary"><?= lang('tnh_sales_orders') ?></span>';
                                }
                                if (data[0] == 1 && data[1] == 1) {
                                    str+= '</br></br>';
                                }
                                if (data[1] == 1)
                                {
                                    str+= '<span class="label label-warning"><?= lang('tnh_business_plan') ?></span>';
                                }
                            }
                            return str;
                        },
                        "targets": 6, "name": 'options'
                    },
                    {"targets": 7, "name": 'note'},
                    {"targets": 8, "name": 'created_by'},
                    {
                        "render": function(data, type, row) {
                            str = '';

                            productions_plan_id = row[0];
                            if (data == "approved" || data == "capacity") {
                                user_status = '<div class="mtop10"><?= lang('tnh_user_agree') ?>: '+row[10]+'</div>';
                            } else {
                                user_status = '';
                            }
                            if (data == "un_approved") {
                                str = '<div class="text-left"><span data-html="true" data-toggle="popover" data-container="body" data-placement="left" style="cursor: pointer;" title="<?= lang('tnh_status') ?>" data-content="<p><a id=\'agree\' productions_plan_id=\''+productions_plan_id+'\' value=\'approved\' class=\'btn btn-success\'><?= lang('tnh_agree') ?></a><button class=\'btn po-close\'><?= lang('close') ?></button></p>" class="label label-danger po"><?= lang('tnh_un_approved') ?></span></div>'+user_status;
                            } else if (data == "approved") {
                                str = '<div class="text-left"><span data-html="true" data-toggle="popover" data-container="body" data-placement="left" style="cursor: pointer;" title="<?= lang('tnh_status') ?>\" data-content="<p><a id=\'agree\' productions_plan_id=\''+productions_plan_id+'\' value=\'un_approved\' class=\'btn btn-danger\'><?= lang('tnh_un_agree') ?></a><button class=\'btn po-close\'><?= lang('close') ?></button></p>" class="label label-success po"><?= lang('tnh_approved') ?></span></div>'+user_status;
                            } else if (data == "capacity") {
                                str = '<div class="text-left"><span class="label label-primary"><?= lang('tnh_capacity') ?></span></div>'+user_status;
                            } else {
                                str = '';
                            }

                            reference_orders = row[11];
                            if (reference_orders) {
                                str+= '<div class=""><span class="label label-primary">Đã tạo LSX</span></div>';
                            }
                            return str;
                        },
                        "targets": 9, "name": 'status'
                    },
                    {"targets": 10, "name": 'user_status', 'visible': false},
                    {"targets": 11, "name": 'reference_orders', 'visible': false},
                    {"targets": 12, "name": 'actions', 'searchable': false, 'sortable': false, 'width': '50px'}
                ]
            }
        );

        $(document).on('click', '#table-productions-plan_wrapper .btn-dt-reload', function(event) {
            oTable.draw('page');
        });

        $(document).on('click', '#table-view-plan_wrapper .btn-dt-reload', function(event) {
            oTableProductionsPlan.draw('page');
        });

        $('#table-productions-plan').on('draw.dt', function(e, settings) {
        })

        $(document).on('click', '.export-excel', function(event) {
            event.preventDefault();
            productions_plan_id = $(this).attr('value');
            bootbox.confirm({
                message: '<?= lang('tnh_you_want_to_export_excel') ?>',
                buttons: {
                    confirm: {
                        label: '<?= lang('yes') ?>',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: '<?= lang('no') ?>',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        if (productions_plan_id) {
                            $.ajax({
                                url: site.base_url+'admin/manufactures/export_excel_production_plan',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    productions_plan_id: productions_plan_id,
                                    export_excel: 1,
                                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                                },
                            })
                            .done(function(data) {
                                if (data.result) {
                                    alert_float('success', data.message);
                                    download(data.filename, data.file);
                                } else {
                                    alert_float('danger', data.message);
                                }
                            })
                            .fail(function() {
                                alert_float('danger', 'errors');
                            });
                        }
                    }
                }
            });
        });

        $(document).on('click', '#agree', function(event) {
            event.preventDefault();
            index = this;
            productions_plan_id = $(this).attr('productions_plan_id');
            status = $(this).attr('value');
            $(index).attr('disabled', 'disabled');
            $('.po').popover('hide');
            if (productions_plan_id) {
                $.ajax({
                    url: site.base_url+'admin/manufactures/agreeProductionsPlan',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
                        productions_plan_id: productions_plan_id,
                        status: status
                    },
                })
                .done(function(data) {
                    if (data.result) {
                        alert_float('success', data.message);
                        oTable.draw('page');
                    } else {
                        alert_float('danger', data.message);
                        oTable.draw('page');
                    }
                })
                .fail(function(data) {
                    alert_float('danger', 'errors');
                    $(index).removeAttr('disabled');
                })
            }
        });

        $(document).on('click', '.status-table li a', function(event) {
            status_table = $(this).attr('value');
            $('#status_table').val(status_table);
            oTable.draw();
        });
	});
</script>
<script type="text/javascript" src="<?= js('modal.js?vs=1.1') ?>"></script>
