<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <?php init_head(); ?>
    <style type="text/css">

    .table-transfer img{
        height: 20px;
        width: 20px;
    }
    .table-transfer thead tr th{
       text-align: center;
    }
    .table-transfer tr td:nth-child(2)
    {
                min-width: 95px;
                white-space: unset;
                text-align: center;
    }
    .table-transfer tr td:nth-child(3)
    {
                min-width: 105px;
                white-space: unset;
                text-align: center;

    }
    .table-transfer tr td:nth-child(4)
    {
                min-width: 125px;
                white-space: unset;
                text-align: center;
    }
    .table-transfer tr td:nth-child(5)
    {
                min-width: 125px;
                white-space: unset;
                text-align: center;
                
    }
    .table-transfer tr td:nth-child(6)
    {
                min-width: 120px;
                white-space: unset;
    }
    .table-transfer tr td:nth-child(7)
    {
                min-width: 120px;
                white-space: unset;
    }
    .table-transfer tr td:nth-child(8)
    {
                min-width: 120px;
                white-space: unset;
    }
    .table-transfer tr td:nth-child(9)
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
                    <?php if (has_permission('transfer','','create')) { ?>
                    <div class="line-sp"></div>
                    <a href="<?=admin_url('transfer/detail')?>"  class="btn btn-info mright5 test pull-right H_action_button">
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
                                          <?=_l('leads_all')?>(0)
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
                              _l('ch_warehouse_do'),
                              _l('ch_warehouse_to'),
                              _l('ch_warehoues_app'),
                              _l('ch_note'),
                              _l('ch_option'),
                            );
                            render_datatable($table_data,'transfer');
                          ?>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
    <?php init_tail(); ?>
    <div id="view_transfer_data"></div>
    <script>
        $('.H_filter').click(function(e) {
          var target = $(e.currentTarget);
          var value = target.attr('data-id');
          target.parent().parent().find('li').removeClass('active');
          target.parent().addClass('active');
          $('input[name="filterStatus"]').val(value);
          $('input[name="filterStatus"]').change();
        });
        $(function(){

            var CustomersServerParams = {
              'filterStatus' : '[name="filterStatus"]',
            };
            var tAPI = initDataTable('.table-transfer', admin_url+'transfer/table', [0], [0], CustomersServerParams,[0, 'desc']);
            $.each(CustomersServerParams, function(filterIndex, filterItem){
              $('' + filterItem).on('change', function(){
                tAPI.ajax.reload();
              });
            });
        });
        function var_status(status,id) {
            {
                dataString={id:id,status:status,[csrfData['token_name']] : csrfData['hash']};
                jQuery.ajax({   
                    type: "post",
                    url:"<?=admin_url()?>transfer/update_status",
                    data: dataString,
                    cache: false,
                    success: function (response) {
                        response = JSON.parse(response);
                        if (response.success == true) {
                            $('.table-transfer').DataTable().ajax.reload();
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
                    $('.table-transfer').DataTable().ajax.reload();
                }, 'json');
            }
            return false;
        });
        function view_transfer(id) {
            $('#view_transfer_data').html('');
            $.get(admin_url + 'transfer/transfer_data/'+id).done(function(response) {
            $('#view_transfer_data').html(response);
            $('#view_transfer').modal({show:true,backdrop:'static'});
            init_selectpicker();
            init_datepicker();
            }).fail(function(error) {
            var response = JSON.parse(error.responseText);
            alert_float('danger', response.message);
            }); 
        }
        $('body').on('hidden.bs.modal', '#view_transfer', function() {
            $('#view_transfer_data').html('');
        });
        function confirm_warehous(id,warehouseman_id) {
        {
            dataString={id:id,warehouseman_id:warehouseman_id,[csrfData['token_name']] : csrfData['hash']};
            jQuery.ajax({   
                type: "post",
                url:"<?=admin_url()?>transfer/confirm_warehous",
                data: dataString,
                cache: false,
                success: function (response) {
                    response = JSON.parse(response);
                    $('.table-transfer').DataTable().ajax.reload();
                    alert_float(response.alert_type, response.message);
                }
            });
            return false;
          }
        }
    </script>
