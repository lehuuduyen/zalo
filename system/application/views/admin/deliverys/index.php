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
                            <h4 style="color:red">QUẢN LÝ GIAO HÀNG</h4>
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

                                        <div class="col-md-3">
                                            <div class="form-group" app-field-wrapper="date_end_customer">
                                                <label for="date_end_customer" class="control-label">Ngày Bắt Đầu</label>
                                                <div class="input-group date">
                                                    <input type="text" id="date_create_start" name="date_end_customer" class="form-control datetimepicker-date" value="<?=$date_from?>" autocomplete="off">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar calendar-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1"> </div>
                                        <div class="col-md-3" >
                                            <div class="form-group" app-field-wrapper="date_end_customer">
                                                <label for="date_end_customer" class="control-label">Ngày kết thúc</label>
                                                <div class="input-group date">
                                                    <input type="text" id="date_create_end" name="date_end_customer" class="form-control datetimepicker-date" value="<?=$date_to?>" autocomplete="off">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar calendar-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1"> </div>

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
                                        <div class="col-md-3">
                                            <div class="form-group" app-field-wrapper="date_end_customer">
                                                <label for="date_end_customer" class="control-label">Trạng Thái</label>
                                                <div class="input-group date" style="width: 100%">
                                                    <select class="form-control"  id="status_find">
                                                        <option value="-1">Tất Cả</option>
                                                        <option value="2">Chưa Báo Cáo</option>
                                                        <option value="1">Đã Báo Cáo</option>
                                                    </select>&nbsp;
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1"> </div>

                                        <div class="col-md-3">
                                            <div class="form-group" app-field-wrapper="date_end_customer">
                                                <label for="date_end_customer" class="control-label">Người tạo</label>
                                                <div class="input-group date" style="width: 100%">
                                                    <select class="form-control" id="staff_create" ></select>

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
                                            <table id="example" class="table table-bordered table-striped hidden" style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                            ", Times, serif !important;font-size: 12px !important;">
                                            <thead >
                                            <tr >
                                                <th><div style="text-align: center"><input type="checkbox"  onclick="checkAll(this)"></div></th>
                                                <th >STT</th>
                                                <th >Thời Gian Tạo</th>
                                                <th >Mã Giao Hàng</th>
                                                <th >Người Giao</th>
                                                <th >Trạng Thái</th>
                                                <th >Tổng Đơn</th>
                                                <th >Đơn Đã Báo Cáo</th>
                                                <th >Tổng Tiền Thu Hộ</th>
                                                <th >Tổng Tiền Đã Thu</th>
                                                <th >Chức Năng</th>

                                            </tr>
                                            </thead>

                                            </table>
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
                    let htmlStaff = "<option></option>"
                    let dataStaff = JSON.parse(result);
                    htmlStaff += dataStaff.map(function (val,key) {
                        return `<option value="${val.staffid}">${val.full_name}</option>`
                    }).join('');
                    $("#staff_id").html(htmlStaff)
                    $("#staff_create").html(htmlStaff)
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
            $("#staff_create").select2({
                placeholder: "Vui Lòng Chọn Người Tạo",
                allowClear: true
            });

            $(".datetimepicker-date").each(function() {
                if($(this).val() !=""){
                    realDate =new Date($(this).val())
                    $(this).datepicker( "option", "dateFormat", "dd/mm/yy" ); // format to show
                    $(this).datepicker('setDate', realDate);
                }

            });
        });

        function searchTuyChinh() {
            $('#example').dataTable().fnDestroy();


            let linkApi = getLink()
            loadDatatables(linkApi);
        }

        function  getLink(dataMavach=""){
            let staff = $("#staff_id").val();
            let staffCreate = $("#staff_create").val();
            let status = $("#status_find").val();
            let dateCreateStart = ($("#date_create_start").val()) ? moment(new Date(convertDate($("#date_create_start").val()))).format('YYYY/MM/DD') : "";
            let dateCreateEnd = ($("#date_create_end").val()) ? moment(new Date(convertDate($("#date_create_end").val()))).format('YYYY/MM/DD') : "";
                let data = {
                staff:staff,
                staff_create:staffCreate,
                status:status,
                date_create_start:dateCreateStart,
                date_create_end:dateCreateEnd
            }
            let linkApi ='/system/admin/Delivery_order/get_delivery?jsonData='+JSON.stringify(data)
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
                            return `<div class="custom-control custom-checkbox" data-code="${row.code_delivery}" style="text-align: center;margin-top: 20%;">
                                      <input type="checkbox" class="custom-control-input" name="list_id" value="${row.id}">
                                    </div>`;
                        }
                    },
                    {
                        "width": "5%",
                        "targets": 1,
                        "data": null,
                        "render": function (data, type, row, meta) {

                            return ``;
                        }
                    },{
                        "width": "15%",
                        "targets": 2,
                        "data": null,
                        "render": function (data, type, row, meta) {

                            return `${moment(row.date_create).format('DD-MM-YYYY')}`;
                        }
                    },{
                        "width": "15%",
                        "targets": 3,
                        "data": null,
                        "render": function (data, type, row, meta) {

                            return row.code_delivery;
                        }
                    },{
                        "width": "10%",
                        "targets": 4,
                        "data": null,
                        "render": function (data, type, row, meta) {

                            return row.fullname;
                        }
                    },
                    {
                        "width": "10%",
                        "targets": 5,
                        "data": null,
                        "render": function (data, type, row, meta) {
                            report = "Chưa báo cáo"
                            if(typeof row.tinh_trang =='undefined'){
                                report = "<span style='font-weight: bold;color:red'>Đã báo cáo</span>"

                            }
                            return report;
                        }
                    },{
                        "width": "5%",
                        "targets": 6,
                        "data": null,
                        "render": function (data, type, row, meta) {

                            return row.tong_don;
                        }
                    },{
                        "width": "15%",
                        "targets": 7,
                        "data": null,
                        "render": function (data, type, row, meta) {
                            return "<span class='o7'></span>"

                        }
                    },{
                        "width": "15%",
                        "targets": 8,
                        "data": null,
                        "render": function (data, type, row, meta) {

                            return "<span class='o8'></span>"

                        }
                    },
                    {
                        "width": "10%",
                        "targets": 9,
                        "data": null,
                        "render": function (data, type, row, meta) {

                            return "<span class='o9'></span>"


                        }
                    },
                    {
                        "width": "5%",
                        "targets": 10,
                        "data": null,
                        "render": function (data, type, row, meta) {
                            return `<div style="text-align: center;margin-top: 5px"><a href="/system/admin/Delivery_order/detail/${row.code_delivery}" target="_blank" class="btn btn-default btn-xs" target="_blank"><i class="fa fa-eye"></i></a></div>`;
                        }
                    }

                ],
                "drawCallback": function( settings ) {
                    $("#example").removeClass('hidden')

                },
                "order": [[2, 'DESC']],
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
            table.on('order.dt search.dt', function () {
                table.column(1, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = `<div style="text-align: center">${i + 1}</div>`;
                });

            }).draw();
            table.on('order.dt search.dt', function () {
                table.column(7, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    if(i==0){
                        $.each($(this).closest('tr'),function (key,ele) {
                            code=$(ele).find('.custom-checkbox').data('code')
                            $.ajax({
                                url: "/system/admin/Delivery_order/get_count_have_delivery/"+code, success: function (result) {
                                    $(ele).find('.o7').html(`<div style="text-align: center">${result.count}</div>`);

                                }
                            });
                        })
                    }

                });
            }).draw();
            table.on('order.dt search.dt', function () {
                table.column(8, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    if(i==0){
                        $.each($(this).closest('tr'),function (key,ele) {
                            code=$(ele).find('.custom-checkbox').data('code')
                            $.ajax({
                                url: "/system/admin/Delivery_order/sumCollect/"+code, success: function (result) {
                                    $(ele).find('.o8').html(`<div style="text-align: center">${formatCurrency(result.sum)}</div>`);

                                }
                            });
                        })
                    }


                });
            }).draw();
            table.on('order.dt search.dt', function () {
                table.column(9, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    if(i==0){
                        $.each($(this).closest('tr'),function (key,ele) {
                            code=$(ele).find('.custom-checkbox').data('code')
                            $.ajax({
                                url: "/system/admin/Delivery_order/sumCollectDaThu/"+code, success: function (result) {
                                    $(ele).find('.o9').html(`<div style="text-align: center">${formatCurrency(result.sum)}</div>`);

                                }
                            });
                        })
                    }

                });
            }).draw();

            $('table').removeClass('dataTable')
        };

        function getCountDelivery(deliveryCode) {
            $.ajax({
                url: "/system/admin/Delivery_order/get_count_have_delivery/"+row.code_delivery, success: function (result) {
                    return 2;
                }
            });
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


