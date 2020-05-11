<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
    .fixedHeader-floating {
        position: fixed !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= css('tnh_core.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= css('tnh.css') ?>">

<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
    <!-- <div> -->
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a class="btn btn-info mright5 test pull-right H_action_button">
               <?php echo _l('Export excel'); ?></a>
            <div class="line-sp"></div>
            <a href="<?= base_url('admin/products/add_stage') ?>" class="btn btn-info pull-right H_action_button tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal">
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
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table id="table-stage" class="table table-hover table-bordered table-condensed dataTable table-stage" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><div class="checkbox mass_select_all_wrap text-center"><input type="checkbox" id="mass_select_all" data-to-table="stage"><label for="mass_select_all"></label></div></th>
                                        <th><?= lang('') ?></th>
                                        <th><?= lang('tnh_stage_code') ?></th>
                                        <th><?= lang('tnh_stage_name') ?></th>
                                        <th><?= lang('note') ?></th>
                                        <th><?= lang('sub') ?></th>
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
<?php $this->load->view('loader')?>
<script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script type="text/javascript">
    var site = <?= json_encode(array('base_url' => base_url())) ?>;
    var token = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var hash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var fnserverparams = {};
    var oTable = '';
    var arr = [];
    function format(d) {
       tr1 = '<tr>'+
                '<td class="text-center" style="width: 4%;"><?= lang('tnh_numbers') ?></td>'+
                '<td class="text-center" style="width: 5%;"><?= lang('tnh_sequence') ?></td>'+
                '<td style="width: 20%;"><?= lang('code') ?></td>'+
                '<td style="width: 20%;"><?= lang('name') ?></td>'+
                '<td style="width: 10%;"><?= lang('departments') ?></td>'+
                '<td class="text-center" style="width: 10%;"><?= lang('tnh_number_hours') ?></td>'+
                '<td style="width: 20%;"><?= lang('note') ?></td>'+
                '<td style="width: 10%;"><?= lang('actions') ?></td>'+
            '</tr>';

        tr = '';
        if (d[5] != '' && d[5] != null && d[5] != 'null') {
            // data = d[5].split('____');
            data = d[5];
            $.each(data, function(index, el) {
                // info = el.split('||');
                info = el;

                sEdit = '<a class="tnh-modal btn btn-success btn-icon" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="<?= base_url('admin/products/edit_stage_sub') ?>/'+info['id']+'"><i class="fa fa-pencil"></i></a>';

                sDelete = '<button type="button" class="btn btn-danger po btn-icon" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="\
                    <button href=\'<?= base_url('admin/products/delete_stage/') ?>/'+info['id']+'\' class=\'btn btn-danger po-delete-json\'><?= lang('delete') ?></button>\
                    <button class=\'btn btn-default po-close\'><?= lang('close') ?></button>\
                "><i class="fa fa-remove"></i></button>';

                tr+= '<tr>'+
                    '<td class="text-center">'+ (++index) +'</td>'+
                    '<td class="text-center">'+ info['sequence'] +'</td>'+
                    '<td>'+ info['code'] +'</td>'+
                    '<td>'+ info['name'] +'</td>'+
                    '<td>'+ info['departments_name'] +'</td>'+
                    '<td class="text-center">'+ info['number_hours'] +'</td>'+
                    '<td>'+ info['note'] +'</td>'+
                    '<td>'+sEdit+''+sDelete+'</td>'+
                '</tr>';
            });
        }

        tb = '<table class="dt-table tnh-table table-bordered" style="width: 95% !important; float: right;">'+
                '<tbody>'+
                tr1+
                tr+
                '</tbody>'+
            '</table>'
        return tb;
    }

    $(document).ready(function() {
        oTable = tnhDatatable(
            '#table-stage',
            {
                'order': [[2, 'asc']],
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
                'sAjaxSource': '<?= site_url('admin/products/getStages') ?>',
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
                "columnDefs": [
                    {
                        "render": function (data, type, row) {
                            return '<div class="checkbox"><input type="checkbox" id="check-item" value="'+ data +'"><label for="check-item"></label></div>';
                        },
                        "targets": 0,
                        "name": 'id',
                        'orderable': false,
                        'width': '50px'
                    },
                    {
                        "render": function(data, type, row) {
                            return '<input type="hidden" name="stage" id="stage" class="form-control" value="'+data+'">';
                        },
                        "targets": 1, "name": 'records', "className": 'details-control', 'width': '30px'
                    },
                    {
                        "render": function(data, type, row) {
                            return '<div>'+ data +'</div>';
                        },
                        "targets": 2,
                        "name": 'code'
                    },
                    {"targets": 3, "name": 'name'},
                    {"targets": 4, "name": 'note'},
                    {"targets": 5, "name": 'sub', 'visible': false},
                    {"targets": 6, "name": 'actions', 'orderable': false, 'searchable': false, 'width': '100px'}
                ]
            }
        );

        $(document).on('click', '.btn-dt-reload', function(event) {
            oTable.draw();
        });

        $('#table-stage').on('draw.dt', function(e, settings) {
            if (arr.length > 0) {
                $.each(arr, function(index, el) {
                    $('input[name="stage"][value="'+ el +'"]').closest('td').trigger('click');
                });
            }
        })

        $('#table-stage tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var stage_id = tr.find('#stage').val();
            var row = oTable.row( tr );
            if ( row.child.isShown() ) {
                arr = removeArray(arr, stage_id);
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if (!arr.includes(stage_id)) {
                    arr.push(stage_id);
                }
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
            console.log(arr);
        } );
    });
</script>
<!-- <script type="text/javascript" src="<?= js('core.js?vs=1.1') ?>"></script> -->
<script type="text/javascript" src="<?= js('modal.js?vs=1.1') ?>"></script>

