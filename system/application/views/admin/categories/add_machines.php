<?php echo form_open('admin/categories/add_machines',array('id'=>'add-machines')); ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= _l('tnh_add_machines'); ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?= lang('tnh_machine_code', 'code') ?>
						<?php echo form_input('code', (isset($_POST['code']) ? $_POST['code'] : ''), 'placeholder="'.lang('code').'" id="code" required class="form-control input-tip"'); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang('tnh_machine_name', 'name') ?>
						<?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : ''), 'placeholder="'.lang('name').'" id="name" required class="form-control input-tip"'); ?>
					</div>
				</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('tnh_product_in_month', 'product_in_month') ?>
                        <?php echo form_input('product_in_month', (isset($_POST['product_in_month']) ? $_POST['product_in_month'] : ''), 'placeholder="'.lang('tnh_product_in_month').'" id="product_in_month" onkeyup="formatNumBerKeyUpCus(this)" class="form-control input-tip"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('tnh_status', 'status') ?>
                        <select name="status" id="status" data-none-selected-text="<?= lang('tnh_status') ?>" class="form-control status" required="required">
                            <option value=""></option>
                            <?php foreach (status_machine() as $key => $value): ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('tnh_efficiency_coefficient', 'efficiency_coefficient') ?>
                        <?php echo form_input('efficiency_coefficient', (isset($_POST['efficiency_coefficient']) ? $_POST['efficiency_coefficient'] : ''), 'placeholder="'.lang('tnh_efficiency_coefficient').'" id="product_in_month" onkeyup="formatNumBerKeyUpCus(this)" class="form-control input-tip"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('tnh_capacity_cycle', 'capacity_cycle') ?>
                        <?php echo form_input('capacity_cycle', (isset($_POST['capacity_cycle']) ? $_POST['capacity_cycle'] : ''), 'placeholder="'.lang('tnh_capacity_cycle').'" id="product_in_month" onkeyup="formatNumBerKeyUpCus(this)" class="form-control input-tip"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('tnh_time_cycle', 'time_cycle') ?>
                        <?php echo form_input('time_cycle', (isset($_POST['time_cycle']) ? $_POST['time_cycle'] : ''), 'placeholder="'.lang('tnh_time_cycle').'" id="time_cycle" onkeyup="formatNumBerKeyUpCus(this)" class="form-control input-tip"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('tnh_time_before_produce', 'time_before_produce') ?>
                        <?php echo form_input('time_before_produce', (isset($_POST['time_before_produce']) ? $_POST['time_before_produce'] : ''), 'placeholder="'.lang('tnh_time_before_produce').'" id="time_before_produce" onkeyup="formatNumBerKeyUpCus(this)" class="form-control input-tip"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('tnh_time_after_produce', 'time_after_produce') ?>
                        <?php echo form_input('time_after_produce', (isset($_POST['time_after_produce']) ? $_POST['time_after_produce'] : ''), 'placeholder="'.lang('tnh_time_after_produce').'" id="time_after_produce" onkeyup="formatNumBerKeyUpCus(this)" class="form-control input-tip"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('tnh_cost_hour', 'cost_hour') ?>
                        <?php echo form_input('cost_hour', (isset($_POST['cost_hour']) ? $_POST['cost_hour'] : ''), 'placeholder="'.lang('tnh_cost_hour').'" id="cost_hour" onkeyup="formatNumBerKeyUpCus(this)" class="form-control input-tip"'); ?>
                    </div>
                </div>
			</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('tnh_specifications', 'specifications') ?>
                        <?php echo form_textarea('specifications', (isset($_POST['specifications']) ? $_POST['specifications'] : ''), 'placeholder="'.lang('tnh_specifications').'" id="specifications" class="form-control input-tip tinymce"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang('note', 'note') ?>
                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ''), 'placeholder="'.lang('note').'" id="note" class="form-control input-tip tinymce"'); ?>
                    </div>
                </div>
            </div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
			<button type="submit" class="btn btn-primary add"><?= _l('add') ?></button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script>
    $(function(){

       	appValidateForm($('#add-machines'), {
           code: 'required',
           name: 'required',
           status: 'required'
        }, addMachines);

        function addMachines(form) {
        	$('.add').attr('disabled', 'disabled');
            tinymce.get('note').save();
            tinymce.get('specifications').save();
            var data = $(form).serialize();
            var url = form.action;
            $.ajax({
            	url: site.base_url+'admin/categories/add_machines',
            	type: 'POST',
            	dataType: 'JSON',
            	data: data,
            })
            .done(function(data) {
            	if (data.result) {
            		alert_float('success', data.message);
            		if (typeof oTable != 'undefined') {
            			oTable.draw();
            		}
            		$('.modal-dialog .close').trigger('click');
            	} else {
            		alert_float('danger', data.message);
            		$('.add').removeAttr('disabled', 'disabled');
            	}
            })
            .fail(function() {
            	console.log("error");
            });
            return false;
        }
        $('.status').selectpicker();
        init_editor('textarea[name="note"]');
        init_editor('textarea[name="specifications"]');
    })
</script>