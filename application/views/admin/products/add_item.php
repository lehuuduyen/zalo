<?php echo form_open('admin/products/add_product',array('id'=>'add-product')); ?>
<div class="modal-dialog" style="width: 70%;">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?= _l('tnh_add_product'); ?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" style="margin-left: 15px;">
                        <li role="presentation" class="active">
                            <a href="#home1" aria-controls="home" role="tab" data-toggle="tab"><?= lang('info') ?></a>
                        </li>
                        <li role="presentation">
                            <a href="#tab1" aria-controls="tab" role="tab" data-toggle="tab"><?= lang('tnh_supplies') ?></a>
                        </li>
                        <li role="presentation">
                            <a href="#tab3" aria-controls="tab2" role="tab" data-toggle="tab"><?= lang('tnh_warehouses') ?></a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home1">
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('category', 'category') ?>
                                        <select name="category" id="category" data-placeholder="<?= lang('tnh_item_materials_category') ?>" class="modal-select2" style="width: 100%;">
                                            <option value=""></option>
                                            <?= recursiveCategoryProducts() ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_type_products', 'type_products') ?>
                                        <select name="type_products" id="type_products" class="modal-select2" data-language="vi_VN"  data-placeholder="<?= lang('tnh_type_products') ?>" required="required" style="width: 100%;">
                                            <option value=""></option>
                                            <?php foreach (type_products() as $key => $value): ?>
                                                <option value="<?= $key ?>"><?= $value ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_product_code', 'code') ?>
                                        <?php echo form_input('code', (isset($_POST['code']) ? $_POST['code'] : ''), 'placeholder="'.lang('code').'" id="code" required class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_product_name', 'name') ?>
                                        <?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : ''), 'placeholder="'.lang('name').'" id="name" required class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_product_name_customer', 'name_customer') ?>
                                        <?php echo form_input('name_customer', (isset($_POST['name_customer']) ? $_POST['name_customer'] : ''), 'placeholder="'.lang('tnh_product_name_customer').'" id="name_customer" class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_product_name_supplier', 'name_supplier') ?>
                                        <?php echo form_input('name_supplier', (isset($_POST['name_supplier']) ? $_POST['name_supplier'] : ''), 'placeholder="'.lang('tnh_material_name_supplier').'" id="name_supplier" class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_import', 'price_import') ?>(<?= lang('tnh_ncc') ?>)
                                        <?php echo form_input('price_import', (isset($_POST['price_import']) ? $_POST['price_import'] : ''), 'placeholder="'.lang('tnh_price_import').'" id="price_import" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_sell', 'price_sell') ?>
                                        <?php echo form_input('price_sell', (isset($_POST['price_sell']) ? $_POST['price_sell'] : ''), 'placeholder="'.lang('tnh_price_sell').'" id="price_sell" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_domestic', 'price_domestic') ?>
                                        <?php echo form_input('price_domestic', (isset($_POST['price_domestic']) ? $_POST['price_domestic'] : ''), 'placeholder="'.lang('tnh_price_domestic').'" id="price_domestic" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_foreign', 'price_foreign') ?>
                                        <?php echo form_input('price_foreign', (isset($_POST['price_foreign']) ? $_POST['price_foreign'] : ''), 'placeholder="'.lang('tnh_price_foreign').'" id="price_foreign" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_processing', 'price_processing') ?>
                                        <?php echo form_input('price_processing', (isset($_POST['price_processing']) ? $_POST['price_processing'] : ''), 'placeholder="'.lang('tnh_price_processing').'" id="price_sell" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_quantity_minimum', 'quantity_minimum') ?>
                                        <?php echo form_input('quantity_minimum', (isset($_POST['quantity_minimum']) ? $_POST['quantity_minimum'] : ''), 'placeholder="'.lang('tnh_quantity_minimum').'" id="quantity_minimum" class="form-control input-tip number-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_quantity_max', 'quantity_max') ?>
                                        <?php echo form_input('quantity_max', (isset($_POST['quantity_max']) ? $_POST['quantity_max'] : ''), 'placeholder="'.lang('tnh_quantity_max').'" id="quantity_max" class="form-control input-tip number-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_number_hours_ap', 'number_hours_ap') ?>
                                        <?php echo form_input('number_hours_ap', (isset($_POST['number_hours_ap']) ? $_POST['number_hours_ap'] : ''), 'placeholder="'.lang('tnh_number_hours_ap').'" id="number_hours_ap" class="form-control input-tip number-format"'); ?>
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
                                        <?= lang('colors', 'colors') ?>
                                        <select name="colors[]" id="colors" class="form-control selectpicker ajax-select" data-language="vi_VN" data-live-search="true" data-none-selected-text="<?= lang('colors') ?>" >
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_mode', 'mode') ?>
                                        <?php echo form_input('mode', (isset($_POST['mode']) ? $_POST['mode'] : ''), 'placeholder="'.lang('tnh_mode').'" id="mode" class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_number_labor', 'number_labor') ?>
                                        <?php echo form_input('number_labor', (isset($_POST['number_labor']) ? $_POST['number_labor'] : ''), 'placeholder="'.lang('tnh_number_labor').'" id="mode" class="form-control input-tip number-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_bom_form', 'BOM') ?>
                                        <span class="fa fa-ban ban-bom red" style="display: none;"></span>
                                        <select name="bom_id" id="bom_id" data-placeholder="<?= lang('BOM') ?>" class="modal-select2" style="width: 100%;">
                                            <option value=""></option>
                                            <?php foreach ($boms as $key => $value): ?>
                                                <option value="<?= $value['id'] ?>"><?= $value['versions'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_images_represent', 'image') ?>
                                        <input type="file" name="image" id="image" class="form-control" value="" title="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_images_multiple', 'images_multiple') ?>
                                        <input type="file" name="images_multiple[]" id="images_multiple" multiple class="form-control" value="" title="">
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
                                            <?= render_custom_fields('products') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                            <!--Công bổ sung thêm các trường động info khách hàng-->
                            <div class="col-md-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <?= _l('cong_dk_client_to_product') ?>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-6">
                                            <?php echo render_select('type_dt', $dt, ['id', 'name'], _l('cong__dt')); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo render_select('type_kt', $kt, ['id', 'name'],_('cong__kt'))?>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <?php
                                                $gender = [
                                                    ['id' => 0, 'name' => _l('all')],
                                                    ['id' => 1, 'name' => _l('cong_male')],
                                                    ['id' => 2, 'name' => _l('cong_female')],
                                                ];
                                            ?>
                                            <?php echo render_select('type_gender', $gender, ['id', 'name'], _('tnh_type_gender'))?>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="col-md-6" style="padding-left: 0px;">
                                                <div class="radio">
                                                    <input type="radio" id="gender_male" name="type_gender" value="1">
                                                    <label for="gender_male"><?=_l('cong_male')?></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="radio">
                                                    <input type="radio" id="gender_female" name="type_gender" value="2">
                                                    <label for="gender_female"><?=_l('cong_female')?></label>
                                                </div>
                                            </div>
                                        </div> -->
                                        <?php if(!empty($info_client)){?>
                                            <?php
                                                foreach($info_client as $key => $value) {?>
                                                    <div class="col-md-6">
                                                        <?php echo render_select('info['.$value['id'].']', $value['detail'], ['id', 'name'], $value['name'], ''); ?>
                                                    </div>
                                                    <?php  if($key % 2 != 0){?>
                                                        <!-- <div class="clearfix"></div> -->
                                                    <?php }?>
                                            <?php } ?>
                                        <?php } ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang('tnh_size', 'size') ?>
                                                <?php echo form_input('size', (isset($_POST['size']) ? $_POST['size'] : ''), 'placeholder="'.lang('tnh_size').'" id="size" class="form-control input-tip"'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang('tnh_weight', 'weight') ?>
                                                <?php echo form_input('weight', (isset($_POST['weight']) ? $_POST['weight'] : ''), 'placeholder="'.lang('tnh_weight').'" id="weight" class="form-control input-tip"'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang('tnh_structure', 'structure') ?>
                                                <?php echo form_input('structure', (isset($_POST['structure']) ? $_POST['structure'] : ''), 'placeholder="'.lang('tnh_structure').'" id="structure" class="form-control input-tip"'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang('tnh_description', 'description') ?>
                                                <?php echo form_input('description', (isset($_POST['description']) ? $_POST['description'] : ''), 'placeholder="'.lang('tnh_description').'" id="description" class="form-control input-tip"'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--END Công bổ sung-->
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
    var procedure_detail = <?= !empty($procedure_detail) ? json_encode($procedure_detail) : '{}' ?>;
    var warehouses = <?= !empty($warehouses) ? json_encode($warehouses) : '{}' ?>;
    var edit = 0;
</script>
<script type="text/javascript" src="<?= js('products.js?vs=1.2') ?>"></script>
<script>
    $(function(){
        // selectAjax('#category', false, 'admin/products/searchCategory');
        $('#category').select2({'allowClear': true});
        $('#bom_id').select2({'allowClear': true});
        $('#type_products').select2();
        selectAjax('#colors', false, 'admin/products/searchColors');

        $('#type_products').change(function(event) {
            type_products = $(this).val();
            if (type_products == "semi_products_outside") {
                $('.ban-bom').show();
                $('#bom_id').val('').trigger('change');
                $('#bom_id').select2('readonly', true);
            } else {
                $('.ban-bom').hide();
                $('#bom_id').select2('readonly', false);
            }
        });

       	appValidateForm($('#add-product'), {
           category: 'required',
           type_products: 'required',
           unit: 'required',
           code: 'required',
           name: 'required'
        }, addproduct);

        function addproduct(form) {
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
            	url : site.base_url+'admin/products/add_product',
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