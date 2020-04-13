<?php echo form_open('admin/items/add_item',array('id'=>'add-item')); ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= _l('tnh_add_item'); ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist" style="margin-left: 15px;">
                        <li role="presentation" class="active">
                            <a href="#home1" aria-controls="home1" role="tab" data-toggle="tab"><?= lang('info') ?></a>
                        </li>
                        <li role="presentation">
                            <a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab"><?= lang('tnh_supplies') ?></a>
                        </li>
                        <li role="presentation">
                            <a href="#tab3" aria-controls="tab2" role="tab" data-toggle="tab"><?= lang('tnh_warehouses') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home1">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang('tnh_item_materials_category', 'category') ?>
                                    <select name="category" id="category" data-placeholder="<?= lang('tnh_item_materials_category') ?>" class="modal-select2" style="width: 100%;">
                                        <option value=""></option>
                                        <?= recursiveCategoryItems() ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <?= lang('tnh_material_code', 'code') ?>
                                    <?php echo form_input('code', (isset($_POST['code']) ? $_POST['code'] : ''), 'placeholder="'.lang('code').'" id="code" required class="form-control input-tip"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('tnh_material_name', 'name') ?>
                                    <?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : ''), 'placeholder="'.lang('name').'" id="name" required class="form-control input-tip"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('tnh_material_name_customer', 'name_customer') ?>
                                    <?php echo form_input('name_customer', (isset($_POST['name_customer']) ? $_POST['name_customer'] : ''), 'placeholder="'.lang('tnh_material_name_customer').'" id="name_customer" class="form-control input-tip"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('tnh_material_name_supplier', 'name_supplier') ?>
                                    <?php echo form_input('name_supplier', (isset($_POST['name_supplier']) ? $_POST['name_supplier'] : ''), 'placeholder="'.lang('tnh_material_name_supplier').'" id="name_supplier" class="form-control input-tip"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('tnh_price_import', 'price_import') ?>
                                    <?php echo form_input('price_import', (isset($_POST['price_import']) ? $_POST['price_import'] : ''), 'placeholder="'.lang('tnh_price_import').'" id="price_import" class="form-control input-tip money-format"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('tnh_price_sell', 'price_sell') ?>
                                    <?php echo form_input('price_sell', (isset($_POST['price_sell']) ? $_POST['price_sell'] : ''), 'placeholder="'.lang('tnh_price_sell').'"  id="price_sell" class="form-control input-tip money-format"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('tnh_images_represent', 'image') ?>
                                    <input type="file" name="image" id="image" class="form-control" value="" title="">
                                </div>
                                <div class="form-group">
                                    <?= lang('tnh_images_multiple', 'images_multiple') ?>
                                    <input type="file" name="images_multiple[]" id="images_multiple" multiple class="form-control" value="" title="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang('tnh_quantity_minimum', 'quantity_minimum') ?>
                                    <?php echo form_input('quantity_minimum', (isset($_POST['quantity_minimum']) ? $_POST['quantity_minimum'] : ''), 'placeholder="'.lang('tnh_quantity_minimum').'" id="quantity_minimum" class="form-control input-tip number-format"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('tnh_quantity_maximum', 'quantity_maximum') ?>
                                    <?php echo form_input('quantity_maximum', (isset($_POST['quantity_maximum']) ? $_POST['quantity_maximum'] : ''), 'placeholder="'.lang('tnh_quantity_maximum').'" id="quantity_maximum" class="form-control input-tip number-format"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('unit', 'unit') ?>
                                    <select name="unit" id="unit" class="form-control selectpicker" data-language="vi_VN" data-live-search="true" data-none-selected-text="<?= lang('unit') ?>" required="required">
                                        <option value=""></option>
                                        <?php foreach ($units as $key => $value): ?>
                                            <option value="<?= $value['unitid'] ?>"><?= $value['unit'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <table class="tnh-tb table-exchange table table-bordered table-hover">
                                    <thead>
                                        <tr class="primary-table">
                                            <th colspan="4"><?= lang('tnh_exchange') ?></th>
                                        </tr>
                                        <tr>
                                            <th style="width: 80px; text-align: center;">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-warning btn-icon btn-add-items"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </th>
                                            <th><?= lang('unit') ?></th>
                                            <th style="width: 150px;"><?= lang('quantity') ?></th>
                                            <th style="width: 80px; text-align: center;"><i class="fa fa-trash-o"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <?= lang('note', 'note') ?>
                                    <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ''), 'placeholder="'.lang('note').'" id="note" class="form-control input-tip tinymce"'); ?>
                                </div>
                            </div>
                            <?php if (!empty($custom_fields)): ?>
                                <div class="col-md-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><?= lang('tnh_custom_fields') ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <?= render_custom_fields('materials') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tab1">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tb-suppliers" class="tnh-table table-hover table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px;"><i class="fa fa-plus btn btn-primary add-supplies"></i></th>
                                                <th style="width: 200px;"><?= lang('tnh_supplies') ?></th>
                                                <th><?= lang('tnh_leadtime') ?></th>
                                                <th style="width: 70px;" class="text-center"><?= lang('actions') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="t-body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tab3">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tb-warehouse" class="tnh-table table-bordered table-condensed table-hover table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px;"><i class="fa fa-plus btn btn-primary add-warehouse" onclick="addTbWarehouse(this)"></i></th>
                                                <th style="width: 200px;"><?= lang('tnh_warehouses') ?></th>
                                                <th><?= lang('tnh_vt') ?></th>
                                                <th style="width: 70px;" class="text-center"><?= lang('actions') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
<script type="text/javascript">
    var counter = 0;
    var units = <?= !empty($units) ? json_encode($units) : false ?>;
    var procedure_detail = <?= !empty($procedure_detail) ? json_encode($procedure_detail) : false ?>;
    var warehouses = <?= !empty($warehouses) ? json_encode($warehouses) : false ?>;
    var edit = 0;
</script>
<script type="text/javascript" src="<?= js('items.js?vs=1.1') ?>"></script>
<script>
    $(function(){
        // selectAjax('#category', false, 'admin/items/searchCategory');
        ajaxSelectParams('#supplier_id', 'admin/items/searchSuppliers', 0);
        $('#category').select2({
            'allowClear': true,
            // escapeMarkup: function(m) {
            //     return $.trim(m);
            // },
        });
       	appValidateForm($('#add-item'), {
           category: 'required',
           unit: 'required',
           code: 'required',
           name: 'required'
        }, additem);

        function additem(form) {
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
            	url : site.base_url+'admin/items/add_item',
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
        init_editor('textarea[name="note"]');
        init_selectpicker();
    })
</script>