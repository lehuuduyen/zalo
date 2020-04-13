  <style>
      img.c_img_item_30{
          border-radius: 50%;
          width: 30px;
          height: 30px;
      }
      .table-view_order tbody td{
          border-right: 2px solid!important;
      }
      .table-view_order tbody td:last-child{
          border-right: none!important;
      }
      .ribbon span{
         font-size: 7px;
      }
      #views_orders .well{
          margin-bottom: 0px;
      }
      .wap-left-title {
        float: left;
        width: 30%;
      }
      .wap-left-content {
        float: left;
        width: calc(70% - 5px);
      }
      .mright15 {
        margin-right: 15px !important;
      }
      .mleft15 {
        margin-left: 15px !important;
      }
      .wap-content.second {
        background: #aac9e7 !important;
      }
      .wap-title {
        background: #68b8d4;
        color: #fff;
        padding: 10px 5px;
      }
  </style>
  <?php if(empty($view_not_modal)){?>
  <div class="modal fade in" id="views_orders" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="false" style="display: none;">
      <div class="modal-dialog modal-lg no-modal-header" style="width: 80%;">
  <?php } else {
	  init_not_head();
  } ?>

          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close <?= !empty($view_not_modal) ? 'hide' : '' ?>" " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                  </button>
                  <?php
                      $type = '';
                      if (!isset($orders))
                          $type = 'info';
                      elseif ($orders->status == -2)
                          $type = 'warning';
                      elseif ($orders->status == -3)
                          $type = 'danger';
                      elseif ($orders->status > 0)
                          $type = 'primary';
                      elseif ($orders->status == 0)
                          $type = 'primary';
                      elseif ($orders->status == -1)
                          $type = 'success';
                  ?>
                  <h4 class="modal-title">
                      <span class="book-title"><?= !empty($title) ? $title : '' ?> <b
                                  class="text-<?= $type ?>">(<?= !empty($orders) ? $orders->name_status : ''; ?>) </b></span>
                  </h4>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="panel panel-primary">
                              <div class="ribbon <?= $type ?>">
                                  <span><?= $orders->name_status ?></span>
                              </div>
                              <div class="panel-heading">
                                  <h3 class="panel-title">
                                      <?= _l('cong_infomation_orders') ?>
                                  </h3>
                              </div>
                              <div class="panel-body padding-0">
                                  <div class="row">
                                      <div class="mright15 mleft15">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_code_orders') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->prefix . $orders->code) ? $orders->prefix . $orders->code : '-') ?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>

                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_customer_orders') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->name_system) ? $orders->name_system : '-') ?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>

                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_code_advisory_lead') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->fullcode_advisory_lead) ? $orders->fullcode_advisory_lead : '-') ?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>

                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_note') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->note) ? $orders->note : '-') ?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>

                                  </div>
                              </div>
                          </div>

                          <div class="panel panel-primary">
                              <div class="panel-heading">
                                  <h3 class="panel-title">
                                      <?= _l('cong_infomation_contact_shipping') ?>
                                  </h3>
                              </div>
                              <div class="panel-body padding-0">
                                  <div class="row">
                                      <div class="mright15 mleft15">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_name_shipping') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->name_shipping) ? $orders->name_shipping : '-') ?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>
                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_phone') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->phone_shipping) ? $orders->phone_shipping : '-')?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>

                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_address') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->address_shipping) ? $orders->address_shipping : '-')?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>
                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_unit_ship') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php if(!empty($orders->unit_ship)){?>
	                                              <?php
                                                    $unit_ship = get_table_where('tblcombobox_client', ['id' => $orders->unit_ship], '' , 'row');
	                                                echo(!empty($unit_ship->name) ? $unit_ship->name : '-')
	                                              ?>
                                              <?php } ?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>

                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_code_ship') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php
                                                    echo(!empty($orders->code_ship) ? $orders->code_ship : '-')
                                              ?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>

                                  </div>
                              </div>
                          </div>

                          <div class="panel panel-primary">
                              <div class="panel-heading">
                                  <h3 class="panel-title"><?= _l('cong_infomation_receipts_orders') ?></h3>
                              </div>
                              <div class="panel-body">
                                  <div class="row">
                                      <div class="table_popover padding10">
                                          <div class="table_popover_head text-center">
                                              <div class="pull-left wap-head">
                                                  <span class="text-center"><?=_l('ch_code_number')?></span>
                                              </div>
                                              <div class="pull-left wap-head">
                                                  <span class="text-center"><?=_l('date')?></span>
                                              </div>
                                              <div class="pull-left wap-head">
                                                  <span class="text-center"><?=_l('als_staff')?></span>
                                              </div>
                                              <div class="pull-left wap-head">
                                                  <span class="text-center"><?=_l('ch_value')?></span>
                                              </div>
                                              <div class="clearfix"></div>
                                          </div>
                                          <div class="table_popover_body text-center">
                                            <?php $total_payment = 0; ?>
                                            <?php foreach ($payment as $kPay => $vPay) { ?>
                                              <div class="wap-body view_payment" data-id="<?=$vPay['id']?>">
                                                  <span class="text-center pull-left"><?=$vPay['prefix'].$vPay['code']?></span>
                                                  <span class="text-center pull-left"><?=_dt($vPay['date'])?></span>
                                                  <span class="text-center pull-left"><?=get_staff_full_name($vPay['staff_id'])?></span>
                                                  <span class="text-right pull-left"><?=number_format($vPay['total_voucher'])?></span>
                                                  <div class="clearfix"></div>
                                              </div>
                                              <?php $total_payment += $vPay['total_voucher']; ?>
                                            <?php } ?>
                                          </div>
                                          <div class="table_popover_body text-center">
                                            <div class="wap-body" style="font-weight:bold;color:red">
                                                <span class="text-center pull-left">
                                                    <?=_l('cong_total')?>
                                                </span>
                                                <span class="text-center pull-left"></span>
                                                <span class="text-center pull-left"></span>
                                                <span class="text-right pull-right"><?=number_format($total_payment)?></span>
                                                <div class="clearfix"></div>
                                            </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="panel panel-primary">
                              <div class="panel-heading">
                                  <h3 class="panel-title"><?= _l('cong_infomation_client') ?></h3>
                              </div>
                              <div class="panel-body padding-0">
                                  <div class="row">
                                      <div class="mright15 mleft15">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_code_system') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->code_system) ? $orders->code_system : '-')?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>
                                      
                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_code_lead') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->full_code_lead) ? $orders->full_code_lead : '-')?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>
                                      
                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_code_client') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->full_code_client) ? $orders->full_code_client : '-')?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>
                                      
                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('code_client_now') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->full_code_client) ? $orders->full_code_client : '-')?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>
                                      
                                      <div class="mright15 mleft15 mtop5">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_zcode') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo (!empty($orders->zcode) ? $orders->zcode : '-')?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="panel panel-primary">
                              <div class="panel-heading">
                                  <h3 class="panel-title"><?= _l('cong_time_action_manage_orders') ?></h3>
                              </div>
                              <div class="panel-body padding-0">
                                  <div class="row">
                                      <div class="mright15 mleft15">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_date_create_orders') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo !empty($orders->date_create) ? _dt($orders->date_create) : '-' ?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="panel panel-primary">
                              <div class="panel-heading">
                                  <h3 class="panel-title"><?= _l('cong_staff_manage_orders') ?></h3>
                              </div>
                              <div class="panel-body padding-0">
                                  <div class="row">
                                      <div class="mright15 mleft15">
                                          <div class="wap-content second wap-left-title">
                                              <b><?= _l('cong_staff_create_orders') ?>: </b>
                                          </div>
                                          <div class="mleft5 wap-content firt wap-left-content">
                                              <?php echo !empty($orders->create_by) ? get_staff_full_name($orders->create_by) : '-' ?>
                                          </div>
                                          <div class="clearfix"></div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="col-md-12">
                          <div class="table-responsive">
                              <table class="table table-bordered table-hover table-striped table-color dont-responsive-table"
                                     style="width: 100%; margin-bottom: 5px;">
                                  <thead>
                                      <tr>
                                          <th><?= _l('status_item_order') ?></th> <!--Tình trạng đơn hàng-->
                                          <th><?= _l('priority_level') ?></th> <!--Mức độ ưu tiên-->
                                          <th><?= _l('cong_item_code') ?></th> <!--mã hàng-->
                                          <th><?= _l('cong_item_image') ?></th> <!--Hình ảnh-->
                                          <th><?= _l('cong_item_name') ?></th> <!--Tên hàng-->
                                          <th><?= _l('cong_quantity') ?></th> <!--Số lượng -->
                                          <th><?= _l('cong_price_thinh_html') ?></th> <!--Giá thỉnh-->
                                          <th><?= _l('cong_discount') ?></th> <!--Chiết khấu-->
                                          <th><?= _l('cong_info_money') ?> (VND)</th> <!--Thành tiền-->
                                          <th><?= _l('cong_buy_gif') ?></th> <!--Thành tiền-->
                                          <th><?= _l('cong_size') ?></th>
                                          <th class="minwidth150px"><?= _l('cong_shipment_date') ?>
                                      </tr>
                                  </thead>
                                  <tbody>
				                  <?php foreach ($orders->detail as $key => $Vorder) { ?>
                                      <tr>
                                          <td class="<?=$Vorder->id?>">
							                  <?php $orders_step = get_table_where('tblorders_step', ['id_orders_item' => $Vorder->id, 'active' => 1], 'order_by desc', 'row');?>
							                  <?= (!empty($orders_step->name_procedure) ? $orders_step->name_procedure : '') ?>
                                          </td>
                                          <td>
							                  <?php
							                  $step_order_by = (!empty($orders_step->order_by) ? $orders_step->order_by : '0');
							                  echo GetPriority_order($step_order_by , $orders->id, $Vorder->id );
							                  ?>
                                          </td>
                                          <td>
                                              <p class="text-left"><?= $Vorder->code_items ?></p>
							                  <?php if ($Vorder->type_items == "items"): ?>
                                                  <span class="label label-success"><?= lang('ch_items') ?></span>
							                  <?php elseif ($Vorder->type_items == "products"): ?>
                                                  <span class="label label-warning"><?= lang('tnh_products') ?></span>
							                  <?php endif ?>
                                          </td>
                                          <td>
                                              <p class="text-center">
	                                              <?php $Vorder->avatar = !empty($Vorder->avatar) ? $Vorder->avatar : 'assets/images/preview-not-available.jpg';?>
                                                  <img src="<?= base_url($Vorder->avatar); ?>" class="c_img_item_30">
                                              </p>
                                          </td>
                                          <td>
                                              <p class="text-left"><?php echo !empty($Vorder->name) ? $Vorder->name : $Vorder->name_product; ?></p>
                                          </td>
                                          <td>
                                              <p class="text-center"><?= number_format($Vorder->quantity); ?></p>
                                          </td>
                                          <td>
                                              <p class="text-right"><?= number_format($Vorder->price) ?></p>
                                          </td>
                                          <td>
							                  <?php if (!empty($Vorder->discount)) { ?>
								                  <?= ($Vorder->type_discount == 1) ? ('<p class="text-center">' . number_format($Vorder->discount) . ' (%)</p>') : ('<p class="text-right">' . number_format($Vorder->discount) . ' (' . _l('cong_money') . ')' . '</p>') ?>
							                  <?php } ?>
                                          </td>
                                          <td>
                                              <p class="text-right"><?= number_format($Vorder->grand_total) ?></p>
                                          </td>
                                          <td>
							                  <?php
							                  if (!empty($Vorder->id_customer)) {
								                  $option_client = get_table_where(db_prefix() . 'clients', ['userid' => $Vorder->id_customer], '', 'row');
								                  if (!empty($option_client)) {
									                  echo '<p class="text-left"><a target="_blank" href="' . admin_url('clients/client/' . $option_client->userid) . '">' . $option_client->company . '</a></p>';
								                  }
							                  }
							                  ?>
                                          </td>
                                          <td>
                                              <p class="text-center"><?= $Vorder->size ?></p>
                                          </td>
                                          <td>
                                              <div>
								                  <?php foreach ($Vorder->shipping as $kShipping => $vShipping) {
									                  echo "<p>" . _d($vShipping->date_shipping) . ' (' . _l('cong_quantity_short') . ': ' . $vShipping->quantity_shipping . ')' . "</p>";
								                  } ?>
                                              </div>
                                          </td>
                                      </tr>
				                  <?php } ?>
                                  </tbody>
                              </table>
                          </div>
                          <div class="panel panel-primary mtop10">
                              <div class="panel-heading">
                                  <h3 class="panel-title"><?= _l('cong_info_pay') ?></h3>
                              </div>
                              <div class="panel-body">
                                  <div id="bottom-total" class="well well-sm" style="margin-bottom: 5px;">
                                      <table class="table table-bordered mbot0 mtop0 table-view_order totals">
                                          <tbody>
                                          <tr class="success">
                                              <td>
                                                  <b><?= _l('cong_num_item_order') ?>: </b>
                                                  <span class="pull-right text-danger">
                                                        <?= !empty($orders->total_item) ? number_format($orders->total_item) : '' ?>
                                                   </span>
                                              </td>
                                              <td>
                                                  <b><?= _l('cong_cost_trans') ?> (VND): </b>
                                                  <span class="pull-right text-danger">
                                                        <?= !empty($orders->total_cost_trans) ? number_format($orders->total_cost_trans) : '0' ?>
                                                  </span>
                                              </td>
                                              <td>
                                                  <b><?= _l('cong_total_orders') ?>(VND): </b>
                                                  <span class="pull-right text-danger">
                                                        <?= !empty($orders->grand_total) ? number_format($orders->grand_total) : '0' ?>
                                                  </span>
                                              </td>
                                          </tr>
                                          <tr class="success">
                                              <td>
                                                  <b><?= _l('cong_guest_giving') ?>: </b>
                                                  <span class="pull-right text-danger">
                                                    <?= !empty($orders->guest_giving) ? number_format($orders->guest_giving) : '0' ?>
                                                  </span>
                                              </td>
                                              <td>
                                                  <b><?= _l('cong_type_pay') ?>: </b>
                                                  <span class="pull-right text-danger">
                                                      <?php
                                                            if(!empty($orders->mode_payment))
                                                            {
                                                                $mode_payment = get_table_where('tblpayment_modes', ['id' => $orders->mode_payment], '', 'row');
                                                            }
                                                      ?>
                                                      <?= !empty($mode_payment) ? $mode_payment->name : '-' ?>
                                                  </span>
                                              </td>
                                              <td>
                                                  <b><?= _l('cong_total_order_debt') ?> (VND):</b>
                                                  <span class="pull-right text-danger">
                                                    <?php $sumPayMent = SumPaymentOrder($orders->id); ?>
                                                      <?= number_format($orders->grand_total - $sumPayMent); ?>
                                                  </span>
                                              </td>
                                          </tr>
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <!-- ngoại tệ -->
                          <div class="panel panel-primary mtop10">
                              <div class="panel-heading">
                                  <h3 class="panel-title"><?= _l('cong_info_pay_word') ?></h3>
                              </div>
                              <div class="panel-body">
                                  <div id="bottom-total" class="well well-sm" style="margin-bottom: 5px;">
                                      <table class="table table-bordered mbot0 mtop0 table-view_order totals">
                                          <tbody>
                                          <tr class="success">
                                              <td>
                                                  <b><?= _l('cong_num_item_order') ?>: </b>
                                                  <span class="pull-right text-danger">
                                                      <?= !empty($orders->total_item) ? number_format($orders->total_item) : '' ?>
                                                  </span>
                                              </td>
                                              <td>
                                                  <b><?= _l('cong_cost_trans') ?>: </b>
                                                  <span class="pull-right text-danger">
                                                       <?= !empty($orders->total_cost_trans_international) ? $orders->total_cost_trans_international : '0' ?>
                                                  </span>
                                              </td>
                                              <td>
                                                  <b><?= _l('cong_total_orders') ?>: </b>
                                                  <span class="pull-right text-danger">
                                                      <?= !empty($orders->grand_total_international) ? $orders->grand_total_international : '0' ?>
                                                  </span>
                                              </td>
                                          </tr>
                                          <tr class="success">
                                              <td>
                                                  <b><?= _l('guest_giving') ?>: </b>
                                                  <span class="pull-right text-danger">
                                                      <?= !empty($orders->money_paid_international) ? $orders->money_paid_international : '0' ?>
                                                  </span>
                                              </td>
                                              <td></td>
                                              <td>
                                                  <b><?= _l('cong_total_order_debt') ?>:</b>
                                                  <span class="pull-right text-danger">
                                                      <?= _l('cong_not_update') ?>
                                                  </span>
                                              </td>
                                          </tr>
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer <?= !empty($view_not_modal) ? 'hide' : '' ?>" ">
                      <button type="button" class="btn btn-danger" data-dismiss="modal"><?= _l('cong_exit') ?></button>
                  </div>
              </div>
          </div>
          <script type="text/javascript">
              $(document).ready(function () {
                  $('#views_orders').modal('show');
              });
          </script>

<?php if(empty($view_not_modal)){?>
      </div>
  </div>
<?php }?>