        <?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
        <?php init_head(); ?>
        <link rel="stylesheet" href="<?=base_url('assets/treegrid/')?>css/jquery.treegrid.css">
        <div id="wrapper">
           <div class="panel_s mbot10 H_scroll" id="H_scroll">
              <div class="panel-body _buttons">
                 <div class="_buttons">
                    <span class="bold uppercase fsize18 H_title"><?=$title?></span>
                    <?php if (is_admin()) { ?>
                    <div class="line-sp"></div>
                    <a href="" onclick="new_costs(); return false;" class="btn btn-info mright5 test pull-right H_action_button">
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
                              <th><?=_l('#')?></th>
                              <th><?=_l('ch_code_costs')?></th>
                              <th><?=_l('ch_name_costs')?></th>
                              <th><?=_l('ch_levers')?></th>
                              <th><?=_l('ch_option')?></th>
                          </thead>
                          <tbody>
                          <?php get_costs($full_costs);?>
                          </tbody>
                      </table>
                    </div>
                 </div>
              </div>
           </div>
        </div>
        <div class="modal fade" id="type" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <?php echo form_open(admin_url('financial_control/add'),array('id'=>'id_type')); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">
                            <span class="edit-title"><?php echo _l('ch_edit'); ?></span>
                            <span class="add-title"><?php echo _l('ch_add'); ?></span>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div id="additional"></div>
                            <div class="col-md-12">
                                <?php echo render_input('code','ch_code_costs','','',array('autocomplete'=>'off')); ?>
                            </div>
                            <div class="col-md-12">
                                <?php echo render_input('name','ch_name_costs','','',array('autocomplete'=>'off')); ?>
                            </div>
                            <div class="col-md-12">
                                <?php echo render_select('costs_parent',$costs, array('id', 'name'), 'ch_chose_parent'); ?>
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
        <div class="modal fade" id="modal_delete_category" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <?php echo form_open(admin_url('costs/delete_costs'),array('id'=>'delete_type')); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">
                            <span class="delete-title"><?php echo _l('Xóa loại'); ?></span>
                            
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="id" id="id_delete"/>
                            <p class="text-danger"><?php echo _l('Khi xóa thì các danh mục con sẽ được chuyển cho danh mục cha cùng cấp'); ?></p>

                            </div>
                            <div class="col-md-12">
                                <?php echo render_select('id_new','', array('id', 'category'), 'Danh mục cha'); ?>
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
        <script type="text/javascript" src="<?=base_url('assets/treegrid/')?>js/jquery.treegrid.js"></script>
        <script type="text/javascript">
          $('.tree').treegrid({
            initialState: 'collapsed',
          });
        </script>
        <script>
          $(function(){
            _validate_form($('form'),{code:'required',name:'required'},manage_costs);
            function manage_costs(form) {
                var data = $(form).serialize();
                var url = form.action;
                $.post(url, data).done(function(response) {
                    response = JSON.parse(response);
                    if(response.success == true){
                        alert_float('success',response.message);
                    }
                    location.reload();
                    $('#type').modal('hide');
                });
                return false;
            }            
          });
          function delete_costs(id="")
          {
              if(id!="")
              {
                $.ajax({
                        url : admin_url + 'costs/get_exsit/' + id ,
                        dataType : 'json',
                    })
                    .done(function(data){

                        $.each(data, function(key,value){
                            id_new.append('<option value="' + value.id +'">' + value.vallue + '</option>');
                        });

                        id_new.selectpicker('refresh');
                });
              }
              return false;
          }           
          function new_costs(){
              $('#type').modal('show');
              $('.edit-title').addClass('hide');
              jQuery('#name').val('');
              jQuery('#id_type').prop('action',admin_url+'costs/add');
          }
          function edit_costs(id,code,name,parent_id){
              $('#type').modal('show');
              $('.edit-title').removeClass('hide');
              $('.add-title').addClass('hide');
              $('#additional').append(hidden_input('id',id));
              $('#type input[name="code"]').val(code);
              $('#type input[name="name"]').val(name);
              $('#type').find('#costs_parent').selectpicker('val',parent_id);
              jQuery('#id_type').prop('action',admin_url+'costs/update/'+id);
              var costs_parent=$('#costs_parent');
              costs_parent.find('option:gt(0)').remove();
              costs_parent.selectpicker('refresh');
              if(costs_parent.length) {
                  $.ajax({
                      url : admin_url + 'costs/get_parent/' + id ,
                      dataType : 'json',
                  })
                  .done(function(data){

                      $.each(data.data, function(key,value){
                          var text = '';
                          if(data.costs_parent == value.id)
                          {
                              text='selected="selected"';
                          }
                          costs_parent.append('<option '+text+' value="' + value.id +'">' + value.name + '</option>');
                      });

                      costs_parent.selectpicker('refresh');
                  });
              }
          }
        </script>
