<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?= css('tnh.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= css('timeline.css') ?>">
<style type="text/css">
	table tr td {
		vertical-align: middle !important;
	}
</style>
<?php echo form_open('admin/manufactures/detail_productions', array('id'=>'add-productions-plan')); ?>
<div id="wrapper">
	<div class="panel_s mbot10 H_scroll" id="">
		<div class="panel-body _buttons">
			<span class="bold uppercase fsize18 H_title"><?=$title?></span>
			<?= $this->load->view('admin/breadcrumb') ?>
		</div>
	</div>
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<ul class="tnh-timeline" id="tnh-timeline">
					<li class="tnh-li tnh-complete" style="width: 33%;">
						<div class="tnh-timestamp">
							<span class="author"></span>
							<span class="date"></span>
						</div>
						<div class="tnh-status">
							<h4> <?= lang('tnh_initialization') ?> </h4>
						</div>
					</li>
					<li class="tnh-li" style="width: 33%;">
						<div class="tnh-timestamp">
							<span class="author"></span>
							<span class="date"></span>
						</div>
						<div class="tnh-status">
							<h4> <?= lang('tnh_producing') ?> </h4>
						</div>
					</li>
					<li class="tnh-li" style="width: 33%;">
						<div class="tnh-timestamp">
							<span class="author"></span>
							<span class="date"></span>
						</div>
						<div class="tnh-status">
							<h4> <?= lang('tnh_complete_production') ?> </h4>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="tabset">
			<!-- Tab 1 -->
			<input type="radio" name="tabset" id="tab1" aria-controls="view-info" checked>
			<label for="tab1"><?= lang('info') ?></label>
			<!-- Tab 2 -->
			<input type="radio" name="tabset" id="tab2" aria-controls="view-material">
			<label for="tab2"><?= lang('tnh_list_material') ?></label>
			<!-- Tab 3 -->
			<input type="radio" name="tabset" id="tab3" aria-controls="view-suggest">
			<label for="tab3"><?= lang('list_suggest_exporting') ?></label>
			<!-- Tab 4 -->
			<input type="radio" name="tabset" id="tab4" aria-controls="view-stock">
			<label for="tab4"><?= lang('tnh_exporting_stock_producion') ?></label>
			<div class="tab-panels">
				<section id="view-info" class="tab-panel">
					<div class="row">
						<div class="col-md-9">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?= lang('tnh_report_productions') ?></h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tb-info" class="tnh-table table-bordered" style="width: 100%;">
											<thead>
												<tr>
													<th style="width: 20%;"><?= lang('tnh_describe') ?></th>
													<th style="width: 20%;"><?= lang('tnh_product_code') ?></th>
													<th style="width: 15%;" class="text-center"><?= lang('tnh_type') ?></th>
													<th style="width: 15%;" class="text-center"><?= lang('tnh_unit') ?></th>
													<th style="width: 15%;" class="text-center"><?= lang('quantity') ?></th>
													<th style="width: 15%;" class="text-center"><?= lang('tnh_quantity_finished') ?></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><?= $production_detail['items_name'] ?></td>
													<td><?= $production_detail['items_code'] ?></td>
													<td class="text-center"><?= lang($production_detail['type_items']) ?></td>
													<td class="text-center"><?= $production_detail['unit_name'] ?></td>
													<td class="text-center"><?= formatNumber($production_detail['quantity']) ?></td>
													<td class="text-center"><?= formatNumber(0) ?></td>
												</tr>
												<?php if (!empty($production_stage_parent)): ?>
													<tr>
														<td colspan="6">
															<table class="table pull-right" style="width: 100%; margin: 5px 0px;">
																<?php foreach ($production_stage_parent as $key => $value): ?>
																<tr class="success text-success">
																	<td style="width: 5%;" class="text-center"></td>
																	<td colspan="7"><?= $value['stage_name'] ?></td>
																</tr>
																<tr data-id="<?= $value['id'] ?>">
																	<td class="text-center"><a value="<?= $value['id'] ?>" class="add-row"><i class="fa fa-plus"></i></a></td>
																	<td style="width: 15%;"><?= lang('tnh_employees_ac') ?></td>
																	<td style="width: 15%;"><?= lang('tnh_datetime_start') ?></td>
																	<td style="width: 15%;"><?= lang('tnh_datetime_end') ?></td>
																	<td style="width: 15%;"><?= lang('tnh_total_time') ?></td>
																	<td style="width: 10%;"><?= lang('tnh_quantity_bad') ?></td>
																	<td style="width: 15%;"><?= lang('tnh_quantity_success') ?></td>
																	<td style="width: 10%;" class="text-center"><?= lang('actions') ?></td>
																</tr>
																<tr data-footer-id="<?= $value['id'] ?>">
																	<tr>
																		<td class="bold text-right" colspan="4"><?= lang('tnh_grand_total') ?>:</td>
																		<td class="tf-total-time text-center"></td>
																		<td class="tf-quantity-bad text-center"></td>
																		<td class="tf-quantity-success text-center"></td>
																		<td></td>
																	</tr>
																</tr>
																<?php endforeach ?>
																<!-- <tr class="danger text-danger">
																	<td class="text-center" style="width: 5%;"><?= lang('tnh_numbers') ?></td>
																	<td style="width: 20%;"><?= lang('tnh_stage_name') ?></td>
																	<td style="width: 20%;"><?= lang('tnh_machine_name') ?></td>
																	<td class="text-center" style="width: 25%;"><?= lang('tnh_number_hours') ?></td>
																	<td class="text-center" style="width: 25%;"><?= lang('tnh_quantity_finished') ?></td>
																</tr>
																<?php foreach ($production_stage_parent as $key => $value): ?>
																	<tr>
																		<td class="text-center"><?= ++$key ?></td>
																		<td><?= $value['stage_name'] ?></td>
																		<td><?= $value['machine_name'] ?></td>
																		<td class="text-center"><?= $value['number_hours'] ?></td>
																		<td class="text-center"><?= formatNumber(0) ?></td>
																	</tr>
																<?php endforeach ?> -->
															</table>
														</td>
													</tr>
												<?php endif ?>
												<!-- <?php foreach ($semi_products as $key => $value): ?>
													<tr>
														<td>------------<?= $value['item_name'] ?></td>
														<td><?= $value['item_code'] ?></td>
														<td class="text-center"><?= lang($value['type']) ?></td>
														<td class="text-center"><?= $value['unit_name'] ?></td>
														<td class="text-center"><?= formatNumber($production_detail['quantity']) ?></td>
														<td class="text-center"><?= formatNumber(0) ?></td>
													</tr>
													<?php
													$production_stage_semi = $this->manufactures_model->getProductionsOrdersItemsStagesBySubId($production_detail['productions_orders_item_id'], $value['id']);
													?>
													<?php if (!empty($production_stage_semi)): ?>
														<tr>
															<td colspan="6">
																<table class="table pull-right" style="width: 95%; margin: 10px 0px;">
																	<tr class="danger text-danger">
																		<td class="text-center" style="width: 10%;"><?= lang('tnh_numbers') ?></td>
																		<td style="width: 20%;"><?= lang('tnh_stage_name') ?></td>
																		<td style="width: 20%;"><?= lang('tnh_machine_name') ?></td>
																		<td class="text-center" style="width: 25%;"><?= lang('tnh_number_hours') ?></td>
																		<td class="text-center" style="width: 25%;"><?= lang('tnh_quantity_finished') ?></td>
																	</tr>
																	<?php foreach ($production_stage_semi as $k => $val): ?>
																		<tr>
																			<td class="text-center"><?= ++$k ?></td>
																			<td><?= $val['stage_name'] ?></td>
																			<td><?= $val['machine_name'] ?></td>
																			<td class="text-center"><?= $val['number_hours'] ?></td>
																			<td class="text-center"><?= formatNumber(0) ?></td>
																		</tr>
																	<?php endforeach ?>
																</table>
															</td>
														</tr>
													<?php endif ?>
												<?php endforeach ?> -->
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?= lang('tnh_info_productions') ?></h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="tnh-table table-bordered" style="width: 100%;">
											<tbody>
												<tr>
													<td style="width: 40%;"><?= lang('tnh_reference_productions_orders') ?></td>
													<td class="bold"><?= $production_detail['reference_no_order'] ?></td>
												</tr>
												<tr>
													<td><?= lang('tnh_reference_productions_orders_details') ?></td>
													<td class="bold"><?= $production_detail['reference_no'] ?></td>
												</tr>
												<tr>
													<td><?= lang('tnh_deadline') ?></td>
													<td class="bold"><?= $production_detail['deadline'] ?></td>
												</tr>
												<tr>
													<td><?= lang('departments') ?></td>
													<td class="bold"><?= $production_detail['department_name'] ?></td>
												</tr>
												<tr>
													<td><?= lang('tnh_product_code') ?></td>
													<td class="bold"><?= $production_detail['items_code'] ?></td>
												</tr>
												<tr>
													<td><?= lang('tnh_product_name') ?></td>
													<td class="bold"><?= $production_detail['items_name'] ?></td>
												</tr>
												<tr>
													<td><?= lang('quantity') ?></td>
													<td class="bold"><?= formatNumber($production_detail['quantity']) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-body">

								</div>
							</div>
						</div>
					</div>
				</section>
				<section id="view-material" class="tab-panel">
					<div class="table-responsive">
						<table id="tb-materials" class="table dt-tnh table-bordered table-hover dont-responsive-table" style="width: 100%;">
							<thead>
								<tr>
									<th><?= lang('tnh_numbers') ?></th>
									<th><?= lang('tnh_material_code') ?></th>
									<th><?= lang('tnh_material_name') ?></th>
									<th><?= lang('tnh_unit') ?></th>
									<th><?= lang('quantity') ?></th>
									<th><?= lang('tnh_quantity_exchange') ?></th>
									<th><?= lang('tnh_quantity_export') ?></th>
									<th><?= lang('tnh_quantity_missing') ?></th>
								</tr>
							</thead>
							<tbody>
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
								</tr>
							</tfoot>
						</table>
					</div>
				</section>
				<section id="view-suggest" class="tab-panel">
					<div class="mbot10">
						<a class="tnh-modal btn btn-primary" data-tnh="modal" data-toggle="modal" data-target="#myModal" target="_blank" href="<?= base_url('admin/manufactures/export_supplies/'.$id) ?>"><i class="fa fa-cube width-icon-actions"></i> <?= lang('tnh_requrest_export_of_supplies') ?></a>
					</div>
					<div class="">
						<table id="tb-suggest" class="table dt-tnh table-hover table-bordered table-condensed" style="width: 100%;">
							<thead>
								<tr>
									<th><?= lang('tnh_numbers') ?></th>
									<th><?= lang('id') ?></th>
									<th><?= lang('date') ?></th>
									<th><?= lang('tnh_reference_no_suggest') ?></th>
									<th><?= lang('tnh_export_name') ?></th>
									<th><?= lang('note') ?></th>
									<th><?= lang('tnh_created_by') ?></th>
									<th><?= lang('status') ?></th>
									<th><?= lang('tnh_type') ?></th>
									<th><?= lang('actions') ?></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</section>
				<section id="view-stock" class="tab-panel">
					<div class="">
						<table id="tb-stock" class="table dt-tnh table-hover table-bordered table-condensed" style="width: 100%;">
							<thead>
								<tr>
									<th><?= lang('tnh_numbers') ?></th>
									<th><?= lang('id') ?></th>
									<th><?= lang('date') ?></th>
									<th><?= lang('tnh_reference_stock') ?></th>
									<th><?= lang('tnh_export_name') ?></th>
									<th><?= lang('tnh_warehouses') ?></th>
									<th><?= lang('note') ?></th>
									<th><?= lang('tnh_created_by') ?></th>
									<th><?= lang('status') ?></th>
									<th><?= lang('ch_warehoues_app') ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="99"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<?php init_tail(); ?>
<script type="text/javascript" src="<?= base_url('assets/plugins/bootbox/bootbox.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/bootbox/bootbox.locales.min.js') ?>"></script>
<script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>

<link rel="stylesheet" type="text/css" href="<?= css('daterangepicker.css') ?>" />
<script type="text/javascript">
	var site = <?= json_encode(array('base_url' => base_url())) ?>;
	var token = "<?= $this->security->get_csrf_token_name() ?>";
	var hash = "<?= $this->security->get_csrf_hash() ?>";
	var fnserverparams = {};
	var dtMaterial = '';
	var dtSuggest = '';
	var dtStock = '';
</script>
<script type="text/javascript">
	$(document).ready(function() {
		dtMaterial = tnhDatatable(
            '#tb-materials',
            {
                'order': [[1, 'desc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "processing": true,
                // scrollY: true,
                // scrollX: true,
                autoWidth: true,
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/manufactures/getMaterialDetailProductions/'.$production_detail['productions_orders_item_id']) ?>',
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
                    var quantity = 0, quantity_exchange = 0, quantity_export = 0, quantity_missing = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        quantity+= intVal(aaData[i][4]);
                        quantity_exchange+= intVal(aaData[i][5]);
                        quantity_export+= intVal(aaData[i][6]);
                        quantity_missing+= intVal(aaData[i][7]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[4].innerHTML = '<div class="text-center bold">'+tnhFormatNumber(quantity)+'</div>';
                    nCells[5].innerHTML = '<div class="text-center bold">'+tnhFormatNumber(quantity_exchange)+'</div>';
                    nCells[6].innerHTML = '<div class="text-center bold">'+tnhFormatNumber(quantity_export)+'</div>';
                    nCells[7].innerHTML = '<div class="text-center bold">'+tnhFormatNumber(quantity_missing)+'</div>';
                },
                "columnDefs": [
                    {"targets": 0, "name": 'number_records', 'width': '45px', 'className': 'text-center'},
                    {
                    	"render": function(data, type, row) {
                    		str = '';
                    		if (data) {
                    			data = data.split('__');
                    			str = '<div>'+data[0]+'</div>';
                    			if (data[1] == "semi_products_outside") {
                    				str+= '<div style="margin-bottom: 5px;"></div><div class="label label-danger" style="margin-top: 5px;"><?= lang('semi_products_outside') ?></div>';
                    			}
                    		}
                    		return str;
                    	},
                    	"targets": 1, "name": 'item_code'
                    },
                    {"targets": 2, "name": 'item_name'},
                    {"targets": 3, "name": 'unit_name'},
                    {
                    	"render": function(data, type, row) {
                    		return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                    	},
                    	"targets": 4, "name": 'quantity', 'searchable': false
                    },
                    {
                    	"render": function(data, type, row) {
                    		return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                    	},
                    	"targets": 5, "name": 'quantity_exchange', 'searchable': false
                    },
                    {
                    	"render": function(data, type, row) {
                    		return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                    	},
                    	"targets": 6, "name": 'quantity_export', 'searchable': false
                    },
                    {
                    	"render": function(data, type, row) {
                    		return '<div class="text-center">'+tnhFormatNumber(data)+'</div>';
                    	},
                    	"targets": 7, "name": 'quantity_missing', 'searchable': false
                    },
                ]
            }
        );

        $(document).on('click', '#tb-materials_wrapper .btn-dt-reload', function(event) {
            dtMaterial.draw('page');
        });

        $('#table-productions-orders').on('draw.dt', function(e, settings) {
        })
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		dtSuggest = tnhDatatable(
            '#tb-suggest',
            {
                'order': [[2, 'desc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "processing": true,
                // scrollY: true,
                // scrollX: true,
                responsive: true,
                autoWidth: true,
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/manufactures/getSuggestExportingDetail/'.$id) ?>',
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
                	st = aData[7];
                	type = aData[8];
                	if (st != "un_approved" || type == 3)
                	{
                		$(nRow).find('.tnh-edit').addClass('tnh-disabled');
                	}
                	if (type == 3) {
                		$(nRow).find('.tnh-delete').addClass('tnh-disabled');
                	}
                },
                "initComplete": function(settings, json) {
                    var t = this;
                    t.parents('.table-loading').removeClass('table-loading');
                    t.removeClass('dt-table-loading');
                    mainWrapperHeightFix();
                },
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                },
                "columnDefs": [
                    {"targets": 0, "name": 'number_records', 'width': '45px', 'className': 'text-center'},
                    {
                    	"targets": 1, "name": 'id', 'visible': false
                    },
                    {
                    	"render": function(data, type, row) {
                    		return fld(data);
                    	},
                    	"targets": 2, "name": 'date', 'searchable': false
                    },
                    {
                    	"render": function(data, type, row) {
                    		return '<a class="tnh-modal" title="<?= lang('view') ?>" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="<?= base_url('admin/manufactures/view_suggest_exporting/') ?>'+row[1]+'">'+data+'</a>';
                    	},
                    	"targets": 3, "name": 'reference_no'
                    },
                    {
                    	"render": function(data, type, row) {
                            type = row[8];
                            label = 'success';
                            typeText = '<?= lang('tnh_tbom') ?>';
                            if (type == 3) {
                                label = 'primary';
                                typeText = '<?= lang('tnh_additional') ?>';
                            }
                            return '<div class="mbot5">'+data+'</div><div class="label label-'+label+'">'+typeText+'</div>';
                        },
                    	"targets": 4, "name": 'export_name'
                    },
                    {"targets": 5, "name": 'note'},
                    {"targets": 6, "name": 'created_by'},
                    {
                    	"render": function(data, type, row) {
                    		str = '';
                    		if (data == "un_approved") {
                    			str = '<span class="label label-danger"><?= lang('un_approved') ?></span>';
                    		} else if (data == "approved") {
                    			str = '<span class="label label-success"><?= lang('approved') ?></span>';
                    		}
                    		return str;
                    	},
                    	"targets": 7, "name": 'status'
                    },
                    {"targets": 8, "name": 'type', 'visible': false},
                    {"targets": 9, "name": 'actions', 'searchable': false, 'sortable': false, 'width': '80px'},
                ]
            }
        );
        $(document).on('click', '#tb-suggest_wrapper .btn-dt-reload', function(event) {
            dtSuggest.draw('page');
        });
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		dtStock = tnhDatatable(
            '#tb-stock',
            {
                'order': [[2, 'desc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "processing": true,
                // scrollY: true,
                // scrollX: true,
                responsive: true,
                autoWidth: true,
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/manufactures/getExportStockByDetailId/'.$id) ?>',
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
                	st = aData[7];
                	if (st != "un_approved")
                	{
                		$(nRow).find('.tnh-edit').addClass('tnh-disabled');
                	}
                },
                "initComplete": function(settings, json) {
                    var t = this;
                    t.parents('.table-loading').removeClass('table-loading');
                    t.removeClass('dt-table-loading');
                    mainWrapperHeightFix();
                },
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                },
                "columnDefs": [
                    {"targets": 0, "name": 'number_records', 'width': '45px', 'className': 'text-center'},
                    {
                    	"targets": 1, "name": 'id', 'visible': false
                    },
                    {
                    	"render": function(data, type, row) {
                    		return fld(data);
                    	},
                    	"targets": 2, "name": 'date', 'searchable': false
                    },
                    {
                    	"render": function(data, type, row) {
                    		return '<a class="tnh-modal" title="<?= lang('view') ?>" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="<?= base_url('admin/stock/view_exporting_production/') ?>'+row[1]+'">'+data+'</a>';
                    	},
                    	"targets": 3, "name": 'reference_stock'
                    },
                    {"targets": 4, "name": 'export_name'},
                    {"targets": 5, "name": 'warehouse_name'},
                    {"targets": 6, "name": 'note'},
                    {"targets": 7, "name": 'created_by'},
                    {
                    	"render": function(data, type, row) {
                    		str = '';
                    		if (data == "un_approved_stock") {
                    			str = '<span class="label label-danger"><?= lang('un_approved_stock') ?></span>';
                    		} else if (data == "approved") {
                    			str = '<span class="label label-success"><?= lang('approved') ?></span>';
                    		}
                    		return str;
                    	},
                    	"targets": 8, "name": 'status'
                    },
                    {
                    	"render": function(data, type, row) {
                    		if (!data) {
                    			return '<div class="label label-danger"><?= lang('tnh_un_approved_ws_stock') ?></div>';
                    		}
                    		return '';
                    	},
                    	"targets": 9, "name": 'status_warehouse'
                    },
                ]
            }
        );
        $(document).on('click', '#tb-stock_wrapper .btn-dt-reload', function(event) {
            dtStock.draw('page');
        });
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('click', 'input[name="tabset"]', function(event) {
			tab = $(this).attr('id');
			localStorage.setItem("tab", tab);
		});
		if (localStorage.getItem("tab")) {
			$('#'+localStorage.getItem("tab")).trigger('click');
		}
	});
</script>
<script type="text/javascript">
	var i = 0;
	var employees = <?= json_encode($employees) ?>;

	function getEmployees(selected_id = 0)
	{
		var options = '<option></option>';
		$.each(employees, function(index, el) {
			selected = selected_id == el.staffid ? 'selected' : '';
			options+= '<option '+selected+' value="'+el.staffid+'">'+el.fullname+'</option>';
		});
		return options;
	}

	function getItemsOfId(id) {
		var el = $('#tb-info tr[data-id="'+id+'"]');
		n = el.length - 1;
		var nb = 1;
		for (i = 1; i <= n; i++) {
			elRow = $(el)[i];
			$(elRow).find('.td-number').html(nb);
			nb++;
		}
	}

	function removeSub(row, id) {
		row = row.closest('tr');
		row.remove();
		getItemsOfId(id);
	}

	$(document).ready(function() {
		$(document).on('click', '.add-row', function(event) {
			event.preventDefault();
			trCurrent = $(this).closest('tr');
			id = $(this).attr('value');

			tdNumber = '<td class="text-center td-number">0</td>';
			tdEmployees = '<td>'+
				'<select name="employee_id" id="employee_id" data-placeholder="<?= lang('chosen') ?>" class="employee_id" style="width: 100%;">'+
					getEmployees()
				'</select>'+
			'</td>';
			tdDateStart = '<td>'+
				'<input type="text" name="datetime_start" placeholder="<?= lang('tnh_datetime_start') ?>" id="datetime_start" class="form-control datetimepicker" value="" title="">'+
			'</td>';
			tdDateEnd = '<td>'+
				'<input type="text" name="datetime_end" placeholder="<?= lang('tnh_datetime_end') ?>" id="datetime_end" class="form-control datetimepicker" value="" title="">'+
			'</td>';
			tdTotalTime = '<td>'+
				'<input type="number" name="total_time" id="total_time" class="form-control total_time" value="0">'
			'</td>';
			tdQuantityBad = '<td>'+
				'<input type="number" name="quantity_bad" id="quantity_bad" class="form-control quantity_bad" value="0">'
			'</td>';
			tdQuantitySuccess = '<td>'+
				'<input type="number" name="quantity_success" id="quantity_success" class="form-control quantity_success" value="0">'
			'</td>';
			tdActions = '<td class="text-center">'+
				'<i onclick="removeSub(this, '+id+')" class="btn btn-danger fa fa-remove rm-sub"></i>'
			'</td>';
			trHtml = '<tr data-id="'+id+'">'+
				tdNumber+
				tdEmployees+
				tdDateStart+
				tdDateEnd+
				tdTotalTime+
				tdQuantityBad+
				tdQuantitySuccess+
				tdActions+
			'</tr>';

			$('#tb-info tr[data-id="'+id+'"]:last').after(trHtml);
			$('select.employee_id').select2();
			init_datepicker();
			getItemsOfId(id);
		});
	});
</script>
