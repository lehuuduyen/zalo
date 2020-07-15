<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<style media="screen">
    #header, #mobile-search {
        display: none;
    }

    #wrapper.shiper {
        padding: 0;
        margin: 0;
    }

    #wrapper.shiper .table > tbody > tr > td {
        padding: 1px 5px;
        font-size: 12px;
    }

    table {
        width: 750px;
        margin-top: 0;
        border-collapse: collapse;
        margin: auto;
    }

    /* Zebra striping */
    tr {
        background: #fff;
    }

    th {
        background: #3498db;
        color: white;
        font-weight: bold;
    }

    td, th {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
        font-size: 18px;
    }

    /*
    Max width before this PARTICULAR table gets nasty
    This query will take effect for any screen smaller than 760px
    and also iPads specifically.
    */
    @media only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px) {

        table {
            width: 100%;
        }

        /* Force table to not be like tables anymore */
        .need table, .need thead, .need tbody, .need th, .need td, .need tr {
            display: block;
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr {
            border: 1px solid #ccc;
        }

        .mr-2 {
            margin-right: 5px;
        }

        td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
            font-size: 14px;
        }

        td:before {

            display: none;
            padding-right: 10px;
            white-space: nowrap;
            float: left;
            content: attr(data-column);
            font-size: 15px;
            color: #000;
            font-weight: bold;
        }

    }

    .row {
        margin: 0;
        padding: 10px;
    }

    .cover-table {
        display: block;
        overflow: auto;
        padding: 0;
        padding-top: 34px;
    }

    .mobile .btn.btn-custom {
        font-size: 10px;
        padding: 3px 7px;
        display: block;
        float: left;
        width: 30%;
    }

    .mobile .btn.btn-custom.right {
        float: right;

    }

    .mobile .btn.btn-custom.center {
        margin-left: 5%;
    }

    .bold-shop {
        font-weight: bold;
        font-size: 14.5px;
    }
    .bold-shop1 {
        font-weight: bold;
        font-size: 14.5px;
    }

    .clear-fix::before {
        content: "";
        clear: both;
        display: table;
    }

    .shiper-table tr {
        padding-top: 7px;
        padding-bottom: 7px;
    }

    .shiper-table tr td:nth-child(1) {
        border-top: none;
    }

    .shiper-table tr td:last-child {
        border-bottom: none;
    }

    .btn-primary.left {
        background: #a73b3b;
        font-weight: bold;
    }

    .footer-nav {
        display: flex;
        justify-content: space-around;
        background: #fff;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 60px;
        align-items: center;
        border-top: 1px solid #ddd;
    }

    .footer-nav a {
        display: block;
        text-align: center;
    }

    .footer-nav a i {
        display: block;
        color: #ddd;
        font-size: 25px;
    }

    .footer-nav a span {
        color: #ddd;
        font-size: 12px;
    }

    .footer-nav a.active i, .footer-nav a.active span {
        color: #a73a3b;
    }

    table.table {
        margin: 0;
    }

    .table-data {
        display: none;
    }

    .table-data.active {
        display: block;
    }

    .header-table {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px 0;
        background: #a73a3b;
        z-index: 55;
    }

    .header-table h5 {
        margin: 0;
        color: #fff;
        text-align: center
    }

    .data-empty {
        display: block;
        width: 90%;
        padding: 20px;
        text-align: center;
        border: 3px dotted #aaa;
        margin: 0 auto;
        margin-top: 30px;
    }

    .disable-view {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999999999999;
        background: #fff;
    }

    .lds-ellipsis {
        display: block;
        position: absolute;
        width: 64px;
        height: 64px;
        margin: 0 auto;
        top: 50%;
        margin-top: -32px;
        margin-left: -32px;
        left: 50%;
    }

    .lds-ellipsis div {
        position: absolute;
        top: 27px;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: #a73a3b;
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

    .select2-selection__rendered {
        line-height: 31px !important;
    }

    .select2-container .select2-selection--single {
        height: 35px !important;
    }

    .select2-selection__arrow {
        height: 34px !important;
    }

    .select2 {
        width: 100% !important;
    }

    #limit_geted {

        position: absolute;
        top: 0;
        right: 10px;
        margin: 0;
        border: 1px solid #fff;
        color: #fff;
        margin-top: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        display: none;
    }

    #limit_geted label {
        color: #fff;
        margin-bottom: 0;
    }

    #limit_geted select {
        outline: none;
        background: none;
        border: none;
    }

    #myBtn {
        display: none;
        position: fixed;
        bottom: 64px;
        right: 5px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        /* background-color: red; */
        /* color: white; */
        cursor: pointer;
        padding: 8px;
        border-radius: 4px;
    }

    #myBtn:hover {
        background-color: #555;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>

<div id="wrapper" class="shiper">

    <div class="disable-view">
        <div id="loader-repo" class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="cover-table">
        <div class="header-table">
            <h5 style="text-align: left;padding-left:10px;">Danh Sách Đơn Hàng Chưa Lấy</h5>
            <div class="form-group" id="limit_geted">
                <label for="limit_geted">Hiển thị</label>
                <select name="limit_geted">
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">Tất cả</option>
                </select>
            </div>

        </div>


        <div class="need table-data table2">

            <?php if (sizeof($regs) > 0): ?>
                <table class="table shiper-table">

                    <tbody>


                    </tbody>
                </table>
            <?php else : ?>
                <p class="data-empty">Chưa Có Danh Sách Đơn Hàng Đăng Ký</p>
            <?php endif; ?>


        </div>


        <div class="need table-data active table1">

            <?php if (sizeof($waits) > 0): ?>
                <table class="table shiper-table">

                    <tbody>

                    </tbody>
                </table>
            <?php else: ?>
                <p class="data-empty">Chưa Có Danh Sách Điểm Đơn Hàng </p>
            <?php endif; ?>


        </div>


        <div class="need table-data table3">
            <table class="table shiper-table">
                <tbody>

                </tbody>
            </table>

        </div>


        <div class="need table-data table4">


            <div class="row">
                <div class="panel_s">

                    <div class="panel-body">
                        <h4 class="no-margin">
                            Tài Khoản
                        </h4>
                        <hr class="hr-panel-heading">

                        <div class="clearfix"></div>
                        <?php echo staff_profile_image($current_user->staffid, array('img', 'img-responsive', 'staff-profile-image-thumb'), 'thumb'); ?>
                        <div class="profile mtop20 display-inline-block">
                            <h4>
                                <?php echo $user_display->lastname . ' ' . $user_display->firstname ?>
                            </h4>
                            <p class="display-block">
                                <i class="fa fa-envelope"></i>
                                <a href="mailto:<?php echo $user_display->email; ?>"><?php echo $user_display->email; ?></a>

                            </p>
                            <p>
                                <i class="fa fa-phone-square"></i>
                                <?php echo $user_display->phonenumber ?>
                            </p>
                        </div>
                        <div class="button-footer">
                            <a style="margin-right:10px;" class="btn btn-default"
                               href="/system/admin/authentication/logout">
                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                <span>Đăng Xuất</span>
                            </a>
                            <a class="btn btn-primary change-password-modal" href="javascript:">
                                <i class="fa fa-key" aria-hidden="true"></i>
                                <span>  Đổi Mật Khẩu</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="need table-data  table5">
            <button onclick="topFunction()" id="myBtn" title="Go to top">
                <i class="fa fa-arrow-up" aria-hidden="true"></i>

            </button>
            <div class="row" style="background-color: #b5b5b5">
                <div style="float:right;border: 3px solid red;padding: 5px;font-weight: bold;">
                    Tổng: <span id="tong_5">0</span>
                </div>
                <br>
                <br>
                <div class="w-100" style="margin-right: 2px;margin-top:10px">
                    <select id="date_create">
                        <option value=""></option>
                        <?php
                        foreach ($list_delivery['date_create'] as $date_create) {
                            ?>
                            <option value="<?= $date_create ?>"><?= $date_create ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="w-100" style="margin: 20px 0px">
                    <select id="code_supership">
                        <option value=""></option>

                        <?php
                        foreach ($list_delivery['code_supership'] as $code_supership) {
                            ?>
                            <option value="<?= $code_supership ?>"><?= $code_supership ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>

                <div style="margin: 20px 0px" class="w-100">
                    <select id="address">
                        <option value=""></option>
                        <?php
                        foreach ($list_delivery['addresss'] as $addresss) {
                            ?>
                            <option value="<?= $addresss ?>"><?= $addresss ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <button class="btn-primary btn-custom left" style="padding: 5px;" onclick="btnTable5Search()">
                        Hiển Thị Tùy Chỉnh
                    </button>
                    <button class="btn-success btn-custom right" style=" float:right;padding: 5px;"
                            onclick="btnTable5All()" style="float:right">
                        Hiển Thị Toàn Bộ
                    </button>
                </div>
                <div>&nbsp;</div>

            </div>


            <table class="table shiper-table">

                <tbody id="scroll" style="height: 600px;">

                </tbody>
            </table>


        </div>
        <div class="table-data  table6">

            <div class="row" style="background-color: #b5b5b5">
                <div class="form-group" app-field-wrapper="date_end_customer">
                    <label for="date_end_customer" class="control-label">Ngày Bắt Đầu</label>
                    <div class="input-group date">
                        <input type="text" id="date_create_start" name="date_end_customer"
                               class="form-control datetimepicker-date" value="<?= $date_from ?>" autocomplete="off">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar calendar-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="form-group" app-field-wrapper="date_end_customer">
                    <label for="date_end_customer" class="control-label">Ngày kết thúc</label>
                    <div class="input-group date">
                        <input type="text" id="date_create_end" name="date_end_customer"
                               class="form-control datetimepicker-date" value="<?= $date_to ?>" autocomplete="off">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar calendar-icon"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <button class="btn-primary btn-custom left" style="padding: 5px;" onclick="btnTable6Search()">
                        Hiển Thị Tùy Chỉnh
                    </button>
                </div>
                <div style="margin-top:20px">
                    <table class="table table-hover">
                        <tbody>
                        <tr class="success">
                            <td>
                                <div class="bold-shop">
                                    Tổng Số Đơn Báo Cáo
                                </div>
                            </td>
                            <td>
                                <span style="color:blue" id="baocao1"></span>
                            </td>
                        </tr>
                        <tr class="danger">
                            <td>
                                <div class="bold-shop">
                                    Tổng Số Đơn Chưa Báo Cáo
                                </div>
                            </td>
                            <td>
                                <span style="color:blue" id="baocao2"></span>
                            </td>
                        </tr>
                        <tr class="info">
                            <td>
                                <div class="bold-shop">
                                    Tổng Số Tiền Đã Thu
                                </div>
                            </td>
                            <td>
                                <span style="color:blue" id="baocao3"></span>
                            </td>
                        </tr>
                        <tr class="active">
                            <td>
                                <div class="bold-shop">
                                    Số Đơn Giao Thành Công
                                </div>
                            </td>
                            <td>
                                <span style="color:blue" id="baocao4"></span>
                            </td>
                        </tr>
                        <tr class="warning">
                            <td>
                                <div class="bold-shop">
                                    Số Đơn Giao Thất Bại
                                </div>
                            </td>
                            <td>
                                <span style="color:blue" id="baocao5"></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>





                </div>


                                <table class="table shiper-table">

                                    <tbody id="scroll" style="height: 600px;">

                                    </tbody>
                                </table>


                            </div>
                        </div>
                        <!-- Table -->

                        <div class="footer-nav">

                            <a class="active tab tab1" data-tab="1" href="javascript:">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span>Điểm Chờ</span>
                            </a>


                            <a class=" tab tab2" data-tab="2" href="javascript:">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span> Đã Đăng Kí</span>
                            </a>
                            <a class=" tab tab5" data-tab="5" href="javascript:">
                                <i class="fa fa-shopping-cart " aria-hidden="true"></i>
                                <span>Giao Hàng</span>
                            </a>
                            <a class=" tab tab3" data-tab="3" href="javascript:">
                                <i class="glyphicon glyphicon-time" aria-hidden="true"></i>
                                <span>Lịch Sử</span>
                            </a>
                            <a class=" tab tab6" data-tab="6" href="javascript:">
                                <i class="fa fa-area-chart" aria-hidden="true"></i>
                                <span>Báo Cáo</span>
                            </a>


                            <a class=" tab tab4" data-tab="4" href="javascript:">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                <span>Tài Khoản</span>
                            </a>
                        </div>
                    </div>
                    <?php init_tail(); ?>


                    <div class="modal fade" id="modal-confirm-order" role="dialog">
                        <div class="modal-dialog">
                            <?php echo form_open(admin_url('customer_policy/add_policy'), array('id' => 'confirm_shiper', "method" => 'post')); ?>

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Nhập Số Đơn</h4>
                                </div>
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="pwd">Nhập Số Đơn</label>
                                        <input required type="text" placeholder="Nhập Số Đơn" class="form-control"
                                               id="order_get" name="order_get" onkeyup="formatNumBerKeyUp(this)">
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                    <button type="submit" name="button" class="btn btn-primary">Xác Nhận</button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>

                        </div>
                    </div>


                    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
                         aria-hidden="true"
                         id="modal_update_status">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="padding: 7px !important;display: flex">
                <span>
                    <h5 class="modal-title">Đã giao</h5>
                </span>
                                    <span style="margin-left: auto;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </span>

                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="delivery_id">
                                    <input type="hidden" id="shop_id">
                                    <div class="m-heading-2 border-grey-mint m-bordered">
                                        <div>
                                            <span style="    font-size: 15px;" id="popup-code-delivery"></span>
                                            <span id=""> | </span>
                                            <span style="font-weight: bold;    font-size: 15px;"
                                                  id="popup-code-order"></span>
                                        </div>
                                        <div id="popup-address">
                                        </div>
                                    </div>
                                    <div id="view_da_giao" style="height: 50px;" class="hidden">
                                        <div class="col-md-4">Trạng Thái <sub style="color:red">[*]</sub></div>
                                        <div class="col-md-4" style="display: flex;"><input type="radio"
                                                                                            key="da_giao_hang"
                                                                                            name="status"
                                                                                            value="Đã Giao Hàng Toàn Bộ">
                                            <div
                                                    style="margin-top: 2px;margin-left: 5px">Đã Giao Hàng Toàn Bộ
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="display: flex;"><input type="radio"
                                                                                            key="da_giao_hang"
                                                                                            name="status"
                                                                                            value="Đã Giao Hàng Một Phần">
                                            <div
                                                    style="margin-top: 2px;margin-left: 5px">Đã Giao Hàng Một Phần
                                            </div>
                                        </div>
                                    </div>
                                    <div id="view_hoan_giao" style="height: 150px;" class="hidden">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">Tại Sao? <sub style="color:red">[*]</sub></div>
                                        <div class="col-md-8">

                                            <div class="col-md-12" style="display: flex;">
                                                <input type="radio" key="hoan_giao" name="status"
                                                       value="Không Nghe Máy/Không Gọi Được">
                                                <div
                                                        style="margin-top: 2px;margin-left: 5px">Không Nghe Máy/Không
                                                    Gọi Được
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="display: flex;">
                                                <input type="radio" key="hoan_giao" name="status" value="Địa Chỉ Sai">
                                                <div
                                                        style="margin-top: 2px;margin-left: 5px">Địa Chỉ Sai
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="display: flex;">
                                                <input type="radio" key="hoan_giao" name="status" value="Lý Do Khác">
                                                <div
                                                        style="margin-top: 2px;margin-left: 5px">Lý Do Khác
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="display: flex;">
                            <textarea type="text" key="hoan_giao" class="form-control ly_do_khac1 hidden"
                                      value="Lý Do Khác"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="view_khong_giao_duoc" style="height: 150px;" class="hidden">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">Tại Sao? <sub style="color:red">[*]</sub></div>
                                        <div class="col-md-8">
                                            <div class="col-md-12" style="display: flex;">
                                                <input type="radio" key="khong_giao_duoc" name="status"
                                                       value="Khách Không Đồng Ý Nhận">
                                                <div
                                                        style="margin-top: 2px;margin-left: 5px">Khách Không Đồng Ý Nhận
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="display: flex;">
                                                <input type="radio" key="khong_giao_duoc" name="status"
                                                       value="Shop Yêu Cầu Hủy Đơn">
                                                <div
                                                        style="margin-top: 2px;margin-left: 5px">Shop Yêu Cầu Hủy Đơn
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="display: flex;">
                                                <input type="radio" name="status" key="khong_giao_duoc"
                                                       value="Lý Do Khác">
                                                <div
                                                        style="margin-top: 2px;margin-left: 5px">Lý Do Khác
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="display: flex;">
                            <textarea type="text" key="khong_giao_duoc" class="form-control ly_do_khac2 hidden"
                                      value="Lý Do Khác"></textarea>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-danger" onclick="updateStatus()">Xác Nhận
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="modalBtnDaTra" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Xác Nhận Đã Trả</h4>
                                </div>
                                <form action=""  method="post" id="form_da_tra" accept-charset="utf-8" enctype="multipart/form-data">
                                    <?php echo form_open_multipart('/shiper/confirm_da_tra/', array('id' => 'form-login', 'class' => 'form-horizontal'));?>


                                    <div class="modal-body">


                                        <p class="bold-shop">Tổng Số Đơn: <span style="color:blue" id="tong_so_don"></span></p>
                                        <hr>
                                        <p class="bold-shop">Danh Sách Đơn: </p>
                                        <div id="don_da_tra">

                                        </div>
                                        <hr>
                                        <div class="w-100">
                                            <input style="display:none" type="file" id="imgInp" name='user_file' capture="camera">

                                            <button onclick='$("#imgInp").trigger("click");' type="button" class="btn btn-primary">Thêm Ảnh </button>

                                            <img style="width: 50%;" id="blah" src="#" alt="your image" />

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                        <button type="submit" disabled name="button" id="buttonTraVe" class="btn btn-primary">Xác Nhận</button>

                                    </div>
                                </form>
                            </div>
                            <?php echo form_close(); ?>

                        </div>
                    </div>
    <div class="modal fade" id="modal-edit-password" role="dialog">
                        <div class="modal-dialog">
                            <?php echo form_open('/shiper/password', array('id' => 'password_shiper', "method" => 'post')); ?>

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Thay đổi mật khẩu</h4>
                                </div>
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="pwd">Mật Khẩu Cũ</label>
                                        <input required type="password" placeholder="Mật Khẩu Cũ" class="form-control"
                                               id="oldpassword" name="oldpassword">
                                    </div>

                                    <div class="form-group">
                                        <label for="pwd">Mật Khẩu Mới</label>
                                        <input required type="password" placeholder="Mật Khẩu Mới" class="form-control"
                                               id="newpasswordr" name="newpasswordr">
                                    </div>

                                    <div class="form-group">
                                        <label for="pwd">Mật Khẩu Xác Nhận</label>
                                        <input required type="password" placeholder="Mật Khẩu Xác Nhận"
                                               class="form-control" id="new_confirm_pass" name="new_confirm_pass">
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary">Xác Nhận</button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>

                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
                    <script>
                        jQuery(function ($) {
                            $.datepicker.regional["vi-VN"] =
                                {
                                    closeText: "Đóng",
                                    prevText: "Trước",
                                    nextText: "Sau",
                                    currentText: "Hôm nay",
                                    monthNames: ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng mười một", "Tháng mười hai"],
                                    monthNamesShort: ["Một", "Hai", "Ba", "Bốn", "Năm", "Sáu", "Bảy", "Tám", "Chín", "Mười", "Mười một", "Mười hai"],
                                    dayNames: ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"],
                                    dayNamesShort: ["CN", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy"],
                                    dayNamesMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
                                    weekHeader: "Tuần",
                                    dateFormat: "dd/mm/yy",
                                    firstDay: 1,
                                    isRTL: false,
                                    showMonthAfterYear: false,
                                    yearSuffix: ""
                                };

                            $.datepicker.setDefaults($.datepicker.regional["vi-VN"]);
                        });
                        $('.datetimepicker-date').datepicker({
                            format: 'd-m-Y',
                            timePicker: false,
                        });
                    </script>
                    <script>

                        $("#date_create").select2({
                            placeholder: "Vui Lòng Chọn Ngày Tạo",
                            allowClear: true
                        });
                        $("#code_supership").select2({
                            placeholder: "Vui Lòng Chọn Mã Đơn Hàng",
                            allowClear: true
                        });
                        $("#address").select2({
                            placeholder: "Vui Lòng Chọn Nhóm Địa Chỉ Giao Hàng",
                            allowClear: true
                        });
                        setTimeout(function () {
                            $('.disable-view').fadeOut();
                        }, 100);

                        var checkAlert = <?php echo isset($_SESSION['success']) ? 'true' : 'false'?>;
                        var success2 = <?php echo isset($_SESSION['success2']) ? 'true' : 'false'?>;
                        var success3 = <?php echo isset($_SESSION['success3']) ? 'true' : 'false'?>;
                        var errorReg = <?php echo isset($_SESSION['error3']) ? 'true' : 'false'?>;
                        var tab1 = false;
                        var tab1 = <?php echo $tab2 ? 'true' : 'false'?>;

                        var error_change_pass = <?php echo isset($_SESSION['error_change_pass']) ? 'true' : 'false'?>;
                        var sucess_change_pass = <?php echo isset($_SESSION['sucess_change_pass']) ? 'true' : 'false'?>;
                        var mess_pass = <?php echo isset($_SESSION['mess_pass']) ? "'" . $_SESSION['mess_pass'] . "'" : 'false'?>;

                        $('#password_shiper').validate({
                            errorClass: 'error text-danger',
                            highlight: function (element) {
                                $(element).parent().addClass("has-error");
                            },
                            unhighlight: function (element) {
                                $(element).parent().removeClass("has-error");
                            },
                            onfocusout: false,
                            invalidHandler: function (form, validator) {
                                var errors = validator.numberOfInvalids();
                                if (errors) {
                                    validator.errorList[0].element.focus();
                                }
                            },
                            rules: {
                                oldpassword: {
                                    required: true
                                },
                                newpasswordr: {
                                    required: true
                                },
                                new_confirm_pass: {
                                    equalTo: "#newpasswordr"
                                }
                            }
                        });

                        $('input[type=radio][name=status]').change(function () {
                            if (this.value == "Lý Do Khác") {
                                if (this.getAttribute("key") == "hoan_giao") {
                                    $(".ly_do_khac1").removeClass('hidden')
                                } else {
                                    $(".ly_do_khac2").removeClass('hidden')
                                }
                            } else {
                                $(".ly_do_khac1").addClass('hidden');
                                $(".ly_do_khac2").addClass('hidden')
                            }

                        });


                        if (error_change_pass) {
                            $('.header-table h5').text('Tài Khoản');
                            $('#modal-edit-password').modal('show');
                            setTimeout(function () {
                                alert(mess_pass);
                            }, 100);

                        }
                        if (sucess_change_pass) {
                            alert_float('success', 'Thay đổi mật khẩu thành công');
                        }

                        if (tab1 === true) {
                            $('.header-table h5').text('Danh Sách Đơn Hàng Chưa Lấy');
                            $('.table-data').removeClass('active');
                            $('.table1').addClass('active');
                            $('.tab').removeClass('active');
                            $('.tab[data-tab="1"]').addClass('active');

                        }

                        if (checkAlert) {

                            var limit = $('#limit_geted select').val();
                            $('.disable-view').fadeIn();

                            $.ajax({
                                url: `/system/shiper/geted_ajax?limit=${limit}`,
                                success: function (data) {
                                    $('.disable-view').fadeOut();
                                    data = JSON.parse(data);
                                    if (data.length > 0) {
                                        $('.table-data.table3 tbody').empty();
                                        var html = '';

                                        for (var i = 0; i < data.length; i++) {

                                            html += `<tr><td>`;
                                            if (data[i].customer_shop_code) {
                                                html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                                            } else {
                                                html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                                            }


                                            html += `<td>
              <span><b>Đ/C:</b> <span style="font-weight:bold;color:blue;">${data[i].repo_customer}</span> </span>
            </td>
            <td>
              <b>Ghi Chú:</b>
              <span style="color:#a73b3b;font-weight:bold">${data[i].note}</span>

            </td>`;

                                            html += `<td>
             <b>Người lấy:</b>
             <span>
               ${data[i].lastname} ${data[i].firstname} - Số Đơn: ${data[i].number_order_get}
             </span>
            </td>`;


                                            html += `</tr>`;

                                        }
                                        $('.table-data.table3 tbody').append(html);
                                    } else {
                                        $('.table-data.table3 tbody').empty();
                                        var html = `<tr><td><p class="data-empty">Chưa Có Danh Sách Đã Lấy Hàng </p></td></tr>`;
                                        $('.table-data.table3 tbody').append(html);
                                    }

                                }
                            });


                            alert_float('success', 'Xác Nhận Thành Công');
                            $('.header-table h5').text('Danh sách đơn hàng đã xử lý');
                            $('.table-data').removeClass('active');
                            $('.table3').addClass('active');
                            $('.tab').removeClass('active');
                            $('.tab[data-tab="3"]').addClass('active');
                        }
                        if (success2) {
                            alert_float('success', 'Đăng Kí Thành Công');
                        }
                        if (success3) {
                            alert_float('success', 'Nhả Điểm Thành Công');
                        }
                        if (errorReg) {
                            alert_float('danger', 'Đơn hàng đã có người đăng ký');

                        }

                        $('.confirm_get').click(function () {

                            var id = $(this).attr('data-id');

                            $('#modal-confirm-order').modal('show');
                            $('#modal-confirm-order form').attr('action', '/system/shiper/confirm/' + id);
                        });

                        $(document).on("click", ".confirm_get", function () {

                            var id = $(this).attr('data-id');

                            $('#modal-confirm-order').modal('show');
                            $('#modal-confirm-order form').attr('action', '/system/shiper/confirm/' + id);
                        });


                        var h = window.innerHeight;
                        var heightContainer = h - (60);
                        $('.cover-table').css('height', heightContainer);


                        function date_format(date) {
                            var today = new Date(date);
                            var day = today.getDate() + "";
                            var month = (today.getMonth() + 1) + "";
                            var year = today.getFullYear() + "";
                            var hour = today.getHours() + "";
                            var minutes = today.getMinutes() + "";
                            var seconds = today.getSeconds() + "";

                            day = checkZero(day);
                            month = checkZero(month);
                            year = checkZero(year);
                            hour = checkZero(hour);
                            mintues = checkZero(minutes);
                            seconds = checkZero(seconds);
                            return day + "/" + month + "/" + year + " " + hour + ":" + minutes + ":" + seconds;

                        }

                        function checkZero(data) {
                            if (data.length == 1) {
                                data = "0" + data;
                            }
                            return data;
                        }

                        function btnTable5All() {
                            let data = {
                                // date_form:(date_form)?moment(new Date(convertDate(date_form))).format('YYYY/MM/DD'):"",
                                staff:<?=$current_user?>
                            };
                            callTable5(data)
                        }

                        function btnTable5Search() {
                            let dateCreate = $("#date_create").val();
                            let data = {
                                // date_form:(date_form)?moment(new Date(convertDate(date_form))).format('YYYY/MM/DD'):"",
                                staff:<?=$current_user?>,
                                date_create: (dateCreate) ? moment(new Date(convertDate(dateCreate))).format('YYYY-MM-DD') : "",
                                code_supership: $("#code_supership").val(),
                                address: $("#address").val(),
                            };
                            callTable5(data)
                        }
                        function btnTable6Search() {
                            let dateCreateStart = ($("#date_create_start").val()) ? moment(new Date(convertDate($("#date_create_start").val()))).format('YYYY/MM/DD') : "";
                            let dateCreateEnd = ($("#date_create_end").val()) ? moment(new Date(convertDate($("#date_create_end").val()))).format('YYYY/MM/DD') : "";
                            let data = {
                                // date_form:(date_form)?moment(new Date(convertDate(date_form))).format('YYYY/MM/DD'):"",
                                staff:<?=$current_user?>,
                                date_create_start:dateCreateStart,
                                date_create_end:dateCreateEnd
                            };
                            callTable6(data)
                        }
                        function callTable6(data) {
                            $.ajax({
                                url: '/system/Shiper/get_delivery_table6?jsonData=' + JSON.stringify(data),
                                success: function (data) {
                                    $("#baocao1").html(data.data.tong_so_don_bao_cao);
                                    $("#baocao2").html(data.data.tong_so_don_chua_bao_cao);
                                    $("#baocao3").html(formatCurrency(data.data.tong_so_tien_da_thu));
                                    $("#baocao4").html(data.data.so_don_giao_thanh_cong);
                                    $("#baocao5").html(data.data.so_don_giao_that_bai);
                                }
                            });
                        }
                        function convertDate(userDate) {
                            str = userDate.split("/");
                            return str[1] + "/" + str[0] + "/" + str[2]
                        }

                        function callTable5(data) {

                            $.ajax({
                                url: '/system/Shiper/get_delivery?jsonData=' + JSON.stringify(data),
                                success: function (data) {
                                    $('.disable-view').fadeOut();

                                    // data = JSON.parse(data);
                                    data = data.data;
                                    if (data.length > 0) {
                                        $("#tong_5").html(data.length);
                                        $('.table-data.table5 tbody').empty();
                                        var html = '';

                                        for (var i = 0; i < data.length; i++) {

                                            var filtered =[]
                                            let address = `${(data[i].address) ? data[i].address + ", " : ""} ${(data[i].ward) ? data[i].ward + ", " : ""} ${(data[i].district) ? data[i].district + ", " : ""}  ${(data[i].city) ? data[i].city : ""} `;
                                            if(data[i].note){
                                                let noteArr = data[i].note.split("\n");
                                                var filtered = noteArr.filter(function (el) {
                                                    if (el != " ") {
                                                        return true
                                                    }
                                                    return false
                                                });
                                            }


                                            let htmlNode = "";
                                            htmlNode += filtered.map(function (note, key) {
                                                if (note.indexOf("/") > 0) {
                                                    var note2Arr = note.split(" ");
                                                    var arrNam = note2Arr[0].split("/");
                                                    note2Arr[0] = arrNam[0] + "/" + arrNam[1];
                                                    var arrGiay = note2Arr[1].split(":");
                                                    note2Arr[1] = arrGiay[0] + ":" + arrGiay[1];
                                                    return `<span style="color:red">${note2Arr.join(' ')}</span>`
                                                }
                                                return `<span style="color:blue">${note}</span>`


                                            }).join('\n');

                                            let htmlButton = `<a class="mr-2 btn btn-custom btn-primary " style="margin-right: 15px" onclick="modalDaGiao(this)"
                                data-code_delivery="${data[i].code_delivery}"
                                data-code_supership="${data[i].code_supership}"
                                data-status_report="${data[i].status_report}"
                                data-delivery_id="${data[i].delivery_id}"

                                data-address="${data[i].address} ${data[i].ward}, ${data[i].district}, ${data[i].city}"
                                data-id="${data[i].id}" ><i style="padding-right: 5px;" class="fa fa-gift"></i>Đã Giao</a>
                                <a class="btn btn btn-custom btn-warning  mr-2 " style="background-color: #cc9a12" onclick="modalHoanGiao(this)"
                                data-code_delivery="${data[i].code_delivery}"
                                data-code_supership="${data[i].code_supership}"
                                data-status_report="${data[i].status_report}"
                                data-delivery_id="${data[i].delivery_id}"

                                data-address="${data[i].address} ${data[i].ward}, ${data[i].district}, ${data[i].city}" data-id="${data[i].id}" ><i style="padding-right: 5px;" class="fa fa-cube"></i>Hoãn Giao</a>
                                <a class="btn btn btn-custom btn-danger right " style="font-size: 9px" onclick="modalKhongGiaoDuoc(this)"
                                data-code_delivery="${data[i].code_delivery}"
                                data-code_supership="${data[i].code_supership}"
                                data-status_report="${data[i].status_report}"
                                data-delivery_id="${data[i].delivery_id}"

                                data-address="${data[i].address} ${data[i].ward}, ${data[i].district}, ${data[i].city}" data-id="${data[i].id}" ><i style="padding-right: 5px;" class="fa fa-bullhorn"></i>Không Giao Được</a>`;
                                            if (data[i].status_report) {
                                                htmlButton = ""
                                            }

                                            html += `<tr style="border-top: 3px solid;"><td>`;
                                            html += `${date_format(data[i].date_create)} - <span class="bold-shop" style="font-size: 13px;color: #a100ff">${data[i].code_delivery}</span> - <span class="bold-shop" style="font-size: 13px;color: #985FD8">${data[i].code_supership}</span>  `;

                                            html += `<td/>`;
                                            html += `<td>`;
                                            html += `<div class="bold-shop" style="text-align:center;font-size:15px;color: red"><span style="color:green">Sản Phẩm: ${data[i].product}</span></div> `;

                                            html += `<td/>`;
                                            html += `<td style="margin-bottom: 25px">`;
                                            html += `<div style="">${htmlButton} </div>`;

                                            html += `<td/>`;
                                            html += `<td>`;
                                            html += `<div class="bold-shop" style="text-align:center;font-size:16px;color: red"><span style="    padding: 2px;"><span style="color:black">Thu Hộ:</span> ${formatCurrency(data[i].collect)} - <span style="color:black">KL:</span> ${formatCurrency(data[i].mass)}</span></div> `;

                                            html += `<td/>`;
                                            html += ` <td><span style="">${data[i].receiver} - ${data[i].phone}</span> <a class="btn btn-custom btn-warning right" href="tel:${data[i].phone}">Gọi Người Nhận</a>`;

                                            html += `<div class="clear-fix"></td>`;
                                            html += `<td style="padding-left: 35px;min-height: 40px;"><a target="_blank" class="copy-address" style="position: absolute;top: -3px;font-size: 30px;    left: 10px;" href="https://www.google.com/maps/search/?api=1&query=${address}"><i class="fa fa-map-marker" aria-hidden="true"></i></a><span><b>Đ/C:</b> <span class="mycopy" style="font-weight:bold;color:blue;">${address}</span> </span></td>`;


                                            html += ` <td><span style="">${data[i].customer_shop_code} - ${data[i].customer_phone}</span> <a class="btn btn-custom btn-primary right" href="tel:${data[i].customer_phone}">Gọi Shop</a>`;

                                            html += `<div class="clear-fix"></td>`;
                                            html += `<td><b>Ghi Chú:</b><div style="color:#a73b3b;white-space: pre-line">${htmlNode}</div> </td>`;


                                            html += `</tr>`;
                                        }
                                        $('.table-data.table5 tbody').append(html);
                                    } else {
                                        $('.table-data.table5 tbody').empty();
                                        var html = `<p class="data-empty">Chưa Có Danh Sách Điểm Đơn Hàng </p>`;
                                        $('.table-data.table5 tbody').append(html);
                                    }

                                }
                            });

                        }

                        function callTable1(){
                            $.ajax({
                                url: '/system/shiper/ajax_wait',
                                success: function (data) {
                                    $('.disable-view').fadeOut();

                                    data = JSON.parse(data);
                                    if (data.length > 0) {
                                        $('.table-data.table1 tbody').empty();
                                        var html = '';

                                        for (var i = 0; i < data.length; i++) {
                                            var codeSupership ="";
                                            html += `<tr><td>`;
                                            if (data[i].customer_shop_code) {
                                                html += `${date_format(data[i].created)} - <span class="bold-shop">${data[i].customer_shop_code}</span> - ${data[i].phone_customer}`;
                                            } else {
                                                html += `${date_format(data[i].created)} - <span class="bold-shop">${data[i].name_customer_new}</span> - ${data[i].phone_customer}`;
                                            }
                                            html += `<td/>`;

                                            if(data[i].order_return.length > 0){
                                                html += `<td ><span class="bold-shop">Tổng Số Đơn: <span style="color:blue">${data[i].number_order_get}</span></span></td> `;

                                                html += ` <td>  `;

                                                $.each(data[i].order_return,function (key,val) {
                                                    html += `<div class="bold-shop1">${val.code_supership}  `
                                                    codeSupership +=val.code_supership
                                                    if(key + 1 < data[i].order_return.length){
                                                        html +=""
                                                        codeSupership+=","
                                                    }
                                                    html +=`</div>`

                                                })

                                                html+=`</td>`;
                                            }
                                            html += `<td style="padding-right: 35px;min-height: 40px;"><span><b>Đ/C:</b> <span class="mycopy" style="font-weight:bold;color:blue;">${data[i].repo_customer}</span> </span><a target="_blank" class="copy-address" style="position: absolute;top: 0;right: 10px;font-size: 26px;" href="https://www.google.com/maps/search/?api=1&query=${data[i].repo_customer}"><i class="fa fa-map-marker" aria-hidden="true"></i></a></td><td><b>Ghi Chú:</b><span style="color:#a73b3b;font-weight:bold">${data[i].note}</span> </td> <td>`;
                                            if(data[i].receive_or_pay == '1'){
                                                html +=
                                                    `<a ${data[i].receive_or_pay == '1' ? "style='background: #FF9800;'" : ""}   data-id="${data[i].id}" data-codesuper="${codeSupership}" onclick="btnDaTra(this)"  class="btn btn-custom btn-primary left ">
                                                        Xác nhận đã trả
                                                      </a>`;
                                            }else{
                                                html +=
                                                    `<a ${data[i].receive_or_pay == '1' ? "style='background: #FF9800;'" : ""}   data-id="${data[i].id}"  href="javascript:;"   class="btn btn-custom btn-primary left confirm_get">
                ${data[i].receive_or_pay == '1' ? 'Xác nhận đã trả' : 'Xác nhận đã lấy'}
              </a>`;
                                            }


                                            html += ` <a  href="javascript:;" data-id="${data[i].id}" class="btn btn-custom btn-warning center reg_data_order confirmation">


                 ${data[i].receive_or_pay == '1' ? 'Đăng ký trả' : 'Đăng ký lấy'}
               </a>`;

                                            html += ` <a class="btn btn-custom btn-primary right" href="tel:${data[i].phone_customer}">Gọi Shop</a>`;

                                            html += `<div class="clear-fix"></td>`;
                                            html += `</tr>`;
                                        }
                                        $('.table-data.table1 tbody').append(html);
                                    } else {
                                        $('.table-data.table1').empty();
                                        var html = `<p class="data-empty">Chưa Có Danh Sách Điểm Đơn Hàng </p>`;
                                        $('.table-data.table1').append(html);
                                    }

                                }
                            });
                        }

                        function btnDaTra(_this){
                            $("#modalBtnDaTra").modal();
                            $("#blah").hide();

                            let don_da_tra = $(_this).attr('data-codesuper');
                            let id = $(_this).attr('data-id');
                            let list = don_da_tra.split(",");
                            let htmlDonDaTra = "";
                            htmlDonDaTra+= list.map(function(val,key){
                                return `<div >${val} </div>`
                            }).join('')
                            $("#form_da_tra").attr('action',"/system/shiper/confirm_da_tra/"+id)
                            $("#don_da_tra").html(htmlDonDaTra)
                            $("#tong_so_don").html(list.length)
                            $("#blah").attr('src', "")

                        }
                        function readURL(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();

                                reader.onload = function (e) {
                                    $('#blah').attr('src', e.target.result);
                                }

                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        $("#imgInp").change(function(){
                            $("#blah").show();
                            $("#buttonTraVe").attr('disabled',false)
                            readURL(this);
                        });
                        $('.tab').click(function () {
                            $('#limit_geted').css('display', 'none');

                            $('.tab').removeClass('active');
                            $(this).addClass('active');
                            var dataTab = $(this).attr('data-tab');
                            $('.table-data').removeClass('active');
                            $('.table' + dataTab).addClass('active');
                            if (dataTab === '2') {
                                $('.header-table h5').text('Danh Sách Đơn Hàng Đã Đăng Ký');
                                callTable2()
                            }
                            if (dataTab === '5') {
                                $('.header-table h5').text('Danh Sách Đơn Hàng Chưa Báo Cáo');

                            }
                            if (dataTab === '6') {
                                $('.header-table h5').text('Danh Sách Báo Cáo');
                            }
                            if (dataTab === '1') {
                                $('.header-table h5').text('Danh Sách Đơn Hàng Chưa Lấy');
                                $('.disable-view').fadeIn();
                                callTable1()

                            }
                            if (dataTab === '3') {
                                $('.header-table h5').text('Danh sách đơn hàng đã xử lý');
                                $('#limit_geted').css('display', 'flex');

                                var limit = $('#limit_geted select').val();
                                $('.disable-view').fadeIn();
                                let data = {
                                    // date_form:(date_form)?moment(new Date(convertDate(date_form))).format('YYYY/MM/DD'):"",
                                    staff:<?=$current_user?>,
                                };
                                $.ajax({
                                    url: `/system/shiper/geted_ajax?limit=${limit}&jsonData=${JSON.stringify(data)}`,
                                    success: function (data) {
                                        $('.disable-view').fadeOut();
                                        data = JSON.parse(data);
                                        if (data.length > 0) {
                                            data.sort(function (a, b) {
                                                var keyA = new Date(a.date_report),
                                                    keyB = new Date(b.date_report);
                                                // Compare the 2 dates
                                                if (keyA > keyB) return -1;
                                                if (keyA < keyB) return 1;
                                                return 0;
                                            });
                                            $('.table-data.table3 tbody').empty();
                                            var html = '';

                                            for (var i = 0; i < data.length; i++) {
                                                if (typeof data[i].orders == "undefined") {
                                                    html += `<tr><td>`;
                                                    if (data[i].customer_shop_code) {
                                                        html += `${date_format(data[i].date_report)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                                                    } else {
                                                        html += `${date_format(data[i].date_report)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                                                    }


                                                    html += `<td>
                                    <span><b>Đ/C:</b> <span style="font-weight:bold;color:blue;">${data[i].repo_customer}</span> </span>
                                  </td>
                                  <td>
                                    <b>Ghi Chú:</b>
                                    <div style="    white-space:break-spaces;color:#a73b3b">${data[i].note}</div>

                                  </td>`;

                                                    html += `<td>
                                   <b>Người lấy:</b>
                                   <span>
                                     ${data[i].lastname} ${data[i].firstname} - Số Đơn: ${data[i].number_order_get}
                                   </span>
                            </td>`;


                                                    html += `</tr>`;
                                                } else {
                                                    let address = `${(data[i].address) ? data[i].address + ", " : ""} ${(data[i].ward) ? data[i].ward + ", " : ""} ${(data[i].district) ? data[i].district + ", " : ""}  ${(data[i].city) ? data[i].city : ""} `;
                                                    var filtered =[]
                                                    if(data[i].note){
                                                        let noteArr = data[i].note.split("\n");
                                                        var filtered = noteArr.filter(function (el) {
                                                            if (el != " ") {
                                                                return true
                                                            }
                                                            return false
                                                        });
                                                    }


                                                    html += `<tr><td>`;
                                                    html += `${date_format(data[i].date_report)} - <span class="bold-shop" style="font-size: 13px;color: #">${data[i].code_delivery}</span> - <span class="bold-shop" style="font-size: 13px;color: #985FD8">${data[i].code_supership}</span>  `;
                                                    html += `<div class="bold-shop" style="">${data[i].customer_shop_code} - ${data[i].customer_phone}</div> `;
                                                    html += `<div class="bold-shop" style="text-align:center;font-size:16px;color: red"><span style="    padding: 2px;"><span style="color:black">Sản Phẩm:</span> ${data[i].product} -<span style="    padding: 2px;"><span style="color:black">Thu Hộ:</span> ${formatCurrency(data[i].collect)} - <span style="color:black">KL:</span> ${formatCurrency(data[i].mass)}</span></div> `;
                                                    html += `<div class="bold-shop" >Trạng Thái:<span style="color:red"> ${data[i].status_report} </span> </div> `;
                                                    html += ` <td><span class="bold-shop">Người Nhận: ${data[i].receiver} - ${data[i].phone}</span> `;

                                                    html += `<div class="bold-shop" >Đ/C:<span style="color:blue"> ${address} </span> </div> `;
                                                    html += `     <td>
                                    <b>Ghi Chú:</b>
                                    <div style="    white-space:break-spaces;color:#a73b3b;">${data[i].note}</div>

                                  </td>`;


                                                }


                                            }
                                            $('.table-data.table3 tbody').append(html);
                                        } else {
                                            $('.table-data.table3 tbody').empty();
                                            var html = `<tr><td><p class="data-empty">Chưa Có Danh Sách Đã Lấy Hàng </p></td></tr>`;
                                            $('.table-data.table3 tbody').append(html);
                                        }

                                    }
                                });

                            }
                            if (dataTab === '4') {
                                $('.header-table h5').text('Tài Khoản');
                            }
                        });
                        $('#modal-confirm-order').on('hide.bs.modal', function (e) {
                            $('#modal-confirm-order form')[0].reset();
                        })


                    </script>


                    <script type="text/javascript">
                        var elems = document.getElementsByClassName('confirmation');
                        var confirmIt = function (e) {

                            if (!confirm('Bạn Sẽ Đăng Ký Điểm Chờ Hàng?')) {
                                return false;
                            }
                            var idRe = $(this).attr('data-id');
                            location.replace(`/system/shiper/reg/${idRe}`);

                        };
                        for (var i = 0, l = elems.length; i < l; i++) {
                            elems[i].addEventListener('click', confirmIt, false);
                        }

                        $(document).on("click", ".confirmation", function (e) {
                            var c = confirm('Bạn Sẽ Đăng Ký Điểm Chờ Hàng?');

                            if (!c) {
                                return false;
                            }

                            var idRe = $(this).attr('data-id');
                            location.replace(`/system/shiper/reg/${idRe}`);
                        });

                        function nha_diem(_this){
                            if (!confirm('Bạn Sẽ Nhả Điểm Chờ Hàng?')) {
                                return false;
                            }
                            var idRe = $(_this).attr('data-reg-id');

                            location.replace(`/system/shiper/un_reg/${idRe}`);
                        }



                        $('.copy-address').click(function () {

                            $(this).find('span').fadeIn();

                            setTimeout(() => {
                                $(this).find('span').fadeOut();
                            }, 250);

                            var copyText = $(this).parent().find('.mycopy');
                            copyToClipboard(copyText);
                        });

                        function copyToClipboard(element) {
                            var $temp = $("<input>");
                            $("body").append($temp);
                            $temp.val($(element).text()).select();
                            document.execCommand("copy");
                            $temp.remove();
                        }


                        $('.change-password-modal').click(function () {
                            $('#modal-edit-password').modal('show');

                        });

                        $(document).on("change", "#limit_geted select", function () {

                            var limit = $('#limit_geted select').val();
                            $('.disable-view').fadeIn();

                            $.ajax({
                                url: `/system/shiper/geted_ajax?limit=${limit}`,
                                success: function (data) {
                                    $('.disable-view').fadeOut();
                                    data = JSON.parse(data);
                                    if (data.length > 0) {
                                        $('.table-data.table3 tbody').empty();
                                        var html = '';

                                        for (var i = 0; i < data.length; i++) {

                                            html += `<tr><td>`;
                                            if (data[i].customer_shop_code) {
                                                html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                                            } else {
                                                html += `${date_format(data[i].modified)} - <span class="bold-shop">${data[i].display_name}</span> - ${data[i].phone_customer}`;
                                            }


                                            html += `<td>
                  <span><b>Đ/C:</b> <span style="font-weight:bold;color:blue;">${data[i].repo_customer}</span> </span>
                </td>
                <td>
                  <b>Ghi Chú:</b>
                  <span style="color:#a73b3b;font-weight:bold">${data[i].note}</span>

                </td>`;

                                            html += `<td>
                 <b>Người lấy:</b>
                 <span>
                   ${data[i].lastname} ${data[i].firstname} - Số Đơn: ${data[i].number_order_get}
                 </span>
                </td>`;


                                            html += `</tr>`;

                                        }
                                        $('.table-data.table3 tbody').append(html);
                                    } else {
                                        $('.table-data.table3 tbody').empty();
                                        var html = `<tr><td><p class="data-empty">Chưa Có Danh Sách Đã Lấy Hàng </p></td></tr>`;
                                        $('.table-data.table3 tbody').append(html);
                                    }

                                }
                            });
                        });

                        function modalDaGiao(_this) {
                            $("#view_da_giao").removeClass('hidden');
                            $("#view_hoan_giao").addClass('hidden');
                            $("#view_khong_giao_duoc").addClass('hidden');
                            $("#modal_update_status").modal();
                            $("#modal_update_status .modal-header").html('Đã Giao');
                            $("#popup-code-delivery").html($(_this).attr('data-code_delivery'));
                            $("#popup-code-order").html($(_this).attr('data-code_supership'));
                            $("#popup-address").html($(_this).attr('data-address'));
                            $("#delivery_id").val($(_this).attr('data-delivery_id'));
                            $("#shop_id").val($(_this).attr('data-id'));
                            status_report = $(_this).attr('data-status_report');
                            if (status_report) {

                            } else {
                                var ele = document.getElementsByName("status");
                                for (var i = 0; i < ele.length; i++)
                                    ele[i].checked = false;
                            }
                        }

                        function modalHoanGiao(_this) {
                            $("#view_hoan_giao").removeClass('hidden');
                            $("#view_da_giao").addClass('hidden');
                            $("#view_khong_giao_duoc").addClass('hidden');
                            $("#modal_update_status").modal();
                            $("#modal_update_status .modal-header").html('Hoãn Giao');
                            $("#popup-code-delivery").html($(_this).attr('data-code_delivery'));
                            $("#popup-code-order").html($(_this).attr('data-code_supership'));
                            $("#popup-address").html($(_this).attr('data-address'));
                            $("#delivery_id").val($(_this).attr('data-delivery_id'));
                            $("#shop_id").val($(_this).attr('data-id'));

                            status_report = $(_this).attr('data-status_report');
                            if (status_report) {

                            } else {
                                var ele = document.getElementsByName("status");
                                for (var i = 0; i < ele.length; i++)
                                    ele[i].checked = false;
                            }

                        }

                        function modalKhongGiaoDuoc(_this) {
                            $("#view_hoan_giao").addClass('hidden');
                            $("#view_da_giao").addClass('hidden');
                            $("#view_khong_giao_duoc").removeClass('hidden');
                            $("#modal_update_status").modal();
                            $("#modal_update_status .modal-header").html('Không Giao Được');
                            $("#popup-code-delivery").html($(_this).attr('data-code_delivery'));
                            $("#popup-code-order").html($(_this).attr('data-code_supership'));
                            $("#popup-address").html($(_this).attr('data-address'));
                            $("#delivery_id").val($(_this).attr('data-delivery_id'));
                            $("#shop_id").val($(_this).attr('data-id'));

                            status_report = $(_this).attr('data-status_report');
                            if (status_report) {

                            } else {
                                var ele = document.getElementsByName("status");
                                for (var i = 0; i < ele.length; i++)
                                    ele[i].checked = false;
                            }

                        }

                        function updateStatus() {
                            let status = document.querySelector('input[name="status"]:checked').value;

                            let key = document.querySelector('input[name="status"]:checked').getAttribute("key");
                            if (status == "Lý Do Khác") {
                                if (key == "hoan_giao") {
                                    status = $(".ly_do_khac1").val()
                                } else {
                                    status = $(".ly_do_khac2").val()

                                }
                            }
                            let delivery_id = $("#delivery_id").val();
                            let shop_id = $("#shop_id").val();

                            $.ajax({
                                url: `/system/admin/Delivery_order/updateDelivery/${delivery_id}?status_report=${status}&key=${key}&shop_id=${shop_id}`,
                                success: function (result) {
                                    $("#modal_update_status").modal('hide');
                                    callTable5();

                                    toastr.success('Trạng Thái!', 'Cập Nhật Thành Công')
                                }
                            });
                        }

                        function formatCurrency(amount) {
                            if (!amount) {
                                amount = 0;
                            }
                            let _currency = '';
                            var formatter = new Intl.NumberFormat('vi-VN');
                            amount = amount.toString().match(/\d+/);
                            if (amount) {
                                _currency = formatter.format(amount);
                            }
                            return _currency;
                        }

                        function callTable2(){
                            $('.disable-view').fadeIn();

                            $.ajax({
                                url: '/system/shiper/call_table2',
                                success: function (data) {
                                    $('.disable-view').fadeOut();

                                    data = JSON.parse(data);
                                    if (data.length > 0) {
                                        $('.table-data.table2 tbody').empty();
                                        var html = '';

                                        for (var i = 0; i < data.length; i++) {
                                            var codeSupership ="";
                                            html += `<tr><td>`;
                                            if (data[i].customer_shop_code) {
                                                html += `${date_format(data[i].created)} - <span class="bold-shop">${data[i].customer_shop_code}</span> - ${data[i].phone_customer}`;
                                            } else {
                                                html += `${date_format(data[i].created)} - <span class="bold-shop">${data[i].name_customer_new}</span> - ${data[i].phone_customer}`;
                                            }
                                            html += `<td/>`;

                                            if(data[i].order_return.length > 0){
                                                html += `<td ><span class="bold-shop">Tổng Số Đơn: <span style="color:blue">${data[i].number_order_get}</span></span></td> `;

                                                html += ` <td>  `;

                                                $.each(data[i].order_return,function (key,val) {
                                                    html += `<div class="bold-shop1">${val.code_supership}  `
                                                    codeSupership +=val.code_supership
                                                    if(key + 1 < data[i].order_return.length){
                                                        html +=""
                                                        codeSupership+=","
                                                    }
                                                    html +=`</div>`

                                                })

                                                html+=`</td>`;
                                            }
                                            html += `<td style="padding-right: 35px;min-height: 40px;"><span><b>Đ/C:</b> <span class="mycopy" style="font-weight:bold;color:blue;">${data[i].repo_customer}</span> </span><a target="_blank" class="copy-address" style="position: absolute;top: 0;right: 10px;font-size: 26px;" href="https://www.google.com/maps/search/?api=1&query=${data[i].repo_customer}"><i class="fa fa-map-marker" aria-hidden="true"></i></a></td><td><b>Ghi Chú:</b><span style="color:#a73b3b;font-weight:bold">${data[i].note}</span> </td> <td>`;
                                            if(data[i].receive_or_pay == '1'){
                                                html +=
                                                    `<a ${data[i].receive_or_pay == '1' ? "style='background: #FF9800;'" : ""}   data-id="${data[i].id}" data-codesuper="${codeSupership}" onclick="btnDaTra(this)"  class="btn btn-custom btn-primary left ">
                                                        Xác nhận đã trả
                                                      </a>`;
                                            }else{
                                                html +=
                                                    `<a ${data[i].receive_or_pay == '1' ? "style='background: #FF9800;'" : ""}   data-id="${data[i].id}"  href="javascript:;"   class="btn btn-custom btn-primary left confirm_get">
                ${data[i].receive_or_pay == '1' ? 'Xác nhận đã trả' : 'Xác nhận đã lấy'}
              </a>`;
                                            }


                                            html += `<a href="javascript:" data-reg-id='${data[i].id}'
                                   class="btn btn-custom btn-warning center " onclick="nha_diem(this)">Nhả Điểm</a>
               </a>`;

                                            html += ` <a class="btn btn-custom btn-primary right" href="tel:${data[i].phone_customer}">Gọi Shop</a>`;

                                            html += `<div class="clear-fix"></td>`;
                                            html += `</tr>`;
                                        }
                                        $('.table-data.table2 tbody').append(html);
                                    } else {
                                        $('.table-data.table2').empty();
                                        var html = `<p class="data-empty">Chưa Có Danh Sách Điểm Đơn Hàng </p>`;
                                        $('.table-data.table2').append(html);
                                    }

                                }
                            });

                        }

                    </script>

                    <script>
                        //Get the button
                        var mybutton = document.getElementById("myBtn");
                        mybutton.style.display = "block";


                        // When the user clicks on the button, scroll to the top of the document
                        function topFunction() {
                            document.getElementById("date_create").focus();
                        }
                        $('.disable-view').fadeIn();
                        callTable1()
                        callTable2()






                    </script>

                    </body>
                    </html>
