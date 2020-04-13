<?php init_head(); ?>
<?php $this->load->view('admin/orders/style_css');?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
            if (empty($orders) || (!empty($orders) && $orders->status == 0)){
                $action = true;
            }
			$action = true;
            if(!empty($action)) {
			    echo form_open($this->uri->uri_string(), array('id' => 'orders-form', 'class' => '_transaction_form orders-form'));
            }
			?>
			<div>
				<div class="panel_s">
				 	<div class="additional"></div>
				 	<div class="panel-body">
				 	<?php
						$type = '';
						if (!isset($orders))
							$type = 'info';
						elseif ($orders->status == -2)
							$type = 'warning';
						elseif ($orders->status == -3)
							$type = 'danger';
						elseif ($orders->status > 0)
							$type = 'primary';
						elseif ($orders->status == 0)
							$type = 'primary';
						elseif ($orders->status == -1)
							$type = 'success';

						?>
				 		<div class="ribbon <?= $type ?>">
				 			<?php
								if(!isset($orders)) {
									$status = _l('cong_add_new');
								}
								else {
									$status = $orders->name_status;
								}
							?>
				 			<span> <?= $status ?> </span>
						 </div>
						<h4 class="bold no-margin font-medium">
					     	<?php echo (!empty($title) ? $title : ''); ?>
                            <span class="label label-<?=!empty($type) ? $type : '' ?> mleft5 inline-block pointer">
                                <?=!empty($orders->name_status) ? $orders->name_status : $title ?>
                            </span>
					   	</h4>
						<hr />
				 		<div class="row">
				 			<div class="panel panel-primary">
								<div class="panel-heading"><?=_l('lead_general_info')?></div>
								<div class="panel-body">
                                    <table class="tnh-tb table-bordered table-hover dont-responsive-table m-group0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="number" class="control-label"> <?php echo _l('cong_code_orders'); ?> </label>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width: 100%">
                                                            <span class="input-group-addon"> <?php echo (isset($orders) ? ($orders->prefix) : 'DH');?>-</span>
                                                            <?php $value = (isset($orders) ? ($orders->code) : sprintf('%06d', ch_getMaxID('id', 'tblorders') + 1)); ?>
                                                            <input type="text" class="form-control" value="<?= $value ?>" readonly>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label for="date" class="control-label">
                                                        <?php echo _l('cong_date_create'); ?>
                                                    </label>
                                                </td>
                                                <td class="w100">
                                                    <?php $value = (isset($orders) ? _d($orders->date) : _d(date('Y-m-d'))); ?>
                                                    <?php echo render_date_input('date', '', $value); ?>
                                                    <!-- ngày thực hiện-->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="object" class="control-label">
                                                        <small class="req text-danger">* </small>
                                                        <span class="js-title-client"><?php echo _l('cong_name_system'); ?></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <?php
                                                        $value = (!empty($orders->id_quotes_orders) ? $orders->id_quotes_orders : (!empty($convert_quotes) ? $convert_quotes : ''));
                                                        echo form_hidden('id_quotes_orders', $value);
                                                    ?>
                                                    <?php $value = (isset($orders) ? $orders->client : ''); ?>

                                                    <div>
                                                        <?php $valueClient = (isset($orders) ? 'client_'.$orders->client : ''); ?>
                                                        <input id="object" name="object" style="width: 100%" value="<?= $valueClient?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <label for="advisory_lead_id" class="control-label">
                                                        <?php echo _l('promissory_advisory'); ?>
                                                    </label>
                                                </td>
                                                <td>
                                                    <?php
                                                        $value = (isset($orders) ? $orders->advisory_lead_id : '');
                                                        echo render_select('advisory_lead_id', $advisory_lead, array('id', 'full_code'), '', $value);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="shipping" class="control-label">
                                                        <?php echo _l('infomation_receiver'); ?>
                                                    </label>
                                                </td>
                                                <td>
                                                    <?php
                                                        $value = (isset($orders) ? $orders->shipping : '');
                                                       // echo render_select('shipping', $shipping, array('id', 'name', 'address'), 'cong_shipping', $value, [], [],'', '_select_input_group');
                                                        echo render_select_with_input_group('shipping', $shipping, ['id', 'name', 'address'], '', $value, '<a onclick="ViewShippingClient(\'shipping\')"><i class="fa fa-plus"></i></a>', ['data-actions-box' => true], [], '', '', false);
                                                    ?>
                                                </td>
                                                <td>
                                                    <label for="assigned" class="control-label">
                                                        <?php echo _l('cong_manage_orders'); ?>
                                                    </label>
                                                </td>
                                                <td>
                                                    <?php
                                                        $value = (isset($orders) ? $orders->assigned : '');
                                                        echo render_select('assigned', $staff, ['staffid', ['lastname', 'firstname']], '', $value);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p><?=_l('cong_type_pay')?>: </p> <!-- Hình thức thanh toán -->
                                                </td>
                                                <td>
		                                            <?php
		                                            $value = !empty($orders->mode_payment) ? $orders->mode_payment : '';
		                                            echo render_select('mode_payment', $mode_payment, ['id', 'name'], '', $value);
		                                            ?>
                                                </td>
                                                <td>
                                                    <label for="date_want_to_receive" class="control-label hide">
                                                        <?php echo _l('date_want_to_receive'); ?>
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="hide">
                                                        <?php
                                                            $value = (isset($orders) ? _dt($orders->shipping) : '');
                                                           echo render_datetime_input('date_want_to_receive', '', $value);
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="note" class="control-label">
                                                        <?php echo _l('cong_note'); ?>
                                                    </label>
                                                </td>
                                                <td colspan="3">
                                                    <?php $value = (isset($orders) ? $orders->note : ""); ?>
                                                    <?php echo render_textarea('note', '', $value); ?>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
								</div>
							</div>
                            <div class="panel panel-info">
                                <div class="panel-heading"><?=_l('cong_info_items')?></div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered dont-responsive-table mtop0 mbot0" id="table-item-orders">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <a onclick="AddItemOrder()" class="btn font-20 padding-0">+</a>
                                                    </th>
                                                    <th>
                                                        <?=_l('cong_item_code')?>
                                                    </th> <!--mã hàng-->
                                                    <th><?=_l('cong_item_image')?></th> <!--Hình ảnh-->
                                                    <th><?=_l('cong_item_name')?></th> <!--Tên hàng-->
                                                    <th><?=_l('cong_quantity')?></th> <!--Số lượng -->
                                                    <th><?=_l('cong_price_thinh_html')?></th> <!--Giá thỉnh-->
                                                    <th><?=_l('cong_sale')?></th> <!--Chiết khấu-->
                                                    <th><?=_l('cong_info_money')?> </th> <!--Thành tiền-->
                                                    <th><?=_l('cong_buy_gif')?></th> <!--mua cho-->
                                                    <th><?=_l('cong_size')?></th>
                                                    <th class="minwidth150px"><?=_l('cong_shipment_date')?></th>
                                                    <th><?=_l('cong_unit_ship')?></th>
                                                    <th><?=_l('cong_code_ship')?></th>
                                                    <th><?=_l('options')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if(!empty($convert_quotes))
                                                {
                                                    include_once(APPPATH.'views/admin/orders/TrAdd.php');
                                                }
                                                else
                                                {
                                                    include_once(APPPATH.'views/admin/orders/TrUpdate.php');
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="15"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 pull-right">
                                <div class="form-group col-md-6">
                                    <label for="currency" class="control-label"><?=_l('invoice_add_edit_currency')?></label>
                                    <select class="selectpicker" id="currency" name="currencies_id" data-width="100%" data-live-search="true">
                                        <?php foreach ($dataCurrency as $key => $value) { ?>
                                            <?php if(!empty($orders)) { ?>
                                                <?php if($orders->currencies_id == $value['id']) { ?>
                                                    <option value="<?=$value['id']?>" data-subtext="<?=$value['symbol']?> [<?=number_format($value['amount_to_vnd'])?>]" selected><?=$value['name']?></option>
                                                <?php } else { ?>
                                                    <option value="<?=$value['id']?>" data-subtext="<?=$value['symbol']?> [<?=number_format($value['amount_to_vnd'])?>]"><?=$value['name']?></option>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if($key==0) { ?>
                                                    <option value="<?=$value['id']?>" data-subtext="<?=$value['symbol']?> [<?=number_format($value['amount_to_vnd'])?>]" selected><?=$value['name']?></option>
                                                <?php } else { ?>
                                                    <option value="<?=$value['id']?>" data-subtext="<?=$value['symbol']?> [<?=number_format($value['amount_to_vnd'])?>]"><?=$value['name']?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="advisory_apply_id" class="control-label"><?=_l('advisory_apply')?></label>
                                    <select class="selectpicker" id="advisory_apply_id" name="advisory_apply_id" data-width="100%" data-live-search="true">
                                        <?php foreach ($dataAdvisory as $key => $value) { ?>
                                            <?php if(!empty($orders)) { ?>
                                                <?php if($orders->advisory_apply_id == $value['id']) { ?>
                                                    <option value="<?=$value['id']?>" selected><?=$value['name']?></option>
                                                <?php } else { ?>
                                                    <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if($key==0) { ?>
                                                    <option value="<?=$value['id']?>" selected><?=$value['name']?></option>
                                                <?php } else { ?>
                                                    <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <!-- thống kê -->
                            <div class="col-md-6 mtop10 mbot20">
                                <div class="panel panel-info mbot0">
                                    <div class="panel-heading"><?=_l('cong_info_pay')?></div>
                                    <div class="panel-body">
                                        <table class="table table-bordered mtop0 mP0 mbot0 wap-table-border">
                                            <thead>
                                                <tr>
                                                    <th><b><?=_l('cong_info_items')?></b></th>
                                                    <th class="text-center"><b><?= _l('VND') ?></b></th>
                                                    <th><b><?=_l('cong_currencies')?></b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><?=_l('cong_num_item_order')?>:</p> <!-- Số mặt hàng trong giõ hàng-->
                                                    </td>
                                                    <td>
                                                        <p class="c_number_items"><?=!empty($orders->total_item) ? number_format($orders->total_item) : '0' ?></p> <!-- Số mặt hàng trong giõ hàng-->
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><?=_l('cong_cost_trans')?>:</p> <!-- Tổng chi phí vận chuyễn -->
                                                    </td>
                                                    <td>
                                                        <input name="total_cost_trans" class="form-control c_total_cost_trans text-right" onchange="C_formatNumBerKeyUp(this)" value="<?=!empty($orders->total_cost_trans) ? number_format($orders->total_cost_trans) : '0' ?>"/>
                                                        <!-- Tổng chi phí vận chuyễn -->
                                                    </td>
                                                    <td>
                                                        <p></p> <!-- Tổng chi phí vận chuyễn -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><?= _l('cong_guest_giving')?>: </p> <!-- Khách tặng thêm -->
                                                    </td>
                                                    <td>
                                                        <input name="guest_giving" class="form-control c_guest_giving" onchange="C_formatNumBerKeyUp(this)" value="<?=!empty($orders->guest_giving) ? number_format($orders->guest_giving) : '0' ?>">
                                                    </td>
                                                    <td>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><?= _l('cong_total_orders')?>: </p> <!-- Tổng giá trị đơn hàng -->
                                                    </td>
                                                    <td>
                                                        <p class="total_orders">
                                                            <?=!empty($orders->grand_total) ? number_format($orders->grand_total) : '0' ?>
                                                        </p> <!-- Tổng giá trị đơn hàng -->
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><?= _l('total_price_receipts')?>: </p> <!-- Tổng giá trị phiếu thu -->
                                                    </td>
                                                    <td>
	                                                    <?php
                                                            if(!empty($orders->id)) {
                                                                $payment = get_table_where_select_cong('tblpayment_order', ['id_order' => $orders->id], '', 'sum(total_voucher) as sum_total', 'row');
                                                            }
	                                                    ?>
                                                        <p class="total_receipts">
                                                            <?= !empty($payment) ? number_format_data($payment->sum_total) : ''; ?> <!-- Tổng giá trị phiếu thu -->
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p> <?=_l('cong_total_order_debt')?>: </p> <!-- Khoản thu còn lại của khách hàng -->
                                                    </td>
                                                    <td>
                                                        <p class="rest_collect text-right">
                                                            <?= !empty($orders) ? number_format_data($orders->grand_total + $orders->guest_giving  - (!empty($payment) ? $payment->sum_total : 0)) : 0 ?>
                                                        </p>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- end thống kê -->
                            <!-- thống kê đơn vị tiền tệ khác -->
                            <div class="col-md-6 mtop10 mbot20">
                                <div class="panel panel-info mbot0">
                                    <div class="panel-heading"><?=_l('payment_world')?></div>
                                    <div class="panel-body">
                                        
                                        <table class="table table-bordered mtop0 mP0 mbot0 wap-table-border">
                                            <thead>
                                                <tr>
                                                    <th><b><?=_l('cong_info_items')?></b></th>
                                                    <th class="text-center"><b class="currency_title"><?=_l('VND')?></b></th>
                                                    <th><b><?=_l('cong_currencies')?></b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><?=_l('cong_num_item_order')?>:</p> <!-- Số mặt hàng trong giõ hàng-->
                                                    </td>
                                                    <td>
                                                        <p class="c_number_items text-right"><?=!empty($orders->total_item) ? number_format($orders->total_item) : '0' ?></p> <!-- Số mặt hàng trong giõ hàng-->
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><?=_l('cong_cost_trans')?>:</p> <!-- Tổng chi phí vận chuyễn -->
                                                    </td>
                                                    <td>
                                                        <p class="total_cost_trans_currency text-right"> <?=!empty($orders->total_cost_trans_international) ? number_format($orders->total_cost_trans_international) : '0' ?></p> <!-- Tổng chi phí vận chuyễn -->
                                                    </td>
                                                    <td>
                                                        <p></p> <!-- Tổng chi phí vận chuyễn -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><?= _l('cong_guest_giving')?>: </p> <!-- Khách tặng thêm -->
                                                    </td>
                                                    <td>
                                                        <p class="c_guest_giving_currency text-right">0</p> <!-- Khách tặng thêm -->
                                                    </td>
                                                    <td>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><?= _l('cong_total_orders')?>: </p> <!-- Tổng giá trị đơn hàng -->
                                                    </td>
                                                    <td>
                                                        <p class="total_orders_currency text-right"> <?=!empty($orders->grand_total_international) ? number_format($orders->grand_total_international) : '0' ?> </p> <!-- Tổng giá trị đơn hàng -->
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p><?= _l('total_price_receipts')?>: </p> <!-- Tổng giá trị phiếu thu -->
                                                    </td>
                                                    <td>
                                                        <p class="total_receipts_currency text-right">0</p> <!-- Tổng giá trị phiếu thu -->
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <p> <?=_l('cong_total_order_debt')?>: </p> <!-- Khoản thu còn lại của khách hàng -->
                                                    </td>
                                                    <td><p class="rest_collect_currency text-right"></p></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- end thống kê -->
						</div>
						<div class="clearfix"></div>
				 	</div>
			 	</div>
            <?php if(!empty($action)){?>
                <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                    <button class="btn btn-info only-save customer-form-submiter">
                        <?php echo _l( 'submit'); ?>
                    </button>
                </div>
            <?php } ?>
			</div>

			<?php
            if(!empty($action)) {
                echo form_close();
            }
            ?>
		</div>
	</div>
</div>


<?php init_tail(); ?>

<script>
    function addRules(rulesObj){
        for (var item in rulesObj){
            $('#'+item).rules('add',rulesObj[item]);
        }
    }

    function removeRules(rulesObj){
        for (var item in rulesObj){
            $('#'+item).rules('remove');
        }
    }

    var ruleClient = {
        client : {
            require: true,
        },
    };
    var ruleLead = {
        wap_lead : {
            require: true,
        },
    };

    var ValdateFrom = {
        date: 'required',
        assigned: 'required',
        shipping: 'required',
        advisory_lead_id: 'required'
    };
    if(vValdateFrom)
    {
        $.each(vValdateFrom, function(iV, vV){
            ValdateFrom[iV] = vV;
        })
    }

    $(function() {
        appValidateForm($('#orders-form'), ValdateFrom);
        resetCurrency();
    })
    var pls_option_select = "<?=_l('cong_pls_selected_option')?>";
    var lang_money = "<?=_l('cong_money')?>";
    $(window).bind("load", function() {
        // selectpicker_jax_item();
        selectpicker_jax_customer();
    })
    var UnitArray = <?=!empty($numDetail) ? $numDetail : 1?>;


    //Add items
    function AddItemOrder()
    {
        var _Tr = $('<tr class="strMain" index="'+UnitArray+'"></tr>');
        var td0 = $('<td></td>');
        var td1 = $('<td></td>');
        var SelectItem = $(
            '<input type="hidden" name="items['+UnitArray+'][type_items]" id="items['+UnitArray+'][type_items]" class="form-control type_items" value="">'+
            '<input data-placeholder="<?=_l('ch_itemss')?>"  class="c_product_items with-ajax" id="items['+UnitArray+'][id_product]"  name="items['+UnitArray+'][id_product]">'+
            '<div class="show-types mtop10"></div>'
        );
        td1.append(SelectItem);

        var td2 = $('<td class="text-center"><img src="'+site_url+'assets/images/preview-not-available.jpg" class="c_img_item"></td>');
        var td3 = $('<td><p class="name_product mtop20"></p></td>');
        var td4 = $('<td><input class="form-control c_quantity width100" min="1" onchange="C_formatNumBerKeyUp(this)" name="items['+UnitArray+'][quantity]" id="items['+UnitArray+'][quantity]" value="1"></td>');
        var td5 = $('<td><input class="form-control c_price width130" onchange="C_formatNumBerKeyUp(this)" name="items['+UnitArray+'][price]" id="items['+UnitArray+'][price]"></td>');
        var td6 = $('<td></td>');
        var div_input_group_td6 = $('<div class="input-group"><input class="form-control c_discount width100 " name="items['+UnitArray+'][discount]"></div>');

        var div_radiot6 = $('<div class="input-group-addon group_addon"></div>');
        div_radiot6.append($('<div class="radio pull-left"><input type="radio" class="c_type_discount" name="items['+UnitArray+'][type_discount]" checked value="1"><label class="small">%</label></div>'));
        div_radiot6.append($('<div class="radio pull-right"><input type="radio" class="c_type_discount" name="items['+UnitArray+'][type_discount]" value="2"><label class="small">'+lang_money+'</label></div>'));
        div_input_group_td6.append(div_radiot6);
        td6.append(div_input_group_td6);

        var td8 = $('<td><p class="c_total mtop10"></p></td>');
        var td9 = $('<td></td>');

        var SelectClient = $('<input class="c_customer" style="width: 250px;min-width: 250px; max-width: 250px" name="items['+UnitArray+'][id_customer]">');
        td9.append(SelectClient);

        var td10 = $('<td><input class="form-control c_size width100" name="items['+UnitArray+'][size]"></td>');
        var td11 = $('<td></td>');
        var Row_shipping = $('<div class="row-shipping" InitRow="0"></div>');
        var ShippingTd11 = $('<div class="col-md-12"></div>');
        ShippingTd11.append('<a class="pointer" onclick="AddRowShipping(\'items\', '+UnitArray+', this)">+ <?=_l('cong_add_shipping_row')?></a>');
        td11.append(Row_shipping);
        td11.append(ShippingTd11);

        var  td12 = $('<td><input style="width: 150px;min-width: 150px; max-width: 150px" class="c_unit_ship"  name="items['+UnitArray+'][unit_ship]" value=""></td>');
        var  td13 = $('<td><input class="form-control c_code_ship width150" name="items['+UnitArray+'][code_ship]"  value=""></td>');

        var tdRemove = $('<td><a class="btn btn-icon btn-danger DeleteItems"><i class="fa fa-times" aria-hidden="true"></i></a></td>');
        _Tr.append(td0);
        _Tr.append(td1);
        _Tr.append(td2);
        _Tr.append(td3);
        _Tr.append(td4);
        _Tr.append(td5);
        _Tr.append(td6);
        _Tr.append(td8);
        _Tr.append(td9);
        _Tr.append(td10);
        _Tr.append(td11);
        _Tr.append(td12);
        _Tr.append(td13);
        _Tr.append(tdRemove);

        $('#table-item-orders tbody').append(_Tr);
        ajaxSelectCallBack('input[name="items['+UnitArray+'][id_product]"]', "<?=admin_url('orders/SearchProductItems')?>", '', '', false);
        // selectpicker_jax_item('select[name="items['+UnitArray+'][id_product]"]');
        //selectpicker_jax_customer('select[name="items['+UnitArray+'][id_customer]"]', "<?//=admin_url('orders/getCustomerAjax')?>//");
        ajaxSelectGroupOption_C('input[name="items['+UnitArray+'][id_customer]"]', admin_url+'orders/SearchObjectItems/', '', '');

        ajaxSelectNotImg('input[name="items['+UnitArray+'][unit_ship]"]', admin_url+'orders/SearchUnit_ship/', '', '');
        UnitArray++;
        orderTR();
    }


    //Select Ajax khách hàng
    function selectpicker_jax_customer(_class = "", _url = "", dataArray = {})
    {
        var _data = {};
        if (typeof(csrfData) !== 'undefined') {
            _data[csrfData['token_name']] = csrfData['hash'];
        }
        _data['q'] = "{{{q}}}";
        var options = {
            ajax: {
                url: _url ? _url : "<?=admin_url('orders/getCustomerAjax')?>",
                type: "POST",
                dataType: "json",
                data: _data
            },
            locale: {
                emptyTitle: pls_option_select,
                statusInitialized: pls_option_select,
            },
            log: 3,
            preserveSelected: false,
            preprocessData: function(data) {
                var i,
                    l = data.length,
                    array = [];
                if (l) {
                    for (i = 0; i < l; i++) {
                        array.push(
                            $.extend(true, data[i], {
                                text: data[i].name_system,
                                value: data[i].userid,
                                data: {
                                    subtext: data[i].company,
                                }
                            })
                        );
                    }
                }
                return array;
            }
        };
        if($(_class).length)
        {
            $(_class).selectpicker().filter(".with-ajax").ajaxSelectPicker(options);
        }
        else
        {
            $(".selectpicker.c_customer.with-ajax").selectpicker().filter(".with-ajax").ajaxSelectPicker(options);
        }
    }

    //Xóa items
    $('body').on('click', '.DeleteItems', function(e){
        if(confirm('<?=_l('cong_you_must_delete_row')?>?'))
        {
            var Tr = $(this).parents('tr');
            Tr.remove();
            ReALL();
            orderTR();
        }
    })


    //Chọn sản phẩm
    $('body').on('change', '.c_product_items', function(e){
        console.log(e);
        if($(this).val())
        {
            var Tr = $(this).parents('tr');
            var option_select = $(this).find('option:selected');
            var data_subtext = e.added.name;
            console.log(e.added.img);
            if(e.added.img)
            {
                var img = e.added.img;
            }
            else
            {
                var img = 'download/preview_image';
            }
            var price = e.added.price;
            //tnh
            var type_items = e.added.type_items;
            Tr.find('.type_items').val(type_items);
            if (type_items == "items") {
                Tr.find('.show-types').html('<span class="label label-success"><?= lang('ch_items') ?></span>');
            } else if (type_items == "products") {
                Tr.find('.show-types').html('<span class="label label-warning"><?= lang('tnh_products') ?></span>');
            }
            //

            Tr.find('p.name_product').text(data_subtext);
            Tr.find('input.c_price').val(C_formatNumber(price));
            Tr.find('img.c_img_item').attr('src', "<?=base_url()?>"+img);

            Tr.find('input.c_quantity').trigger('change');

            var index = Tr.attr('index');
            ValdateFrom['items['+index+'][id_product]'] = 'required';
            ValdateFrom['items['+index+'][id_product]'] = 'required';
            ValdateFrom['items['+index+'][quantity]'] = {
                required : true,
                min : [1]
            };
            ValdateFrom['items['+index+'][price]'] = 'required';
            ValdateFrom['items['+index+'][price]'] = 'required';
            appValidateForm($('#orders-form'), ValdateFrom);
            if($('#table-item-orders tbody').find('tr:gt(-2)').html() == Tr.html())
            {
                AddItemOrder();
            }
        }
    })

    $('body').on('change', 'select.c_customer', function(e){
        if($(this).val())
        {
            var userid = $(this).val();
            var data = {};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['client'] = userid;
            $.post(admin_url+'orders/getShipping', data, function(data){
                data = JSON.parse(data);
                var option = "<option></option>";
                $.each(data, function(i, v){
                    option += '<option value="'+v.id+'" data-subtext="'+v.address+'">'+v.name+'</option>';
                })
                $('#shipping').html(option).selectpicker('refresh');
            })
        }
    })


    //Chang các input có giá trị thay đổi tiền
    $('body').on('change', 'input.c_quantity, input.c_price, input.c_discount, input.c_type_discount', function(e){
        var Tr = $(this).parents('tr.strMain');
        var quantity = intVal(Tr.find('input.c_quantity').val());
        var price = intVal(Tr.find('input.c_price').val());
        var discount = intVal(Tr.find('input.c_discount').val());
        var type_discount = Tr.find('input.c_type_discount:checked').val();
        var total = 0;
        if(type_discount == 1)
        {
            total = (quantity * price) - (quantity * price * (discount/100) );
        }
        else if(type_discount == 2)
        {
            total = (quantity * price) - (discount);
        }
        Tr.find('p.c_total').html(C_formatNumber(total));
        ReALL();
    })

    $('body').on('change', 'input.c_total_cost_trans', function(e){
        ReALL();
    })


    //Tính tổng lại
    function ReALL()
    {
        var TableItemBody = $('#table-item-orders tbody');
        var Tr = TableItemBody.find('tr');
        var CountItems = 0;
        var total = 0; // tổng thanh toán
        console.log(Tr)
        $.each(Tr, function(Ki, Vv){
            if($(Vv).find('select.c_product_items').val() || $(Vv).find('input.c_product_items').val())
            {
                CountItems++;

                var quantity = intVal($(Vv).find('input.c_quantity').val());
                var price = intVal($(Vv).find('input.c_price').val());
                var discount = intVal($(Vv).find('input.c_discount').val());
                var type_discount = $(Vv).find('input.c_type_discount:checked').val();
                if(type_discount == 1)
                {
                    total += (quantity * price) - (quantity * price * (discount/100) );
                }
                else if(type_discount == 2)
                {
                    total += (quantity * price) - (discount);
                }

            }
        })
        var total_cost_trans = intVal($('input[name="total_cost_trans"]').val());
        $('p.c_number_items').html(C_formatNumber(CountItems));

        total += total_cost_trans;

        total += intVal($('input[name="guest_giving"]').val());

        $('p.total_orders').html(C_formatNumber(total));

        total -= intVal($('.total_receipts').text());

        $('.rest_collect').html(C_formatNumber(total));
        resetCurrency();
    }

    function orderTR()
    {
        var Tr = $('#table-item-orders tbody tr');
        $.each(Tr, function(i, v){
            $(v).find('td:nth-child(1)').text(i+1);
        })
    }


    function ViewShippingClient(idSelect)
    {
        var object = $('#object').val();
        if(object != "")
        {
            object = object.split('_');
            var data = {};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['id'] = object[1];
            data['type'] = object[0];
            data['idSelect'] = 'shipping';
            $.post(admin_url+'orders/ViewModalShipping', data, function(data){
                data = JSON.parse(data);
                if(data.success)
                {
                    $('#cong_modal').html(data.data);
                }
                else
                {
                    alert_float(data.alert_type, data.message);
                }
            })
        }
    }

   function AddRowShipping(items = 'items',Rowint = 0, _this)
   {
        var Td = $(_this).parents('td');
        var RowShipping = Td.find('div.row-shipping');
        var initRow = parseInt(RowShipping.attr('InitRow')); // lấy dòng thêm bao nhiu
        var DivRowIndex = $('<div class="row-0 row-index"></div>');
        //div Delete
        var DivDelete = $('<div class="col-md-1 plef0 pright0"></div>');
        DivDelete.append('<p class="mtop10"><a class="text-danger DeleteInitRow">x</a></p>');

        //Div date
        var DivDate = $('<div class="col-md-7 plef0 pright5"></div>');
        DivDate.append('<input class="form-control c_date_shipping datepicker" name="'+items+'['+Rowint+'][shipping]['+initRow+'][date_shipping]" id="'+items+'['+Rowint+'][shipping]['+initRow+'][date_shipping]"  value="">');

       //Div Số lượng
        var DivQuantity = $('<div class="col-md-4 plef0 pright0"></div>');
        DivQuantity.append('<input class="form-control c_quantity_shipping" name="'+items+'['+Rowint+'][shipping]['+initRow+'][quantity_shipping]" id="'+items+'['+Rowint+'][shipping]['+initRow+'][quantity_shipping]"  value="">');

        DivRowIndex.append(DivDelete);
        DivRowIndex.append(DivDate);
        DivRowIndex.append(DivQuantity);
        DivRowIndex.append('<div class="clearfix"></div>');

        RowShipping.append(DivRowIndex);

        RowShipping.attr('InitRow', (initRow+1));
        init_datepicker();
       ValdateFrom[items+'['+Rowint+'][shipping]['+initRow+'][date_shipping]'] = 'required';
       ValdateFrom[items+'['+Rowint+'][shipping]['+initRow+'][quantity_shipping]'] = {
           required : true,
           min : [1]
       };
       appValidateForm($('#orders-form'), ValdateFrom);
   }

   $('body').on('click', '.DeleteInitRow', function(e){
       $(this).parents('.row-index').remove();
   })

   $('body').on('change', '.c_quantity_shipping', function(e){
       var Tr = $(this).parents('tr');
       var Td = $(this).parents('td');
       var RowShipping = Td.find('div.row-shipping');
       var QuantityShipping = Td.find('.c_quantity_shipping');

       var quantity = intVal(Tr.find('.c_quantity').val());
       console.log(QuantityShipping)
       $.each(QuantityShipping, function(i, v){
           var Vquantity = intVal($(v).val());
           var id = $(v).attr('id');
           var afterQuantity = quantity - Vquantity;
           if(afterQuantity > 0)
           {
               ValdateFrom[id] = {
                   required : true,
                   range : [0, Vquantity]
               };
           }
           else
           {
               ValdateFrom[id] = {
                   required : true,
                   range : [0, quantity]
               };
           }
           quantity -= Vquantity;
           appValidateForm($('#orders-form'), ValdateFrom);
       })
    })

    $(document).ready(function() {
        $('.table-responsive').on('show.bs.dropdown', function () {
           $('.table-responsive').css( "overflow", "inherit" );
        });

        $('.table-responsive').on('hide.bs.dropdown', function () {
           $('.table-responsive').css( "overflow", "auto" );
        })
    });











































    //hoàng crm bổ xung
    $('#client').change(function(e){
        if($(this).val())
        {
            <?php if(isset($orders)) { ?>
                var idMain = <?=$orders->id?>;
            <?php } else { ?>
                var idMain = '';
            <?php } ?>
            var userid = $(this).val();
            var data = {};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            $.post(admin_url+'orders/getAdvisory_lead/'+userid+'/client/'+idMain, data, function(data){
                data = JSON.parse(data);
                var option = "<option></option>";
                $.each(data, function(i, v){
                    option += '<option value="'+v.id+'">'+v.full_code+'</option>';
                })
                $('#advisory_lead_id').html(option).selectpicker('refresh');
            })
        }
    })

    $('#wap_lead').change(function(e){
        if($(this).val())
        {
            <?php if(isset($orders)) { ?>
                var idMain = <?=$orders->id?>;
            <?php } else { ?>
                var idMain = '';
            <?php } ?>
            var userid = $(this).val();
            var data = {};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            $.post(admin_url+'orders/getAdvisory_lead/'+userid+'/lead/'+idMain, data, function(data){
                data = JSON.parse(data);
                var option = "<option></option>";
                $.each(data, function(i, v){
                    option += '<option value="'+v.id+'">'+v.full_code+'</option>';
                })
                $('#advisory_lead_id').html(option).selectpicker('refresh');
            })
        }
    })

    function swap_client(type) {
        if(type == 'lead') {
            $('.js-client').addClass('hide');
            $('.js-lead').removeClass('hide');
            removeRules(ruleClient);
            addRules(ruleLead);

            $('.js-title-client').text('<?=_l('cong_lead')?>');
            selectpicker_jax_lead();
            //reset select
            var option = "<option></option>";
            $('#shipping').html(option).selectpicker('refresh');
            $('#advisory_lead_id').html(option).selectpicker('refresh');
            $('#client').html(option).selectpicker('refresh');
            $('#wap_lead').html(option).selectpicker('refresh');
            //end
        }
        else if(type == 'client') {
            $('.js-client').removeClass('hide');
            $('.js-lead').addClass('hide');
            addRules(ruleClient);
            removeRules(ruleLead);

            $('.js-title-client').text('<?=_l('cong_name_system')?>');
            //reset select
            var option = "<option></option>";
            $('#shipping').html(option).selectpicker('refresh');
            $('#advisory_lead_id').html(option).selectpicker('refresh');
            $('#client').html(option).selectpicker('refresh');
            $('#wap_lead').html(option).selectpicker('refresh');
            //end
        }
    }

    //Select Ajax LEAD
    function selectpicker_jax_lead(_class = "", _url = "", dataArray = {})
    {
        var _data = {};
        if (typeof(csrfData) !== 'undefined') {
            _data[csrfData['token_name']] = csrfData['hash'];
        }
        _data['q'] = "{{{q}}}";
        var options = {
            ajax: {
                url: _url ? _url : "<?=admin_url('orders/getLeadAjax')?>",
                type: "POST",
                dataType: "json",
                data: _data
            },
            locale: {
                emptyTitle: pls_option_select,
                statusInitialized: pls_option_select,
            },
            log: 3,
            preserveSelected: false,
            preprocessData: function(data) {
                var i,
                    l = data.length,
                    array = [];
                if (l) {
                    for (i = 0; i < l; i++) {
                        array.push(
                            $.extend(true, data[i], {
                                text: data[i].name_system,
                                value: data[i].id,
                                data: {
                                    subtext: data[i].company,
                                }
                            })
                        );
                    }
                }
                return array;
            }
        };
        $(".selectpicker.h_lead.with-ajax").selectpicker().filter(".with-ajax").ajaxSelectPicker(options);
    }

    $('body').on('change', 'select.h_lead', function(e){
        if($(this).val())
        {
            var userid = $(this).val();
            var data = {};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['lead_id'] = userid;
            $.post(admin_url+'orders/getShippingLead', data, function(data){
                data = JSON.parse(data);
                var option = "<option></option>";
                $.each(data, function(i, v){
                    option += '<option value="'+v.id+'" data-subtext="'+v.address+'">'+v.name+'</option>';
                })
                $('#shipping').html(option).selectpicker('refresh');
            })
        }
    })

    $('#currency').change(function(e){
        resetCurrency();
    })
    $('.c_guest_giving').change(function(e){
        ReALL();
    })

    function formatNumber(nStr, decSeperate=".", groupSeperate=",") {
        nStr += '';
        x = nStr.split(decSeperate);
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
        }
        return x1 + x2;
    }
    function unformatNumber(nStr, decSeperate=".", groupSeperate=",") {
        return nStr.replace(/\,/g,'');
    }

    function resetCurrency() {
        var total_cost_trans = Number(unformatNumber($('input[name="total_cost_trans"]').val()));
        var total_orders = Number(unformatNumber($('.total_orders').text()));
        var total_receipts = Number(unformatNumber($('.total_receipts').text()));
        var c_guest_giving = Number(unformatNumber($('.c_guest_giving').val()));
        var rest_collect = Number(unformatNumber($('.rest_collect').html()));

        var id_currency = $('#currency').val();
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id_currency'] = id_currency;
        data['total_cost_trans'] = total_cost_trans;
        data['total_orders'] = total_orders;
        data['total_receipts'] = total_receipts;
        data['c_guest_giving'] = c_guest_giving;
        data['rest_collect'] = rest_collect;
        $.post(admin_url+'orders/getCurrency', data, function(data){
            data = JSON.parse(data);
            $('.currency_title').text(data.name);

            $('.total_cost_trans_currency').text(data.total_cost_trans_currency);
            $('.total_orders_currency').text(data.total_orders_currency);
            $('.total_receipts_currency').text(data.total_receipts_currency);
            $('.c_guest_giving_currency').text(data.c_guest_giving_currency);
            $('.rest_collect_currency').text(data.rest_collect_currency);
        })
    }
    //end
</script>




<script type="text/javascript">
    ajaxSelectGroupOption_C('#object', "<?=admin_url('orders/SearchObjectItems')?>", '<?=$valueClient?>', '');

    $('body').on('change', '#object', function(e){
        var _object = $(this).val();
        var _data = {};
        if (typeof(csrfData) !== 'undefined') {
            _data[csrfData['token_name']] = csrfData['hash'];
        }
        _data['object'] = _object;
        $.post(admin_url+'orders/getAdvisory_lead_and_shipping', _data, function(data){
                data = JSON.parse(data);

                var option = "<option></option>";
                if(data.advisory)
                {
                    $.each(data.advisory, function(i, v){
                        option += '<option value="'+v.id+'">'+v.full_code+'</option>';
                    })
                }
                $('#advisory_lead_id').html(option).selectpicker('refresh');

                var option_shipping = "<option></option>";
                if(data.shipping)
                {
                    $.each(data.shipping, function(i, v){
                        option_shipping += '<option value="'+v.id+'" data-subtext="'+v.address+'">'+v.name+'</option>';
                    })
                }
                $('#shipping').html(option_shipping).selectpicker('refresh');
            })
    })
</script>
