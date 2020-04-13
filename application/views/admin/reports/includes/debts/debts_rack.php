    <div id="debts_rack" class="">
      <div class="row">
         <div class="clearfix"></div>
          <div class="col-md-2">
              <?php  echo render_select('id_racks',$rack,array('rackid','rack'),'Lái xe');?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_start_racks','Ngày bắt đầu',_d($date_start));?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_end_racks','Ngày kết thúc',_d($date_end));?>
          </div>
          <div class="col-md-2">
              <?php  echo render_select('id_rows_racks',array(array('id'=>'1','name'=>'Dương'),array('id'=>'2','name'=>'Âm')),array('id','name'),'Lọc giá trị');?>
          </div>
          <div class="col-md-2">
              <button class="btn btn-info mtop25" type="button" onclick="load_table_racks()">Load danh sách</button>
          </div>
      </div>
      <table class="table table table-striped table-debts_rack">
         <thead>
            <tr>
               <th><?php echo _l('TÊN'); ?></th>
               <th><?php echo _l('SỐ ĐIỆN THOẠI'); ?></th>
               <th>SỐ DƯ ĐẦU KỲ</th>
               <th>SỐ PHÁT GIẢM</th>
               <th>SỐ PHÁT TĂNG</th>
               <th>SỐ CUỐI KỲ</th>
               <th>Thuộc tính</th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <tr>
               <td>TỔNG</td>
               <td></td>
               <td><p class="money_debt text-danger"></p></td>
               <td><p class="money_giam text-danger"></p></td>
               <td><p class="money_tang text-danger"></p></td>
               <td><p class="money_total text-danger"></p></td>
               <td></td>
            </tr>
         </tfoot>
      </table>
   </div>
    <div id="detail_rack" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="min-width: 90%;height:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title title_debits_rack">Chi tiết công nợ</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-6">
                        <?php
                            $end = date('Y-m-d');
                            $date = new DateTime($end);;
                            date_sub($date, date_interval_create_from_date_string('30 days'));
                            $start = date_format($date, 'Y-m-d');
                        ?>
                        <?php echo render_date_input('start_detail_rack','Ngày từ',_d($start))?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_date_input('end_detail_rack','Đến Ngày',_d($end))?>
                        <?php echo render_input('id_rack_detail','','','hidden')?>
                    </div>
                    <table class="table table table-striped table-debts_detail_rack">
                        <thead>
                            <tr>
                                <th style="width:8%;">NGÀY</th>
                                <th style="width:15%;">LOẠI</th>
                                <th style="width:13%;">MÃ PHIẾU</th>
                                <th style="width:8%;text-align:center;">ĐỐI TƯỢNG</th>
                                <th style="width:8%;">SỐ LƯỢNG</th>
                                <th style="width:8%;">ĐƠN GIÁ</th>
                                <th style="width:8%;">PS GIẢM</th>
                                <th style="width:8%;">PS TĂNG</th>
                                <th style="width:10%;">CÒN NỢ</th>
                                <th style="width:13%;max-width:13%;">GHI CHÚ</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer" style="padding: 6px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="debt_rack_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <form action="<?=admin_url('reports/update_rack')?>" method="post" id="update_rack">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title title_debt_rack">Form nhập dư đầu kỳ Công Lái xe</h4>
                    </div>
                    <div class="modal-body">
                        <?php echo render_select('id_rack',$rack,array('rackid','rack'),'Lái xe'); ?>
                        <?php echo render_input('debt_rack','Số lượng đầu kì')?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Lưu</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
