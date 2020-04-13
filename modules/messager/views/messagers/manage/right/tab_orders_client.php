<?php if(!empty($data)){ ?>
    <?php
        $CI =& get_instance();
        $this->load->model('orders_model');
    ?>
        <div class="div_advisory panel panel-info mbot10">
            <div class="panel-heading">
                <?=_l('cong_orders')?>
            </div>
            <div class="mleft5 mright5 mbot10">
                <table class="table table-not-top-bot">
                    <thead>
                        <tr>
                            <th><b><?=_l('cong_t_item')?></b></th>
                            <th colspan="4">
                                <b class="mright5"><?=_l('cong_t_price')?></b>
                                X
                                <b class="mleft5 mright5 text-info"><?=_l('cong_quantity_short')?></b>
                                -
                                <b class="mleft5 mright5 text-warning"><?=_l('cong_discount_short')?></b>
                                =
                                <b class="mleft5 mright5 text-danger"><?=_l('cong_t_money')?></b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $key => $value){?>
                            <tr>
                                <td colspan="5" class="panel-heading <?=$value->status == '-2' ? 'panel-warning' : ($value->status == '-3' ? 'panel-danger' : '')?>">

                                    <div class="mbot5 pull-left">
                                        <a target="_blank" href="<?=base_url('messager/view_detail_orders/'.$value->id)?>">
                                            <?= $value->prefix . $value->code .' - '. _d($value->date)  ?>
                                        </a>
                                        <?php if(!empty($value->draft)) {?>
                                            <div class="mtop5">
                                                <a class="text-warning" onclick="moved_orders_primary(<?= $value->id ?>)">
                                                    <b> <?=_l('cong_war_to_orders_primary')?> </b>
                                                </a>
                                            </div>
                                            <div class="mtop5">
                                                <a class="text-danger btn btn-icon" onclick="DeleteOrders(<?= $value->id ?>)">
                                                    <b> <?=_l('delete')?> </b>
                                                </a>
                                            </div>
                                        <?php } ?>

                                        <div class="mtop5">
                                            <a class="text-danger" data-toggle="collapse" data-target=".collapseOrder_<?= $value->id ?>">
                                                <b> <?=_l('more')?> </b>
                                            </a>
                                        </div>
                                    </div>
	                                <?php if(!empty($value->draft)) {?>
                                        <span class="inline-block label label-danger pointer  pull-right mleft5"> <?=_l('cong_draft')?></span>
	                                <?php } ?>

                                    <?php if(empty($value->draft)) {?>
                                        <?php $payment = get_table_where('tblpayment_order', array('id_order' => $value->id));?>
                                        <?php if( !empty($payment) ) {?>
                                            <span class="inline-block label label-warning pointer menu-receipts pull-right"><?=_l('ch_payment_count')?> <?=count($payment);?>
                                                <div class="content-menu hide">
                                                    <div class="table_popover padding10">
                                                        <div class="table_popover_head text-center">
                                                            <div class="pull-left wap-head">
                                                                <span class="text-center"><?=_l('ch_code_number')?></span>
                                                            </div>
                                                            <div class="pull-left wap-head">
                                                                <span class="text-center"><?=_l('date')?></span>
                                                            </div>
                                                            <div class="pull-left wap-head">
                                                                <span class="text-center"><?=_l('als_staff')?></span>
                                                            </div>
                                                            <div class="pull-left wap-head">
                                                                <span class="text-center"><?=_l('amount')?></span>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                    <div class="table_popover_body text-center">
                                                        <?php
                                                        $total = 0;
                                                         foreach ($payment as $kch => $vch) { ?>
                                                         <div class="wap-body view_payment" data-id=" <?= $vch['id'] ?> ">
                                                            <span class="text-center pull-left"> <?= $vch['prefix'] . $vch['code'] ?> </span>
                                                            <span class="text-center pull-left"><?= _dt($vch['date']) ?></span>
                                                            <span class="text-center pull-left"><?= get_staff_full_name($vch['staff_id']) ?></span>
                                                            <span class="text-right pull-left"><?= number_format($vch['total_voucher']) ?></span>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <?php
                                                        $total += $vch['total_voucher'];
                                                        } ?>
                                                        <div class="wap-body" style="font-weight:bold;color:red">
                                                            <span class="text-center pull-left"><?=_l('cong_total')?></span>
                                                            <span class="text-center pull-left"></span>
                                                            <span class="text-center pull-left"></span>
                                                            <span class="text-right pull-right"><?= number_format($total) ?></span>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                </div>
                                            </span>
                                        <?php } else {?>
                                            <span class="inline-block label label-warning pointer  pull-right"><?=_l('ch_payment_no')?></span>
                                        <?php }?>
                                    <?php }?>
                                </td>
                            </tr>
                            <?php foreach($value->detail as $kDetail => $vDetail) {?>
                                    <tr id-data="collapseOrder_<?= $value->id ?>" class="collapseOrder collapseOrder_<?= $value->id ?> collapse">
                                        <td>
                                            <p class="mbot5">
                                                <?=$vDetail->name?>
                                            </p>
                                            <p class="text-left mtop10">
                                            <?php if ($vDetail->type_items == "items"): ?>
                                                <span class="label label-success">
                                                    <?= lang('ch_items') ?>
                                                </span>
                                            <?php elseif ($vDetail->type_items == "products"): ?>
                                                <span class="label label-warning">
                                                    <?= lang('tnh_products') ?>
                                                </span>
                                            <?php endif ?>
                                            </p>

                                        </td>
                                        <td colspan="4">
                                            <p class="text-left">
                                                <b class="mright5"><?=number_format_data($vDetail->price)?> đ</b> x
                                                <b class="mleft5 mright5 text-info"><?=number_format_data($vDetail->quantity)?></b> -
                                                <b class="mleft5 mright5 text-warning"><?=number_format_data($vDetail->money_discount)?> đ</b> =
                                                <b class="mleft5 mright5 text-danger"><?=number_format_data($vDetail->grand_total)?> đ</b>
                                            </p>
                                            <p class="text-left mtop10">
                                                <?php
                                                    if(empty($value->draft)){
                                                        $activeStatus = get_table_where(' tblorders_step', [
                                                                'id_orders_item' => $vDetail->id,
                                                                'active' => 1
                                                        ], 'order_by desc', 'row');
                                                        if(!empty($activeStatus))
                                                        {
                                                            $colorSpan = !empty($activeStatus->color) ? $activeStatus->color : '';
                                                            echo '<span class="label" style="border: 1px solid '.$colorSpan.';color: '.$colorSpan.';">'.$activeStatus->name_procedure.'</span>';
                                                        }
                                                        else
                                                        {
                                                            echo '<span class="label label-warning">'._l('cong_orders_warning').'</span>';
                                                        }
                                                    }
                                                ?>
                                            </p>
                                        </td>
                                    </tr>
                                <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
<?php } else { ?>
    <div class="div_advisory panel panel-info">
        <div class="panel-heading">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            <?=_l('cong_orders')?>
        </div>

        <div class="panel-body padding-5">
            <p class="text-danger"><?=_l('cong_not_find_orders')?></p>
        </div>
    </div>
<?php } ?>
<div id="payment_order_data_view"></div>