<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<style>
    #create_order_ob .control-label, #create_order_ob label {
        margin-bottom: 0;
    }
    .mb-10{
        margin-bottom: 10px;
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
    .kh-tab4 .control-label {
        font-size: 15px !important;

    }

    .kh-tab4 .order-style .button-blue {
        border: 1px solid #41719B;
        background: #5794CC;
        width: 35%;
        padding: 5px;
    }

    .kh-tab4 p {
        margin: 0 0 0px !important;
    }

    .kh-tab4 .order-style .button-blue:hover {
        background: #d9dbdd;
    }

    .kh-tab4 table tbody tr:hover {
        background: #FBC2C4 !important;
    }

    .kh-tab4 table {
        margin: 0 auto;
        width: 100%;
        clear: both;
        border-collapse: collapse;
        table-layout: fixed;
    / / add this word-wrap: break-word;
    / / add this
    }

    .kh-tab4 .order-style .button-red {
        border: 1px solid #41719B;
        background: red;
        padding: 6px;
    }

    .kh-tab4 .order-style .button-green {
        border: 1px solid #41719B;
        background: green;
        padding: 6px;
    }

    .kh-tab4 .mb-5 {
        margin-bottom: 15px
    }

    .kh-tab4 .mb-15 {
        margin-bottom: 15px
    }

    .kh-tab4 .mr-2 {
        margin-right: 2px
    }

    .kh-tab4 .label.label-xs {
        font-size: 17px;
        padding: 1px 4px;
    }

    .kh-tab4 .label-orange {
        background: white;
        color: #e52228;
        border: 1px solid green;
    }

    .kh-tab4 .select2 {
        width: 100% !important;
    }


    .kh-tab4 table thead th:last-child {
        width: 30% !important;
    }
    .kh-tab4 table thead th:nth-last-child(-n+2) {
        width: 40%  !important;
    }

    .kh-tab4 table tbody .sorting_1 {
        width: 200px !important;
    }



    .kh-tab4 table tbody .sorting_1 {
        width: 200px !important;
    }
    .kh-tab4 .dataTables_length label {
        font-size: 15px;
    }

    .kh-tab4 .dataTables_length select {
        width: 50px;
        height: 30px;
    }

    .kh-tab4 .select2-selection__rendered {
        line-height: 31px !important;
    }

    .kh-tab4 .select2-container .select2-selection--single {
        height: 35px !important;
    }

    .kh-tab4 .select2-selection__arrow {
        height: 34px !important;
    }
    input[type=checkbox]{
        text-align: center;
        transform: scale(1.5);
    }
</style>

<div class="row kh-tab4">

    <div class="col-md-12 col-xs-12">
        <a style="margin-bottom:10px;font-size: 13px;padding: 15px;" href="javascript:;"
           class="open-modal-addnew-create-order btn btn-info pull-left display-block"><?php echo _l('Tạo Đơn Hàng'); ?></a>
        <button style="margin-bottom:10px;font-size: 13px;padding: 15px;margin-left: 10px" href="javascript:;" class="open-modal-addnew-create-order-excel btn btn-success pull-left display-block">
            <?php echo _l('Tải Excel'); ?>
        </button>
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

        <div class="row col-md-12">
            <div class="panel_s">
                <div class="">
                    <div class="col-md-12">
                        <h3>CHỈ MỤC TÌM KIẾM</h3>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading"/>

                    <div class="clearfix"></div>

                    <div class="row form-horizontal">

                        <input type="hidden" id="search_fast" name="search_fast"
                               value="<?php echo (isset($_GET['search_fast'])) ? $_GET['search_fast'] : ""; ?>">
                        <div class="col-md-5">
                            <div class="col-md-12 no-padding mb-5">
                                <div class="col-sm-3 control-label">Ngày Tạo</div>
                                <div class="col-sm-9 " style="display: flex">

                                    <input class="form-control datetimepicker-date"
                                           value=""
                                           id="order-from-date-tab4"
                                           type="input"> &nbsp;
                                    <input class="form-control datetimepicker-date"
                                           value=""
                                           id="order-to-date-tab4" type="input">
                                </div>


                            </div>
                            <div class="col-md-12 no-padding mb-5">
                                <div class="col-sm-3 control-label ">Mã Đơn / SĐT</div>
                                <div class="col-sm-9 " style="display: flex">
                                    <input class="form-control" onkeyup="emptyDate()" id="code_request" type="text">
                                    &nbsp;
                                </div>
                            </div>



                        </div>
                        <div class="col-md-7 mb-15">
                            <div class="col-md-12 no-padding mb-5">
                                <div class="col-sm-2 control-label">Mã Đơn KH</div>
                                <div class="col-sm-9 " style="display: flex">
                                    <input class="form-control" onkeyup="emptyDate()" id="code_order" type="text">
                                    &nbsp;
                                </div>
                            </div>

                            <div class="col-md-12 no-padding mb-5">
                                <div class="col-sm-2 control-label">Tỉnh / Huyện</div>
                                <div class="col-sm-9 " style="display: flex">
                                    <select onchange="getDistrictTab4(this)" id="cityTab4" name="city">

                                    </select>
                                    &nbsp;
                                    <select id="kh-district-tab4" name="district">
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class=" col-md-12 mb-10">

                        <button class="btn btn-sm btn-primary button-red mr-2" onclick="clickSearchTab4()"
                                style="width: 15%;">Tìm Kiếm
                        </button>
                        <button class="btn btn-sm btn-primary button-red mr-2" onclick="clickPrintA5()"
                                style="width: 10%;float: right">In A5
                        </button>
                        <button class="btn btn-sm btn-primary button-red mr-2" onclick="clickSearchTab4()"
                                style="width: 10%;float: right">In K5
                        </button>
                        <button class="btn btn-sm btn-primary button-red mr-2" onclick="clickSearchTab4()"
                                style="width: 10%;float: right">In S9
                        </button>

                    </div>
                </div>

                <div class="">

                    <div class="col-md-12" id="table-order-wrapper">
                        <table id="example" class="table table-bordered table-striped"
                               style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                        ", Times, serif !important;font-size: 12px !important;">
                        <thead >
                        <tr>
                            <th style="width: 5% !important;text-align: center"><input type="checkbox" onchange="toggleCheckBox(this,'check_id[]')"></th>
                            <th style="width: 5% !important;">STT</th>
                            <th style="width: 10% !important;">ĐƠN HÀNG</th>
                            <th style="width: 10% !important;">GÓI HÀNG</th>
                            <th style="width: 40% !important;">NGƯỜI NHẬN</th>
                            <th style="width: 30% !important;">NỘI DUNG</th>
                        </tr>
                        </thead>

                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true"
             id="modal-update">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="padding: 7px !important;display: flex">
                <span>
                    <h5 class="modal-title">Ghi chú nội bộ</h5>
                </span>
                        <span style="margin-left: auto;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </span>

                    </div>
                    <div class="modal-body">
                        <label>Ghi Chú Nội bộ</label>
                        <p><textarea id="ghi-chu-noi-bo-old" rows="5" cols="80"></textarea></p>
                    </div>
                    <div class="modal-body">
                        <label>Cập Nhật Mới Ghi Chú Nội bộ</label>
                        <input type="hidden" id="shop_id">
                        <p><textarea id="ghi-chu-noi-bo-new" rows="20" cols="80"></textarea></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="updateNode()">Cập nhật</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
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


<?php endif; ?>

</div>

</body>
</html>
