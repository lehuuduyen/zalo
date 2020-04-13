<script type="text/javascript">
	$(document).on('click', '.customer-tab', (e)=>{
      $('.customer-tab').addClass('active');
      $('.order-tab').removeClass('active');
      $('.tab-content-customer').removeClass('hide');
      $('.order').addClass('hide');
      document.getElementById('customer-tab').style.zIndex = '2';
      document.getElementById('order-tab').style.zIndex = '1';
    });
    $(document).on('click', '.order-tab', (e)=>{
      $('.customer-tab').removeClass('active');
      $('.order-tab').addClass('active');
      $('.tab-content-customer').addClass('hide');
      $('.order').removeClass('hide');
      document.getElementById('customer-tab').style.zIndex = '1';
      document.getElementById('order-tab').style.zIndex = '2';
      resizeHeight();
    });
        //láy chiều cao động
    $(document).ready(function(){
        var offsetHeightCustomer = 0;
        var offsetHeightSummary = 0;
        if($('#customer-info').length)
        {
            offsetHeightCustomer = document.getElementById('customer-info').offsetHeight;
        }
        if($('#summary-inserted').length)
        {
            offsetHeightSummary = document.getElementById('summary-inserted').offsetHeight;
        }
        var totalHeight = offsetHeightCustomer + offsetHeightSummary;
        if($('#each-list-container').length) {
            document.getElementById('each-list-container').style.height = 'calc(100% - '+totalHeight+'px)';
        }
    });
    function resizeHeight() {
      var offsetHeightDelivery = document.getElementById('delivery-info').offsetHeight;
      var offsetHeightOrder = document.getElementById('order-search').offsetHeight;
      var offsetHeightItem = document.getElementById('item-list-container').offsetHeight;
      var offsetHeightCheckout = document.getElementById('checkout-details').offsetHeight;
      var offsetHeightPos = document.getElementById('pos-actions').offsetHeight;
      var totalHeight = offsetHeightDelivery + offsetHeightOrder + offsetHeightItem + offsetHeightCheckout + offsetHeightPos;
      document.getElementById('body-item-list-container').style.height = 'calc(100% - '+totalHeight+'px)';
    }
    //end
    //focus input khách hàng khi click search
    $(document).on('click', '.search-customer', (e)=>{
      $('#search_customer').focus();
    });
    //end
    $(document).on('click','.submit_profile',function(e){
    var phone_number_client = $('.phone_number_client').val();
		var address_client = $('.address_client').val();
		var email_client = $('.email_client').val();
		var note_profile = $('.note_profile').val(); 
		var id_facebook = $('#id_facebook').val();
		var client_id = $('#client_id').val();
    var name = $('#name-customer-right').text();
        var checkbox = document.getElementsByName("sex");
        for (var i = 0; i < checkbox.length; i++){
            if (checkbox[i].checked === true){
             var sex=checkbox[i].value;
            }
        }
    	dataString={name:name,client_id:client_id,id_facebook:id_facebook,phone_number_client:phone_number_client,address_client:address_client,email_client:email_client,note_profile:note_profile,sex:sex};
	    jQuery.ajax({
	      type: "post",
	      url:"<?=base_url()?>messager/add_profile/",
	      data: dataString,
	      cache: false,
	      success: function (data) {

	      	find_uid_facebook(id_facebook);
	      		// data = JSON.parse(data);
	            // alert_float(data.alert_type,data.message)
	            alert(data.alert_type)
	          }
	        });
    });
    function get_sale_client(data) {
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                var html='';
                var html_sales='';
                if(data.items)
                {
                $.each(data.items, function(key,value){
                   html+= '<div class="item-name">\
                        '+value.name+' ('+value.quantity+')\
                      </div>';
                });
                }
                $('.each-item-name').html(html);
                if(data.sales)
                {
                $.each(data.sales, function(key,value){
                   html_sales+= '<div class="order-item-list">\
                      <div class="ordercode">'+value.prefix+value.code+'</div>\
                      <div class="state">\
                        (Đang xử lý)\
                      </div>\
                      <div class="total">'+value.total+'</div>\
                      <div class="orderdate">'+(value.date).toLocaleDateString('de-DE', options)+'</div>\
                    </div>';
                });
                }
                $('.each-order-item-list').html(html_sales);
    }
</script>