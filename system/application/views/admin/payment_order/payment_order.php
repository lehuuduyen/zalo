<div class="modal fade" id="payment_order" role="dialog">
 <div class="modal-dialog modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
        <?php 
        echo form_open(admin_url('payment_order/payment/'), array('id' => 'payment-form'));
         ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
              <span class="book-title"><?php echo _l('ch_payment'); ?> </span>
            </h4>
          </div>
        <div class="modal-body" style="height:auto">
            <div class="tab-content true_formation">
                <div class="panel-body">
                    <table class="tnh-tb table-bordered table-hover dont-responsive-table m-group0" style="table-layout: fixed;">
                        <tbody>
                            <tr>
                                <td style="width: 17%;">
                                    <label for="number" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_code_payment'); ?>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" style="width: 100%" id="code" name="code" class="form-control " readonly value="<?=$code?>">
                                    </div>
                                </td>
                                <td style="width: 17%;">
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_date_payment'); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php $value = (isset($purchase) ? _d($purchase->date) : _d(date('Y-m-d H:i:s'))); ?>
                                    <?php echo render_datetime_input('date', '', $value); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="number" class="control-label">
                                        
                                        <?php echo _l('clients'); ?>
                                    </label>
                                </td>
                                <td>
                                       <?php
                                         echo render_select('client', $clients, array('userid', 'name_system', 'company'), '', $value, [], [],'', 'c_customer with-ajax search-ajax');
                                                        ?>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        
                                        <?php echo _l('ch_purchase_order'); ?>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select  data-placeholder="<?=_l('cong_orders') ?>" id="id_order" name="id_order" class="id_order" style="width: 100%;"><option value=""></option>\
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="number" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_total_need_payment'); ?>
                                    </label>
                                </td>
                                <td>
                                       <input type="text" id="left" name="left" class="form-control " value="0" readonly>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('cong_currency'); ?>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                    <select  class="selectpicker no-margin currency"  data-width="100%" name="currency" id="currency" data-none-selected-text="<?php echo _l('ch_chose_currency'); ?>"  data-live-search="true" >
                                        <option value=""></option>

                                        <?php foreach ($currencies as $product) { ?>
                                            <option value="<?php echo $product['id']; ?>" data-id="<?php echo $product['amount_to_vnd']; ?>"><?php echo $product['name']; ?> (<?php echo number_format($product['amount_to_vnd']); ?>)</option>
                                            <?php
                                        } ?>
                                    </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="number" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_subtotal_payment'); ?>
                                    </label>
                                </td>
                                <td>
                                       <input type="text" id="total" name="total" class="form-control " value="0" readonly>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <span class="total_text"><?php echo _l('Giá trị phiếu thu nhận được'); ?><span>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" id="votes_total"   name="payment" class="form-control " value="">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="number" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('acs_sales_payment_modes_submenu'); ?>
                                    </label>
                                </td>
                                <td>
                                       <?php echo render_select('payment_mode',$payment_modes,array('id','name'),''); ?>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                         <?php echo _l('ch_account_bus'); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php echo render_select('account_business',$account_business,array('id','name'),''); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="number" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_account_user'); ?>
                                    </label>
                                </td>
                                <td>
                                    <textarea rows="5" id="account_information" name="account_information" class="form-control account_information" value=""></textarea>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_text_payment'); ?>
                                    </label>
                                </td>
                                <td>
                                    <textarea rows="5" id="note" name="note" class="form-control note" value=""></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <div class="clearfix">  </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info"  id="submit" autocomplete="off"><?=_l('submit')?></button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><?=_l('close')?></button>
        <!--  -->
    </div>
</form>
</div>
</div>
</div>
<script type="text/javascript">
    $('#id_order').select2();
    $('#client').on('change', (e)=>{
        var client = $('#client').val();
        dataString = {[csrfData['token_name']] : csrfData['hash']};
        $('#id_order').select2();
        jQuery.ajax({
            type: "post",
            url: "<?=admin_url()?>payment_order/get_order/"+client,
            data: dataString,
            cache: false,
            success: function (data) {
                $('#id_order').html(data);
            }
        });
    });

    $('#id_order').on('change', (e)=>{
        var id = $('#id_order').val();
        
        dataString = {[csrfData['token_name']] : csrfData['hash']};
        jQuery.ajax({
            type: "post",
            url: "<?=admin_url()?>payment_order/get_order_datail/"+id,
            data: dataString,
            cache: false,
            success: function (data) {
                var item = JSON.parse(data);
                $('#currency').selectpicker('val',item.currencies_id);
                // if(item.currencies_id == 4)
                // {
                 var left = $('#id_order option:selected').attr('left-id');
                 $('#left').val(formatNumber(left)); 
                 
                // }else
                // {
                //  var left_usd = $('#id_order option:selected').attr('left-usd');
                //  $('#left').val(left_usd); 
                // }
                
            }
        });
    });
    $('#votes_total').on('change', (e)=>{
        var currency = $('#currency').val();
        var votes_total = $('#votes_total').val();
        if(currency == 4)
        {
            key="";
            money=$('#votes_total').val().replace(/[^\-\d\.]/g, '');
            a=money.split(".");
            $.each(a , function (index, value){
                key=key+value;
            });
            $('#votes_total').val(formatNumber(key, '.', ','));
        }
        var left = unformat_number($('#left').val());
        var payment = unformat_number($('#votes_total').val());
        var currencys = $('#currency option:selected').attr('data-id');
        if(empty(currencys))
        {
            currencys = 1;
        }
        var total = currencys*payment;
        $('#total').val(formatNumber(total));
    });
    $('#currency').on('change', (e)=>{
        var id = $('#id_order').val();
        $('#votes_total').val(0);
        $('#votes_total').change();  
        var currency = $('#currency').val();
        if(currency != 4)
        {
            $('.total_text').html('<?php echo _l('Giá trị phiếu thu nhận được')?>');
            $('#votes_total').attr('onkeyup',false);
            $('#votes_total').attr('type','number');
        }else
        {
            $('.total_text').html('<?php echo _l('Giá trị phiếu thu nhận được (VNĐ)')?>');
            $('#votes_total').attr('type','text');
            $('#votes_total').attr('onkeyup',false);
        }
    });
    $('#votes_total').on('keyup', (e)=>{
        // var currency = $('#currency').val();
        // var votes_total = $('#votes_total').val();
        // if(currency == 4)
        // {
        //     key="";
        //     money=$('#votes_total').val().replace(/[^\-\d\.]/g, '');
        //     a=money.split(".");
        //     $.each(a , function (index, value){
        //         key=key+value;
        //     });
        //     $('#votes_total').val(formatNumber(key, '.', ','));
        // }
        var left = unformat_number($('#left').val());
        var payment = unformat_number($('#votes_total').val());
        var currencys = $('#currency option:selected').attr('data-id');
        if(empty(currencys))
        {
            currencys = 1;
        }
        var total = currencys*payment;
        // if(total > left)
        // {
        //    $('#total').val(0);
        //    $('#votes_total').val(0);
        //    alert('Giá trị thanh toán vượt quá giá trị đơn đặt hàng!')
        //    return;
        // }
        $('#total').val(formatNumber(total));
    })
    $('#votes_total').on('click', (e)=>{
        // var currency = $('#currency').val();
        // var votes_total = $('#votes_total').val();
        // if(currency == 4)
        // {
        //     key="";
        //     money=$('#votes_total').val().replace(/[^\-\d\.]/g, '');
        //     a=money.split(".");
        //     $.each(a , function (index, value){
        //         key=key+value;
        //     });
        //     $('#votes_total').val(formatNumber(key, '.', ','));
        // }
        var left = unformat_number($('#left').val());
        var payment = unformat_number($('#votes_total').val());
        var currencys = $('#currency option:selected').attr('data-id');
        if(empty(currencys))
        {
            currencys = 1;
        }
        var total = currencys*payment;
        // if(total > left)
        // {
        //    $('#total').val(0);
        //    $('#votes_total').val(0);
        //    alert('Giá trị thanh toán vượt quá giá trị đơn đặt hàng!')
        //    return;
        // }
        $('#total').val(formatNumber(total));
    })
    $(function(){
    _validate_form($('#payment-form'), {
        date: "required",
        receiver: "required",
        payment_mode: "required",
        payment: "required",
        currency: "required",
        account_information: "required",
        note: "required",
    },add_payment);
    });
      function add_payment(form) {
        if($('#payment-form input.error').length == 0) {
            $('#submit').button('loading');
        }
         var data = $(form).serialize(),
             action = form.action;
         return $.post(action, data).done(function(form) {
             form = JSON.parse(form),
             alert_float(form.alert_type, form.message);
             if(form.success)
             {
                 tAPI.draw('page');
                 $('#payment_order').modal('hide');
             }

         }), !1
      }
    function formatNumber(nStr, decSeperate=".", groupSeperate=",") {
        nStr += '';
        x = nStr.split(decSeperate);
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        x2=x2.substr(0,2);
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
        }
        return x1 + x2;
    };
    function unformat_number(number)
    {
        var _number=0;
        if(number)
        {
            _number=number.replace(/[^\-\d\.]/g, '');
        }
        return _number;
    };
</script>