<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    body .return-style {
        font-family: "Times New Roman", Times, serif !important;
        font-size: 15px;
    !important;
    }

    .control-label {
        font-size: 15px !important;

    }

    .return-style .button-blue {
        border: 1px solid #41719B;
        background: #5794CC;
    }

    p {
        margin: 0 0 0px !important;
    }

    .return-style .button-blue:hover {
        background: #d9dbdd;
    }

    table tbody tr:hover {
        background: #FBC2C4 !important;
    }

    table {
        margin: 0 auto;
        width: 100%;
        clear: both;
        border-collapse: collapse;
        table-layout: fixed;
    / / add this word-wrap: break-word;
    / / add this
    }

    .return-style .button-red {
        border: 1px solid #41719B;
        background: red;
        padding: 6px;
    }

    .return-style .button-violet {
        border: 1px solid #41719B;
        background: #d72aea;
        padding: 6px;
    }

    .return-style .button-green {
        border: 1px solid #41719B;
        background: green;
        padding: 6px;
    }

    .mb-5 {
        margin-bottom: 15px
    }

    .mb-15 {
        margin-bottom: 15px
    }

    .mr-2 {
        margin-right: 2px
    }

    .label.label-xs {
        font-size: 15.5px;
        padding: 1px 4px;
    }

    .btn {
        font-size: 11.5px;
    }

    .label-orange {
        background: white;
        color: #e52228;
        border: 1px solid green;
    }

    .select2 {
        width: 100% !important;
    }

    table tbody td:first-child {
        width: 50px !important;
    }


    table tbody .sorting_1 {
        width: 200px !important;
    }

    .dataTables_length label {
        font-size: 15px;
    }

    .dataTables_length select {
        width: 50px;
        height: 30px;
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

    textarea#note {
        width: 100%;
        box-sizing: border-box;
        display: block;
        max-width: 100%;
        line-height: 1.5;
        padding: 15px 15px 30px;
        border-radius: 3px;
        font: 13px Tahoma, cursive;
        transition: box-shadow 0.5s ease;
        font-smoothing: subpixel-antialiased;

    }

    table td {
        padding: 0px !important;
    }

    .m-heading-1.m-bordered {
        border-right: 1px solid #10161c;
        border-top: 1px solid #10161c;
        border-bottom: 1px solid #10161c;
        padding: 15px;
    }

    .m-heading-2.m-bordered {
        border-right: 1px solid #B9F0F5;
        border-top: 1px solid #B9F0F5;
        border-bottom: 1px solid #B9F0F5;
        padding: 15px;
    }

    .m-heading-2 {
        margin: 0 0 20px;
        background: #fff;
        padding-left: 15px;
        border-left: 8px solid #57D1D5;
        background-color: #B9F0F5;
    }

    .border-red {
        border-color: #e1293d !important;
    }


    .m-heading-1 {
        margin: 0 0 20px;
        background: #fff;
        padding-left: 15px;
        border-left: 8px solid #88909a;
    }

    .green {
        background-color: #00612D;
        border-color: #00612D;
        color: white
    }

    .note-danger {
        background-color: #f6c1c7;
        color: black
    }

    .note {
        border-left: 5px solid #e1293d;
        padding: 15px
    }

    .red {
        background-color: #e1293d !important;
        border-color: #e1293d !important;
        color: white
    }

    input[type=checkbox] {
        margin: 4px 0 0;
        line-height: normal;
        -ms-transform: scale(2);
        -moz-transform: scale(2);
        -webkit-transform: scale(2);
        -o-transform: scale(2);
        transform: scale(2);
        padding: 10px;
        margin-right: 10px;
    }
    .lshipper {
        color:red;

        margin-top: 7px;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://codeseven.github.io/toastr/build/toastr.min.css" rel="stylesheet"/>

<div id="wrapper">
    <div class="overlay-dark">
        <div id="loader-repo3" class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="content return-style">
        <div class="row">
            <div class="col-md-12">
                <select id="status" class="hidden" name="status">
                    <option></option>
                    <?php
                    foreach ($list_status as $status1 => $color) { ?>
                        <option data-color="<?= $color ?>" value="<?= $status1 ?>"><?= $status1 ?></option>
                    <?php }

                    ?>
                </select>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h4 style="color:red">CHI TIẾT GIAO HÀNG</h4>
                        </div>
                        <div class="clearfix"></div>


                        <hr class="hr-panel-heading"/>
                        <div class="m-heading-1 border-grey-mint m-bordered">
                            <div class="row" id="deliveries-area">
                                <form action="javascript:;" class="form form-horizontal" method="post"
                                      autocomplete="off">
                                    <input type="hidden" name="_token" value="5BycHW9GCGet0Mlr7Rq1SO19ZxSTGH5PCBLxr7KA">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <h4 class="bold uppercase">CHỈ MỤC TÌM KIẾM</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Mã Giao Hàng: </label>
                                                <div class="col-md-8 lshipper">
                                                    <?= $code ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Người Giao Hàng: </label>
                                                <div class="col-md-8 lshipper">
                                                    <?= $fullname ?>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Trạng Thái: </label>
                                                <div class="col-md-8 lshipper">

                                                    <?= $status ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Tổng Số Đơn Hàng: </label>
                                                <div class="col-md-8 lshipper">
                                                    <?= $total ?>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Số Đơn Đã Báo Cáo: </label>
                                                <div class="col-md-8 lshipper">
                                                    <?= $so_don_bao_cao ?>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Số Đơn Chưa Báo Cáo: </label>
                                                <div class="col-md-8 lshipper">
                                                    <?= $so_don_chua_bao_cao ?>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Tổng Tiền Thu Hộ: </label>
                                                <div class="col-md-8 lshipper">
                                                    <?= number_format($tong_tien_thu_ho, 0, ',', '.') ?>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Tổng Tiền Đã Thu: </label>
                                                <div class="col-md-8 lshipper">
                                                    <?= number_format($tong_tien_da_thu, 0, ',', '.') ?>


                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12" id="table-order-wrapper">
                                            <table id="example" class="table table-bordered table-striped "
                                                   style="border-collapse: collapse;width:100%;font-family: " Times New
                                                   Roman
                                            ", Times, serif !important;font-size: 12px !important;">
                                            <thead>
                                            <tr>
                                                <th>
                                                    <div style="text-align: center"><input type="checkbox"
                                                                                           onclick="checkAll(this)">
                                                    </div>
                                                </th>
                                                <th>Shop</th>
                                                <th>Tình trạng</th>
                                                <th>Sản phẩm</th>
                                                <th style="width: 24%;">Địa chỉ</th>
                                                <th></th>
                                            </tr>
                                            </thead>

                                            </table>
                                        </div>


                                </form>
                            </div>
                        </div>
                    </div>
                    <!--                    border red-->


                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true"
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
                        <span style="font-weight: bold;    font-size: 15px;" id="popup-code-order"></span>
                    </div>
                    <div id="popup-address">
                    </div>
                </div>
                <div id="view_da_giao" style="height: 50px;" class="hidden">
                    <div class="col-md-4">Trạng Thái <sub style="color:red">[*]</sub></div>
                    <div class="col-md-4"><input type="radio" key="da_giao_hang" name="status"
                                                 value="Đã Giao Hàng Toàn Bộ"> <span
                                style="margin-left: 5px">Đã Giao Hàng Toàn Bộ</span></div>
                    <div class="col-md-4"><input type="radio" key="da_giao_hang" name="status"
                                                 value="Đã Giao Hàng Một Phần"> <span
                                style="margin-left: 5px">Đã Giao Hàng Một Phần</span></div>
                </div>
                <div id="view_hoan_giao" style="height: 150px;" class="hidden">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">Tại Sao? <sub style="color:red">[*]</sub></div>
                    <div class="col-md-8">

                        <div class="col-md-12">
                            <input type="radio" key="hoan_giao" name="status" value="Không Nghe Máy/Không Gọi Được">
                            <span
                                    style="margin-left: 5px">Không Nghe Máy/Không Gọi Được</span>
                        </div>
                        <div class="col-md-12">
                            <input type="radio" key="hoan_giao" name="status" value="Địa Chỉ Sai"> <span
                                    style="margin-left: 5px">Địa Chỉ Sai</span>
                        </div>
                        <div class="col-md-12">
                            <input type="radio" key="hoan_giao" name="status" value="Lý Do Khác"> <span
                                    style="margin-left: 5px">Lý Do Khác</span>
                        </div>
                        <div class="col-md-12">
                            <textarea type="text" key="hoan_giao" class="form-control ly_do_khac1 hidden"
                                      value="Lý Do Khác"></textarea>
                        </div>
                    </div>
                </div>
                <div id="view_khong_giao_duoc" style="height: 150px;" class="hidden">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">Tại Sao? <sub style="color:red">[*]</sub></div>
                    <div class="col-md-8">
                        <div class="col-md-12">
                            <input type="radio" key="khong_giao_duoc" name="status" value="Khách Không Đồng Ý Nhận">
                            <span
                                    style="margin-left: 5px">Khách Không Đồng Ý Nhận</span>
                        </div>
                        <div class="col-md-12">
                            <input type="radio" key="khong_giao_duoc" name="status" value="Shop Yêu Cầu Hủy Đơn"> <span
                                    style="margin-left: 5px">Shop Yêu Cầu Hủy Đơn</span>
                        </div>
                        <div class="col-md-12">
                            <input type="radio" name="status" key="khong_giao_duoc" value="Lý Do Khác"> <span
                                    style="margin-left: 5px">Lý Do Khác</span>
                        </div>
                        <div class="col-md-12">
                            <textarea type="text" key="khong_giao_duoc" class="form-control ly_do_khac2 hidden"
                                      value="Lý Do Khác"></textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="updateStatus()">Xác Nhận</button>
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
<?php init_tail(); ?>
<script type="text/javascript" charset="utf8"
        src="https://cdn.jsdelivr.net/npm/sweetalert2@9.10.9/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>

<script>
    autosize(document.getElementById("note"));
    autosize(document.getElementById("note_error"));
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
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://codeseven.github.io/toastr/build/toastr.min.js"></script>
<script>
    function changeProvince(_this) {
        let listProvince = $(_this).val();
        $.ajax({
            url: "/system/admin/Delivery_order/get_district?list_province=" + JSON.stringify(listProvince),
            success: function (result) {
                let htmlDistrict = ""
                let dataDistrict = JSON.parse(result);
                htmlDistrict += dataDistrict.map(function (val, key) {
                    return `<option value="${val.district}">${val.district}</option>`
                }).join('');
                $("#district").html(htmlDistrict)
            }
        });
    }

    function changeDistrict(_this) {
        let listDistrict = $(_this).val();
        $.ajax({
            url: "/system/admin/Delivery_order/get_commune?list_district=" + JSON.stringify(listDistrict),
            success: function (result) {
                let htmlCommune = ""
                let dataCommune = JSON.parse(result);
                htmlCommune += dataCommune.map(function (val, key) {
                    return `<option value="${val.commune}">${val.commune}</option>`
                }).join('');
                $("#commune").html(htmlCommune)
            }
        });
    }

    function checkAll(_this) {

        if (_this.checked) {
            $("input[name='list_id']").prop('checked', true);

        } else {
            $("input[name='list_id']").prop('checked', false);

        }
    }

    function createDilivery() {
        if (!document.getElementById('is_check').checked) {
            toastr.error('CHƯA CHỌN XÁC NHẬN',)
            return;
        }
        var listId = [];
        $.each($("input[name='list_id']:checked"), function () {
            listId.push($(this).val());
        });
        let staff = $("#staff_id").val();
        let data = {
            'list_order': listId,
            'staff': staff,
        }
        $.ajax({
            url: "/system/admin/Delivery_order/add",
            type: "post",
            data: data,

            success: function (result) {
                if (result.length > 0) {
                    toastr.success('TẠO GIAO HÀNG!', 'THÊM THÀNH CÔNG')
                } else {
                    toastr.error('TẠO GIAO HÀNG!', 'THẤT BẠI')

                }
            }
        });


    }

    $(document).ready(function () {
        searchTuyChinh()
        $('input[type=radio][name=status]').change(function () {
            if (this.value == "Lý Do Khác") {
                if (this.getAttribute("key") == "hoan_giao") {
                    $(".ly_do_khac1").removeClass('hidden')
                } else {
                    $(".ly_do_khac2").removeClass('hidden')
                }
            } else {
                $(".ly_do_khac1").addClass('hidden')
                $(".ly_do_khac2").addClass('hidden')
            }

        });
    });

    function searchTuyChinh() {
        let linkApi = getLink()
        loadDatatables(linkApi);
    }

    function getLink() {

        let linkApi = '/system/admin/Delivery_order/getTableDeliveryDetail/<?=$code?>'

        return linkApi;
    }

    function getLinkView(key, code_supership, code_ghtk) {
        let link = ''
        if (key == "SPS") {
            link = 'https://mysupership.com/search?category=orders&query=' + code_supership;
        } else if (key == "GHTK") {
            link = 'https://khachhang.giaohangtietkiem.vn/khachhang?code=' + code_ghtk;
        } else if (key == "VNC") {
            link = 'https://cs.vncpost.com/order/list';
        }

        return link;
    }

    function getLinkPrint(key, create_order_id, code_supership) {
        let link = ''
        if (key == "SPS") {
            link = `https://mysupership.com/orders/print?code=${code_supership}&size=S9`;
        } else if (key == "GHTK") {
            link = `http://spshd.com/system/admin/create_order_ghtk/print_data_order/${create_order_id}?print=true`;
        } else if (key == "VNC") {
            link = `/system/admin/create_order_ghtk/print_data_order/${create_order_id}?print=true&dv=VNC`;
        }
        return link;
    }

    let loadDatatables = (link) => {
        var table = $('#example').DataTable({
            "ajax": link,
            "columnDefs": [
                {
                    "width": "5%",
                    "targets": 0,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        return `<div class="custom-control custom-checkbox" style="margin-left: 7px;text-align: center;margin-top: 100%;">
                                      <input type="checkbox" class="custom-control-input" name="list_id" value="${row.id}">
                                    </div>`;
                    }
                },
                {
                    "width": "10%",
                    "targets": 1,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let nameShop = '';
                        if (row.is_hd_branch == 0) {
                            nameShop = 'Shop chi nhánh khác'
                        }
                        if (row.is_hd_branch == 1) {
                            nameShop = 'Shop chi nhánh mình'
                        }
                        return `<p style="color:red">${row.shop}</p><p>${nameShop}</p>`;
                    }
                },
                {
                    "width": "17%",
                    "targets": 2,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let color = $("#status").find(`option[value='${row.status}']`).data('color')
                        let backgroundStatus = "#" + color
                        if (!color) {
                            backgroundStatus = "black"
                        }
                        let dvvc = "";
                        if (row.DVVC != "") {
                            dvvc = `<p>ĐVVC: ${row.DVVC}</p>`
                        }
                        let mkh = "";
                        if (row.code_orders != null && row.code_orders != "") {
                            mkh = `<p>Mã Đơn KH : <span style="color:green">${row.code_orders}</span></p>`
                        }
                        let requestCode = "";
                        if (row.required_code != null && row.required_code != "") {
                            requestCode = `<p>Mã Yêu Cầu : <span style="color:#6a7dfe">${row.required_code}</span></p></p>`
                        }
                        let ghtk = "";
                        if (row.code_ghtk != null) {
                            ghtk = `<p style="color:red">${row.code_ghtk}</p>`
                        }
                        return `
                                <div style="width: 100%;    margin-top: 5px;" class="mb-5"><label class="label label-orange label-xs tooltips" style="color:white;background-color:${backgroundStatus}">${row.status}  &emsp;&emsp;</label></div>
                                <p style="color:red"> ${row.code_supership} </p>
                                ${dvvc}
                                ${ghtk}
                                ${mkh}
                                ${requestCode}
                                <p>Ngày tạo : ${moment(row.date_create).format('DD-MM-YYYY HH:mm:SS')}</p>`;
                    }
                },
                {
                    "width": "10%",
                    "targets": 3,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.hd_fee)}</span></p>`
                        if (row.hd_fee == null) {
                            phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.hd_fee_stam)}</span></p>`
                        }
                        if (row.is_hd_branch == 0) {
                            phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.pay_transport)}</span></p>`
                        }
                        let date = ``

                        if (row.date_debits != null && row.date_debits != "" && row.date_debits != "0000-00-00 00:00:00") {
                            date = `<p style="color:#6a7dfe">NTN:${moment(row.date_debits).format('DD-MM-YYYY')}</p>`
                        }
                        return `
                                <p>SP: ${row.product}</p>
                                <p>Khối lượng: <span style="color:red">${row.mass}</span></p>
                                <p>Thu Hộ: <span style="color:red">${formatCurrency(row.collect)}</span></p>
                                <p>Trị giá: <span style="color:red">${formatCurrency(row.value)}</span></p>
                                ${phi}
                                ${date}`;
                    }
                },
                {
                    "width": "20%",
                    "targets": 4,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let address = `${(row.address) ? row.address + ", " : ""} ${(row.ward) ? row.ward + ", " : ""} ${(row.district) ? row.district + ", " : ""}  ${(row.city) ? row.city : ""} `


                        return `
                                <div style=" margin-top: 5px;width: 100%" class="mb-5"><label class="label label-orange label-xs tooltips" data-original-title="Được tạo bằng API">&emsp;&emsp;${row.city}&emsp;&emsp;</label>&emsp;</div>
                                <p>${row.receiver}</p>
                                <p style="color:red">${row.phone}</p>
                                <p>${address}</p>`;
                    }
                },
                {
                    "width": "38%",
                    "targets": 5,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let noteArr = row.note.split("\n");
                        let htmlNode = "";
                        htmlNode += noteArr.map(function (note,key) {
                            if(note.indexOf("/")>0){
                                return `<span style="color:red">${note}</span>`

                            }
                            return `<span style="color:blue">${note}</span>`


                        }).join('\n');
                        let html =`<button class="btn btn-sm btn-success mr-2 btn${row.id}" onclick="modalDaGiao(this)"
                                data-code_delivery="${row.code_delivery}"
                                data-code_supership="${row.code_supership}"
                                data-status_report="${row.status_report}"
                                data-delivery_id="${row.delivery_id}"

                                data-address="${row.address} ${row.ward}, ${row.district}, ${row.city}"
                                data-id="${row.id}" ><i style="padding-right: 5px;" class="fa fa-gift"></i>Đã Giao</button>
                                <button class="btn btn-sm btn-primary button-blue mr-2 btn${row.id}" onclick="modalHoanGiao(this)"
                                data-code_delivery="${row.code_delivery}"
                                data-code_supership="${row.code_supership}"
                                data-status_report="${row.status_report}"
                                data-delivery_id="${row.delivery_id}"

                                data-address="${row.address} ${row.ward}, ${row.district}, ${row.city}" data-id="${row.id}" ><i style="padding-right: 5px;" class="fa fa-cube"></i>Hoãn Giao</button>
                                <button class="btn btn-sm btn-danger  mr-2 btn${row.id}" onclick="modalKhongGiaoDuoc(this)"
                                data-code_delivery="${row.code_delivery}"
                                data-code_supership="${row.code_supership}"
                                data-status_report="${row.status_report}"
                                data-delivery_id="${row.delivery_id}"

                                data-address="${row.address} ${row.ward}, ${row.district}, ${row.city}" data-id="${row.id}" ><i style="padding-right: 5px;" class="fa fa-bullhorn"></i>Không Giao Được</button>`
                            if(row.status_report){
                                html=""
                            }
                        return `<div style=" margin-top: 3px;width: 100%;table-layout: fixed;"><div class="mb-15" style="display:flex">
                                    ${html}

                                 <button class="btn btn-sm btn-primary button-blue mr-2 btn${row.id}" onclick="modalUpdate(this)" data-id="${row.id}" data-note="${row.note_delay}"><i style="padding-right: 5px;" class="fa fa-comment"></i>Ghi chú</button>

</div>
                                <p style="color:#557f38"><strong>Ghi Chú Giao Hàng:</strong></p>
                                <p style="white-space: pre-line">${htmlNode}</p>
                                <p style="color:#A47FE1"><strong>Ghi Chú Nội Bộ:</strong></p>
                                <div style="white-space: pre-line"><span class="span${row.id}">${(row.note_delay) ? row.note_delay : ""}</span></p>
                                </div>`;
                    }
                }

            ],
            "drawCallback": function (settings) {
                // $("#example thead").remove();
            },
            "order": [[0, 'DESC']],
            searching: false,
            info: false,
            // lengthChange: false, // Will Disabled Record number per page
            processing: true,
            "pageLength": 100,
            language: {
                emptyTable: " ",
                loadingRecords: '&nbsp;',
                processing: 'Loading...',
                lengthMenu: 'Hiển Thị <select>' +
                    '<option value="100">100</option>' +
                    '<option value="20">20</option>' +
                    '<option value="50">50</option>' +
                    '<option value="-1">Tất cả</option>' +
                    '</select> Dòng'
            }

        });

        $('table').removeClass('dataTable')
    };

    function modalDaGiao(_this) {
        $("#view_da_giao").removeClass('hidden')
        $("#view_hoan_giao").addClass('hidden')
        $("#view_khong_giao_duoc").addClass('hidden')
        $("#modal_update_status").modal();
        $("#modal_update_status .modal-header").html('Đã Giao')
        $("#popup-code-delivery").html($(_this).attr('data-code_delivery'))
        $("#popup-code-order").html($(_this).attr('data-code_supership'))
        $("#popup-address").html($(_this).attr('data-address'))
        $("#delivery_id").val($(_this).attr('data-delivery_id'))
        $("#shop_id").val($(_this).attr('data-id'))
        status_report = $(_this).attr('data-status_report')
        if (status_report) {

        } else {
            var ele = document.getElementsByName("status");
            for (var i = 0; i < ele.length; i++)
                ele[i].checked = false;
        }
    }

    function modalHoanGiao(_this) {
        $("#view_hoan_giao").removeClass('hidden')
        $("#view_da_giao").addClass('hidden')
        $("#view_khong_giao_duoc").addClass('hidden')
        $("#modal_update_status").modal();
        $("#modal_update_status .modal-header").html('Hoãn Giao')
        $("#popup-code-delivery").html($(_this).attr('data-code_delivery'))
        $("#popup-code-order").html($(_this).attr('data-code_supership'))
        $("#popup-address").html($(_this).attr('data-address'))
        $("#delivery_id").val($(_this).attr('data-delivery_id'))
        $("#shop_id").val($(_this).attr('data-id'))

        status_report = $(_this).attr('data-status_report')
        if (status_report) {

        } else {
            var ele = document.getElementsByName("status");
            for (var i = 0; i < ele.length; i++)
                ele[i].checked = false;
        }

    }

    function modalKhongGiaoDuoc(_this) {
        $("#view_hoan_giao").addClass('hidden')
        $("#view_da_giao").addClass('hidden')
        $("#view_khong_giao_duoc").removeClass('hidden')
        $("#modal_update_status").modal();
        $("#modal_update_status .modal-header").html('Không Giao Được')
        $("#popup-code-delivery").html($(_this).attr('data-code_delivery'))
        $("#popup-code-order").html($(_this).attr('data-code_supership'))
        $("#popup-address").html($(_this).attr('data-address'))
        $("#delivery_id").val($(_this).attr('data-delivery_id'))
        $("#shop_id").val($(_this).attr('data-id'))

        status_report = $(_this).attr('data-status_report')
        if (status_report) {

        } else {
            var ele = document.getElementsByName("status");
            for (var i = 0; i < ele.length; i++)
                ele[i].checked = false;
        }

    }

    function updateStatus() {
        let status = document.querySelector('input[name="status"]:checked').value;

        let key = document.querySelector('input[name="status"]:checked').getAttribute("key")
        if (status == "Lý Do Khác") {
            if (key == "hoan_giao") {
                status = $(".ly_do_khac1").val()
            }else{
                status = $(".ly_do_khac2").val()

            }
        }
        let delivery_id = $("#delivery_id").val()
        let shop_id = $("#shop_id").val()

        $.ajax({
            url: `/system/admin/Delivery_order/updateDelivery/${delivery_id}?status_report=${status}&key=${key}&shop_id=${shop_id}`,
            success: function (result) {
                $('#example').dataTable().fnDestroy();
                $("#modal_update_status").modal('hide');

                searchTuyChinh()
                toastr.success('Trạng Thái!', 'Cập Nhật Thành Công')
            }
        });
    }

    function modalUpdate(_this) {


        $("#modal-update").modal();
        $(".ly_do_khac1").val('')
        $(".ly_do_khac2").val('')
        document.getElementById("shop_id").value = $(_this).data('id')
        document.getElementById("ghi-chu-noi-bo-old").value = ($(_this).attr('data-note') != "null") ? $(_this).attr('data-note') : ""

        document.getElementById("ghi-chu-noi-bo-new").value = ""
    }

    function updateNode() {
        let noteOld = document.getElementById("ghi-chu-noi-bo-old").value;
        let noteNew = document.getElementById("ghi-chu-noi-bo-new").value;
        if (noteOld != "") {
            noteOld += '\n';
        }
        if (noteNew != "") {
            noteNew += " " + moment(new Date()).format('HH:mm DD/MM');
        }
        let text = noteOld + noteNew;
        note = JSON.stringify(text)
        let id = document.getElementById("shop_id").value;

        $.ajax({
            url: `/system/api/order/update?note=${note}&id=${id}`, success: function (result) {
                if (result == true) {
                    $("#modal-update").modal('hide');
                    toastr.success('Ghi Chú Nội Bộ!', 'Cập Nhật Thành Công')
                    $(`.span${id}`).html(text)
                    $(`.btn${id}`).attr('data-note', text)
                    $('#example').dataTable().fnDestroy();

                    searchTuyChinh()
                }
            }
        });
    }


    function convertDate(userDate) {
        str = userDate.split("/");
        return str[1] + "/" + str[0] + "/" + str[2]
    }


    function get_region_by_city(city, district) {
        let data = {
            city: city,
            district: district,
        };
        let linkApi = '/system/api/get_region_by_city?jsonData=' + JSON.stringify(data);
        return linkApi;
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


</script>


