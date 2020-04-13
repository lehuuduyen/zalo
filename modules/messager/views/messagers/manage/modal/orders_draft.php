<div id="modal_orders" class="modal fade" role="dialog">
    <style type="text/css">
        .c_img_item{
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }
        .width100{
            width:80px!important;
        }
        .width130{
            width:100px!important;
        }
        .minwidth150px{
            min-width: 230px;
        }
        div.c_product_items{
            width: 100%!important;
            margin-top: 10px;
        }
        tr div.c_customer {
            width: 100%!important;
        }
        .group_addon{
            padding: 0px 0px 0px 5px!important;
            border: none!important;
        }

        .radio.pull-right
        {
            height: 8px;
        }
        .c_price, .c_total, .total_cost_trans, .total_orders, .c_guest_giving, .c_number_items, .c_cost_trans{
            text-align: right!important;
        }
        .c_size{
            text-align: left!important;
        }
        .c_guest_giving, .c_total_cost_trans{
            padding-right: 0px!important;
        }
        .ribbon span{
            font-size: 8px;
        }
        #table-item-orders tbody tr td{
            vertical-align: middle;
        }
        .plef0{
            padding-left: 0px!important;
        }
        .pright0{
            padding-right: 0px!important;
        }
        .pright5{
            padding-right: 5px!important;
        }
        input[type="number"]{
            height: 42px;
        }
        .tab-pane{
            display: none;
        }
        .tab-pane.active{
            display: block;
        }
        table p {
            margin: 0 !important;
        }
        #table-item-orders tbody tr td:nth-child(2), #table-item-orders thead tr th:nth-child(2) {
            width: 200px !important;
            max-width: 200px!important;
            min-width: 200px!important;
        }

        #table-item-orders tbody tr td:nth-child(4), #table-item-orders thead tr th:nth-child(4) {
            width: 200px !important;
            max-width: 200px!important;
            min-width: 200px!important;
            white-space: initial;
        }

        .radio label.small::before {
            content: "";
            display: inline-block;
            position: absolute;
            width: 13px;
            height: 13px;
            left: 0;
            margin-left: -20px;
            border: 1px solid #bfcbd9;
            border-radius: 50%;
            background-color: #fff;
            -webkit-transition: border .15s ease-in-out;
            transition: border .15s ease-in-out;
        }
        .radio label.small::after {
            width: 9px;
            height: 9px;
            left: 2px;
            top: 2px;
        }
        label.small {
            font-size: 12px;
        }
        .font-20
        {
            font-size: 20px;
        }
        .mbot-2
        {
            margin-bottom: -2px;
        }
        #table-item-orders thead tr th:nth-child(2),
        #table-item-orders tbody tr td:nth-child(2),
        #table-item-orders thead tr th:nth-child(4),
        #table-item-orders tbody tr td:nth-child(4) {
            width: 150px;
            min-width: 150px;
        }
        .padding-0{
            padding: 0px;
        }
        #table-item-orders tbody tr td {
            padding:5px;
        }

        #form_create_orders .modal-body {
            padding-top: 0px;
        }
    </style>
    <div class="modal-dialog modal-xl" style="min-width: 90%">
        <?php echo form_open('messager/create_orders_draft', array('id' => 'form_create_orders')); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?=_l('cong_orders_draft')?></h4>
                </div>
                <div class="modal-body">
                    <div class="panel_s">
                        <div class="additional"></div>
                        <div class="panel-body">
                            <div class="ribbon info">
                                <?php $status = _l('cong_add_new'); ?>
                                <span> <?= $status ?> </span>
                            </div>
                            <div>
                                <ul class="nav nav-tabs mbot2" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#item_detail" aria-controls="item_detail" role="tab" data-toggle="tab"><?=_l('lead_general_info')?></a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#item_item" aria-controls="item_item" role="tab" data-toggle="tab"><?=_l('cong_detail_orders')?></a>
                                    </li>
                                </ul>
                                <div role="tabpanel" class="tab-pane active" id="item_detail">
                                    <div class="row">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><?=_l('lead_general_info')?></div>
                                            <div class="panel-body mbot5">
                                                <?php
                                                    echo form_hidden('id_object_draft', (isset($id) ? $id : ''));
                                                    echo form_hidden('draft', (isset($draft) ? $draft : ''));
                                                    echo form_hidden('type_object_draft', (isset($type) ? $type : ''));
                                                ?>
                                                <input type="hidden" id="client" value="<?=$id?>">
                                                <table class="table-tb table-bordered table-hover dont-responsive-table">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <label for="date" class="control-label"><?= _l('cong_create_orders'); ?></label>
                                                            </td>
                                                            <td>
                                                                <?php echo render_date_input('date', '', _d(date('Y-m-d'))); ?>
                                                            </td>
                                                            <td>
                                                                <label for="assigned" class="control-label"><?= _l('cong_manage_orders'); ?></label>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $staff = get_table_where('tblstaff', ['active' => 1]);
                                                                echo render_select('assigned', $staff, ['staffid', ['lastname', 'firstname']], '', get_staff_user_id());
                                                                ?>
                                                            </td>
                                                            <!-- ngày thực hiện-->
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label for="advisory_lead_id" class="control-label"><?= _l('promissory_advisory'); ?></label>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    echo render_select('advisory_lead_id', $advisory, ['id', ['full_code', 'type_code']], '', '', [], [], '', '', true, '-');
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <label for="date_want_to_receive" class="control-label">
                                                                    <?php echo _l('date_want_to_receive'); ?>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $value = (isset($orders) ? _dt($orders->shipping) : '');
                                                                echo render_datetime_input('date_want_to_receive', '', $value);
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p><?=_l('cong_unit_ship')?>: </p> <!-- Hình thức thanh toán -->
                                                            </td>
                                                            <td>
		                                                        <?php
                                                                    $unit_ship = get_table_where('tblcombobox_client', ['type' => 'ship']);
                                                                    $value = !empty($orders->unit_ship) ? $orders->unit_ship : '';
                                                                    echo render_select('unit_ship', $unit_ship, ['id', 'name'], '', $value);
		                                                        ?>
                                                            </td>
                                                            <td>
                                                                <p><?=_l('cong_code_ship')?>: </p> <!-- mã giao hàng -->
                                                            </td>
                                                            <td>
		                                                        <?php $value = (isset($orders) ? $orders->code_ship : ""); ?>
		                                                        <?php echo render_input('code_ship', '', $value); ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label for="shipping" class="control-label"><?= _l('cong_shipping'); ?></label>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                echo render_select_with_input_group('shipping', $shipping, ['id', 'name', 'address'], '', '', '<a href="#" onclick="ViewShippingClient(\'shipping\')"><i class="fa fa-plus"></i></a>', ['data-actions-box' => true], [], '', '', false);
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <p><?=_l('cong_type_pay')?>: </p> <!-- Hình thức thanh toán -->
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $mode_payment = get_table_where('tblpayment_modes', ['active' => 1]);
                                                                echo render_select('mode_payment', $mode_payment, ['id', 'name'], '', $value);
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label for="note" class="control-label"><?= _l('cong_note_orders'); ?></label></td>
                                                            <td colspan="3">
		                                                        <?php echo render_textarea('note', '', ''); ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 pull-right">
                                            <?php
                                                $dataCurrency = get_table_where('tblcurrencies');
                                                $dataAdvisory = get_table_where('tbladvisory_apply');
                                            ?>
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
                                                            <?php if($key == 0) { ?>
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
                                                            <?php if($key == 0) { ?>
                                                                <option value="<?=$value['id']?>" selected><?=$value['name']?></option>
                                                            <?php } else { ?>
                                                                <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clearfix_C"></div>
                                        <div class="col-md-6">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading"><?=_l('cong_info_pay')?></div>
                                                <div class="panel-body">
                                                    <table class="table table-bordered width_100 dont-responsive-table mtop0 mbot0 wap-table-border">
                                                        <thead>
                                                        <tr>
                                                            <th><b><?=_l('cong_info_items')?></b></th>
                                                            <th class="text-center"><b><?=_l('VND')?></b></th>
                                                            <th><b><?=_l('cong_currencies')?></b></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <p><?=_l('cong_num_item_order')?>:</p> <!-- Số mặt hàng trong giõ hàng-->
                                                            </td>
                                                            <td>
                                                                <p class="c_number_items">0</p> <!-- Số mặt hàng trong giõ hàng-->
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p><?=_l('cong_cost_trans')?>:</p> <!-- Tổng chi phí vận chuyễn -->
                                                            </td>
                                                            <td>
                                                                <input name="total_cost_trans" class="form-control c_total_cost_trans text-right" onkeyup="C_formatNumBerKeyUp(this)" value="<?=!empty($orders->total_cost_trans) ? number_format($orders->total_cost_trans) : '0' ?>"/>
                                                                <!-- Tổng chi phí vận chuyễn -->
                                                            </td>
                                                            <td>
                                                                <p></p> <!-- Tổng chi phí vận chuyễn -->
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p><?= _l('cong_total_orders')?>:</p> <!-- Tổng giá trị đơn hàng -->
                                                            </td>
                                                            <td>
                                                                <p class="total_orders"> 0 </p> <!-- Tổng giá trị đơn hàng -->
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p><?= _l('cong_guest_giving')?>:  <!-- Khách tặng thêm -->
                                                            </td>
                                                            <td>
                                                                <input name="guest_giving" class="H_input c_guest_giving" onkeyup="C_formatNumBerKeyUp(this)" value="0">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p><?=_l('cong_type_pay')?>: </p> <!-- Hình thức thanh toán -->
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p> <?=_l('cong_total_order_debt')?>: </p> <!-- Khoản thu còn lại của khách hàng -->
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading"><?=_l('payment_world')?></div>
                                            <div class="panel-body">
                                                <table class="table table-bordered width_100 dont-responsive-table mtop0 mbot0 wap-table-border">
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
                                                            <p class="total_cost_trans_currency text-right"> <?=!empty($orders->total_cost_trans) ? number_format($orders->total_cost_trans) : '0' ?></p> <!-- Tổng chi phí vận chuyễn -->
                                                        </td>
                                                        <td>
                                                            <p></p> <!-- Tổng chi phí vận chuyễn -->
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <p><?= _l('cong_total_orders')?>: </p> <!-- Tổng giá trị đơn hàng -->
                                                        </td>
                                                        <td>
                                                            <p class="total_orders_currency text-right"> <?=!empty($orders->grand_total) ? number_format($orders->grand_total) : '0' ?> </p> <!-- Tổng giá trị đơn hàng -->
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
                                                            <p><?=_l('cong_type_pay')?>: </p> <!-- Hình thức thanh toán -->
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <p> <?=_l('cong_total_order_debt')?>: </p> <!-- Khoản thu còn lại của khách hàng -->
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="item_item">
                                    <div class="row">
                                        <div class="panel panel-info mbot0">
                                            <div class="panel-heading"><?=_l('cong_info_items')?></div>
                                            <div class="">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered dont-responsive-table mtop0" id="table-item-orders">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <a onclick="AddItemOrder()" class="btn btn-info btn-icon">+</a>
                                                                </th>
                                                                <th>
                                                                    <?=_l('cong_item_code')?>
                                                                </th> <!--mã hàng-->
                                                                <th><?=_l('cong_item_image')?></th> <!--Hình ảnh-->
                                                                <th><?=_l('cong_item_name')?></th> <!--Tên hàng-->
                                                                <th><?=_l('cong_quantity')?></th> <!--Số lượng -->
                                                                <th><?=_l('cong_price_thinh_html')?></th> <!--Giá thỉnh-->
                                                                <th><?=_l('cong_discount')?></th> <!--Chiết khấu-->
                                                                <th><?=_l('cong_info_money')?></th> <!--Thành tiền-->
                                                                <th><?=_l('cong_buy_gif')?></th> <!--mua cho-->
                                                                <th><?=_l('cong_size')?></th>
                                                                <th class="minwidth150px"><?=_l('cong_shipment_date')?></th>
                                                                <th><?=_l('options')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php include_once(APPPATH.'views/admin/orders/TrUpdate.php'); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">
                        <?php echo _l( 'submit'); ?>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        <?php echo form_close() ?>
    </div>
</div>

<script>

    function ValiFrom(form, valfrom)
    {
        appValidateForm(form, valfrom, manageSubmitFB);
    }

    $('#modal_orders').modal('show');
    init_selectpicker();

    init_datepicker();

    selectpicker_jax_customer();

    var ValdateFrom = {
        date: 'required',
        assigned: 'required',
        shipping: 'required'
    };

    if(vValdateFrom)
    {
        $.each(vValdateFrom, function(iV, vV){
            ValdateFrom[iV] = vV;
        })
    }

    $(function() {
        ValiFrom($('#form_create_orders'), ValdateFrom);
    })

    var pls_option_select = "<?=_l('cong_pls_selected_option')?>";

    var lang_money = "<?=_l('cong_money')?>";

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
        var td4 = $('<td><input class="form-control c_quantity width100" min="1" onkeyup="C_formatNumBerKeyUp(this)" name="items['+UnitArray+'][quantity]" id="items['+UnitArray+'][quantity]" value="1"></td>');
        var td5 = $('<td><input class="form-control c_price width130" onkeyup="C_formatNumBerKeyUp(this)" name="items['+UnitArray+'][price]" id="items['+UnitArray+'][price]"></td>');
        var td6 = $('<td></td>');
        var div_input_group_td6 = $('<div class="input-group"><input class="form-control c_discount width100 " name="items['+UnitArray+'][discount]"></div>');

        var div_radiot6 = $('<div class="input-group-addon group_addon"></div>');
        div_radiot6.append($('<div class="radio pull-left"><input type="radio" class="c_type_discount" name="items['+UnitArray+'][type_discount]" checked value="1"><label class="small">%</label></div>'));
        div_radiot6.append($('<div class="radio pull-right"><input type="radio" class="c_type_discount" name="items['+UnitArray+'][type_discount]" value="2"><label class="small">'+lang_money+'</label></div>'));
        div_input_group_td6.append(div_radiot6);
        td6.append(div_input_group_td6);

        var td8 = $('<td><p class="c_total mtop10"></p></td>');
        var td9 = $('<td></td>');

        var SelectClient = $('<input class="c_customer" name="items['+UnitArray+'][id_customer]" style="width: 250px;min-width: 150px; max-width: 250px">');
        td9.append(SelectClient);

        var td10 = $('<td><input class="form-control c_size width100" name="items['+UnitArray+'][size]"></td>');
        var td11 = $('<td></td>');
        var Row_shipping = $('<div class="row-shipping" InitRow="0"></div>');
        var ShippingTd11 = $('<div class="col-md-12"></div>');
        ShippingTd11.append('<a class="pointer" onclick="AddRowShipping(\'items\', '+UnitArray+', this)">+ <?=_l('cong_add_shipping_row')?></a>');
        td11.append(Row_shipping);
        td11.append(ShippingTd11);

        var td12 = $('<td><a class="btn btn-icon btn-danger DeleteItems"><i class="fa fa-times" aria-hidden="true"></i></a></td>');
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

        $('#table-item-orders tbody').append(_Tr);
        ajaxSelectCallBack('input[name="items['+UnitArray+'][id_product]"]', "<?=admin_url('orders/SearchProductItems')?>", '', '', false);
        ajaxSelectCallBack('input[name="items['+UnitArray+'][id_customer]"]', admin_url+'orders/SearchCustomerSelect2/', '', '');
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
                                text: data[i].company,
                                value: data[i].userid,
                                data: {
                                    subtext: data[i].full_code,
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
        var total_cost_trans = 0; // tổng chi phú vận chuyển
        $.each(Tr, function(Ki,Vv){
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
        $('p.total_orders').html(C_formatNumber(total));
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
        var id = $('#form_create_orders').find('input[name="id_object_draft"]').val();
        var type = $('#form_create_orders').find('input[name="type_object_draft"]').val();
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;
        data['type'] = type;
        data['idSelect'] = idSelect;
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

    function AddRowShipping(items = 'items',Rowint = 0, _this)
    {
        var Td = $(_this).parents('td');
        var RowShipping = Td.find('div.row-shipping');
        var initRow = parseInt(RowShipping.attr('InitRow')); // lấy dòng thêm bao nhiu
        var DivRowIndex = $('<div class="row-0 row-index"></div>');
        //div Delete
        var DivDelete = $('<div class="col-md-1 plef0 pright0"></div>');
        DivDelete.append('<p class="mtop15"><a class="text-danger DeleteInitRow">x</a></p>');

        //Div date
        var DivDate = $('<div class="col-md-7 plef0 pright5"></div>');
        DivDate.append('<input class="form-control c_date_shipping  H_input datepicker" name="'+items+'['+Rowint+'][shipping]['+initRow+'][date_shipping]" id="'+items+'['+Rowint+'][shipping]['+initRow+'][date_shipping]"  value="">');

        //Div Số lượng
        var DivQuantity = $('<div class="col-md-4 plef0 pright0"></div>');
        DivQuantity.append('<input class="form-control c_quantity_shipping  H_input" name="'+items+'['+Rowint+'][shipping]['+initRow+'][quantity_shipping]" id="'+items+'['+Rowint+'][shipping]['+initRow+'][quantity_shipping]"  value="">');

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
        ValiFrom($('#form_create_orders'), ValdateFrom);
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
            ValiFrom($('#form_create_orders'), ValdateFrom);
        })
    })

    //lấy từ hoàng CRM
    $('#currency').change(function(e){
        resetCurrency();
    })

    $('.c_guest_giving').change(function(e){
        resetCurrency();
    })

    function resetCurrency() {
        var total_cost_trans = Number(unformatNumber($('input[name="total_cost_trans"]').val()));
        var total_orders = Number(unformatNumber($('.total_orders').text()));
        var total_receipts = Number(unformatNumber($('.total_receipts').text()));
        var c_guest_giving = Number(unformatNumber($('.c_guest_giving').val()));

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
        $.post(admin_url+'orders/getCurrency', data, function(data){
            data = JSON.parse(data);
            $('.currency_title').text(data.name);

            $('.total_cost_trans_currency').text(data.total_cost_trans_currency);
            $('.total_orders_currency').text(data.total_orders_currency);
            $('.total_receipts_currency').text(data.total_receipts_currency);
            $('.c_guest_giving_currency').text(data.c_guest_giving_currency);
        })
    }

    function unformatNumber(nStr, decSeperate=".", groupSeperate=",") {
        return nStr.replace(/\,/g,'');
    }

</script>
