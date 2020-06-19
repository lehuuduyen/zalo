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
                            <h3 >KHAI BÁO</h3>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading"/>
                        <div class="clearfix"></div>
                        <div class="col-md-12" style="margin-bottom: 10px">
                            <button class="btn btn-success btn-declare" style="margin-right:10px" onclick="$('#list-table').removeClass('hidden');$('#list-table2').addClass('hidden');getTable();$('.btn-declare').addClass('btn-danger');$('.btn-declare').removeClass('btn-success');$('.btn-deadline').removeClass('btn-danger');$('.btn-deadline').addClass('btn-success');">Trạng Thái Đơn Hàng</button>
                            <button class="btn btn-success btn-deadline" onclick="$('#list-table2').removeClass('hidden');$('#list-table').addClass('hidden');getTableDealine();$('.btn-declare').removeClass('btn-danger');$('.btn-declare').addClass('btn-success');$('.btn-deadline').addClass('btn-danger');$('.btn-deadline').removeClass('btn-success');">Deadline Hành Trình</button>
                        </div>
                        <div class="clearfix"></div>
                        <div class="hidden" id="list-table">

                            <div class="col-md-12"><button class="btn btn-info" onclick="$('#modal-update').modal()">Thêm mới</button></div>
                            <div class="clearfix"></div>

                            <div class="col-md-12">
                                <table id="table-declare" class="table table-bordered table-striped" style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                ", Times, serif !important;font-size: 12px !important;">
                                    <thead >
                                    <tr  >
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
                                <button class="btn btn-info" onclick="$('#modal-update-deadline').modal()">Thêm Mới</button>
                                <button class="btn btn-info" onclick="$('#modal-update-time').modal()">Cập Nhật Giờ Hành Chính </button>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-12">
                                <table id="table-deadline" class="table table-bordered table-striped" style="border-collapse: collapse;width:100%;font-family: " Times New Roman
                                ", Times, serif !important;font-size: 12px !important;">
                                <thead >
                                <tr >
                                    <th>Tên Hành Trình</th>
                                    <th>Ghi Chú</th>
                                    <th>Thời Gian Xử Lý Nội Tỉnh</th>
                                    <th>Thời Gian Xử Lý Nội Miền</th>
                                    <th>Time Xử Lý Liên Miền SPS, GHTK, VNC</th>
                                    <th>DVVC </th>

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
<div class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" id="modal-update" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 7px !important;display: flex" >
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
                <div class="col-md-12 row"><input type="text" class="form-control" id="name"> </div>
            </div>
            <div class="col-md-12">
                <label>Trạng Thái Thất Bại</label>
                <div class="col-md-12 row">
                    <select class="form-control" id="fail">
                        <option value="0"> Không </option>
                        <option value="1"> Có </option>
                    </select></div>

            </div>
            <div class="col-md-12">
                <label>Trạng Thái Thành Công</label>
                <div class="col-md-12 row">
                    <select class="form-control" id="success">
                        <option value="0"> Không </option>
                        <option value="1"> Có </option>
                    </select></div>
            </div>
            <div class="col-md-12">
                <label>Trạng Thái Công Nợ.</label>
                <div class="col-md-12 row">
                    <select class="form-control" id="debit">
                        <option value="0"> Không </option>
                        <option value="1"> Có </option>
                    </select></div>
            </div>
			<div class="col-md-12">
                <label>Trạng Thái Cuối Cùng</label>
                <div class="col-md-12 row">
                    <select class="form-control" id="status_end">
                        <option value="0"> Không </option>
                        <option value="1"> Có </option>
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
<div class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" id="modal-update-deadline" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 7px !important;display: flex" >
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
                <div class="col-md-12 row"><input type="text" class="form-control" id="name-deadline"> </div>
            </div>
            <div class="col-md-12">
                <label>Ghi Chú</label>
                <div class="col-md-12 row">
                    <textarea oninput="auto_grow(this)"  class="form-control" id="note-deadline"> </textarea>
                </div>
            </div>
            <div class="col-md-12">
                <label>Thời Gian Xử Lý Nội Tỉnh</label>
                <div class="col-md-12 row"><input type="text" class="form-control" id="time-nt"> </div>
            </div>
            <div class="col-md-12">
                <label>Thời Gian Xử Lý Nội Miền</label>
                <div class="col-md-12 row"><input type="text" class="form-control" id="time-nm"> </div>
            </div>
            <div class="col-md-12">
                <label>Time Xử Lý Liên Miền SPS, GHTK, VNC</label>
                <div class="col-md-12 row"><input type="text" class="form-control" id="time-lm"> </div>
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
<div class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" id="modal-update-time" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 7px !important;display: flex" >
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
                <div class="col-md-12 row"><input type="time" class="form-control timepicker" id="hanh_chinh_time_start"> </div>
            </div>
            <div class="col-md-12">
                <label>Giờ kết thúc</label>
                <div class="col-md-12 row"><input type="time" class="form-control timepicker" id="hanh_chinh_time_end"> </div>
            </div>



            <div class="col-md-12">&nbsp;</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateTimeHanhChinh()">Cập nhật</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/sweetalert2@9.10.9/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://codeseven.github.io/toastr/build/toastr.min.js"></script>
<script>


        function getTable(){
            $.ajax({url: `/system/admin/declare_controller/get`, success: function(result){
                    let listData = JSON.parse(result)
                    let html = ""
                    $.each(listData,function (key,val) {
                        if(val.fail == 0){
                            fail = "Không"
                        }else{
                            fail = "Có"
                        }
                        if(val.success == 0){
                            success = "Không"
                        }else{
                            success = "Có"
                        }
                        if(val.debit == 0){
                            debit = "Không"
                        }else{
                            debit = "Có"
                        }
						
						if(val.status_end == 0){
                            status_end = "Không"
                        }else{
                            status_end = "Có"
                        }
						var color =`<input type="color" class="form-control" disabled  value="#${val.color}">`
                        html+=`<tr >
                                    <td>${val.name}</td>
                                    <td>${fail}</td>
                                    <td>${success}</td>
                                    <td>${debit}</td>
                                    <td>${status_end}</td>
                                    <td>${(val.color)?color:""}</td>
                                    <td>
                                        <button class="btn btn-info" onclick="modalUpdate(${val.id})">Sửa</button>
                                        <button class="btn btn-danger" onclick="deleteDeclare(${val.id})">Xóa</button>
                                    </td>
                                </tr>`
                    })
                    $("#table-declare tbody").html(html)
                }});
        }
        function getTableDealine(){
            $.ajax({url: `/system/admin/declare_controller/getDeadline`, success: function(result){
                    let listData = JSON.parse(result)
                    let html = ""
                    $.each(listData,function (key,val) {

                        html+=`<tr >
                                    <td>${val.name}</td>
                                    <td>${(val.note)?val.note:""}</td>
                                    <td>${val.time_nt}</td>
                                    <td>${val.time_nm}</td>
                                    <td>${val.time_lm}</td>
                                    <td>${(val.dvvc)?val.dvvc:""}</td>
                                    <td>
                                        <button class="btn btn-info" onclick="modalUpdateDeadline(${val.id})">Sửa</button>
                                        <button class="btn btn-danger" onclick="deleteDeadline(${val.id})">Xóa</button>
                                    </td>
                                </tr>`
                    })
                    $("#table-deadline tbody").html(html)
                }});
        }
    function convertDate (userDate) {
        str = userDate.split("/")
        return str[1]+"/"+str[0]+"/"+str[2]
    }
    function deleteDeclare(id){
        $.ajax({url: `/system/admin/declare_controller/delete/${id}`, success: function(result){
                getTable()
                toastr.success('', 'Xóa Thành Công Khai Báo!')

            }});
    }

    function modalUpdate(id) {
        $("#modal-update").modal();
        $.ajax({url: `/system/admin/declare_controller/get_one/${id}`, success: function(result){
                let data = JSON.parse(result)
                document.getElementById("name").value=data.name;
                document.getElementById("fail").value=data.fail;
                document.getElementById("success").value=data.success;
                document.getElementById("debit").value=data.debit;
                document.getElementById("declare-id").value=data.id;
				document.getElementById("status_end").value=data.status_end;
                document.getElementById("favcolor").value=`#${data.color}`;
            }});

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
        if(id !=""){
            json.id = id
        }
        let data = JSON.stringify(json)

        $.ajax({url: `/system/admin/declare_controller/add?data=${data}`, success: function(result){
                getTable()
                $("#modal-update").modal('hide');
                toastr.success('', 'Cập Nhật Thành Công Khai Báo!')
                document.getElementById("name").value="";
                document.getElementById("fail").value="";
                document.getElementById("success").value="";
                document.getElementById("debit").value="";
                document.getElementById("declare-id").value="";

            }});
    }


        function modalUpdateDeadline(id) {
            $("#modal-update-deadline").modal();
            $.ajax({url: `/system/admin/declare_controller/deadline_get_one/${id}`, success: function(result){
                    let data = JSON.parse(result)
                    document.getElementById("name-deadline").value=data.name;
                    document.getElementById("note-deadline").value=data.note;
                    document.getElementById("time-nt").value=data.time_nt;
                    document.getElementById("time-nm").value=data.time_nm ;
                    document.getElementById("time-lm").value=data.time_lm;
                    document.getElementById("dvvc").value=data.dvvc;
                    document.getElementById("deadline-id").value=data.id;

                }});

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

            if(id !=""){
                json.id = id
            }
            let data = JSON.stringify(json)

            $.ajax({url: `/system/admin/declare_controller/deadline_add?data=${data}`, success: function(result){
                    getTableDealine()
                    $("#modal-update-deadline").modal('hide');
                    toastr.success('', 'Cập Nhật Thành Công Deadline!')
                    document.getElementById("name-deadline").value="";
                    document.getElementById("note-deadline").value="";
                    document.getElementById("time-nt").value="";
                    document.getElementById("time-nm").value="";
                    document.getElementById("time-lm").value="";
                    document.getElementById("dvvc").value="";
                    document.getElementById("deadline-id").value="";
                }});
        }
        function deleteDeadline(id){
            $.ajax({url: `/system/admin/declare_controller/delete_deadline/${id}`, success: function(result){
                    getTableDealine()
                    toastr.success('', 'Xóa Thành Công Deadline!')

                }});
        }
        function auto_grow(element) {
            element.style.height = "5px";
            element.style.height = (element.scrollHeight)+"px";
        }
</script>


