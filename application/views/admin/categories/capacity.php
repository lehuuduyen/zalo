<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
    .fixedHeader-floating {
        position: fixed !important;
    }
</style>
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
    <!-- <div> -->
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a class="btn btn-info mright5 test pull-right H_action_button">
               <?php echo _l('Export excel'); ?></a>
            <div class="line-sp"></div>
            <a href="<?= base_url('admin/categories/add_capacity') ?>" class="btn btn-info pull-right H_action_button tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal">
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
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table id="table-capacity" class="table table-hover table-bordered table-condensed dataTable table-capacity" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><div class="checkbox mass_select_all_wrap text-center"><input type="checkbox" id="mass_select_all" data-to-table="capacity"><label for="mass_select_all"></label></div></th>
                                        <th><?= lang('code') ?></th>
                                        <th><?= lang('name') ?></th>
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
<?php $this->load->view('loader')?>
<script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript">
    var site = <?= json_encode(array('base_url' => base_url())) ?>;
    var token = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var hash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var fnserverparams = {};
    var oTable = '';
    $(document).ready(function() {
        oTable = tnhDatatable(
            '#table-capacity',
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
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/categories/getCapacity') ?>',
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
                            return '<div>'+ data +'</div>';
                        },
                        "targets": 1,
                        "name": 'code'
                    },
                    {"targets": 2, "name": 'name'},
                    {"targets": 3, "name": 'note'},
                    {"targets": 4, "name": 'actions', 'orderable': false, 'searchable': false, 'width': '100px'}
                ]
            }
        );
        $(document).on('click', '.btn-dt-reload', function(event) {
            oTable.draw();
        });
        loadAjax();
    });
</script>
<script type="text/javascript" src="<?= js('modal.js?vs=1.1') ?>"></script>

