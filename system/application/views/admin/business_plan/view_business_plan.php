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
							<span class="bold font-medium-xs lead-name"><?= _dt($business_plan['date']) ?></span>
						</div>
						<div class="wap-content second">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_reference_business_plan') ?>: </span>
							<span class="bold font-medium-xs lead-name"><?= $business_plan['reference_no'] ?></span>
						</div>
						<div class="wap-content firt">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_status') ?>: </span>
							<span class="bold font-medium-xs lead-name"><?= lang($business_plan['status']) ?></span>
						</div>
						<div class="wap-content second">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('tnh_user_agree') ?>: </span>
							<span class="bold font-medium-xs lead-name">
								<?php if ($business_plan['status'] == "approved"): ?>
									<?= $user_status ?>
								<?php endif ?>
							</span>
						</div>
						<div class="wap-content firt">
							<span class="text-muted lead-field-heading no-mtop bold"><?= lang('note') ?>: </span>
							<span class="bold font-medium-xs lead-name"><?= $business_plan['note'] ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-12 mtop10">
					<div class="table-responsive">
						<table class="tnh-table dt-table table-bordered table-hover">
							<thead>
								<tr>
									<th><?= lang('tnh_numbers') ?></th>
									<th><?= lang('tnh_images') ?></th>
									<th><?= lang('tnh_product_code') ?></th>
									<th><?= lang('tnh_product_name') ?></th>
									<th><?= lang('quantity') ?></th>
									<th><?= lang('date') ?></th>
									<th><?= lang('note') ?></th>
								</tr>
							</thead>
							<tbody>
								<?= $tr_html ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-6 pull-right mtop10">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-user"></i> <?= lang('tnh_user_created') ?></h3>
						</div>
						<div class="panel-body">
							<div><?= lang('tnh_created_by') ?>: <?= $created_by ?></div>
							<div><?= lang('tnh_date_creted') ?>: <?= _dt($business_plan['date_created']) ?></div>
							<?php if (!empty($updated_by)): ?>
								<hr/ style="margin: 5px;">
								<div><?= lang('tnh_updated_by') ?>: <?= $updated_by ?></div>
								<div><?= lang('tnh_date_updated') ?>: <?= _dt($business_plan['date_updated']) ?></div>
							<?php endif ?>
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
