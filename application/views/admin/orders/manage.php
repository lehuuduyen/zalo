<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .wap-button {
        background: #c4c4c461;
    }
    .wap-button:hover {
        background: #449ed7;
    }
    .wap-button:hover i {
       color: #fff;
    }
    .wap-button.active {
        background: #449ed7;
    }
    .wap-button.active i {
        color: #fff;
    }
    .wap-tab {
        display: none;
    }
    .wap-tab.active {
        display: block;
    }
    .progressbar:not(.initli) {
        margin: 0;
        padding: 0;
        counter-reset: step;
    }
    .progressbar li span{
        font-size: 11px;
    }
    .progressbar li:not(.initli) {
        list-style-type: none;
        width: 100px;
        float: left;
        font-size: 9px;
        position: relative;
        text-align: center;
        /*text-transform: uppercase;*/
        color: #7d7d7d;
        z-index: 0;
    }
    .progressbar li:not(.initli):before {
        width: 10px;
        height: 10px;
        content: ' ';
        counter-increment: step;
        line-height: 51px;
        border: 5px solid #7d7d7d;
        display: block;
        text-align: center;
        margin: 0 auto 0px auto;
        border-radius: 50%;
        background-color: white;
    }
    .progressbar li:not(.initli):after {
        width: 100%!important;
        height: 2px!important;
        content: ''!important;
        position: absolute!important;
        background-color: #7d7d7d!important;
        top: 4px!important;
        left: -50%!important;
        z-index: -1!important;
    }
    .progressbar li:first-child:after {
        content: none;
        display: none;
    }
    .progressbar li.active:not(.initli) {
        color: green;
    }
    .progressbar li.active:not(.initli):before {
        border-color: #55b776;
    }
    .progressbar li.cancel:before {
        border-color: red;
    }
    .progressbar li.active + li:after {
        background-color: #55b776!important;
    }
    .font11
    {
        font-size: 11px;
    }
    .btn-info.active, .btn-info:active{
        background-color: #094865;
    }
    .table-orders th, .table-orders td { white-space: nowrap; }
    .table-orders_draft th, .table-orders_draft td { white-space: nowrap; }
    .mw600{
        min-width: 600px;
    }
    .li_pad0{
        white-space: normal;
        padding-bottom: 0px!important;
        margin-bottom: 0px!important;
    }
    .li_pad10{
        white-space: normal;
        padding-left: 10px;
    }
    .CRa{
        color: #55b776;
    }
    .progressbar_img{
        text-align: center!important;
        display: flex;
        flex-direction: row;
        justify-content: center;
        margin-bottom: 0px;
    }
    ul.progressbar_img li {
        width: 100px;
        float: left;
    }
    .CRwa{
        color: red;
    }
    .initli{
        width: 100px;
    }
    tr.bg-warning {
        background-color: #fcf8e3;
    }
    tr.bg-dd {
        background-color: #dddddd;
    }
    .initli .status_orders
    {
        color:red;
    }
    .table-orders tr td:nth-child(6), .table-orders tr td:nth-child(10), .table-orders tr td:nth-child(11), .table-orders tr td:nth-child(12), .table-orders tr td:nth-child(14), .table-orders tr td:nth-child(15), .table-orders tr td:nth-child(16), .table-orders tr td:nth-child(17), .table-orders tr td:nth-child(20), .table-orders tr td:nth-child(23), .table-orders tr td:nth-child(24), .table-orders tr td:nth-child(25), .table-orders tr td:nth-child(27), .table-orders tr td:nth-child(28), .table-orders tr td:nth-child(29), .table-orders tr td:nth-child(30), .table-orders tr td:nth-child(31), .table-orders tr td:nth-child(32), .table-orders tr td:nth-child(33){
        min-width: 200px;
        white-space: unset;
    }
    .table-orders_draft tr td:nth-child(6), .table-orders_draft tr td:nth-child(10), .table-orders_draft tr td:nth-child(11), .table-orders_draft tr td:nth-child(12), .table-orders_draft tr td:nth-child(14), .table-orders_draft tr td:nth-child(15), .table-orders_draft tr td:nth-child(16), .table-orders_draft tr td:nth-child(17), .table-orders_draft tr td:nth-child(20), .table-orders_draft tr td:nth-child(23), .table-orders_draft tr td:nth-child(24), .table-orders_draft tr td:nth-child(25), .table-orders_draft tr td:nth-child(27), .table-orders_draft tr td:nth-child(28), .table-orders_draft tr td:nth-child(29), .table-orders_draft tr td:nth-child(30), .table-orders_draft tr td:nth-child(31), .table-orders_draft tr td:nth-child(32), .table-orders_draft tr td:nth-child(33){
        min-width: 200px;
        white-space: unset;
    }
    .close {
        margin: -10px -10px 0px 10px;
    }
    .table_popover_head div {
        width: 25%;
    }
    .table_popover_body span {
        width: 25%;
    }
    .wap-head {
        background: #3c97d71a;
    }
    .wap-body {
        cursor: pointer;
    }
    .wap-body:hover {
        background: #00000012;
    }
    .table-orders_detail tr td:nth-child(14), .table-orders_detail tr td:nth-child(15), .table-orders_detail tr td:nth-child(16), .table-orders_detail tr td:nth-child(18), .table-orders_detail tr td:nth-child(19), .table-orders_detail tr td:nth-child(35), .table-orders_detail tr td:nth-child(36), .table-orders_detail tr td:nth-child(37), .table-orders_detail tr td:nth-child(38), .table-orders_detail tr td:nth-child(39), .table-orders_detail tr td:nth-child(43){
        min-width: 200px;
        white-space: unset;
    }

    .table-orders_detail tr td:nth-child(7){
        width: 200px !important;
        white-space: unset;
    }
    .table-orders_detail th td:nth-child(7){
        width: 200px !important;
        white-space: unset;
    }
    .css-no-drop {
        cursor: no-drop;
    }
    .css-no-background {
        background: #c6c6c6;
    }
    .css-no-background a{
        color: red;
    }
    .css-no-event {
        pointer-events: none;
    }
    .bold600 {
        font-weight: 600;
    }

    .table-orders_detail thead tr th,.table-orders thead tr th, .table-orders_detail tbody tr td,.table-orders tbody tr td{
        white-space: nowrap;
    }
    .content-menu-care_of{
        max-height: 450px;
        overflow: auto;
    }

    .table-orders_detail tbody tr td:nth-child(7),.table-orders_detail thead th:nth-child(7) {
        width: 30px!important;
        white-space: initial;
    }
    .table-orders_detail tbody tr td:nth-child(1),.table-orders_detail tbody tr td:nth-child(2),.table-orders_detail tbody tr td:nth-child(3),.table-orders_detail tbody tr td:nth-child(4),.table-orders_detail tbody tr td:nth-child(5),.table-orders_detail tbody tr td:nth-child(6) {
        white-space: initial;
    }

    .table-orders_detail tr td {
        padding: 3px 5px 3px 5px!important;
    }

</style>
<div id="wrapper">
   <div class="panel_s mbot10 H_scroll" id="H_scroll">
      <div class="panel-body _buttons">
         <div class="_buttons">
            <span class="bold uppercase fsize18 H_title"><?=!empty($title) ? $title : ''?></span>

            <?php if (has_permission('orders','','create')) { ?>
                <a href="<?=admin_url('orders/detail')?>"  class="btn btn-info mright5 test pull-right H_action_button">
                   <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                   <?php echo _l('create_add_new'); ?>
                </a>
            <?php } ?>
            <div class="clearfix"></div>
         </div>
      </div>
   </div>
    <div class="content">
        <div class="row">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab_info"><?=_l('list_total')?></a></li>
                <li><a class="js-btn-tab tab-click" data-toggle="tab" href="#tab_detail"><?=_l('list_item')?></a></li>
                <li><a class="tab-draft" data-toggle="tab" href="#tab_draft"><?=_l('cong_orders_draft')?></a></li>
            </ul>


            <!-- tab tổng -->
            <div class="wap-tab active" id="tab_info">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="horizontal-scrollable-tabs">
                            <div class="scroller scroller-left arrow-left disabled" style="display: block;"><i class="fa fa-angle-left"></i></div>
                            <div class="scroller scroller-right arrow-right" style="display: block;"><i class="fa fa-angle-right"></i></div>
                            <div class="horizontal-tabs">
                                <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                    <li class="active">
                                        <a class="H_filter" data-id="">
                                            <?=_l('cong_all')?> <b class="filter_"></b>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="0">
                                            <?=_l('cong_orders_warning')?> <b class="filter_0"></b>
                                        </a>
                                    </li>
                                    <?php if(!empty($procedure_detail)){?>
                                        <?php foreach($procedure_detail as $key => $value){?>
                                            <li>
                                                <a class="H_filter" data-id="<?=$value['id']?>">
                                                    <?= $value['name'] ?> <b class="filter_<?=$value['id']?>"></b>
                                                </a>
                                            </li>
                                        <?php }?>
                                    <?php }?>
                                    <li>
                                        <a class="H_filter" data-id="-1">
                                            <?=_l('cong_orders_success')?> <b class="filter_-1"></b>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="-2">
                                            <?=_l('cong_orders_delay')?> <b class="filter_-2"></b>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="-3">
                                            <?=_l('cong_orders_cancel')?> <b class="filter_-3"></b>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" id="filterStatus" name="filterStatus" value=""/>
                        <div class="clearfix mtop20"></div>
                        <?php $table_data = array(
                            _l('#'), //stt
                            _l('cong_code'), //code
                            _l('cong_name_system'), //tên hệ thống
                            _l('Zcode'), // zcode
                            _l('cong_date'), //ngaft c
                            _l('cong_manage_orders'),
                            _l('cong_step_order'),
                            _l('cong_date_create_orders'),
                            _l('cong_create_by_orders'),
                            _l('total_price_order').' (VND)',
                            _l('total_price_receipts').' (VND)',
                            _l('cong_total_order_debt').' (VND)',
                            _l('cong_num_item'),
                            _l('cong_cost_trans').' (VND)',
                            _l('cong_guest_giving'),
                            _l('cong_payment_mode'),
                            _l('cong_name_shipping_client'),
                            _l('cong_phone'),
                            _l('cong_address'),
                            _l('detail_receipts'),
                            _l('promissory_advisory'),
                            _l('cong_code_system'),
                            _l('cong_code_lead'),
                            _l('cong__code_client'),
                            _l('code_client_now'),
                            _l('invoice_add_edit_currency'),
                            _l('advisory_apply'),
                            _l('rate_exchange'),
                            _l('cong_cost_trans').' ('._l('currency_lang').')',
                            _l('guest_giving').' ('._l('currency_lang').')',
                            _l('total_price_order').' ('._l('currency_lang').')',
                            _l('total_price_receipts').' ('._l('currency_lang').')',
                            _l('cong_total_order_debt').' ('._l('currency_lang').')',
                            _l('ch_option'),
                        );
                        render_datatable($table_data, 'orders table-bordered');
                        ?>
                    </div>
                </div>
            </div>
            <!-- end -->
            <!-- tab chi tiêt -->
            <div class="wap-tab" id="tab_detail">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix mtop20"></div>
                        <?php $table_data_detail = array(
                            _l('#'), //stt
                            _l('status_item_order'), // trạng thái mặt hàng
                            _l('ticket_settings_priority'), //mức độ ưu tiên
	                        _l('cong_item_code'), //Mã sản phẩm
	                        _l('cong_name_system'), //tên người nhận hàng

	                        _l('cong_item_name'), //tên sản phẩm
	                        _l('cong_quantity'), //Số lượng

                            _l('cong_name_shipping_client'), //tên khách hệ thống
                            _l('Zcode'), //zcode
                            _l('tnh_images'), //Hình ảnh
	                        _l('cong_code_orders'), // mã đơn hàng
                            _l('price_thinh'), //giá thỉnh trong nước
                            _l('cong_discount'), // Chiết khấu
                            _l('cong_info_money'), //Thành tiền


                            _l('total_price_order').' (VND)', //tổng giá trị đơn hàng
                            _l('total_price_receipts').' (VND)', //tổng giá trị phiếu thu
                            _l('cong_total_order_debt_short').' (VND)', //Khoản Còn Lại Phải Thu Của KH
                            _l('cong_num_item_order'), //Số Mặt Hàng Trong Giỏ Hàng

                            _l('cong_cost_trans').' (VND)', //Chi phí vận chuyển

                            _l('cong_guest_giving').' (VND)', //Khách hàng tặng thêm
//                            _l('Hình thức thanh toán'), //Hình thức thanh toán


                            _l('detail_receipts'), //Chi tiết phiếu thu

                            _l('cong_buy_gif'), //Mua cho
                            _l('item_size'), //kích thước
                            _l('cong_note'), //Ghi chú

                            _l('cong_phone_shipping'), //số điện thoại người nhận
                            _l('cong_shipping'), //Địa chỉ giao hàng

                            _l('cong_country'), //Quốc gia
                            _l('cong_client_city'), //Tỉnh/Thành phố
                            _l('cong_district'), //Quận huyện
                            _l('cong_ward'), //Phường xã

                            _l('promissory_advisory'), // phiếu tư vấn khách hàng
                            _l('cong_code_system'), // mã khách hàng hệ thống
                            _l('cong_code_lead'), //mã khách hàng tiềm năng
                            _l('cong__code_client'), //mã khách hàng
                            _l('code_client_now'), // mã khách hàng hiện tại
                            _l('invoice_add_edit_currency'), //đơn vị tiền
                            _l('advisory_apply').' (VND)',//giá thỉnh áp dụng
                            _l('cong_exchange_rate'), // tỉ giá ngoại tệ
                            _l('cong_cost_trans').' ('._l('currency_lang').')', // chi phí vận chuyển ngoại tệ
                            _l('guest_giving_word'), // Khách Tặng Thêm (Ngoại Tệ)
                            _l('cong_total_orders_word'), // Tổng giá trị đơn hàng(Ngoại tệ)
                            _l('total_price_receipts_word'), // Tổng Giá Trị Phiếu Thu (Ngoại Tệ)
                            _l('cong_total_order_debt_short_word'), // Khoản Còn Lại Phải Thu Của KH (Ngoại Tệ)
                            _l('date_want_to_receive'), // Ngày Khách Mong Muốn Được Nhận Được Đơn Hàng
                            _l('cong_shipment_date'), //ngày giao hàng dự kiến
                            _l('cong_date_create_orders'), // ngày tạo
                            _l('cong_create_by_orders'), // được tạo bởi
                            _l('cong_workflows'), // Quy trình
                            //bổ xung trạng thái
                            //end
	                        _l('cong_unit_ship'),
	                        _l('cong_code_ship'),
                            _l('cong_code_care_of_client'), //mã phiếu chăm sóc khách hàng
                        );
                        render_datatable($table_data_detail, 'orders_detail table-bordered dont-responsive-table');
                        ?>
                    </div>
                </div>
            </div>
            <!-- end -->
            <!-- tab đơn hàng nháp -->
            <div class="wap-tab" id="tab_draft">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix mtop20"></div>
				        <?php $table_data = array(
					        _l('#'), //stt
					        _l('cong_code'), //code
					        _l('cong_name_system'), //tên hệ thống
					        _l('Zcode'), // zcode
					        _l('cong_date'), //ngaft c
					        _l('cong_manage_orders'),
					        _l('cong_step_order'),
					        _l('cong_date_create_orders'),
					        _l('cong_create_by_orders'),
					        _l('total_price_order').' (VND)',
					        _l('total_price_receipts').' (VND)',
					        _l('cong_total_order_debt').' (VND)',
					        _l('cong_num_item'),
					        _l('cong_cost_trans').' (VND)',
					        _l('cong_guest_giving'),
					        _l('cong_payment_mode'),
					        _l('cong_name_shipping_client'),
					        _l('cong_phone'),
					        _l('cong_address'),
					        _l('detail_receipts'),
					        _l('promissory_advisory'),
					        _l('cong_code_system'),
					        _l('cong_code_lead'),
					        _l('cong__code_client'),
					        _l('code_client_now'),
					        _l('invoice_add_edit_currency'),
					        _l('advisory_apply'),
					        _l('rate_exchange'),
					        _l('cong_cost_trans').' ('._l('currency_lang').')',
					        _l('guest_giving').' ('._l('currency_lang').')',
					        _l('total_price_order').' ('._l('currency_lang').')',
					        _l('total_price_receipts').' ('._l('currency_lang').')',
					        _l('cong_total_order_debt').' ('._l('currency_lang').')',
					        _l('ch_option'),
				        );
				        render_datatable($table_data, 'orders_draft table-bordered');
				        ?>
                    </div>
                </div>
            </div>
            <!-- end đơn hàng nháp -->
        </div>
    </div>
</div>
<?php init_tail(); ?>
<div id="payment_order_data"></div>
<div id="payment_order_data_view"></div>
<link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
<script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script>
var tAPI;
var tAPI_detail;
$(function(){
    var CustomersServerParams = {
      'filterStatus' : '[name="filterStatus"]',
    };
     tAPI = initDataTableCustom('.table-orders', admin_url+'orders/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1,'desc'))); ?>, fixedColumns = {leftColumns: 4, rightColumns: 0});
     tAPI_draft = initDataTableCustom('.table-orders_draft', admin_url+'orders/table?draft=1', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1,'desc'))); ?>, fixedColumns = {leftColumns: 4, rightColumns: 0}); // đơn hàng nháp
    // var tAPI = initDataTable('.table-orders', admin_url+'orders/table', [], [], CustomersServerParams, ['1', 'desc']);
    // var tAPI_detail = initDataTable('.table-orders_detail', admin_url+'orders/table_detail', [], [], [], []);
    var tAPI_detail = initDataTableCustom('.table-orders_detail', admin_url+'orders/table_detail', [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18], [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18], [], ['0', 'desc'], fixedColumns = {leftColumns: 7, rightColumns: 0});
    $.each(CustomersServerParams, function(filterIndex, filterItem){
          $(filterItem).on('change', function(){
                tAPI.draw('page');
          });
    });

    $('.tab-click').click(function(event) {
        tAPI_detail.draw('page');
    });
    $('.tab-draft').click(function(event) {
        tAPI_draft.draw('page');
    });
});

$('.table-orders').on('draw.dt',function(){
    var expenseReportsTable = $(this).DataTable();
    var total = expenseReportsTable.ajax.json().total;
    var sumtotal = 0;
    $.each(total, function(i, v){
        $('.filter_'+i).html('('+formatNumber(v)+')');
        if(v > 0)
        {
            sumtotal++;
        }
    })
    $('.filter_').html('('+formatNumber(sumtotal)+')');
})
$('body').on('click', '.H_filter', function(e){
    $('.H_filter').parent('li').removeClass('active');
    $(this).parent('li').addClass('active');
    $('input[name="filterStatus"]').val($(this).attr('data-id'));
    tAPI.draw('page');
})
//hau
$('body').on('click', '.AStatusAdvisory',function(e){
    var PStatus = $(this);
    var id = $(this).attr('data-id');
    var id_detail = $(this).attr('id-detail');
    var status = $(this).attr('status-table');
    var data = {};
    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    data['id_detail'] = id_detail;
    data['status'] = status;
    data['id'] = id;
    $.post(admin_url+'orders/update_status', data, function(data){
        data = JSON.parse(data);
        tAPI_detail.ajax.reload();
        alert_float(data.alert_type, data.message);
    })
})
//end

//click chuyễn trạng thái .status_orders => bỏ đi vì k cho chuyển ở đơn chính, thay chuyển trạng thái ở bảng chi tiết, thêm số 1 để hủy bỏ thao tác
$('body').on('click', '.status_orders',function(e){
    var PStatus = $(this);
    var id = $(this).attr('id-data');
    var status = $(this).attr('status-procedure');
    if(status < 0)
    {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;
        data['status'] = status;
        $.post(admin_url+'orders/update_status', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                tAPI.draw('page');
            }
            alert_float(data.alert_type, data.message);
        })
    }
})

function restore_step(id = "")
{
    if($.isNumeric(id))
    {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;
        $.post(admin_url+'orders/restore_step', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                tAPI.draw('page');
            }
            alert_float(data.alert_type, data.message);
        })
    }
}

function restore_orders(id = "", status = "")
{
    if($.isNumeric(id) && $.isNumeric(status))
    {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        data['id'] = id;
        data['status'] = status;
        $.post(admin_url+'orders/restore_orders', data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                tAPI.draw('page');
            }
            alert_float(data.alert_type, data.message);
        })
    }
}

function DeleteOrders(id = "")
{
    if(confirm('<?=_l('cong_you_must_delete')?>'))
    {
        if($.isNumeric(id))
        {
            var data = {};
            if (typeof(csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }
            data['id'] = id;
            $.post(admin_url+'orders/delete_orders', data, function(data){
                data = JSON.parse(data);
                if(data.success)
                {
                    tAPI.draw('page');
                }
                alert_float(data.alert_type, data.message);
            })
        }
    }
}

function initOrders(id = "")
{
    if($.isNumeric(id))
    {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
            data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'orders/loadViewOrder/'+id, data, function(data){
            data = JSON.parse(data);
            if(data.success)
            {
                $('.div_modal_orders').html(data.data)
            }
            else
            {
                alert_float(data.alert_type, data.message);
            }
        })
    }
}

var inner_popover_template = '<div class="popover" style="width:600px;max-width: 600px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>'; 
$(document).on('click','.menu-receipts',function(e){
    $(this).popover({
        html: true,
        placement: "left",
        trigger: 'click',
        title:'<?php echo _l('detail_receipts'); ?>'+'<button class="close"><span aria-hidden="true">&times;</span></button>',
        content: function() {
            return $(this).find('.content-menu').html();
        },
        template: inner_popover_template
    });
});
var inner_popover_template_status = '<div class="popover" style="width:200px;max-width: 300px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>'; 
$(document).on('click','.menu-receipts-status',function(e){
    $(this).popover({
        html: true,
        placement: "right",
        trigger: 'click',
        title:'<?php echo _l('status_item_order'); ?>'+'<button class="close"><span aria-hidden="true">&times;</span></button>',
        content: function() {
            return $(this).find('.content-menu-status').html();
        },
        template: inner_popover_template_status
    });
});
var inner_popover_template_care_of_client = '<div class="popover" style="width:500px;max-width: 700px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>'; 
$(document).on('click','.js-care_of_client',function(e){
    var popover = $(this);
    var id_data = $(this).attr('id-data');
    var id_detail = $(this).attr('id-detail');
    var theme_of = $(this).attr('theme-of');
    var data = {};
    if (typeof(csrfData) !== 'undefined') {
        data[csrfData['token_name']] = csrfData['hash'];
    }
    data['id'] = id_data;
    data['theme_of'] = theme_of;
    data['id_detail'] = id_detail;
    $.post(admin_url+'care_of_clients/get_experience', data, function(result){
        var data_result = result;
        $(popover).popover({
            html: true,
            placement: "left",
            trigger: 'click',
            title: '<?php echo _l('cong_fullcode_care_of'); ?>: ' + $(popover).attr('title_html') +'<button class="close close_popover"><span aria-hidden="true">&times;</span></button>',
            template: inner_popover_template_care_of_client
        });
        $(popover).attr('data-content', data_result);
        $(popover).popover('show');
    })
});

$(document).on('click','.popover-title > .close',function(e){

    var id = $(this).parents('.popover').attr('id');
    $('[aria-describedby="'+id+'"]').popover('hide');
    console.log(id)
    // $('.menu-receipts').popover('hide');
    // $('.menu-receipts-status').popover('hide');
    // $('.js-care_of_client').popover('hide');
});
$('.js-btn-tab').click(function(e){
    var current = $(e.currentTarget);
    var id_element = current.attr('data-tab');
    $('.wap-tab').removeClass('active');
    $('.wap-button').removeClass('active');

    $(id_element).addClass('active');
    $(this).addClass('active');
    // responsive_table();
});

//hau
function payment(id) {
        $('#payment_order_data').html('');
        $.get(admin_url + 'orders/payment_order/'+id).done(function(response) {
        $('#payment_order_data').html(response);
        $('#payment_order').modal({show:true,backdrop:'static'});
        init_selectpicker();
        init_datepicker();
        }).fail(function(error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
        });
    }
$('body').on('hidden.bs.modal', '#payment_order', function() {
    $('#payment_order_data').html('');
});

$('body').on('click', '.view_payment',function(e){
    var id = $(this).attr('data-id');
    $('#payment_order_data').html('');
            $.get(admin_url + 'orders/payment_order_view/'+id).done(function(response) {
            $('#payment_order_data_view').html(response);
            $('#payment_order_view').modal({show:true,backdrop:'static'});
            init_selectpicker();
            init_datepicker();
            }).fail(function(error) {
            var response = JSON.parse(error.responseText);
            alert_float('danger', response.message);
            }); 
})

$('body').on('hidden.bs.modal', '#payment_order_view', function() {
    $('#payment_order_data_view').html('');
});

</script>
