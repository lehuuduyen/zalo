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
        width: 35%;
        padding: 5px;
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
        font-size: 17px;
        padding: 1px 4px;
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

                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h4 style="color:red">NHẬP HÀNG HOÀN</h4>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
                        <div class="row">
                            <div class="col-md-12 mb-15">
                                <div class="col-sm-2 control-label">Quét Mã Vạch</div>
                                <div class="col-sm-10 ">
                                    <textarea id="note" class="form-control" rows="10"></textarea>

                                </div>
                            </div>
                            <div class="col-md-12 mb-15">
                                <button class="btn btn-sm btn-success col-md-2  mr-2"
                                        onclick="searchOrder()"
                                        style="width: 15%;">Kiểm Tra
                                </button>
                            </div>

                        </div>
                        <div class="hidden" id="danhsach">
                            <div class="col-md-12">
                                <h4 style="color:red">D/S ĐƠN LỖI</h4>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading"/>
                            <div class="row">
                                <input type="hidden" id="list-id-return">
                                <div class="col-md-12 mb-15">
                                    <textarea id="note_error" class="form-control" rows="10"></textarea>

                                </div>


                            </div>

                            <div class="col-md-12">
                                <h4 style="color:red">D/S ĐƠN TẠO CHUYỂN TRẢ</h4>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading"/>
                            <div class="row">
                                <div class="col-md-12 mb-15">
                                    <table id="table1" class="table table-bordered table-striped"
                                           style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                    ", Times, serif !important;font-size: 12px !important;">
                                    <thead>
                                    <tr>
                                        <th style="width: 20%;">Mã Đơn</th>
                                        <th style="width: 20%;">Tên Shop</th>
                                        <th>Người Nhận</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12 mb-15">
                                    <button id="btn-confirm" class="btn btn-sm btn-primary col-md-6 button-red mr-2"
                                            onclick="confirmReturn()" style="width: 40%;">
                                        Xác Nhận Nhập Kho Những Đơn Hoàn Về Thỏa Mãn
                                    </button>
                                </div>

                            </div>
                        </div>


                        <div class="col-md-12">
                            <h4 style="color:red">CHỈ MỤC TÌM KIẾM</h4>
                        </div>
                        <div class="row ">

                            <div class="clearfix"></div>

                            <hr class="hr-panel-heading"/>

                            <input type="hidden" id="search_fast" name="search_fast"
                                   value="<?php echo (isset($_GET['search_fast'])) ? $_GET['search_fast'] : ""; ?>">
                            <div class="col-md-6">
                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-3 control-label">Ngày Trả</div>
                                    <div class="col-sm-9 " style="display: flex">

                                        <input class="form-control datetimepicker-date dateReturn" onchange="emptyDateCreate()"
                                               value=""
                                               id="return-from-date"
                                               type="input"> &nbsp;
                                        <input class="form-control datetimepicker-date dateReturn" onchange="emptyDateCreate()"
                                               value=""
                                               id="return-to-date" type="input">

                                    </div>


                                </div>

                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-3 control-label ">Khách Hàng</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <select id="customer" name="shop">
                                            <option></option>
                                            <?php
                                            foreach ($customers as $customer) { ?>
                                                <option value="<?= $customer['name'] ?>"><?= $customer['name'] ?></option>
                                            <?php }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-15">
                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-3 control-label">Ngày Tạo</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <input class="form-control datetimepicker-date dateCreate" onchange="emptyDateReturn()"
                                               value="<?=$date_from?>"
                                               id="created-from-date"
                                               type="input"> &nbsp;
                                        <input class="form-control datetimepicker-date dateCreate" onchange="emptyDateReturn()"
                                               value="<?=$date_to?>"
                                               id="created-to-date" type="input">

                                    </div>
                                </div>
                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-3 control-label ">Mã Đơn</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <input class="form-control" id="code-supership" type="text">
                                        &nbsp;
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-sm btn-primary col-md-2 button-violet mr-2"
                                        onclick="loadTableDetail()"
                                        style="width: 15%;">Tìm kiếm
                                </button>
                            </div>
                        </div>
                        <div class="row mb-15">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item active">
                                    <a class="nav-link active" data-toggle="tab" href="#home">Hiển Thị Theo Phiếu</a>
                                </li>
                                <li class="nav-item" style="">
                                    <a class="nav-link" data-toggle="tab" href="#menu1">Hiển Thị Chi Tiết</a>
                                </li>

                            </ul>


                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane  active" id="home">
                                    <table id="table-tab1" class="table table-bordered table-striped"
                                           style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                    ", Times, serif !important;font-size: 12px !important;">
                                    <thead>
                                    <tr>
                                        <th>Ngày Tạo</th>
                                        <th>Mã Phiếu</th>
                                        <th>D/S Shop Trả</th>
                                        <th>Số Lượng Đơn</th>
                                        <th style="width: 24%;">Cài Đặt</th>
                                    </tr>
                                    </thead>
                                    <tbody style="white-space: pre-line">

                                    </tbody>
                                    </table>
                                </div>

                                <div class="tab-pane  fade" id="menu1">
                                    <table id="table-tab2" class="table table-bordered table-striped"
                                           style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                    ", Times, serif !important;font-size: 12px !important;">
                                    <thead>
                                    <tr>
                                        <th>Ngày Tạo</th>
                                        <th>Mã Đơn</th>
                                        <th>Shop</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!--                        <div class="row">-->
                        <!---->
                        <!--                            <div class="col-md-12" id="table-order-wrapper">-->
                        <!--                                <table id="example" class="table table-bordered table-striped"-->
                        <!--                                       style="border-collapse: collapse;width:100%;font-family: " Times New Roman-->
                        <!--                                ", Times, serif !important;font-size: 12px !important;">-->
                        <!--                                <thead class="hidden">-->
                        <!--                                <tr>-->
                        <!--                                    <th>Name</th>-->
                        <!--                                    <th>Position</th>-->
                        <!--                                    <th>Office</th>-->
                        <!--                                    <th>Extn.</th>-->
                        <!--                                    <th style="width: 24%;">Start date</th>-->
                        <!--                                    <th>Salary</th>-->
                        <!--                                </tr>-->
                        <!--                                </thead>-->
                        <!---->
                        <!--                                </table>-->
                        <!--                            </div>-->
                        <!--                        </div>-->

                    </div>
                </div>
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
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
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
    function emptyDateCreate() {
        $(".dateCreate").each(function() {
            realDate =new Date("")
            $(this).datepicker('setDate', '');
        });
    }
    function emptyDateReturn() {
        $(".dateReturn").each(function() {
            realDate =new Date("")
            $(this).datepicker('setDate', '');
        });
    }
        $('.datetimepicker-date').datepicker({
            format: 'd-m-Y',
            timePicker: false,
        });

        $(".datetimepicker-date").each(function () {
            if ($(this).val() != "") {
                realDate = new Date($(this).val());
                $(this).datepicker("option", "dateFormat", "dd/mm/yy"); // format to show
                $(this).datepicker('setDate', realDate);
            }

        });
        loadTableDetail()


        $("#customer").select2({
            placeholder: "Vui Lòng Chọn Tên Khách Hàng",
            allowClear: true
        });

        $("#city").select2({
            placeholder: "Vui Lòng Chọn Tỉnh",
            allowClear: true
        });
        $("#district").select2({
            placeholder: "Vui Lòng Chọn Huyện",
            allowClear: true
        });
        $("#region").select2({
            placeholder: "Vui Lòng Chọn Vùng Miền",
            allowClear: true
        });
        $("#status").select2({
            placeholder: "Vui Lòng Chọn Tình Trạng",
            allowClear: true
        });
        $("#is_hd_branch").select2({
            placeholder: "Vui Lòng Chọn Chi Nhánh",
            allowClear: true
        });
        $("#dvvc").select2({
            placeholder: "Vui Lòng Chọn Đơn Vị Vận Chuyển",
            allowClear: true
        })



    function searchOrder() {
        let order = $("#note").val();
        if (order != "") {
            $('.return-style #danhsach').removeClass('hidden')
            let data = order.split('\n')
            if(data[data.length -1] ==""){
                data.splice(-1,1)
            }
            $.ajax({
                url: "/system/admin/imports/getMaVachImports?data=" + data, success: function (result) {

                    let table0 = "";
                    let table1 = "";
                    let list_id = [];
                    $.each(result.table0, function (key, value) {
                        table0 += value + "\n"
                    })
                    $.each(result.table1, function (key, value) {
                        list_id.push(value.id)
                        table1 += `<tr>
                    <td>${value.code_supership}</td>
                    <td>${value.shop}</td>
                    <td>${value.receiver} - ${value.phone} - ${value.address}, ${value.district}, ${value.ward} </td> </tr> `
                    })
                    $("#list-id-return").attr('list_id_return', JSON.stringify(list_id))
                    $("#note_error").val(table0)
                    $("#table1 tbody").html(table1)
                }
            });
        }


    }

    function confirmReturn() {
        document.getElementById("btn-confirm").disabled = true;
        let shop = '';
        let total = '';
        let html = '<tr>';

        let listId = $("#list-id-return").attr('list_id_return')
        $.ajax({
            url: "/system/admin/imports/updateOrderShopImport?data=" + JSON.parse(listId), success: function (result) {
                window.location.href = '/system/admin/imports';


                html += `
                    <td> ${formatDateTime(result[0].date_return)}</td>
                    <td> ${formatDateTime(result[0].created_at)}</td>
                    <td>${result[0].code_return}</td>`
                $.each(result, function (key, value) {
                    shop += value.shop;
                    shop += '\n';
                    total += value.total;
                    total += '\n';

                })
                html += `
                    <td>${shop}  </td>
                    <td>${total}  </td>
                    <td>
                        <button  class="btn btn-primary" onclick="printOrderReturnPdf('${result[0].code_return}')">In</button>
<!--                    <button  class="btn btn-success" onclick="printOrderReturn('-----')">Xuất Excel</button>-->
                    </td> `
                html += "</tr>";
                loadTableDetail(result[0].code_return,true)
                $("#table-tab1 tbody").html(html)
                toastr.success('Xác Nhận Thành Công!');

            },error: function () {
                document.getElementById("btn-confirm").disabled = false;
                toastr.error('Xác Nhận Không Thành Công!');

            }
        });

    }

    function formatDateTime($datetime) {
        let result = "";
        if ($datetime != null && $datetime != "") {
            result = moment($datetime).format('DD-MM-YYYY HH:mm:SS')
        }
        return result
    }

    function loadTableDetail($maPhieu = "",confirm=false) {

        let created_date_from = $("#created-from-date").val();
        let created_date_to = $("#created-to-date").val();
        let return_date_from = $("#return-from-date").val();
        let return_date_to = $("#return-to-date").val();
        let customer = $("#customer").val();
        let codeSupership = $("#code-supership").val();
        let data = {
            created_date_from: (created_date_from) ? moment(new Date(convertDate(created_date_from))).format('YYYY/MM/DD') : "",
            created_date_to: (created_date_to) ? moment(new Date(convertDate(created_date_to))).format('YYYY/MM/DD') : "",
            return_date_from: (return_date_from) ? moment(new Date(convertDate(return_date_from))).format('YYYY/MM/DD') : "",
            return_date_to: (return_date_to) ? moment(new Date(convertDate(return_date_to))).format('YYYY/MM/DD') : "",
            customer: customer,
            code_super_ship: codeSupership,
            code_return: $maPhieu
        };
        let linkApi = '/system/admin/imports/getTableDetail?jsonData=' + JSON.stringify(data);

        $.ajax({
            url: linkApi, success: function (result) {
                let html = '';
                $.each(result.table_detail, function (key, value) {
                    html += "<tr>";
                    html += `
                    <td> ${formatDateTime(value.created_at)}</td>`;

                    html += `
                    <td>${value.code_supership}  </td>
                    <td>${value.shop}  </td>`;

                    html += "</tr>";
                })
                $("#table-tab2 tbody").html(html)
                let htmlTable1 ="";
                //load hien thi theo phieu
                if(!confirm){
                    $.each(result.table_phieu, function (key, value1) {
                        var shop = '';
                        var total = '';
                        var date_return = '';
                        var html = "<tr>"
                        $.each(value1, function (key, value2) {
                            shop += value2.shop;
                            total += value2.total;
                            date_return+=formatDateTime(value2.date_return);
                            shop += '\n';
                            total += '\n';
                            date_return += '\n';

                        })
                        html += `
                    <td> ${formatDateTime(value1[0].created_at)}</td>
                    <td>${value1[0].code_return}</td>`
                        html += `
                    <td>${shop}  </td>
                    <td>${total}  </td>
                    <td style="text-align: center;">
                 <button  class="btn btn-primary" onclick="printOrderReturnPdf('${value1[0].code_return}')">In</button></td> `
                        html += "</tr>";
                        htmlTable1+=html;
                    })
                    $("#table-tab1 tbody").html(htmlTable1)
                }



            }
        });
    }

    function emptyDate() {
        $(".datetimepicker-date").each(function () {
            realDate = new Date("");
            $(this).datepicker('setDate', '');
        });
    }
    // <!--                    <button  class="btn btn-success" onclick="printOrderReturn('-----')">Xuất Excel</button>-->

    function printOrderReturn(codeReturn) {
        let linkApi = '/system/api/return/print_excel?code_return=' + codeReturn;

        $.ajax({
            url: linkApi, success: function (result) {
                window.location.href = result.url

            }
        });
    }

    function printOrderReturnPdf(codeReturn) {
        let linkApi = '/system/admin/imports/printPdf?code_return=' + codeReturn;
        window.open(linkApi)

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


