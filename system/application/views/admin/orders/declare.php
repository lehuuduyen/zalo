<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    body .order-style {
        font-family: "Times New Roman", Times, serif !important;
        font-size: 13px;
    !important;
    }

    .control-label {
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
        table-layout: fixed;
    / / add this word-wrap: break-word;
    / / add this
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

    .select2 {
        width: 100% !important;
    }

    table tbody td:first-child {
        width: 50px !important;
    }

    table tbody td:last-child {
        width: 400px !important;
    }

    table tbody .sorting_1 {
        width: 200px !important;
    }

    .dataTables_length label {
        font-size: 13px;
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
    <div class="content order-style">
        <div class="row">
            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h3>KHAI BÁO</h3>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
                        <div class="clearfix"></div>
                        <div class="col-md-12" style="margin-bottom: 10px">
                            <button class="btn btn-success btn-declare" style="margin-right:10px"
                                    onclick="$('#list-table').removeClass('hidden');$('#list-table2').addClass('hidden');$('#list-table3').addClass('hidden');getTable();$('.btn-declare').addClass('btn-danger');$('.btn-declare').removeClass('btn-success');$('.btn-deadline').removeClass('btn-danger');$('.btn-deadline').addClass('btn-success');$('.btn-warehouse').removeClass('btn-danger');$('.btn-warehouse').addClass('btn-success');$('.btn-warehouse').addClass('btn-success')">
                                Trạng Thái Đơn Hàng
                            </button>
                            <button class="btn btn-success btn-deadline"
                                    onclick="$('#list-table2').removeClass('hidden');$('#list-table').addClass('hidden');$('#list-table3').addClass('hidden');getTableDealine();$('.btn-declare').removeClass('btn-danger');$('.btn-declare').addClass('btn-success');$('.btn-deadline').addClass('btn-danger');$('.btn-deadline').removeClass('btn-success');$('.btn-warehouse').addClass('btn-success');$('.btn-warehouse').removeClass('btn-danger')">
                                Deadline Hành Trình
                            </button>
                            <button class="btn btn-success btn-warehouse"
                                    onclick="$('#list-table3').removeClass('hidden');$('#list-table').addClass('hidden');$('#list-table2').addClass('hidden');getTableWarehouse();$('.btn-declare').removeClass('btn-danger');$('.btn-deadline').removeClass('btn-danger');$('.btn-declare').addClass('btn-success');$('.btn-deadline').addClass('btn-success');$('.btn-warehouse').addClass('btn-danger');$('.btn-warehouse').removeClass('btn-success');">
                                Quản lý kho hàng
                            </button>
                        </div>
                        <div class="clearfix"></div>
                        <div class="hidden" id="list-table">

                            <div class="col-md-12">
                                <button class="btn btn-info" onclick="$('#modal-update').modal()">Thêm mới</button>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-12">
                                <table id="table-declare" class="table table-bordered table-striped"
                                       style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                ", Times, serif !important;font-size: 12px !important;">
                                <thead>
                                <tr>
                                    <th>Tên Trạng Thái</th>
                                    <th>Trạng Thái Thất Bại</th>
                                    <th>Trạng Thái Thành Công</th>
                                    <th>Trạng Thái Công Nợ</th>
                                    <th>Trạng Thái Cuối Cùng</th>
                                    <th style="text-align: center">Màu sắc</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="hidden" id="list-table2">

                            <div class="col-md-12">
                                <button class="btn btn-info" onclick="$('#modal-update-deadline').modal()">Thêm Mới
                                </button>
                                <button class="btn btn-info" onclick="$('#modal-update-time').modal()">Cập Nhật Giờ Hành
                                    Chính
                                </button>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-12">
                                <table id="table-deadline" class="table table-bordered table-striped"
                                       style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                ", Times, serif !important;font-size: 12px !important;">
                                <thead>
                                <tr>
                                    <th>Tên Hành Trình</th>
                                    <th>Ghi Chú</th>
                                    <th>Thời Gian Xử Lý Nội Tỉnh</th>
                                    <th>Thời Gian Xử Lý Nội Miền</th>
                                    <th>Time Xử Lý Liên Miền SPS, GHTK, VNC</th>
                                    <th>DVVC</th>

                                    <th></th>

                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="hidden" id="list-table3">

                            <div class="col-md-12">
                                <button class="btn btn-info open-modal-warehouse">Thêm Mới
                                </button>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-12">
                                <table id="table-warehouse" class="table table-bordered table-striped"
                                       style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                ", Times, serif !important;font-size: 12px !important;">
                                <thead>
                                <tr>
                                    <th>TT</th>
                                    <th>Địa chỉ</th>
                                    <th>Điện thoại</th>
                                    <th>Tỉnh/thành phố</th>
                                    <th>Quận/Huyện</th>
                                    <th>Xã/Phường</th>
                                    <th>Kho mặc định</th>

                                    <th></th>

                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                </table>
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
                    <h5 class="modal-title">Thêm Khai Báo</h5>
                </span>
                <span style="margin-left: auto;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </span>

            </div>
            <input type="hidden" id="declare-id">
            <div class="col-md-12">&nbsp;</div>

            <div class="col-md-12">
                <label>Tên Trạng Thái</label>
                <div class="col-md-12 row"><input type="text" class="form-control" id="name"></div>
            </div>
            <div class="col-md-12">
                <label>Trạng Thái Thất Bại</label>
                <div class="col-md-12 row">
                    <select class="form-control" id="fail">
                        <option value="0"> Không</option>
                        <option value="1"> Có</option>
                    </select></div>

            </div>
            <div class="col-md-12">
                <label>Trạng Thái Thành Công</label>
                <div class="col-md-12 row">
                    <select class="form-control" id="success">
                        <option value="0"> Không</option>
                        <option value="1"> Có</option>
                    </select></div>
            </div>
            <div class="col-md-12">
                <label>Trạng Thái Công Nợ.</label>
                <div class="col-md-12 row">
                    <select class="form-control" id="debit">
                        <option value="0"> Không</option>
                        <option value="1"> Có</option>
                    </select></div>
            </div>
            <div class="col-md-12">
                <label>Trạng Thái Cuối Cùng</label>
                <div class="col-md-12 row">
                    <select class="form-control" id="status_end">
                        <option value="0"> Không</option>
                        <option value="1"> Có</option>
                    </select></div>
            </div>
            <div class="col-md-12">
                <label>Màu sắc</label>
                <div class="col-md-12 row">
                    <input type="color" class="form-control" id="favcolor" name="favcolor" value="#ff0000">
                </div>
            </div>
            <div class="col-md-12">&nbsp;</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateNode()">Cập nhật</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true"
     id="modal-update-deadline">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 7px !important;display: flex">
                <span>
                    <h5 class="modal-title">Thêm Deadline Hành Trình</h5>
                </span>
                <span style="margin-left: auto;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </span>

            </div>
            <input type="hidden" id="deadline-id">
            <div class="col-md-12">&nbsp;</div>

            <div class="col-md-12">
                <label>Tên Hành Trình</label>
                <div class="col-md-12 row"><input type="text" class="form-control" id="name-deadline"></div>
            </div>
            <div class="col-md-12">
                <label>Ghi Chú</label>
                <div class="col-md-12 row">
                    <textarea oninput="auto_grow(this)" class="form-control" id="note-deadline"> </textarea>
                </div>
            </div>
            <div class="col-md-12">
                <label>Thời Gian Xử Lý Nội Tỉnh</label>
                <div class="col-md-12 row"><input type="text" class="form-control" id="time-nt"></div>
            </div>
            <div class="col-md-12">
                <label>Thời Gian Xử Lý Nội Miền</label>
                <div class="col-md-12 row"><input type="text" class="form-control" id="time-nm"></div>
            </div>
            <div class="col-md-12">
                <label>Time Xử Lý Liên Miền SPS, GHTK, VNC</label>
                <div class="col-md-12 row"><input type="text" class="form-control" id="time-lm"></div>
            </div>

            <div class="col-md-12">
                <label>DVVC</label>
                <div class="col-md-12 row">
                    <select class="form-control" id="dvvc">
                        <option value="SPS">SPS</option>
                        <option value="VNC">VNC</option>
                        <option value="GHTK">GHTK</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12">&nbsp;</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateDeadline()">Cập nhật</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true"
     id="modal-update-time">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 7px !important;display: flex">
                <span>
                    <h5 class="modal-title">Cập Nhật Giờ Hành Chính</h5>
                </span>
                <span style="margin-left: auto;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </span>

            </div>

            <div class="col-md-12">
                <label>Giờ bắt đầu</label>
                <div class="col-md-12 row"><input type="time" class="form-control timepicker"
                                                  id="hanh_chinh_time_start"></div>
            </div>
            <div class="col-md-12">
                <label>Giờ kết thúc</label>
                <div class="col-md-12 row"><input type="time" class="form-control timepicker" id="hanh_chinh_time_end">
                </div>
            </div>


            <div class="col-md-12">&nbsp;</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateTimeHanhChinh()">Cập nhật</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="default_warehouse" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width:80%;margin:auto;margin-top:50px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo "Khai Báo Kho Hàng"; ?></h4>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id_warehouse_default" id="id_warehouse_default" value="0">
                <div class="form-group ">
                    <label for="mass_default">Địa chỉ kho</label>
                    <input type="text" class="form-control" placeholder="Địa chỉ kho" value="" id="address" name="address">
                </div>

                <div class="form-group ">
                    <label for="volume_default">Điện Thoại</label>
                    <input type="text" class="form-control" name="phone_default" placeholder="Điện Thoại" id="phone_default">
                </div>

                <div class="form-group ">
                    <label for="province">Tỉnh/Thành</label>
                    <select data-live-search="true" class="form-control" id="province_default"
                            name="province_default">
                        <option value="null">Chọn Tỉnh/Thành</option>
                        <?php foreach ($province as $value) { ?>
                            <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group ">
                    <label for="type_customer">Chọn Quận Huyện/Thành Phố:</label>
                    <select class="form-control" id="district_default" name="district_default">

                        <?php foreach ($district_hd as $key => $value): ?>
                            <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group ">
                    <label for="type_customer">Chọn Phường Xã:</label>
                    <div class="load-area">
                        <select class="form-control" id="area_hd_default" name="area_hd_default">

                        </select>
                    </div>
                </div>

                <div class="form-group ">
                    <label for="type_customer">
                        <input type="checkbox" value="1" id="is_default">
                        Mặc định
                    </label>
                </div>


            </div>

            <div class="modal-footer">
                <input type="hidden" id="active" value="0">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="button" class="btn btn-primary" onclick="setWareHouse()"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div>

</div>


<?php init_tail(); ?>
<script type="text/javascript" charset="utf8"
        src="https://cdn.jsdelivr.net/npm/sweetalert2@9.10.9/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://codeseven.github.io/toastr/build/toastr.min.js"></script>
<script>

    $(document).on('change', '#province_default', function () {
        var provinces = JSON.parse($("#province_default").find(":selected").val());
        if (provinces) {

            $('.disable-view').show();
            $('#loader-repo2').show();
            province_name = provinces.name;
            $.ajax({
                url: '/system/admin/create_order_bestinc/get_district_by_hd/' + provinces.code,
                method: 'GET',
                success: function (data) {
                    $('.disable-view').hide();
                    $('#loader-repo2').hide();
                    data = JSON.parse(data);
                    data_districts_default = data;
                    $('#district_default').empty();
                    $('#area_hd_default').empty();

                    var html = '';
                    html += `<option  value='null'>Chọn Quận Huyện/Thành Phố</option>`;
                    for (var i = 0; i < data.length; i++) {
                        html += `<option  value='${i}'>${data[i].name}</option>`;
                    }
                    $('#district_default').append(html);
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
    });

    $(document).on('change', '#district_default', function () {
        var index = $(this).val();
        if (index > -1) {
            var val = data_districts_default[index];
            district_name = val.name;

            $.ajax({
                url: '/system/admin/pick_up_points/get_commune_by_hd/' + val.code,
                method: 'GET',
                success: function (data) {
                    $('.disable-view').hide();
                    $('#loader-repo2').hide();
                    data = JSON.parse(data);
                    data_commue_default = data;
                    $('#area_hd_default').empty();
                    var html = '<option  value="null">Chọn Phường Xã</option>';
                    for (var i = 0; i < data.length; i++) {
                        html += `<option  value='${i}'>${data[i].name}</option>`;
                    }
                    $('#area_hd_default').append(html);
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
    });

    $(document).on('change', '#area_hd_default', function () {
        var index = $(this).val();
        if (index > -1) {
            var val = data_commue_default[index];
            commune_name = val.name;
        }
    });

    function getTable() {
        $.ajax({
            url: `/system/admin/declare_controller/get`, success: function (result) {
                let listData = JSON.parse(result)
                let html = ""
                $.each(listData, function (key, val) {
                    if (val.fail == 0) {
                        fail = "Không"
                    } else {
                        fail = "Có"
                    }
                    if (val.success == 0) {
                        success = "Không"
                    } else {
                        success = "Có"
                    }
                    if (val.debit == 0) {
                        debit = "Không"
                    } else {
                        debit = "Có"
                    }

                    if (val.status_end == 0) {
                        status_end = "Không"
                    } else {
                        status_end = "Có"
                    }
                    var color = `<input type="color" class="form-control" disabled  value="#${val.color}">`
                    html += `<tr >
                                    <td>${val.name}</td>
                                    <td>${fail}</td>
                                    <td>${success}</td>
                                    <td>${debit}</td>
                                    <td>${status_end}</td>
                                    <td>${(val.color) ? color : ""}</td>
                                    <td>
                                        <button class="btn btn-info" onclick="modalUpdate(${val.id})">Sửa</button>
                                        <button class="btn btn-danger" onclick="deleteDeclare(${val.id})">Xóa</button>
                                    </td>
                                </tr>`
                })
                $("#table-declare tbody").html(html)
            }
        });
    }

    function getTableDealine() {
        $.ajax({
            url: `/system/admin/declare_controller/getDeadline`,
            success: function (result) {
                let listData = JSON.parse(result)
                let html = ""
                $.each(listData, function (key, val) {

                    html += `<tr >
                                    <td>${val.name}</td>
                                    <td>${(val.note) ? val.note : ""}</td>
                                    <td>${val.time_nt}</td>
                                    <td>${val.time_nm}</td>
                                    <td>${val.time_lm}</td>
                                    <td>${(val.dvvc) ? val.dvvc : ""}</td>
                                    <td>
                                        <button class="btn btn-info" onclick="modalUpdateDeadline(${val.id})">Sửa</button>
                                        <button class="btn btn-danger" onclick="deleteDeadline(${val.id})">Xóa</button>
                                    </td>
                                </tr>`
                })
                $("#table-deadline tbody").html(html)
            }
        });
    }

    // Warehouse
    function getTableWarehouse() {
        $.ajax({
            url: `/system/admin/declare_controller/getWarehouse`,
            success: function (result) {
                let listData = JSON.parse(result)
                let html = ""
                let index = 0;
                $.each(listData, function (key, val) {
                    index++;
                    html += '<tr>';
                    html += '<td>'+ index+'</td>';
                    html += '<td>'+ val.nameAddress +'</td>';
                    html += '<td>'+ val.phone +'</td>';
                    html += '<td>'+ val.province +'</td>';
                    html += '<td>'+ val.district +'</td>';
                    html += '<td>'+ val.commune +'</td>';
                    if(val.is_default === "1")
                        html += '<td><i class="fa fa-check"></i></td>';
                    else
                        html += '<td></td>';
                    html += '<td>';
                    html += '   <button class="btn btn-danger" onclick="fnDeleteWarehouse('+ val.id +')">Xóa</button>';
                    html += '</td>';
                    html += '</tr>';
                })
                $("#table-warehouse tbody").html(html)
            }
        });
    }

    $('.open-modal-warehouse').click(function () {

        var p = JSON.parse($("#province_default").find(":selected").val());

        if(p){
            $('.disable-view').show();
            $('#loader-repo2').show();
            $.ajax({
                url: '/system/admin/create_order_bestinc/get_district_by_hd/' + p.code,
                method: 'GET',
                success: function (data) {
                    $('.disable-view').hide();
                    $('#loader-repo2').hide();
                    data = JSON.parse(data);
                    data_districts_default = data;
                    $('#district_default').empty();
                    $('#area_hd_default').empty();
                    $('.load-html').empty();

                    var html = '';
                    var val_district = '';
                    var i = 0;

                    html += '<option  value="null">Chọn Quận Huyện/Thành Phố</option>';
                    $.each(data, function(index, value){
                        if(value.name === district_name){
                            html += '<option selected value="'+ i +'">'+ value.name +'</option>';
                            val_district = value;
                        }else{
                            html += '<option value="'+ i +'">'+ value.name +'</option>';
                        }
                    });
                    $('#district_default').append(html);


                    // commune_name
                    $.ajax({
                        url: '/system/admin/pick_up_points/get_commune_by_hd/' + val_district.code,
                        method: 'GET',
                        success: function (data) {
                            $('.disable-view').hide();
                            $('#loader-repo2').hide();
                            data = JSON.parse(data);
                            data_commue_default = data;
                            $('#area_hd_default').empty();
                            var html = '<option  value="null">Chọn Phường Xã</option>';
                            $.each(data, function(index, value){
                                if(value.name === commune_name){
                                    html += '<option selected value="'+ i +'">'+ value.name +'</option>';
                                }else{
                                    html += '<option value="'+ i +'">'+ value.name +'</option>';
                                }
                            });
                            $('#area_hd_default').append(html);
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });

                },
                error: function (e) {
                    console.log(e);
                }
            });
        }

        $('#default_warehouse').modal('show');
    });

    function setWareHouse() {
        var address_default = $("#address").val();
        var phone_default = $("#phone_default").val();
        var is_default = 0;
        if($('#is_default').is(":checked"))
            is_default = $('#is_default').val();

        var id_default = $("#id_warehouse_default").val();
        $.ajax({
            url: '<?= base_url('api/set_warehouse')?>',
            data: {
                id_default: id_default,
                address_default: address_default,
                phone_default: phone_default,
                province_name: province_name,
                district_name: district_name,
                commune_name: commune_name,
                is_default:is_default
            },
            method: "POST",
            beforeSend: function () {

            },
            success: function (data) {
                var result = JSON.parse(data);
                if (result.status === true && result.error === '') {
                    alert(result.message);
                }
            }
        });

    }

    function fnDeleteWarehouse(id) {
        window.location.href = '/system/admin/declare_controller/delete/' + id + '?t=warehouse';
    }
    // End Warehouse

    function convertDate(userDate) {
        str = userDate.split("/")
        return str[1] + "/" + str[0] + "/" + str[2]
    }

    function deleteDeclare(id) {
        $.ajax({
            url: `/system/admin/declare_controller/delete/${id}`, success: function (result) {
                getTable()
                toastr.success('', 'Xóa Thành Công Khai Báo!')

            }
        });
    }

    function modalUpdate(id) {
        $("#modal-update").modal();
        $.ajax({
            url: `/system/admin/declare_controller/get_one/${id}`, success: function (result) {
                let data = JSON.parse(result)
                document.getElementById("name").value = data.name;
                document.getElementById("fail").value = data.fail;
                document.getElementById("success").value = data.success;
                document.getElementById("debit").value = data.debit;
                document.getElementById("declare-id").value = data.id;
                document.getElementById("status_end").value = data.status_end;
                document.getElementById("favcolor").value = `#${data.color}`;
            }
        });

    }

    function updateNode() {
        let name = document.getElementById("name").value;
        let fail = document.getElementById("fail").value;
        let success = document.getElementById("success").value;
        let debit = document.getElementById("debit").value;
        let id = document.getElementById("declare-id").value;
        let status_end = document.getElementById("status_end").value;
        let color = document.getElementById("favcolor").value.substr(1);

        let json = {};
        json.name = name
        json.fail = fail
        json.success = success
        json.debit = debit
        json.status_end = status_end
        json.color = color
        if (id != "") {
            json.id = id
        }
        let data = JSON.stringify(json)

        $.ajax({
            url: `/system/admin/declare_controller/add?data=${data}`, success: function (result) {
                getTable()
                $("#modal-update").modal('hide');
                toastr.success('', 'Cập Nhật Thành Công Khai Báo!')
                document.getElementById("name").value = "";
                document.getElementById("fail").value = "";
                document.getElementById("success").value = "";
                document.getElementById("debit").value = "";
                document.getElementById("declare-id").value = "";

            }
        });
    }


    function modalUpdateDeadline(id) {
        $("#modal-update-deadline").modal();
        $.ajax({
            url: `/system/admin/declare_controller/deadline_get_one/${id}`, success: function (result) {
                let data = JSON.parse(result)
                document.getElementById("name-deadline").value = data.name;
                document.getElementById("note-deadline").value = data.note;
                document.getElementById("time-nt").value = data.time_nt;
                document.getElementById("time-nm").value = data.time_nm;
                document.getElementById("time-lm").value = data.time_lm;
                document.getElementById("dvvc").value = data.dvvc;
                document.getElementById("deadline-id").value = data.id;

            }
        });

    }

    function updateDeadline() {
        let name = document.getElementById("name-deadline").value;
        let time_nt = document.getElementById("time-nt").value;
        let note = document.getElementById("note-deadline").value;
        let time_nm = document.getElementById("time-nm").value;
        let time_lm = document.getElementById("time-lm").value;
        let dvvc = document.getElementById("dvvc").value;
        let id = document.getElementById("deadline-id").value;


        let json = {};
        json.name = name
        json.note = note
        json.time_nt = time_nt
        json.time_nm = time_nm
        json.time_lm = time_lm
        json.dvvc = dvvc

        if (id != "") {
            json.id = id
        }
        let data = JSON.stringify(json)

        $.ajax({
            url: `/system/admin/declare_controller/deadline_add?data=${data}`, success: function (result) {
                getTableDealine()
                $("#modal-update-deadline").modal('hide');
                toastr.success('', 'Cập Nhật Thành Công Deadline!')
                document.getElementById("name-deadline").value = "";
                document.getElementById("note-deadline").value = "";
                document.getElementById("time-nt").value = "";
                document.getElementById("time-nm").value = "";
                document.getElementById("time-lm").value = "";
                document.getElementById("dvvc").value = "";
                document.getElementById("deadline-id").value = "";
            }
        });
    }

    function deleteDeadline(id) {
        $.ajax({
            url: `/system/admin/declare_controller/delete_deadline/${id}`, success: function (result) {
                getTableDealine()
                toastr.success('', 'Xóa Thành Công Deadline!')

            }
        });
    }

    function auto_grow(element) {
        element.style.height = "5px";
        element.style.height = (element.scrollHeight) + "px";
    }
</script>


