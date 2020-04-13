<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <?php init_head(); ?>
    <style type="text/css">
    .table-purchase_invoice tr td:nth-child(1){
       text-align: center;
    }
    .table-purchase_invoice tr td:nth-child(2){
       text-align: center;
    }
    .table-purchase_invoice tr td:nth-child(3){
        min-width: 100px;
        white-space: unset;
        text-align: center;
    }
    .table-purchase_invoice tr td:nth-child(4){
        min-width: 100px;
        white-space: unset;
        text-align: center;
    }
    .table-purchase_invoice tr td:nth-child(5){
        min-width: 110px;
        white-space: unset;
        text-align: center;
    }
    .table-purchase_invoice tr td:nth-child(6){
        min-width: 200px;
        white-space: unset;
    }
    .table-purchase_invoice tr td:nth-child(7){
        min-width: 110px;
        white-space: unset;
    }
    .table-purchase_invoice tr td:nth-child(8){
        min-width: 110px;
        white-space: unset;
    }
    .table-purchase_invoice tr td:nth-child(9){
        min-width: 110px;
        white-space: unset;
    }
    .table-purchase_invoice tr td:nth-child(10){
        min-width: 110px;
        white-space: unset;
    }
    .table-purchase_invoice tr td:nth-child(11){
        min-width: 100px;
        white-space: unset;
        text-align: center;
    }
    .table-purchase_invoice tr td:nth-child(12){
        min-width: 150px;
        white-space: unset;
        text-align: center;
    }
    .table-purchase_invoice img{
        height: 20px;
        width: 20px;
    }
    .table-purchase_invoice thead tr th{
       text-align: center;
    }
    </style>
        <div id="wrapper">
           <div class="panel_s mbot10 H_scroll" id="H_scroll">
              <div class="panel-body _buttons">
                 <div class="_buttons">
                    <span class="bold uppercase fsize18 H_title"><?=$title?></span>
                    <a  class="add_contact_person btn btn-info pull-right mleft5 H_action_button option_barcode" >
                    <?php echo _l('ch_pay_slip_total'); ?></a>
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
                          <input type="hidden" id="filterStatus" name="filterStatus" value=""/>
                          <input type="hidden" id="suppliers_id" name="suppliers_id" value=""/>
                          <div class="clearfix mtop20"></div>
                           <?php $table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="purchase_invoice"><label></label></div>';
                           ?>
                          <?php $table_data = array_merge($table_data, array(
                              _l('#'),
                              _l('ch_code_invoice'),
                              _l('ch_date_invoice'),
                              _l('ch_order'),
                              _l('supplier'),
                              _l('total_price_befor_vat'),
                              _l('total_price_vat'),
                              _l('total_price_affter_vat'),
                              _l('ch_other_expenses'),
                              _l('leads_dt_status'),
                              _l('leads_dt_assigned'),
                              _l('Link'),
                              _l('ch_option'),
                            ));
                            render_datatable($table_data,'purchase_invoice');
                          ?>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
    <div id="electronic_bill_data"></div>
    <div id="payment_data"></div>
    <?php init_tail(); ?>
    <link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
    <script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?= js('datatables/dataTables.fixedColumns.min.js') ?>"></script>
    <script>
        $(document).on('change', '#id_suppliers', function() {
        $('#suppliers_id').val($('#id_suppliers').val());
        $('#suppliers_id').change();
        });
        var tAPI;
        $(function(){

            var CustomersServerParams = {
              'suppliers_id' : '[name="suppliers_id"]',
            };
            tAPI = initDataTableCustom('.table-purchase_invoice', admin_url+'purchase_invoice/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1,'desc'))); ?>, fixedColumns = {leftColumns: 4, rightColumns: 0}, true);
            // var tAPI = initDataTable('.table-purchase_invoice', admin_url+'purchase_invoice/table', [0], [0], CustomersServerParams,[1, 'desc']);
            $.each(CustomersServerParams, function(filterIndex, filterItem){
              $('' + filterItem).on('change', function(){
                tAPI.draw('page');
              });
            });
        });
        function var_status(status,id) {
            {
                dataString={id:id,status:status,[csrfData['token_name']] : csrfData['hash']};
                jQuery.ajax({   
                    type: "post",
                    url:"<?=admin_url()?>purchase_invoice/update_status",
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
                    tAPI.draw('page');
                    // table_api.ajax.reload();
                    
                    data = JSON.parse(data);
                    alert_float(data.alert_type,data.message)
                  }
                });
          }
        $(document).on('click','.close',function(e){
          $('.add_contact_person').popover('hide');
          $('#suppliers_id').val('');
          $('#suppliers_id').change();
        }); 
        $('body').on('hidden.bs.modal', '#views_import', function() {
            $('#import_data').html('');
            $('.table-import').DataTable().ajax.reload();
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
        function electronic_bill(id) {
          $('#electronic_bill_data').html('');
          $.get(admin_url + 'purchase_invoice/electronic_bill/'+id).done(function(response) {
          $('#electronic_bill_data').html(response);
          $('#electronic_bill').modal({show:true,backdrop:'static'});
          init_selectpicker();
          init_datepicker();
          }).fail(function(error) {
          var response = JSON.parse(error.responseText);
          alert_float('danger', response.message);
          }); 
        }
        $('body').on('hidden.bs.modal', '#electronic_bill', function() {
            $('#electronic_bill_data').html('');
        });
        function payment(id) {
          $('#payment_data').html('');
          $.get(admin_url + 'purchase_invoice/payment/'+id).done(function(response) {
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
                var rows = $('.table-purchase_invoice').find('tbody tr');
                $.each(rows, function() {
                    var checkbox = $($(this).find('td').eq(0)).find('input');
                    if (checkbox.prop('checked') == true) {
                        ids+=checkbox.val()+',';
                    }
                });
                if(empty(ids))
                {
                  alert("<?=_l('<?=ch_pay_total_all?>')?>");
                  return;
                }
                else
                {
                    $('#payment_data').html('');
                    dataString={ids:ids,[csrfData['token_name']] : csrfData['hash']};
                    jQuery.ajax({   
                        type: "post",
                        url:"<?=admin_url()?>purchase_invoice/payment_all",
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
    </script>
