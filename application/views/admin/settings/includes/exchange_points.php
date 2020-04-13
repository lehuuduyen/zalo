<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-5">
  <?php echo render_input('settings[exchange_amount]','exchange_amount_value',get_option('exchange_amount'),'text',array('onkeyup'=>'formatNumBerKeyUp(this)')); ?>
</div>
<div class="col-md-2 text-center mtop15">
  <span style="font-weight: bold; font-size: 35px;">&#8644;</span>
</div>
<div class="col-md-5">
  <?php echo render_input('settings[exchange_points]','exchange_points_value',get_option('exchange_points'),'text',array('onkeyup'=>'formatNumBerKeyUp(this)')); ?>
</div>
<div class="clearfix"></div>
<hr />