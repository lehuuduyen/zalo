<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <?php if (has_permission('suppliers','','create')) { ?>
                                <a href="<?php echo admin_url('suppliers/supplier'); ?>" class="btn btn-info mright5 test pull-left display-block">
                                    <?php echo _l('Thêm mới nhà cung cấp'); ?>
                                </a>
                            <?php } ?>
                                    <div class="visible-xs">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <?php if(has_permission('suppliers','','view')) {
                                    $where_summary = '';
                                    ?>
                                    <hr />
                                    <div class="row mbot15">
                                        <div class="col-md-12">
                                            <h3 class="text-success no-margin"><?php echo _l('Thống kê nhà cung cấp'); ?></h3>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo total_rows('tblsuppliers',''); ?></h3>
                                            <span class="text-dark"><?php echo _l('Tổng nhà cung cấp'); ?></span>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo total_rows('tblsuppliers','active = 1'); ?></h3>
                                            <span class="text-success"><?php echo _l('Nhà cung cấp đang hoạt động'); ?></span>
                                        </div>
                                        <div class="col-md-2 col-xs-6 border-right">
                                            <h3 class="bold"><?php echo total_rows('tblsuppliers','active = 0'); ?></h3>
                                            <span class="text-danger"><?php echo _l('Nhà cung cấp không hoạt động'); ?></span>
                                        </div>
                                        
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="_filters _hidden_inputs hidden">
                            <?php
                             foreach($groups as $group){
                                 echo form_hidden('customer_group_'.$group['id']);
                             }
                             foreach($contract_types as $type){
                                 echo form_hidden('contract_type_'.$type['id']);
                             }
                             foreach($invoice_statuses as $status){
                                 echo form_hidden('invoices_'.$status);
                             }
                             foreach($estimate_statuses as $status){
                                 echo form_hidden('estimates_'.$status);
                             }
                             foreach($project_statuses as $status){
                                echo form_hidden('projects_'.$status);
                            }
                            foreach($proposal_statuses as $status){
                                echo form_hidden('proposals_'.$status);
                            }
                            foreach($customer_admins as $cadmin){
                                echo form_hidden('responsible_admin_'.$cadmin['staff_id']);
                            }
                            ?>
                        </div>
                        <div class="panel_s">
                            <div class="panel-body">
                               <a href="#" data-toggle="modal" data-target="#customers_bulk_action" class="btn btn-info mbot15"><?php echo _l('bulk_actions'); ?></a>
                               <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                   <div class="modal-content">
                                      <div class="modal-header">
                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                         <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                                     </div>
                                     <div class="modal-body">
                                      <?php if(has_permission('customers','','delete')){ ?>
                                      <div class="checkbox checkbox-danger">
                                        <input type="checkbox" name="mass_delete" id="mass_delete">
                                        <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                    </div>
                                    <hr class="mass_delete_separator" />
                                    <?php } ?>
                                    <div id="bulk_change">
                                     <?php echo render_select('move_to_groups_customers_bulk[]',$groups,array('id','name'),'customer_groups','', array('multiple'=>true),array(),'','',false); ?>
                                     <p class="text-danger"><?php echo _l('bulk_action_customers_groups_warning'); ?></p>
                                 </div>
                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                                 <a href="#" class="btn btn-info" onclick="customers_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                             </div>
                         </div><!-- /.modal-content -->
                     </div><!-- /.modal-dialog -->
                 </div><!-- /.modal -->

                 <div class="clearfix"></div>
                 <?php
                 
                 $table_data = array();
                 $_table_data = array(
                    _l('clients_list_company'),
                    _l('contact_primary'),
                    _l('company_primary_email'),
                    _l('clients_list_phone'),
                    _l('customer_active')
                    );
                 foreach($_table_data as $_t){
                    array_push($table_data,$_t);
                }
                array_unshift($table_data,'<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label></label></div>');
                $_op = _l('options');

                array_push($table_data, $_op);
                render_datatable($table_data,'clients');
                ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>





<?php init_tail(); ?>
<script>
    var CustomersServerParams = {};
    $.each($('._hidden_inputs._filters input'),function(){
     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
 });
    var headers_clients = $('.table-clients').find('th');
    var not_sortable_clients = (headers_clients.length - 1);
    initDataTable('.table-clients', window.location.href, [not_sortable_clients,0], [not_sortable_clients,0], CustomersServerParams,[1,'asc']);

    function customers_bulk_action(event) {
        var r = confirm(confirm_action_prompt);
        if (r == false) {
            return false;
        } else {
            var mass_delete = $('#mass_delete').prop('checked');
            var ids = [];
            var data = {};
            if(mass_delete == false || typeof(mass_delete) == 'undefined'){
                data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
                if (data.groups.length == 0) {
                    data.groups = 'remove_all';
                }
            } else {
                data.mass_delete = true;
            }
            var rows = $('.table-clients').find('tbody tr');
            $.each(rows, function() {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') == true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            $(event).addClass('disabled');
            setTimeout(function(){
              $.post(admin_url + 'clients/bulk_action', data).done(function() {
                 window.location.reload();
             });
          },50);
        }
    }


</script>
</body>
</html>
