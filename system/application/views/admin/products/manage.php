<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style class="">
    table tr td {
        vertical-align: middle !important;
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
                        <button href=\''.base_url('admin/products/delete_products_multiple').'\' class=\'btn btn-danger po-delete-multiple-json\'>'.lang('delete').'</button>
                        <button class=\'btn btn-default po-close\'>'.lang('close').'</button>
                    "><i class="fa fa-remove"></i> '.lang('delete').'</button>' ?>
            <div class="line-sp"></div>
            <a href="<?= base_url('admin/products/export_excel_products') ?>" class="btn btn-info pull-right H_action_button tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                <?php echo lang('tnh_export_excel'); ?>
            </a>
            <div class="line-sp"></div>
            <a href="<?= base_url('admin/products/import_products') ?>" class="btn btn-info pull-right H_action_button">
                <i class="fa fa-upload" aria-hidden="true"></i>
                <?php echo lang('tnh_import_excel'); ?>
            </a>
            <div class="line-sp"></div>
            <a href="<?= base_url('admin/products/add_product') ?>" class="btn btn-info pull-right H_action_button tnh-modal" data-tnh="modal" data-toggle="modal" data-target="#myModal">
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
                                <?php foreach (type_products() as $key => $value): ?>
                                <li role="presentation">
                                    <a href="#<?= $key ?>" aria-controls="<?= $key ?>" role="tab" value="<?= $key ?>" data-toggle="tab"><?= $value ?></a>
                                </li>
                                <?php endforeach ?>
                                <li role="presentation" class="active">
                                    <a href="#all" aria-controls="all" role="tab" value="" data-toggle="tab"><?= lang('all') ?></a>
                                </li>
                            </ul>
                            <input type="hidden" name="status_table" id="status_table" class="form-control status_table" value="">
                        </div>
                        <div class="">
                            <table id="table-products" class="table dt-tnh table-hover table-bordered table-condensed table-products">
                                <thead>
                                    <tr>
                                        <th><div class="checkbox mass_select_all_wrap text-center"><input type="checkbox" id="mass_select_all" data-to-table="products"><label for="mass_select_all"></label></div></th>
                                        <th><?= lang('image') ?></th>
                                        <th><?= lang('category') ?></th>
                                        <th><?= lang('tnh_type_products') ?></th>
                                        <th><?= lang('tnh_product_code') ?></th>
                                        <th><?= lang('tnh_product_name') ?></th>
                                        <th><?= lang('tnh_code_system') ?></th>
                                        <th><?= lang('tnh_product_name_customer') ?></th>
                                        <th><?= lang('tnh_product_name_supplier') ?></th>
                                        <th><?= lang('tnh_type_gender') ?></th>
                                        <th><?= lang('tnh_type_dt') ?></th>
                                        <th><?= lang('tnh_size') ?></th>
                                        <th><?= lang('tnh_weight') ?></th>
                                        <th><?= lang('tnh_structure') ?></th>
                                        <th><?= lang('tnh_description') ?></th>
                                        <th><?= lang('unit') ?></th>
                                        <th><?= lang('colors') ?></th>
                                        <th><?= lang('tnh_mode') ?></th>
                                        <th><?= lang('tnh_price_import') ?>(<?= lang('tnh_ncc') ?>)</th>
                                        <!-- <th><?= lang('tnh_price_sell') ?></th> -->
                                        <th><?= lang('tnh_price_domestic') ?></th>
                                        <th><?= lang('tnh_price_foreign') ?></th>
                                        <th><?= lang('tnh_price_processing') ?></th>
                                        <th><?= lang('tnh_quantity_minimum') ?></th>
                                        <th><?= lang('tnh_quantity_max') ?></th>
                                        <th><?= lang('tnh_number_hours_ap') ?></th>
                                        <th><?= lang('BOM') ?></th>
                                        <th><?= lang('stages') ?></th>
                                        <th><?= lang('tnh_barcode') ?></th>
                                        <th><?= lang('tnh_number_labor') ?></th>
                                        <th><?= lang('note') ?></th>
                                        <th><?= lang('tnh_versions') ?></th>
                                        <th><?= lang('tnh_versions_stage') ?></th>
                                        <?= $th ?>
                                        <th><?= lang('tnh_created_by') ?></th>
                                        <th><?= lang('tnh_date_created') ?></th>
                                        <th><?= lang('tnh_updated_by') ?></th>
                                        <th><?= lang('tnh_date_updated') ?></th>
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
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script type="text/javascript">
	// var site = <?= json_encode(array('base_url' => base_url())) ?>;
    var lang_product = <?= json_encode(array('tnh_sequence' => lang('tnh_sequence'), 'tnh_stage' => lang('tnh_stage'), 'tnh_number_date' => lang('tnh_number_date'), 'tnh_number_date' => lang('tnh_number_date'))) ?>;
	var token = '<?php echo $this->security->get_csrf_token_name(); ?>';
	var hash = '<?php echo $this->security->get_csrf_hash(); ?>';
	var fnserverparams = {status_table: '#status_table'};
	var oTable = '';
    var iDt = 0;
</script>
<script type="text/javascript">
	$(document).ready(function() {
		oTable = tnhDatatable(
            '#table-products',
            {
                'order': [[2, 'asc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "processing": true,
                // 'fixedHeader': {
                //     header: true,
                //     footer: true
                // },
                scrollY: height_body,
                scrollX: true,
                // scrollCollapse: true,
                fixedColumns:   {
                    leftColumns: 6,
                    rightColumns: 0
                },
                // stateSave: true,
                autoWidth: true,
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/products/getProducts') ?>',
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
                "drawCallback": function(aoData, settings) {
                    $('.sl-bom').selectpicker();
                    $('.sl-stages').selectpicker();
                },
                'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                    type_products = aData[3];
                    if (type_products == 'semi_products_outside')
                    {
                        $(nRow).find('.design_bom').addClass('tnh-disabled');
                        $(nRow).find('.stages').addClass('tnh-disabled');
                    }
                    return nRow;
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
                    {
                        "render": function (data, type, row) {
                            return '<div class="checkbox"><input type="checkbox" name="product_id[]" id="check-item'+data+'" value="'+ data +'"><label for="check-item'+data+'"></label></div>';
                        },
                        "targets": iDt,
                        "name": 'id',
                        'orderable': false,
                        'width': '30px'
                    },
                    {
                    	"targets": ++iDt, "name": 'images', 'width': '45px',
                    	"render": function (data, type, row) {
                    		images = (data != null) ? site.base_url+"uploads/products/"+data+'?' : site.base_url+"assets/images/tnh/no_image.png";
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
                    {"targets": ++iDt, "name": 'category_name', 'width': '60px'},
                    {
                        "targets": ++iDt, "name": 'type_products', 'width': '120px',
                        "render": function(data, type, row) {
                            str = '';
                            if (data == "products") {
                                str = '<span class="label label-success"><?= lang('products') ?></span>';
                            } else if (data == "semi_products") {
                                str = '<span class="label label-danger"><?= lang('semi_products') ?></span>';
                            } else if (data == "semi_products_outside") {
                                str = '<span class="label label-warning"><?= lang('semi_products_outside') ?></span>';
                            }
                            return '<div class="text-left">'+str+'</div>';
                        }
                    },
                    {
                        "render": function(data, type, row) {
                            return '<div style="min-width: 100px;">\
                                        <a data-tnh="modal" class="tnh-modal" href="'+site.base_url+'admin/products/view_product/'+row[0]+'" data-toggle="modal" data-target="#myModal">'+ data +'</a>\
                                    </div>';
                        },
                        "targets": ++iDt,
                        "name": 'code',
                        'width': '100px'
                    },
                    {
                        "render": function(data, type, row) {
                            return '<div style="width: 100px;">'+data+'</div>'
                        },
                        "targets": ++iDt, "name": 'name', 'width': '100px'
                    },
                    {"targets": ++iDt, "name": 'code_system', 'width': '50px'},
                    {"targets": ++iDt, "name": 'name_customer', 'width': '150px'},
                    {"targets": ++iDt, "name": 'name_supplier', 'width': '150px'},
                    {
                        "render": function(data, type, row) {
                            return data == 1 ? '<?= lang('cong_male') ?>' : (data == 2 ? '<?= lang('cong_female') ?>' : '<?= lang('all') ?>');
                        },
                        "targets": ++iDt, "name": 'type_gender', 'width': '80px'
                    },
                    {"targets": ++iDt, "name": 'name_dt', 'width': '50px'},
                    {"targets": ++iDt, "name": 'size', 'width': '80px'},
                    {"targets": ++iDt, "name": 'weight', 'width': '80px'},
                    {"targets": ++iDt, "name": 'structure', 'width': '80px'},
                    {"targets": ++iDt, "name": 'description', 'width': '80px'},
                    {"targets": ++iDt, "name": 'unit_name', 'width': '50px'},
                    {"targets": ++iDt, "name": 'color', 'width': '60px'},
                    {"targets": ++iDt, "name": 'mode', 'width': '60px'},
                    {
                    	"targets": ++iDt, "name": 'price_import', 'width': '100px',
                    	"render": function(data, type, row) {
                    		return '<div class="text-right">'+tnhFormatMoney(data)+'</div>';
                    	}
                    },
                    // {
                    // 	"targets": ++iDt, "name": 'price_sell', 'width': '100px',
                    // 	"render": function(data, type, row) {
                    // 		return '<div class="text-right">'+tnhFormatMoney(data)+'</div>';
                    // 	}
                    // },
                    {
                        "targets": ++iDt, "name": 'price_domestic', 'width': '100px',
                        "render": function(data, type, row) {
                            return '<div class="text-right">'+tnhFormatMoney(data)+'</div>';
                        }
                    },
                    {
                        "targets": ++iDt, "name": 'price_foreign', 'width': '100px',
                        "render": function(data, type, row) {
                            return '<div class="text-right">'+tnhFormatMoney(data)+'</div>';
                        }
                    },
                    {
                        "targets": ++iDt, "name": 'price_processing', 'width': '100px',
                        "render": function(data, type, row) {
                            return '<div class="text-right">'+tnhFormatMoney(data)+'</div>';
                        }
                    },
                    {
                        "targets": ++iDt, "name": 'quantity_minimum', 'width': '100px',
                        "render": function(data, type, row) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        }
                    },
                    {
                        "targets": ++iDt, "name": 'quantity_max', 'width': '100px',
                        "render": function(data, type, row) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        }
                    },
                    {
                        "targets": ++iDt, "name": 'number_hours_ap', 'width': '100px',
                        "render": function(data, type, row) {
                            return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                        }
                    },
                    {
                        "targets": ++iDt, "name": 'bm', 'width': '100px',
                        "render": function(data, type, row) {
                            sl = '';
                            if (data) {
                                data = data.split(':::');
                                sl += '<div style="width: 100px;"><select name="sl-bom[]" class="form-control sl-bom" data-live-search="true" data-none-selected-text="<?= lang('choose') ?>"><option value=""></option>';
                                $.each(data, function(index, el) {
                                    selected = row[30] == el ? 'selected' : '';
                                    sl += '<option '+selected+' value="'+el+'">'+el+'</option>';
                                });
                                sl += '</select></div>';
                            }
                            return sl;
                        }
                    },
                    {
                        "targets": ++iDt, "name": 'st', 'width': '100px',
                        "render": function(data, type, row) {
                            sl = '';
                            if (data) {
                                data = data.split(':::');
                                sl += '<div style="width: 100px;"><select name="sl-stages[]" class="form-control sl-stages" data-live-search="true" data-none-selected-text="<?= lang('choose') ?>"><option value=""></option>';
                                $.each(data, function(index, el) {
                                    selected = row[31] == el ? 'selected' : '';
                                    sl += '<option '+selected+' value="'+el+'">'+el+'</option>';
                                });
                                sl += '</select></div>';
                            }
                            return sl;
                        }
                    },
                    {
                        "targets": ++iDt, "name": 'barcode', 'width': '200px',
                        "render": function(data, type, row) {
                            // return '';
                            return '<div style="width: 200px; height: 30px;"><img style="max-width: 200px; height: 30px;" src="'+site.base_url+'admin/products/gen_barcode/'+data+'"></div>'
                        }
                    },
                    {"targets": ++iDt, "name": 'number_labor', 'width': '80px', 'className': 'text-center'},
                    {"targets": ++iDt, "name": 'note', 'width': '80px'},
                    {"targets": ++iDt, "name": 'versions', 'visible': false},
                    {"targets": ++iDt, "name": 'versions_stage', 'visible': false},
                    <?= $script ?>
                    {"targets": <?= $targets ?>, "name": 'created_by', 'width': '100px'},
                    {
                        "render": function(data, type, row) {
                            return fld(data);
                        },
                        "targets": <?= ++$targets ?>, "name": 'date_created', 'width': '100px', 'searchable': false
                    },
                    {"targets": <?= ++$targets ?>, "name": 'updated_by', 'width': '100px'},
                    {
                        "render": function(data, type, row) {
                            return fld(data);
                        },
                        "targets": <?= ++$targets ?>, "name": 'date_updated', 'width': '100px', 'searchable': false
                    },
                    {"targets": <?= ++$targets ?>, "name": 'actions', 'orderable': false, 'searchable': false, 'width': '50px'}
                ]
            }
        );

		// filterCustom('#table-products thead', oTable, [
  //           {element: '#table-products thead tr:eq(1) th:nth-child(2)', type: "text", data: []},
  //           {element: '#table-products thead tr:eq(1) th:nth-child(3)', type: "text", data: []},
  //           {element: '#table-products thead tr:eq(1) th:nth-child(4)', type: "text", data: []},
  //           {element: '#table-products thead tr:eq(1) th:nth-child(5)', type: "text", data: []},
  //           {element: '#table-products thead tr:eq(1) th:nth-child(6)', type: "text", data: []},
  //           {element: '#table-products thead tr:eq(1) th:nth-child(7)', type: "text", data: []},
  //           {element: '#table-products thead tr:eq(1) th:nth-child(8)', type: "text", data: []},
  //           {element: '#table-products thead tr:eq(1) th:nth-child(9)', type: "text", data: []},
  //       ]);

		$(document).on('click', '.btn-dt-reload', function(event) {
            oTable.draw();
        });

        $('#table-products').on('draw.dt', function(e, settings) {
            // $('.dataTables_scrollHead tr th:nth-child(15)').addClass('abcd');
            // $(this).removeClass('dataTable');
            // $('.DTFC_LeftBodyLiner .dataTables_wrapper').remove();
            // $('table').removeClass('dataTable');
            // $('.DTFC_LeftBodyLiner table').removeClass('dataTable');
            // setTimeout(function(){ }, 3000);

            // $('.DTFC_LeftBodyLiner table td:nth-child(1)').trigger('click');
            // $('.DTFC_RightBodyLiner table td:nth-child(1)').trigger('click');
            // setTimeout(function(){
            //     $('.DTFC_RightBodyLiner table td:nth-child(1)').trigger('click');
            //     $('.DTFC_LeftBodyLiner table').removeClass('dataTable');
            //     $('.DTFC_RightBodyLiner table').removeClass('dataTable');
            // }, 1000);

            // console.log(settings.fnRecordsTotal());
        })

        $(document).on('change', 'select.sl-bom', function(event) {
            event.preventDefault();
            row = $(this).closest('tr');
            product_id = row.find('input[name="product_id[]"]').val();
            material_bom = $(this).val();
            $.ajax({
                url: site.base_url+'admin/products/change_versions',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>',
                    product_id: product_id,
                    material_bom: material_bom,
                },
            })
            .done(function(data) {
                alert_float('success', data.message);
            })
            .fail(function() {
                alert_float('danger', 'fail');
            });
        });

        $(document).on('change', 'select.sl-stages', function(event) {
            event.preventDefault();
            row = $(this).closest('tr');
            product_id = row.find('input[name="product_id[]"]').val();
            vs_stage = $(this).val();
            $.ajax({
                url: site.base_url+'admin/products/change_versions_stages',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>',
                    product_id: product_id,
                    vs_stage: vs_stage,
                },
            })
            .done(function(data) {
                alert_float('success', data.message);
            })
            .fail(function() {
                alert_float('danger', 'fail');
            });
        });

        $(document).on('click', '.status-table li a', function(event) {
            status_table = $(this).attr('value');
            $('#status_table').val(status_table);
            oTable.draw();
        });
		loadAjax();
	});
</script>
<script type="text/javascript" src="<?= js('modal.js?vs=1.1') ?>"></script>
<script type="text/javascript">
    var json = {};
</script>
<script type="text/javascript" src="<?= js('design_bom.js?vs=1.5') ?>"></script>
