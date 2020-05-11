<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <?php if(has_permission('warehouse','','create')){ ?>
  <div class="panel_s mbot10 H_scroll" id="H_scroll">
    <div class="panel-body _buttons">
        <span class="bold uppercase fsize18 H_title"><?=$title?></span>
        <a class="btn btn-info mright5 test pull-right H_action_button">
               <?php echo _l('Export excel'); ?></a>
        <div class="line-sp"></div>
        <a href="#" class="btn btn-info pull-right H_action_button" onclick="add(); return false;">
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
            <?php render_datatable(array(
              _l('#'),
              _l('code_group'),
              _l('code'),
              _l('name'),
              _l('note'),
              _l('options'),
            ),'warehouse'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="warehouse_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo _l('warehouse_add_heading'); ?></span>
                    <span class="edit-title"><?php echo _l('warehouse_edit_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/warehouse/group_detail',array('id'=>'warehouse-group-modal')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_select('id_group_warehouse', $group, array('id', 'name'), 'group_warehouse'); ?>
                        <?php echo render_input('code','code'); ?>
                        <?php echo render_input('name','name'); ?>
                        <?php echo render_input('address','address'); ?>
                        <?php echo render_textarea('note','note'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button group="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
  $(function(){
    initDataTable('.table-warehouse', admin_url+'warehouse/table_warehouse', [0], [0],'',[0,'asc']);
    appValidateForm($('#warehouse-group-modal'), {id_group_warehouse: 'required', code: 'required', name: 'required', address: 'required'}, manage_warehouse);
  });
  function add() {
    $('.add-title').removeClass('hide');
    $('.edit-title').addClass('hide');
    $('#warehouse-group-modal').attr("action","<?=admin_url('warehouse/detail')?>");
    $('#id_group_warehouse').selectpicker('val','');
    $('#name').val('');
    $('#address').val('');
    $('#code').val('');
    $('#note').val('');
    $('#warehouse_modal').modal('show');
  }
  function edit(id) {
    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
    $('#warehouse-group-modal').attr("action","<?=admin_url('warehouse/detail/')?>"+id);
    var data = {};
    if (typeof(csrfData) !== 'undefined') {
      data[csrfData['token_name']] = csrfData['hash'];
    }
    $.post(admin_url+'warehouse/getData/'+id, data).done(function(response){
       response = JSON.parse(response);
       $('#id_group_warehouse').selectpicker('val',response.id_group_warehouse);
       $('#name').val(response.name);
       $('#address').val(response.address);
       $('#code').val(response.code);
       $('#note').val(response.note);
       $('#warehouse_modal').modal('show');
    });
  }
  function delete_main(id) {
    var r = confirm("<?php echo _l('confirm_action_prompt');?>");
    if (r == false) {
          return false;
      } else {
        var data = {};
        if (typeof(csrfData) !== 'undefined') {
          data[csrfData['token_name']] = csrfData['hash'];
        }
        $.post(admin_url+'warehouse/delete_main/'+id, data).done(function(response){
           response = JSON.parse(response);
           if(response.success == true) {
            $('.table-warehouse').DataTable().ajax.reload();
            alert_float(response.alert_type,response.message)
           }
           else {
            alert_float(response.alert_type,response.message)
           }
        });
      }
      return false;
  }
  function manage_warehouse(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {
            $('#id_group_warehouse').selectpicker('val','');
            $('#name').val('');
            $('#address').val('');
            $('#code').val('');
            $('#note').val('');
            $('.table-warehouse').DataTable().ajax.reload();
            alert_float(response.alert_type, response.message);
        }
        $('#warehouse_modal').modal('hide');
    });
    return false;
  }
</script>
</body>
</html>
