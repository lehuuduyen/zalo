<?php echo form_open('admin/products/edit_product/'.$product['id'],array('id'=>'edit-product')); ?>
<div class="modal-dialog" style="width: 70%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?= _l('tnh_edit_product'); ?></h4>
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
                        <li role="presentation">
                            <a href="#tab4" class="tabs-bom" aria-controls="tab4" role="tab" data-toggle="tab"><?= lang('tnh_bom_edit') ?></a>
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
                                        <select name="type_products" id="type_products" class="form-control selectpicker" data-language="vi_VN" data-live-search="true" data-none-selected-text="<?= lang('tnh_type_products') ?>" required="required">
                                            <option value=""></option>
                                            <?php foreach (type_products() as $key => $value): ?>
                                                <option <?= $product['type_products'] == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_product_code', 'code') ?>
                                        <?php echo form_input('code', (isset($_POST['code']) ? $_POST['code'] : $product['code']), 'placeholder="'.lang('code').'" id="code" required class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_product_name', 'name') ?>
                                        <?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : $product['name']), 'placeholder="'.lang('name').'" id="name" required class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_product_name_customer', 'name_customer') ?>
                                        <?php echo form_input('name_customer', (isset($_POST['name_customer']) ? $_POST['name_customer'] : $product['name_customer']), 'placeholder="'.lang('tnh_product_name_customer').'" id="name_customer" class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_product_name_supplier', 'name_supplier') ?>
                                        <?php echo form_input('name_supplier', (isset($_POST['name_supplier']) ? $_POST['name_supplier'] : $product['name_supplier']), 'placeholder="'.lang('tnh_material_name_supplier').'" id="name_supplier" class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_import', 'price_import') ?>
                                        <?php echo form_input('price_import', (isset($_POST['price_import']) ? $_POST['price_import'] : formatNumber($product['price_import'])), 'placeholder="'.lang('tnh_price_import').'"  id="price_import" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_sell', 'price_sell') ?>
                                        <?php echo form_input('price_sell', (isset($_POST['price_sell']) ? $_POST['price_sell'] : formatNumber($product['price_sell'])), 'placeholder="'.lang('tnh_price_sell').'" id="price_sell" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_domestic', 'price_domestic') ?>
                                        <?php echo form_input('price_domestic', (isset($_POST['price_domestic']) ? $_POST['price_domestic'] : formatNumber($product['price_domestic'])), 'placeholder="'.lang('tnh_price_domestic').'" id="price_domestic" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_foreign', 'price_foreign') ?>
                                        <?php echo form_input('price_foreign', (isset($_POST['price_foreign']) ? $_POST['price_foreign'] : formatNumber($product['price_foreign'])), 'placeholder="'.lang('tnh_price_foreign').'" id="price_foreign" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_price_processing', 'price_processing') ?>
                                        <?php echo form_input('price_processing', (isset($_POST['price_processing']) ? $_POST['price_processing'] : formatNumber($product['price_processing'])), 'placeholder="'.lang('tnh_price_processing').'" id="price_sell" class="form-control input-tip money-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_quantity_minimum', 'quantity_minimum') ?>
                                        <?php echo form_input('quantity_minimum', (isset($_POST['quantity_minimum']) ? $_POST['quantity_minimum'] : formatNumber($product['quantity_minimum'])), 'placeholder="'.lang('tnh_quantity_minimum').'" id="quantity_minimum" class="form-control input-tip number-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_quantity_max', 'quantity_max') ?>
                                        <?php echo form_input('quantity_max', (isset($_POST['quantity_max']) ? $_POST['quantity_max'] : formatNumber($product['quantity_max'])), 'placeholder="'.lang('tnh_quantity_max').'" id="quantity_max" class="form-control input-tip number-format"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_number_hours_ap', 'number_hours_ap') ?>
                                        <?php echo form_input('number_hours_ap', (isset($_POST['number_hours_ap']) ? $_POST['number_hours_ap'] : formatNumber($product['number_hours_ap'])), 'placeholder="'.lang('tnh_number_hours_ap').'" id="number_hours_ap" class="form-control input-tip number-format"'); ?>
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
                                                <option <?= $product['unit_id'] == $value['unitid'] ? 'selected' : '' ?> value="<?= $value['unitid'] ?>"><?= $value['unit'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('colors', 'colors') ?>
                                        <select name="colors[]" id="colors" class="form-control selectpicker ajax-select" data-language="vi_VN" data-live-search="true" data-none-selected-text="<?= lang('colors') ?>" >
                                            <option value=""></option>
                                            <?php foreach ($colors as $key => $value): ?>
                                                <option value="<?= $value['id'] ?>" selected><?= $value['color_name'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_mode', 'mode') ?>
                                        <?php echo form_input('mode', (isset($_POST['mode']) ? $_POST['mode'] : $product['mode']), 'placeholder="'.lang('tnh_mode').'" id="mode" class="form-control input-tip"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('tnh_number_labor', 'number_labor') ?>
                                        <?php echo form_input('number_labor', (isset($_POST['number_labor']) ? $_POST['number_labor'] : formatNumber($product['number_labor'])), 'placeholder="'.lang('tnh_number_labor').'" id="mode" class="form-control input-tip number-format"'); ?>
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
                                    <?php if (!empty($product['images_multiple'])): ?>
                                    <div class="preview_image" id="avatar_view" style="width: auto;">
                                        <div class="display-block contract-attachment-wrapper img-1">
                                            <?php $images_multiple = explode('||', $product['images_multiple']); ?>
                                            <?php foreach ($images_multiple as $key => $value): ?>
                                            <div class="col-md-2">
                                                <input type="hidden" name="images_old[]" id="images_old[]" class="form-control" value="<?= $value ?>">
                                                <button type="button" class="close remove-image" data-id="50" data-src="uploads/items/50/tru_ringlock.jpg" style="color:red;" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <a href="<?= pathProduct($value) ?>" data-lightbox="customer-profile" class="display-block mbot5">
                                                    <div class="">
                                                        <img style="max-width: 200px;max-height: 300px;" src="<?= pathProduct($value) ?>">
                                                    </div>
                                                </a>
                                            </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                    <?php endif ?>
                                    <input type="hidden" name="remove_image" id="remove_image" class="form-control" value="0">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang('note', 'note') ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $product['note']), 'placeholder="'.lang('note').'" id="note" class="form-control input-tip tinymce"'); ?>
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
                                            <?= render_custom_fields('products', $product['id']) ?>
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
                                            <?php
                                                $selected = (isset($_POST['type_dt']) ? $_POST['type_dt'] : $product['type_dt']);
                                                echo render_select('type_dt', $dt, ['id', 'name'],'cong__dt', $selected);
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                                $selected = (isset($_POST['type_kt']) ? $_POST['type_kt'] : $product['type_kt']);
                                                echo render_select('type_kt', $kt, ['id', 'name'],'cong__kt', $selected);
                                            ?>
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
                                            <?php echo render_select('type_gender', $gender, ['id', 'name'], _('tnh_type_gender'), $product['type_gender'])?>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <div class="col-md-6" style="padding-left: 0px;">
                                                <div class="radio">
                                                    <input type="radio" id="gender_male" name="type_gender" value="1" <?= $product['type_gender'] == 1 ? 'checked' : ''?>>
                                                    <label for="gender_male"><?=_l('cong_male')?></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="radio">
                                                    <input type="radio" id="gender_female" name="type_gender" value="2" <?= $product['type_gender'] == 2 ? 'checked' : ''?>>
                                                    <label for="gender_female"><?=_l('cong_female')?></label>
                                                </div>
                                            </div>
                                        </div> -->
                                        <?php if(!empty($info_client)){?>
                                            <?php
                                            foreach($info_client as $key => $value) {?>
                                                <div class="col-md-6">
                                                    <?php
                                                        $selected = !empty($value['val']) ? $value['val'] : '';
                                                        echo render_select('info['.$value['id'].']', $value['detail'], ['id', 'name'], $value['name'], $selected);

                                                    ?>
                                                </div>
                                                <?php  if($key % 2 != 0){?>
                                                    <!-- <div class="clearfix"></div> -->
                                                <?php }?>
                                            <?php } ?>
                                        <?php } ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang('tnh_size', 'size') ?>
                                                <?php echo form_input('size', (isset($_POST['size']) ? $_POST['size'] : $product['size']), 'placeholder="'.lang('tnh_size').'" id="size" class="form-control input-tip"'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang('tnh_weight', 'weight') ?>
                                                <?php echo form_input('weight', (isset($_POST['weight']) ? $_POST['weight'] : $product['weight']), 'placeholder="'.lang('tnh_weight').'" id="weight" class="form-control input-tip"'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang('tnh_structure', 'structure') ?>
                                                <?php echo form_input('structure', (isset($_POST['structure']) ? $_POST['structure'] : $product['structure']), 'placeholder="'.lang('tnh_structure').'" id="structure" class="form-control input-tip"'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang('tnh_description', 'description') ?>
                                                <?php echo form_input('description', (isset($_POST['description']) ? $_POST['description'] : $product['description']), 'placeholder="'.lang('tnh_description').'" id="description" class="form-control input-tip"'); ?>
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
                                            <?php $counter = 0; ?>
                                        <?php if (!empty($product_suppliers)): ?>
                                            <?php foreach ($product_suppliers as $key => $value): ?>
                                                <?php $leadtimes = $this->products_model->getProductSuppliersByProductAndSupplier($id, $value['supplier_id']) ?>
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
                                            <?php if (!empty($product_warehouse)): ?>
                                            <?php foreach ($product_warehouse as $k => $val): ?>
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
                        <div role="tabpanel" class="tab-pane" id="tab4">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="tnh-table table-bordered table-condensed table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;" class="text-center"><a href="javascript:void(0)" onclick="aBOM(this)"><i class="fa fa-plus"></i></a></th>
                                                <th><?= lang('tnh_list_bom') ?></th>
                                                <th style="width: 200px;"><?= lang('tnh_using') ?></th>
                                                <th style="width: 100px;" class="text-center"><?= lang('actions') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $counter_bom = 0; ?>
                                            <?php if (!empty($boms_product)): ?>
                                                <?php foreach ($boms_product as $key => $value): ?>
                                                    <tr>
                                                        <input type="hidden" name="product_versions[]" id="inputProduct_versions[]" class="form-control" value="<?= $value['versions'] ?>">
                                                        <td></td>
                                                        <td><?= $value['versions'] ?></td>
                                                        <td>
                                                            <div class="radio radio-info no-mtop no-mbot cbobox"><input type="radio" class="rel_type" <?= $value['versions'] == $product['versions'] ? 'checked' : '' ?> name="using" value="<?= $counter_bom ?>" id="radio_<?= $key ?>"><label for="radio_<?= $key ?>"><?= lang('choose') ?></label></div>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" onclick="revBom(this)"><i class="fa fa-remove"></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php $counter_bom++; ?>
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
        </div>
        <div class="modal-footer">
            <div class="hidden div-view">
                <a data-tnh="modal" class="tnh-modal md-view" href="<?= base_url('admin/products/view_product/'.$id) ?>" data-toggle="modal" data-target="#myModal"><?= lang('view') ?></a>
            </div>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close') ?></button>
            <button type="submit" class="btn btn-primary edit"><?= _l('edit') ?></button>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    var counter = <?= $counter ?>;
    var counter_bom = <?= $counter_bom ?>;
    var procedure_detail = <?= !empty($procedure_detail) ? json_encode($procedure_detail) : '{}' ?>;
    var warehouses = <?= !empty($warehouses) ? json_encode($warehouses) : '{}' ?>;
    var boms = <?= !empty($boms) ? json_encode($boms) : '{}' ?>;
    var edit = 1;
</script>
<script type="text/javascript" src="<?= js('products.js?vs=1.1') ?>"></script>
<script>

    function getBOM() {
        var options = '<option></option>';
        $.each(boms, function(index, el) {
            options+= '<option value="'+ el.id +'">'+el.versions+'</option>';
        });
        return options;
    }

    function aBOM(el)
    {
        tbB = $(el).closest('table');
        tdNB = '<td class="td-number-b text-center"></td>';
        tdBoms = '<td class="td-bs">'+
            '<select name="bs[]" id="bs" data-placeholder="'+lang_core['choose']+'" class="bs modal-select2" style="width: 100%;">'+getBOM()+'</select>'+
        '</td>';
        tdRadio = '<td class="td-radio">'+
            '<div class="radio radio-info no-mtop no-mbot cbobox"><input type="radio" class="rel_type" name="using" value="'+counter_bom+'" id="radio_'+counter_bom+'"><label for="radio_'+counter_bom+'"><?= lang('choose') ?></label></div>'
        '</td>';

        tdRemove = '<td class="text-center">'+
            '<a class="fa fa-remove" onclick="revBom(this)"></a>'+
        '</td>';

        trBm = '<tr>'+
            tdNB+
            tdBoms+
            tdRadio+
            tdRemove+
        '</tr>';

        tbB.find('tbody').append(trBm);
        counter_bom++;
        $('select.bs').select2();
    }

    function revBom(el) {
        $(el).closest('tr').remove();
    }

    $(function(){
        // selectAjax('#category', false, 'admin/products/searchCategory');
        $('#category').select2({'allowClear': true});
        $('#category').val(<?= $product['category_id'] ?>).trigger('change');

        type_products = '<?= $product['type_products'] ?>';
        if (type_products == "semi_products_outside") {
            $('.tabs-bom').hide();
        }
        $('#type_products').change(function(event) {
            type_products = $(this).val();
            if (type_products == "semi_products_outside") {
                $('.tabs-bom').hide();
            } else {
                $('.tabs-bom').show();
            }
        });

        selectAjax('#colors', false, 'admin/products/searchColors');
        appValidateForm($('#edit-product'), {
           category: 'required',
           type_products: 'required',
           unit: 'required',
           code: 'required',
           name: 'required'
        }, editproduct);

        $('.remove-image').click(function(event) {
            $(this).closest('.col-md-2').remove();
            $('#remove_image').val(1);
        });

        function editproduct(form) {
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
                url : site.base_url+'admin/products/edit_product/<?= $product['id'] ?>',
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
                    // $('.modal-dialog .close').trigger('click');
                    $('.md-view')[0].click();
                } else {
                    alert_float('danger', data.message);
                    $('.edit').removeAttr('disabled', 'disabled');
                }
            })
            .fail(function() {
                alert_float('danger', 'error');
                $('.edit').removeAttr('disabled', 'disabled');
            });
            return false;
        }
        init_editor('textarea[name="note"]');
        init_selectpicker();
    })
</script>