<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<style>
    #create_order_ob .control-label, #create_order_ob label {
        margin-bottom: 0;
    }

    #create_order_ob .form-group {
        margin-bottom: 5px;
    }

    #create_order .modal-header {
        display: none;
    }

    #create_order .modal-footer {
        position: absolute;
        bottom: -44px !important;
        padding: 5px;
    }

    .overlay-dark {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 123123123;
    }

    #loader-repo3 {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -32px;
        margin-left: -32px;
    }

    #loader-repo4 {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -32px;
        margin-left: -32px;
    }

    #create_order_ob .form-group label {
        font-size: 15px;
        font-weight: bold;
    }

    #create_order_ob .form-group input {
        font-size: 15px;
    }

    #create_order .modal-dialog {
        width: 100%;
        margin: 0;
    }

    #create_order {
        padding: 0;
        margin: 0;
        padding-left: 0 !important;
    }

    .row-custom {
        display: -webkit-flex;
        display: -ms-flex;
        display: flex;
        align-items: center;
    }

    #create_order .col-md-3 {
        margin: 0;
        padding-top: 0;
        padding-bottom: 0;
        background: #fff;
    }

    .bs3.bootstrap-select .dropdown-toggle .filter-option {
        padding-right: inherit;
        position: absolute;
        padding-left: 10px;
        padding-top: 4px;
    }

    .modal-body {
        overflow: auto;
        position: relative;
    }

    #create_order {
        padding: 0 !important;;
    }

    .modal-footer {
        position: absolute;
        bottom: -60px;
        width: 100%;
        background: #fff;
    }

    .more-config {
        display: none;

    }

    .collapse {
        display: none;
    }

    .add-more-phone {
        position: absolute;
        top: 22px;
        right: 16px;
        border-left: 1px solid #ddd;
        height: 35px;
        padding: 10px;
    }

    .col-md-6 {

    }

    .col-md-6.right {
        float: right;
    }

    .search-icon {
        position: absolute;
        top: 33px;
        right: 12px;
        font-size: 18px;
    }

    .search-item {
        display: none;
        position: absolute;
        width: 100%;
        top: 65px;
        left: 0;
        z-index: 1;
        max-height: 275px;
        overflow-y: auto;
        cursor: pointer;

    }

    .search-item li:hover {
        background: #ddd;
        color: #fff;
    }

    #loader-repo, #loader-repo2, #loader-repo4 {
        display: none;
    }

    .disable-view {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999999999999;
    }

    .lds-ellipsis {
        display: block;
        position: relative;
        width: 64px;
        height: 64px;
        margin: 0 auto;
    }

    .lds-ellipsis div {
        position: absolute;
        top: 27px;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: #03a9f4;
        animation-timing-function: cubic-bezier(0, 1, 1, 0);
    }

    .lds-ellipsis div:nth-child(1) {
        left: 6px;
        animation: lds-ellipsis1 0.6s infinite;
    }

    .lds-ellipsis div:nth-child(2) {
        left: 6px;
        animation: lds-ellipsis2 0.6s infinite;
    }

    .lds-ellipsis div:nth-child(3) {
        left: 26px;
        animation: lds-ellipsis2 0.6s infinite;
    }

    .lds-ellipsis div:nth-child(4) {
        left: 45px;
        animation: lds-ellipsis3 0.6s infinite;
    }

    @keyframes lds-ellipsis1 {
        0% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes lds-ellipsis3 {
        0% {
            transform: scale(1);
        }
        100% {
            transform: scale(0);
        }
    }

    @keyframes lds-ellipsis2 {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(19px, 0);
        }
    }


    /* Hide the browser's default checkbox */
    .container-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .container-checkbox :hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .container-checkbox input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .container-checkbox input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .container-checkbox .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    .cover-checked {
        position: relative;
        display: flex;
        align-items: center;
        padding-top: 5px;
        padding-left: 35px;
    }

    #create_order .modal-dialog {
        width: 100%;
        margin: 0;
    }

    #success-order .modal-dialog {
        width: 300px;
        height: 210px;
        left: 50%;
        top: 50%;
        margin-top: -105px;
        margin-left: -150px;
        position: absolute;
        overflow: hidden;
    }

    #success-order .modal-dialog .modal-body {
        height: auto !important;
    }

    #check_disable_super {
        width: 20px;
        height: 20px;
        margin-right: 5px;
        margin-top: 0;
        top: 5px;
        position: relative;
    }

    .table.table-create_order_filter {
        display: none;
    }

    .row-custom {
        display: -webkit-flex;
        display: -ms-flex;
        display: flex;
        align-items: center;
    }

    .mb0 {
        margin-bottom: 0 !important;
    }

    #create_order .modal-content {
        border-radius: 0;
    }

    @media only screen and (max-width: 768px) {
        #create_order .col-md-3.cover-checked {
            height: 29px;
            position: relative;
            left: 17px;
        }

        .modal-dialog {
            width: auto
        }

        .col-md-6 {
            width: 100%;
        }

        .col-md-6.right {
            float: none;
        }

        .inner.open {
            height: 250px;
        }

        .row-custom {
            display: block;
            width: 100%;
            float: left;
        }

        #create_order .bottom-mobile {
            float: left;
            width: 100%;
        }

        #create_order {
            position: absolute;
            z-index: 11111;
        }

        #create_order .modal-body {
            padding: 10px 0;
        }

        #create_order_ob .form-group label {
            margin-bottom: 5px;
        }

        .add-more-phone {
            top: 26px;
        }

        #create_order .col-xs-6 {
            width: 49%;
            padding: 0px 5px;
            float: none;
            display: inline-block;
        }
    }


</style>


<div class="row">

    <div class="col-md-12 col-xs-12">
        <a style="margin-bottom:10px;font-size: 13px;padding: 15px;" href="javascript:;"
           class="open-modal-addnew-create-order btn btn-info pull-left display-block"><?php echo _l('Tạo Đơn Hàng'); ?></a>
    </div>

    <div class="layout-mobile">
        <?php if ($isAppMobile) {?>
            <div class="row">
                <div class="col-md-2 col-xs-6">
                    <div class="form-group">
                        <label for="province">Tỉnh/Thành</label>
                        <select data-live-search="true" class="form-control selectpicker" id="province_filter"
                                name="province_filter">
                            <option value="null">Chọn Tỉnh/Thành</option>
                            <?php foreach ($province as $key => $value): ?>
                                <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                            <?php endforeach; ?>
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

                <div class="col-md-2 col-xs-6">
                    <div class="form-group">
                        <label for="type_customer">Quận Huyện/Thành Phố:</label>
                        <select class="form-control" id="district_filter" name="district_filter">

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-xs-6">
                    <?php echo render_date_input('date_start_customer_order', 'Ngày bắt đầu', $date_start); ?>
                </div>
                <div class="col-md-2 col-xs-6">
                    <?php echo render_date_input('date_end_customer_order', 'Ngày kết thúc', $date_end); ?>
                </div>


                <div class="col-md-4 col-xs-12">
                    <button class="btn btn-info mtop25" type="button" onclick="<?= (!$isAppMobile) ? 'load_table_customer_tab4()':'load_mobile_customer_tab4()'?>">Lọc danh sách</button>
                    <a class="btn btn-success mtop25" type="button" id="btn-export-excel">Xuất ra excel</a>
                </div>
                <div class="clearfix"></div>
            </div>
        <?php } else {?>

            <div class="row">
                <div class="col-md-2 col-xs-6">
                    <div class="form-group">
                        <label for="province">Tỉnh/Thành</label>
                        <select data-live-search="true" class="form-control selectpicker" id="province_filter"
                                name="province_filter">
                            <option value="null">Chọn Tỉnh/Thành</option>
                            <?php foreach ($province as $key => $value): ?>
                                <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                            <?php endforeach; ?>
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

                <div class="col-md-2 col-xs-6">
                    <div class="form-group">
                        <label for="type_customer">Quận Huyện/Thành Phố:</label>
                        <select class="form-control" id="district_filter" name="district_filter">

                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-xs-6">
                    <?php echo render_date_input('date_start_customer_order', 'Ngày bắt đầu', $date_start); ?>
                </div>
                <div class="col-md-2 col-xs-6">
                    <?php echo render_date_input('date_end_customer_order', 'Ngày kết thúc', $date_end); ?>
                </div>


                <div class="col-md-4 col-xs-12">
                    <button class="btn btn-info mtop25" type="button" onclick="<?= (!$isAppMobile) ? 'load_table_customer_tab4()':'load_mobile_customer_tab4()'?>">Lọc danh sách</button>
                    <a class="btn btn-success mtop25" type="button" id="btn-export-excel">Xuất ra excel</a>
                </div>
                <div class="clearfix"></div>
            </div>

        <?php }?>

    </div>
    <?php if ($isAppMobile): ?>


        <div class="">

            <div class="init-data-mobile">


                <ul class="scroll-list-tab4">
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


    <?php else: ?>


        <div class="col-md-12">


            <div class="tab-cover">
                <div class="tab1_pick tab-table">
                    <table id="table_customer_order" class="table table table-striped table-debts_customer_detail">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Ngày Tạo</th>
                            <th>product</th>
                            <th>Mã yêu cầu</th>
                            <th>Khách Hàng</th>
                            <th>Mã đơn hàng</th>
                            <th>Dịch Vụ Super Ship</th>
                            <th>Thu hộ</th>
                            <th>Họ và Tên Người Nhận</th>
                            <th>SDT</th>
                            <th>SDT Phụ</th>
                            <th>Địa Chỉ Khách hàng</th>
                            <th>Phường Xã</th>
                            <th>Quận Huyện/Thành Phố</th>
                            <th>Tỉnh</th>
                            <th>Tổng Tiền</th>
                            <th>Cân Nặng</th>
                            <th>Thể Tích</th>
                            <th>Mã Đơn Hàng</th>
                            <th>Ghi Chú</th>
                            <th>Dịch Vụ</th>
                            <th>Cấu Hình</th>
                            <th>Người Trả Phí</th>
                            <th>Đổi/Lấy Hàng Về</th>
                            <th>Giá Trị Đơn Hàng</th>
                            <th>Người tạo</th>
                            <th>Người tạo</th>
                            <th>status</th>
                            <th>Cài Đặt</th>

                        </tr>
                        </thead>
                        <tbody></tbody>

                    </table>

                </div>


            </div>


        </div>
    <?php endif; ?>

</div>

</body>
</html>
