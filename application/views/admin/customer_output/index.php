<?php init_head(); ?>
<style>
  .total_cal {
    display: block;
    border: 1px solid red;
    float: left;
    margin-top: 25px;
    margin-left: 10px;
  }
  .total_calc_cover {
    position: relative;
  }
  .total_calc_cover button {
    float: left;
  }
</style>
<div id="wrapper">
   <div class="content">
      <div class="row">
        <div class="panel_s">
          <div class="panel-body">
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

                  <input type="hidden" name="shop_name" id="shop_name">



                  <div class="col-md-6 total_calc_cover">
                      <button class="btn btn-info mtop25" type="button" onclick="load_table_customer()">Load danh sách</button>
                      <p class="total-append"></p>
                  </div>
                 <div class="clearfix"></div>

              </div>
              <div class="">
                <table class="table table table-striped table-sales_report_customer">
                   <thead>
                      <tr>
                        <th>Tên KH</th>
                        <th>Số Đơn Thành Công </th>
                        <th>Số Đơn Thất Bại </th>
                        <th>Số Đơn Đang Giao </th>
                        <th>Tổng Đơn Gửi  </th>
                        <th>Nhân Viên Kinh Doanh</th>
                        <th> Thuộc Tính  </th>





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
                            <h4 class="modal-title title_debits">Doanh Thu Khách Hàng</h4> <span class="name-shop-modal"></span>
                        </div>
                        <div class="modal-body">


                            <?php echo render_input('id_shop_detail','','','hidden')?>
                            <input type="hidden" name="filter_debits" id="filter_debits">
                            <table class="table table table-striped table-sales_report_customer_detail">
                                <thead>




                                <tr>
                                  <th>Ngày Tạo</th>
                                  <th >Mã Đơn Hàng</th>
                                  <th >Trạng Thái</th>
                                  <th >Nội Dung</th>
                                  <th>Khối lượng</th>
                                  <th >Người nhận</th>
                                  <th >city</th>
                                  <th >district</th>
                                  <th >collect</th>
                                  <th >hd</th>
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
          </div>
        </div>

      </div>
    </div>
</div>
<?php init_tail(); ?>


<script>
var fnServerParams = {
  "id_customer": '[name="id_customer"]',
  "date_start_customer": '[name="date_start_customer"]',
  "date_end_customer": '[name="date_end_customer"]',
  "id_rows_customer": '[name="id_rows_customer"]',
  "shop_name": '[name="shop_name"]',
};




function load_table_customer() {
  if ($.fn.DataTable.isDataTable('.table-sales_report_customer')) {
      $('.table-sales_report_customer').DataTable().ajax.reload(function(json) {

        var totalHtml = `<span style="float: left;margin-top: 33px;margin-left: 10px;" class="total_label">Tổng số đơn gửi:</span><span class="btn btn-default total_cal">${formatNumber(json.calc_total_s_e)}</span>`;
        $('.total-append').empty();
        $('.total-append').append(totalHtml);
      },false);
  }
  $('#start_detail_customer').val($('#date_start_customer').val());
  $('#end_detail_customer').val($('#date_end_customer').val());
  var data = initDataTableDungbt2('.table-sales_report_customer', '/system/admin/customer_output/load_output_report_customer' , false, false, fnServerParams, [0, 'ASC'] , 'Tổng số đơn gửi:');


}






$(document).on("click",".get_data_sale_report",function() {

  if ($.fn.DataTable.isDataTable('.table-sales_report_customer_detail')) {

      $('.table-sales_report_customer_detail').DataTable().ajax.reload();
  }
  var dataShop = $(this).attr('data-shop');

  $('#shop_name').val(dataShop);
  $('#detail_customer').modal('show');
  $('.name-shop-modal').text(dataShop);
  var data = initDataTableDungbt('.table-sales_report_customer_detail', '/system/admin/customer_output/load_output_report_customer_detail' , false, false, fnServerParams, [0, 'ASC']);


  data.column(4).visible(false);
  data.column(5).visible(false);
  data.column(6).visible(false);
  data.column(7).visible(false);
  data.column(8).visible(false);
  data.column(9).visible(false);
});


</script>
