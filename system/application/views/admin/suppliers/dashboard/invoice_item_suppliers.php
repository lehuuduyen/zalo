<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo basename(__FILE__,".php"); ?>" data-name="<?php echo _l('user_widget'); ?>">
   <div class="panel_s user-data">
      <div class="panel-body">
         <div class="widget-dragger"></div>
         <div class="panel panel-primary">
            <div class="panel-heading"><?=_l('invoice_in_month')?></div>
            <div class="panel-body">
               <div>
                  <img src="<?=base_url('uploads/client.png')?>" width="35" height="35">
               </div>
               <div class="font_2em">
                  <?=number_format(9999);?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
