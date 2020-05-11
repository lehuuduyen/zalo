<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?= css('tnh_core.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= css('tnh.css') ?>">
<?php echo form_open('admin/manufactures/add_productions_orders', array('id'=>'add-productions-orders')); ?>
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
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?= lang('info') ?></h3>
					</div>
					<div class="panel-body">
						<table class="tnh-tb table-bordered table-hover">
							<tbody>
								<tr>
									<td style="width: 10%;">
										<?= lang('tnh_reference_productions_orders', 'reference_no') ?>
									</td>
									<td>
										<div class="form-group">
											<div class="input-group">
												<span title="<?= lang('tnh_referesh') ?>" data-toggle="tooltip" class="input-group-addon btn btn-danger referesh-reference">
													<i class="fa fa-undo"></i>
												</span>
												<input type="text" name="reference_no" class="form-control" id="reference_no" value="<?= $reference_no ?>" readonly="" aria-invalid="false">
											</div>
										</div>
									</td>
									<td><?= lang('date', 'date') ?></td>
									<td>
										<?= form_input('date', set_value('date') ? set_value('date') : date('d/m/Y H:i'), 'id="date" class="form-control datetimepicker" placeholder="'.lang('date').'" required ') ?>
									</td>
								</tr>
								<tr>
									<td><?= lang('tnh_location', 'location') ?></td>
									<td colspan="3">
										<div class="form-group">
											<select name="location" id="location" class="form-control selectpicker" data-language="vi_VN" data-live-search="true" data-none-selected-text="<?= lang('tnh_location') ?>" required="required">
												<option value=""></option>
												<?php foreach ($locations as $key => $value): ?>
													<option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
												<?php endforeach ?>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<?= lang('productions_plan', 'productions_plan') ?>
									</td>
									<td colspan="3">
										<input type="text" name="productions_plan_id" id="productions_plan" class="productions_plan_id" data-placeholder="<?= lang('choose') ?>" style="width: 100%;" value="" title="">
									</td>
								</tr>
								<tr>
									<td><?= lang('note', 'note') ?></td>
									<td colspan="3">
										<textarea name="note" id="note" class="form-control" rows="3"></textarea>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-info" style="min-height: auto; margin-bottom: 100px;">
					<div class="panel-heading">
						<h3 class="panel-title"><?= lang('cong_info_items') ?></h3>
					</div>
					<div class="panel-body">
						<div class="tb-height">
							<table id="tb-productions-orders" class="dt-tnh table table-bordered table-hover" style="width: 100%;">
								<thead>
									<tr>
										<th class="text-center" style="width: 5%;">
											<a class="btn btn-info btn-icon add-row"><i class="fa fa-plus"></i></a>
		                                </th>
		                                <th style="width: 20%;"><?= lang('tnh_product_code') ?></th>
		                                <th style="width: 10%;"><?= lang('tnh_images') ?></th>
		                                <th style="width: 20%;"><?= lang('tnh_product_name') ?></th>
		                                <th style="width: 20%;"><?= lang('quantity') ?></th>
		                                <th style="width: 20%;"><?= lang('note') ?></th>
		                                <th style="width: 5%;"><?= lang('actions') ?></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
								<tfoot>
									<tr>
										<th class="text-center"><a class="btn btn-info btn-icon add-row-foot"><i class="fa fa-plus"></i></a></th>
										<th></th>
										<th></th>
										<th></th>
										<th class="th-total-quantity text-center"></th>
										<th></th>
										<th></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
				<input type="hidden" name="add" id="" class="form-control" value="1">
				<button type="submit" class="btn btn-info only-save customer-form-submiter add">
					<?php echo _l( 'submit'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<?php init_tail(); ?>

<script type="text/javascript">
	var site = <?= json_encode(array('base_url' => base_url())) ?>;
	var token = "<?= $this->security->get_csrf_token_name() ?>";
	var hash = "<?= $this->security->get_csrf_hash() ?>";
	var edit = 0;
	var counter = 0;
	var count_errors = 0;
	var arr_productions_plan_id = [];
	// window.open('http://192.168.1.57/ERP_2019/admin/clients/client','winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=350');
</script>

<script type="text/javascript" src="<?= js('productions_orders.js?vs=1.1') ?>"></script>