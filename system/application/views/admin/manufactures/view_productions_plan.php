<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= lang('view') ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="lead-view" id="leadViewWrapper">
						<div class="wap-content firt">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('date') ?>: </span>
							<span class="bold font-medium-xs lead-name"><?= _dt($productions_plan['date']) ?></span>
						</div>
						<div class="wap-content second">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_planning_cycle') ?>: </span>
							<span class="bold font-medium-xs lead-name"><?= _d($productions_plan['date_start']) ?> - <?= _d($productions_plan['date_end']) ?></span>
						</div>
						<div class="wap-content firt">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_applied_standard') ?>: </span>
							<span class="bold font-medium-xs lead-name">
								<?php if ($productions_plan['safe_inventory'] == 1): ?>
									<span class="label label-success"><?= lang('tnh_safe_inventory') ?></span>
								<?php else: ?>
									<span class="label label-danger"><?= lang('tnh_not') ?></span>
								<?php endif ?>
							</span>
						</div>
						<div class="wap-content second">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_options') ?>: </span>
							<span class="bold font-medium-xs lead-name">
								<?php if ($productions_plan['options1']): ?>
									<span class="label label-primary"><?= lang('tnh_sales_orders') ?></span>
								<?php endif ?>
							</span>
								<?php if ($productions_plan['options2']): ?>
									<span class="label label-warning"><?= lang('tnh_business_plan') ?></span>
								<?php endif ?>
							</span>
						</div>
						<?php if ($productions_plan['options1']): ?>
						<div class="wap-content firt">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_sales_orders') ?>: </span>
							<span class="bold font-medium-xs lead-name"><?= $productions_plan['options1_reference_no'] ?></span>
						</div>
						<?php endif ?>
						<?php if ($productions_plan['options2']): ?>
						<div class="wap-content second">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_business_plan') ?>: </span>
							<span class="bold font-medium-xs lead-name"><?= $productions_plan['options2_reference_no'] ?></span>
						</div>
						<?php endif ?>
						<div class="wap-content firt">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('note') ?>: </span>
							<span class="bold font-medium-xs lead-name"><?= $productions_plan['note'] ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-12 mtop10">
					<div class="table-responsive">
						<table id="table-view-plan" class="dt-tnh table table-bordered table-hover dont-responsive-table" style="width: 100%;">
							<thead>
								<tr>
									<th><?= lang('tnh_numbers') ?></th>
									<th><?= lang('tnh_product_code') ?></th>
									<th><?= lang('tnh_product_name') ?></th>
									<th><?= lang('unit') ?></th>
									<th><?= lang('tnh_safe_inventory') ?></th>
									<th><?= lang('tnh_quantity_warehouses') ?></th>
									<?= $th ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-6 pull-right">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-user"></i> <?= lang('tnh_user_created') ?></h3>
						</div>
						<div class="panel-body">
							<div><?= lang('tnh_created_by') ?>: <?= $created_by ?></div>
							<div><?= lang('tnh_date_creted') ?>: <?= _dt($productions_plan['date_created']) ?></div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="filter_productions_plan_id" id="filter_productions_plan_id" class="form-control" value="<?= $productions_plan['id'] ?>">
			<input type="hidden" name="filter_date_start" id="filter_date_start" class="form-control" value="<?= $productions_plan['date_start'] ?>">
			<input type="hidden" name="filter_date_end" id="filter_date_end" class="form-control" value="<?= $productions_plan['date_end'] ?>">
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
	var oTableProductionsPlan = '';
	var paramsProductionsPlan = {'filter_productions_plan_id': '#filter_productions_plan_id', 'filter_date_start': '#filter_date_start', 'filter_date_end': '#filter_date_end'};
	$(document).ready(function() {
		oTableProductionsPlan = tnhDatatable(
            '#table-view-plan',
            {
                'order': [[1, 'asc']],
                'orderCellsTop': true,
                "language": app.lang.datatables,
                "pageLength": app.options.tables_pagination_limit,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                scrollY: "300px",
                scrollX: true,
                fixedColumns:   {
                    leftColumns: 3,
                },
                "serverSide": true,
                'sAjaxSource': '<?= site_url('admin/manufactures/getViewProductionsPlan') ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "<?= $this->security->get_csrf_token_name() ?>",
                        "value": "<?= $this->security->get_csrf_hash() ?>"
                    });
                    for (var key in paramsProductionsPlan) {
                        aoData.push({
                            "name": key,
                            "value": $(paramsProductionsPlan[key]).val()
                        });
                    }
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
                "initComplete": function(settings, json) {
                    var t = this;
                    t.parents('.table-loading').removeClass('table-loading');
                    t.removeClass('dt-table-loading');
                    // mainWrapperHeightFix();
                },
                "columnDefs": [
                    {"targets": 0, "name": 'number_records', 'width': '50px', 'className': 'text-center'},
                    {"targets": 1, "name": 'product_code', 'width': '150px'},
                    {"targets": 2, "name": 'product_name', 'width': '150px'},
                    {"targets": 3, "name": 'unit', 'width': '80px', 'className': 'text-center'},
                    {
                    	"render": function(data) {
                    		return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                    	},
                    	"targets": 4, "name": 'quantity_minimum', 'width': '80px'
                    },
                    {
                    	"render": function(data) {
                    		return '<div class="text-center">'+tnhFormatNumber(data)+'</div>'
                    	},
                    	"targets": 5, "name": 'quantity_warehouses', 'width': '80px'
                    },
                    <?= $script ?>
                ],
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    start = 6;
                    if (!aaData) return;
                    end = aaData[0].length;
                    arr_id = [];
                    arr = [];
                    for (var i = 0; i < aaData.length; i++) {
                        for (j = start; j < end; j++)
                        {
                            if (typeof arr[j] == "undefined")
                            {
                                arr[j] = 0;
                            }
                            if (isNaN(parseFloat(aaData[aiDisplay[i]][j])))
                            {
                                total = 0;
                            } else {
                                total = parseFloat(aaData[aiDisplay[i]][j]);
                            }
                            arr[j] = arr[j] + total;
                        }
                    }
                    for (var i = start; i < arr.length; i++) {
                        if (arr[i] == 0) {
                            if (arr_id.indexOf(i) == -1) {
                                arr_id.push(i);
                            }
                        }
                    }
                    oTableProductionsPlan.columns(arr_id).visible(false, false);
                }
            }
        );
	});
</script>