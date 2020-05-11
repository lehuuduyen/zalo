<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
  .table-rfq tr td:nth-child(8){
      max-width: 100px;
      white-space: inherit;
      min-width: 100px;
  }
  .table-rfq tr td:nth-child(2){
      min-width: 100px;
      white-space: unset;
  }
  .table-rfq tr td:nth-child(3){
      min-width: 120px;
      white-space: unset;
  }
  .table-rfq tr td:nth-child(5){
      min-width: 130px;
      white-space: unset;
  }
  .table-rfq tr td:nth-child(7){
      min-width: 130px;
      white-space: unset;
  }
  .table-rfq thead tr th{
     text-align: center;
  }
  .table-rfq img{
        height: 20px;
        width: 20px;
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
            <a class="btn btn-info mright5 test pull-right H_action_button">
               <?php echo _l('Export excel'); ?></a>
            <div class="clearfix"></div>
         </div>
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
                                  <?=_l('leads_all')?>(<?=total_rows('tblrfq_ask_price');?>)
                                </a>
                            </li>
                            <li>
                                <a class="H_filter" data-id="1">
                                  <?=_l('dont_approve')?>(<?=total_rows('tblrfq_ask_price',array('status'=>1));?>)
                                </a>
                            </li>
                            <li>
                                <a class="H_filter" data-id="2">
                                  <?=_l('do_approve')?>(<?=total_rows('tblrfq_ask_price',array('status'=>2));?>)
                                </a>
                            </li>
                          </ul>
                      </div>
                  </div>
                  <input type="hidden" id="filterStatus" name="filterStatus" value=""/>
                  <div class="clearfix mtop20"></div>
                  <?php $table_data = array(
                      _l('ID'),
                      _l('ch_code_p'),
                      _l('ch_code_old'),
                      _l('ch_name_suppliers'),
                      _l('ch_staff_crate_rfq'),
                      _l('ch_date_p'),
                      _l('invoice_dt_table_heading_status'),
                      _l('settings_sales_heading_estimates'),
                      _l('ch_option'),
                    );
                    render_datatable($table_data,'rfq');
                  ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
<!-- <script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script> -->
<script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
<script>

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
      'search_code' : '[name="search_code"]',
      'search_staff' : '[name="search_staff[]"]',
      'search_id_suppliers' : '[name="search_id_suppliers[]"]',
      'search_date' : '[name="search_date"]',
    };

    tAPI = initDataTableCustom('.table-rfq', admin_url+'RFQ/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'desc'))); ?>, fixedColumns = {leftColumns: 0, rightColumns: 0});
    $.each(CustomersServerParams, function(filterIndex, filterItem){
      $('' + filterItem).on('change', function(){
        tAPI.draw('page');
      });
    });
});
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
function var_status(status,id) {
    {
        dataString={id:id,status:status,[csrfData['token_name']] : csrfData['hash']};
        jQuery.ajax({   
            type: "post",
            url:"<?=admin_url()?>RFQ/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    alert_float('success', response.message);
                    tAPI.draw('page');
                }
            }
        });
        return false;
    }
}
$(document).on('change', '#items_ch', function(e) {
      var currentQuantityInput = $(e.currentTarget);
      var supplier_id =  $(e.currentTarget).attr('data-id'); 
      var type = $('option:selected', e.currentTarget).attr('data-idd');
      var lengths = $('table.table-striped_'+supplier_id+' tbody tr').length;
      if(currentQuantityInput.val() != '')
      {
      if($('table.table-striped_'+supplier_id+' tbody tr').find('td input[value=' + currentQuantityInput.val() + ']').length) {
          alert_float('warning', "<?=_l('ch_exsit_items_rfq')?>");
          return;
      }else
      {
      
      var td='<tr>\
        <td>'+(lengths+1)+'</td>\
        <td class="text-center"><img class="mbot5" style="border-radius: 50%;width: 4em;height: 4em;" src="'+findpurchase(currentQuantityInput.val()).avatar_1+'"><br><div>'+findItemtypetemtype(type)+'</div></td>\
        <td>\
          <input type="text" name="items['+supplier_id+']['+lengths+'][product_id]" class="hide" value="'+currentQuantityInput.val()+'"><input type="text" name="items['+supplier_id+']['+lengths+'][type]" class="hide" value="'+type+'">'+findpurchase(currentQuantityInput.val()).name_item+'<br>('+findpurchase(currentQuantityInput.val()).code_item+')\
        </td>\
        <td class="text-center">'+findpurchase(currentQuantityInput.val()).html+'</td>\
        <td class="center"><input type="text" class="hide" name="items['+supplier_id+']['+lengths+'][quantity_net]" value="'+findpurchase(currentQuantityInput.val()).quantity_net+'">'+findpurchase(currentQuantityInput.val()).quantity_net+'\
        </td>\
        <td class="center"><i onclick="deleteTrItem(this); return false;" class="fa fa-times"></i></td>\
      </tr>';
      $('table.table-striped_'+supplier_id+' tbody').append(td);
      }
      }
    });
// function int_suppliers_view(id=null,edit = false) 
// {
//     $('#suppliers_view_data').html('');
//     $.get(admin_url + 'suppliers/int_suppliers_view/' + edit + '/' + id+'/1').done(function(response) {
//     $('#suppliers_view_data').html(response);
//     $('#suppliers_add').modal({show:true,backdrop:'static'});
//     init_selectpicker();
//     init_datepicker();
//     }).fail(function(error) {
//     var response = JSON.parse(error.responseText);
//     alert_float('danger', response.message);
// });    
// }
// $('body').on('hidden.bs.modal', '#suppliers_add', function() {
//     $('#suppliers_view_data').html('');
// });
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
   init_ajax_searchs('rfq','#search_code');
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
   tAPI.draw('page');
});
$(document).on('change','select[name="search_staff[]"]',function(e){
   tAPI.draw('page');
});
$(document).on('change','select[name="search_id_suppliers[]"]',function(e){
   tAPI.draw('page');
});
$(document).on('change','#search_date',function(e){
   tAPI.draw('page');
});
//end
</script>
