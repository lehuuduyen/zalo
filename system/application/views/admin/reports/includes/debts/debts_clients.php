<style>
    legend {
        font-size: 15px;
        font-weight: 500;
        width: auto!important;
    }
    fieldset {
        padding: .35em .625em .75em!important;
        margin: 0 2px!important;
        border: 1px solid #19a9ea!important;
    }
</style>
<div id="debts_clients" class="">
      <div class="row">
         <div class="clearfix"></div>
          <div class="col-md-2">
              <?php  echo render_select('id_client',$clients,array('userid','company'),'Khách háng');?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_start_client','Ngày bắt đầu',_d($date_start));?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_end_client','Ngày kết thúc',_d($date_end));?>
          </div>
          <div class="col-md-2">
             <div class="form-group">
                  <label for="id_rows_client" class="control-label">Lọc giá trị</label>
                  <select id="id_rows_client" name="id_rows_client" class="selectpicker" data-width="100%" data-none-selected-text="Không có gì được chọn" data-live-search="true" tabindex="-98">
                      <option value="0">Hiển thị tất cả</option>
                      <option value="1">Dương</option>
                      <option value="2">Âm</option>
                  </select>
              </div>
          </div>
          <div class="col-md-2">
              <?php  echo render_select('province_client',$province,array('provinceid','name'),'Tỉnh thành');?>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-2">
              <?php  echo render_date_input('start_cooperative_day','Lọc cgày hợp tác từ');?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('end_cooperative_day','Ngày hợp tác đến');?>
          </div>
          <div class="col-md-2">
              <div class="form-group">
                  <label for="check_active_client" class="control-label">Tình trạng khách hàng</label>
                  <select id="check_active_client" name="check_active_client" class="selectpicker" data-width="100%" data-none-selected-text="Không có gì được chọn" data-live-search="true" tabindex="-98">
                      <option value=" ">Tất cả</option>
                      <option value="1" selected>Còn công nợ hoặc còn hoạt động trong 3 tháng</option>
                      <option value="0">Hết công nợ và không còn hoạt động trong 3 tháng</option>
                  </select>
              </div>
          </div>
          <div class="col-md-2">
              <button type="button" class="btn btn-info mtop25" onclick="load_table_client()">Load bảng danh sách</button>
          </div>
      </div>
      <table class="table table table-striped table-debts_client">
         <thead>
            <tr>
               <th><?php echo _l('TÊN'); ?></th>
               <th><?php echo _l('SỐ ĐIỆN THOẠI'); ?></th>
               <th><?php echo _l('TỈNH THÀNH'); ?></th>
               <th>SỐ DƯ ĐẦU KỲ</th>
               <th>PS GIẢM</th>
               <th>PS TĂNG</th>
               <th>CUỐI KỲ</th>
               <th>Thuộc tính</th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <tr>
               <th>Tổng</th>
               <th>-</th>
               <th>-</th>
               <th class="total_debt"></th>
               <th class="total_dimished"></th> <!--tang-->
               <th class="total_up"></th> <!--giảm-->
               <th class="total_last"></th>
               <th></th>
            </tr>
         </tfoot>
      </table>
   </div>
    <div id="detail_clients" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="min-width: 90%;height:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title title_debits_client">Chi tiết công nợ</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-6">
                        <?php
                            $end = date('Y-m-d');
                            $date = new DateTime($end);;
                            date_sub($date, date_interval_create_from_date_string('30 days'));
                            $start = date_format($date, 'Y-m-d');
                        ?>
                        <?php echo render_date_input('start_detail_client','Ngày từ',_d($start))?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_date_input('end_detail_client','Đến Ngày',_d($end))?>
                        <?php echo render_input('id_client_detail','','','hidden')?>
                    </div>
                    <table class="table table table-striped table-debts_detail_client">
                        <thead>
                            <tr>
                                <th style="width:8%;">NGÀY</th>
                                <th style="width:15%;">LOẠI</th>
                                <th style="width:13%;">MÃ PHIẾU</th>
                                <th style="width:8%;text-align:center;">KHÁCH HÀNG</th>
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
    <div id="debt_client_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <form action="<?=admin_url('reports/update_clients')?>" method="post" id="update_client">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title title_debt_rack">Khai Báo Loại Công Nợ</h4>
                    </div>
                    <div class="modal-body">
                            <div class="col-md-4" style="border-right: 1px solid blue;">
                                <?php echo render_select('id_client_update',$clients,array('userid','company'),'Khách hàng'); ?>
                                <?php echo render_select('type_debt_client',$type_client,array('id','name'),'Loại công nợ')?>
                                <?php echo render_date_input('debit_date','Nhập ngày nhắc')?>
                                <?php echo render_textarea('note','Nội dung nhắc')?>
                            </div>
                            <div class="col-md-8" style="border-left: 1px solid blue;margin-left: -1px;">
                                <fieldset>
                                    <legend>Lịch sử gọi</legend>
                                        <button class="btn btn-info mbot20 mleft10" type="button" onclick="add_history()">ADD</button>
                                        <div class="history_add"></div>
                                </fieldset>
                            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Lưu</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
