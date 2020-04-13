<?php init_head(); ?>
<style>
    .inline-block{
        padding-top: 1px!important;
        padding-bottom: 1px!important;
    }
    .table-localtion_warehouses p{
        height: 23px!important;
    }
</style>
<div id="wrapper">
      <?php if(has_permission('warehouse','','create')){ ?>
  <div class="panel_s mbot10 H_scroll" id="H_scroll">
    <div class="panel-body _buttons">
        <span class="bold uppercase fsize18 H_title"><?=$title?></span>
        <div class="line-sp"></div>
        <a href="" onclick="new_localtion_warehouse(); return false;" class="btn btn-info pull-right H_action_button" onclick="add(); return false;">
               <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
               <?php echo _l('create_add_new'); ?>
        </a>
    </div>
  </div>
  <?php } ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('ID'),
                            _l('ch_warehouse'),
                            _l('warehouse_localtion'),
                            _l('invoice_dt_table_heading_status'),
                            _l('proposal_date_created'),
                            _l('ch_option'),
                        ),'localtion_warehouses'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_localtion_warehouses" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('warehouse/add_location_warehouse'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('ch_edit_warehouse_localtion'); ?></span>
                    <span class="add-title"><?php echo _l('ch_add_warehouse_localtion'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="id" id="id_localtion_warehouse"/>
                        <div id="additional"></div>
                        <?php echo render_input('code','ch_code_warehouse_localtion'); ?>
                        <?php echo render_input('name','ch_name_warehouse_localtion'); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_select('warehouse',$warehouse,array('id','name'),'ch_warehouse',''); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_select('id_parent',array(),array(),'ch_note_warehouse_localtion',''); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div><
</div>
<div class="modal fade" id="modal_delete_localtion_warehouses" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('localtion_warehouses/delete_location_warehouse'),array('id'=>'delete_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="delete-title"><?php echo _l('ch_delete_warehouse_localtion'); ?></span>
                    <p class="text-danger"><?=_l('ch_note_delete_warehouse_localtion')?></p>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="id" id="id_delete"/>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_select('warehouse_new',$warehouse,array('id','name'),'Kho',''); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_select('id_new',array(),array(),'ch_remove_items_warehouse_localtion',''); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div><
</div>
<?php init_tail(); ?>
<script>
    var filterList = {
        // "warehouse"  : "[name='warehouse']"
    };
    $(function(){
        initDataTable('.table-localtion_warehouses', admin_url+'warehouse/table_warehouse_localtion', [1], [1], filterList);
    
        _validate_form($('#id_type'),{code:'required',name:'required',warehouse:'required'},manage_localtion_manage);
        $('#type').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#type input').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
        _validate_form($('#delete_type'),{id_new:'required'},manage_localtion_manage);
        function manage_localtion_manage(form) {
            var data = $(form).serialize();
            var url = form.action;
            $.post(url, data).done(function(response) {
                response = JSON.parse(response);
                if(response.success == true){
                    alert_float('success',response.message);
                }
                else
                {
                    alert_float('danger',response.message);
                }
                $('.table-localtion_warehouses').DataTable().ajax.reload();
                $('#modal_localtion_warehouses').modal('hide');
                $('#modal_delete_localtion_warehouses').modal('hide');
            });
            return false;
        }
    }); 
    function new_localtion_warehouse(id="",_this)
    {
        $(_this).button('loading');
        if(id=="")
        {
            $('#modal_localtion_warehouses').modal('show');
            $('#id_localtion_warehouse').val('');
            $('#code').val('');
            $('#name').val('');
            $('#warehouse').selectpicker('val','');
            $('#id_parent').val('').selectpicker('val','');
            $('.add-title').show();
            $('.edit-title').hide();
            $('#modal_localtion_warehouses').modal('show');
            $(_this).button('reset');
        }
        else
        {
            $('#id_localtion_warehouse').val(id);
            $.ajax({
                type: "post",
                url:admin_url+"warehouse/get_localtion_warehouse/"+id,
                data: {[csrfData['token_name']] : csrfData['hash']},
                cache: false,
                success: function (data) {
                    var json = JSON.parse(data);
                    $('#code').val(json.code);
                    $('#name').val(json.name);
                    $('#warehouse').selectpicker('val',json.warehouse);
                    $.post(admin_url+"warehouse/list_localtion",{warehouse:json.warehouse,lever:json.lever,[csrfData['token_name']] : csrfData['hash']},function(_data){
                        $('#id_parent').html(_data).selectpicker('refresh');
                        $('#id_parent').selectpicker('val',json.id_parent);
                        $('#modal_localtion_warehouses').modal('show');
                        $(_this).button('reset');
                    })
                }
            });
            $('.edit-title').show();
            $('.add-title').hide();
        }
         setTimeout(function() {
            $(_this).button('reset');
        }, 8000);
    }
    $('body').on('change','#warehouse',function(e){
        var warehouse=$(this).val();
        $.post(admin_url+"warehouse/list_localtion",{warehouse:warehouse,[csrfData['token_name']] : csrfData['hash']},function(data){
            $('#id_parent').html(data).selectpicker('refresh');
            
        })
    })
    function delete_localtion_warehouses(id="")
    {

        if(id!="")
        {
            var r = confirm("<?php echo _l('confirm_action_prompt');?>");
            if (r == false) {
                return false;
            } else {
                $.ajax({
                    url: '<?php echo admin_url('warehouse/get_exist/') ?>' + id,
                    dataType: 'json',
                }).done((data)=>{
                   
                    // $('#warehouse_new').selectpicker('val','');
                    // $('#id_new').html('').selectpicker('refresh');
                    // $('#modal_delete_localtion_warehouses').modal('show');
                    // $('#id_delete').val(id);
                    
                    alert_float(data.alert_type, data.message);
                    $('.table-localtion_warehouses').DataTable().ajax.reload();   
                });
            }
        }
    }
    $('body').on('change','#warehouse_new',function(e){
        var warehouse=$('#warehouse_new').val();
        $.post(admin_url+"warehouse/list_localtion",{warehouse:warehouse,[csrfData['token_name']] : csrfData['hash']},function(_data){
            $('#id_new').html(_data).selectpicker('refresh');
            $('#id_new option[value="'+$('#id_delete').val()+'"]').prop('style','display:none;').selectpicker('refresh');
            $('#modal_delete_localtion_warehouses').modal('show');
        })  
    })
  $(document).on('click', '.onoffswitch_ch', function() {
      var r = confirm("<?php echo _l('Phải chắc chắn chuyển toàn bộ sản phẩm trong vị trí này vào vị trí khác!');?>");
      if (r == false) { 
        return false;
      } else {
        $.get($(this).attr('data-switch-url'), function(response) {
          alert_float(response.alert_type, response.message);
            $('.table-localtion_warehouses').DataTable().ajax.reload();
          }, 'json');
      }
      return false;
    });
  $(document).on('click', '.onoffswitch_chc', function() {
    console.log(123);
        $.get($(this).attr('data-switch-url'), function(response) {
          alert_float(response.alert_type, response.message);
            $('.table-localtion_warehouses').DataTable().ajax.reload();
          }, 'json');
      return false;
    });  
</script>
</body>
</html>
