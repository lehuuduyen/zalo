<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <?php if(!empty($admin_change)) {?>
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <a href="#" class="btn btn-info pull-left H_action_button" onclick="editProcedure_client()">
                <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
               <?php echo _l('create_add_new'); ?>
            </a>
            <div class="line-sp"></div>
        </div>
    </div>
    <?php } ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                    <div class="clearfix"></div>
                        <ul class="nav nav-tabs">
                            <?php foreach($procedure_client as $key => $value){?>
                                <li class="<?=(($key == 0) ? 'active' : '')?>">
                                    <a data-toggle="tab" href="#procadure_detail_<?=$value['id']?>">
                                        <?=$value['name']?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="tab-content" id="tab_content_procadure">
                            <?php foreach($procedure_client as $key => $value){?>
                                <div id="procadure_detail_<?=$value['id']?>" id_data="<?=$value['id']?>" class="tab-pane fade <?=(($key == 0) ? 'in active' : '')?>">
                                    <h4> <?=$value['name']?> </h4>
                                    <?php render_datatable(array(
                                        _l('cong_procedure_detail_name'),
                                        _l('cong_lead_time_detail'),
                                        _l('cong_orders_by'),
                                        _l('ch_color'),
                                        _l('options'),
                                        ),'procadure_detail_'.$value['id'].' '.( ($value['type_object'] == 1 && !empty($admin_change)) ? 'sortable' : '').' dont-responsive-table'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_procedure_client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<?php init_tail(); ?>
<script>
   $(function(){
       $('.sortable tbody').sortable({
           start:function(){
               // alert(123);
           },
           stop:function(){
               EventUpdateSor(this);
           }

       });
       <?php foreach($procedure_client as $key => $value){?>
           var tAPI =  initDataTable('.table-procadure_detail_<?=$value['id']?>', admin_url+'procedure_client/table/<?=$value['id'].(!empty($admin_change) ? '?admin_change=true' : '')?>', [0], [0], {}, [2, 'ASC']);
        <?php } ?>
   });

   function editProcedure_client(id = "", _this)
   {
       var button = $(_this);
       button.button({loadingText: '<?=_l('cong_please_wait')?>'});
       button.button('loading');
       var data = {};
       if (typeof (csrfData) !== 'undefined') {
           data[csrfData['token_name']] = csrfData['hash'];
       }
       var id_detail = $('#tab_content_procadure').find('.tab-pane.active').attr('id_data');
       data['id_detail'] = id_detail;
       if($.isNumeric(id))
       {
           data['id'] = id;
       }
       $.post(admin_url+'procedure_client/modal_procedure', data, function(data){
            $('#modal_procedure_client').html(data);
            $('#modal_procedure_client').modal('show');
       }).always(function() {
           button.button('reset')
       });

   }

   function deleteProcedure_client(id = "", table = "", _this) {
        if($.isNumeric(id))
        {
            if(confirm("<?=_l('cong_you_must_delete')?>"))
            {
                var button = $(_this);
                button.button({loadingText: '<?=_l('cong_please_wait')?>'});
                button.button('loading');
                var data = {};
                if (typeof (csrfData) !== 'undefined') {
                    data[csrfData['token_name']] = csrfData['hash'];
                }
                data['id'] = id;
                $.post(admin_url+'procedure_client/delete_procedure', data, function(data){
                    data = JSON.parse(data);
                    alert_float(data.alert_type, data.message);
                    if(data.success)
                    {
                        $('.'+table).DataTable().ajax.reload();
                    }
                }).always(function() {
                    button.button('reset')
                });
            }
        }
   }

   function EventUpdateSor(_this)
   {
       var table = $(_this).parents('table');
       if(confirm('<?=_l('cong_you_must_order_procedure')?>'))
       {
           var button = $(_this);
           button.button({loadingText: '<?=_l('cong_please_wait')?>'});
           button.button('loading');
           var TrData = table.find('tbody').find('tr');
           var data = {};
           if (typeof (csrfData) !== 'undefined') {
               data[csrfData['token_name']] = csrfData['hash'];
           }

           var stt = 1;
           $.each(TrData, function (i, v) {
               data[$(v).find('input.hidden_id').val()] = stt;
               stt++;
           })
           $.post(admin_url+'procedure_client/OrdersProcedure', data, function(data){
                data = JSON.parse(data);
                alert_float(data.alert_type, data.message);
                if(data.success)
                {
                    $(table).DataTable().ajax.reload();
                }
           }).always(function() {
               button.button('reset')
           });
       }
       else {
           $(table).DataTable().ajax.reload();
       }
   }
</script>
</body>
</html>
