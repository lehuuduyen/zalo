<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo basename(__FILE__,".php"); ?>" data-name="<?php echo _l('user_widget'); ?>">
   <div class="panel_s user-data">
      <div class="panel-body">
         <div class="widget-dragger"></div>
         <div class="panel panel-success">
            <div class="panel-heading"><?=_l('official_client')?></div>
            <div class="panel-body">
               <div>
                  <img src="<?=base_url('uploads/client.png')?>" width="35" height="35">
               </div>
               <div class="primary font_2em">
                   <?php $customer = get_table_where(db_prefix().'clients', ['active' => 1],'','row','','count(userid) as maxid'); ?>
                   <?=number_format($customer->maxid)?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
