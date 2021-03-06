<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
  .table-import img {
    height: 20px;
    width: 20px;
}
    .table-import tr td:nth-child(1){
        text-align: center;
    }
    .table-import tr td:nth-child(2){
        min-width: 110px;
        white-space: unset;
        text-align: center;
    }
    .table-import tr td:nth-child(3){
        min-width: 108px;
        white-space: unset;
        text-align: center;
    }
    .table-import tr td:nth-child(4){
        min-width: 110px;
        white-space: unset;
        text-align: center;
    }
    .table-import tr td:nth-child(5){
        min-width: 180px;
        white-space: unset;
    }
    .table-import tr td:nth-child(6){
        min-width: 100px;
        white-space: unset;
        text-align: right;
    }
    .table-import tr td:nth-child(7){
        min-width: 130px;
        white-space: unset;
        text-align: center;
    }
    .table-import tr td:nth-child(9){
        min-width: 130px;
        white-space: unset;
    }
    .table-import tr td:nth-child(10){
        min-width: 70px;
        white-space: unset;
    }
    .table-import tr td:nth-child(11){
        min-width: 100px;
        white-space: unset;
    }
    .table-import tr td:nth-child(12){
        min-width: 100px;
        white-space: unset;
    }
    .table-import thead tr th{
       text-align: center;
    }

</style>
<div id="wrapper">
   <div class="panel_s mbot10 H_scroll" id="H_scroll">
      <div class="panel-body _buttons">
         <div class="_buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <a class="search_person btn btn-info pull-right mright5 H_action_button">
               <span style="font-size: 16px;margin-bottom: 3px;" class="lnr lnr-funnel"></span>   
               <?php echo _l('ch_seach_statistical'); ?>
            </a>
            <a href="<?=admin_url('option_pdf')?>" class="btn btn-info mright5 test pull-right H_action_button" target="_blank">
               <?php echo _l('option_pdf'); ?></a>
            <?php if (has_permission('import','','create')) { ?>
            <!-- <div class="line-sp"></div> -->
            <a href="<?=admin_url('import/detail')?>"  class="btn btn-info mright5 test pull-right H_action_button">
               <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
               <?php echo _l('create_add_new'); ?></a>
            <?php } ?>
            <div class="clearfix"></div>
         </div>
      </div>
   </div>
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                <div class="clearfix"></div>
                  <div class="horizontal-scrollable-tabs">
                      <div class="scroller scroller-left arrow-left"><i class="fa fa-angle-left"></i></div>
                      <div class="scroller scroller-right arrow-right"><i class="fa fa-angle-right"></i></div>
                      <div class="horizontal-tabs">
                          <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                            <li class="active">
                                <a class="H_filter" data-id="all">
                                  <?=_l('leads_all')?>(<?=total_rows('tblimport');?>)
                                </a>
                            </li>
                            <li>
                                <a class="H_filter" data-id="3">
                                  <?=_l('ch_confirm_22')?>(<?=total_rows('tblimport',array('status'=>2));?>)
                                </a>
                            </li>
                            <li>
                                <a class="H_filter" data-id="4">
                                  <?=_l('dont_approve')?>(<?=total_rows('tblimport',array('status'=>1));?>)
                                </a>
                            </li>
                            <li>
                                <a class="H_filter" data-id="5">
                                  <?=_l('ch_warehouse_d')?>(<?=total_rows('tblimport',array('warehouseman_id <>'=>0));?>)
                                </a>
                            </li>
                            <li>
                                <a class="H_filter" data-id="6">
                                  <?=_l('ch_warehouse_nd')?>(<?=total_rows('tblimport',array('warehouseman_id'=>0));?>)
                                </a>
                            </li>
                          </ul>
                      </div>
                  </div>
                  <input type="hidden" id="filterStatus" name="filterStatus" value=""/>
                  <input type="hidden" id="suppliers_id" name="suppliers_id" value=""/>
                  <div class="clearfix"></div>
                  <?php $table_data = array(
                      _l('#'),
                      _l('ch_code_p'),
                      _l('ch_code_old'),
                      _l('ch_date_p'),
                      _l('supplier'),
                      _l('total_price'),
                      _l('ch_addedfrom'),
                      _l('invoice_dt_table_heading_status'),
                      _l('ch_warehoues_app'),
                    );
                    $custom_fields = get_custom_fields('imports',array('show_on_table'=>1));
                    foreach($custom_fields as $field){
                      array_push($table_data,$field['name']);
                    }
                    array_push($table_data,_l('ch_option'));
                    render_datatable($table_data,'import');
                  ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="red_invoice_all" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('ch_red_invoice_all'); ?>
        </h4>
      </div>
      <?php echo form_open('admin/purchase_invoice/add_all',array('id'=>'invoice_all-form')); ?>
      <div class="modal-body" style="background: #f1f1f1">
          <div class="panel_s panel_box">
            <div class="panel-body">
                <input id="id_import_all" class="id_import_all hide" name="id_import_all">
                <input id="id_supplier" class="id_supplier hide" name="id_supplier">
                <?php echo render_input('code_invoice_all', 'ch_code_invoice'); ?>
                <?php echo render_date_input('date_invoice_all', 'ch_date_invoice'); ?>
                <?php echo render_textarea('note_all', 'ch_note') ?>
            </div>
          </div>
        <div class="clearfix"></div>
      </div>
      <div class="modal-footer" style="background: #f1f1f1">
        <button type="submit" class="btn btn-info" target="_blank"><?php echo _l('create_add_new'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<div id="payment_data"></div>
<link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
<!-- <script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script> -->
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script>
$(document).on('change', '#id_suppliers', function() {
$('#suppliers_id').val($('#id_suppliers').val());
$('#suppliers_id').change();
});
$(document).on('change', '#id_suppliers_invoice', function() {
$('#suppliers_id').val($('#id_suppliers_invoice').val());
$('#suppliers_id').change();
});
  function red_invoice_all() {
            $('#id_supplier').val('');
            $('#id_import_all').val('');
            var ids = '';
            var rows = $('.table-import').find('tbody tr');
            $.each(rows, function() {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') == true) {
                    ids+=checkbox.val()+',';
                }
            });
            if(empty(ids))
            {
              alert("<?=_l('ch_null_invoice_all')?>");
              return;
            }else
            {
            $('#id_supplier').val($('#suppliers_id').val());
            $('#id_import_all').val(ids);
            }
   $('#red_invoice_all').modal('show');
  }
_validate_form($('#invoice_all-form'),{code_invoice_all:'required',date_invoice_all:'required'},purchase_invoice_all);
   function purchase_invoice_all(form) {
       var data = $(form).serialize(),
           action = form.action;
       return $.post(action, data).done(function(form) {
           form = JSON.parse(form);
           alert_float(form.alert_type, form.message);
            $('#red_invoice_all').modal('hide');
            $('.table-import').DataTable().ajax.reload(); 
            $('#suppliers_id').val('');
            $('#suppliers_id').change();
            $('.add_contact_person_invoice').popover('hide');
            window.open('<?=admin_url('purchase_invoice')?>', "_blank");  
       }), !1
   }
$('.H_filter').click(function(e) {
  var target = $(e.currentTarget);
  var value = target.attr('data-id');
  target.parent().parent().find('li').removeClass('active');
  target.parent().addClass('active');
  $('input[name="filterStatus"]').val(value);
  $('input[name="filterStatus"]').change();
});
var tAPI;
$(function(){
    var CustomersServerParams = {
      'filterStatus' : '[name="filterStatus"]',
      'suppliers_id' : '[name="suppliers_id"]',
      'search_code' : '[name="search_code"]',
      'search_staff' : '[name="search_staff[]"]',
      'search_id_suppliers' : '[name="search_id_suppliers[]"]',
      'search_date' : '[name="search_date"]',
    };
    tAPI = initDataTableCustom('.table-import', admin_url+'import/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'desc'))); ?>, fixedColumns = {leftColumns: 0, rightColumns: 0});
    // var tAPI = initDataTable('.table-import', admin_url+'import/table', [0], [0], CustomersServerParams,[1, 'desc']);
    $.each(CustomersServerParams, function(filterIndex, filterItem){
      $('' + filterItem).on('change', function(){
        tAPI.ajax.reload();
      });
    });

    $('.table-import').on('draw.dt', function() {
      checkNoDrop();
    });
});
function view_supplier_quotes(id) {
    $('#view_supplier_quotes').html('');
    $.get(admin_url + 'supplier_quotes/view_supplier_quotes/' + id).done(function (response) {
        $('#view_supplier_quotes').html(response);
        $('#views_items').modal('show');
    }).fail(function (error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    });
}
$('body').on('hidden.bs.modal', '#views_import', function() {
    $('#import_data').html('');
    $('.table-import').DataTable().ajax.reload();
});
$('body').on('hidden.bs.modal', '#views_items', function() {
    $('#view_supplier_quotes').html('');
});
function view_purchase_order(id) {
    $('#purchase_order_data').html('');
    $.get(admin_url + 'purchase_order/view_purchase_order/' + id).done(function (response) {
        $('#purchase_order_data').html(response);
        $('#view_purchase_order').modal('show');
    }).fail(function (error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    });
}
$('body').on('hidden.bs.modal', '#view_purchase_order', function() {
    $('#purchase_order_data').html('');
});
function view_purchases(id) {
    $('#purchases_data').html('');
    $.get(admin_url + 'purchases/views_purchases/' + id).done(function (response) {
        $('#purchases_data').html(response);
        $('#views_purchases').modal('show');
    }).fail(function (error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    });
}

$(document).on('click', '.delete-remind', function() {
    var r = confirm("<?php echo _l('confirm_action_prompt');?>");
    if (r == false) {
        return false;
    } else {
        $.get($(this).attr('href'), function(response) {
          alert_float(response.alert_type, response.message);
            $('.table-import').DataTable().ajax.reload();
        }, 'json');
    }
    return false;
});
$(document).on('click', '.change_type', function() {
  setTimeout(function(){
    $('.table-import').DataTable().ajax.reload();
    }, 500);
});
function confirm_warehous(id,warehouseman_id) {
    {
        dataString={id:id,warehouseman_id:warehouseman_id,[csrfData['token_name']] : csrfData['hash']};
        jQuery.ajax({   
            type: "post",
            url:"<?=admin_url()?>import/confirm_warehous",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                $('.table-import').DataTable().ajax.reload();
                alert_float(response.alert_type, response.message);
            }
        });
        return false;
    }
}
function var_status(status,id) {
    {
        dataString={id:id,status:status,[csrfData['token_name']] : csrfData['hash']};
        jQuery.ajax({   
            type: "post",
            url:"<?=admin_url()?>import/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $('.table-import').DataTable().ajax.reload();
                    alert_float('success', response.message);
                }
            }
        });
        return false;
    }
}
    $(document).on('click', '.invoice_button', (e)=>{
      $('#purchase_invoice-form').attr('action','<?=admin_url("purchase_invoice/add")?>');
      $('#code_invoice').val('');
      $('#note').val('');
      $('#date_invoice').val("<?=_d(date('Y-m-d'));?>");
    });  
function send_quote_suppliers(supplier_id,ask_price) {
    $('#send_quote_suppliers').html('');
    $.get(admin_url + 'RFQ/send_quote_suppliers/' + supplier_id + '/' +ask_price).done(function (response) {
        $('#send_quote_suppliers').html(response);
        $('#send_quote').modal('show');
        init_editor();
    }).fail(function (error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    });
}
var inner_popover_template = '<div class="popover" style="width:400px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>';
  $(document).on('click','.add_contact_person',function(e){
    $('#suppliers_id').val('');
    $('#suppliers_id').change();
    $('.add_contact_person_invoice').popover('hide');
    var id=$(this).attr('data-id');
    var dropdown_menu='\
    <?php
        echo render_select('id_suppliers',$suppliers,array('id','company'),'ch_chose_suppliers');
    ?>
    <button type="button" onclick="payment_all();return false;" class="btn btn-info btn-block mtop15"><?php echo _l('ch_submit_import'); ?></button>\
    </div>';
    $('select[name="id_suppliers"]').selectpicker('refresh');
    $(this).popover({
      html: true,
      container: 'body',
      placement: "bottom",
      trigger: 'click focus',
      // trigger: 'focus',
      title:'<?=_l('ch_pay_slip_total')?><button type="button" class="close close_pay">&times;</button>',
      content: function() {
        return dropdown_menu;
      },
      template: inner_popover_template
    });
    $('#suppliers_id').selectpicker('refresh');
  });
  var inner_popover_template_invoice = '<div class="popover" style="width:400px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>';
  $(document).on('click','.add_contact_person_invoice',function(e){
    $('#suppliers_id').val('');
    $('#suppliers_id').change();
    $('.add_contact_person').popover('hide');
    var id=$(this).attr('data-id');
    var dropdown_menu='\
    <?php
        echo render_select('id_suppliers_invoice',$suppliers,array('id','company'),'ch_chose_suppliers');
    ?>
    <button type="button" onclick="red_invoice_all();return false;" class="btn btn-info btn-block mtop15"><?php echo _l('ch_submit_import'); ?></button>\
    </div>';
    $('select[name="id_suppliers"]').selectpicker('refresh');
    $(this).popover({
      html: true,
      container: 'body',
      placement: "bottom",
      trigger: 'click focus',
      // trigger: 'focus',
      title:'<?=_l('ch_red_invoice_all')?><button type="button" class="close close_invoice">&times;</button>',
      content: function() {
        return dropdown_menu;
      },
      template: inner_popover_template_invoice
    });
    $('#id_suppliers_invoice').selectpicker('refresh');
  });
  function save_contact_person(id) {
    var note_cancel = $('#note_cancel').val();
    dataString={note_cancel:note_cancel,[csrfData['token_name']] : csrfData['hash']};
    jQuery.ajax({
      type: "post",
      url:"<?=admin_url()?>purchases/note_cancel/"+id,
      data: dataString,
      cache: false,
      success: function (data) {
            // itemList = data;
            $('.add_contact_person').popover('hide');
            $('.table-import').DataTable().ajax.reload();
            // table_api.ajax.reload();
            
            data = JSON.parse(data);
            alert_float(data.alert_type,data.message)
          }
        });
  }
    $(document).on('click','.close_pay',function(e){
      $('.add_contact_person').popover('hide');
      $('#suppliers_id').val('');
      $('#suppliers_id').change();
    }); 
    $(document).on('click','.close_invoice',function(e){
      $('.add_contact_person_invoice').popover('hide');
      $('#suppliers_id').val('');
      $('#suppliers_id').change();
    }); 
    $(document).on('click','.po-close',function(e){
      $('.popover').popover('hide');
    });
    function payment(id) {
      // id_supplierss

          $('#payment_data').html('');
          $.get(admin_url + 'import/payment/'+id).done(function(response) {
          $('#payment_data').html(response);
          $('#payment').modal({show:true,backdrop:'static'});
          init_selectpicker();
          init_datepicker();
          }).fail(function(error) {
          var response = JSON.parse(error.responseText);
          alert_float('danger', response.message);
          }); 
    }
    $('body').on('hidden.bs.modal', '#payment', function() {
        $('#payment_data').html('');
    });
    function payment_all()
    {
            
            var ids = '';
            var rows = $('.table-import').find('tbody tr');
            $.each(rows, function() {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') == true) {
                    ids+=checkbox.val()+',';
                }
            });
            if(empty(ids))
            {
              alert("<?=_l('ch_pay_total_all')?>");
              return;
            }
            else
            {
                $('#payment_data').html('');
                dataString={ids:ids,[csrfData['token_name']] : csrfData['hash']};
                jQuery.ajax({   
                    type: "post",
                    url:"<?=admin_url()?>import/payment_all",
                    data: dataString,
                    cache: false,
                    success: function (response) {
                        $('#payment_data').html(response);
                        $('#payment').modal({show:true,backdrop:'static'});

                        init_selectpicker();
                        init_datepicker();
                        $('#id_supplierss').val($('#suppliers_id').val());
                    }
                });
                return false;
            }
    }


    //hoàng crm bổ xung search
    var inner_popover_template = '<div class="popover" style="width:1000px;max-width: 2000px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>';
    $(document).on('click','.search_person',function(e){
       var dropdown_menu='\
          <div class="col-md-3">\
            <?php echo render_select('search_code',array(),array('id','company'),'ch_code_p');?>\
          </div>\
          <div class="col-md-3">\
            <?php echo render_select('search_staff[]',$dataStaff,array('staffid','name'),'ch_staff_p','',array('data-actions-box'=>1,'multiple'=>true),array(),'','',false);?>\
          </div>\
          <div class="col-md-3">\
            <?php echo render_select('search_id_suppliers[]',$dataSupplier,array('id','company','code'),'ch_name_suppliers','',array('data-actions-box'=>1,'multiple'=>true),array(),'','',false);?>\
          </div>\
          <div class="col-md-3">\
            <div class="form-group">\
               <label for="search_date" class="control-label"><?=_l('ch_date_p')?></label>\
               <div class="input-group">\
                  <input type="text" id="search_date" name="search_date" class="form-control search_date" aria-invalid="false">\
                  <div class="input-group-addon">\
                     <i class="fa fa-calendar calendar-icon"></i>\
                  </div>\
               </div>\
            </div>\
          </div>\
       ';
       $(this).popover({
          html: true,
          container: 'body',
          placement: "bottom",
          trigger: 'click focus',
          title:'<?=_l('ch_seach_statistical')?><button type="button" class="close">&times;</button>',
          content: function() {
             return dropdown_menu;
          },
          template: inner_popover_template
       });
       init_selectpicker();
       init_ajax_searchs('import','#search_code');
       search_daterangepicker();
    });
    $(document).on('click','.close',function(e){
       $('.search_person').popover('hide');
    });

    function init_ajax_searchs(e, t, a, i) {
       var n = $("body").find(t);
       var h = t;
       if (n.length) {
          var s = {
             ajax: {
                 url: void 0 === i ? admin_url + "misc/get_relation_data" : i,
                 data: function() {
                     var t = {[csrfData.token_name] : csrfData.hash};
                     return t.type = e, t.rel_id = "", t.q = "{{{q}}}", void 0 !== a && jQuery.extend(t, a), t
                 }
             },
             locale: {
                 emptyTitle: app.lang.search_ajax_empty,
                 statusInitialized: app.lang.search_ajax_initialized,
                 statusSearching: app.lang.search_ajax_searching,
                 statusNoResults: app.lang.not_results_found,
                 searchPlaceholder: app.lang.search_ajax_placeholder,
                 currentlySelected: app.lang.currently_selected
             },
             requestDelay: 500,
             cache: !1,
             preprocessData: function(e) {
                 var t = [];
                 var _temp_all = {
                    'value': 'all',
                    'text': 'Tất cả',
                 };
                 t.push(_temp_all);
                 for (var a = e.length, i = 0; i < a; i++) {
                     var n = {
                         value: e[i].id,
                         text: e[i].name
                     }; t.push(n)
                 }
                 return t;
             },
             preserveSelectedPosition: "after",
             preserveSelected: !0
          };
          n.data("empty-title") && (s.locale.emptyTitle = n.data("empty-title")), n.selectpicker().ajaxSelectPicker(s);
       }
    }

    var search_daterangepicker = () => {
       $('input[name="search_date"]').daterangepicker({
          opens: 'left',
          autoUpdateInput: false, 
          isInvalidDate: false,
          "locale": {
             "format": "DD/MM/YYYY",
             "separator": " - ",
             "applyLabel": lang_daterangepicker.applyLabel,
             "cancelLabel": lang_daterangepicker.cancelLabel,
             "fromLabel": lang_daterangepicker.fromLabel,
             "toLabel": lang_daterangepicker.toLabel,
             "customRangeLabel": lang_daterangepicker.customRangeLabel,
             "daysOfWeek": lang_daterangepicker.daysOfWeek,
             "monthNames": lang_daterangepicker.monthNames
          },
       }, function(start, end, label) {
       });
       $('input[name="search_date"]').val('').datepicker("refresh");
       $('input[name="search_date"]').on('apply.daterangepicker', function(ev, picker) {
          $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
          $( "#search_date" ).trigger( "change" );
       });
       $('input[name="search_date"]').on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
          $( "#search_date" ).trigger( "change" );
       });
    };

    $(document).on('change','#search_code',function(e){
       $('.table-import').DataTable().ajax.reload();
    });
    $(document).on('change','select[name="search_staff[]"]',function(e){
       $('.table-import').DataTable().ajax.reload();
    });
    $(document).on('change','select[name="search_id_suppliers[]"]',function(e){
       $('.table-import').DataTable().ajax.reload();
    });
    $(document).on('change','#search_date',function(e){
       $('.table-import').DataTable().ajax.reload();
    });
    //end
</script>
