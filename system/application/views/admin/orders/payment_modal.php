<div class="modal fade" id="payment_order" role="dialog">
 <div class="modal-dialog modal-lg">
  <!-- Modal content-->
  <div class="modal-content">
        <?php 
        echo form_open(admin_url('payment_order/add_update_payment/'), array('id' => 'payment-form'));
         ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
              <span class="book-title"><?php echo _l('ch_chose_payment'); ?> </span>
            </h4>
          </div>
        <div class="modal-body" style="height:auto">
            <div class="tab-content true_formation">
                <div class="panel-body">
                    <table class="tnh-tb table-bordered table-hover dont-responsive-table m-group0" style="table-layout: fixed;">
                        <tbody>
                            <tr>
                                <td>
                                    <label for="number" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_code_payment'); ?>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select  data-placeholder="<?=_l('ch_chose_payment') ?>" id="code"  name="code" class="code" style="width: 100%;"><option value=""></option>
                                            <?php if(!empty($payment_order)){?>
                                                <?php foreach ($payment_order as $key => $value) {
                                                  ?>
                                                  <option  value="<?=$value['id']?>"><?=$value['prefix']?><?=$value['code']?> (<?=number_format($value['total_voucher'])?>)</option>\
                                                  <?php  
                                                } ?>
                                            <?php }?>
                                            
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_date_payment'); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php $value = (isset($purchase) ? _d($purchase->date) : _d(date('Y-m-d H:i:s'))); ?>
                                    <?php echo render_datetime_input('date', '', $value,array('disabled'=>true)); ?>
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
                                    <?php $client = (isset($order) ? $order->client : '' ); ?>
                                       <?php
                                         echo render_select('client', $clients, array('userid', 'name_system', 'company'),'', $client, array('disabled'=>true), [],'', 'c_customer with-ajax search-ajax');
                                                        ?>
                                    <input type="text" class="hide" name="client" value="<?=$client?>">
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_purchase_order'); ?>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select  data-placeholder="<?=_l('cong_orders') ?>" id="id_order" disabled name="id_order" class="id_order" style="width: 100%;"><option value=""></option><option selected value="<?=$order->id?>"><?=$order->prefix?><?=$order->code?></option>\
                                        </select>
                                        <input type="text" class="hide" name="id_order" value="<?=$order->id?>">
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
                                    <?php 
                                        $total = number_format($order->grand_total - $order->money_paid);
                                     
                                    ?>
                                       <input type="text" id="left" name="left" class="form-control " value="<?=$total?>" readonly>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('cong_currency'); ?>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                    <select disabled class="selectpicker no-margin" data-width="100%" name="currency" id="currency" data-none-selected-text="<?php echo _l('ch_chose_currency'); ?>" data-live-search="true" >
                                        <option value=""></option>

                                        <?php foreach ($currencies as $product) { ?>
                                            <option  value="<?php echo $product['id']; ?>" data-id="<?php echo $product['amount_to_vnd']; ?>"><?php echo $product['name']; ?> (<?php echo number_format($product['amount_to_vnd']); ?>)</option>
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
                                       <input  type="text" id="total" name="total" class="form-control " value="0" readonly>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <span class="total_text"><?php echo _l('ch_subtotal_payment_receive'); ?></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input readonly  id="votes_total"  name="payment" class="form-control " value="">
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
                                       <?php echo render_select('payment_mode',$payment_modes,array('id','name'),'','',array('disabled'=>true)); ?>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                         <?php echo _l('ch_account_bus'); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php echo render_select('account_business',$account_business,array('id','name'),'','',array('disabled'=>true)); ?>
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
                                    <textarea readonly rows="5" id="account_information" name="account_information" class="form-control " value=""></textarea>
                                </td>
                                <td>
                                    <label for="date" class="control-label">
                                        <small class="req text-danger">* </small>
                                        <?php echo _l('ch_text_payment'); ?>
                                    </label>
                                </td>
                                <td>
                                    <textarea readonly rows="5" id="note" name="note" class="form-control " value=""></textarea>
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
    $('#code').select2();
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
                console.log(data);
                $('#id_order').html(data);
            }
        });
    });
    $('#code').on('change', (e)=>{
        var code = $('#code').val();
        dataString = {[csrfData['token_name']] : csrfData['hash']};
        $('#id_order').select2();
        jQuery.ajax({
            type: "post",
            url: "<?=admin_url()?>payment_order/get_code/"+code,
            data: dataString,
            cache: false,
            success: function (data) {
                var item = JSON.parse(data);
                $('#currency').selectpicker('val',item.currency);
                if(item.currency != 4)
                {
                    $('.total_text').html('<?php echo _l('ch_subtotal_payment_receive') ?>');
                }else
                {
                    $('.total_text').html('<?php echo _l('ch_subtotal_payment_receivevnd') ?>');
                }
                $('#total').val(formatNumber(item.total_voucher));
                $('#votes_total').val(formatNumber(item.total_voucher_received));
                $('#payment_mode').selectpicker('val',item.payment_modes);
                $('#account_business').selectpicker('val',item.account_business);
                $('#account_information').val(formatNumber(item.account_information));
                $('#note').val(formatNumber(item.note));
                // $('#id_order').html(data);
            }
        });
    });
    $('#id_order').on('change', (e)=>{
        var id = $('#id_order').val();
        var left = $('#id_order option:selected').attr('left-id');
        $('#left').val(formatNumber(left));
        dataString = {[csrfData['token_name']] : csrfData['hash']};
        jQuery.ajax({
            type: "post",
            url: "<?=admin_url()?>payment_order/get_order_datail/"+id,
            data: dataString,
            cache: false,
            success: function (data) {
                var item = JSON.parse(data);
                $('#currency').selectpicker('val',item.currencies_id);
            }
        });
    });
    $('#votes_total').on('keyup', (e)=>{
        var left = unformat_number($('#left').val());
        var payment = unformat_number($('#votes_total').val());
        var currency = $('#currency option:selected').attr('data-id');
        var total = currency*payment;
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
        code: "required",
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
                 $('.table-orders').DataTable().ajax.reload();
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