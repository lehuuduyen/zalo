<div id="debts_customer" class="">
  <div class="row">

      <div class="col-md-2">
          <?php  echo render_select('id_customer',$customer,array('id','customer_shop_code'),'Khách Hàng');?>
      </div>
      <div class="col-md-2">
          <?php  echo render_date_input('date_start_customer','Ngày bắt đầu',_d($date_start));?>
      </div>
      <div class="col-md-2">
          <?php  echo render_date_input('date_end_customer','Ngày kết thúc',_d($date_end));?>
      </div>

      <div class="col-md-2">
          <?php  echo render_select('id_rows_customer',array(array('id'=>'1','name'=>'Dương'),array('id'=>'2','name'=>'Âm')),array('id','name'),'Lọc giá trị');?>
      </div>

      <div class="col-md-4">
          <button class="btn btn-info mtop25" type="button" onclick="load_table_customer(0)">Load danh sách</button>
		  <button class="btn btn-info mtop25" type="button" onclick="load_table_customer(1)">Load danh sách KH 30 ngày</button>
      </div>
     <div class="clearfix"></div>

  </div>
  <div class="">
    <table class="table table table-striped table-debts_customer">
       <thead>
          <tr>
            <th style="display:none;">ID</th>
            <th>TÊN</th>
            <th>DƯ ĐẦU KỲ</th>
            <th>SỐ TIỀN PHÁT SINH TĂNG</th>
            <th>SỐ TIỀN PHÁT SINH GIẢM</th>
            <th>SUPERSHIP CÒN NỢ</th>
            <th>LỊCH THANH TOÁN</th>
            <th>THUỘC TÍNH</th>

            <!-- <th>ngày</th>
            <th>loại</th>
            <th>mã phiếu</th>
            <th>status</th>
            <th>tăng</th>
            <th>giảm</th>
            <th>note</th>
            <th>thuộc tính</th> -->


          </tr>
       </thead>
       <tbody>

       </tbody>

    </table>
  </div>

</div>


<div id="detail_customer" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="min-width: 90%;height:90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title_debits">Chi tiết công nợ</h4>
            </div>
            <div class="modal-body">
                <!-- <div class="col-md-6">
                    <?php echo render_date_input('start_detail_customer','Ngày từ', '')?>
                </div>
                <div class="col-md-6">
                    <?php echo render_date_input('end_detail_customer','Đến Ngày', '')?>

                </div> -->

                <?php echo render_input('id_shop_detail','','','hidden')?>
                <div class="detail_print pull-left">
                    <?php
                        $html_popover = "<div class='col-md-12'>
                                            <div class='form-group'>
                                                <label for='date_end_customer' class='control-label'>Ngày bắt đầu</label>
                                                    <div class='input-group date'>
                                                        <input type='text' id='date_export_excel_start' name='date_export_excel_start' class='form-control datepicker' value='' autocomplete='off'>
                                                        <div class='input-group-addon'>
                                                            <i class='fa fa-calendar calendar-icon'></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
                        $html_popover.= "<div class='col-md-12'>
                                            <div class='form-group'>
                                                <label for='date_end_customer' class='control-label'>Ngày kết thúc</label>
                                                    <div class='input-group date'>
                                                        <input type='text' id='date_export_excel_end' name='date_export_excel_end' class='form-control datepicker' value='' autocomplete='off'>
                                                        <div class='input-group-addon'>
                                                            <i class='fa fa-calendar calendar-icon'></i>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>";
                        $html_popover.="<div class='col-md-12 mbot20'><a class='btn btn-info exportExcelCustomer'>Bắt đầu tạo đối soát KH</a></div>";
                    ?>
                    <a href="#" id="clickExportExcel" class="btn btn-info" title="Xuất excel đối soát khách hàng" data-toggle="popover" data-placement="bottom" data-html="true" data-content="<?=$html_popover?>">Tạo đối soát KH</a><br>
                    <input type="hidden" id="id_hidden_shop"/>
                    <input type="hidden" id="id_hidden_customer"/>
                </div>
                <div class="col-md-12"><h4 class="text-center">Tổng số tiền đợi đối soát <b id="total_wating" class="text-danger">xxxx</b></h4></div>
                <input type="hidden" name="filter_debits" id="filter_debits">
                <table class="table table table-striped table-debts_customer_detail">
                    <thead>
                        <tr>
                          <th>ID</th>
                          <th style="width:0%;">Ngày Tính Nợ</th>
                          <th style="width:0%;">Ngày Tạo</th>
                          <th style="width:8%;">LOẠI</th>
                          <th style="width:15%;">MÃ</th>
                          <th style="width:15%;">Trạng Thái</th>
                          <th style="width:10%;">Biến động công nợ</th>
                          <th style="width:10%;">PS Giảm</th>
                          <th style="width:10%;">SPS Còn Nợ</th>
                          <th style="width:30%;">Nội Dung</th>
                          <th>Khối lượng</th>
                          <th >Người nhận</th>
                          <th >city</th>
                          <th >district</th>
                          <th >collect2</th>
                        </tr>
                    </thead>
                    <tbody></tbody>

                </table>
            </div>
            <div class="modal-footer" style="padding: 6px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
