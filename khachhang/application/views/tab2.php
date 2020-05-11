<style>
    .scroll-list-tab2 {

    }
    input ,
    input::-webkit-input-placeholder {
        font-size: 12px !important;
    }

</style>

<div class="layout-mobile">
    <div class="row">
        <div class="col-md-2 col-xs-6">
            <?php echo render_date_input('date_start_customer_order_tab2', 'Ngày bắt đầu', $date_start); ?>
        </div>
        <div class="col-md-2 col-xs-6">
            <?php echo render_date_input('date_end_customer_order_tab2', 'Ngày kết thúc', $date_end); ?>
        </div>



    </div>
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <div class="form-group" app-field-wrapper="date_start_customer_order_tab2">
                <label for="date_start_customer_order_tab2" class="control-label">Nhập Mã Tìm Kiếm</label>
                <input type="text" class="form-control font" placeholder="Nhập SĐT Người Nhận, Mã Yêu Cầu,Mã Đơn Hàng hoặc Mã Đơn Shop" id="code_order_tab2">

            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-6 col-xs-6">
            <div class="form-group">
                <label for="province">Trạng thái</label>
                <select data-live-search="true" class="form-control selectpicker" id="status-tab2"
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


            <div class="col-md-12">
                <div id="loader-repo4" class="lds-ellipsis" style="display:none;">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xs-12" style="margin-bottom: 2%">
            <button class="btn btn-info mtop25" type="button" onclick="fnFilter_list(<?= (!empty($isAppMobile)) ? $isAppMobile : 0 ?>)">Lọc danh sách</button>
<!--            <button class="btn btn-success mtop25" type="button" onclick="exportExcelMobile()">Xuất ra excel</button>-->
        </div>
        <div class="clearfix"></div>
    </div>

</div>

<div id="debts_customer" class="">

    <div class="">
        <div class="init-data-mobile">


            <ul class="scroll-list-tab2">
                <li>
                    <p class="stt-left">
                        1
                    </p>
                    <div class="left-width">

                        <div class="row-1 border-row">
                            <p class="left-row">
                                <span style="color:red;font-weight:bold">07/09</span>
                                <span style="color:#000;font-weight:bold">HDGS209724NT.1280296</span>
                            </p>

                        </div>


                        <div class="row-3 border-row" style="color:red">
                            Nội dung cái khối lượng ở đây
                        </div>

                        <div class="row-3 border-row">
                            Nội dung cái Node ở đây
                        </div>

                    </div>

                    <div class="clear-fix"></div>
                </li>
            </ul>

            <div class="footer-app"></div>
        </div>

    </div>

</div>
