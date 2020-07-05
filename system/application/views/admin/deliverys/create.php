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

    .m-heading-1.m-bordered {
        border-right: 1px solid #10161c;
        border-top: 1px solid #10161c;
        border-bottom: 1px solid #10161c;
        padding: 15px;
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
    .note-danger{
        background-color: #f6c1c7;
        color:black
    }
    .note{
        border-left: 5px solid #e1293d ;
        padding:15px
    }
    .red {
        background-color: #e1293d !important;
        border-color: #e1293d !important;
        color: white
    }
    input[type=checkbox]{
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
                <select id="status" class="hidden" name="status" >
                    <option></option>
                    <?php
                    foreach ($list_status as $status => $color){ ?>
                        <option data-color="<?=$color?>" value="<?=$status?>"><?=$status?></option>
                    <?php }

                    ?>
                </select>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h4 style="color:red">TẠO GIAO HÀNG</h4>
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


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Tỉnh/Thành</label>
                                                <div class="col-md-9 ldistrict" style="">
                                                    <select multiple id="province" onchange="changeProvince(this)"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Quận/Huyện</label>
                                                <div class="col-md-9 ldistrict" style="">
                                                    <select multiple id="district" onchange="changeDistrict(this)"></select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Phường/Xã</label>
                                                <div class="col-md-9 lcommune" style="">
                                                    <select multiple id="commune" ></select>

                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-md-1 control-label">Quét Mã Vạch</label>
                                                <div class="col-md-11">
                                                    <textarea id="note"  class="form-control autosizeme order" name="order"
                                                              rows="4" placeholder="Mã Đơn Hàng" data-autosize-on="true"
                                                              style="overflow: hidden; overflow-wrap: break-word; resize: horizontal; height: 90px;"></textarea>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-toolbar">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="btn-group">
                                                            <button type="button"
                                                                    onclick="searchTuyChinh()" class="btn btn-info btn-sm deliveries-find-orders-custom">
                                                                Hiển Thị Tùy Chỉnh
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="btn-group pull-right">
                                                            <button type="button"
                                                                    class="btn green btn-sm deliveries-find-orders-all" onclick="searchAll()" style="background-color: #009444;border-color:#009444">
                                                                Hiển Thị Toàn Bộ
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                        </div>
                    <!--                    border red-->
                    <div class="m-heading-1 border-red m-bordered">
                        <div class="row" id="deliveries-order">
                                <input type="hidden" name="_token" value="5BycHW9GCGet0Mlr7Rq1SO19ZxSTGH5PCBLxr7KA">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <h4 class="bold uppercase" style="font-weight: bold"">KẾT QUẢ TÌM KIẾM</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">sMan <span class="required tooltips" data-original-title="Bắt Buộc">[*]</span></label>
                                        <div class="col-md-10 lshipper">
                                            <select  id="staff_id" ></select>

                                        </div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Xác Nhận</label>
                                        <div class="col-md-10">
                                            <div class="mt-checkbox-inline">
                                                <label class="mt-checkbox">
                                                    <input type="checkbox" id="is_check" name="agree" value="1" required=""> Tôi xác nhận sẽ tạo Bảng Kê Giao Hàng với những đơn hàng được chọn bên dưới.
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="uuid" value="12234300-b793-11ea-9a83-ab91454b07b4" required="">
                                </div>
                                <div class="col-md-3">
                                    <div class="note note-danger">
                                        Lưu ý: Chỉ nhấn vào nút Tạo Giao Hàng một lần duy nhất!
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button onclick="createDilivery()" class="form-control btn red" >Tạo Giao Hàng</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="table-order-wrapper">
                                    <table id="example" class="table table-bordered table-striped hidden" style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                    ", Times, serif !important;font-size: 12px !important;">
                                    <thead >
                                    <tr >
                                        <th><div style="text-align: center"><input type="checkbox"  onclick="checkAll(this)"></div></th>
                                        <th >Shop</th>
                                        <th >Tình trạng</th>
                                        <th >Sản phẩm</th>
                                        <th style="width: 24%;">Địa chỉ</th>
                                        <th  ></th>
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
        function changeProvince(_this){
            let listProvince = $(_this).val();
            $.ajax({
                url: "/system/admin/Delivery_order/get_district?list_province="+JSON.stringify(listProvince), success: function (result) {
                    let htmlDistrict = ""
                    let dataDistrict = JSON.parse(result);
                    htmlDistrict += dataDistrict.map(function (val,key) {
                        return `<option value="${val.district}">${val.district}</option>`
                    }).join('');
                    $("#district").html(htmlDistrict)
                }
            });
        }

        function changeDistrict(_this){
            let listDistrict = $(_this).val();
            $.ajax({
                url: "/system/admin/Delivery_order/get_commune?list_district="+JSON.stringify(listDistrict), success: function (result) {
                    let htmlCommune = ""
                    let dataCommune = JSON.parse(result);
                    htmlCommune += dataCommune.map(function (val,key) {
                        return `<option value="${val.commune}">${val.commune}</option>`
                    }).join('');
                    $("#commune").html(htmlCommune)
                }
            });
        }
        function checkAll(_this) {
            console.log(_this.checked)
            if(_this.checked){
                $("input[name='list_id']").prop('checked', true);

            }else{
                $("input[name='list_id']").prop('checked', false);

            }
        }
        function createDilivery(){
            if(!document.getElementById('is_check').checked){
                toastr.error('CHƯA CHỌN XÁC NHẬN', )
                return ;
            }
            var listId = [];
            $.each($("input[name='list_id']:checked"), function(){
                listId.push($(this).val());
            });
            let staff = $("#staff_id").val();
            let data = {
                'list_order':listId,
                'staff':staff,
            }
            $.ajax({
                url: "/system/admin/Delivery_order/add",
                type: "post",
                data: data ,

                success: function (result) {
                    if(result){
                        toastr.success('TẠO GIAO HÀNG!', 'THÊM THÀNH CÔNG')
                        window.location.href="/system/admin/Delivery_order";
                        window.open("/system/admin/Delivery_order/detail/"+result);

                    }else{
                        toastr.error('TẠO GIAO HÀNG!', 'THẤT BẠI')

                    }
                }
            });


        }
        $(document).ready(function () {

            $.ajax({
                url: "/system/admin/Delivery_order/get_province", success: function (result) {
                    let htmlProvince = ""
                    let dataProvince = JSON.parse(result);
                    htmlProvince += dataProvince.map(function (val,key) {
                        return `<option value="${val.province}">${val.province}</option>`
                    }).join('');
                    $("#province").html(htmlProvince)
                }
            });
            $.ajax({
                url: "/system/admin/Delivery_order/getStaff", success: function (result) {
                    let htmlStaff = ""
                    let dataStaff = JSON.parse(result);
                    htmlStaff += dataStaff.map(function (val,key) {
                        return `<option value="${val.staffid}">${val.full_name}</option>`
                    }).join('');
                    $("#staff_id").html(htmlStaff)
                }
            });

            $("#province").select2({
                placeholder: "Vui Lòng Chọn Tỉnh Thành",
                allowClear: true
            });
            $("#district").select2({
                placeholder: "Vui Lòng Chọn Quận Huyện",
                allowClear: true
            });
            $("#commune").select2({
                placeholder: "Vui Lòng Chọn Phường Xã",
                allowClear: true
            });

            $("#staff_id").select2({
                placeholder: "Vui Lòng Chọn Người Giao Hàng",
                allowClear: true
            });


        });

        function searchTuyChinh() {
            $("#example").removeClass('hidden')
            let order = $("#note").val();
            let dataMavach  =""
            if (order != "") {
                dataMavach = order.split('\n')
                if (dataMavach[dataMavach.length - 1] == "") {
                    dataMavach.splice(-1, 1)
                }
            }
            let linkApi = getLink(dataMavach)
            loadDatatables(linkApi);
        }
        function searchAll() {
            $("#example").removeClass('hidden')

            $('#example').dataTable().fnDestroy();

            let linkApi = '/system/admin/Delivery_order/getTableDeliveryAll'
            loadDatatables(linkApi);
        }
        function  getLink(dataMavach=""){
            $('#example').dataTable().fnDestroy();
            let province = $("#province").val();
            let district = $("#district").val();
            let commune = $("#commune").val();

            let data = {

                province:province,
                district:district,
                commune:commune,
                ma_vach:dataMavach
            }
            let linkApi ='/system/admin/Delivery_order/getTableDelivery?jsonData='+JSON.stringify(data)

            return linkApi;
        }
        function getLinkView(key,code_supership,code_ghtk){
            let link = ''
            if(key == "SPS"){
                link = 'https://mysupership.com/search?category=orders&query='+code_supership;
            }else if(key =="GHTK"){
                link = 'https://khachhang.giaohangtietkiem.vn/khachhang?code='+code_ghtk;
            }else if(key =="VNC"){
                link = 'https://cs.vncpost.com/order/list';
            }

            return link;
        }
        function getLinkPrint(key,create_order_id,code_supership){
            let link = ''
            if(key == "SPS"){
                link = `https://mysupership.com/orders/print?code=${code_supership}&size=S9`;
            }else if(key =="GHTK"){
                link = `http://spshd.com/system/admin/create_order_ghtk/print_data_order/${create_order_id}?print=true`;
            }else if(key =="VNC"){
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
                            return `<div class="custom-control custom-checkbox" style="text-align: center;margin-top: 100%;">
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
                        "width": "20%",
                        "targets": 2,
                        "data": null,
                        "render": function (data, type, row, meta) {
                            let color =$("#status").find(`option[value='${row.status}']`).data('color')
                            let backgroundStatus ="#"+color
                            if(!color){
                                backgroundStatus="black"
                            }
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
                                <p>Trị giá: <span style="color:red">${formatCurrency(row.value)}</span></p>
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
                                <div style=" margin-top: 5px;width: 100%" class="mb-5"><label class="label label-orange label-xs tooltips" data-original-title="Được tạo bằng API">&emsp;&emsp;${row.city}&emsp;&emsp;</label>&emsp;</div>
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
                            let noteArr = row.note.split("\n");
                            let htmlNode = "";
                            htmlNode += noteArr.map(function (note,key) {
                                if(note.indexOf("/")>0){
                                    return `<span style="color:red">${note}</span>`

                                }
                                return `<span style="color:blue">${note}</span>`


                            }).join('\n');
                            let linkXem = getLinkView(row.DVVC,row.code_supership,row.code_ghtk);
                            let linkPrint = getLinkPrint(row.DVVC,row.create_order_id,row.code_supership);
                            return `<div style=" margin-top: 3px;width: 100%;table-layout: fixed;"><div class="mb-15" style="display:flex">
                                <a style="color: white" class="btn btn-sm btn-primary button-blue mr-2" target="_blank" href="${linkXem}"><i style="padding-right: 5px;" class="fa fa-eye"></i>Xem</a>
                                <button class="btn btn-sm btn-primary button-blue mr-2 btn${row.id}" onclick="modalUpdate(this)" data-id="${row.id}" data-note="${row.note_delay}"><i style="padding-right: 5px;" class="fa fa-comment"></i>Ghi chú</button>
                                <a style="color: white" class="btn btn-sm btn-primary button-blue" target="_blank" href="${linkPrint}"><i style="padding-right: 5px;" class="fa fa-print"></i>In</a>
                                </div>
                                <p style="color:#557f38"><strong>Ghi Chú Giao Hàng:</strong></p>
                                <p style="white-space: pre-line">
                                ${(htmlNode)}
                                </p>
                                <p style="color:#A47FE1"><strong>Ghi Chú Nội Bộ:</strong></p>
                                <div style="white-space: pre-line"><span class="span${row.id}">${(row.note_delay)?row.note_delay:""}</span></p>
                                </div>`;
                        }
                    }

                ],
                "drawCallback": function( settings ) {
                    // $("#example thead").remove();
                },
                "order": [[0, 'DESC']],
                searching: false,
                info: false,
                // lengthChange: false, // Will Disabled Record number per page
                processing:true,
                "pageLength": 100,
                language:{
                    emptyTable: " ",
                    loadingRecords: '&nbsp;',
                    processing: 'Loading...',
                    lengthMenu: 'Hiển Thị <select>'+
                        '<option value="100">100</option>'+
                        '<option value="20">20</option>'+
                        '<option value="50">50</option>'+
                        '<option value="-1">Tất cả</option>'+
                        '</select> Dòng'
                }

            });

            $('table').removeClass('dataTable')
        };
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
                noteNew += " " + moment(new Date()).format('HH:mm DD/MM') ;
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


