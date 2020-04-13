<?php echo form_open('admin/manufactures/export_supplies/'.$id, array('id'=>'export-supplies')); ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= lang('tnh_requrest_export_of_supplies') ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
                        <?= lang('date', 'date') ?>
                        <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y H:i')), 'placeholder="'.lang('date').'" id="date" required class="form-control input-tip datetimepicker"'); ?>
                    </div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang('tnh_export_name', 'export_name') ?>
						<?php echo form_input('export_name', (isset($_POST['export_name']) ? $_POST['export_name'] : ''), 'placeholder="'.lang('tnh_export_name').'" id="export_name" required class="form-control input-tip"'); ?>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<?= lang('note', 'note') ?>
						<textarea name="note" id="note" placeholder="<?= lang('note') ?>" class="form-control" rows="3"></textarea>
					</div>
				</div>
				<div class="col-md-12">
					<p class="text-danger">*<?= lang('tnh_only_save_quantity_bigger_zero') ?></p>
					<div class="table-responsive">
						<table class="tnh-table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center"><?= lang('tnh_numbers') ?></th>
									<th><?= lang('tnh_material_code') ?></th>
									<th><?= lang('tnh_material_name') ?></th>
									<th class="text-center"><?= lang('tnh_unit') ?></th>
                                    <th class="text-center" style="width: 180px;"><?= lang('tnh_quantity_export') ?></th>
									<th class="text-center"><?= lang('tnh_value_exchange') ?></th>
									<th class="text-center"><?= lang('tnh_quantity_exchange') ?></th>
								</tr>
							</thead>
							<tbody>
                                <?php foreach ($materials as $key => $value): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?= ++$key ?>
                                            <input type="hidden" name="item_id[]" id="item_id[]" class="form-control" value="<?= $value['item_id'] ?>">
                                            <input type="hidden" name="unit_id[]" id="unit_id[]" class="form-control" value="<?= $value['unit_id'] ?>">
                                            <input type="hidden" name="type_item[]" id="type_item[]" class="form-control" value="<?= $value['type_item'] ?>">
                                            <input type="hidden" name="unit_parent_id[]" id="unit_parent_id[]" class="form-control" value="<?= $value['unit_parent_id'] ?>">
                                            <input type="hidden" name="quantity_exchange[]" id="quantity_exchange[]" class="form-control quantity_exchange" value="<?= $value['quantity_exchange'] ?>">
                                        </td>
                                        <td>
                                            <div><?= $value['item_code'] ?></div>
                                            <?php if ($value['type_item'] == 'semi_products_outside'): ?>
                                                <div style="margin-bottom: 5px;"></div>
                                                <div class="label label-danger" style="margin-top: 5px;"><?= lang('semi_products_outside') ?></div>
                                            <?php endif ?>
                                        </td>
                                        <td><?= $value['item_name'] ?></td>
                                        <td class="text-center"><?= $value['unit_name'] ?></td>
                                        <td>
                                            <input type="number" name="quantity_export[]" id="quantity_export[]" class="form-control quantity_export" value="0">
                                        </td>
                                        <td class="text-center"><?= $value['quantity_exchange'] ?></td>
                                        <td class="text-center quantity-primary">0</td>
                                    </tr>
                                <?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="save" class="form-control" value="1">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
			<button type="sumit" name="add" class="btn btn-primary add"><?= lang('save') ?></button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script>
    $(function(){
        init_datepicker();
        // init_editor('#note');
        // $('#preview-purchase').DataTable({
        //     "language": app.lang.datatables,
        //     "pageLength": intVal(app.options.tables_pagination_limit),
        //     "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
        //     // 'searching': true,
        //     // 'ordering': true,
        //     // 'paging': true,
        //     // "info": true,
        //     "initComplete": function(settings, json) {
        //         var t = this;
        //         t.parents('.table-loading').removeClass('table-loading');
        //         t.removeClass('dt-table-loading');
        //         mainWrapperHeightFix();
        //     },
        // });

        $('.quantity_export').change(function(event) {
            event.preventDefault();
            tr_current = $(this).closest('tr');
            quantity_export = intVal($(this).val());
            quantity_exchange = intVal(tr_current.find('.quantity_exchange').val());
            quantity_primary = quantity_export/quantity_exchange;
            tr_current.find('.quantity-primary').html(tnhFormatNumber(quantity_primary));
        });

       	appValidateForm($('#export-supplies'), {
            'date': 'required',
            'export_name': 'required'
        }, convert);

        function convert(form) {
        	$('.add').attr('disabled', 'disabled');
        	var url = form.action;
        	for (var i = 0; i < tinymce.editors.length; i++) {
        		tinymce.editors[i].save();
        	}

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
            		if (typeof dtSuggest != 'undefined' && dtSuggest != '') {
            			dtSuggest.draw();
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
    })
</script>