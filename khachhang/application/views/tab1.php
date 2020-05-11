<div id="debts_customer" class="">
  <div class="row">

      <div class="col-md-2 col-xs-6">
          <?php  echo render_date_input('date_start_customer','Ngày bắt đầu',$date_start);?>
      </div>
      <div class="col-md-2 col-xs-6">
          <?php  echo render_date_input('date_end_customer','Ngày kết thúc',$date_end);?>
      </div>



      <div class="col-md-2 col-xs-12">
<!--          <button class="btn btn-info mtop25" type="button" onclick="load_table_customer()">Load danh sách</button>-->
          <button class="btn btn-info mtop25" type="button" onclick="InitTableCong()">Load danh sách</button>
      </div>
     <div class="clearfix"></div>

  </div>
  <div class="">

    <?php if ($isAppMobile == false): ?>
      <table id="table-debts_customer_detail" class="table table table-striped table-debts_customer_detail">
          <thead>
          

          <tr>
            <th>ID</th>
            <th style="width:0%;">Ngày Tính Nợ</th>
            <th style="width:0%;">Ngày Tạo</th>
            <th style="width:8%;">Mã Yêu Cầu</th>
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
    <?php else: ?>
      <div class="init-data-mobile">
        <div class="header-app">
          <p class="left-width">
            Nội dung chi tiết
          </p>
          <p class="right-width">
            SPS Còn Nợ
          </p>
          <div class="clear-fix"></div>
        </div>
        <ul class="scroll-list">
          <li>
            <div class="left-width">
              <div class="row-1 border-row">
                <p class="left-row">
                  <span style="color:red;font-weight:bold">07/09</span>
                  <span style="color:#000;font-weight:bold">HDGS209724NT.1280296</span>
                </p>
                <p class="righ-row red">
                  +4,500,000
                </p>
              </div>

              <div class="row-2 border-row">
                cái status ở đây
              </div>


              <div class="row-3 border-row" style="color:red">
                Nội dung cái khối lượng ở đây
              </div>

              <div class="row-3 border-row">
                Nội dung cái Node ở đây
              </div>

            </div>
            <div class="right-width">
              50,000,000
            </div>
            <div class="clear-fix"></div>
          </li>
        </ul>

        <div class="footer-app"></div>
      </div>




    <?php endif; ?>

  </div>

</div>
