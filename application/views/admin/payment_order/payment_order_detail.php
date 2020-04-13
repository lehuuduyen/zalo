<div class="modal fade" id="payment_order_detail" role="dialog">
 <div class="modal-dialog modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
        <?php 
        echo form_open(admin_url('payment_order/payment_update/'.$payment->id), array('id' => 'payment-form'));
         ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
              <span class="book-title"><?php echo _l('ch_payment'); ?> </span>
            </h4>
          </div>
        <div class="modal-body" style="height:auto">
            <input type="text" class="currency_vnd hide" value="<?=(!empty($payment) ? $payment->currency_vnd : 1 )?>">
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
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('clients'); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php $client = (!empty($payment) ? $payment->client : '' ); ?>

                                    <?php
                                    $disabled = array();
                                    if(!empty($order)){
                                        $disabled = array('disabled'=>true);
                                        ?>
                                        <input type="text" name="client" class="hide" value="<?=$client?>">
                                        <?php 
                                    } ?>
                                       <?php
                                         echo render_select('client', $clients, array('userid', 'name_system', 'company'), '', $client, $disabled, [],'', 'c_customer with-ajax search-ajax');
                                                        ?>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_purchase_order'); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php if(!empty($order)){ ?>
                                    <div class="form-group">
                                        <select  data-placeholder="<?=_l('cong_orders') ?>" id="id_order" disabled name="id_order" class="id_order" style="width: 100%;"><option value=""></option><option selected value="<?=$order->id?>"><?=$order->prefix?><?=$order->code?></option>\
                                        </select>
                                        <input type="text" class="hide" name="id_order" value="<?=$order->id?>">
                                    </div>
                                    <?php }else{ ?>
                                    <div class="form-group">
                                        <select  data-placeholder="<?=_l('cong_orders') ?>" id="id_order" name="id_order" class="id_order" style="width: 100%;"><option value=""></option>\
                                        </select>
                                    </div>
                                    <?php } ?>
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
                                       <input type="text" id="left" name="left" class="form-control " value="<?=number_format($payment->left_total)?>" readonly>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('cong_currency'); ?>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <?php if(!is_admin()){?>
                                            <input type="text" class="hide" value="<?=$payment->currency?>" name="currency">
                                    <select class="selectpicker no-margin currency" data-width="100%" name="currency" id="currency" disabled data-none-selected-text="<?php echo _l('ch_chose_currency'); ?>" data-live-search="true" >
                                        <option value=""></option>

                                        <?php foreach ($currencies as $product) { ?>
                                            <option <?=(($product['id']==$payment->currency) ? 'selected' : '' )?> value="<?php echo $product['id']; ?>" data-id="<?php echo $product['amount_to_vnd']; ?>"><?php echo $product['name']; ?> (<?php echo number_format($product['amount_to_vnd']); ?>)</option>
                                            <?php
                                        } ?>
                                    </select>
                                    <?php }else{?>
                                     <select class="selectpicker no-margin currency" data-width="100%" name="currency" id="currency" data-none-selected-text="<?php echo _l('Chọn đơn vị tiền tệ'); ?>" data-live-search="true" >
                                        <option value=""></option>

                                        <?php foreach ($currencies as $product) { ?>
                                            <option <?=(($product['id']==$payment->currency) ? 'selected' : '' )?> value="<?php echo $product['id']; ?>" data-id="<?php echo $product['amount_to_vnd']; ?>"><?php echo $product['name']; ?> (<?php echo number_format($product['amount_to_vnd']); ?>)</option>
                                            <?php
                                        } ?>
                                    </select>   
                                    <?php }?>
                                    <div class="form-group">
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
                                        <?php $total_voucher = (!empty($payment) ? $payment->total_voucher : '' ); ?>
                                       <input type="text" id="total" name="total" class="form-control " value="<?=number_format($total_voucher)?>" readonly>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php if($payment->currency == 4) {?>
                                        <?php echo _l('ch_subtotal_payment_receive'); ?>
                                        <?php }else{?>
                                        <?php echo _l('ch_subtotal_payment_receivevnd'); ?>   
                                        <?php }?>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <?php $votes_total = (!empty($payment) ? $payment->total_voucher_received : '' ); ?>
                                        <?php if(!is_admin()){?>
                                        <input readonly  id="votes_total" <?=(!empty($order) ? (($order->currencies_id == 4) ? ' type="number"' : 'type="text"' ) : '' )?> name="payment" class="form-control " value="<?=number_format($votes_total)?>">
                                        <?php }else{?>
                                         <input  id="votes_total" <?=(!empty($order) ? (($order->currencies_id == 4) ? 'type="number"' : 'type="text"' ) : '' )?> name="payment" class="form-control " value="<?=number_format($votes_total)?>">   
                                        <?php }?>
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
                                        <?php $payment_mode = (!empty($payment) ? $payment->payment_modes : '' ); ?>
                                        <?php if(!is_admin()){?>
                                            <input type="text" class="hide" value="<?=$payment_mode?>" name="payment_mode">
                                       <?php echo render_select('payment_mode',$payment_modes,array('id','name'),'',$payment_mode,array('disabled'=>true)); ?>
                                       <?php }else{?>
                                        <?php echo render_select('payment_mode',$payment_modes,array('id','name'),'',$payment_mode); ?>
                                       <?php }?> 
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                         <?php echo _l('ch_account_bus'); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php $account = (!empty($payment) ? $payment->account_business : '' ); ?>
                                    <?php if(!is_admin()){?>
                                     <input type="text" class="hide" value="<?=$account?>" name="account_business">   
                                    <?php echo render_select('account_business',$account_business,array('id','name'),'',$account,array('disabled'=>true)); ?>
                                    <?php }else{?>
                                    <?php echo render_select('account_business',$account_business,array('id','name'),'',$account); ?>
                                    <?php }?>     
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
                                    <?php $account_information = (!empty($payment) ? $payment->account_information : '' ); ?>
                                    <textarea rows="5" <?= (is_admin() ? '' : 'readonly' ) ?> id="account_information" name="account_information" class="form-control " value=""><?=$account_information?></textarea>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_text_payment'); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php $note = (!empty($payment) ? $payment->note : '' ); ?>
                                    <textarea rows="5" <?= (is_admin() ? '' : 'readonly' ) ?> id="note" name="note" class="form-control " value=""><?=$note?></textarea>
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
    $('#client').change();
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
                // $('#currency').selectpicker('val',item.currencies_id);
                 var left = $('#id_order option:selected').attr('left-id');
                 $('#left').val(formatNumber(left)); 
            }
        });
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
        var left = unformat_number($('#left').val());
        var payment = unformat_number($('#votes_total').val());
        var currency_vnd = $('.currency_vnd').val();
        var currency = $('#currency option:selected').attr('data-id');
        var total = currency_vnd*payment;
        // if(total > left)
        // {
        //    $('#total').val(0);
        //    $('#votes_total').val(0);
        //    alert('Giá trị thanh toán vượt quá giá trị đơn đặt hàng!')
        //    return;
        // }
        $('#total').val(formatNumber(total));
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
        var currency_vnd = $('.currency_vnd').val();
        
        var total = currency_vnd*payment;
        $('#total').val(formatNumber(total));
    });
    $('#votes_total').on('click', (e)=>{
        var left = unformat_number($('#left').val());
        var payment = unformat_number($('#votes_total').val());
        var currency = $('#currency option:selected').attr('data-id');
        var currency_vnd = $('.currency_vnd').val();
        var total = currency_vnd*payment;
        // if(total > left)
        // {
        //    $('#total').val(0);
        //    $('#votes_total').val(0);
        //    alert('Giá trị thanh toán vượt quá giá trị đơn đặt hàng!')
        //    return;
        // }
        $('#total').val(formatNumber(total));
    });
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
                 $('#payment_order_detail').modal('hide');
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