<?php echo form_open("admin/products/design_stages/$id", array('id'=>'add-stage')); ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= lang('stages') ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<?= lang('tnh_versions', 'versions') ?>
						<?php echo form_input('versions', (isset($_POST['versions']) ? $_POST['versions'] : (!empty($stage['versions']) ? $stage['versions'] : '')), 'placeholder="'.lang('tnh_versions').'" id="versions" required class="form-control input-tip"'); ?>
					</div>
				</div>
				<input type="hidden" name="product_id" id="product_id" class="form-control product_id" value="<?= $id ?>">
				<div class="col-md-12">
					<div class="">
						<table class="table table-stages table-hover table-bordered table-condensed sortable" style="margin: 0;">
							<thead>
								<tr style="background: #5cb0d5;">
									<th style="width: 5%;">
										<div class="text-center">
											<button type="button" class="btn btn-warning btn-icon btn-add-stage"><i class="fa fa-plus"></i></button>
										</div>
									</th>
									<th style="width: 30%;"><?= lang('tnh_stage_name') ?></th>
									<th style="width: 45%;"><?= lang('tnh_machines') ?></th>
									<th style="width: 15%;"><?= lang('tnh_number_hours') ?></th>
									<th style="width: 5%px; text-align: center;"><i class="fa fa-trash-o"></i></th>
								</tr>
							</thead>
							<tbody class="ui-sortable">
								<?= $html_stages ?>
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
			<button type="submit" class="btn btn-primary add"><?= lang('save') ?></button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
	var i_stage =  <?= !empty($items) ? count($items) : 0 ?>;
	var product_stage_id = <?= !empty($stage['id']) ? $stage['id'] : 0 ?>;
	var list_stages = '<?= $list_stages ?>';
	console.log(list_stages);
	var json_stage = {};
	json_stage['versions'] = 'required';

	<?php if (!empty($items)): ?>
    	$(document).ready(function() {
    		for (n = 0; n <= i_stage; n++)
    		{
    			selectAjax($('select#stage_'+ n +''), false, 'admin/products/searchStages');
    			selectAjax($('select#machines_'+ n +''), false, 'admin/categories/searchMachines');
    			json_stage['stage_'+n+''] = 'required';
    		}
    	});
    <?php endif ?>

	function totalStages()
	{
	    var table_stage = $('.table-stages tbody tr').length;
	    var stt = 0;
	    for (ii = 0; ii < table_stage; ii++)
	    {
	        stt++;
	        element = $('.table-stages tbody tr')[ii];
	        $(element).find('.stt').html(stt);
	        $(element).find('.number').val(stt);
	    }
	}

	$(document).ready(function() {
		$('.sortable tbody').sortable({
           	start:function(){
           	},
           	stop:function(){
           		totalStages();
           	}
       	});

		$('.btn-add-stage').click(function(event) {
			event.preventDefault();
			tr_html = '';
			tr_html += '<tr class="sortable item">';
	        tr_html += '<input type="hidden" name="i_stage[]" id="i_stage" class="form-control i_stage" value="'+i_stage+'">'
	        tr_html += '<input type="hidden" name="number[]" id="number" class="form-control number">'
			tr_html += '<td class="stt text-center dragger"></td>';

			tr_html += '<td>\
	                        <select name="stage[]"  data-live-search="true" data-none-selected-text="<?= lang('choose') ?>" id="stage_'+ i_stage +'" class="form-control" required="required">\
	                            <option value=""></option>\
	                            '+list_stages+'\
	                        </select>\
	                    </td>';
	        tr_html += '<td>\
	                        <select name="machines['+i_stage+'][]"  data-live-search="true" data-none-selected-text="<?= lang('tnh_machines') ?>" id="machines_'+ i_stage +'" class="form-control ajax-search" >\
	                            <option value=""></option>\
	                        </select>\
	                    </td>';
	        tr_html += '<td>\
	                        <input type="number" name="number_hours[]" id="input" class="form-control" value="0" title="">\
	                    </td>';
			tr_html += '<td>\
							<div class="text-center"><i class="btn btn-danger fa fa-remove remove-stage"></i></div>\
						</td>';
			tr_html += '</tr>';

			$('.table-stages tbody').append(tr_html);
            // selectAjax($('select#stage_'+ i_stage +''), false, 'admin/products/searchStages');
            $('select#stage_'+ i_stage +'').selectpicker();
            selectAjax($('select#machines_'+ i_stage +''), false, 'admin/categories/searchMachines');
	        json_stage['stage_'+i_stage+''] = 'required';
	        appValidateForm($('#add-stage'), json, addStages);
	        totalStages();
	        i_stage++;
		});

		$('.modal').on('click', '.remove-stage', function(e) {
			e.preventDefault();
			$(this).closest('tr').remove();
			totalStages();
		});

		appValidateForm($('#add-stage'), json_stage, addStages);
	    function addStages(form) {
	    	$('.add').attr('disabled', 'disabled');
	        product_id = $('#product_id').val();
	        if (!product_id) {
	            alert_float('danger', 'errors');
	            $('.add').removeAttr('disabled', 'disabled');
	            return;
	        }
	        var data = $(form).serialize();
	        var url = form.action;
	        $.ajax({
	        	url: site.base_url+'admin/products/design_stages/'+product_id+'/'+product_stage_id,
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
