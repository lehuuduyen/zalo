    <div id="debts_staff" class="">
      <div class="row">
         <div class="clearfix"></div>
          <div class="col-md-2">
              <?php  echo render_select('id_staff',$staff,array('staffid', ['lastname' ,'firstname']),'Nhân viên');?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_start_staff','Ngày bắt đầu',_d($date_start));?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_end_staff','Ngày kết thúc',_d($date_end));?>
          </div>
          <div class="col-md-2">
              <?php  echo render_select('id_rows_staff',array(array('id'=>'1','name'=>'Dương'),array('id'=>'2','name'=>'Âm')),array('id','name'),'Lọc giá trị');?>
          </div>
          <div  class="col-md-2">
            <div class="checkbox mtop30">
                  <input  type="checkbox"  id="staff_active" name="staff_active" value="1" autocomplete="off" checked>
                  <label for="staff_active">Chỉ view nhân viên đang hoạt động</label>
            </div>
          </div>
          <div class="col-md-2">
              <button class="btn btn-info mtop25" type="button" onclick="load_table_staff()">Load danh sách</button>
          </div>
      </div>
      <table class="table table table-striped table-debts_staff">
         <thead>
            <tr>
               <th><?php echo _l('TÊN NHÂN VIÊN'); ?></th>
               <th>SỐ DƯ ĐẦU KỲ</th>
               <th>SỐ PHÁT GIẢM</th>
               <th>SỐ PHÁT TĂNG</th>
               <th>SỐ CUỐI KỲ</th>
               <th>Thuộc tính</th>
            </tr>
         </thead>
         <tbody></tbody>
      </table>
   </div>
    <div id="detail_staff" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="min-width: 90%;height:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title title_debits_staff">Chi tiết công nợ  <b id="fullname_staff"></b></h4>
                </div>
                <div class="modal-body">
                    <div class="hide">
                      <div class="col-md-6">
                          <?php
                              $end = date('Y-m-d');
                              $date = new DateTime($end);;
                              date_sub($date, date_interval_create_from_date_string('30 days'));
                              $start = date_format($date, 'Y-m-d');
                          ?>
                          <?php echo render_date_input('start_detail_staff','Ngày từ',_d($start))?>
                      </div>
                      <div class="col-md-6">
                          <?php echo render_date_input('end_detail_staff','Đến Ngày',_d($end))?>
                          <?php echo render_input('id_staff_detail','','','hidden')?>
                      </div>
                    </div>
                    <table class="table table table-striped table-debts_detail_staff">
                        <thead>
                            <tr>
                                <th>NGÀY</th>
                                <th>LOẠI</th>
                                <th>MÃ PHIẾU</th>
                                <th>PS GIẢM</th>
                                <th>PS TĂNG</th>
                                <th>CÒN NỢ</th>
                                <th>NỘI DUNG PHIẾU</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer" style="padding: 6px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>

        </div>
    </div>
