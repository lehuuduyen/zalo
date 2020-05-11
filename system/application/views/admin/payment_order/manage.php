<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <?php init_head(); ?>
    <style type="text/css">
    .progressbar_img{
        text-align: center!important;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
    .progressbar_img img{
      height: 25px !important;
      width: 25px !important;
    }
    ul.progressbar_img li.active_img img{
      border: 2px solid #00ff50;
    }
    ul.progressbar_img li.cancel img{
      border: 2px solid red;
    }
    ul.progressbar_img li.cancel_all img{
      border: 2px solid blue;
    }
    ul.progressbar_img li {
        width: 100px;
        float: left;
    }
        .progressbar:not(.hoang) {
        margin: 0;
        padding: 0;
        counter-reset: step;
    }
    .progressbar li span{
      font-size: 11px;
    }    
    .progressbar li:not(.hoang) {
        list-style-type: none;
        width: 13%;
        float: left;
        font-size: 12px;
        position: relative;
        text-align: center;
        /*text-transform: uppercase;*/
        color: #7d7d7d;
        z-index: 0;
    }
    .progressbar li:not(.hoang):before {
        width: 10px;
        height: 10px;
        content: ' ';
        counter-increment: step;
        line-height: 51px;
        border: 5px solid #7d7d7d;
        display: block;
        text-align: center;
        margin: 0 auto 10px auto;
        border-radius: 50%;
        background-color: white;
    }
    .progressbar li:not(.hoang):after {
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
    .progressbar li.active:not(.hoang) {
        width: 100px;
        color: green;
    }
    .progressbar li.active:not(.hoang):before {
        border-color: #55b776;
    }
    .progressbar li.cancel:before {
        border-color: red;
    }   
    .progressbar li.active + li:after {
        background-color: #55b776!important;
    }
    .table_popover_head div {
        width: 33%;
    }
    .table_popover_body span {
        width: 33%;
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
    .table-payment_order img{
        height: 20px;
        width: 20px;
    }
    .table-payment_order thead tr th{
       text-align: center;
    }
    .table-payment_order tr td:nth-child(2)
    {
                min-width: 120px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(3)
    {
                min-width: 120px;
                white-space: unset;
                text-align: center;
                
    }
    .table-payment_order tr td:nth-child(4)
    {
                min-width: 100px;
                white-space: unset;
                text-align: center;

    }
    .table-payment_order tr th:nth-child(5)
    {
                min-width: 250px;
                white-space: unset;
    }
    .table-payment_order tr td:nth-child(6)
    {
                min-width: 100px;
                white-space: unset;
                text-align: center; 
    }
    .table-payment_order tr td:nth-child(7)
    {
                min-width: 100px;
                white-space: unset;
                text-align: center; 
    }
    .table-payment_order tr td:nth-child(8)
    {
                min-width: 150px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(9)
    {
                min-width: 100px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(10)
    {
                min-width: 80px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(11)
    {
                min-width: 70px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(12)
    {
                min-width: 200px;
                white-space: unset;
    }
    .table-payment_order tr td:nth-child(13)
    {
                min-width: 130px;
                white-space: unset;
    }
    .table-payment_order tr td:nth-child(14)
    {
                min-width: 160px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(15)
    {
                min-width: 140px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(16)
    {
                min-width: 130px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(17)
    {
                min-width: 130px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(18)
    {
                min-width: 100px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(19)
    {
                min-width: 400px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(20)
    {
                min-width: 120px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(21)
    {
                min-width: 120px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(22)
    {
                min-width: 120px;
                white-space: unset;
                text-align: center;
    }
    .table-payment_order tr td:nth-child(23)
    {
                min-width: 120px;
                white-space: unset;
                text-align: center;
    }
    /*hoang crm bo xung*/
    .progressbar li {
        width: 100px !important;
    }
    /*end*/
    </style>
        <div id="wrapper">
           <div class="panel_s mbot10 H_scroll" id="H_scroll">
              <div class="panel-body ">
                 <div class="_buttons">
                    <span class="bold uppercase fsize18 H_title"><?=$title?></span>
                    <?php if (is_admin()) { ?>
                  <div class="line-sp"></div>
                  <a onclick="payment()" class="btn btn-info mright5 test pull-right H_action_button">
                     <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                     <?php echo _l('create_add_new'); ?></a>
                  <?php } ?>
                  <div class="clearfix"></div>
                  </div>
                  <div class="clearfix"></div>
              </div>
           </div>
           <div class="content">
              <div class="row">
                 <div class="col-md-12">
                    <div class="panel_s">
                       <div class="panel-body">
                          <div class="horizontal-scrollable-tabs">
                              <div class="scroller scroller-left arrow-left"><i class="fa fa-angle-left"></i></div>
                              <div class="scroller scroller-right arrow-right"><i class="fa fa-angle-right"></i></div>
                              <div class="horizontal-tabs">
                                  <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                    <li class="active">
                                        <a class="H_filter" data-id="all">
                                          <?=_l('leads_all')?> (<span class="all">0</span>)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="1">
                                          <?=_l('ch_payment_not_client')?> (<span class="no_determined">0</span>)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="2">
                                          <?=_l('ch_payment_client')?> (<span class="determined">0</span>)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="3">
                                          <?=_l('Hủy')?> (<span class="cancel">0</span>)
                                        </a>
                                    </li>
                                  </ul>
                              </div>
                          </div>
                        <input type="hidden" id="filterStatus" name="filterStatus" value=""/>
                          <div class="clearfix mtop20"></div>
                          <?php $table_data = array(
                              _l('#'),
                              _l('cong_status_receipts'),
                              _l('Mức độ ưu tiên'),
                              _l('Mã phiếu thu'),
                              _l('ch_client_payment'),
                              _l('ch_subtotal_payment'),
                              _l('cong_code_orders'),
                              _l('ch_subtotal_payment_receive'),
                              _l('cong_currency'),
                              _l('Tỉ Gía Ngoại Tệ/VND'),
                              _l('HTTT'),
                              _l('ch_text_payment'),
                              _l('ch_account_user'),
                              _l('ch_type_payment'),
                              _l('ch_account_bus'),
                              _l('ch_date_payment_receive'),
                              _l('Ngày Tạo Phiếu Thu'),
                              _l('Người Tạo Phiếu Thu'),
                              _l('Quy trình'),
                              _l('Mã khách hệ thống'),
                              _l('Mã khách tiềm năng'),
                              _l('Mã khách hàng'),
                              _l('Mã khách hiện tại'),
                            );
                            render_datatable($table_data,'payment_order');
                          ?>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
    <?php init_tail(); ?>
    <div id="payment_order_data"></div>
    <div id="payment_order_data_detail"></div>
    <link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
    <script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
    <script>
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
        $('.H_filter').click(function(e) {
          var target = $(e.currentTarget);
          var value = target.attr('data-id');
          target.parent().parent().find('li').removeClass('active');
          target.parent().addClass('active');
          $('input[name="filterStatus"]').val(value);
          $('input[name="filterStatus"]').change();
        });
        var tAPI ;
        $(function(){

            var CustomersServerParams = {
              'filterStatus' : '[name="filterStatus"]',
            };
            tAPI = initDataTableCustom('.table-payment_order', admin_url+'payment_order/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'desc'))); ?>, fixedColumns = {leftColumns: 4, rightColumns: 0});
            // var tAPI = initDataTable('.table-payment_order', admin_url+'payment_order/table', [0], [0], CustomersServerParams,[0, 'desc']);
            $.each(CustomersServerParams, function(filterIndex, filterItem){
              $('' + filterItem).on('change', function(){
                tAPI.draw('page');
              });
            });
        });
        $('.table-payment_order').on('draw.dt', function() {
              get_total_limit();
            });
        function var_status(status,id) {
            {
                dataString={id:id,status:status,[csrfData['token_name']] : csrfData['hash']};
                jQuery.ajax({   
                    type: "post",
                    url:"<?=admin_url()?>pay_slip/update_status",
                    data: dataString,
                    cache: false,
                    success: function (response) {
                        response = JSON.parse(response);
                        if (response.success == true) {
                            tAPI.draw('page');
                            alert_float('success', response.message);
                        }
                    }
                });
                return false;
            }
        }
        $(document).on('click', '.delete-remind', function() {
            var r = confirm("<?php echo _l('confirm_action_prompt');?>");
            if (r == false) {
                return false;
            } else {
                $.get($(this).attr('href'), function(response) {
                  alert_float(response.alert_type, response.message);
                    tAPI.draw('page');
                }, 'json');
            }
            return false;
        });
        function payment() {
            $('#payment_order_data').html('');
            $.get(admin_url + 'payment_order/payment_order/').done(function(response) {
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
        function payment_detail(id) {
            $('#payment_order_data_detail').html('');
            $.get(admin_url + 'payment_order/detail/'+id).done(function(response) {
            $('#payment_order_data_detail').html(response);
            $('#payment_order_detail').modal({show:true,backdrop:'static'});
            init_selectpicker();
            init_datepicker();
            }).fail(function(error) {
            var response = JSON.parse(error.responseText);
            alert_float('danger', response.message);
            }); 
        }
        $('body').on('hidden.bs.modal', '#payment_order_detail', function() {
            $('#payment_order_data_detail').html('');
        });
        function get_total_limit() {
          dataString = {[csrfData['token_name']] : csrfData['hash']};
            jQuery.ajax({
                type: "post",
                url: "<?=admin_url()?>payment_order/count_all/",
                data: dataString,
                cache: false,
                success: function (data) {
                  data = JSON.parse(data);
                  $('.all').html(data.all);
                  $('.no_determined').html(data.no_determined);
                  $('.determined').html(data.determined); 
                  $('.cancel').html(data.cancel); 
                  }
            });
        }
var inner_popover_template = '<div class="popover" style="width:400px;max-width: 500px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>'; 
$(document).on('click','.menu-receipts',function(e){
    $(this).popover({
        html: true,
        placement: "right",
        trigger: 'click',
        title:'<?php echo _l('Hủy phiếu thu'); ?>'+'<button class="close"><span aria-hidden="true">&times;</span></button>',
        content: function() {
            return $(this).find('.content-menu').html();
        },
        template: inner_popover_template
    });
});
$(document).on('click','.popover-title > .close',function(e){

    var id = $(this).parents('.popover').attr('id');
    $('[aria-describedby="'+id+'"]').popover('hide');
    console.log(id)
    // $('.menu-receipts').popover('hide');
    // $('.menu-receipts-status').popover('hide');
    // $('.js-care_of_client').popover('hide');
});
function ajaxSelectNotImg_ch(element, url, id, types = '',client='') {
    if (id != "")
    {
        var DataSelect = {
            width: 'resolve',
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: url + '/' + $(element).val(),
                    dataType: "json",
                    success: function (data)
                    {
                        callback(data.results);
                    }
                });
            },
            ajax: {
                url: url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        client: client,
                        types: types,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            },
            escapeMarkup: function (m) { return m; }
        };



        $(element).val(id).select2(DataSelect);
    }
    else
    {
        var DataSelect = {
            width: 'resolve',
            allowClear: true,
            ajax: {
                url: url,
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        types: types,
                        term: term,
                        limit: 50
                    };
                },
                results: function (data, page) {
                    if(data.results != null) {
                        return { results: data.results };
                    } else {
                        return { results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            },
            escapeMarkup: function (m) { return m; }
        };


        $(element).select2(DataSelect);
    }
}
$('body').on('click', '.editDataTable_ch', function(e){
    var type = $(this).attr('data-type');
    var client = $(this).attr('data-client');
    var _td = $(this).parents('td');
    _td.find('.lableScript').addClass('hide');
    _td.find('.inputScript').removeClass('hide');
    if(type == 'select')
    {
        var inputSelect =  _td.find('.inputScript').find('.ChangeDataTable');
        var url = $(this).attr('data-href');
        if(url)
        {
            if(inputSelect.hasClass('multiple'))
            {
                ajaxSelectNotImgSelect2(inputSelect, url, inputSelect.attr('data-hidden'),'', {'multiple': true});
            }
            else
            {
                ajaxSelectNotImg_ch(inputSelect, url, inputSelect.attr('data-hidden'),'',client);
            }
        }
        else
        {
            $(inputSelect).select2();
        }
    }
    init_datepicker();
    appValidateForm($('.formUpdateDataTable'), {}, manage_Udpdatecolum);
})
$(document).on('click','.po-close',function(e){
      $('.popover').popover('hide');
});
$(document).ready(function() {
    $(document).ready(function() {
        $(document).on('click', '.dropdown-menu .not-outside', function(event) {
            event.stopPropagation();
        });
    });
});
  function save_contact_person(id) {
    var note_cancel = $('#note_cancel').val();
    dataString={note_cancel:note_cancel,[csrfData['token_name']] : csrfData['hash']};
    jQuery.ajax({
      type: "post",
      url:"<?=admin_url()?>payment_order/note_cancel/"+id,
      data: dataString,
      cache: false,
      success: function (data) {
            $('.add_contact_person').popover('hide');
            tAPI.draw('page');
            $('.popover').popover('hide');
            data = JSON.parse(data);
            alert_float(data.alert_type,data.message)
          }
        });
  }
    </script>
