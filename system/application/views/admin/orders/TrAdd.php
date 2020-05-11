<?php
$vValdateFrom = [];
$arrayObjectCustomer = [];
if(!empty($orders->detail))
{
    foreach($orders->detail as $Korder => $Vorder){?>
        <tr class="strMain">
            <td><?=($Korder+1)?></td>
            <td>
                <input type="hidden" name="items[<?=$Korder?>][type_items]" id="items[<?=$Korder?>][type_items]" class="form-control items" value="<?= !empty($Vorder->type_items) ? $Vorder->type_items : 'items' ?>">
                <input type="hidden" name="items[<?=$Korder?>][id_product]" id="items[<?=$Korder?>][id_product]" value="<?=$Vorder->id_product?>" />
                <p class="code_product">
                    <?php echo $Vorder->code_items;?>
                </p>
                <div class="show-types mbot10">
                    <?php if ($Vorder->type_items == "items"): ?>
                        <span class="label label-success"><?= lang('ch_items') ?></span>
                    <?php elseif ($Vorder->type_items == "products"): ?>
                        <span class="label label-warning"><?= lang('tnh_products') ?></span>
                    <?php endif ?>
                </div>
            </td>
            <td class="text-center">
                <img src="<?=base_url($Vorder->avatar);?>" class="c_img_item">
            </td>
            <td>
                <p class="name_product mtop20">
                    <?php echo $Vorder->name;?>
                </p>
            </td>
            <td>
                <input class="form-control c_quantity width100" onchange="C_formatNumBerKeyUp(this)" name="items[<?=$Korder?>][quantity]" id="items[<?=$Korder?>][quantity]" value="<?=number_format($Vorder->quantity);?>">
            </td>
            <td>
                <input class="form-control c_price width130" onchange="C_formatNumBerKeyUp(this)" name="items[<?=$Korder?>][price]" id="items[<?=$Korder?>][price]" value="<?=number_format($Vorder->price)?>">
            </td>
            <td>
                <div class="input-group">
                    <input class="form-control c_discount width100 " name="items[<?=$Korder?>][discount]" onchange="C_formatNumBerKeyUp(this)" value="<?=number_format($Vorder->discount)?>">
                    <div class="input-group-addon group_addon">
                        <div class="radio pull-left">
                            <input type="radio" class="c_type_discount" name="items[<?=$Korder?>][type_discount]" value="1" <?= ($Vorder->type_discount == 1 ? 'checked': '') ?>>
                            <label class="small">%</label>
                        </div>
                        <div class="radio pull-right">
                            <input type="radio" class="c_type_discount" name="items[<?=$Korder?>][type_discount]" value="2" <?= ($Vorder->type_discount == 2 ? 'checked': '') ?>>
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
                <input style="width: 150px;min-width: 150px; max-width: 150px" class="c_customer"  name="items[<?=$Korder?>][id_customer]" value="<?=$Vorder->type_customer.'_'.$Vorder->id_customer?>">
                <?php
                $arrayObjectCustomer[] = [
                    'name' => 'items['.$Korder.'][id_customer]',
                    'id' => $Vorder->type_customer.'_'.$Vorder->id_customer
                ];
                ?>
            </td>
            <td>
                <input class="form-control c_size width100" name="items[<?=$Korder?>][size]"  value="<?= $Vorder->size ?>">
            </td>
            <td>
                <div class="row-shipping" InitRow="<?=!empty($Vorder->shipping) ? (count($Vorder->shipping)) : 0?>">
                    <?php if(!empty($Vorder->shipping)){?>
                        <?php foreach($Vorder->shipping as $kShipping => $vShipping){?>
                            <div class="row-0 row-index">
                                <div class="col-md-1 plef0 pright0">
                                    <p class="mtop10"><a class="DeleteInitRow text-danger pointer">x</a></p>
                                    <input type="hidden" name="items[<?=$Korder?>][shipping][<?=$kShipping?>][id]" value="<?= $vShipping->id ?>">
                                </div>
                                <div class="col-md-7 plef0 pright5">
                                    <input class="form-control c_date_shipping datepicker" name="items[<?=$Korder?>][shipping][<?=$kShipping?>][date_shipping]" id="items[<?=$Korder?>][shipping][<?=$kShipping?>][date_shipping]"  value="<?= _d($vShipping->date_shipping) ?>">
                                </div>
                                <div class="col-md-4 plef0 pright0">
                                    <input type="number" class="form-control c_quantity_shipping " name="items[<?=$Korder?>][shipping][<?=$kShipping?>][quantity_shipping]" id="items_update[<?=$Korder?>][shipping][<?=$kShipping?>][quantity_shipping]"  value="<?= $vShipping->quantity_shipping ?>">
                                </div>
                                <?php
                                    $vValdateFrom['items['.$Korder.'][shipping]['.$kShipping.'][date_shipping]'] = 'required';
                                    $vValdateFrom['items['.$Korder.'][shipping]['.$kShipping.'][quantity_shipping]'] = [
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
                    <a class="pointer" onclick="AddRowShipping('items', <?=$Korder?>, this)">
                        + <?=_l('cong_add_shipping_row')?>
                    </a>
                </div>
            </td>
            <td>
                <input style="width: 150px;min-width: 150px; max-width: 150px" class="c_unit_ship"  name="items[<?=$Korder?>][unit_ship]" value="<?=$Vorder->unit_ship?>">
                <?php
                $arrayObjectUnit_ship[] = [
                    'name' => 'items['.$Korder.'][unit_ship]',
                    'id' => $Vorder->unit_ship
                ];
                ?>
            </td>
            <td>
                <input class="form-control c_code_ship width150" name="items[<?=$Korder?>][code_ship]"  value="<?= $Vorder->code_ship ?>">
            </td>

            <td>
                <a class="btn btn-icon btn-danger DeleteItems"><i class="fa fa-times" aria-hidden="true"></i></a>
            </td>
        </tr>
        <?php
            $vValdateFrom['items['.$Korder.'][id]'] = 'required';
            $vValdateFrom['items['.$Korder.'][id_product]'] = 'required';
            $vValdateFrom['items['.$Korder.'][quantity]'] = [
                'required' => true,
                'min' => [1]
            ];
            $vValdateFrom['items['.$Korder.'][price]'] = 'required';
        ?>
<?php }
    $numDetail = count($orders->detail);
}?>
<script>
    var vValdateFrom = <?=!empty($vValdateFrom) ? json_encode($vValdateFrom) : '[]' ?>

        $(function(e) {
            ajaxSelectCallBack('input.c_product_items', "<?=admin_url('orders/SearchProductItems')?>", '', '', false);
            var arrayObjectCustomer = <?=!empty($arrayObjectCustomer) ? json_encode($arrayObjectCustomer) : []?>;
            $.each(arrayObjectCustomer, function(iCus, vCus){
                ajaxSelectGroupOption_C('input[name="'+vCus.name+'"]', admin_url+'orders/SearchObjectItems/', vCus.id, '');
            })

            var arrayObjectUnit_ship = <?=!empty($arrayObjectUnit_ship) ? json_encode($arrayObjectUnit_ship) : []?>;
            $.each(arrayObjectUnit_ship, function(iCus, vCus){
                ajaxSelectNotImg('input[name="'+vCus.name+'"]', admin_url+'orders/SearchUnit_ship/', vCus.id, '');
            })
        })
</script>
