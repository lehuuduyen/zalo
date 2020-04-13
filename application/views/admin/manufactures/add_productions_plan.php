<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?= css('tnh_core.css') ?>">
<?php echo form_open('admin/manufactures/add_productions_plan', array('id'=>'add-productions-plan')); ?>
<div id="wrapper">
	<div class="panel_s mbot10 H_scroll" id="">
        <div class="panel-body _buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
        	<?= $this->load->view('admin/breadcrumb') ?>
        </div>
    </div>
	<div class="content">
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><?= lang('info') ?></h3>
				</div>
				<div class="panel-body">
					<table class="tnh-tb table-bordered table-hover">
						<tbody>
							<tr>
								<td>
									<?= lang('tnh_reference_productions_plan', 'reference_no') ?>
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
								<td><?= lang('tnh_planning_cycle', 'planning_cycle') ?></td>
								<td colspan="3"><?= form_input('planning_cycle', set_value('date') ? set_value('date') : date('d/m/Y H:i'), 'id="planning_cycle" class="form-control dateranger" placeholder="'.lang('tnh_planning_cycle').'" required') ?></td>
							</tr>
							<tr>
								<td><?= lang('tnh_applied_standard', 'applied_standard') ?></td>
								<td>
									<div class="checkbox checkbox-info" style="margin: auto;">
										<input type="checkbox" <?= set_value('safe_inventory') == 1 ? 'checked' : '' ?> id="safe_inventory" name="safe_inventory" value="1">
										<label for="safe_inventory"><?= lang('tnh_safe_inventory') ?></label>
									</div>
								</td>
								<td><?= lang('tnh_options') ?></td>
								<td>
									<fieldset id="options">
										<div class="checkbox checkbox-info cbobox">
											<input type="checkbox" <?= set_value('options1') == 1 ? 'checked' : '' ?> class="rel_type" name="options1" value="1" id="options1">
											<label for="options1"><?= lang('tnh_sales_orders') ?></label>
										</div>
										<div class="checkbox checkbox-info cbobox">
											<input type="checkbox" <?= set_value('options2') == 1 ? 'checked' : '' ?> class="rel_type" name="options2" value="1" id="options2">
											<label for="options2"><?= lang('tnh_business_plan') ?></label>
										</div>
									</fieldset>
									<div class="text-danger"><?= lang('note') ?>: (<?= lang('tnh_only_pick_approved') ?>)</div>
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
					<div class="">
						<button type="button" class="btn btn-danger mtop10 mbot10 btn-view"><?= lang('tnh_preview') ?></button>
					</div>
					<div class="show-table-productions-plan">
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
<script type="text/javascript" src="<?= base_url('assets/plugins/bootbox/bootbox.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/bootbox/bootbox.locales.min.js') ?>"></script>
<script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>

<link rel="stylesheet" type="text/css" href="<?= css('daterangepicker.css') ?>" />
<script type="text/javascript">
	var site = <?= json_encode(array('base_url' => base_url())) ?>;
	var token = "<?= $this->security->get_csrf_token_name() ?>";
	var hash = "<?= $this->security->get_csrf_hash() ?>";
	$(document).ready(function() {
		init_editor('textarea[name="note"]');
		$(document).on('click', '.btn-view', function(e) {
			e.preventDefault();
			bootbox.confirm({
                message: '<?= lang('tnh_you_want_to_view') ?>',
                buttons: {
                    confirm: {
                        label: '<?= lang('yes') ?>',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: '<?= lang('no') ?>',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                	if (result) {
                		form = $('#add-productions-plan');
	                	data = form.serialize();
	                	$.ajax({
	                		url: site.base_url+'admin/manufactures/show_table_productions_plan',
	                		type: 'GET',
	                		dataType: 'html',
	                		data: data,
	                	})
	                	.done(function(response) {
	                		$('.show-table-productions-plan').html(response);
	                	})
	                	.fail(function() {
	                		console.log("error");
	                	});
                	}
                }
            });
		});

		$(document).on('click', '.referesh-reference', function(event) {
			event.preventDefault();
			$.ajax({
				url: site.base_url+'admin/manufactures/refereshReference',
				type: 'GET',
				dataType: 'JSON',
				data: {
					token: hash,
					'referesh': 1
				},
			})
			.done(function(data) {
				if (data) {
					$('#reference_no').val(data.reference_no);
					alert_float('success', data.message);
				} else {
					alert_float('danger', '<?= lang('tnh_referesh_error') ?>');
				}
			})
			.fail(function() {
				console.log("error");
			});
		});

		appValidateForm($('#add-productions-plan'), {
			reference_no: 'required',
           	date: 'required',
           	planning_cycle: 'required',
        }, add);

        function add(form) {
        	$('.add').attr('disabled', 'disabled');
            tinymce.get('note').save();
            // var data = $(form).serialize();
            var form = $(form),
                formData = new FormData(),
                formParams = form.serializeArray();

            $.each(form.find('input[type="file"]'), function(i, tag) {
                $.each($(tag)[0].files, function(i, file) {
                    formData.append(tag.name, file);
                });
            });
            $.each(formParams, function(i, val) {
                formData.append(val.name, val.value);
            });
            //
            var url = form.action;
            $.ajax({
            	url : site.base_url+'admin/manufactures/add_productions_plan',
            	type : 'POST',
            	dataType: 'JSON',
                cache : false,
                contentType : false,
                processData : false,
            	data: formData,
            })
            .done(function(data) {
            	if (data.result) {
            		alert_float('success', data.message);
            		window.location.href = site.base_url+'admin/manufactures/productions_plan';
            	} else {
            		alert_float('danger', data.message);
            		$('.add').removeAttr('disabled', 'disabled');
            	}
            })
            .fail(function() {
                alert_float('danger', 'error');
            	$('.add').removeAttr('disabled', 'disabled');
            });
            return false;
        }


	});
</script>