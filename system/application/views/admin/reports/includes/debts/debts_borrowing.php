    <div id="debts_borrowing" class="">
      <div class="row">
         <div class="clearfix"></div>
          <div class="col-md-2">
              <?php  echo render_select('id_supplier_borrrowing',$suppliers,array('userid','company'),'Nhà cung cấp');?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_start_borrrowing','Ngày bắt đầu',_d($date_start));?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_end_borrrowing','Ngày kết thúc',_d($date_end));?>
          </div>
          <div class="col-md-2">
              <?php  echo render_select('id_rows_supplier_borrrowing',array(array('id'=>'1','name'=>'Dương'),array('id'=>'2','name'=>'Âm')),array('id','name'),'Lọc giá trị');?>
          </div>
          <div class="col-md-2">
              <button class="btn btn-info mtop25" type="button" onclick="load_table_borrrowing()">Load danh sách</button>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-12">
            <a href="#" class="btn mright5 mbot20 btn-info pull-left display-block" onclick="view_update_borrowing('','')">Thêm số lượng vay mượn đầu kỳ NVL</a>
          </div>
      </div>
      <table class="table table table-striped table-debts_borrowing">
         <thead>
            <tr>
               <th><?php echo _l('NHÀ CUNG CẤP'); ?></th>
               <th><?php echo _l('NGUYÊN VẬT LIỆU'); ?></th>
               <th>SỐ LƯỢNG ĐẦU KỲ</th>
               <th>SỐ LƯỢNG PHÁT GIÃM</th>
               <th>SỐ LƯỢNG PHÁT TĂNG</th>
               <th>SỐ LƯỢNG CUỐI KỲ</th>
               <th>THUỘC TÍNH</th>
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
            </tr>
         </tfoot>
      </table>
   </div>
    <div id="detail_borrowing" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="min-width: 90%;height:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title title_debits_borrowing">Chi tiết vay mượn</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-6">
                        <?php
                            $end = date('Y-m-d');
                            $date = new DateTime($end);;
                            date_sub($date, date_interval_create_from_date_string('30 days'));
                            $start = date_format($date, 'Y-m-d');
                        ?>
                        <?php echo render_date_input('start_detail_borrowing','Ngày từ',_d($start))?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_date_input('end_detail_borrowing','Đến Ngày',_d($end))?>
                        <?php echo render_input('id_borrowing_detail','','','hidden')?>
                        <?php echo render_input('id_borrowing_product','','','hidden')?>
                    </div>
                    <table class="table table-striped table-debts_detail_borrowing">
                        <thead>
                        <tr>
                            <th>NGÀY</th>
                            <th>LOẠI</th>
                            <th>MÃ PHIẾU</th>
                            <th>SL GIẢM</th>
                            <th>SL TĂNG</th>
                            <th>CÒN NỢ</th>
                            <th>GHI CHÚ</th>
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

    <div id="debt_borrowing_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <form action="<?=admin_url('reports/update_borrowing')?>" method="post" id="update_borrowing">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title title_debt_borrowing">Form nhập dư số vay đầu kì</h4>
                    </div>
                    <div class="modal-body">
                        <?php echo render_select('id_supplier_borrowing',$suppliers,array('userid','company'),'Nhà cung cấp'); ?>
                        <?php echo render_select('product_id_borrowing',$materials,array('id','name'),'Nguyên vật liệu'); ?>
                        <?php echo render_input('debt_borrowing','Số lượng đầu kì')?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Lưu</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
