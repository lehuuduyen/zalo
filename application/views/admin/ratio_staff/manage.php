<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="panel_s mbot10 H_scroll" id="H_scroll">
      <div class="panel-body _buttons">
         <div class="_buttons">
            <span class="bold uppercase fsize18 H_title"><?=!empty($title) ? $title : ''?></span>
            <?php if (has_permission('orders','','create')) { ?>
            <a href="<?=admin_url('ratio_staff/detail')?>"  class="btn btn-info mright5 test pull-right H_action_button">
               <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
               <?php echo _l('create_add_new_ratio_staff'); ?></a>
            <?php } ?>
            <div class="clearfix"></div>
         </div>
      </div>
   </div>
    <div class="content">
        <div class="row">
            <!-- tab tổng -->
            <div class="wap-tab active" id="tab_info">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix mtop20"></div>
                        <?php $table_data = array(
                            _l('cong_name_ratio'), //code
                            _l('cong_month_year'), //tên hệ thống
                            _l('cong_create_by'), // zcode
                            _l('cong_date_create'), //ngaft c
                            _l('ch_option'),
                        );
                        render_datatable($table_data, 'ratio_staff table-bordered');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
var tAPI;
var tAPI1;
$(function(){
    var CustomersServerParams = {
      'filterStatus' : '[name="filterStatus"]',
    };
    var tAPI = initDataTable('.table-ratio_staff', admin_url+'ratio_staff/table', [0], [0], CustomersServerParams, ['1','desc'], fixedColumns = {leftColumns: 4, rightColumns: 0});
    $.each(CustomersServerParams, function(filterIndex, filterItem){
          $(filterItem).on('change', function(){
                tAPI.draw('page');
          });
    });
});
</script>
