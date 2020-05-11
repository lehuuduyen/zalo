<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="purchases_data"></div>
<div id="view_supplier_quotes"></div>
<div id="rdq_modal_data"></div>
<div id="suppliers_view_data"></div>
<div id="purchases_data_view"></div>
<div id="evaluate_modal_data"></div>
<div id="purchase_order_data"></div>
<div id="purchases_data_rdq"></div>
<div id="import_data"></div>
<script type="text/javascript">
    function add_html_evaluate(id_supplier) {
      $('.evaluate_view').html('');
      $.ajax({
          url : admin_url + 'suppliers/get_html_evaluate/' + id_supplier,
          dataType : 'json',
      })
      .done(function(data){
        $('.evaluate_view').html(data.data);
      });
    }
function int_suppliers_view(id=null,edit = false) 
{
    $('#suppliers_view_data').html('');
    $.get(admin_url + 'suppliers/int_suppliers_view/' + edit + '/' + id+'/1').done(function(response) {
    $('#suppliers_view_data').html(response);
    $('#suppliers_add').modal({show:true,backdrop:'static'});
    init_selectpicker();
    init_datepicker();
    add_html_evaluate(id);
    }).fail(function(error) {
    var response = JSON.parse(error.responseText);
    alert_float('danger', response.message);
    });    
}
$('body').on('hidden.bs.modal', '#suppliers_add', function() {
    $('#suppliers_view_data').html('');
});
function view_purchases(id) {
    $('#purchases_data_view').html('');
    $.get(admin_url + 'purchases/views_purchases/' + id).done(function (response) {
        $('#purchases_data').html(response);
        $('#views_purchases').modal('show');
        changeRowNew('tblpurchases', id);
    }).fail(function (error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    });
}
$('body').on('hidden.bs.modal', '#views_purchases', function() {
    $('#purchases_data').html('');
});
function view_import(id) {
    $('#import_data').html('');
    $.get(admin_url + 'import/views_import/' + id).done(function (response) {
        $('#import_data').html(response);
        changeRowNew('tblimport', id);
        $('#views_import').modal('show');
    }).fail(function (error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    });
}

function view_supplier_quotes(id) {
    $('#purchases_data').html('');
    $.get(admin_url + 'supplier_quotes/view_supplier_quotes/' + id).done(function (response) {
        $('#view_supplier_quotes').html(response);
        changeRowNew('tblsupplier_quotes', id);
        $('#views_items').modal('show');
    }).fail(function (error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    });
}	

function rdq_modal(id) {
    $('#purchases_data').html('');
    $.get(admin_url + 'purchases/rfq_modal/' + id +'/2').done(function (response) {
        $('#rdq_modal_data').html(response);
        changeRowNew('tblrfq_ask_price', id);
        $('#rdq_modal').modal('show');
        init_selectpicker();
    }).fail(function (error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    }); 
} 


function view_purchase_order(id) {
    $('#purchase_order_data').html('');
    $.get(admin_url + 'purchase_order/view_purchase_order/' + id).done(function (response) {
        $('#purchase_order_data').html(response);
        changeRowNew('tblpurchase_order', id);
        $('#view_purchase_order').modal('show');
    }).fail(function (error) {
        var response = JSON.parse(error.responseText);
        alert_float('danger', response.message);
    });
}

</script>