<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style class="">
    table tr td {
        vertical-align: middle !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= css('tnh.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= css('timeline.css') ?>">
<?php echo form_open(); ?>
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a href="<?= base_url('admin/manufactures/add_productions_orders') ?>" class="btn btn-info pull-right H_action_button">
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
                        <div class="table-responsive">
                            <table id="table-productions-orders" class="table dt-tnh table-hover table-bordered table-condensed table-productions-orders dont-responsive-table dataTable" style="">
                                <thead>
                                    <tr>
                                        <th><?= lang('id') ?></th>
                                        <th><?= lang('tnh_numbers') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('tnh_reference_productions_orders') ?></th>
                                        <th><?= lang('tnh_location') ?></th>
                                        <th><?= lang('tnh_reference_productions_plan') ?></th>
                                        <th><?= lang('tnh_items') ?></th>
                                        <th><?= lang('tnh_total_quantity') ?></th>
                                        <th><?= lang('note') ?></th>
                                        <th><?= lang('tnh_created_by') ?></th>
                                        <th><?= lang('tnh_status') ?></th>
                                        <th><?= lang('tnh_user_agree') ?></th>
                                        <th><?= lang('tnh_status_productions') ?></th>
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
</script>
<script type="text/javascript">
	$(document).ready(function() {
		oTable = tnhDatatable(
            '#table-productions-orders',
            {
                'order': [[2, 'desc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "processing": true,
                scrollY: height_body,
                scrollX: true,
                fixedColumns: {
                    leftColumns: 4,
                    rightColumns: 0
                },
                // stateSave: true,
                // autoWidth: true,
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/manufactures/getProductionsOrders') ?>',
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
                    processingProduction = aData[12];
                    if (processingProduction) {
                        processingProduction = processingProduction.split('||');
                        if (processingProduction[0] == 1) {
                            $(nRow).find('.created-detail').css('display', 'none');
                            $(nRow).find('.delete-detail').css('display', 'block');
                        } else {
                            $(nRow).find('.created-detail').css('display', 'block');
                            $(nRow).find('.delete-detail').css('display', 'none');
                        }
                    }
                },
                "initComplete": function(settings, json) {
                    var t = this;
                    t.parents('.table-loading').removeClass('table-loading');
                    t.removeClass('dt-table-loading');
                    mainWrapperHeightFix();
                },
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    var total_quantity = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        total_quantity+= intVal(aaData[i][7]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[6].innerHTML = '<div class="text-center bold">'+tnhFormatNumber(total_quantity)+'</div>';
                },
                "columnDefs": [
                    {"targets": 0, "name": 'id', 'visible': false},
                    {"targets": 1, "name": 'number_records', 'width': '45px', 'className': 'text-center'},
                    {
                        "render": function(data, type, row) {
                            return fld(data);
                        },
                        "targets": 2, "name": 'date', 'width': '150px', 'searchable': false
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a class="tnh-modal" data-tnh="modal" href="'+site.base_url+'admin/manufactures/view_productions_orders/'+row[0]+'" data-toggle="modal" data-target="#myModal">'+data+'</a>';
                        },
                        "targets": 3, "name": 'reference_no', 'width': '150px'
                    },
                    {"targets": 4, "name": 'location_name', 'width': '150px'},
                    {
                        "render": function(data, type, row) {
                            str = '';
                            if (data) {
                                str = data.replace(/,/g, '</br>');
                            }
                            return str;
                        },
                        "targets": 5, "name": 'productions_plan_reference_no', 'width': '150px'
                    },
                    {
                        "render": function(data, type, row) {
                            str = '';
                            if (data) {
                                str+= '<table class="tnh-table table-bordered" style="width: 100%;">'+
                                '<tbody>';
                                data = data.split(':::');
                                $.each(data, function(index, el) {
                                    if (index > 3) {
                                        str+= '<tr>'+
                                            '<td colspan="3" style="padding: 5px !important;">'+
                                                '<a class="tnh-modal" data-tnh="modal" href="'+site.base_url+'admin/manufactures/view_productions_orders/'+row[0]+'" data-toggle="modal" data-target="#myModal"><?= lang('more') ?></a>'+
                                            '</td>'+
                                        '</tr>';
                                        return str;
                                    }
                                    el = el.split('___');
                                    images = site.base_url+'assets/images/tnh/no_image.png';
                                    if (el[0]) {
                                        images = site.base_url+'uploads/products/'+el[0];
                                    }
                                    str+= '<tr>'+
                                        '<td style="width: 5%; padding: 5px !important;">'+
                                            '<div class="td-image">'+
                                                '<div class="preview_image" style="width: auto;">'+
                                                    '<div class="display-block contract-attachment-wrapper img">'+
                                                        '<div style="width:20px;">'+
                                                            '<a href="'+images+'" data-lightbox="customer-profile" class="display-block mbot5">'+
                                                                '<div class="">'+
                                                                    '<img src="'+images+'" style="border-radius: 50%">'+
                                                                '</div>'+
                                                            '</a>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</td>'+
                                        '<td style="width: 45%; padding: 5px !important;">'+el[1]+'</td>'+
                                        '<td style="width: 45%; padding: 5px !important;">'+el[2]+'</td>'+
                                    '</tr>';

                                });
                                str+= '</tbody></table>';
                            }
                            return str;
                        },
                        "targets": 6, "name": 'items', 'width': '300px'
                    },
                    {
                        "render": function(data, type, row) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        },
                        "targets": 7, "name": 'total_quantity', 'width': '100px'
                    },
                    {"targets": 8, "name": 'note', 'width': '150px'},
                    {"targets": 9, "name": 'created_by', 'width': '150px'},
                    {
                        "render": function(data, type, row) {
                            productions_orders_id = row[0];
                            if (data == "approved") {
                                user_status = '<div class="mtop10"><?= lang('tnh_user_agree') ?>: '+row[9]+'</div>';
                            } else {
                                user_status = '';
                            }
                            if (data == "un_approved") {
                                return  '<div class="text-left"><span data-html="true" data-toggle="popover" data-container="body" data-placement="left" style="cursor: pointer;" title="<?= lang('tnh_status') ?>" data-content="<p><a id=\'agree\' productions_orders_id=\''+productions_orders_id+'\' value=\'approved\' class=\'btn btn-success\'><?= lang('tnh_agree') ?></a><button class=\'btn po-close\'><?= lang('close') ?></button></p>" class="label label-danger po"><?= lang('tnh_un_approved') ?></span></div>'+user_status;
                            } else if (data == "approved") {
                                return  '<div class="text-left"><span data-html="true" data-toggle="popover" data-container="body" data-placement="left" style="cursor: pointer;" title="<?= lang('tnh_status') ?>\" data-content="<p><a id=\'agree\' productions_orders_id=\''+productions_orders_id+'\' value=\'un_approved\' class=\'btn btn-danger\'><?= lang('tnh_un_agree') ?></a><button class=\'btn po-close\'><?= lang('close') ?></button></p>" class="label label-success po"><?= lang('tnh_approved') ?></span></div>'+user_status;
                            }
                            return '';
                        },
                        "targets": 10, "name": 'status', 'width': '150px'
                    },
                    {"targets": 11, "name": 'user_status', 'visible': false},
                    {
                        "render": function(data, type, row) {
                            processProduction = data.split('||');
                            completeProducing = processProduction[0] == 1 ? 'tnh-complete' : '';
                            var str = '<ul class="tnh-timeline" id="tnh-timeline">'+
                                '<li class="tnh-li tnh-complete" style="width: 33%;">'+
                                    '<div class="tnh-timestamp">'+
                                        '<span class="author"></span>'+
                                        '<span class="date"><span>'+
                                    '</div>'+
                                    '<div class="tnh-status">'+
                                        '<h4> <?= lang('tnh_initialization') ?> </h4>'+
                                    '</div>'+
                                '</li>'+
                                '<li class="tnh-li '+completeProducing+'" style="width: 33%;">'+
                                    '<div class="tnh-timestamp">'+
                                        '<span class="author"></span>'+
                                        '<span class="date"><span>'+
                                    '</div>'+
                                    '<div class="tnh-status">'+
                                        '<h4> <?= lang('tnh_producing') ?> </h4>'+
                                    '</div>'+
                                '</li>'+
                                '<li class="tnh-li" style="width: 33%;">'+
                                    '<div class="tnh-timestamp">'+
                                        '<span class="author"></span>'+
                                        '<span class="date"><span>'+
                                    '</div>'+
                                    '<div class="tnh-status">'+
                                        '<h4> <?= lang('tnh_complete_production') ?> </h4>'+
                                    '</div>'+
                                '</li>'+
                            '</ul>';
                            return str;
                        },
                        "targets": 12, "name": 'status_productions', 'width': '350px'
                    },
                    {"targets": 13, "name": 'actions', 'searchable': false, 'sortable': false, 'width': '100px'},
                    // {"targets": 14, "name": 'hide', 'searchable': false, 'sortable': false, 'visible': false}
                ]
            }
        );

        $(document).on('click', '#table-productions-orders_wrapper .btn-dt-reload', function(event) {
            oTable.draw('page');
        });

        // $(document).on('click', '#table-view-capacity_wrapper .btn-dt-reload', function(event) {
        //     oTableProductionsCapacity.draw('page');
        // });

        // $(document).on('click', '#view-statistical_wrapper .btn-dt-reload', function(event) {
        //     oTableProductionsCapacityStatistical.draw('page');
        // });

        $('#table-productions-orders').on('draw.dt', function(e, settings) {
        })


        $(document).on('click', '#agree', function(event) {
            event.preventDefault();
            index = this;
            productions_orders_id = $(this).attr('productions_orders_id');
            status = $(this).attr('value');
            $(index).attr('disabled', 'disabled');
            $('.po').popover('hide');
            if (productions_orders_id) {
                $.ajax({
                    url: site.base_url+'admin/manufactures/agreeProductionsOrders',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
                        productions_orders_id: productions_orders_id,
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

        // var fc = new $.fn.dataTable.FixedColumns( oTable, {
        //    leftColumns: 3
        // });

        // oTable.on("click", "tr", function(){
        //    var aData = oTable.row(fc.fnGetPosition(this)).data();
        // });
	});
</script>
<script type="text/javascript" src="<?= js('modal.js?vs=1.1') ?>"></script>
