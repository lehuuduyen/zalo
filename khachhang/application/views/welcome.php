<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="debts_customer" class="">
  <div class="row">


      <div class="col-md-2">
          <?php  echo render_date_input('date_start_customer','Ngày bắt đầu',$date_start);?>
      </div>
      <div class="col-md-2">
          <?php  echo render_date_input('date_end_customer','Ngày kết thúc',$date_end);?>
      </div>



      <div class="col-md-2">
          <button class="btn btn-info mtop25" type="button" onclick="load_table_customer()">Load danh sách</button>
      </div>
     <div class="clearfix"></div>

  </div>
  <div class="">
    <table class="table table table-striped table-debts_customer" id="debits">
       <thead>
          <tr>
            <th>ID</th>
            <th>TÊN</th>
            <th>DƯ ĐẦU KỲ</th>
            <th>SỐ TIỀN PHÁT SINH TĂNG</th>
            <th>SỐ TIỀN PHÁT SINH GIẢM</th>
            <th>SUPERSHIP CÒN NỢ</th>
            <th>THUỘC TÍNH</th>
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
                <input type="hidden" name="filter_debits" id="filter_debits">
                <table id="table-debts_customer_detail" class="table table table-striped table-debts_customer_detail">
                    <thead>


                    <tr>
                      <th>ID</th>
                      <th style="width:0%;">Ngày Tính Nợ</th>
                      <th style="width:0%;">Ngày Tạo</th>
                      <th style="width:8%;">LOẠI</th>
                      <th style="width:15%;">MÃ</th>
                      <th style="width:15%;">Trạng Thái</th>
                      <th style="width:10%;">PS Tăng</th>
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
