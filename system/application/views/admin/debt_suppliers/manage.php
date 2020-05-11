        <?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
        <?php init_head(); ?>
        <style type="text/css">
            .table-debt_suppliers thead tr th{
               text-align: center;
            }
            .table-debt_suppliers tr td:nth-child(2){
                min-width: 200px;
                white-space: unset;
            }
            .table-debt_suppliers tr td:nth-child(3){
                min-width: 100px;
                white-space: unset;
                text-align: right;
            }
            .table-debt_suppliers tr td:nth-child(4){
                min-width: 100px;
                white-space: unset;
                text-align: right;
            }
            .table-debt_suppliers tr td:nth-child(5){
                min-width: 100px;
                white-space: unset;
                text-align: right;
            }
            .table-debt_suppliers tr td:nth-child(6){
                min-width: 100px;
                white-space: unset;
                text-align: right;
            }
            .table-debt_suppliers tr td:nth-child(7){
                min-width: 100px;
                white-space: unset;
                text-align: right;
            }
            .table-debt_suppliers tr td:nth-child(8){
                min-width: 100px;
                white-space: unset;
                text-align: right;
            }
            .table-debt_suppliers tr td:nth-child(9){
                min-width: 100px;
                white-space: unset;
                text-align: right;
                background: #f1eab5;
                font-weight: bold;
            }
            .popover{
                max-width:2000px;
                height:140px;    
            }
        </style>
        <div id="wrapper">
           <div class="panel_s mbot10 H_scroll" id="H_scroll">
              <div class="panel-body _buttons">
                 <div class="_buttons">
                    <span class="bold uppercase fsize18 H_title"><?=$title?></span>
                    <a class="search_person btn btn-info pull-right mleft5 H_action_button option_barcode">
                      <span style="font-size: 16px;margin-bottom: 3px;" class="lnr lnr-funnel"></span>   
                      <?php echo _l('ch_seach_statistical'); ?>
                    </a>
                    <div class="clearfix"></div>
                 </div>
              </div>
           </div>
           <input type="text" name="suppliers_id" class="hide" id="suppliers_id" value=""><!-- 
           <input type="text" name="date_start" id="date_start" value="">
           <input type="text" name="date_end" id="date_end" value=""> -->
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
                                  <?=_l('leads_all')?>(<span class="all_debt">0</span>)
                                </a>
                            </li>
                            <li>
                                <a class="H_filter" data-id="1">
                                  <?=_l('Vượt mức công nợ')?>(<span class="all_debt_limit">0</span>)
                                </a>
                            </li>
                          </ul>
                      </div>
                  </div>
                  <input type="hidden" id="filterStatus" name="filterStatus" value=""/>
                        <div class="clearfix"></div>
                          <?php $table_data = array(
                              _l('#'),
                              _l('supplier'),
                              _l('ch_debt_total'),
                              _l('ch_total_expenses'),
                              _l('ch_other_expenses'),
                              _l('ch_debt_30N'),
                              _l('ch_debt_30N60N'),
                              _l('ch_debt_60N'),
                              _l('ch_total_left'),
                            );
                            render_datatable($table_data,'debt_suppliers');
                          ?>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
        <?php init_tail(); ?>
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
          var tAPI
        $(function(){
            var CustomersServerParams = {
              'filterStatus' : '[name="filterStatus"]',
              'suppliers_id' : '[name="suppliers_id"]',
            };
             tAPI = initDataTableCustom('.table-debt_suppliers', admin_url+'debt_suppliers/table', [0,1,2,3,4,5,6,7,8], [0,1,2,3,4,5,6,7,8], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1,'desc'))); ?>, fixedColumns = {leftColumns: 0, rightColumns: 0});
            // var tAPI = initDataTable('.table-debt_suppliers', admin_url+'debt_suppliers/table', [0,1,2,3,4,5,6,7,8], [0,1,2,3,4,5,6,7,8], CustomersServerParams,[0, 'desc']);
            $.each(CustomersServerParams, function(filterIndex, filterItem){
              $('' + filterItem).on('change', function(){
                tAPI.draw('page');
              });
            });

            $('.table-debt_suppliers').on('draw.dt', function() {
              get_total_debt_limit();
            });
        });
        var suppliers_id = $('#suppliers_id').val();
        if(suppliers_id == '')
        {
            $.ajax({
                url : admin_url + 'debt_suppliers/get_total_debt/',
                dataType : 'json',
            })
            .done(function(data){
             $('.text-muted.debt').text(data.debt);
             $('.text-muted.payment').text(data.payment);
             $('.text-muted.left').text(data.left);
            });
        }
        else
        {
        $('.table-debt_suppliers').on('draw.dt', function() {
           var invoiceReportsTable = $(this).DataTable();
           var sums = invoiceReportsTable.ajax.json().sums;
           $('.text-muted.debt').text(sums.debt);
           $('.text-muted.payment').text(sums.payment);
           $('.text-muted.left').text(sums.left);
         });
        }
        var inner_popover_template = '<div class="popover" style="width:1000px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>';
          $(document).on('click','.search_person',function(e){
            $('.add_contact_person_invoice').popover('hide');
            var dropdown_menu='\
    <div class="col-md-2 col-xs-2 border-right">\
        <h4 class="bold text-muted debt">\
            0\
        </h4>\
        <span style="color:red" class="text-danger">\
            <?=_l('ch_total_arises')?>\
        </span>\
    </div>\
    <div class="col-md-2 col-xs-2 border-right">\
        <h4 class="bold text-muted payment">\
            0\
        </h4>\
        <span style="color:red" class="text-danger">\
            <?=_l('ch_total_payment')?>\
        </span>\
    </div>\
    <div class="col-md-2 col-xs-2 border-right">\
        <h4 class="bold text-muted left">\
            0\
        </h4>\
        <span style="color:red" class="text-danger">\
            <?=_l('ch_total_left')?>\
        </span>\
    </div>\
            <div class="col-md-6">\
            <?php
                echo render_select('id_suppliers[]',$suppliers,array('id','company'),'ch_chose_suppliers','',array('data-actions-box'=>1,'multiple'=>true));
            ?></div><br>';
            $(this).popover({
              html: true,
              container: 'body',
              placement: "bottom",
              trigger: 'click focus',
              // trigger: 'focus',
              title:'<?=_l('Thống kê và tìm kiếm')?><button type="button" class="close close_pay">&times;</button>',
              content: function() {
                return dropdown_menu;
              },
              template: inner_popover_template
            });

            init_selectpicker();
                var res = [];
                var suppliers_id = $('#suppliers_id').val();
                res = suppliers_id.split(",");
                if(res[0] == '')
                {
                  res.splice(0,1);
                }
                // $('[name="id_suppliers[]"]').selectpicker('val',[suppliers_id]); 
                $('[name="id_suppliers[]"]').val(res).trigger('change');

                  if(suppliers_id == '')
                  {
                      $.ajax({
                          url : admin_url + 'debt_suppliers/get_total_debt/',
                          dataType : 'json',
                      })
                      .done(function(data){
                       $('.text-muted.debt').text(data.debt);
                       $('.text-muted.payment').text(data.payment);
                       $('.text-muted.left').text(data.left);
                      });
                  }
                });
        $(document).on('click','.close',function(e){
          $('.search_person').popover('hide');
        });
        $(document).on('change', '[name="id_suppliers[]"]', function() {
         
        $('#suppliers_id').val($('[name="id_suppliers[]"]').val());
        $('#suppliers_id').change();
            var suppliers_id = $('#suppliers_id').val();
                if(suppliers_id == '')
                {
                    $.ajax({
                        url : admin_url + 'debt_suppliers/get_total_debt/',
                        dataType : 'json',
                    })
                    .done(function(data){
                     $('.text-muted.debt').text(data.debt);
                     $('.text-muted.payment').text(data.payment);
                     $('.text-muted.left').text(data.left);
                    });
                }
                else
                {
                $('.table-debt_suppliers').on('draw.dt', function() {
                   var invoiceReportsTable = $(this).DataTable();
                   var sums = invoiceReportsTable.ajax.json().sums;
                   $('.text-muted.debt').text(sums.debt);
                   $('.text-muted.payment').text(sums.payment);
                   $('.text-muted.left').text(sums.left);
                 });
                }
        });
// ngày

// var ch_daterangepicker = () => {
//   $('input[name="daterange"]').daterangepicker({
//     opens: 'left',
//     autoUpdateInput: false, 
//     isInvalidDate: false,
//     "locale": {
//             "format": "DD/MM/YYYY",
//             "separator": " - ",
//             "applyLabel": lang_daterangepicker.applyLabel,
//             "cancelLabel": lang_daterangepicker.cancelLabel,
//             "fromLabel": lang_daterangepicker.fromLabel,
//             "toLabel": lang_daterangepicker.toLabel,
//             "customRangeLabel": lang_daterangepicker.customRangeLabel,
//             "daysOfWeek": lang_daterangepicker.daysOfWeek,
//             "monthNames": lang_daterangepicker.monthNames
//         },
//   }, function(start, end, label) {
//   });
//   $('input[name="daterange"]').val('').datepicker("refresh");
//   $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
//       $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
//       $('#date_start').val(picker.startDate.format('YYYY-MM-DD'));
//       $('#date_end').val(picker.endDate.format('YYYY-MM-DD'));
//       $('#date_end').change();
//       $('#date_start').change();
//   });
//   $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
//       $(this).val('');
//       $('#date_start').val('');
//       $('#date_end').val('');
//       $('#date_end').change();
//       $('#date_start').change();
//   });
// };
//         var inner_popover_template = '<div class="popover" style="width:400px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>';
//           $(document).on('click','.search_person',function(e){
//             $('#suppliers_id').val('');
//             $('#suppliers_id').change();
//             $('.add_contact_person_invoice').popover('hide');
//             var dropdown_menu='\
//             <?php
//                 echo render_select('id_suppliers',$suppliers,array('id','company'),'ch_chose_suppliers');
//             ?>
//               <label for="id_suppliers" class="control-label"><?=_l('ch_chose_date')?></label>\
//               <div class="input-group date">\
//                 <input readonly type="text" name="daterange" id="daterange" name="daterange" class="form-control daterange" value="" autocomplete="off">\
//                 <div class="input-group-addon">\
//                   <i class="fa fa-calendar calendar-icon"></i>\
//                 </div>\
//               </div>\
//             </div><br>';
//             $('select[name="id_suppliers"]').selectpicker('refresh');
//             $(this).popover({
//               html: true,
//               container: 'body',
//               placement: "bottom",
//               trigger: 'click focus',
//               // trigger: 'focus',
//               title:'<?=_l('search')?><button type="button" class="close close_pay">&times;</button>',
//               content: function() {
//                 return dropdown_menu;
//               },
//               template: inner_popover_template
//             });
//             $('#suppliers_id').selectpicker('refresh');
//             ch_daterangepicker();
//           });

        // end
        function get_total_debt_limit() {
              var suppliers_id = $('#suppliers_id').val();
              dataString = {suppliers_id:suppliers_id,[csrfData['token_name']] : csrfData['hash']};
                      jQuery.ajax({
                          type: "post",
                          url: "<?=admin_url()?>debt_suppliers/count_debt/",
                          data: dataString,
                          cache: false,
                          success: function (data) {
                            data = JSON.parse(data);
                            $('.all_debt').html(data.all);
                            $('.all_debt_limit').html(data.count_limit);
                          }
                      });
        }
        </script>
