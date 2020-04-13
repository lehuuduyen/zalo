<div class="layout-mobile">
    <div class="row">
        <div class="col-md-2 col-xs-6">
            <div class="form-group" app-field-wrapper="date_start_customer_order">
                <label for="date_start_customer_order" class="control-label">Ngày bắt đầu</label>
                <div class="input-group date">
                    <input type="text" id="date_start_order" name="date_start_customer_order" class="form-control" value="<?= $date_start?>" autocomplete="off">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar calendar-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-xs-6">
            <div class="form-group" app-field-wrapper="date_end_customer_order">
                <label for="date_end_customer_order" class="control-label">Ngày kết thúc</label>
                <div class="input-group date">
                    <input type="text" id="date_end_order" name="date_end_customer_order" class="form-control" value="<?= $date_end?>" autocomplete="off">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar calendar-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-xs-6">
            <div class="form-group" app-field-wrapper="date_end_customer_order">
                <label for="province">Trạng thái</label>
                <select data-live-search="true" class="form-control selectpicker" id="status-tab2-pc"
                        name="status-tab2">
                    <option value="null">Chọn Trạng thái</option>
                    <option value="Hủy">Hủy</option>
                    <option value="Đã Đối Soát Giao Hàng">Đã đối soát giao hàng</option>
                    <option value="Đã Trả Hàng">Đã trả hàng</option>
                    <option value="Đã Nhập Kho">Đã Nhập Kho</option>
                    <option value="Chờ Lấy Hàng">Chờ Lấy Hàng</option>
                    <option value="Đã Lấy Hàng">Đã Lấy Hàng</option>
                    <option value="Đã Giao Hàng Toàn Bộ">Đã Giao Hàng Toàn Bộ</option>
                    <option value="Đã Chuyển Kho Giao">Đã Chuyển Kho Giao</option>
                    <option value="Đang Chuyển Kho Giao">Đang Chuyển Kho Giao</option>
                    <option value="Đã Giao Hàng Một Phần">Đã Giao Hàng Một Phần</option>
                    <option value="Đang Vận Chuyển">Đang Vận Chuyển</option>
                    <option value="Hoãn Giao Hàng">Hoãn Giao Hàng</option>
                    <option value="Đang Giao Hàng">Đang Giao Hàng</option>
                </select>
            </div>
        </div>


        <div class="col-md-4 col-xs-12" style="margin-bottom: 2%">
            <button class="btn btn-info mtop25" type="button"
                    onclick="initOrderManager()">Lọc danh sách
            </button>

            <button class="btn btn-success mtop25" type="button"
                    onclick="exportExcel()">Xuất ra excel
            </button>
        </div>
        <div class="clearfix"></div>
    </div>

</div>

<div id="debts_customer" class="">
    <?php if ($isAppMobile == false): ?>
        <table id="table-manager-order" class="table table table-striped table-debts_customer_detail dataTable no-footer">
            <thead>
            <tr>
                <th>STT</th>
                <th style="width:10%;">Ngày Tạo</th>
                <th style="width:10%;">Mã Yêu Cầu</th>
                <th style="width:15%;">Mã Đơn Hàng</th>
                <th style="width:15%;">Trạng Thái</th>
                <th style="width:10%;">Thu Hộ</th>
                <th style="width:10%;">Phí DV</th>
                <th style="width:5%;">Khối lượng</th>
                <th style="width:35%;">Thông tin</th>
            </tr>
            </thead>
            <tbody id="listBoxOrderManager"></tbody>
            <tfoot>
                <tr id="boxpage" >

                    <td colspan="3" onclick="initOrderManager(2)" style="cursor: pointer;">
                        <input type="hidden" value="2" id="number-page">
                        <button class="btn-info btn" style="font-size: 15px;border-radius: 9px;">Tải thêm</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>

</div>
