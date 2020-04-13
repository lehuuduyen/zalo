<?php echo form_open('admin/tools_supplies/add_item',array('id'=>'add-item')); ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= _l('add'); ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('tnh_category_tools_supplies', 'category') ?>
                            <select name="category" id="category" class="form-control selectpicker ajax-select" data-language="vi_VN" data-live-search="true" data-none-selected-text="<?= lang('tnh_category_tools_supplies') ?>" required="required">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('type', 'type') ?>
                            <select name="type" id="type" class="form-control selectpicker" data-language="vi_VN" data-live-search="true" data-none-selected-text="<?= lang('type') ?>" required="required">
                                <option value=""></option>
                                <!-- <option value="tools"><?= lang('tools') ?></option>
                                <option value="supplies"><?= lang('supplies') ?></option> -->
                                <?php foreach (type_tools_supplies() as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
    				<div class="col-md-12">
    					<div class="form-group">
    						<?= lang('tnh_tool_supplies_code', 'code') ?>
    						<?php echo form_input('code', (isset($_POST['code']) ? $_POST['code'] : ''), 'placeholder="'.lang('code').'" id="code" required class="form-control input-tip"'); ?>
    					</div>
    				</div>
    				<div class="col-md-12">
    					<div class="form-group">
    						<?= lang('tnh_tool_supplies_name', 'name') ?>
    						<?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : ''), 'placeholder="'.lang('name').'" id="name" required class="form-control input-tip"'); ?>
    					</div>
    				</div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('tnh_price_import', 'price_import') ?>
                            <?php echo form_input('price_import', (isset($_POST['price_import']) ? $_POST['price_import'] : ''), 'placeholder="'.lang('tnh_price_import').'" onkeyup="formatNumBerKeyUpCus(this)" id="price_import" class="form-control input-tip"'); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('image', 'image') ?>
                            <input type="file" name="image" id="image" class="form-control" value="" title="">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang('unit', 'unit') ?>
                            <select name="unit" id="unit" class="form-control selectpicker" data-language="vi_VN" data-live-search="true" data-none-selected-text="<?= lang('unit') ?>" required="required">
                                <option value=""></option>
                                <?php foreach ($units as $key => $value): ?>
                                    <option value="<?= $value['unitid'] ?>"><?= $value['unit'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
    				<div class="col-md-12">
    					<div class="form-group">
    						<?= lang('note', 'note') ?>
    						<?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ''), 'placeholder="'.lang('note').'" id="note" class="form-control input-tip tinymce"'); ?>
    					</div>
    				</div>
                </div>
                <?php if (!empty($custom_fields)): ?>
                    <div class="col-md-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?= lang('tnh_custom_fields') ?></h3>
                            </div>
                            <div class="panel-body">
                                <?= render_custom_fields('tools_supplies') ?>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
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
        selectAjax('#category', false, 'admin/tools_supplies/searchCategory');
       	appValidateForm($('#add-item'), {
           category: 'required',
           unit: 'required',
           code: 'required',
           type: 'required',
           name: 'required'
        }, addToolsSupplies);

        function addToolsSupplies(form) {
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
            	url : site.base_url+'admin/tools_supplies/add_item',
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
            	console.log("error");
            });
            return false;
        }
        init_editor('textarea[name="note"]');
        init_selectpicker();
    })
</script>