<script type="text/javascript">

 var i=0;

 var total_debt_supplier=0;
 var total_debt_client=0;
 var total_debt_object=0;


 var scoll_client=0;
 var tables_pagination_limit=[-1,"Tất cả"];


 var report_from = $('input[name="report-from"]');
 var report_to = $('input[name="report-to"]');
 var report_customers = $('#customers-report');
 var report_customers_groups = $('#customers-group');
 var report_from_choose = $('#report-time');
 var date_range = $('#date-range');
 var fnServerParams = {
   "report_months": '[name="months-report"]',
   "filter_debits": '[name="filter_debits"]',
   "report_from": '[name="report-from"]',
   "id_customer": '[name="id_customer"]',
   "report_to": '[name="report-to"]',
   "proposal_status": '[name="proposal_status"]',
   "years_report": '[name="years_report"]',
   "start_detail_supplier": '[name="start_detail_supplier"]',
   "end_detail_supplier": '[name="end_detail_supplier"]',
   "start_detail_porters": '[name="start_detail_porters"]',
   "end_detail_porters": '[name="end_detail_porters"]',
   "start_detail_rack": '[name="start_detail_rack"]',
   "end_detail_rack": '[name="end_detail_rack"]',
   "start_detail_personal": '[name="start_detail_personal"]',
   "end_detail_personal": '[name="end_detail_personal"]',
   "id_suppliers_detail": '[name="id_suppliers_detail"]',
   "id_porter_detail": '[name="id_porter_detail"]',
   "id_rack_detail": '[name="id_rack_detail"]',
   "id_object_detail": '[name="id_object_detail"]',
   "id_client_detail": '[name="id_client_detail"]',
   "staff_id_detail": '[name="staff_id_detail"]',
   "start_detail_borrowing": '[name="start_detail_borrowing"]',
   "end_detail_borrowing": '[name="end_detail_borrowing"]',
   "id_borrowing_detail": '[name="id_borrowing_detail"]',
   "id_borrowing_product": '[name="id_borrowing_product"]',
   'id_supplier_borrrowing': '[name="id_supplier_borrrowing"]',
   'id_porter': '[name="id_porter"]',
   'id_racks': '[name="id_racks"]',
   'id_other_object': '[name="id_other_object"]',
   'date_start_supplier': '[name="date_start_supplier"]',
   'date_end_supplier': '[name="date_end_supplier"]',
   'date_start_customer': '[name="date_start_customer"]',
   'date_end_customer': '[name="date_end_customer"]',
   'date_start_porters': '[name="date_start_porters"]',
   'date_end_porters': '[name="date_end_porters"]',
   'date_start_racks': '[name="date_start_racks"]',
   'date_end_racks': '[name="date_end_racks"]',
   'date_start_personal': '[name="date_start_personal"]',
   'date_end_personal': '[name="date_end_personal"]',
   'date_start_borrrowing': '[name="date_start_borrrowing"]',
   'date_end_borrrowing': '[name="date_end_borrrowing"]',
   'start_detail_client': '[name="start_detail_client"]',
   'end_detail_client': '[name="end_detail_client"]',
   'date_start_client': '[name="date_start_client"]',
   'date_end_client': '[name="date_end_client"]',
   'id_rows_supplier': '[name="id_rows_supplier"]',
   'id_rows_porters': '[name="id_rows_porters"]',
   'id_rows_customer': '[name="id_rows_customer"]',
   'id_rows_racks': '[name="id_rows_racks"]',
   'id_rows_personal': '[name="id_rows_personal"]',
   'id_rows_supplier_borrrowing': '[name="id_rows_supplier_borrrowing"]',
   'id_rows_client': '[name="id_rows_client"]',
   'id_supplier': '[name="id_supplier"]',
   'id_client': '[name="id_client"]',
   'staff_sale_client': '[name="staff_sale_client"]',
   'province_client': '[name="province_client"]',
   'start_cooperative_day': '[name="start_cooperative_day"]',
   'end_cooperative_day': '[name="end_cooperative_day"]',
   'check_active_client': '[name="check_active_client"]'

 };

 //change_time
 function formatNumber(nStr, decSeperate=".", groupSeperate=",") {
     nStr += '';
     x = nStr.split(decSeperate);
     x1 = x[0];
     x2 = x.length > 1 ? '.' + x[1] : '';
     var rgx = /(\d+)(\d{3})/;
     while (rgx.test(x1)) {
         x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
     }
     return x1 + x2;
 }

  //boc vac
 $('#date_start_porters,#start_detail_porters').on('change',function(e)
 {
    var date=$(this).val();
    $('#date_start_porters').val(date);
    $('#start_detail_porters').val(date);
 })
 $('#date_end_porters,#end_detail_porters').on('change',function(e)
 {
     var date=$(this).val();
     $('#date_end_porters').val(date);
     $('#end_detail_porters').val(date);
 })
  //end boc vac


  //start nhà cung cấp
  $('#date_start_supplier,#start_detail_supplier').on('change',function(e)
 {
    var date=$(this).val();
    $('#date_start_supplier').val(date);
    $('#start_detail_supplier').val(date);
 })
 $('#date_end_supplier,#end_detail_supplier').on('change',function(e)
 {
     var date=$(this).val();
     $('#date_end_supplier').val(date);
     $('#end_detail_supplier').val(date);
 })
  //end nhà cung cấp

  //start lái xe
  $('#start_detail_rack,#date_start_racks').on('change',function(e)
  {
      var date=$(this).val();
      $('#date_start_supplier').val(date);
      $('#start_detail_supplier').val(date);
  })
  $('#end_detail_rack,#date_end_racks').on('change',function(e)
  {
      var date=$(this).val();
      $('#date_end_supplier').val(date);
      $('#end_detail_supplier').val(date);
  })
  //end lái xe

  //start cong nợ cá nhân
  $('#date_start_personal,#start_detail_personal').on('change',function(e)
  {
      // alert('123')
      var date=$(this).val();
      $('#date_start_personal').val(date);
      $('#start_detail_personal').val(date);
  })
  $('#date_end_personal,#end_detail_personal').on('change',function(e)
  {
      var date=$(this).val();
      $('#date_end_personal').val(date);
      $('#end_detail_personal').val(date);
  })
  //end công nợ cá nhân
  //
  // start cong nợ vay mượn
  $('#date_start_borrrowing,#start_detail_borrowing').on('change',function(e)
  {
      var date=$(this).val();
      $('#date_start_borrrowing').val(date);
      $('#start_detail_borrowing').val(date);
  })
  $('#date_end_borrrowing,#end_detail_borrowing').on('change',function(e)
  {
      var date=$(this).val();
      $('#date_end_borrrowing').val(date);
      $('#end_detail_borrowing').val(date);
  })
  //end công nợ vay mượn

  //end change_time


  // khách hàng

  $('#start_detail_client,#date_start_client').on('change',function(e)
  {
      var date=$(this).val();
      $('#date_start_client').val(date);
      $('#start_detail_client').val(date);
  })
  $('#end_detail_client,#date_end_client').on('change',function(e)
  {
      var date=$(this).val();
      $('#date_end_client').val(date);
      $('#end_detail_client').val(date);
  })

  //end khách hàng

















  $.each(fnServerParams, function(filterIndex, filterItem){
      $(filterItem).on('change', function(){
          gen_reports();
          if($('#detail_suppliers').hasClass('in'))
          {
              $('.table-detail_suppliers_debts').DataTable().ajax.reload();
          }
          if($('#detail_porters').hasClass('in'))
          {
              $('.table-debts_detail_porters').DataTable().ajax.reload();
          }
          if($('#detail_rack').hasClass('in'))
          {
              $('.table-debts_detail_rack').DataTable().ajax.reload();
          }
          if($('#detail_personal').hasClass('in'))
          {
              $('.table-debts_detail_personal').DataTable().ajax.reload();
          }
          if($('#detail_borrowing').hasClass('in'))
          {
              $('.table-debts_detail_borrowing').DataTable().ajax.reload();
          }
          if($('#detail_clients').hasClass('in'))
          {
              $('.table-debts_detail_client').DataTable().ajax.reload();
          }

          if($('#detail_customer').hasClass('in'))
          {
              $('.table-debts_customer_detail').DataTable().ajax.reload();
          }
      });
  });

  report_from.on('change', function() {
     var val = $(this).val();
     var report_to_val = report_to.val();
     if (val != '') {
       report_to.attr('disabled', false);
       if (report_to_val != '') {
         gen_reports();
       }
     } else {
       report_to.attr('disabled', true);
     }
   });

  report_to.on('change', function() {
     var val = $(this).val();
     if (val != '') {
       gen_reports();
     }
   });

  $('select[name="months-report"]').on('change', function() {
     var val = $(this).val();
     report_to.attr('disabled', true);
     report_to.val('');
     report_from.val('');
     if (val == 'custom') {
       date_range.addClass('fadeIn').removeClass('hide');
       return;
     } else {
       if (!date_range.hasClass('hide')) {
         date_range.removeClass('fadeIn').addClass('hide');
       }
     }
     gen_reports();
   });

  function init_report(e, type) {

      $('#report_tiltle').text($(e).text());
      var report_wrapper = $('#report');
       if (report_wrapper.hasClass('hide')) {
         report_wrapper.removeClass('hide');
       }
      $('#report-time').removeClass('hide');
      $('#genernal-receivables-suppliers-debts-report').addClass('hide');
      $('#debts_rack').addClass('hide');
      $('#debts_porters').addClass('hide');
      $('#debts_personal').addClass('hide');
      $('#debts_borrowing').addClass('hide');
      $('#debts_clients').addClass('hide');
      $('#debts_all').addClass('hide');
      $('#debts_customer').addClass('hide');
      $('#debts_control').addClass('hide');
      $('#report_cod_sum').addClass('hide');
      $('select[name="months-report"]').selectpicker('val', '');
           // Clear custom date picker
       report_to.val('');
       report_from.val('');
       $('#currency').removeClass('hide');

      if (type == 'genernal-receivables-suppliers-debts-report') {
         $('#genernal-receivables-suppliers-debts-report').removeClass('hide');
       }

       if (type == 'debts_customer') {
         $('#debts_customer').removeClass('hide');
       }

       if (type == 'debts_porters') {
         $('#debts_porters').removeClass('hide');
       }
       if (type == 'debts_rack') {
         $('#debts_rack').removeClass('hide');
       }
       if (type == 'debts_personal') {
         $('#debts_personal').removeClass('hide');
       }
       if (type == 'debts_borrowing') {
         $('#debts_borrowing').removeClass('hide');
       }
       if (type == 'debts_clients') {
         $('#debts_clients').removeClass('hide');
       }
       if (type == 'debts_all') {
         $('#debts_all').removeClass('hide');
       }

       if (type == 'debts_control') {
         $('#debts_control').removeClass('hide');
       }
       if (type == 'report_cod_sum') {
         $('#report_cod_sum').removeClass('hide');
       }
      gen_reports();
  }
  // Main generate report function
   function gen_reports() {
     if (!$('#genernal-receivables-suppliers-debts-report').hasClass('hide')) {

     }
     if (!$('#debts_porters').hasClass('hide')) {

     }
     if (!$('#debts_rack').hasClass('hide')) {

     }
     if (!$('#debts_personal').hasClass('hide')) {

     }
     if (!$('#debts_borrowing').hasClass('hide')) {

     }
     if (!$('#debts_clients').hasClass('hide')) {
         // debts_clients_report();
     }
     if (!$('#debts_all').hasClass('hide')) {
         // debts_all_report();
     }
     if (!$('#debts_control').hasClass('hide')) {
         debts_control_report();
         $('#report-time').addClass('hide');
     }
     if (!$('#report_cod_sum').hasClass('hide')) {
         debts_report_cod_sum();
         $('#report-time').addClass('hide');
     }
  }

 function load_table_supper()
 {
     genernal_receivables_suppliers_debts_report();
 }
 function load_table_personal()
 {
     debts_personal_report();
 }
 function load_table_porters()
 {
     debts_porters_report();
 }

function load_table_customer() {
  if ($.fn.DataTable.isDataTable('.table-debts_customer')) {
      $('.table-debts_customer').DataTable().ajax.reload();
  }
  $('#start_detail_customer').val($('#date_start_customer').val());
  $('#end_detail_customer').val($('#date_end_customer').val());
  var debts_customer = initDataTableDungbt('.table-debts_customer', admin_url + 'reports/debts_porters_customer', false, false, fnServerParams, [0, 'ASC']);
  debts_customer.column(0).visible(false);
}

 function load_table_borrrowing()
 {
     debts_borrowing_report();
 }
 function load_table_racks()
 {
     debts_rack_report();
 }

  function load_table_client()
  {
      debts_clients_report();
  }

  function genernal_receivables_suppliers_debts_report() {
    if ($.fn.DataTable.isDataTable('.table-genernal-receivables-suppliers-debts-report')) {
     $('.table-genernal-receivables-suppliers-debts-report').DataTable().destroy();
    }
     initDataTableFixedHeader('.table-genernal-receivables-suppliers-debts-report', admin_url + 'reports/genernal_receivables_suppliers_debts_report', false, false, fnServerParams, [0, 'DESC']);
   }

  function debts_porters_report() {
      if ($.fn.DataTable.isDataTable('.table-debts_porters')) {
          $('.table-debts_porters').DataTable().ajax.reload();
      }
      initDataTable('.table-debts_porters', admin_url + 'reports/debts_porters_report', false, false, fnServerParams, [0, 'ASC']);
  }





  function debts_rack_report() {
      if ($.fn.DataTable.isDataTable('.table-debts_rack')) {
          $('.table-debts_rack').DataTable().ajax.reload();
      }
      initDataTable('.table-debts_rack', admin_url + 'reports/debts_rack_report', false, false, fnServerParams, [0, 'ASC']);
  }
  function debts_personal_report() {
      if ($.fn.DataTable.isDataTable('.table-debts_personal')) {
          $('.table-debts_personal').DataTable().ajax.reload();
      }
      initDataTable('.table-debts_personal', admin_url + 'reports/debts_personal_report', false, false, fnServerParams, [0, 'ASC']);
  }
  function debts_borrowing_report() {
      if ($.fn.DataTable.isDataTable('.table-debts_borrowing')) {
          $('.table-debts_borrowing').DataTable().ajax.reload();
      }
      initDataTable('.table-debts_borrowing', admin_url + 'reports/debts_borrowing_report', false, [1,2,3,4,5,6], fnServerParams, [0, 'ASC']);
  }

  function debts_clients_report() {
      if ($.fn.DataTable.isDataTable('.table-debts_client')) {
          $('.table-debts_client').DataTable().ajax.reload();
      }
      initDataTable('.table-debts_client', admin_url + 'reports/debts_client_report', false, [1,2,3,4,5,6], fnServerParams, [0, 'ASC']);
  }


 var fnServerParams_debt_client={
     "client_all": '[name="client_all"]',
     "date_start_client_all": '[name="date_start_client_all"]',
     "date_end_client_all": '[name="date_end_client_all"]',
     "type_client": '[name="type_client"]',
     "check_active": '[name="check_active"]:checked'
 };
 var fnServerParams_debt_supplier={
     "supplier_all": '[name="supplier_all"]',
     "date_start_supplier_all": '[name="date_start_supplier_all"]',
     "date_end_supplier_all": '[name="date_end_supplier_all"]',
     "type_supplier": '[name="type_supplier"]'
 };
 var fnServerParams_debt_object={
     "personal_all": '[name="personal_all"]',
     "date_start_personal_all": '[name="date_start_personal_all"]',
     "date_end_personal_all": '[name="date_end_personal_all"]',
     "type_personal": '[name="type_personal"]'
 };
  function debts_all_report(type) {
      if(type=='client')
      {
          if ($.fn.DataTable.isDataTable('.table-debts_all_client')) {
              $('.table-debts_all_client').DataTable().ajax.reload();
          }
          initDataTable('.table-debts_all_client', admin_url + 'reports/debts_all_client', false, [1,2,3,4], fnServerParams_debt_client, [0, 'ASC']);

      }
      if(type=='supplier') {
          if ($.fn.DataTable.isDataTable('.table-debts_all_supplier')) {
              $('.table-debts_all_supplier').DataTable().ajax.reload();
          }
          initDataTable('.table-debts_all_supplier', admin_url + 'reports/debts_all_supplier', false, [1,2,3,4,5,6], fnServerParams_debt_supplier, [0, 'ASC']);

      }
      if(type=='personal') {
          if ($.fn.DataTable.isDataTable('.table-debts_all_personal')) {
              $('.table-debts_all_personal').DataTable().ajax.reload();
          }
          initDataTable('.table-debts_all_personal', admin_url + 'reports/debts_all_personal', false, [1,2,3,4,5,6], fnServerParams_debt_object, [0, 'ASC']);
      }
  }




var fnServerParams_debt_control={
     "date_start_control": '[name="date_start_control"]',
     "date_end_control": '[name="date_end_control"]'
};
function debts_control_report() {
  if ($.fn.DataTable.isDataTable('.table-report_control')) {
      $('.table-report_control').DataTable().ajax.reload();
  }
  initDataTable('.table-report_control', admin_url + 'reports/debts_report_control', [0,1,2,3,4,5], [0,1,2,3,4,5], fnServerParams_debt_control);
}



var fnServerParams_debt_code_sum={
 "date_start_code_sum": '[name="date_start_code_sum"]',
 "date_end_code_sum": '[name="date_end_code_sum"]'
};
function debts_report_cod_sum() {
  if ($.fn.DataTable.isDataTable('.table-report_cod_sum')) {
      $('.table-report_cod_sum').DataTable().ajax.reload();
  }
  initDataTable('.table-report_cod_sum', admin_url + 'report_cod_sum/table', [0,1,2,3,4,5], [0,1,2,3,4,5], fnServerParams_debt_code_sum);
}

 $('.table-debts_client').on('draw.dt', function() {
     var invoiceReportsTable = $(this).DataTable();
     var array_total = invoiceReportsTable.ajax.json().array_total;
     $.each(array_total,function(i,v){
         $('.'+i).html('<b class="text-danger">'+v+'</b>');
     })
 });
 $('.table-debts_all_supplier').on('draw.dt', function() {
     var invoiceReportsTable = $(this).DataTable();
     var array_total = invoiceReportsTable.ajax.json().array_total;
     $.each(array_total,function(i,v){
         $('.'+i).html('<b class="text-danger">'+v+'</b>');
         if(i=='supplier_total_last'&&v!=0)
         {
             total_debt_supplier=parseFloat(v.replace(/\,/g, ''));
             $('#report_tiltle').html(' Tổng hợp công nợ phải đòi: <b class="text-danger">'+formatNumber(total_debt_client-total_debt_object-total_debt_supplier)+'</b>');
         }
     })
 });
 $('.table-debts_all_personal').on('draw.dt', function() {
     var invoiceReportsTable = $(this).DataTable();
     var array_total = invoiceReportsTable.ajax.json().array_total;
     $.each(array_total,function(i,v){
         $('.'+i).html('<b class="text-danger">'+v+'</b>');
         if(i=='object_total_last'&&v!=0)
         {
             total_debt_object=parseFloat(v.replace(/\,/g, ''));
             $('#report_tiltle').html(' Tổng hợp công nợ phải đòi: <b class="text-danger">'+formatNumber(total_debt_client-total_debt_object-total_debt_supplier)+'</b>');
         }
     })
 });

 $('.table-debts_all_client').on('draw.dt', function() {
     var invoiceReportsTable = $(this).DataTable();
     var array_total = invoiceReportsTable.ajax.json().array_total;
     $.each(array_total,function(i,v){
         $('.table-debts_all_client .'+i).html('<b class="text-danger">'+v+'</b>');
         if(i=='total_remaining'&&v!=0)
         {
             total_debt_client=parseFloat(v.replace(/\,/g, ''));
             $('#report_tiltle').html(' Tổng hợp công nợ phải đòi: <b class="text-danger">'+formatNumber(total_debt_client-total_debt_object-total_debt_supplier)+'</b>');
         }
     })
 });



  $('.table-debts_personal').on('draw.dt', function() {
      var invoiceReportsTable = $(this).DataTable();
      var total_type = invoiceReportsTable.ajax.json().total_type;
      $('.total_type_1').html(total_type.total_type_1);
      $('.total_type_0').html(total_type.total_type_0);
      $('.total_type_2').html(total_type.total_type_2);
  })

  $('.table-genernal-receivables-suppliers-debts-report').on('draw.dt', function() {
      var invoiceReportsTable = $(this).DataTable();
      var total_type = invoiceReportsTable.ajax.json().total_type;
      $('.total_type_supplier_1').html(total_type.total_type_1);
      $('.total_type_supplier_0').html(total_type.total_type_0);
      $('.total_type_supplier_2').html(total_type.total_type_2);
  })


   function view_detail_suppliers(id_supplier) {
      $('#id_suppliers_detail').val(id_supplier);
      // initDataTable('.table-detail_suppliers_debts', admin_url + 'reports/detail_debts_suppliers', false, [1,2,3,4,5], fnServerParams, [0, 'ASC']);
      initDataTableFixedHeader('.table-detail_suppliers_debts', admin_url + 'reports/detail_debts_suppliers', false,  [1,2,3,4,5], fnServerParams, [0, 'DESC']);


       $('.table-detail_suppliers_debts').DataTable().ajax.reload();
      $('#detail_suppliers').modal('show');
  }
  $('.table-detail_suppliers_debts').on('draw.dt', function() {
      var invoiceReportsTable = $(this).DataTable();
      var supplier = invoiceReportsTable.ajax.json().suppliers;
      var total_stock_products= invoiceReportsTable.ajax.json().total_stock_products;
      var total_three_products= invoiceReportsTable.ajax.json().total_three_products;
      var total_pay= invoiceReportsTable.ajax.json().total_pay;
      if(typeof total_stock_products!='undefined')
      {
          $('.div_total_stock_products').show();
          $('.total_stock_products').text(total_stock_products);
          $('.total_three_products').text(total_three_products);
          $('.total_pay').text(total_pay);
      }
      else
      {
          $('.div_total_stock_products').hide();
      }
      $('.title_debits').html('Chi tiết công nợ  '+supplier);
  })

  function view_detail_porter(id_porter) {
      $('#id_porter_detail').val(id_porter);
      // initDataTable('.table-debts_detail_porters', admin_url + 'reports/detail_debts_porters', false, [1,2,3,4,5], fnServerParams, [0, 'ASC']);
      initDataTableFixedHeader('.table-debts_detail_porters', admin_url + 'reports/detail_debts_porters', false, [1,2,3,4,5], fnServerParams, [0, 'ASC']);
      $('.table-debts_detail_porters').DataTable().ajax.reload();
      $('#detail_porters').modal('show');
  }
  $('.table-debts_detail_porters').on('draw.dt', function() {
      var invoiceReportsTable = $(this).DataTable();
      var porters = invoiceReportsTable.ajax.json().porters;
      $('.title_debits').html('Chi tiết công nợ bốc vác '+porters);
  })
  function view_detail_rack(id_rack) {
      $('#id_rack_detail').val(id_rack);
      initDataTableFixedHeader('.table-debts_detail_rack', admin_url + 'reports/detail_debts_rack', false, [1,2,3,4,5], fnServerParams, [0, 'ASC']);
      $('.table-debts_detail_rack').DataTable().ajax.reload();
      $('#detail_rack').modal('show');
  }
  $('.table-debts_detail_rack').on('draw.dt', function() {
      var invoiceReportsTable = $(this).DataTable();
      var rack = invoiceReportsTable.ajax.json().rack;
      $('.title_debits_rack').html('Chi tiết công nợ lái xe '+rack);
  })

  function view_detail_clients(id_client) {
      $('#id_client_detail').val(id_client);
      initDataTableFixedHeader('.table-debts_detail_client', admin_url + 'reports/detail_debts_client', false, [1,2,3,4,5], fnServerParams, [0, 'ASC']);
      $('.table-debts_detail_client').DataTable().ajax.reload();
      $('#detail_clients').modal('show');
  }
  function view_detail_personal(id_object,staff_id) {
      $('#id_object_detail').val(id_object);
      $('#staff_id_detail').val(staff_id);
      initDataTable('.table-debts_detail_personal', admin_url + 'reports/detail_debts_personal', false, [1,2,3,4,5], fnServerParams, [0, 'ASC']);
      $('.table-debts_detail_personal').DataTable().ajax.reload();
      $('#detail_personal').modal('show');
  }
  $('.table-debts_detail_personal').on('draw.dt', function() {
      var invoiceReportsTable = $(this).DataTable();
      var personal = invoiceReportsTable.ajax.json().personal;
      $('.title_debits_personal').html('Chi tiết công nợ cá nhân '+personal);
  })
  function view_detail_borrowing(id_supplier,product) {
      $('#id_borrowing_detail').val(id_supplier);
      $('#id_borrowing_product').val(product);
      initDataTable('.table-debts_detail_borrowing', admin_url + 'reports/detail_debts_borrowing', false, [1,2,3,4,5], fnServerParams, [0, 'ASC']);
      $('.table-debts_detail_borrowing').DataTable().ajax.reload();
      $('#detail_borrowing').modal('show');
  }



  function view_detail_customer(filter_debits , id) {


    $('#filter_debits').val(filter_debits);
    $('#id_shop_detail').val(id);


    var serverParam = {
      'start_detail_customer': '[name="date_start_customer"]',
      'end_detail_customer': '[name="date_end_customer"]',
      'id_rows_customer': '[name="id_rows_customer"]',
      'filter_debits': '[name="filter_debits"]',
      'id_shop_detail': '[name="id_shop_detail"]'
    };
    var height = 'auto';
    if (window.innerWidth > 768) {
      height = (window.innerHeight - 60 - 62 - 30 - 76 - 31 - 100) + "px";
    }

    var data = initDataTableDungbt('.table-debts_customer_detail', admin_url + 'reports/detail_debts_customer', false, [1,2,3,4,5], serverParam, [0, 'ASC'] , height);

    data.column(0).visible(false);
    data.column(10).visible(false);
    data.column(7).visible(false);
    data.column(11).visible(false);
    data.column(12).visible(false);
    data.column(13).visible(false);
    data.column(14).visible(false);



    if ($.fn.DataTable.isDataTable('.table-debts_customer_detail')) {
        $('.table-debts_customer_detail').DataTable().ajax.reload();
    }

    $('#detail_customer').modal('show');
    $('#detail_customer .modal-title.title_debits').text('Chi Tiết Công Nợ KH: '+filter_debits);
  }


  $(document).on("click",".get_data_debits",function() {

    view_detail_customer($(this).attr('data-debits') , $(this).attr('data-id') );
    var data_debt = $(this).attr('data-debits');
    var data_id = $(this).attr('data-id');

    $('#id_hidden_shop').val(data_debt);
    $('#id_hidden_customer').val(data_id);
    // $('.detail_print').find('.exportExcelCustomer').attr('data-debits', data_debt).attr('data-id', data_id);

  });

  $('#start_detail_customer,#end_detail_customer').on('change',function(e)
  {


      var serverParam = {
        'start_detail_customer': '[name="start_detail_customer"]',
        'end_detail_customer': '[name="end_detail_customer"]',
        'id_rows_customer': '[name="id_rows_customer"]',
        'filter_debits': '[name="filter_debits"]',
        'id_shop_detail': '[name="id_shop_detail"]'
      };

      var data = initDataTable('.table-debts_customer_detail', admin_url + 'reports/detail_debts_customer', false, [1,2,3,4,5], serverParam, [0, 'ASC']);

      data.column(0).visible(false);
      data.column(9).visible(false);
      data.column(10).visible(false);
      data.column(11).visible(false);
      data.column(12).visible(false);
      data.ajax.reload();

  });

  $('.table-debts_detail_borrowing').on('draw.dt', function() {
      var invoiceReportsTable = $(this).DataTable();
      var supplier = invoiceReportsTable.ajax.json().suppliers;
      $('.title_debits_borrowing').html('Chi tiết vay mượn nguyên vật liệu của nhà cung cấp'+supplier);
  })
 $('.table-debts_rack').on('draw.dt', function() {
      var invoiceReportsTable = $(this).DataTable();
      var array_total = invoiceReportsTable.ajax.json().array_total;
      $.each(array_total,function(i,v){
          $('.'+i).html(v);
      })
  })
 $('.table-debts_porters').on('draw.dt', function() {
      var invoiceReportsTable = $(this).DataTable();
      var array_total = invoiceReportsTable.ajax.json().array_total;
      $.each(array_total,function(i,v){
          $('.'+i).html(v);
      })
  })



 $.each(fnServerParams_debt_client, function(filterIndex, filterItem){
     $(filterItem).on('change', function(){
         if($('.table-debts_all_client').hasClass('dataTable'))
         {
            $('.table-debts_all_client').DataTable().ajax.reload();
         }
     });
 });
 $.each(fnServerParams_debt_supplier, function(filterIndex, filterItem){
     $(filterItem).on('change', function(){

         if($('.table-debts_all_supplier').hasClass('dataTable')) {
             $('.table-debts_all_supplier').DataTable().ajax.reload();
         }
     });
 });
 $.each(fnServerParams_debt_object, function(filterIndex, filterItem){
     $(filterItem).on('change', function(){
         if($('.table-debts_all_personal').hasClass('dataTable')) {
             $('.table-debts_all_personal').DataTable().ajax.reload();
         }
     });
 });
</script>


<!--Bổ sung-->
<script>
    function view_update_supplier(id)
    {
        $.get(admin_url + 'reports/update_suppliers/'+id, {}).done(function(response) {
            response = JSON.parse(response);
            $('#id_supplier').val(id).selectpicker('refresh');
            $('#debt_supplier').val(response.debt);
            $('#type_supplier').val(response.type_supplier).selectpicker('refresh');

            $('.title_debt_suppliers').html('Đối tượng nhà cung cấp: '+response.company);

            $('#debt_suppliers #debit_date').val(response.debit_date);
            $('#debt_suppliers #note').val(response.note);
            $('.history_supplier_add').html('');
            i=0;
            if(response.call_logs.length==0)
            {
                add_history_supplier('','');
            }
            $.each(response.call_logs,function(j,v){
                add_history_supplier(v.date,v.note);
            })
        });
        $('#debt_suppliers').modal('show');
    }
    function view_update_clients(id,_this)
    {
        scoll_client= $(_this).position().top;
        $.get(admin_url + 'reports/update_clients/'+id, {}).done(function(response) {
            response = JSON.parse(response);
            $('#id_client_update').val(id).selectpicker('refresh');
            $('#type_debt_client').val(response.type_debt_client).selectpicker('refresh');
            $('#debt_client_modal #debit_date').val(response.debit_date);
            $('#debt_client_modal #note').val(response.note);

            $('.title_debt_rack').html('Khách hàng: '+response.company);
            $('.history_add').html('');
            i=0;
            if(response.call_logs.length==0)
            {
                add_history('','');
            }
            $.each(response.call_logs,function(j,v){
                add_history(v.date,v.note);
            })
        });
        $('#debt_client_modal').modal('show');
    }

    function view_update_porters(id)
    {
        $.get(admin_url + 'reports/update_porters/'+id, {}).done(function(response) {
            response = JSON.parse(response);
            $('#id_porters').val(id).selectpicker('refresh');
            $('#debt_porters').val(response.opening_balance);
        });
        $('#debt_porters_modal').modal('show');
    }
    function view_update_rack(id)
    {
        $.get(admin_url + 'reports/update_rack/'+id, {}).done(function(response) {
            response = JSON.parse(response);
            $('#id_rack').val(id).selectpicker('refresh');
            console.log(response.opening_balance);
            $('#debt_rack').val(response.opening_balance);
        });
        $('#debt_rack_modal').modal('show');
    }
    function view_update_other_object(id)
    {
        $.get(admin_url + 'reports/update_other_object/'+id, {}).done(function(response) {
            response = JSON.parse(response);
            $('#update_other_object #id_other_object').val(response.id).selectpicker('refresh');
            $('#debt_other_object').val(response.opening_balance);
            $('#type_other_object').val(response.type_other_object).selectpicker('refresh');
            $('.title_debt_porters').html('Đối tượn vay mượn: '+response.name);
            $('#debt_other_object_modal #debit_date').val(response.debit_date);
            $('#debt_other_object_modal #note').val(response.note);

            $('.history_object_add').html('');
            i=0;
            if(response.call_logs.length==0)
            {
                add_history_object('','');
            }
            $.each(response.call_logs,function(j,v){
                add_history_object(v.date,v.note);
            })
        });
        $('#debt_other_object_modal').modal('show');
    }
    function view_update_borrowing(id,supplier)
    {
        $.get(admin_url + 'reports/update_borrowing/'+id+'/'+supplier, {}).done(function(response) {
            response = JSON.parse(response);
            if(response=='null')
            {
                response.debit=0;
            }
            $('#id_supplier_borrowing').val(supplier).selectpicker('refresh');
            $('#product_id_borrowing').val(id).selectpicker('refresh');
            $('#debt_borrowing').val(response.debit);
        });
        $('#debt_borrowing_modal').modal('show');
    }
    $(function() {
        _validate_form($('#update_supplier'), {
            id_supplier: 'required'
        }, update_suppliers);
    })
    function update_suppliers(form)
    {
        var data = $(form).serialize();
        var url = form.action;
        return $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            alert_float(response.type_alert,response.mes);
            if($('#genernal-receivables-suppliers-debts-report').hasClass('hide')==false) {
                $('.table-genernal-receivables-suppliers-debts-report').DataTable().ajax.reload();
            }
            if($('#debts_all').hasClass('hide')==false) {
                // $('.table-debts_all_supplier').DataTable().ajax.reload();
                $('.table-debts_all_supplier').find('.supplier_date_debt_'+response.id).html(response.date_debt);
                $('.table-debts_all_supplier').find('.supplier_type_'+response.id).html(response.type_supplier);
                $('.table-debts_all_supplier').find('.supplier_note_'+response.id).html(response.note);
                if(response.RowClass!="")
                {
                    if(!$('.table-debts_all_supplier').find('.supplier_date_debt_'+response.id).parents('tr').hasClass('bg-warning'))
                    {
                        $('.table-debts_all_supplier').find('.supplier_date_debt_'+response.id).parents('tr').addClass('bg-warning');
                    }
                }
                else
                {
                    if($('.table-debts_all_supplier').find('.supplier_date_debt_'+response.id).parents('tr').hasClass('bg-warning'))
                    {
                        $('.table-debts_all_supplier').find('.supplier_date_debt_'+response.id).parents('tr').removeClass('bg-warning');
                    }
                }
            }
            $('#debt_suppliers').modal('hide');
        }),!1
    }
    $(function() {
        _validate_form($('#update_client'), {
            id_client: 'required'
        }, update_client);
    })
    function update_client(form)
    {
        var data = $(form).serialize();
        var url = form.action;
        return $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            alert_float(response.type_alert,response.mes);

            $('#debt_client_modal').modal('hide');
            if($('#debts_all').hasClass('hide')==false)
            {
                // $('.table-debts_all_client').DataTable().ajax.reload();
                $('.table-debts_all_client').find('.client_date_debt_'+response.id).html(response.date_debt);
                $('.table-debts_all_client').find('.client_type_'+response.id).html(response.type_debt_client).focus();
                $('.table-debts_all_client').find('.client_note_'+response.id).html(response.note).focus();
                if(response.RowClass!="")
                {
                    if(!$('.table-debts_all_client').find('.client_date_debt_'+response.id).parents('tr').hasClass('bg-warning'))
                    {
                        $('.table-debts_all_client').find('.client_date_debt_'+response.id).parents('tr').addClass('bg-warning');
                    }
                }
                else
                {
                    if($('.table-debts_all_client').find('.client_date_debt_'+response.id).parents('tr').hasClass('bg-warning'))
                    {
                        $('.table-debts_all_client').find('.client_date_debt_'+response.id).parents('tr').removeClass('bg-warning');
                    }
                }
            }
            if($('#debts_clients').hasClass('hide')==false)
            {
                $('.table-debts_client').DataTable().ajax.reload();
            }
            // $("html, body").scrollTop(scoll_client);
        }),!1
    }

    $(function() {
        _validate_form($('#update_porters'), {
            id_porters: 'required'
        }, update_porters);
    })
    function update_porters(form)
    {
        var data = $(form).serialize();
        var url = form.action;
        return $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            alert_float(response.type_alert,response.mes);
            $('.table-debts_porters').DataTable().ajax.reload();
            $('#debt_porters_modal').modal('hide');
        }),!1
    }
    $(function() {
        _validate_form($('#update_rack'), {
            id_rack: 'required'
        }, update_rack);
    })
    function update_rack(form)
    {
        var data = $(form).serialize();
        var url = form.action;
        return $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            alert_float(response.type_alert,response.mes);
            $('.table-debts_rack').DataTable().ajax.reload();
            $('#debt_rack_modal').modal('hide');
        }),!1
    }
    $(function() {
        _validate_form($('#update_other_object'), {
            id_other_object: 'required'
        }, update_other_object);
    });
    function update_other_object(form)
    {
        var data = $(form).serialize();
        var url = form.action;
        return $.post(url, data).done(function(response)
        {
            response = JSON.parse(response);
            alert_float(response.type_alert,response.mes);
            if($('#debts_all').hasClass('hide')==false)
            {
                $('.table-debts_all_personal').find('.object_date_debt_'+response.id).html(response.date_debt);
                $('.table-debts_all_personal').find('.object_type_'+response.id).html(response.type_other_object);
                $('.table-debts_all_personal').find('.object_note_'+response.id).html(response.note);
                if(response.RowClass!="")
                {
                    if(!$('.table-debts_all_personal').find('.object_date_debt_'+response.id).parents('tr').hasClass('bg-warning'))
                    {
                        $('.table-debts_all_personal').find('.object_date_debt_'+response.id).parents('tr').addClass('bg-warning');
                    }
                }
                else
                {
                    if($('.table-debts_all_personal').find('.object_date_debt_'+response.id).parents('tr').hasClass('bg-warning'))
                    {
                        $('.table-debts_all_personal').find('.object_date_debt_'+response.id).parents('tr').removeClass('bg-warning');
                    }
                }
            }
            $('#debt_other_object_modal').modal('hide');
        }),!1
    }

    $(function() {
        _validate_form($('#update_borrowing'), {
            id_supplier_borrowing: 'required',
            product_id_borrowing: 'required'
        }, update_borrowing);
    })
    function update_borrowing(form)
    {
        var data = $(form).serialize();
        var url = form.action;
        return $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            alert_float(response.type_alert,response.mes);
            $('.table-debts_borrowing').DataTable().ajax.reload();
            $('#debt_borrowing_modal').modal('hide');
        }),!1
    }


    $('body').on('change','#id_object',function(data){
        var id=$(this).val();
        if(id)
        {
            $('.staff_not').hide();
            $('.staff_yes').show();
            $('#staff_id_not').val();
            $.post(admin_url+"/cash_book/get_object", {id:id}).done(function(form) {
                obj = JSON.parse(form);
                var_option="<option></option>";
                $.each(obj,function(i,v){
                    var_option+="<option value='"+v.id+"'>"+v.name+"</option>";
                })
                $('#staff_id').html(var_option).selectpicker('refresh');
                return true;
            })
        }
        else
        {
            $('.staff_not').show();
            $('.staff_yes').hide();
            $('#staff_id').val('').selectpicker('refresh');
            return true;
        }
    })



    function add_history(date="",note="")
    {
      var var_div ='<div class="items"><div class="col-md-4">';
         var_div +='    <div class="form-group">';
         var_div +='       <label class="control-label">Ngày</label>';
         var_div +='       <div class="input-group">';
         var_div +='           <input type="text" name="items['+i+'][date]" class="form-control datepicker" value="'+date+'">';
         var_div +='           <div class="input-group-addon">';
         var_div +='               <i class="fa fa-calendar calendar-icon"></i>';
         var_div +='           </div>';
         var_div +='       </div>';
         var_div +='   </div>';
         var_div +='</div>';
         var_div +='<div class="col-md-7">';
         var_div +='   <div class="form-group">';
         var_div +='       <label class="control-label">Nội dung</label>';
         var_div +='       <textarea name="items['+i+'][note]" class="form-control" rows="2">'+note+'</textarea>';
         var_div +='   </div>';
         var_div +='</div>';
         var_div +='<div class="col-md-1">';
         var_div +='   <button type="button" class="btn btn-danger mtop30" onclick="delete_div(this)">X</button>';
         var_div +='</div></div>';
        $('.history_add').append(var_div);
        init_datepicker();
        i++;
    }
    function delete_div(_this)
    {
        $(_this).parents('.items').remove();
    }

    function add_history_supplier(date="",note="")
    {
      var var_div ='<div class="items"><div class="col-md-4">';
         var_div +='    <div class="form-group">';
         var_div +='       <label class="control-label">Ngày</label>';
         var_div +='       <div class="input-group">';
         var_div +='           <input type="text" name="items['+i+'][date]" class="form-control datepicker" value="'+date+'">';
         var_div +='           <div class="input-group-addon">';
         var_div +='               <i class="fa fa-calendar calendar-icon"></i>';
         var_div +='           </div>';
         var_div +='       </div>';
         var_div +='   </div>';
         var_div +='</div>';
         var_div +='<div class="col-md-7">';
         var_div +='   <div class="form-group">';
         var_div +='       <label class="control-label">Nội dung</label>';
         var_div +='       <textarea name="items['+i+'][note]" class="form-control" rows="2">'+note+'</textarea>';
         var_div +='   </div>';
         var_div +='</div>';
         var_div +='<div class="col-md-1">';
         var_div +='   <button type="button" class="btn btn-danger mtop30" onclick="delete_div(this)">X</button>';
         var_div +='</div></div>';
        $('.history_supplier_add').append(var_div);
        init_datepicker();
        i++;
    }
    function add_history_object(date="",note="")
    {
        var var_div ='<div class="items"><div class="col-md-4">';
        var_div +='    <div class="form-group">';
        var_div +='       <label class="control-label">Ngày</label>';
        var_div +='       <div class="input-group">';
        var_div +='           <input type="text" name="items['+i+'][date]" class="form-control datepicker" value="'+date+'">';
        var_div +='           <div class="input-group-addon">';
        var_div +='               <i class="fa fa-calendar calendar-icon"></i>';
        var_div +='           </div>';
        var_div +='       </div>';
        var_div +='   </div>';
        var_div +='</div>';
        var_div +='<div class="col-md-7">';
        var_div +='   <div class="form-group">';
        var_div +='       <label class="control-label">Nội dung</label>';
        var_div +='       <textarea name="items['+i+'][note]" class="form-control" rows="2">'+note+'</textarea>';
        var_div +='   </div>';
        var_div +='</div>';
        var_div +='<div class="col-md-1">';
        var_div +='   <button type="button" class="btn btn-danger mtop30" onclick="delete_div(this)">X</button>';
        var_div +='</div></div>';
        $('.history_object_add').append(var_div);
        init_datepicker();
        i++;
    }


    function SearchTable(table)
    {
        $(table).DataTable().ajax.reload();
    }



    $('body').on('click', '.exportExcelCustomer', function(e){
        var shop = $('#id_hidden_shop').val();
        var id = $('#id_hidden_customer').val();
        var date_start = $('#date_export_excel_start').val();
        var date_end = $('#date_export_excel_end').val();
        var get = '?start='+date_start+'&end='+date_end+'&shop='+shop+'&customer='+id
        window.open(admin_url+'reports/detail_debts_customer_excel' + get, '_blank');
    })


    $('body').on('shown.bs.popover', '#clickExportExcel', function(e){
        init_datepicker();
    })


    $('.table-debts_customer_detail').on('draw.dt', function() {
        var invoiceReportsTable = $(this).DataTable();
        var array_total = invoiceReportsTable.ajax.json().total_wating;
       $('#total_wating').html(formatNumber(array_total));
    });


</script>
