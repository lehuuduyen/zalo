<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" href="<?=base_url('assets/treegrid/')?>css/jquery.treegrid.css">

<div id="wrapper">
   <div class="panel_s mbot10 H_scroll" id="H_scroll">
      <div class="panel-body _buttons">
         <div class="_buttons">
            <span class="bold uppercase fsize18 H_title"><?=$title?></span>
            <?php if (has_permission('categories','','create')) { ?>
              <a href="#"  onclick="new_category(); return false;" class="btn btn-info mright5 test pull-right H_action_button">
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
                      <table class="table tree">
                          <thead>
                              <th><?=_l('ch_categories_name')?></th>
                              <th><?=_l('ch_categories_level')?></th>
                              <th><?=_l('leads_dt_assigned')?></th>
                              <th><?=_l('proposal_date_created')?></th>
                              <th><?=_l('ch_option')?></th>
                          </thead>
                          <tbody>
                          <?php get_categories_type_client($full_categories);?>
                          </tbody>
                      </table>
                  </div>
              </div>          
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="type_client" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('categories/add_category'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('cong_type_client_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('cong_type_client_add_heading'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name','cong_name_type_client'); ?>
                        <?php echo render_select('category_parent', $categories, array('id', 'name'), 'ch_categories_parent'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript" src="<?=base_url('assets/treegrid/')?>js/jquery.treegrid.js"></script>
<script type="text/javascript">
  $('.tree').treegrid({
    initialState: 'collapsed',
  });
</script>
<script>
    function delete_category(id="")
    {
      $.ajax({
        url: '<?php echo admin_url('type_clients/delete_type_client/') ?>' + id,
        dataType: 'json',
      }).done((data)=>{
          if(data.success == true) {
            alert_float('success',data.message);
            location.reload();
          }
          else {
            alert_float('danger',data.message);
          }
      });
    } 
    $(function(){
      appValidateForm($('#delete_type'),{id_new:'required'},manage_delete_types);
    });
    function manage_delete_types(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
                location.reload();
            }
            $('#id_type').modal('hide');
        });
        return false;
    }
    function edit_category(category_id,category_name,parent_id){
        $('#type_client').modal('show');
        $('.edit-title').removeClass('hide');
        $('.add-title').addClass('hide');
        $('#additional').append(hidden_input('id',category_id));
        $('#type_client input[name="name"]').val(category_name);
        $('#type_client').find('#category_parent').selectpicker('val',parent_id);
        jQuery('#id_type').prop('action',admin_url+'type_clients/update_category/'+category_id);
    }
    function new_category(){
        $('#type_client').modal('show');
        $('.edit-title').addClass('hide');
        jQuery('#name').val('');
        $('#type_client').find('#category_parent').selectpicker('val','');
        jQuery('#id_type').prop('action',admin_url+'type_clients/add_category');
    }
    $(function(){
      appValidateForm($('#id_type'),{name:'required'},manage_contract_types);
        $('#id_type').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#type_client input').val('');
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
                location.reload();
            }
            $('#id_type').modal('hide');
        });
        return false;
    }
  $(document).on('click', '.delete-remind', function() {
      var r = confirm("<?php echo _l('confirm_action_prompt');?>");
      if (r == false) {
        return false;
      } else {
        $.get($(this).attr('href'), function(response) {
          alert_float(response.alert_type, response.message);
            $('.table-suppliers').DataTable().ajax.reload();
            $('.table-contacts').DataTable().ajax.reload();
          }, 'json');
      }
      return false;
    });
</script>
</body>
</html>
