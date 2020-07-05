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
    table tbody td:last-child {
        width:400px!important;
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

    table td, table th{
        padding: 0px !important;
        text-align: center;
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
    .wrapper ul{
        list-style:none;
        padding:0;
        min-height:12em;
    }

    .wrapper div{
        float:left;
        margin:10px;
        border:1px solid black;
        min-width:40%;

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
                            <h4 style="color:red">QUẢN LÝ ĐƠN HÀNG SHIPER</h4>
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
                                        <div class="col-md-4" style=" margin-left: 20px;">
                                            <div class="form-group" app-field-wrapper="date_end_customer">
                                                <label for="date_end_customer" class="control-label">sMan</label>
                                                <div class="input-group "style="width: 100%">
                                                    <select class="form-control"  id="staff_id" ></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="col-md-12">
                                        <div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="btn-group">

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="btn-group pull-right">
                                                        <button type="button"
                                                                class="btn green btn-sm deliveries-find-orders-all" onclick="searchTuyChinh()" style="background-color: #009444;border-color:#009444">
                                                            Hiển Thị Tùy Chỉnh
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="table-order-wrapper">
                                        <ul id="current-files">

                                        </ul>
                                    </div>


                                </form>
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
    $('.datetimepicker-date').datepicker({
        format: 'd-m-Y',
        timePicker: false,
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
        if(_this.checked){
            $("input[name='list_id']").prop('checked', true);

        }else{
            $("input[name='list_id']").prop('checked', false);

        }
    }

    $(document).ready(function () {

        $.ajax({
            url: "/system/admin/Delivery_order/getStaff", success: function (result) {
                let htmlStaff = "<option></option>"
                let dataStaff = JSON.parse(result);
                htmlStaff += dataStaff.map(function (val,key) {
                    return `<option value="${val.staffid}">${val.full_name}</option>`
                }).join('');
                $("#staff_id").html(htmlStaff)
                $("#staff_create").html(htmlStaff)
            }
        });

        $("#current-files").sortable({
            stop: function(e, ui) {
                let arr = [];
                $.map($(this).find('li'), function(el) {
                    var json = {
                        'id':el.id,
                        'orders':$(el).index()
                    }
                    arr.push(json)
                    return el.id + ' = ' + $(el).index();
                })
                $.ajax({
                    url: "/system/admin/Shipper/updateOrders?list="+JSON.stringify(arr), success: function (result) {
                        searchTuyChinh()
                    }
                });

            }

        });



        $("#staff_id").select2({
            placeholder: "Vui Lòng Chọn Người Giao Hàng",
            allowClear: true
        });

    });
    function searchTuyChinh(){
        let staff = $("#staff_id").val()
        if(!staff){
            toastr.error('Vui Lòng Chọn Người Giao Hàng!', )
            return
        }
        callAjax(staff)

    }
    function callAjax(staff) {
        $.ajax({
            url: "/system/admin/Shipper/getDeliveryByStaff/"+staff, success: function (result) {
                let html = "";
                html += result.data.map(function (val,key) {
                    return `<li id="${val.delivery_id}">${val.orders} - ${val.code_supership} - ${val.ward} - ${val.district} - ${val.city} </li>`
                }).join('\n');
                $("#current-files").html(html)
            }
        });
    }


</script>


