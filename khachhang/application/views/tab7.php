
<style>
    body .kh-tab7  .order-style {
        font-family: "Times New Roman", Times, serif !important;
        font-size: 15px;
    !important;
    }

    .kh-tab7 .control-label {
        font-size: 15px !important;

    }

    .kh-tab7 .order-style .button-blue {
        border: 1px solid #41719B;
        background: #5794CC;
        width: 35%;
        padding: 5px;
    }

    .kh-tab7 p {
        margin: 0 0 0px !important;
    }

    .kh-tab7 .order-style .button-blue:hover {
        background: #d9dbdd;
    }

    .kh-tab7 table tbody tr:hover {
        background: #FBC2C4 !important;
    }

    .kh-tab7 table {
        margin: 0 auto;
        width: 100%;
        clear: both;
        border-collapse: collapse;
        table-layout: fixed;
    / / add this word-wrap: break-word;
    / / add this
    }

    .kh-tab7 .order-style .button-red {
        border: 1px solid #41719B;
        background: red;
        padding: 6px;
    }

    .kh-tab7 .order-style .button-green {
        border: 1px solid #41719B;
        background: green;
        padding: 6px;
    }

    .kh-tab7 .mb-5 {
        margin-bottom: 15px
    }

    .kh-tab7 .mb-15 {
        margin-bottom: 15px
    }

    .kh-tab7 .mr-2 {
        margin-right: 2px
    }

    .kh-tab7 .label.label-xs {
        font-size: 17px;
        padding: 1px 4px;
    }

    .kh-tab7 .label-orange {
        background: white;
        color: #e52228;
        border: 1px solid green;
    }

    .kh-tab7 .select2 {
        width: 100% !important;
    }

    .kh-tab7 table tbody td:first-child {
        width: 50px !important;
    }

    .kh-tab7 table tbody td:last-child {
        width: 430px !important;
    }
    .kh-tab7 table tbody td:nth-last-child(-n+2) {
        width: 430px !important;
    }

    .kh-tab7 table tbody .sorting_1 {
        width: 200px !important;
    }

    .kh-tab7 .dataTables_length label {
        font-size: 15px;
    }

    .kh-tab7 .dataTables_length select {
        width: 50px;
        height: 30px;
    }

    .kh-tab7 .select2-selection__rendered {
        line-height: 31px !important;
    }

    .kh-tab7 .select2-container .select2-selection--single {
        height: 35px !important;
    }

    .kh-tab7 .select2-selection__arrow {
        height: 34px !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://codeseven.github.io/toastr/build/toastr.min.css" rel="stylesheet"/>








<div class="layout-mobile kh-tab7">
    <div class="row">
        <div class="panel_s">
            <div class="panel-body">
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

                                <input onkeyup="enterTab7(event)" class="form-control datetimepicker-date"
                                       value=""
                                       id="order-from-date"
                                       type="input"> &nbsp;
                                <input onkeyup="enterTab7(event)" class="form-control datetimepicker-date"
                                       value=""
                                       id="order-to-date" type="input">
                            </div>


                        </div>


                        <div class="col-md-12 no-padding mb-5">
                            <div class="col-sm-3 control-label ">Trạng Thái</div>
                            <div class="col-sm-9 " style="display: flex">
                                <select id="kh-status" multiple="multiple" name="status">
                                    <option></option>


                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 no-padding mb-5">
                            <div class="col-sm-3 control-label">Nhóm Vùng Miền</div>
                            <div class="col-sm-9 " style="display: flex">
                                <select id="region" name="region">
                                    <option></option>

                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-7 mb-15">
                        <div class="col-md-12 no-padding mb-5">
                            <div class="col-sm-2 control-label ">Mã Đơn / SĐT</div>
                            <div class="col-sm-9 " style="display: flex">
                                <input class="form-control" onkeyup="emptyDate();enterTab7(event)" id="code_order_tab7" type="text">
                                &nbsp;
                            </div>
                        </div>

                        <div class="col-md-12 no-padding mb-5">
                            <div class="col-sm-2 control-label">Mã Đơn KH</div>
                            <div class="col-sm-9 " style="display: flex">
                                <input class="form-control" onkeyup="emptyDate();enterTab7(event)" id="code_request_tab7" type="text">
                                &nbsp;
                            </div>
                        </div>

                        <div class="col-md-12 no-padding mb-5">
                            <div class="col-sm-2 control-label">Tỉnh / Huyện</div>
                            <div class="col-sm-9 " style="display: flex">
                                <select onchange="getDistrict(this)" id="city" name="city">

                                </select>
                                &nbsp;
                                <select id="kh-district" name="district">
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row mb-3">
                    <button class="btn btn-sm btn-primary button-green mr-2" onclick="exportExcel()"
                            style="width: 15%;">Tải Excel
                    </button>
<!--                    <button class="btn btn-sm btn-primary button-green mr-2" onclick="alert('Đang Cập Nhật')"-->
<!--                            style="width: 15%;">Tải Excel-->
<!--                    </button>-->
                    <button class="btn btn-sm btn-primary button-red mr-2" onclick="clickSearch()"
                            style="width: 15%;">Tìm Kiếm
                    </button>

                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12" id="table-order-wrapper">
                        <table id="kh-order" class="table table-bordered table-striped"
                               style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                        ", Times, serif !important;font-size: 12px !important;">
                        <thead class="hidden">
                        <tr>
                            <th>Name</th>
                            <th>Office</th>
                            <th>Extn.</th>
                            <th style="width: 24%;">Start date</th>
                            <th>Salary</th>
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

</div>

