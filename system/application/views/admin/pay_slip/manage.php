<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <?php init_head(); ?>
    <style type="text/css">

    .table-pay_slip img{
        height: 20px;
        width: 20px;
    }
    .table-pay_slip thead tr th{
       text-align: center;
    }
    .table-pay_slip tr td:nth-child(2)
    {
                min-width: 90px;
                white-space: unset;
    }
    .table-pay_slip tr td:nth-child(3)
    {
                min-width: 95px;
                white-space: unset;
                text-align: center;

    }
    .table-pay_slip tr td:nth-child(4)
    {
                min-width: 110px;
                white-space: unset;
                text-align: center;
    }
    .table-pay_slip tr td:nth-child(5)
    {
                min-width: 110px;
                white-space: unset;
                text-align: center;
                
    }
    .table-pay_slip tr td:nth-child(6)
    {
                min-width: 250px;
                white-space: unset;
    }
    .table-pay_slip tr td:nth-child(7)
    {
                min-width: 90px;
                white-space: unset;
    }
    .table-pay_slip tr td:nth-child(8)
    {
                min-width: 110px;
                white-space: unset;
                text-align: center; 
    }
    .table-pay_slip tr td:nth-child(9)
    {
                min-width: 120px;
                white-space: unset;
    }
    .table-pay_slip tr td:nth-child(10)
    {
                min-width: 120px;
                white-space: unset;
    }
    .table-pay_slip tr td:nth-child(11)
    {
                min-width: 160px;
                white-space: unset;
                text-align: center;
    }
    .table-pay_slip tr td:nth-child(12)
    {
                min-width: 150px;
                white-space: unset;
    }
    </style>
        <div id="wrapper">
           <div class="panel_s mbot10 H_scroll" id="H_scroll">
              <div class="panel-body ">
                 <div class="_buttons">
                    <span class="bold uppercase fsize18 H_title"><?=$title?></span>
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
                                          <?=_l('leads_all')?>(<?=total_rows('tblpay_slip');?>)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="1">
                                          <?=_l('ch_invoice_tax')?>(<?=total_rows('tblpay_slip',array('type'=>1));?>)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="2">
                                          <?=_l('ch_retail_invoice')?>(<?=total_rows('tblpay_slip',array('type'=>2));?>)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="3">
                                          <?=_l('ch_status_pays_slip')?>(<?=total_rows('tblpay_slip',array('status'=>1));?>)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="4">
                                          <?=_l('ch_status_pays_slip_no')?>(<?=total_rows('tblpay_slip',array('status'=>0));?>)
                                        </a>
                                    </li>
                                  </ul>
                              </div>
                          </div>
                        <input type="hidden" id="filterStatus" name="filterStatus" value=""/>
                          <div class="clearfix mtop20"></div>
                          <?php $table_data = array(
                              _l('#'),
                              _l('ch_code_pay_slip'),
                              _l('ch_type_invoice'),
                              _l('ch_code_old'),
                              _l('ch_date_p'),
                              _l('supplier'),
                              _l('ch_all_total'),
                              _l('ch_price_pay_slip'),
                              _l('acs_leads_statuses_submenu'),
                              _l('ch_addedfrom'),
                              _l('acs_sales_payment_modes_submenu'),
                              _l('note'),
                              _l('ch_option'),

                            );
                            render_datatable($table_data,'pay_slip');
                          ?>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
    <?php init_tail(); ?>
    <div id="view_pay_slip_data"></div>
    <link rel="stylesheet" type="text/css" href="<?= css('fixdatatable.css') ?>">
    <script type="text/javascript" src="<?= js('datatables/jquery.dataTables.min.js') ?>"></script>
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
        var tAPI;
        $(function(){

            var CustomersServerParams = {
              'filterStatus' : '[name="filterStatus"]',
            };
            tAPI = initDataTableCustom('.table-pay_slip', admin_url+'pay_slip/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'desc'))); ?>, fixedColumns = {leftColumns: 4, rightColumns: 0});
            // var tAPI = initDataTable('.table-pay_slip', admin_url+'pay_slip/table', [0], [0], CustomersServerParams,[0, 'desc']);
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
        function view_pay_slip(id) {
            $('#view_pay_slip_data').html('');
            $.get(admin_url + 'pay_slip/electronic_bill/'+id).done(function(response) {
            $('#view_pay_slip_data').html(response);
            $('#view_pay_slip').modal({show:true,backdrop:'static'});
            init_selectpicker();
            init_datepicker();
            }).fail(function(error) {
            var response = JSON.parse(error.responseText);
            alert_float('danger', response.message);
            }); 
        }
        $('body').on('hidden.bs.modal', '#view_pay_slip', function() {
            $('#view_pay_slip_data').html('');
        });
        $('body').on('hidden.bs.modal', '#views_import', function() {
            $('#import_data').html('');
            $('.table-import').DataTable().ajax.reload();
        });
    </script>
