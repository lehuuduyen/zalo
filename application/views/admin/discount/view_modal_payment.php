  <style type="text/css">
    .img_ch{
      height: 20px;
      width: 20px;
    }
  </style>
  <div class="modal fade in" id="view_discount_payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="false" style="display: block;"><div class="modal-dialog modal-lg no-modal-header" style="width: 80%;">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
              <span class="book-title"><?php echo _l('Thông tin chiết khấu'); ?> </span>
            </h4>
          </div>
          <div class="modal-body">
            <div class="row">
                  <div class="col-md-6  pull-left">
                      <div class="panel panel-success">
                          <div class="panel-heading">
                              <h3 class="panel-title">Thông tin chiết khấu thanh toán</h3>
                          </div>
                          <div class="panel-body">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-6">
                                            <div><b><?=_l('ch_code_p')?>: </b><?php echo $discount->prefix.$discount->code ?></div>
                                            <div><b><?=_l('ch_date_p')?>: </b><?php echo _d($discount->date)?></div>
                                            <div><b><?=_l('Tên bảng chiết khấu')?>: </b><?php echo $discount->name_discount?></div>
                                            <div><b><?=_l('ch_note')?>: </b><?php echo $discount->note?></div>
                                    </div>
                                    <div class="col-md-6">
                                            <div><b><?=_l('Người tạo')?>:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo staff_profile_image($discount->staff_create, array('staff-profile-image-small mright5 img_ch'), 'small', array(
                                                  'data-toggle' => 'tooltip',
                                                  'data-title' => get_staff_full_name($discount->staff_create)
                                              )).get_staff_full_name($discount->staff_create)?></b></div>
                                            
                                            <?php
                                          $history_status = explode('|',$discount->history_status);
                                          foreach ($history_status as $key => $value) {
                                              $data=explode(',',$value);
                                              if(is_numeric($data[0]))
                                              {
                                                  ?>
                                                  <div><b><?=_l('ch_status_import')?></b>: <?php echo staff_profile_image($data[0], array('staff-profile-image-small mright5 img_ch'), 'small', array(
                                                        'data-toggle' => 'tooltip',
                                                        'data-title' => ' Vào lúc: '._dt($data[1])
                                                    )).get_staff_full_name($data[0])?>
                                                  </div>
                                                  <?php 
                                              }
                                          }
                                        ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                          </div>
                      </div>
                  </div>
              </div>
              <ul class="nav nav-tabs profile-tabs" role="tablist">
                    <li role="presentation" class="itemss active">
                        <a href="#items" aria-controls="items" role="tab" data-toggle="tab">
                          Danh mục áp dụng
                        </a>
                    </li>
                    <li role="presentation" class="clientss <?=(isset($discount) ? ($discount->id_client == 2 ? ''  : 'hide') : 'hide'); ?>">
                        <a href="#client" aria-controls="client" role="tab" data-toggle="tab">
                          Khách hàng áp dụng
                        </a>
                    </li>
                </ul>
                               <div class="tab-content">
             <div role="tabpanel" class="tab-pane active" id="items">
        <table id="example"  class="table table-striped  table-discount table-bordered" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th class="text-center" style="width: 15%">STT</th>
              <th class="text-center" style="width: 50%">Tên mức thời gian thanh toán</th>
              <th class="text-center" style="width: 25%">Chiết khấu</th>
            </tr>
          </thead>
          <tbody>
          <?php $i=0; foreach ($discount->items as $key => $value) { $i++?>
            <tr>
                  <td class="text-center"><?=$i?></td>
                  <td ><?=$value['name_payment']?></td>
                  <td class="text-center"><?=$value['discounts']?></td>
                </tr>
          <?php } ?>
            
          </tbody>
        </table>
      </div>
          <div role="tabpanel" class="tab-pane" id="client">
            <table id="inventory" class="table table-striped  item-inventory table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><?php echo _l('Mã khách hàng'); ?></th>
                  <th ><?php echo _l('Tên khách hàng'); ?></th>
                  <th ><?php echo _l('SDT'); ?></th>
                  <th ><?php echo _l('Địa chỉ'); ?></th>
                </tr>
              </thead>
              <tbody>
            <?php $j=0; 
            if(!empty($discount)){
            foreach ($discount->clients as $key => $value) {?>
              <tr>
                <td><input class="id hide" name="client[<?=$j?>][id_client]" value="<?=$value['id']?>"> <?=$value['code_clients']?> </td>
                <td ><?=$value['text']?></td>
                <td class="text-center"><?=$value['phonenumber']?></td>
                <td ><?=$value['address']?></td>
              </tr>
            <?php $j++; } }?>
              </tbody>
            </table>
          </div>
        </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Thoát</button>
              </div>
          </div>
      </div>
  </div>

  <script>
   $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
         .columns.adjust()
         .fixedColumns().relayout();
   }); 
      $(document).ready( function() {
          $('.tip').tooltip();
      });
    $(document).ready(function() {  
    $('#example').DataTable( {
      responsive : true,
        scrollY:        '19vh',
        "oLanguage":{
          "sProcessing":   "Đang xử lý...",
          "sLengthMenu":   "Xem _MENU_ mục",
          "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",
          "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
          "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
          "sInfoFiltered": "(được lọc từ _MAX_ mục)",
          "sInfoPostFix":  "",
          "sSearch":       "Tìm:",
          "sUrl":          "",
          "oPaginate": {
              "sFirst":    "Đầu",
              "sPrevious": "Trước",
              "sNext":     "Tiếp",
              "sLast":     "Cuối"
          }
      },
      pageLength:50,
    });
  });
  $(document).ready(function() {  
    $('#inventory').DataTable( {
      responsive : true,
        scrollY:        '19vh',
        "oLanguage":{
          "sProcessing":   "Đang xử lý...",
          "sLengthMenu":   "Xem _MENU_ mục",
          "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",
          "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
          "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
          "sInfoFiltered": "(được lọc từ _MAX_ mục)",
          "sInfoPostFix":  "",
          "sSearch":       "Tìm:",
          "sUrl":          "",
          "oPaginate": {
              "sFirst":    "Đầu",
              "sPrevious": "Trước",
              "sNext":     "Tiếp",
              "sLast":     "Cuối"
          }
      },
      pageLength:50,
    });
  });
  </script>

  </div>