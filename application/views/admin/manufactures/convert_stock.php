<?php echo form_open('admin/manufactures/convert_stock/'.$id, array('id'=>'add-purchase')); ?>
<div class="modal-dialog modal-lg" style="width: 70%;">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= lang('tnh_convert_to_export_stock') ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
	                <div class="form-group">
	                    <?= lang('tnh_warehouses', 'warehouses') ?>
	                    <select name="warehouses" data-placeholder="<?= lang('tnh_warehouses') ?>" id="warehouses" required="required" style="width: 100%;">
	                    	<option value=""></option>
	                    	<?php foreach ($warehouses as $key => $value): ?>
	                    		<option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
	                    	<?php endforeach ?>
	                    </select>
	                </div>
	            </div>
	            <div class="col-md-12">
	            	<div class="table-responsive">
	            		<table id="cs-table" class="tnh-table table-hover table-condensed table-bordered">
	            			<thead>
	            				<tr>
	            					<th class="text-center" style="width: 5%;">
	            						#
	            					</th>
	            					<th style="width: 15%;"><span class="red">*</span><?= lang('tnh_material_code') ?></th>
	            					<th style="width: 15%;"><?= lang('tnh_material_name') ?></th>
	            					<th style="width: 5%;"><?= lang('tnh_unit') ?></th>
	            					<th style="width: 15%;"><span class="red">*</span><?= lang('tnh_location_warehouse') ?></th>
	            					<th class="text-center" style="width: 15%;"><?= lang('tnh_quantity_export') ?></th>
	            					<th class="text-center" style="width: 15%;"><?= lang('tnh_value_exchange') ?></th>
	            					<th class="text-center" style="width: 10%;"><?= lang('tnh_quantity_exchange') ?></th>
	            				</tr>
	            			</thead>
	            			<tbody>
	            				<?php $counter = 0; ?>
	            				<?php foreach ($suggest_exporting_items as $key => $value): ?>
	            				<tr value="<?= $value['id'] ?>">
	            					<input type="hidden" name="counter[]" id="input" class="form-control" value="<?= $counter ?>">
	            					<input type="hidden" name="suggest_exporting_items_id[<?= $counter ?>]" id="input" class="form-control" value="<?= $value['id'] ?>">
	            					<td class="text-center"><?= ++$key ?></td>
	            					<td><?= $value['item_code'] ?></td>
	            					<td><?= $value['item_name'] ?></td>
	            					<td><?= $value['unit_name'] ?></td>
	            					<td>
	            						<select name="locations[<?= $counter ?>]" data-placeholder="<?= lang('choose') ?>" id="locations[<?= $counter ?>]" class="locations" required style="width: 180px;">
	            							<option></option>
	            						</select>
	            					</td>
	            					<td class="text-center"><?= formatNumber($value['quantity_export']) ?></td>
	            					<td class="text-center"><?= formatNumber($value['number_exchange']) ?></td>
	            					<td class="text-center"><?= formatNumber($value['quantity_exchange']) ?></td>
	            				</tr>
	            				<?php $counter++; ?>
	            				<?php endforeach ?>
	            			</tbody>
	            		</table>
	            	</div>
	            </div>
            </div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="save" id="save" class="form-control" value="1">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
			<button type="submit" class="btn btn-primary add"><?= _l('save') ?></button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	var validation = {'warehouses': 'required'};
	var counter = <?= $counter ? $counter : 0 ?>;
	function getLocations(locations) {
		var option = '<option value=""></option>';
		$.each(locations, function(index, el) {
			option+= '<option value="'+el.location_id+'">'+el.location_name+'</option>';
		});
		return option;
	}
	$(document).ready(function() {
		$('#warehouses').select2();
		$('select.locations').select2();

		if (counter >= 0) {
			for (i = 0; i <= counter; i++) {
				validation['locations['+i+']'] = 'required';
			}
		}

		$('#warehouses').change(function(event) {
			warehouse_id = $(this).val();
			$('select.locations').val(null).trigger('change');
			$('select.locations').html('');
			if (warehouse_id) {
				$.ajax({
					url: site.base_url+'admin/manufactures/getLocationsForItems',
					type: 'POST',
					dataType: 'json',
					data: {
						"<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
						warehouse_id: warehouse_id,
						id: <?= $id ?>

					},
				})
				.done(function(data) {
					if (data) {
						$.each(data.locations, function(index, el) {
							$('#cs-table').find('tr[value="'+index+'"] select.locations').html(getLocations(el));
						});
					}
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
			}
		});

		appValidateForm($('#add-purchase'), validation, convert);

        function convert(form) {
        	$('.add').attr('disabled', 'disabled');
            var url = form.action;
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
            			oTable.draw();
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