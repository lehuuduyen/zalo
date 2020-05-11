<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
    .fixedHeader-floating {
        position: fixed !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= css('tnh.css?vs=1.1') ?>">
<?php echo form_open(); ?>
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
    <!-- <div> -->
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <?php echo '<button type="button" class="po btn btn-info pull-right H_action_button" data-container="body" data-html="true" data-toggle="popover" data-placement="left" data-content="
                        <button href=\''.base_url('admin/items/delete_material_multiple').'\' class=\'btn btn-danger po-delete-multiple-json\'>'.lang('delete').'</button>
                        <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
                    "><i class="fa fa-remove"></i> '.lang('delete').'</button>' ?>
            <div class="line-sp"></div>
            <a href="<?= base_url('admin/items/export_excel_items') ?>" class="btn btn-info pull-right H_action_button tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                <?php echo lang('tnh_export_excel'); ?>
            </a>
            <div class="line-sp"></div>
            <a href="<?= base_url('admin/items/import_items') ?>" class="btn btn-info pull-right H_action_button">
                <i class="fa fa-upload" aria-hidden="true"></i>
                <?php echo lang('tnh_import_excel'); ?>
            </a>
            <div class="line-sp"></div>
            <a href="<?= base_url('admin/items/add_item') ?>" class="btn btn-info pull-right H_action_button tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal">
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
                        <div class="alert alert-danger alert-dismissible show-alert" style="display: none;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right: 0;">&times;</a>
                            <div class="show-errors">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table id="table-materials" class="table table-hover table-datatable table-bordered table-condensed dataTable table-materials" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><div class="checkbox mass_select_all_wrap text-center"><input type="checkbox" id="mass_select_all" data-to-table="materials"><label for="mass_select_all"></label></div></th>
                                        <th><?= lang('image') ?></th>
                                        <th><?= lang('category') ?></th>
                                        <th><?= lang('tnh_material_code_system') ?></th>
                                        <th><?= lang('tnh_material_code') ?></th>
                                        <th><?= lang('tnh_material_name') ?></th>
                                        <th><?= lang('unit') ?></th>
                                        <th><?= lang('tnh_exchange') ?></th>
                                        <th><?= lang('tnh_price_import') ?></th>
                                        <th><?= lang('tnh_price_sell') ?></th>
                                        <th><?= lang('tnh_quantity_minimum') ?></th>
                                        <th><?= lang('note') ?></th>
                                        <?= $th ?>
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
<script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript">
	// var site = <?= json_encode(array('base_url' => base_url())) ?>;
    var lang_material = <?= json_encode(array('tnh_sequence' => lang('tnh_sequence'), 'tnh_stage' => lang('tnh_stage'), 'tnh_number_date' => lang('tnh_number_date'), 'tnh_number_date' => lang('tnh_number_date'))) ?>;
	var token = '<?php echo $this->security->get_csrf_token_name(); ?>';
	var hash = '<?php echo $this->security->get_csrf_hash(); ?>';
	var fnserverparams = {};
	var oTable = '';
</script>
<script type="text/javascript">
	$(document).ready(function() {
		oTable = tnhDatatable(
            '#table-materials',
            {
                'order': [[3, 'desc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                scrollY: height_body,
                scrollX: true,
                // dom: "<'row'><'row'<'col-md-7'lB><'col-md-5'f>>rt<'row'<'col-md-4'i>><'row'<'#colvis'><'.dt-page-jump'>p>",
                // stateSave: true,
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/items/getMaterials') ?>',
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
                            return '<div class="checkbox"><input type="checkbox" name="material_id[]" id="check-item'+data+'" value="'+ data +'"><label for="check-item'+data+'"></label></div>';
                        },
                        "targets": 0,
                        "name": 'id',
                        'orderable': false,
                        'width': '50px'
                    },
                    {
                    	"targets": 1, "name": 'images', 'width': '60px',
                    	"render": function (data, type, row) {
                    		images = (data != null) ? site.base_url+"uploads/materials/"+data+'?' : site.base_url+"assets/images/tnh/no_image.png";
                    		return '<div class="preview_image" style="width: auto;">\
		                        <div class="display-block contract-attachment-wrapper img">\
		                            <div style="width:45px; margin: auto;">\
		                                <a href="'+images+'" data-lightbox="customer-profile" class="display-block mbot5">\
		                                    <div class="">\
		                                        <img src="'+images+'" style="border-radius: 50%" />\
		                                    </div>\
		                                </a>\
		                            </div>\
		                        </div>\
		                    </div>';
                    	}
                    },
                    {"targets": 2, "name": 'category_name', 'width': '100px'},
                    {"targets": 3, "name": 'code_system', 'width': '100px'},
                    {
                        "render": function(data, type, row) {
                            return '<div><a class="tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="'+site.base_url+'admin/items/view_item/'+row[0]+'">'+data+'</a></div>';
                        },
                        "targets": 4,
                        "name": 'code',
                        'width': '100px'
                    },
                    {"targets": 5, "name": 'name', 'width': '180px'},
                    {"targets": 6, "name": 'unit_name', 'width': '80px'},
                    {
                        "targets": 7, "name": 'exchange', 'width': '80px',
                        "render": function(data, type, row) {
                            str = '';
                            if (data) {
                                data = data.split(':::');
                                $.each(data, function(index, el) {
                                    e = el.split('::');
                                    str += '<div class="label label-primary">'+e[0]+' -> '+e[1]+'</div><div class="tnh-hr"></div>';
                                });
                                return str;
                            } else {
                                return str;
                            }
                        }
                    },
                    {
                    	"targets": 8, "name": 'price_import', 'width': '80px',
                    	"render": function(data, type, row) {
                    		return '<div class="text-right">'+tnhFormatMoney(data)+'</div>';
                    	}
                    },
                    {
                    	"targets": 9, "name": 'price_sell', 'width': '80px',
                    	"render": function(data, type, row) {
                    		return '<div class="text-right">'+tnhFormatMoney(data)+'</div>';
                    	}
                    },
                    {
                        "targets": 10, "name": 'quantity_minimum', 'width': '80px',
                        "render": function(data, type, row) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        }
                    },
                    {"targets": 11, "name": 'note'},
                    <?= $script ?>
                    {"targets": <?= $targets ?>, "name": 'actions', 'orderable': false, 'searchable': false, 'width': '80px'}
                ]
            }
        );

		// filterCustom('#table-materials thead', oTable, [
  //           {element: '#table-materials thead tr:eq(1) th:nth-child(2)', type: "text", data: []},
  //           {element: '#table-materials thead tr:eq(1) th:nth-child(3)', type: "text", data: []},
  //           {element: '#table-materials thead tr:eq(1) th:nth-child(4)', type: "text", data: []},
  //           {element: '#table-materials thead tr:eq(1) th:nth-child(5)', type: "text", data: []},
  //           {element: '#table-materials thead tr:eq(1) th:nth-child(6)', type: "text", data: []},
  //           {element: '#table-materials thead tr:eq(1) th:nth-child(7)', type: "text", data: []},
  //           {element: '#table-materials thead tr:eq(1) th:nth-child(8)', type: "text", data: []},
  //           {element: '#table-materials thead tr:eq(1) th:nth-child(9)', type: "text", data: []},
  //       ]);

		$(document).on('click', '.btn-dt-reload', function(event) {
            oTable.draw('page');
        });
		loadAjax();
	});
</script>
<script type="text/javascript" src="<?= js('modal.js?vs=1.1') ?>"></script>
