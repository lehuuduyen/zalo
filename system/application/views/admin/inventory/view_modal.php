    <style type="text/css">
    .img_ch{
      height: 20px;
      width: 20px;
    }
  </style>
  <div class="modal fade in" id="view_inventory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
              <span class="book-title"><?php echo _l('Thông tin phiếu kiểm kê kho'); ?> </span>
            </h4>
          </div>
          <div class="modal-body">
            <div class="row">
                  <div class="col-md-6  pull-left">
                      <div class="panel panel-success">
                     <!--    <?php 
                      $type = '';
                      if (!isset($items))
                        $type = 'warning';
                      elseif ($items->status == 1)
                        $type = 'warning';
                      elseif ($items->status == 2)
                        $type = 'danger';
                      elseif ($items->status == 3)
                        $type = 'success';

                      ?>
                      <div style="right: 10px;" class="ribbon <?= $type ?>" project-status-ribbon-2="">
                        <?php 
                          if (isset($items))
                          {
                            $status = format_status_inventory($items->status, '', false);
                          }
                          else
                          {
                            $status = format_status_inventory(-1, '', false);
                          }
                          ?>
                        <span><?= $status ?></span>
                      </div> -->
                          <div class="panel-heading">
                              <h3 class="panel-title">Thông tin</h3>
                          </div>
                          <div class="panel-body">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-6">
                                      <div><?=format_purchase_order_father($items->id,'',true,'12px')?></div>
                                        <div>
                                          <b><?=_l('ch_code_p')?>: </b><?php echo $items->prefix.$items->code ?></div>
                                            <div><b><?=_l('ch_staff_crate_rfq')?>: </b><?php echo staff_profile_image($items->staff_id, array('staff-profile-image-small mright5 img_ch'), 'small', array(
                                                  'data-toggle' => 'tooltip',
                                                  'data-title' => get_staff_full_name($items->staff_id)
                                              )).get_staff_full_name($items->staff_id)?></div>
                                            <div><b><?=_l('ch_date_p')?>: </b><?php echo _d($items->date)?></div>
                                            
                                        <p></p>
                                    </div>
                                    <div class="col-md-6">
                                      <div><b><?=_l('warehouse')?>: </b><?php echo $items->namewareide?></div>
                                    <div><b><?=_l('ch_note_t')?>: </b><?php echo $items->note?></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                          </div>
                      </div>
                  </div>
                  <?php
                    $customer_custom_fields = false;
                    if(total_rows(db_prefix().'customfields',array('fieldto'=>'imports','active'=>1)) > 0 ){
                         $customer_custom_fields = true;
                       }
                    ?>
                <?php if($customer_custom_fields) { ?>
                <div class="col-md-6  pull-left">
                      <div class="panel panel-info">
                      
                          <div class="panel-heading">
                              <h3 class="panel-title"><?php echo _l('custom_fields'); ?></h3>
                          </div>
                      </div>
                  </div>
                  <?php } ?>
              </div>
              <?php 
              $totalQuantity = 0;
              $total = 0;
              $totalQuantity_time = 0;
              if(isset($items->items)&&(count($items->items)>0)){ ?>
              <div class="table-responsive">
                  <table id="view-enquiry" class="table table-bordered table-hover table-color layout-fixed" width="2000">
                      <thead>
                          <tr>
                            <th class="text-center"><?=_l('image')?><input type="hidden" id="itemID" value="" /></th>
                            <th style="width: 200px" class="text-center"><?php echo _l('ch_items_name_t'); ?></th>
                            <th class="text-center"><?php echo _l('item_unit'); ?></th>
                            <th class="text-center"><?php echo _l('warehouse_localtion'); ?></th>
                            <th class="text-center"><?php echo _l('item_quantity'); ?></th>
                            <th class="text-center"><?php echo _l('Số lượng thực'); ?></th>
                            <th class="text-center"><?php echo _l('Chênh lệch'); ?></th>  
                            <th class="text-center"><?php echo _l('Xử lý'); ?></th>  
                          </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($items->items as $key => $value) { ?>
                          <tr>
                            <td class="center">
                              <img style="border-radius: 50%;width: 4em;height: 4em;" src="<?=(!empty($value['avatar'])?(file_exists($value['avatar']) ? base_url($value['avatar']) : (file_exists('uploads/materials/'.$value['avatar']) ? base_url('uploads/materials/'.$value['avatar']) : (file_exists('uploads/products/'.$value['avatar']) ? base_url('uploads/products/'.$value['avatar']) : base_url('assets/images/preview-not-available.jpg')))):base_url('assets/images/preview-not-available.jpg'))?>">
                              <br>
                              <?=format_item_purchases($value['type'])?>
                            </td>
                            <td>
                                <?php echo $value['name_item'].' ('.$value['code_item'].')'; ?><br><?=format_item_color($value['product_id'],$value['type'])?>
                            </td>
                            <td>
                                <?php echo $value['unit']; ?>
                            </td>
                            <td>
                                <?php echo $value['localtion_name_id']; ?>
                            </td>
                            <td class="center">
                                <?php echo number_format($value['quantity']); ?>
                            </td>
                            <td class="center">
                                <?php echo number_format($value['quantity_net']); ?>
                            </td>
                            <td class="center">
                              
                              <?php echo number_format($value['quantity']+$value['quantity_net']); 
                              $totalQuantity_time+= $value['quantity']+$value['quantity_net'];
                              ?> 
                            </td>
                            <td >
                                <?php echo $value['handling']; ?>
                            </td>
                          </tr>
                        <?php 
                        $totalQuantity+= $value['quantity'];
                        $total+= $value['quantity_net'];
                        
                      } ?>
                    </tbody>
                  </table>
              </div>
            <?php }?>
              <div id="bottom-total" class="well well-sm" style="margin-bottom: 5px;">
                  <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
                      <tbody>
                        <tr class="success">
                          <td><?=_l('item_quantity_all')?>:<span class="pull-right"><?=number_format($totalQuantity)?></span></td>
                          <td><?=_l('Tổng số lượng thực')?>:<span class="pull-right"><?=number_format($total)?></span></td>
                          <td><?=_l('Chênh lệch')?>:<span class="pull-right"><?=number_format($totalQuantity_time)?></span></td>
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
      });
      $('body').on('hidden.bs.modal', '#view_adjusted', function() {
      $('#import_data').html('');
      tAPI.draw('page');
      });
  </script>

  </div>