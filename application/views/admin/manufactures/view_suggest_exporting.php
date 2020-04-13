<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= lang('view') ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-6">
                    <div class="lead-view" id="leadViewWrapper">
                    	<div class="wap-content firt">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('date') ?>: </span>
                            <span class="bold font-medium-xs lead-name"><?= _dt($suggest_exporting['date']) ?></span>
                        </div>
                        <div class="wap-content second">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_reference_no_suggest') ?>: </span>
                            <span class="bold font-medium-xs lead-name"><?= $suggest_exporting['reference_no'] ?></span>
                        </div>
                        <div class="wap-content firt">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_export_name') ?>: </span>
                            <span class="bold font-medium-xs lead-name"><?= $suggest_exporting['export_name'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                	<div class="lead-view" id="leadViewWrapper">
                		<div class="wap-content firt">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('note') ?>: </span>
                            <span class="bold font-medium-xs lead-name"><?= $suggest_exporting['note'] ?></span>
                        </div>
                	</div>
                </div>
                <div class="col-md-12 mtop10">
                	<div class="table-responsive">
                		<table id="table-items" class="table dt-tnh table-hover table-condensed table-bordered">
                			<thead>
                				<tr>
                					<th class="text-center"><?= lang('tnh_numbers') ?></th>
                					<th><?= lang('tnh_material_code') ?></th>
									<th><?= lang('tnh_material_name') ?></th>
									<th class="text-center"><?= lang('tnh_unit') ?></th>
									<th class="text-center"><?= lang('tnh_quantity_export') ?></th>
									<th class="text-center"><?= lang('tnh_value_exchange') ?></th>
									<th class="text-center"><?= lang('tnh_quantity_exchange') ?></th>
                				</tr>
                			</thead>
                			<tbody>
                				<?php foreach ($items as $key => $value): ?>
                					<tr>
                						<td class="text-center"><?= ++$key ?></td>
                						<td>
                                            <?= $value['item_code'] ?>
                                            <?php if ($value['type_item'] == 'semi_products_outside'): ?>
                                                <div style="margin-bottom: 5px;"></div>
                                                <div class="label label-danger" style="margin-top: 5px;"><?= lang('semi_products_outside') ?></div>
                                            <?php endif ?>
                                        </td>
                						<td><?= $value['item_name'] ?></td>
                						<td class="text-center"><?= $value['unit_name'] ?></td>
                						<td class="text-center"><?= formatNumber($value['quantity_export']) ?></td>
                						<td class="text-center"><?= formatNumber($value['number_exchange']) ?></td>
                						<td class="text-center"><?= formatNumber($value['quantity_exchange']) ?></td>
                					</tr>
                				<?php endforeach ?>
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
                				</tr>
                			</tfoot>
                		</table>
                	</div>
                </div>
                <div class="col-md-6 pull-right mtop10">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-user"></i> <?= lang('tnh_user_created') ?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-6">
                                <div><?= lang('tnh_created_by') ?>: <?= $created_by ?></div>
                                <div><?= lang('tnh_date_creted') ?>: <?= _dt($suggest_exporting['date_created']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <?php if (!empty($updated_by)): ?>
                                    <div><?= lang('tnh_updated_by') ?>: <?= $updated_by ?></div>
                                    <div><?= lang('tnh_date_updated') ?>: <?= _dt($suggest_exporting['date_updated']) ?></div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var dtItems = $('#table-items').DataTable({
			"language": app.lang.datatables,
            "pageLength": app.options.tables_pagination_limit,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            // scrollY: true,
            // scrollX: true,
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
            },
            "initComplete": function(settings, json) {
                var t = this;
                t.parents('.table-loading').removeClass('table-loading');
                t.removeClass('dt-table-loading');
            },
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                pageQuantityExport = api
                    .column( 4, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                pageQuantityExchange = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                $( api.column( 4 ).footer() ).html('<div class="text-center">'+tnhFormatNumber(pageQuantityExport)+'</div>');
                $( api.column( 6 ).footer() ).html('<div class="text-center">'+tnhFormatNumber(pageQuantityExchange)+'</div>');
            }
		});
	});
</script>