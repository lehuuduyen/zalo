<div class="modal-dialog modal-lg" style="width: 70%;">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= $product['name'] ?></h4>
		</div>
		<div class="modal-body" style="padding-top: 0;">
			<div class="row">
				<div class="">
					<div role="tabpanel">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
								<a href="#info" aria-controls="info" role="tab" data-toggle="tab"><?= lang('info') ?></a>
							</li>
							<li role="presentation">
								<a href="#tab-supplies" aria-controls="tab" role="tab" data-toggle="tab"><?= lang('tnh_supplies') ?></a>
							</li>
							<li role="presentation">
								<a href="#tab-warehouses" aria-controls="tab" role="tab" data-toggle="tab"><?= lang('tnh_warehouses') ?></a>
							</li>
							<?php if ($product['type_products'] != 'semi_products_outside'): ?>
							<li role="presentation">
								<a href="#BOM" aria-controls="BOM" role="tab" data-toggle="tab"><?= lang('BOM') ?></a>
							</li>
							<li role="presentation">
								<a href="#tab-stages" aria-controls="tab-stages" role="tab" data-toggle="tab"><?= lang('stages') ?></a>
							</li>
							<?php endif ?>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="info">
								<div class="col-md-12">
									<a class="tnh-modal pull-right" data-tnh="modal" data-toggle="modal" data-target="#myModal" href="<?= base_url('admin/products/edit_product/'.$id) ?>"><i class="fa fa-pencil width-icon-actions"></i> <?= lang('edit') ?></a>
								</div>
								<div class="lead-view" id="leadViewWrapper">
									<div class="col-md-4 col-xs-4 lead-information-col mbot10">
										<div class="wap-content second">
											<span class="bold font-medium-xs mbot15">
												<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_images_represent') ?>: </span>
												<?php $images = ($product['images'] != null) ? base_url("uploads/products/".$product['images']) : base_url("assets/images/preview-not-available.jpg"); ?>
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
										</div>
										<div class="wap-content firt">
											<span class="bold font-medium-xs mbot15">
												<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_images_multiple') ?>: </span>
												<span class="bold font-medium-xs lead-name">
													<?php if (!empty($product['images_multiple'])): ?>
														<div class="preview_image" style="width: auto; display: flex">
															<?php foreach (explode('||', $product['images_multiple']) as $key => $value): ?>
																<?php $images_multiple = !empty($value) ? pathProduct($value) : base_url("assets/images/preview-not-available.jpg"); ?>
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
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('category') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $product['category_name'] ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_type_products') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= lang($product['type_products']) ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_code_system') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $product['code_system'] ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('code') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $product['code'] ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('name') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= $product['name'] ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('unit') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $unit['unit'] ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('colors') ?>: </span>
											<span class="bold font-medium-xs mbot15">
												<?php foreach ($colors as $key => $value): ?>
													<?= $value['color_name'] ?>
													<?php if ($key != (count($colors) - 1)): ?>
														|
													<?php endif ?>
												<?php endforeach ?>
											</span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_mode') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $product['mode'] ?></span>
										</div>
									</div>
									<div class="col-md-4 col-xs-4 lead-information-col mbot10">
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_type_dt') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $dt['name'] ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_type_kt') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $kt['name'] ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_type_gender') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $product['type_gender'] == 1 ? lang('cong_male') : ($product['type_gender'] == 2 ? lang('cong_female') : lang('all')) ?></span>
										</div>
										<?php if(!empty($info_client)){?>
                                            <?php
                                                foreach($info_client as $key => $value) {?>
                                                    <div class="wap-content <?= $key % 2 == 0 ? 'second' : 'firt' ?>">
                                                        <span class="text-muted lead-field-heading no-mtop bold"><?= $value['name'] ?>: </span>
                                                        <span class="bold font-medium-xs lead-name"><?= $value['detail']->value_name ?></span>
                                                    </div>
                                            <?php } ?>
                                        <?php } ?>
										<!-- <div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_type_wish') ?>: </span>
											<span class="bold font-medium-xs lead-name"></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_consultation_purpose') ?>: </span>
											<span class="bold font-medium-xs lead-name"></span>
										</div> -->
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_size') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $product['size'] ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_weight') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $product['weight'] ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_structure') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $product['structure'] ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_description') ?>: </span>
											<span class="bold font-medium-xs lead-name"><?= $product['description'] ?></span>
										</div>
									</div>
									<div class="col-md-4 col-xs-4 lead-information-col mbot10">
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_price_import') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($product['price_import']) ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_price_sell') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($product['price_sell']) ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_price_domestic') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($product['price_domestic']) ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_price_foreign') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($product['price_foreign']) ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_quantity_minimum') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($product['quantity_minimum']) ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_quantity_max') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($product['quantity_max']) ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_price_processing') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($product['price_processing']) ?></span>
										</div>
										<div class="wap-content second">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('tnh_number_hours_ap') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= formatNumber($product['number_hours_ap']) ?></span>
										</div>
										<div class="wap-content firt">
											<span class="text-muted lead-field-heading no-mtop"><?= lang('note') ?>: </span>
											<span class="bold font-medium-xs mbot15"><?= $product['note'] ?></span>
										</div>
										<?php if (!empty($custom_fields)): ?>
											<?php foreach ($custom_fields as $key => $value): ?>
												<div class="wap-content <?= ($key % 2 == 0) ? 'firt' : 'second' ?>">
													<span class="text-muted lead-field-heading no-mtop"><?= $value['name'] ?>: </span>
													<span class="bold font-medium-xs mbot15"><?= get_custom_field_value($product['id'], $value['id'], $value['fieldto']) ?></span>
												</div>
											<?php endforeach ?>
										<?php endif ?>
									</div>
									<div class="col-md-12">
										<div class="col-md-6 pull-right mtop10">
											<div class="panel panel-primary">
												<div class="panel-heading">
													<h3 class="panel-title"><i class="fa fa-user"></i> <?= lang('tnh_user_created') ?></h3>
												</div>
												<div class="panel-body">
													<div class="col-md-6">
														<div><?= lang('tnh_created_by') ?>: <?= $created_by ?></div>
														<div><?= lang('tnh_date_creted') ?>: <?= _dt($product['date_created']) ?></div>
													</div>
													<div class="col-md-6">
														<?php if (!empty($updated_by)): ?>
															<div><?= lang('tnh_updated_by') ?>: <?= $updated_by ?></div>
															<div><?= lang('tnh_date_updated') ?>: <?= _dt($product['date_updated']) ?></div>
														<?php endif ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab-supplies">
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
													<?php $leadtimes = $this->products_model->getProductSuppliersByProductAndSupplier($id, $value['supplier_id']) ?>
													<tr>
														<td class="text-center"><?= (++$key) ?></td>
														<td><?= $value['supplier_company'] ?></td>
														<td>
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
							<div role="tabpanel" class="tab-pane" id="tab-warehouses">
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
							<div role="tabpanel" class="tab-pane" id="BOM">
								<div class="col-md-12" style="max-height: 500px; overflow: auto;">
									<?= $BOM ?>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab-stages">
								<div class="col-md-12" style="max-height: 500px; overflow: auto;">
									<?= $html_stages ?>
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
<script type="text/javascript">
	$(document).ready(function() {
		// $('.cols').trigger('click');
		$('.tbbb').DataTable({
			"language": app.lang.datatables,
			"pageLength": app.options.tables_pagination_limit,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
		});
	});
</script>