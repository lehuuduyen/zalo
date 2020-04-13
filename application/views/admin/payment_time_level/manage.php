<?php init_head(); ?>
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
      <div class="panel-body _buttons">
         <div class="_buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
           
            <?php if (has_permission('payment_time_level','','create')) { ?>
            <div class="line-sp"></div>
            <a href="#"  onclick="new_unit(); return false;" id="suppliers_modal" class="btn btn-info mright5 test pull-right H_action_button">
               <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
               <?php echo _l('create_add_new'); ?></a>
            <?php } ?>
            <div class="clearfix"></div>
         </div>
      </div>
   </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('#'),
                            _l('Tên mức thời gian thanh toán'),
                            _l('Thời gian'),
                            _l('options')
                        ),'payment_time_level'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="type" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('payment_time_level/add_payment_time_level'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('Sửa mức thời gian thanh toán'); ?></span>
                    <span class="add-title"><?php echo _l('Thêm mức thời gian thanh toán'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name','Tên mức thời gian thanh toán'); ?>
                        <?php echo render_input('time','Thời gian / ngày','','number'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script>

    function view_init_department(id)
    {
        $('#type').modal('show');
        $('.add-title').addClass('hide');
        $('#name').val('');
        $('#time').val('');
        $.ajax({
                url : admin_url + 'payment_time_level/get_row_unit/' + id,
                dataType : 'json',
            })
            .done(function(data){
               if(data!="")
                {
                    $('#name').val(data.name);
                    $('#time').val(data.time);
                    $('#id_type').prop('action',admin_url+'payment_time_level/update_payment_time_level/'+id);
                }
            });
    }
   $(function(){
       var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
       CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';

       var tAPI = initDataTable('.table-payment_time_level', admin_url+'payment_time_level/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'asc'))); ?>);
       $('input[name="exclude_inactive"]').on('change',function(){
           tAPI.ajax.reload();
       });
   });
    $(function(){
        appValidateForm($('#id_type'),{name:'required',time:'required'},manage_contract_types);
        $('#type').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#type input[name="name"]').val('');
            $('#type input[name="time"]').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });
    function manage_contract_types(form) {
        var data = $(form).serialize();
        
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
            }
            $('.table-payment_time_level').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }

    function new_unit(){

        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        $('#name').val('');
        $('#time').val('');
        $('#id_type').attr('action',admin_url+'payment_time_level/add_payment_time_level');
    }
    function edit_type(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#type input[name="unit"]').val(name);
        $('#type').modal('show');
        $('.add-title').addClass('hide');
    }
    $(document).on('click', '.delete-remind', function() {
        var r = confirm("<?php echo _l('confirm_action_prompt');?>");
        if (r == false) {
            return false;
        } else {
            $.get($(this).attr('href'), function(response) {
              alert_float(response.alert_type, response.message);
                $('.table-payment_time_level').DataTable().ajax.reload();
            }, 'json');
        }
        return false;
    });
    

</script>
