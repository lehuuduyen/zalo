<?php echo form_open("admin/products/design_bom/$id/$bom_id/$actions", array('id'=>'add-category')); ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= lang('tnh_design_bom') ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<?= lang('tnh_versions', 'versions') ?>
						<?php echo form_input('versions', (isset($_POST['versions']) ? $_POST['versions'] : (!empty($bom['versions']) ? $bom['versions'] : '')), 'placeholder="'.lang('tnh_versions').'" id="versions" required class="form-control input-tip"'); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<?= lang('date_start', 'date_start') ?>
						<?php echo form_input('date_start', (isset($_POST['date_start']) ? $_POST['date_start'] : (!empty($bom['date_start']) ? _d($bom['date_start']) : '')), 'placeholder="'.lang('date_start').'" id="date_start" class="form-control input-tip datepicker"'); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<?= lang('date_end', 'date_end') ?>
						<?php echo form_input('date_end', (isset($_POST['date_end']) ? $_POST['date_end'] : (!empty($bom['date_end']) ? _d($bom['date_end']) : '')), 'placeholder="'.lang('date_end').'" id="date_end" class="form-control input-tip datepicker"'); ?>
					</div>
				</div>
				<input type="hidden" name="product_id" id="product_id" class="form-control product_id" value="<?= $id ?>">
				<div class="col-md-12">
					<div class="">
						<table class="table table-hover table-bordered table-condensed table-bom" style="margin: 0;">
							<thead>
								<tr style="background: #5cb0d5;">
									<th style="width: 50px;">
										<div class="text-center">
											<button type="button" class="btn btn-warning btn-icon btn-add-element"><i class="fa fa-plus"></i></button>
										</div>
									</th>
									<th colspan="2"><?= lang('tnh_element_name') ?></th>
									<th style="width: 150px;"><?= lang('unit') ?></th>
									<th style="width: 150px;"><?= lang('quantity') ?></th>
									<th style="width: 80px; text-align: center;"><i class="fa fa-trash-o"></i></th>
								</tr>
							</thead>
							<tbody>
								<?= !empty($html_BOM) ? $html_BOM : '' ?>
							</tbody>
							<tfoot>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
			<button type="submit" class="btn btn-primary"><?= lang('save') ?></button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    var lang_bom = <?= json_encode(array('tnh_element_name' => lang('tnh_element_name'), 'type' => lang('type'), 'choose' => lang('choose'))) ?>;
    var i = <?= !empty($count_i) ? $count_i : 0 ?>;
    var k = <?= !empty($count_k) ? $count_k : 0 ?>;
    var bom_id = <?= $bom_id ?>;
    var type_design_bom = <?= json_encode(type_design_bom($products['type_products'] == 'products' ? 'all' : 'not_all')) ?>;
    json['versions'] = 'required';
    <?php if (!empty($html_BOM)): ?>
    	$(document).ready(function() {
    		for (n = 0; n <= k; n++)
    		{
    			$('#type_design_bom_'+n).selectpicker();
    			json['type_design_bom_'+n] = 'required';
    			type_design_bom_current = $('#type_design_bom_'+n).val();
    			if (type_design_bom_current == "semi_products") {
    				ajaxSelectParamsCallback('#items_'+ n +'', 'admin/products/searchSelect2SemiProducts', $('#items_'+ n +'').val());
    				// selectAjax($('select#items_'+ n +''), false, 'admin/products/searchSemiProducts', 'products/searchSemiProducts');
    			} else if (type_design_bom_current == "semi_products_outside") {
    				ajaxSelectParamsCallback('#items_'+ n +'', 'admin/products/searchSelect2SemiProductsOutside', $('#items_'+ n +'').val());
    			} else {
    				ajaxSelectParamsCallback('#items_'+ n +'', 'admin/items/searchSelect2Materials', $('#items_'+ n +'').val());
    				// selectAjax($('select#items_'+ n +''), false, 'admin/items/searchMaterials', 'items/searchMaterials');
    			}
    			json['items_'+n] = 'required';
    		}
    		$('.units').select2();
    	});
    <?php endif ?>
    $(document).ready(function() {
    	init_datepicker();
    	appValidateForm($('#add-category'), json, addBOM);
	    function addBOM(form) {
	    	$('.add').attr('disabled', 'disabled');
	        product_id = $('#product_id').val();
	        if (!product_id) {
	            alert_float('danger', 'errors');
	            $('.add').removeAttr('disabled', 'disabled');
	            return;
	        }
	        var url = form.action;
	        var data = $(form).serialize();
	        $.ajax({
	        	// url: site.base_url+'admin/products/design_bom/'+product_id+'/'+bom_id,
	        	url: url,
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
	        	alert_float('danger', 'error');
	            $('.add').removeAttr('disabled', 'disabled');
	        });
	        return false;
	    }
    });
</script>
