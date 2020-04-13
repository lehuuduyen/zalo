<?php echo form_open('admin/manufactures/created_productions_detail/'.$id, array('id'=>'created-detail')); ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">
				<?= lang('created_productions_detail') ?>
			</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
                    <div class="form-group">
                        <?= lang('tnh_status', 'status') ?>
                        <?= $productions_orders['status'] == 'un_approved' ? '<span class="label label-danger">'.lang($productions_orders['status']).'</span>' : '<span class="label label-success">'.lang($productions_orders['status']).'</span>' ?>
                    </div>
                </div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table class="tnh-table table-hover table-bordered">
							<thead>
								<tr>
									<th class="text-center" style="width: 50px;"><?= lang('tnh_numbers') ?></th>
									<th><?= lang('code') ?></th>
									<th><?= lang('name') ?></th>
									<th class="text-center"><?= lang('quantity') ?></th>
									<th style="width: 180px;">
										<?= lang('tnh_deadline') ?><span style="color:#fc2d6b;">*</span>
									</th>
									<th style="width: 180px;">
										<?= lang('departments') ?><span style="color:#fc2d6b;">*</span>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php $index = 0; ?>
								<?php foreach ($items as $key => $value): ?>
									<tr>
										<td class="text-center"><?= (++$key) ?></td>
										<td><?= $value['items_code'] ?></td>
										<td><?= $value['items_name'] ?></td>
										<td class="text-center"><?= formatNumber($value['quantity']) ?></td>
										<td>
											<input type="text" name="deadline[<?= $index ?>]" placeholder="<?= lang('tnh_deadline') ?>" id="deadline[<?= $index ?>]" class="form-control deadline datepicker" value="" required>
										</td>
										<td>
											<select name="departments[<?= $index ?>]" data-placeholder="<?= lang('departments') ?>" id="departments[<?= $index ?>]" class="departments modal-select2" required="required" style="width: 100%;">
												<option value=""></option>
												<?php foreach ($departments as $k => $val): ?>
													<option value="<?= $val['departmentid'] ?>"><?= $val['name'] ?></option>
												<?php endforeach ?>
											</select>
										</td>
									</tr>
									<?php $index++; ?>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="save" id="save" class="form-control" value="1">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
			<button type="submit" class="btn btn-primary add"><?= lang('save') ?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		init_datepicker();
		$('select.departments').select2();

		appValidateForm($('#created-detail'), {
        }, convert);

        function convert(form) {
        	$('.add').attr('disabled', 'disabled');
            var url = form.action;
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
            $.ajax({
            	url : url,
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
            		if (typeof oTable != 'undefined' && oTable != '') {
            			oTable.draw('page');
            		}
            		$('.modal-dialog .close').trigger('click');
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
<?php echo form_close(); ?>