<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    body .order-style {
        font-family: "Times New Roman", Times, serif !important;
        font-size: 13px;
    !important;
    }
    .control-label{
        font-size: 13px !important;

    }
    .order-style .button-blue {
        border: 1px solid #41719B;
        background: #5794CC;
        width: 35%;
        padding: 5px;
    }
    p {
        margin: 0 0 0px !important;
    }
    .order-style .button-blue:hover {
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
        table-layout: fixed;         // add this
    word-wrap:break-word;        // add this
    }

    .order-style .button-red {
        border: 1px solid #41719B;
        background: red;
        padding: 6px;
    }

    .order-style .button-green {
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
        font-size: 15px;
        padding: 1px 4px;
    }

    .label-orange {
        background: white;
        color: #e52228;
        border: 1px solid green;
    }
    .select2{
        width: 100%!important;
    }
    table tbody td:first-child {
        width:50px!important;
    }
    table tbody td:last-child {
        width:400px!important;
    }
    table tbody .sorting_1 {
        width:200px!important;
    }

    .dataTables_length label{
        font-size: 13px;
    }
    .dataTables_length select{
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
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://codeseven.github.io/toastr/build/toastr.min.css" rel="stylesheet" />

<div id="wrapper">
    <div class="overlay-dark">
        <div id="loader-repo3" class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="content order-style">
        <div class="row">
            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h3 >CHỈ MỤC TÌM KIẾM</h3>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>

                        <div class="clearfix"></div>

                        <div class="row form-horizontal">

                            <input type="hidden" id="search_fast" name="search_fast" value="<?php echo (isset($_GET['search_fast']))? $_GET['search_fast']:"" ;?>">
                            <div class="col-md-5">
                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-3 control-label">Ngày Tạo</div>
                                    <div class="col-sm-9 " style="display: flex">

                                        <input class="form-control datetimepicker-date" onkeyup="enterOrder(event)" value="<?=(isset($_GET['search_fast']))? "" :$date_from?>" id="order-from-date"
                                               type="input"> &nbsp;
                                        <input class="form-control datetimepicker-date" onkeyup="enterOrder(event)" value="<?=(isset($_GET['search_fast']))? "":$date_to?>" id="order-to-date" type="input">
                                    </div>


                                </div>

                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-3 control-label ">Khách Hàng</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <select id="customer" name="shop" >
                                            <option></option>
                                            <?php
                                            foreach ($customers as $customer){ ?>
                                                <option value="<?=$customer['name']?>"><?=$customer['name']?></option>
                                            <?php }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-3 control-label ">Trạng Thái</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <select id="status" name="status" >
                                            <option></option>
                                            <?php
                                            foreach ($list_status as $status){ ?>
                                                <option value="<?=$status?>"><?=$status?></option>
                                            <?php }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-3 control-label ">Chi Nhánh</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <select id="is_hd_branch" class="" name="is_hd_branch" >
                                            <option></option>
                                            <option value="1">Shop chi nhánh mình</option>
                                            <option value="0">Shop chi nhánh khác</option>

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-3 control-label ">Đơn Vị Vận Chuyển</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <select id="dvvc" name="dvvc" >
                                            <option></option>
                                            <?php
                                            foreach ($dvvc as $value){ ?>
                                                <option value="<?=$value['dvvc']?>"><?=$value['dvvc']?></option>
                                            <?php }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 mb-15" >
                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-2 control-label ">Mã Đơn / SĐT</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <input class="form-control" onkeyup="emptyDate();enterOrder(event)" id="code_order" type="text"> &nbsp;
                                    </div>
                                </div>

                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-2 control-label">Mã Yêu Cầu</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <input class="form-control" onkeyup="emptyDate();enterOrder(event)" id="code_request" type="text"> &nbsp;
                                    </div>
                                </div>

                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-2 control-label">Tỉnh / Huyện</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <select onchange="getDistrict(this)" id="city" name="city" >
                                            <option></option>
                                            <?php
                                            foreach ($city as $key => $value){ ?>
                                                <option value="<?=$value['city']?>"><?=$value['city']?></option>
                                            <?php } ?>
                                        </select>
                                        &nbsp;
                                        <select  id="district" name="district" >
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 no-padding mb-5">
                                    <div class="col-sm-2 control-label">Nhóm Vùng Miền</div>
                                    <div class="col-sm-9 " style="display: flex">
                                        <select  id="region" name="region" >
                                            <option></option>
                                            <?php
                                            foreach ($regions as $key => $region){ ?>
                                                <option value="<?=$region['name_region']?>"><?=$region['name_region']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-15">

                            <div class="col-md-3" style="float:left">
                                <button class="btn btn-sm btn-primary button-green mr-2" onclick="exportExcel()" style="width: 40%;">Tải Excel</button>
                                <button class="btn btn-sm btn-primary button-red mr-2" onclick="clickSearch()" style="width: 40%;">Tìm Kiếm</button>

                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-12" id="table-order-wrapper">
                                <table id="example" class="table table-bordered table-striped" style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                ", Times, serif !important;font-size: 12px !important;">
                                <thead class="hidden">
                                <tr >
                                    <th>Name</th>
                                    <th>Position</th>
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
            </div>
        </div>
    </div>
</div>
<div class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" id="modal-update" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 7px !important;display: flex" >
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
                <input type="hidden"id="shop_id">
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
<script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/sweetalert2@9.10.9/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
    jQuery(function ($)
    {
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

    $(document).ready(function () {
        let search_fast = $("#search_fast").val()
        if(search_fast!=''){
            $("#code_order").val(search_fast)

            var link = getLink();
            loadDatatables(link)
        }
        $('.datetimepicker-date').datepicker({
            format: 'd-m-Y',
            timePicker: false,
        });

        $(".datetimepicker-date").each(function() {
            if($(this).val() !=""){
                realDate =new Date($(this).val())
                $(this).datepicker( "option", "dateFormat", "dd/mm/yy" ); // format to show
                $(this).datepicker('setDate', realDate);
            }

        });



        $("#customer").select2({
            placeholder: "Vui Lòng Chọn Tên Khách Hàng",
            allowClear: true
        })

        $("#city").select2({
            placeholder: "Vui Lòng Chọn Tỉnh",
            allowClear: true
        })
        $("#district").select2({
            placeholder: "Vui Lòng Chọn Huyện",
            allowClear: true
        })
        $("#region").select2({
            placeholder: "Vui Lòng Chọn Vùng Miền",
            allowClear: true
        })
        $("#status").select2({
            placeholder: "Vui Lòng Chọn Tình Trạng",
            allowClear: true
        })
        $("#is_hd_branch").select2({
            placeholder: "Vui Lòng Chọn Chi Nhánh",
            allowClear: true
        })
        $("#dvvc").select2({
            placeholder: "Vui Lòng Chọn Đơn Vị Vận Chuyển",
            allowClear: true
        })

    })
    function enterOrder(event) {
        if (event.keyCode === 13) {
            clickSearch()
        }
    }
    function emptyDate() {
        $(".datetimepicker-date").each(function() {
            realDate =new Date("")
            $(this).datepicker('setDate', '');
        });
    }
    function getLinkView(key,code_supership,code_ghtk){
        let link = ''
        if(key == "SPS"){
            link = 'https://mysupership.com/search?category=orders&query='+code_supership;
        }else if(key =="GHTK"){
            link = 'https://khachhang.giaohangtietkiem.vn/khachhang?code='+code_ghtk;
        }

        return link;
    }
    function getLinkPrint(key,create_order_id,code_supership){
        let link = ''
        if(key == "SPS"){
            link = `https://mysupership.com/orders/print?code=${code_supership}&size=S9`;
        }else if(key =="GHTK"){
            link = `http://spshd.com/system/admin/create_order_ghtk/print_data_order/${create_order_id}?print=true`;
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
                        return row.date_create;
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
                    "width": "20%",
                    "targets": 2,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let backgroundStatus =getColorStatus(row.status)

                        let dvvc ="";
                        if(row.DVVC !=""){
                            dvvc = `<p>ĐVVC: ${row.DVVC}</p>`
                        }
                        let mkh ="";
                        if(row.code_orders !=null && row.code_orders !=""){
                            mkh = `<p>Mã Đơn KH : <span style="color:green">${row.code_orders}</span></p>`
                        }
                        let requestCode ="";
                        if(row.required_code !=null && row.required_code !=""){
                            requestCode = `<p>Mã Yêu Cầu : <span style="color:#6a7dfe">${row.required_code}</span></p></p>`
                        }
                        let ghtk ="";
                        if(row.code_ghtk !=null){
                            ghtk = `<p style="color:red">${row.code_ghtk}</p>`
                        }
                        return `
                                <div style="width: 100%" class="mb-5"><label class="label label-orange label-xs tooltips" style="color:white;background-color:${backgroundStatus}">&emsp;&emsp;${row.status}  &emsp;&emsp;</label></div>
                                <p style="color:red"> ${row.code_supership} </p>
                                ${dvvc}
                                ${ghtk}
                                ${mkh}
                                ${requestCode}
                                <p>Ngày tạo : ${moment(row.date_create).format('DD-MM-YYYY HH:mm:SS')}</p>`;
                    }
                },
                {
                    "width": "20%",
                    "targets": 3,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.hd_fee)}</span></p>`
                        if(row.hd_fee == null){
                            phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.hd_fee_stam)}</span></p>`
                        }
                        if(row.is_hd_branch ==0){
                            phi = `<p>Phí DV: <span style="color:red">${formatCurrency(row.pay_transport)}</span></p>`
                        }
                        let date = ``

                        if(row.date_debits != null && row.date_debits != ""&& row.date_debits != "0000-00-00 00:00:00"){
                            date = `<p style="color:#6a7dfe">NTN:${moment(row.date_debits).format('DD-MM-YYYY')}</p>`
                        }
                        return `
                                <p>SP: ${row.product}</p>
                                <p>Khối lượng: <span style="color:red">${row.mass}</span></p>
                                <p>Thu Hộ: <span style="color:red">${formatCurrency(row.collect)}</span></p>
                                ${phi}
                                ${date}`;
                    }
                },
                {
                    "width":"20%",
                    "targets": 4,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let address =`${(row.address)?row.address+", ":"" } ${(row.ward)?row.ward+", ":""} ${(row.district)?row.district+", ":""}  ${(row.city)?row.city:""} `


                        return `
                                <div style="width: 100%" class="mb-5"><label class="label label-orange label-xs tooltips" data-original-title="Được tạo bằng API">&emsp;&emsp;${row.city}&emsp;&emsp;</label>&emsp;</div>
                                <p>${row.receiver}</p>
                                <p style="color:red">${row.phone}</p>
                                <p>${address}</p>`;
                    }
                },
                {
                    "width": "25%",
                    "targets": 5,
                    "data": null,
                    "render": function (data, type, row, meta) {
                        let linkXem = getLinkView(row.DVVC,row.code_supership,row.code_ghtk);
                        let linkPrint = getLinkPrint(row.DVVC,row.create_order_id,row.code_supership);
                        return `<div style="width: 100%;table-layout: fixed;"><div class="mb-15" style="display:flex">
                                <a style="color: white" class="btn btn-sm btn-primary button-blue mr-2" target="_blank" href="${linkXem}"><i style="padding-right: 5px;" class="fa fa-eye"></i>Xem</a>
                                <button class="btn btn-sm btn-primary button-blue mr-2 btn${row.id}" onclick="modalUpdate(this)" data-id="${row.id}" data-note="${row.note_delay}"><i style="padding-right: 5px;" class="fa fa-comment"></i>Ghi chú</button>
                                <a style="color: white" class="btn btn-sm btn-primary button-blue" target="_blank" href="${linkPrint}"><i style="padding-right: 5px;" class="fa fa-print"></i>In</a>
                                </div>
                                <p style="color:#557f38"><strong>Ghi Chú Giao Hàng:</strong></p>
                                <p>${(row.note)?row.note:""}</p>
                                <p style="color:#A47FE1"><strong>Ghi Chú Nội Bộ:</strong></p>
                                <div style="white-space: pre-line"><span class="span${row.id}">${(row.note_delay)?row.note_delay:""}</span></p>
                                </div>`;
                    }
                }

            ],
            "drawCallback": function( settings ) {
                $("#example thead").remove();
            },
            "order": [[0, 'DESC']],
            searching: false,
            info: false,
            // lengthChange: false, // Will Disabled Record number per page
            processing:true,
            language:{
                emptyTable: " ",
                loadingRecords: '&nbsp;',
                processing: 'Loading...',
                lengthMenu: 'Hiển Thị <select>'+
                    '<option value="10">10</option>'+
                    '<option value="20">20</option>'+
                    '<option value="50">50</option>'+
                    '<option value="-1">Tất cả</option>'+
                    '</select> Dòng'
            }

        });
        table.on('order.dt search.dt', function () {
            table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = `<div style="text-align: center">${i + 1}</div>`;
            });

        }).draw();
        $('table').removeClass('dataTable')
    };
    function getColorStatus(status) {
        let color = "";
        switch (status) {
            case "Chờ Duyệt":
                color = "#0b8a00";
                break;
            case "Chờ Lấy Hàng":
                color = "#0b8a00";
                break;
            case "Đang Lấy Hàng":
                color = "#0b8a00";
                break;
            case "Đã Lấy Hàng":
                color = "#4f0080";
                break;
            case "Hoãn Lấy Hàng":
                color = "#cca200";
                break;
            case "Không Lấy Được":
                color = "#424242";
                break;
            case "Đang Nhập Kho":
                color = "#B40404";
                break;
            case "Đã Nhập Kho":
                color = "#04B4AE";
                break;
            case "Đang Chuyển Kho Giao":
                color = "#0040FF";
                break;
            case "Đã Chuyển Kho Giao":
                color = "#0B614B";
                break;
            case "Đang Giao Hàng":
                color = "#B40404";
                break;
            case "Đã Giao Hàng Toàn Bộ":
                color = "#610B0B";
                break;
            case "Đã Giao Hàng Một Phần":
                color = "#0080FF";
                break;
            case "Hoãn Giao Hàng":
                color = "#cca200";
                break;
            case "Không Giao Được":
                color = "#424242";
                break;
            case "Đã Đối Soát Giao Hàng":
                color = "#060070";
                break;
            case "Đã Đối Soát Trả Hàng":
                color = "#060070";
                break;
            case "Đang Chuyển Kho Trả":
                color = "#00646F";
                break;
            case "Đã Chuyển Kho Trả":
                color = "#777100";
                break;
            case "Đang Trả Hàng":
                color = "#B40404";
                break;
            case "Đã Trả Hàng":
                color = "#322F65";
                break;
            case "Hoãn Trả Hàng":
                color = "#cca200";
                break;
            case "Hủy":
                color = "#5F5F5F";
                break;
            case "Đang Vận Chuyển":
                color = "#B40404";
                break;
            case "Xác Nhận Hoàn":
                color = "#98782E";
                break;
            case "Đã Trả Hàng Một Phần":
                color = "#322F65";
                break;
            case "Huỷ":
                color = "#5F5F5F";
                break;
        }
        return color
    }
    function getDistrict(_this) {
        let city = $(_this).val();

        $.ajax({url: "/system/api/order/district?city="+city, success: function(result){
                let data =JSON.parse(result)
                if(data.data.length >0){
                    let html ="<option></option>"
                    html+= data.data.map(function (value) {
                        return `<option value="${value.district}">${value.district}</option>`
                    }).join('')
                    $("#district").html(html)
                }
            }});
    }

    function exportExcel() {
        let link = getLink(true)
        window.location.href = link
    }
    function clickSearch() {

        let link = getLink()
        $('#example').dataTable().fnDestroy();
        loadDatatables(link)
    }
    function convertDate (userDate) {
        str = userDate.split("/")
        return str[1]+"/"+str[0]+"/"+str[2]
    }
    function  getLink(checkExcel =false){
        let date_form = $("#order-from-date").val();
        let date_to = $("#order-to-date").val();
        let customer = $("#customer").val();
        let status = $("#status").val();
        let code_order = $("#code_order").val();
        let code_request = $("#code_request").val();
        let city = $("#city").val();
        let district = $("#district").val();
        let region = $("#region").val();
        let is_hd_branch = $("#is_hd_branch").val();
        let dvvc = $("#dvvc").val();
        let data = {
            date_form:(date_form)?moment(new Date(convertDate(date_form))).format('YYYY/MM/DD'):"",
            date_to:(date_to)?moment(new Date(convertDate(date_to))).format('YYYY/MM/DD'):"",
            customer:customer,
            status:status,
            code_order:code_order,
            code_request:code_request,
            city:city,
            district:district,
            region:region,
            is_hd_branch:is_hd_branch,
            dvvc:dvvc
        }
        let linkApi ='/system/api/order?jsonData='+JSON.stringify(data)
        if(checkExcel){
            linkApi = '/system/api/order/export_excel?jsonData='+JSON.stringify(data)
        }
        return linkApi;
    }
    function  get_region_by_city(city,district){
        let data = {
            city:city,
            district:district,
        }
        let linkApi ='/system/api/get_region_by_city?jsonData='+JSON.stringify(data)
        return linkApi;
    }
    function formatCurrency(amount){
        if(!amount){
            amount = 0;
        }
        let _currency = '';
        var formatter = new Intl.NumberFormat('vi-VN');
        amount = amount.toString().match(/\d+/);
        if(amount){
            _currency = formatter.format(amount);
        }
        return _currency;
    }
    function modalUpdate(_this) {
        $("#modal-update").modal();
        document.getElementById("shop_id").value = $(_this).data('id')
        document.getElementById("ghi-chu-noi-bo-old").value = ($(_this).attr('data-note') !="null")?$(_this).attr('data-note'):""

        document.getElementById("ghi-chu-noi-bo-new").value = ""
    }
    function updateNode() {
        let noteOld = document.getElementById("ghi-chu-noi-bo-old").value;
        let noteNew = document.getElementById("ghi-chu-noi-bo-new").value;
        if(noteOld!=""){
            noteOld+='\n';
        }
        if (noteNew != "") {
            noteNew +=  moment(new Date()).format('HH:mm DD/MM') + " " + noteNew;
        }
        let text = noteOld + noteNew;
        note = JSON.stringify(text)
        let id = document.getElementById("shop_id").value;

        $.ajax({url: `/system/api/order/update?note=${note}&id=${id}`, success: function(result){
                if(result ==true){
                    $("#modal-update").modal('hide');
                    toastr.success('Ghi Chú Nội Bộ!', 'Cập Nhật Thành Công')
                    $(`.span${id}`).html(text)
                    $(`.btn${id}`).attr('data-note',text)
                }
            }});
    }

</script>


