<?php
$vValdateFrom = [];

$arrayObjectCustomer = [];
if(!empty($orders->detail))
{
    foreach($orders->detail as $Korder => $Vorder){?>
        <tr class="strMain">
            <td><?=($Korder+1)?></td>
            <td>
                <input type="hidden" name="items_update[<?=$Korder?>][type_items]" id="items_update[<?=$Korder?>][type_items]" class="form-control type_items" value="<?= !empty($Vorder->type_items) ? $Vorder->type_items : 'items' ?>">
                <input type="hidden"  name="items_update[<?=$Korder?>][id]" id="items_update[<?=$Korder?>][id]" value="<?=$Vorder->id?>" />
                <input type="hidden" class="c_product_items hide" name="items_update[<?=$Korder?>][id_product]" id="items_update[<?=$Korder?>][id_product]" value="<?=$Vorder->id_product?>" />
                <p class="code_product">
                    <?= ($Vorder->code_product) ?>
                </p>
                <div class="show-types mtop10">
                    <?php if ($Vorder->type_items == "items"): ?>
                        <span class="label label-success"><?= lang('ch_items') ?></span>
                    <?php elseif ($Vorder->type_items == "products"): ?>
                        <span class="label label-warning"><?= lang('tnh_products') ?></span>
                    <?php endif ?>
                </div>
            </td>
            <td class="text-center">
                <?php $Vorder->avatar = !empty($Vorder->avatar) ? $Vorder->avatar : 'assets/images/preview-not-available.jpg';?>
                <img src="<?=base_url($Vorder->avatar);?>" class="c_img_item">
            </td>
            <td>
                <p class="name_product mtop20">
                    <?php echo !empty($Vorder->name) ? $Vorder->name : $Vorder->name_product;?>
                </p>
            </td>
            <td>
                <input class="form-control c_quantity width100" onchange="C_formatNumBerKeyUp(this)" name="items_update[<?=$Korder?>][quantity]" id="items_update[<?=$Korder?>][quantity]" value="<?=number_format($Vorder->quantity);?>">
            </td>
            <td>
                <input class="form-control c_price width130" onchange="C_formatNumBerKeyUp(this)" name="items_update[<?=$Korder?>][price]" id="items_update[<?=$Korder?>][price]" value="<?=number_format($Vorder->price)?>">
            </td>
            <td>
                <div class="input-group">
                    <input class="form-control c_discount width100 " name="items_update[<?=$Korder?>][discount]" onchange="C_formatNumBerKeyUp(this)" value="<?=number_format($Vorder->discount)?>">
                    <div class="input-group-addon group_addon">
                        <div class="radio pull-left">
                            <input type="radio" class="c_type_discount" name="items_update[<?=$Korder?>][type_discount]" value="1" <?= ($Vorder->type_discount == 1 ? 'checked': '') ?>>
                            <label class="small">%</label>
                        </div>
                        <div class="radio pull-right">
                            <input type="radio" class="c_type_discount" name="items_update[<?=$Korder?>][type_discount]" value="2" <?= ($Vorder->type_discount == 2 ? 'checked': '') ?>>
                            <label class="small"><?=_l('cong_money')?></label>
                        </div>
                    </div>
                </div>
            </td>

            <td>
                <p class="c_total mtop10">
                    <?=number_format($Vorder->grand_total)?>
                </p>
            </td>
            <td>
                <input style="width: 150px;min-width: 150px; max-width: 150px" class="c_customer"  name="items_update[<?=$Korder?>][id_customer]" value="<?=$Vorder->type_customer.'_'.$Vorder->id_customer?>">
                <?php
                    $arrayObjectCustomer[] = [
                           'name' => 'items_update['.$Korder.'][id_customer]',
                           'id' => $Vorder->type_customer.'_'.$Vorder->id_customer
                    ];
                ?>
            </td>
            <td>
                <input class="form-control c_size width100" name="items_update[<?=$Korder?>][size]"  value="<?= $Vorder->size ?>">
            </td>
            <td>
                <div class="row-shipping" InitRow="<?=!empty($Vorder->shipping) ? (count($Vorder->shipping)) : 0?>">
                    <?php if(!empty($Vorder->shipping)){?>
                        <?php foreach($Vorder->shipping as $kShipping => $vShipping){?>
                            <div class="row-0 row-index">
                                <div class="col-md-1 plef0 pright0">
                                    <p class="mtop10"><a class="DeleteInitRow text-danger pointer">x</a></p>
                                    <input type="hidden" name="items_update[<?=$Korder?>][shipping][<?=$kShipping?>][id]" value="<?= $vShipping->id ?>">
                                </div>
                                <div class="col-md-7 plef0 pright5">
                                    <input class="form-control c_date_shipping datepicker" name="items_update[<?=$Korder?>][shipping][<?=$kShipping?>][date_shipping]" id="items_update[<?=$Korder?>][shipping][<?=$kShipping?>][date_shipping]"  value="<?= _d($vShipping->date_shipping) ?>">
                                </div>
                                <div class="col-md-4 plef0 pright0">
                                    <input type="number" class="form-control c_quantity_shipping" name="items_update[<?=$Korder?>][shipping][<?=$kShipping?>][quantity_shipping]" id="items_update[<?=$Korder?>][shipping][<?=$kShipping?>][quantity_shipping]"  value="<?= $vShipping->quantity_shipping ?>">
                                </div>
                                <?php
                                    $vValdateFrom['items_update['.$Korder.'][shipping]['.$kShipping.'][date_shipping]'] = 'required';
                                    $vValdateFrom['items_update['.$Korder.'][shipping]['.$kShipping.'][quantity_shipping]'] = [
                                        'required' => true,
                                        'range' => [0, $Vorder->quantity]
                                    ];;
                                ?>
                                <div class="clearfix"></div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="col-md-12">
                    <a class="pointer" onclick="AddRowShipping('items_update', <?=$Korder?>, this)">
                        + <?=_l('cong_add_shipping_row')?>
                    </a>
                </div>
            </td>
            <td>
                <input style="width: 150px;min-width: 150px; max-width: 150px" class="c_unit_ship"  name="items_update[<?=$Korder?>][unit_ship]" value="<?=$Vorder->unit_ship?>">
		        <?php
		        $arrayObjectUnit_ship[] = [
			        'name' => 'items_update['.$Korder.'][unit_ship]',
			        'id' => $Vorder->unit_ship
		        ];
		        ?>
            </td>
            <td>
                <input class="form-control c_code_ship width150" name="items_update[<?=$Korder?>][code_ship]"  value="<?= $Vorder->code_ship ?>">
            </td>
            <td>
                <a class="btn btn-icon btn-danger DeleteItems"><i class="fa fa-times" aria-hidden="true"></i></a>
            </td>
        </tr>
        <?php
            $vValdateFrom['items_update['.$Korder.'][id]'] = 'required';
            $vValdateFrom['items_update['.$Korder.'][id_product]'] = 'required';
            $vValdateFrom['items_update['.$Korder.'][quantity]'] = [
                'required' => true,
                'min' => [1]
            ];
            $vValdateFrom['items_update['.$Korder.'][price]'] = 'required';
        ?>
<?php }
} else {?>
    <tr class="strMain" index="0">
        <td>1</td>
        <td>
            <input type="hidden" name="items[0][type_items]" id="items[0][type_items]" class="form-control type_items" value="">
            <input style="width: 200px;" data-placeholder="<?=_l('ch_itemss')?>" class="c_product_items  with-ajax" name="items[0][id_product]" id="items[0][id_product]" tabindex="-98" title="<?= _l('cong_pls_selected_option') ?>"></input>
            <div class="show-types mtop10"></div>
        </td>
        <td class="text-center">
            <img src="<?=base_url()?>assets/images/preview-not-available.jpg" class="c_img_item">
        </td>
        <td>
            <p class="name_product mtop20"></p>
        </td>
        <td>
            <input class="form-control c_quantity width100" onchange="C_formatNumBerKeyUp(this)" name="items[0][quantity]" id="items[0][quantity]" value="1">
        </td>
        <td>
            <input class="form-control c_price width130" onchange="C_formatNumBerKeyUp(this)" name="items[0][price]" id="items[0][price]" value="">
        </td>
        <td>
            <div class="input-group">
                <input class="form-control c_discount width100 " name="items[0][discount]" value="">
                <div class="input-group-addon group_addon">
                    <div class="radio pull-left">
                        <input type="radio" class="c_type_discount" name="items[0][type_discount]" value="1" checked>
                        <label class="small">%</label>
                    </div>
                    <div class="radio pull-right">
                        <input type="radio" class="c_type_discount" name="items[0][type_discount]" value="2">
                        <label class="small"><?=_l('cong_money')?></label>
                    </div>
                </div>
            </div>
        </td>
        <td>
            <p class="c_total mtop10"></p>
        </td>
        <td>
            <input class="c_customer" name="items[0][id_customer]"  style="width: 150px;min-width: 150px; max-width: 150px">
            <?php
            $arrayObjectCustomer[] = [
                'name' => 'items[0][id_customer]',
                'id' => ''
            ];
            ?>
        </td>
        <td>
            <input class="form-control c_size width150" name="items[0][size]">
        </td>
        <td>
            <div class="row-shipping" InitRow="0"></div>
            <div class="col-md-12">
                <a class="pointer" onclick="AddRowShipping('items', 0, this)">
                    + <?=_l('cong_add_shipping_row')?>
                </a>
            </div>
        </td>
        <td>
            <input style="width: 150px;min-width: 150px; max-width: 150px" class="c_unit_ship"  name="items[0][unit_ship]" value="">
		    <?php
		    $arrayObjectUnit_ship[] = [
			    'name' => 'items[0][unit_ship]',
			    'id' => ''
		    ];
		    ?>
        </td>
        <td>
            <input class="form-control c_code_ship width150" name="items[0][code_ship]"  value="">
        </td>
        <td>
            <a class="btn btn-icon btn-danger DeleteItems"><i class="fa fa-times" aria-hidden="true"></i></a>
        </td>
    </tr>
    <?php
        $vValdateFrom['items[0][id]'] = 'required';
        $vValdateFrom['items[0][id_product]'] = 'required';
        $vValdateFrom['items[0][quantity]'] = 'required';
        $vValdateFrom['items[0][price]'] = 'required';
    ?>
<?php } ?>
<script>
    var vValdateFrom = <?=!empty($vValdateFrom) ? json_encode($vValdateFrom) : '[]' ?>;

    $(function(e) {
        ajaxSelectCallBack('input.c_product_items', "<?=admin_url('orders/SearchProductItems')?>", '', '', false);
        var arrayObjectCustomer = <?=!empty($arrayObjectCustomer) ? json_encode($arrayObjectCustomer) : []?>;

        $.each(arrayObjectCustomer, function(iCus, vCus){
            console.log(vCus.id)
            ajaxSelectGroupOption_C('input[name="'+vCus.name+'"]', admin_url+'orders/SearchObjectItems/', vCus.id, '');
        })

        var arrayObjectUnit_ship = <?=!empty($arrayObjectUnit_ship) ? json_encode($arrayObjectUnit_ship) : []?>;
        $.each(arrayObjectUnit_ship, function(iCus, vCus){
            ajaxSelectNotImg('input[name="'+vCus.name+'"]', admin_url+'orders/SearchUnit_ship/', vCus.id, '');
        })
    })
</script>
