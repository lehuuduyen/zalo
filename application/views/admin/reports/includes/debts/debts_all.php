
<style>
    .bg-danger{
        background-color: red!important;
    }
    .bg-warning{
        background-color: yellow;!important;
    }
</style>
<div id="debts_all" class="">

      <h4>DANH SÁCH NHÀ CUNG CẤP CẦN ĐÒI NỢ <a style="cursor: pointer;color: red" onclick="debts_all_report('supplier')">Hiển thị danh sách</a></h4>
        <div class="row">
            <div class="clearfix"></div>
            <div class="col-md-3">
                <?php  echo render_select('supplier_all',$suppliers,array('userid','company'),'Nhà cung cấp');?>
            </div>
            <div class="col-md-3">
                <?php  echo render_date_input('date_start_supplier_all','Từ',_d($date_start));?>
            </div>
            <div class="col-md-3">
                <?php  echo render_date_input('date_end_supplier_all','Đến',_d($date_end));?>
            </div>
            <div class="col-md-3">
                <?php  echo render_select('type_supplier',$type,array('id','name'),'Loại công nợ nhà cung cấp');?>
            </div>
        </div>
      <table class="table table table-striped table-debts_all_supplier">
            <thead>
            <tr>
                <th><?php echo _l('TÊN'); ?></th>
                <th>SỐ DƯ ĐẦU KỲ</th>
                <th>SỐ PHÁT GIẢM</th>
                <th>SỐ PHÁT TĂNG</th>
                <th>SỐ CUỐI KỲ</th>
                <th>LOẠI CÔNG NỢ</th>
                <th>NGÀY NHẮC</th>
                <th>NỘI DUNG NHẮC</th>
                <th>Thuộc tính</th>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
            <tr>
                <th>TỔNG</th>
                <th class="supplier_total_debt"></th>
                <th class="supplier_total_dimished"></th>
                <th class="supplier_total_up"></th>
                <th class="supplier_total_last"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
        </table>
     <hr/>

      <h4>DANH SÁCH VAY MƯỢN CẦN ĐÒI NỢ <a style="cursor: pointer;color: red" onclick="debts_all_report('personal')">Hiển thị danh sách</a></h4>

        <div class="row">
            <div class="clearfix"></div>
            <div class="col-md-3">
                <?php  echo render_select('personal_all',$other_object,array('id','name'),'Đối tượng vay mượn');?>
            </div>
            <div class="col-md-3">
                <?php  echo render_date_input('date_start_personal_all','Từ',_d($date_start));?>
            </div>
            <div class="col-md-3">
                <?php  echo render_date_input('date_end_personal_all','Đến',_d($date_end));?>
            </div>
            <div class="col-md-3">
                <?php  echo render_select('type_personal',$type,array('id','name'),'Loại công nợ đối tượng');?>
            </div>
        </div>
      <table class="table table table-striped table-debts_all_personal">
            <thead>
            <tr>
                <th><?php echo _l('TÊN'); ?></th>
                <th>SỐ TIỀN VAY MƯỢN TRƯỚC ĐÓ</th>
                <th>SỐ PS GIẢM</th>
                <th>SỐ PS TĂNG</th>
                <th>SỐ CUỐI KỲ</th>
                <th>LOẠI CÔNG NỢ</th>
                <th>NGÀY NHẮC</th>
                <th>NỘI DUNG NHẮC</th>
                <th>Thuộc tính</th>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
            <tr>
                <th></th>
                <th class="object_total_debt"></th>
                <th class="object_total_dimished"></th>
                <th class="object_total_up"></th>
                <th class="object_total_last"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
        </table>
        <hr/>
        <h4>DANH SÁCH KHÁCH HÀNG CẦN ĐÒI NỢ <a style="cursor: pointer;color: red" onclick="debts_all_report('client')">Hiển thị danh sách</a></h4>
        <div class="row">
            <div class="clearfix"></div>
            <div class="col-md-2">
                <?php  echo render_select('client_all',$clients,array('userid','company'),'Khách hàng');?>
            </div>
            <div class="col-md-2">
                <?php  echo render_date_input('date_start_client_all','Từ',_d($date_start));?>
            </div>
            <div class="col-md-2">
                <?php  echo render_date_input('date_end_client_all','Đến',_d($date_end));?>
            </div>
            <div class="col-md-2">
                <?php  echo render_select('type_client',$type,array('id','name'),'Loại công nợ khách hàng');?>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="check_active" class="control-label">Tình trạng khách hàng</label>
                    <select id="check_active" name="check_active" class="selectpicker" data-width="100%" data-none-selected-text="Không có gì được chọn" data-live-search="true" tabindex="-98">
                        <option value=" ">Tất cả</option>
                        <option value="1" selected>Còn công nợ hoặc còn hoạt động trong 3 tháng</option>
                        <option value="0">Hết công nợ và không còn hoạt động trong 3 tháng</option>
                    </select>
                </div>
            </div>
        </div>
        <table class="table table table-striped table-debts_all_client">
            <thead>
            <tr>
                <th style="max-width:100px"><?php echo _l('TÊN'); ?></th>
                <th>KD GS</th>
                <th>TỈNH</th>
                <th>CUỐI KỲ</th>
                <th>TIỀN HÀNG</br> MỚI ĐI</th>
                <th>CÒN LẠI</th>
                <th>LOẠI</th>
                <th>TỔNG SL</th>
                <th>NGÀY NHẮC</th>
                <th>NỘI DUNG NHẮC</th>
                <th>THUỘC TÍNH</th>
            </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
            <tr>
                <th>Tổng</th>
                <th></th>
                <th></th>
                <th class="total_last"></th>
                <th class="total_new"></th>
                <th class="total_remaining"></th>
                <th></th>
                <th class="total_sl"></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
        </table>

        <hr/>
   </div>
