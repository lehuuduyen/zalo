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
                            <span class="bold font-medium-xs lead-name"><?= _dt($productions_orders['date']) ?></span>
                        </div>
                        <div class="wap-content second">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_reference_productions_orders') ?>: </span>
                            <span class="bold font-medium-xs lead-name"><?= $productions_orders['reference_no'] ?></span>
                        </div>
                        <div class="wap-content firt">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_location') ?>: </span>
                            <span class="bold font-medium-xs lead-name"><?= $location['name'] ?></span>
                        </div>
                        <div class="wap-content second">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_reference_productions_plan') ?>: </span>
                            <span class="bold font-medium-xs lead-name"><?= $productions_orders['productions_plan_reference_no'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="lead-view" id="leadViewWrapper">
                    	<div class="wap-content firt">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('note') ?>: </span>
                            <span class="bold font-medium-xs lead-name"></span>
                        </div>
                        <div class="wap-content firt">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_status') ?>: </span>
                            <span class="bold font-medium-xs lead-name"><?= lang($productions_orders['status']) ?></span>
                        </div>
                        <div class="wap-content second">
                            <span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_user_agree') ?>: </span>
                            <span class="bold font-medium-xs lead-name"><?= $user_status ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mtop10">
                    <div class="tabset">
                        <!-- Tab 1 -->
                        <input type="radio" name="tabset" id="tab1" aria-controls="view-items" checked>
                        <label for="tab1"><?= lang('tnh_items') ?></label>
                        <!-- Tab 2 -->
                        <input type="radio" name="tabset" id="tab2" aria-controls="view-bom">
                        <label for="tab2"><?= lang('tnh_plan_material') ?></label>
                        <div class="tab-panels">
                            <section id="view-items" class="tab-panel">
                            	<div class="table-responsive">
                            		<table id="table-items" class="dt-table table table-bordered table-hover dont-responsive-table" style="max-height: 400px !important;">
                            			<thead>
                            				<tr>
                            					<th class="text-center"><?= lang('tnh_numbers') ?></th>
                            					<th><?= lang('tnh_images') ?></th>
                            					<th><?= lang('code') ?></th>
                            					<th><?= lang('name') ?></th>
                            					<th class="text-center"><?= lang('quantity') ?></th>
                            					<th><?= lang('note') ?></th>
                                                <th class="hide"><?= lang('sub') ?></th>
                            				</tr>
                            			</thead>
                            			<tbody>
                            				<?php foreach ($productions_orders_items as $key => $value): ?>
                            					<?php
                            					$images = base_url().'assets/images/tnh/no_image.png';
                            					if (!empty($value['images'])) {
                            						$images = base_url('uploads/products/'.$value['images']);
                            					}
                            					?>
                            					<tr>
                            						<td class="text-center details-control">
                                                        <!-- <?= (++$key) ?> -->
                                                    </td>
                            						<td>
                            							<div class="td-image">
                            								<div class="preview_image" style="width: auto;">
                            									<div class="display-block contract-attachment-wrapper img">
                            										<div style="width:45px;">
                            											<a href="<?= $images ?>" data-lightbox="customer-profile" class="display-block mbot5">
                            												<div class="">
                            													<img src="<?= $images ?>" style="border-radius: 50%">
                            												</div>
                            											</a>
                            										</div>
                            									</div>
                            								</div>
                            							</div>
                            						</td>
                            						<td>
                            							<?= $value['item_code'] ?>
                            						</td>
                            						<td>
                            							<?= $value['item_name'] ?>
                            						</td>
                            						<td class="text-center">
                            							<?= formatNumber($value['quantity']) ?>
                            						</td>
                            						<td>
                            							<?= $value['note_items'] ?>
                            						</td>
                                                    <td class="hide">
                                                        <table class="tnh-table table-bordered" style="width: 90%; float: right;">
                                                            <body>
                                                                <tr>
                                                                    <td class="text-center" style="width: 80px;"><?= lang('tnh_numbers') ?></td>
                                                                    <td><?= lang('tnh_stage_name') ?></td>
                                                                    <td><?= lang('tnh_machines') ?></td>
                                                                    <td><?= lang('tnh_number_hours') ?></td>
                                                                </tr>
                                                                <?php
                                                                $stages = $this->manufactures_model->getProductionsOrdersItemsStagesView($value['id']);
                                                                ?>
                                                                <?php foreach ($stages as $k => $val): ?>
                                                                    <tr>
                                                                        <td class="text-center"><?= (++$k) ?></td>
                                                                        <td><?= $val['stage_name'] ?></td>
                                                                        <td><?= $val['machine_name'] ?></td>
                                                                        <td><?= $val['number_hours'] ?></td>
                                                                    </tr>
                                                                <?php endforeach ?>
                                                            </body>
                                                        </table>
                                                    </td>
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
                            					<th class="hide"></th>
                            				</tr>
                            			</tfoot>
                            		</table>
                            	</div>
                            </section>
                            <section id="view-bom" class="tab-panel">
                                <div class="table-responsive">
                                    <table id="table-bom" class="dt-table table table-bordered table-hover dont-responsive-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px;"><?= lang('tnh_numbers') ?></th>
                                                <th style="width: 150px;"><?= lang('type') ?></th>
                                                <th><?= lang('code') ?></th>
                                                <th><?= lang('name') ?></th>
                                                <th class="text-center"><?= lang('unit') ?></th>
                                                <th class="text-center"><?= lang('quantity') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($subs as $key => $value): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?= (++$key) ?>
                                                    </td>
                                                    <td><?= lang($value['type']) ?></td>
                                                    <td><?= $value['item_code'] ?></td>
                                                    <td><?= $value['item_name'] ?></td>
                                                    <td class="text-center"><?= $value['unit'] ?></td>
                                                    <td class="text-center"><?= formatNumber($value['total_quantity']) ?></td>
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
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </section>
                        </div>
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
                                <div><?= lang('tnh_date_creted') ?>: <?= _dt($productions_orders['date_created']) ?></div>
                            </div>
                            <div class="col-md-6">
                                <?php if (!empty($updated_by)): ?>
                                    <div><?= lang('tnh_updated_by') ?>: <?= $updated_by ?></div>
                                    <div><?= lang('tnh_date_updated') ?>: <?= _dt($productions_orders['date_updated']) ?></div>
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
    var arr = [];
    function formatProductionsOrders(d) {
        sub = d[6];
        return sub;
    }
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
                pageTotalQuantity = api
                    .column( 4, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                $( api.column( 4 ).footer() ).html('<div class="text-center">'+tnhFormatNumber(pageTotalQuantity)+'</div>');
            }
		});

        $('#table-items tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var records = tr.find('#records').val();
            var row = dtItems.row( tr );

            if ( row.child.isShown() ) {
                arr = removeArray(arr, records);
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if (!arr.includes(records)) {
                    arr.push(records);
                }
                row.child( formatProductionsOrders(row.data()) ).show();
                tr.addClass('shown');
            }
        } );

        var dtBOM = $('#table-bom').DataTable({
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
                pageTotalQuantity = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                $( api.column( 5 ).footer() ).html('<div class="text-center">'+tnhFormatNumber(pageTotalQuantity)+'</div>');
            }
        });
	});
</script>