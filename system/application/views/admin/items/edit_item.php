<?php echo form_open('admin/items/edit_item'.$material['id'],array('id'=>'edit-item')); ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= _l('tnh_edit_item'); ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
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
        						<?php echo form_input('code', (isset($_POST['code']) ? $_POST['code'] : $material['code']), 'placeholder="'.lang('code').'" id="code" required class="form-control input-tip"'); ?>
        					</div>
        					<div class="form-group">
        						<?= lang('tnh_material_name', 'name') ?>
        						<?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : $material['name']), 'placeholder="'.lang('name').'" id="name" required class="form-control input-tip"'); ?>
        					</div>
                            <div class="form-group">
                                <?= lang('tnh_material_name_customer', 'name_customer') ?>
                                <?php echo form_input('name_customer', (isset($_POST['name_customer']) ? $_POST['name_customer'] : $material['name_customer']), 'placeholder="'.lang('tnh_material_name_customer').'" id="name_customer" class="form-control input-tip"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('tnh_material_name_supplier', 'name_supplier') ?>
                                <?php echo form_input('name_supplier', (isset($_POST['name_supplier']) ? $_POST['name_supplier'] : $material['name_supplier']), 'placeholder="'.lang('tnh_material_name_supplier').'" id="name_supplier" class="form-control input-tip"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('tnh_price_import', 'price_import') ?>
                                <?php echo form_input('price_import', (isset($_POST['price_import']) ? $_POST['price_import'] : formatMoney($material['price_import'])), 'placeholder="'.lang('tnh_price_import').'" onkeyup="formatNumBerKeyUpCus(this)" id="price_import" class="form-control input-tip money-format"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('tnh_price_sell', 'price_sell') ?>
                                <?php echo form_input('price_sell', (isset($_POST['price_sell']) ? $_POST['price_sell'] : formatMoney($material['price_sell'])), 'placeholder="'.lang('tnh_price_sell').'" onkeyup="formatNumBerKeyUpCus(this)" id="price_sell" class="form-control input-tip money-format"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('tnh_images_represent', 'image') ?>
                                <input type="file" name="image" id="image" class="form-control" value="" title="">
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <?= lang('tnh_images_multiple', 'images_multiple') ?>
                                    <input type="file" name="images_multiple[]" id="images_multiple" multiple class="form-control" value="" title="">
                                </div>
                                <?php if (!empty($material['images_multiple'])): ?>
                                <div class="preview_image" id="avatar_view" style="width: auto;">
                                    <div class="display-block contract-attachment-wrapper img-1">
                                        <?php $images_multiple = explode('||', $material['images_multiple']); ?>
                                        <?php foreach ($images_multiple as $key => $value): ?>
                                        <div class="col-md-2">
                                            <input type="hidden" name="images_old[]" id="images_old[]" class="form-control" value="<?= $value ?>">
                                            <button type="button" class="close remove-image" data-id="50" data-src="uploads/items/50/tru_ringlock.jpg" style="color:red;" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <a href="<?= pathMaterial($value) ?>" data-lightbox="customer-profile" class="display-block mbot5">
                                                <div class="">
                                                    <img style="max-width: 200px;max-height: 300px;" src="<?= pathMaterial($value) ?>">
                                                </div>
                                            </a>
                                        </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                                <?php endif ?>
                                <input type="hidden" name="remove_image" id="remove_image" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang('tnh_quantity_minimum', 'quantity_minimum') ?>
                                <?php echo form_input('quantity_minimum', (isset($_POST['quantity_minimum']) ? $_POST['quantity_minimum'] : formatNumber($material['quantity_minimum'])), 'placeholder="'.lang('tnh_quantity_minimum').'" id="quantity_minimum" class="form-control input-tip number-format"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('tnh_quantity_maximum', 'quantity_maximum') ?>
                                <?php echo form_input('quantity_maximum', (isset($_POST['quantity_maximum']) ? $_POST['quantity_maximum'] : formatNumber($material['quantity_maximum'])), 'placeholder="'.lang('tnh_quantity_maximum').'" id="quantity_maximum" class="form-control input-tip number-format"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('unit', 'unit') ?>
                                <select name="unit" id="unit" class="form-control selectpicker" data-language="vi_VN" data-live-search="true" data-none-selected-text="<?= lang('unit') ?>" required="required">
                                    <option value=""></option>
                                    <?php foreach ($units as $key => $value): ?>
                                        <option <?= $value['unitid'] == $material['unit_id'] ? 'selected' : '' ?> value="<?= $value['unitid'] ?>"><?= $value['unit'] ?></option>
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
                                    <?php if (!empty($exchanges)): ?>
                                    <?php foreach ($exchanges as $key => $value): ?>
                                        <tr>
                                            <td class="stt text-center"><?= ++$key ?></td>
                                            <td>
                                                <select name="unit_exchange[]"  data-live-search="true" id="unit_exchange" class="form-control unit_exchange">
                                                    <option value="0"></option>
                                                    <?php foreach ($units as $k => $val): ?>
                                                        <option <?= $val['unitid'] == $value['unit_id'] ? 'selected' : '' ?> value="<?= $val['unitid'] ?>"><?= $val['unit'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="number_exchange[]" id="number_exchange[]" class="form-control" value="<?= $value['number_exchange'] ?>" min="0"  step="0.1">
                                            </td>
                                            <td>
                                                <div class="text-center"><i class="btn btn-danger fa fa-remove remove-exchange"></i></div>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                    <?php endif ?>
                                </tbody>
                            </table>
        					<div class="form-group">
        						<?= lang('note', 'note') ?>
        						<?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $material['note']), 'placeholder="'.lang('note').'" id="note" class="form-control input-tip tinymce"'); ?>
        					</div>
                        </div>
                        <?php if (!empty($custom_fields)): ?>
                            <div class="col-md-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?= lang('tnh_custom_fields') ?></h3>
                                    </div>
                                    <div class="panel-body">
                                        <?= render_custom_fields('materials', $material['id']) ?>
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
                                        <?php $counter = 0; ?>
                                        <?php if (!empty($material_suppliers)): ?>
                                            <?php foreach ($material_suppliers as $key => $value): ?>
                                                <?php $leadtimes = $this->items_model->getMaterialSuppliersByMaterialAndSupplier($id, $value['supplier_id']) ?>
                                                <tr>
                                                    <td class="td-number text-center"></td>
                                                    <td class="td-suppliers ">
                                                        <input type="hidden" name="counter[]" id="counter" class="form-control counter" value="<?= $counter ?>">
                                                        <input type="text" name="suppliers[<?= $counter ?>]" data-placeholder="<?= lang('choose') ?>" id="suppliers_<?= $counter ?>" class="suppliers modal-select2" style="width: 100%;" value="<?= $value['supplier_id'] ?>">
                                                    </td>
                                                    <td class="td-leadtime">
                                                        <table class="tnh-table tb-subb">
                                                            <thead>
                                                                <th style="width: 50px;" class="text-center">
                                                                    <i onclick="addStageSub(this, <?= $counter ?>)" class="fa fa-plus btn btn-success add-sub-st"></i>
                                                                </th>
                                                                <th style="width: 150px;"><?= lang('tnh_stage') ?></th>
                                                                <th style="width: 150px;"><?= lang('tnh_sequence') ?></th>
                                                                <th><?= lang('tnh_number_date') ?></th>
                                                                <th class="text-center" style="width: 50px;"><i class="fa fa-trash" class="remove-sub"></i></th>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($leadtimes)): ?>
                                                                    <?php foreach ($leadtimes as $k => $val): ?>
                                                                        <tr>
                                                                            <td class="td-number-sub text-center"></td>
                                                                            <td class="td-stage-sub ">
                                                                                <select name="procedure[<?= $counter ?>][]" id="procedure" data-placeholder="<?= lang('choose') ?>" class="procedure modal-select2" style="width: 100%;">
                                                                                    <?php foreach ($procedure_detail as $e => $v): ?>
                                                                                        <option <?= $v['id'] == $val['procedure_id'] ? 'selected' : '' ?> value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                                                                                    <?php endforeach ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="sequence[<?= $counter ?>][]" id="sequence" class="form-control sequence" value="<?= $val['sequence'] ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="number_date[<?= $counter ?>][]" id="number_date" class="form-control number_date" value="<?= $val['number_date'] ?>">
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a class="fa fa-remove" onclick="removeStageSubb(this)"></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach ?>
                                                                <?php endif ?>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td class="td-actions text-center"><i class="btn btn-danger fa fa-remove remove-suppliers" onclick="removeSuppliers(this)"></i></td>
                                                </tr>
                                                <?php $counter++; ?>
                                            <?php endforeach ?>
                                        <?php endif ?>
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
                                        <?php if (!empty($material_warehouse)): ?>
                                            <?php foreach ($material_warehouse as $k => $val): ?>
                                            <tr>
                                                <td class="td-number-ws text-center"></td>
                                                <td>
                                                    <select onchange="changeWarehouse(this)" name="warehouses[]" id="warehouses" data-placeholder="<?= lang('choose') ?>" class="warehouses modal-select2" style="width: 100%;">
                                                        <?php foreach ($warehouses as $key => $value): ?>
                                                            <option <?= $val['warehouse_id'] == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <?php $options = '<option></option>'.recursiveLocationWarehouses($val['warehouse_id']);
                                                    ?>
                                                    <select name="location[]" id="location" data-placeholder="<?= lang('choose') ?>" class="location modal-select2 sl<?= $val['id'] ?>" style="width: 100%;">
                                                        <?= $options ?>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <a class="fa fa-remove" onclick="removeWarehouse(this)"></a>
                                                </td>
                                            </tr>
                                            <script type="text/javascript">
                                                $(document).ready(function() {
                                                    $('select.sl<?= $val['id'] ?>').select2();
                                                    $('select.sl<?= $val['id'] ?>').select2().val(<?= $val['location_id'] ?>).trigger('change');
                                                });
                                            </script>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
			<button type="submit" class="btn btn-primary edit"><?= _l('save') ?></button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    var counter = <?= $counter ?>;
    var units = <?= !empty($units) ? json_encode($units) : false ?>;
    var procedure_detail = <?= !empty($procedure_detail) ? json_encode($procedure_detail) : false ?>;
    var warehouses = <?= !empty($warehouses) ? json_encode($warehouses) : false ?>;
    var edit = 1;
</script>
<script type="text/javascript" src="<?= js('items.js?vs=1.1') ?>"></script>
<script>
    $(function(){
        // selectAjax('#category', false, 'admin/items/searchCategory');
        $('#category').select2({'allowClear': true});
        $('#category').val(<?= $material['category_id'] ?>).trigger('change');
        $('.unit_exchange').selectpicker();

        $('.remove-image').click(function(event) {
            $(this).closest('.col-md-2').remove();
            $('#remove_image').val(1);
        });

       	appValidateForm($('#edit-item'), {
           category: 'required',
           unit: 'required',
           code: 'required',
           name: 'required'
        }, edititem);

        function edititem(form) {
        	$('.edit').attr('disabled', 'disabled');
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
            	url : site.base_url+'admin/items/edit_item/<?= $material['id'] ?>',
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
            		$('.edit').removeAttr('disabled', 'disabled');
            	}
            })
            .fail(function() {
                $('.edit').removeAttr('disabled', 'disabled');
            	console.log("error");
            });
            return false;
        }
        init_editor('textarea[name="note"]');
        init_selectpicker();
    })
</script>