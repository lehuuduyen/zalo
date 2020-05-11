<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= lang('view') ?></h4>
		</div>
		<div class="modal-body">
			<div role="tabpanel">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#home33" aria-controls="home" role="tab" data-toggle="tab"><?= lang('info') ?></a>
					</li>
					<li role="presentation">
						<a href="#tab33" aria-controls="tab" role="tab" data-toggle="tab"><?= lang('tnh_supplies') ?></a>
					</li>
					<li role="presentation">
						<a href="#tab333" aria-controls="tab" role="tab" data-toggle="tab"><?= lang('tnh_warehouses') ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="home33">
						<div class="row">
							<div class="col-md-12">
								<div class="lead-view" id="leadViewWrapper">
									<div class="col-md-6 col-xs-6 lead-information-col mbot10">
										<div class="wap-content firt">
											<span class="bold font-medium-xs mbot15">
												<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_images_represent') ?>: </span>
												<?php $images = ($material['images'] != null) ? pathMaterial($material['images']) : base_url("assets/images/preview-not-available.jpg"); ?>
												<span class="bold font-medium-xs lead-name">
													<div class="preview_image" style="width: auto;">
														<div class="display-block contract-attachment-wrapper img">
															<div style="width:45px;">
																<a href="<?= $images ?>" data-lightbox="customer-profile" class="display-block mbot5">
																	<div class="">
																		<img src="<?= $images ?>" style="border-radius: 50%" />
																	</div>
																</a>
															</div>
														</div>
													</div>
												</span>
											</span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('category') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $category['name'] ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_material_code_system') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= $material['code_system'] ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_material_code') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= $material['code'] ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_material_name') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= $material['name'] ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_material_name_customer') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= $material['name_customer'] ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_material_name_supplier') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= $material['name_supplier'] ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_price_import') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatMoney($material['price_import']) ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_price_sell') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatMoney($material['price_sell']) ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_quantity_minimum') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($material['quantity_minimum']) ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_quantity_maximum') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($material['quantity_maximum']) ?></span>
										</div>
									</div>
									<div class="lead-view" id="leadViewWrapper">
										<div class="col-md-6 col-xs-6 lead-information-col mbot10">
											<div class="wap-content firt">
												<span class="bold font-medium-xs mbot15">
													<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_images_multiple') ?>: </span>
													<span class="bold font-medium-xs lead-name">
														<?php if (!empty($material['images_multiple'])): ?>
															<div class="preview_image" style="width: auto; display: flex">
																<?php foreach (explode('||', $material['images_multiple']) as $key => $value): ?>
																	<?php $images_multiple = !empty($value) ? pathMaterial($value) : base_url("assets/images/preview-not-available.jpg"); ?>
																	<div class="display-block contract-attachment-wrapper img">
																		<div style="width:45px;">
																			<a href="<?= $images_multiple ?>" data-lightbox="customer-profile" class="display-block mbot5">
																				<div class="">
																					<img src="<?= $images_multiple ?>" style="border-radius: 50%" />
																				</div>
																			</a>
																		</div>
																	</div>
																<?php endforeach ?>
															</div>
														<?php endif ?>
													</span>
												</span>
											</div>
											<?php if (!empty($custom_fields)): ?>
												<?php foreach ($custom_fields as $key => $value): ?>
													<div class="wap-content <?= ($key % 2 == 0) ? 'firt' : 'second' ?>">
														<span class="text-muted lead-field-heading no-mtop"><?= $value['name'] ?>: </span>
														<span class="bold font-medium-xs mbot15"><?= get_custom_field_value($material['id'], $value['id'], $value['fieldto']) ?></span>
													</div>
												<?php endforeach ?>
											<?php endif ?>
											<div class="wap-content second">
												<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_unit') ?>: </span>
												<span class="bold font-medium-xs mbot15"><?= $unit['unit'] ?></span>
											</div>
											<div class="wap-content firt">
												<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_exchange') ?>: </span>
												<table class="tnh-tb table-exchange table-bordered table-hover">
													<thead>
														<tr>
															<th style="width: 80px; text-align: center;">
																#
															</th>
															<th class="text-center"><?= lang('unit') ?></th>
															<th class="text-center" style="width: 150px;"><?= lang('quantity') ?></th>
														</tr>
													</thead>
													<tbody>
														<?php if (!empty($exchanges)): ?>
															<?php foreach ($exchanges as $key => $value): ?>
																<tr>
																	<td class="text-center"><?= ++$key ?></td>
																	<td class="text-center"><?= $value['unit_name'] ?></td>
																	<td class="text-center"><?= $value['number_exchange'] ?></td>
																</tr>
															<?php endforeach ?>
														<?php endif ?>
													</tbody>
												</table>
											</div>
										</div>
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
			                                <div><?= lang('tnh_date_creted') ?>: <?= _dt($material['date_created']) ?></div>
			                            </div>
			                            <div class="col-md-6">
			                                <?php if (!empty($updated_by)): ?>
			                                    <div><?= lang('tnh_updated_by') ?>: <?= $updated_by ?></div>
			                                    <div><?= lang('tnh_date_updated') ?>: <?= _dt($material['date_updated']) ?></div>
			                                <?php endif ?>
			                            </div>
			                        </div>
			                    </div>
			                </div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="tab33">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="tnh-table table-bordered table-condensed table-hover" style="width: 100%;">
										<thead>
											<tr>
												<th class="text-center" style="width: 80px;"><?= lang('tnh_numbers') ?></th>
												<th style="width: 200px;"><?= lang('tnh_supplies') ?></th>
												<th><?= lang('tnh_leadtime') ?></th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($suppliers)): ?>
											<?php foreach ($suppliers as $key => $value): ?>
												<?php $leadtimes = $this->items_model->getMaterialSuppliersByMaterialAndSupplier($id, $value['supplier_id']) ?>
												<tr>
													<td class="text-center"><?= (++$key) ?></td>
													<td><?= $value['supplier_company'] ?></td>
													<td>
														<!-- <ul class="progressbar" style="display: flex;flex-direction: row;justify-content: center;"> -->
														<ul class="progressbar">
															<?php if (!empty($leadtimes)): ?>
															<?php foreach ($leadtimes as $k => $val): ?>
															<li class="active">
																<p class="pointer li_pad10"><?= $val['procedure_detail_name'] ?> (<?= $val['number_date'] ?>)</p>
															</li>
															<?php endforeach ?>
															<?php endif ?>
														</ul>
													</td>
												</tr>
											<?php endforeach ?>
											<?php endif ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="tab333">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="tnh-table table-bordered table-condensed table-hover" style="width: 100%;">
										<thead>
											<tr>
												<th class="text-center" style="width: 80px;"><?= lang('tnh_numbers') ?></th>
												<th><?= lang('tnh_warehouses') ?></th>
												<th><?= lang('tnh_vt') ?></th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($warehouses)): ?>
												<?php foreach ($warehouses as $key => $value): ?>
													<tr>
														<td class="text-center"><?= ++$key ?></td>
														<td><?= $value['warehouse_name'] ?></td>
														<td><?= $value['location_name'] ?></td>
													</tr>
												<?php endforeach ?>
											<?php endif ?>
										</tbody>
									</table>
								</div>
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
