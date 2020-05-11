<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <?php init_head(); ?>
    <style type="text/css">

    .table-inventory img{
        height: 20px;
        width: 20px;
    }
    .table-inventory thead tr th{
       text-align: center;
    }
    .table-inventory tr td:nth-child(2)
    {
                min-width: 95px;
                white-space: unset;
                text-align: center;
    }
    .table-inventory tr td:nth-child(3)
    {
                min-width: 105px;
                white-space: unset;
                text-align: center;

    }
    .table-inventory tr td:nth-child(4)
    {
                min-width: 125px;
                white-space: unset;
                text-align: center;
    }
    .table-inventory tr td:nth-child(5)
    {
                min-width: 125px;
                white-space: unset;
                text-align: center;
                
    }
    .table-inventory tr td:nth-child(6)
    {
                min-width: 120px;
                white-space: unset;
    }
    .table-inventory tr td:nth-child(7)
    {
                min-width: 120px;
                white-space: unset;
    }
    .table-inventory tr td:nth-child(8)
    {
                min-width: 120px;
                white-space: unset;
    }
    .table-inventory tr td:nth-child(9)
    {
                min-width: 250px;
                white-space: unset;
    }
    </style>
        <div id="wrapper">
           <div class="panel_s mbot10 H_scroll" id="H_scroll">
              <div class="panel-body ">
                 <div class="_buttons">
                    <span class="bold uppercase fsize18 H_title"><?=$title?></span>
                    <?php if (has_permission('inventory','','create')) { ?>
                    <div class="line-sp"></div>
                    <a href="<?=admin_url('inventory/detail')?>"  class="btn btn-info mright5 test pull-right H_action_button">
                   <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                   <?php echo _l('create_add_new'); ?></a>
                    <?php } ?>
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
                                          <?=_l('ch_confirm_22')?> (<span class="status0">0</span>)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="H_filter" data-id="2">
                                          <?=_l('dont_approve')?> (<span class="status1">0</span>)
                                        </a>
                                    </li>
                                  </ul>
                              </div>
                          </div>
                        <input type="hidden" id="filterStatus" name="filterStatus" value=""/>
                          <div class="clearfix mtop20"></div>
                          <?php $table_data = array(
                              _l('#'),
                              _l('ch_code_p'),
                              _l('ch_date_p'),
                              _l('ch_catestaff_create'),
                              _l('leads_dt_status'),
                              _l('Kho hàng'),
                              _l('ch_note'),
                              _l('ch_option'),
                            );
                            render_datatable($table_data,'inventory');
                          ?>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
    <?php init_tail(); ?>
    <div id="view_inventory_data"></div>
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
        var tAPI;
        $(function(){

            var CustomersServerParams = {
              'filterStatus' : '[name="filterStatus"]',
            };
            tAPI = initDataTableCustom('.table-inventory', admin_url+'inventory/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'desc'))); ?>, fixedColumns = {leftColumns: 3, rightColumns: 0});
            // var tAPI = initDataTable('.table-inventory', admin_url+'inventory/table', [0], [0], CustomersServerParams,[0, 'desc']);
            $.each(CustomersServerParams, function(filterIndex, filterItem){
              $('' + filterItem).on('change', function(){
                tAPI.draw('page');
              });
            });
            $('.table-inventory').on('draw.dt', function() {
                  get_total_limit();
                });
        });
        function var_status(status,id) {
            {
                dataString={id:id,status:status,[csrfData['token_name']] : csrfData['hash']};
                jQuery.ajax({   
                    type: "post",
                    url:"<?=admin_url()?>inventory/update_status",
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
        function view_inventory(id) {
            $('#view_inventory_data').html('');
            $.get(admin_url + 'inventory/inventory_data/'+id).done(function(response) {
            $('#view_inventory_data').html(response);
            $('#view_inventory').modal({show:true,backdrop:'static'});
            init_selectpicker();
            init_datepicker();
            changeRowNew_ch('tblinventory',id);
            }).fail(function(error) {
            var response = JSON.parse(error.responseText);
            alert_float('danger', response.message);
            }); 
        }
        $('body').on('hidden.bs.modal', '#view_inventory', function() {
            $('#view_inventory_data').html('');
        });
        function confirm_warehous(id,warehouseman_id) {
        {
            dataString={id:id,warehouseman_id:warehouseman_id,[csrfData['token_name']] : csrfData['hash']};
            jQuery.ajax({   
                type: "post",
                url:"<?=admin_url()?>inventory/confirm_warehous",
                data: dataString,
                cache: false,
                success: function (response) {
                    response = JSON.parse(response);
                    tAPI.draw('page');
                    alert_float(response.alert_type, response.message);
                }
            });
            return false;
          }
        }
        function get_total_limit() {
          dataString = {[csrfData['token_name']] : csrfData['hash']};
            jQuery.ajax({
                type: "post",
                url: "<?=admin_url()?>inventory/count_all/",
                data: dataString,
                cache: false,
                success: function (data) {
                  data = JSON.parse(data);
                  $('.all').html(data.all);
                  $('.status0').html(data.status0);
                  $('.status1').html(data.status1);        
                  }
            });
        }
    </script>
