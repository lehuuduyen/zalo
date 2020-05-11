  <style type="text/css">
    .img_ch{
      height: 20px;
      width: 20px;
    }
  </style>
  <div class="modal fade in" id="views_purchases" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="false" style="display: block;"><div class="modal-dialog modal-lg no-modal-header" style="width: 80%;">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
              <span class="book-title"><?php echo _l('ch_purchases_detail'); ?> </span>
            </h4>
          </div>
          <div class="modal-body">
            <div class="row">
                  <div class="col-md-6  pull-left">
                      <div class="panel panel-success">
                      <?php 
                      $type = '';
                      if (!isset($purchase))
                        $type = 'warning';
                      elseif ($purchase->status == 1)
                        $type = 'warning';
                      elseif ($purchase->status == 2)
                        $type = 'danger';
                      elseif ($purchase->status == 3)
                        $type = 'success';

                      ?>
                      <div style="right: 10px;" class="ribbon <?= $type ?>" project-status-ribbon-2="">
                        <?php 
                          if (isset($purchase))
                            {
                            $status = format_purchase_status($purchase->status, '', false);
                          }
                          else
                            {
                            $status = format_purchase_status(-1, '', false);
                          }
                          ?>
                        <span><?= $status ?></span>
                      </div>
                          <div class="panel-heading">
                              <h3 class="panel-title">Thông tin</h3>
                          </div>
                          <div class="panel-body">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-6">
                                            <div><b><?=_l('ch_code_p')?>: </b><?php echo $purchase->prefix.$purchase->code ?></div>
                                            <div><b><?=_l('ch_date_p')?>: </b><?php echo _dt($purchase->date)?></div>
                                            <div><b><?=_l('ch_name_p')?>: </b><?php echo $purchase->name_purchase?></div>
                                            <div><b><?=_l('ch_note_t')?>: </b><?php echo $purchase->explanation?></div>
                                        <p></p>
                                    </div>
                                    <div class="col-md-6">
                                            <div><b><?=_l('ch_staff_p')?>:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo staff_profile_image($purchase->staff_create, array('staff-profile-image-small mright5 img_ch'), 'small', array(
                                                  'data-toggle' => 'tooltip',
                                                  'data-title' => get_staff_full_name($purchase->staff_create)
                                              )).get_staff_full_name($purchase->staff_create)?></b></div>
                                            
                                            <?php
                                                $history_status = explode('|',$purchase->history_status);
                                                foreach ($history_status as $key => $value) {
                                                    $data=explode(',',$value);
                                                    if(is_numeric($data[0]))
                                                    {
                                                    if($key == 1)
                                                    {
                                                        ?>
                                                        </p><div><b><?=_l('ch_status_confirm')?>: <?php echo staff_profile_image($data[0], array('staff-profile-image-small mright5 img_ch'), 'small', array(
                                                              'data-toggle' => 'tooltip',
                                                              'data-title' => ' Vào lúc: '._dt($data[1])
                                                          )).get_staff_full_name($data[0])?></b></div>
                                                        <?php 
                                                    }elseif($key == 2)
                                                    {
                                                       ?>
                                                       </p><div><b> <?=_l('ch_status_import')?>:&emsp;&nbsp;&nbsp;&nbsp;<?php echo staff_profile_image($data[0], array('staff-profile-image-small mright5 img_ch'), 'small', array(
                                                            'data-toggle' => 'tooltip',
                                                            'data-title' => ' Vào lúc: '._dt($data[1])
                                                        )).get_staff_full_name($data[0])?></b></div>
                                                       <?php 
                                                    }
                                                    }
                                                } ?>
                                        <p></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="table-responsive">
                  <table id="view-enquiry" class="table table-bordered table-hover table-striped table-color" style="width: 100%; margin-bottom: 5px;table-layout: fixed;">
                      <thead>
                          <tr>
                              <th class="border-top text-center"style="width: 50px;"><?=_l('#')?></th>
                              <th class="border-top text-center" style="width: 100px;"><?=_l('ch_image')?></th>
                              <th class="border-top text-center" style="width: 250px;"><?=_l('ch_items_name_t')?></th>
                              <th class="border-top text-center" style="width: 100px;"><?=_l('ch_color')?></th>
                              <th class="border-top text-center" style="width: 100px;"><?=_l('item_unit')?></th>
                              <th class="border-top text-center" style="width: 100px;"><?=_l('item_quantity_all')?></th>
                              <th class="border-top text-center" style="width: 100px;"><?=_l('item_quantity_confirm')?></th>
                              <th class="border-top text-center" style="width: 200px;"><?=_l('note')?></th>
                          </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $totalQuantity_approve=0;
                        $totalQuantity=0;
                        if(isset($purchase->items)&&(count($purchase->items)>0)){
                          
                          ?>
                        <?php foreach ($purchase->items as $key => $value) { ?>
                          <tr >
                              <td class="text-center border-left"><?=($key+1)?></td>
                              <td class="text-center"><img class="mbot5" style="border-radius: 50%;width: 4em;height: 4em;" src="<?=(!empty($value['avatar']) ? (file_exists($value['avatar']) ? base_url($value['avatar']) : (file_exists('uploads/materials/'.$value['avatar']) ? base_url('uploads/materials/'.$value['avatar']) : (file_exists('uploads/products/'.$value['avatar']) ? base_url('uploads/products/'.$value['avatar']) : base_url('assets/images/preview-not-available.jpg')))):base_url('assets/images/preview-not-available.jpg'))?>"><br>
                              <?=format_item_purchases($value['type'])?></td>
                              <td><div><?=($value['name_item'])?> (<?=$value['code_item']?>)</div></td>
                              <td class="text-center"><?=format_item_color($value['product_id'],$value['type'],1)?></td>
                              <td class="text-center"><div><?=($value['unit'])?></div></td>
                              <td class="text-center"><?=(number_format($value['quantity']))?></td>
                              <td class="text-center"><?=(number_format($value['quantity_net']))?></td>
                              <td class="text-center"><?=$value['note']?></td>
                          </tr>
                        <?php 
                        $totalQuantity_approve+=$value['quantity_net'];
                        $totalQuantity+=$value['quantity'];
                        }} ?>
                    </tbody>
                  </table>
              </div>
              <div id="bottom-total" class="well well-sm" style="margin-bottom: 5px;">
                  <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
                      <tbody>
                        <tr class="success">
                          <td><?=_l('item_quantity_all')?>:<span class="pull-right"><?=$totalQuantity?></span></td>
                          <td><?=_l('item_quantity_approve')?>:<span class="pull-right"><?=$totalQuantity_approve?></span></td>
                    </tr>
                    </tbody>
                  </table>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Thoát</button>
              </div>
          </div>
      </div>
  </div>
  <script type="text/javascript">
      $(document).ready( function() {
          $('.tip').tooltip();
          // $('#view-enquiry').dataTable();
      });
  </script>

  </div>