<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?= css('tnh.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= css('timeline.css') ?>">
<?php echo form_open(); ?>
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo $this->load->view('admin/alert') ?>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table id="table-productions-orders-details" class="table dt-tnh table-hover table-bordered table-condensed table-productions-orders dont-responsive-table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><?= lang('id') ?></th>
                                        <th><?= lang('tnh_numbers') ?></th>
                                        <th><?= lang('tnh_reference_productions_orders') ?></th>
                                        <th><?= lang('tnh_reference_productions_orders_details') ?></th>
                                        <th><?= lang('tnh_deadline') ?></th>
                                        <th><?= lang('departments') ?></th>
                                        <th><?= lang('tnh_product_code') ?></th>
                                        <th><?= lang('tnh_product_name') ?></th>
                                        <th><?= lang('quantity') ?></th>
                                        <th><?= lang('tnh_quantity_finished') ?></th>
                                        <th><?= lang('tnh_precent_finished') ?></th>
                                        <th><?= lang('tnh_status') ?></th>
                                        <th><?= lang('actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="99"></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
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
<script type="text/javascript" src="<?= js('datatables/colReorderWithResize.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/bootbox/bootbox.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/bootbox/bootbox.locales.min.js') ?>"></script>
<script type="text/javascript">
	var site = <?= json_encode(array('base_url' => base_url())) ?>;
	var token = '<?php echo $this->security->get_csrf_token_name(); ?>';
	var hash = '<?php echo $this->security->get_csrf_hash(); ?>';
	var fnserverparams = {status_table: '#status_table'};
	var oTable = '';
    var arr = [];
</script>
<script type="text/javascript">

	$(document).ready(function() {
		oTable = tnhDatatable(
            '#table-productions-orders-details',
            {
                'order': [[0, 'desc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "processing": true,
                scrollY: height_body,
                scrollX: true,
                // fixedColumns: {
                //     leftColumns: 4,
                //     rightColumns: 0
                // },
                // stateSave: true,
                autoWidth: true,
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/manufactures/getProductionsOrdersDetails') ?>',
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
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    var total_quantity = 0;
                    var total_quantity_finished = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        total_quantity+= intVal(aaData[i][8]);
                        total_quantity_finished+= intVal(aaData[i][9]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[7].innerHTML = '<div class="text-center bold">'+tnhFormatNumber(total_quantity)+'</div>';
                    nCells[8].innerHTML = '<div class="text-center bold">'+tnhFormatNumber(total_quantity_finished)+'</div>';
                },
                "columnDefs": [
                    {"targets": 0, "name": 'id', 'visible': false},
                    {
                        "render": function(data, type, row) {
                            return '<input type="hidden" name="records" id="records" class="form-control" value="'+row[0]+'">'+
                                '<div style="padding-left: 0px;">'+data+'</div>';
                        },
                        "targets": 1, "name": 'number_records', 'width': '45px', 'className': 'text-center'
                    },
                    {"targets": 2, "name": 'reference_no_order'},
                    {"targets": 3, "name": 'reference_no'},
                    {
                        "render": function(data, type, row) {
                            return '<div class="">'+fsd(data)+'</div>';
                        },
                        "targets": 4, "name": 'deadline'
                    },
                    {"targets": 5, "name": 'department_name'},
                    {"targets": 6, "name": 'items_code'},
                    {"targets": 7, "name": 'items_name'},
                    {
                        "render": function(data, type, row) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        },
                        "targets": 8, "name": 'quantity'
                    },
                    {
                        "render": function(data, type, row) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        },
                        "targets": 9, "name": 'quantity_finished'
                    },
                    {
                        "render": function(data, type, row) {
                            return '<div class="progress" style="margin-bottom: 0px;">'+
                                '<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%"><div style="color: black;">0%</div>'+
                                '</div>'+
                            '</div>';
                        },
                        "targets": 10, "name": 'precent_finished'
                    },
                    {
                        "render": function(data, type, row) {
                            str = '';
                            if (data == "un_produced")
                            {
                                str = '<span class="label label-warning"><?= lang('tnh_un_produced') ?><span>';
                            }
                            return str;
                        },
                        "targets": 11, "name": 'status'
                    },
                    {"targets": 12, "name": 'actions', 'searchable': false, 'sortable': false, 'width': '80px'},
                ]
            }
        );

        $(document).on('click', '#table-productions-orders-details_wrapper .btn-dt-reload', function(event) {
            oTable.draw('page');
        });

        $('#table-productions-orders-details_wrapper').on('draw.dt', function(e, settings) {
        })

        $('#table-productions-orders-details_wrapper tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var records = tr.find('#records').val();
            var row = oTable.row( tr );

            if ( row.child.isShown() ) {
                arr = removeArray(arr, records);
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if (!arr.includes(records)) {
                    arr.push(records);
                }
                // row.child( formatProductionsOrdersDetail(row.data()) ).show();
                row.child( '123' ).show();
                tr.addClass('shown');
            }
        } );

        // $(document).on('click', '.status-table li a', function(event) {
        //     status_table = $(this).attr('value');
        //     $('#status_table').val(status_table);
        //     oTable.draw();
        // });
	});
</script>
<script type="text/javascript" src="<?= js('modal.js?vs=1.1') ?>"></script>
