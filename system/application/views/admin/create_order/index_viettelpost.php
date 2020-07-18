<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" href="/system/assets/css/create_order.css">
<style>
    .total-append {
        display: none;
    }

    .total_cal {
        display: block;
        border: 1px solid red;
        float: left;
        margin-top: 10px !important;
        margin-left: 10px;
    }

    .total_calc_cover {
        position: relative;
    }

    .total_calc_cover button {
        margin: 10px 0;
    }

    .total-append .total_label {
        margin-top: 18px !important;
    }

    #create_order_ob .control-label, #create_order_ob label {
        margin-bottom: 0;
    }

    #create_order_ob .form-group {
        margin-bottom: 5px;
    }
</style>
<style>
    #create_order .modal-header {
        display: none;
    }

    #create_order .modal-footer {
        position: absolute;
        bottom: -44px !important;
        padding: 5px;
    }

    .overlay-dark {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 123123123;
    }

    #loader-repo3 {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -32px;
        margin-left: -32px;
    }

    #loader-repo4 {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -32px;
        margin-left: -32px;
    }

    #create_order_ob .form-group label {
        font-size: 15px;
        font-weight: bold;
    }

    #create_order_ob .form-group input {
        font-size: 15px;
    }
</style>

<style media="screen">
    .bs3.bootstrap-select .dropdown-toggle .filter-option {
        padding-right: inherit;
        position: absolute;
        padding-left: 10px;
        padding-top: 4px;
    }

    .modal-body {
        overflow: auto;
        position: relative;
    }

    #create_order {
        padding: 0 !important;;
    }

    .modal-footer {
        position: absolute;
        bottom: -60px;
        width: 100%;
        background: #fff;
    }

    .more-config {
        display: none;

    }

    .collapse {
        display: none;
    }

    .add-more-phone {
        position: absolute;
        top: 22px;
        right: 16px;
        border-left: 1px solid #ddd;
        height: 35px;
        padding: 10px;
    }

    .col-md-6 {

    }

    .col-md-6.right {
        float: right;
    }

    .search-icon {
        position: absolute;
        top: 33px;
        right: 12px;
        font-size: 18px;
    }

    .search-item {
        display: none;
        position: absolute;
        width: 100%;
        top: 65px;
        left: 0;
        z-index: 1;
        max-height: 275px;
        overflow-y: auto;
        cursor: pointer;

    }

    .search-item li:hover {
        background: #ddd;
        color: #fff;
    }

    #loader-repo, #loader-repo2, #loader-repo4 {
        display: none;
    }

    .disable-view {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999999999999;
    }

    .lds-ellipsis {
        display: block;
        position: relative;
        width: 64px;
        height: 64px;
        margin: 0 auto;
    }

    .lds-ellipsis div {
        position: absolute;
        top: 27px;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: #03a9f4;
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


    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .container-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .container-checkbox :hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .container-checkbox input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .container-checkbox input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .container-checkbox .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    .cover-checked {
        position: relative;
        display: flex;
        align-items: center;
        padding-top: 5px;
        padding-left: 35px;
    }

    .modal-dialog {
        width: 100%;
        margin: 0;
    }

    #success-order .modal-dialog {
        width: 300px;
        height: 210px;
        left: 50%;
        top: 50%;
        margin-top: -105px;
        margin-left: -150px;
        position: absolute;
        overflow: hidden;
    }

    #success-order .modal-dialog .modal-body {
        height: auto !important;
    }

    #check_disable_super {
        width: 20px;
        height: 20px;
        margin-right: 5px;
        margin-top: 0;
        top: 5px;
        position: relative;
    }

    @media only screen and (max-width: 768px) {

        .modal-dialog {
            width: auto
        }

        .col-md-6 {
            width: 100%;
        }

        .col-md-6.right {
            float: none;
        }

        .inner.open {
            height: 250px;
        }
    }

    .table.table-create_order_filter {
        display: none;
    }

    .row-custom {
        display: -webkit-flex;
        display: -ms-flex;
        display: flex;
        align-items: center;
    }

    .mb0 {
        margin-bottom: 0 !important;
    }
</style>


<?php
$mass = ($default_data) ? number_format($default_data->mass_default) : '';
$mass_fake = ($default_data) ? number_format($default_data->mass_fake) : '';
$volume = ($default_data) ? number_format($default_data->volume_default) : '';
$username = ($default_data) ? $default_data->username : '';
$password = ($default_data) ? base64_decode($default_data->password) : '';
$id_default = ($default_data) ? $default_data->id : '';
?>
<div id="wrapper">
    <div class="overlay-dark">
        <div id="loader-repo3" class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (has_permission('staff', '', 'create')) { ?>
                            <div class="_buttons">
                                <a data-type="ghtk" style="margin-right:10px;" href="javascript:;"
                                   class="open-modal-addnew btn btn-info pull-left display-block"><?php echo _l('new_polycy'); ?></a>

                                <a style="margin-right:10px;" href="javascript:;"
                                   class="open-modal-default-value btn btn-info pull-left display-block">Khai Báo</a>
                                <a style="margin-right:10px;" href="javascript:;"
                                   class="open-modal-declare btn btn-info pull-left display-block">Khai Báo Trạng Thái
                                    Đơn Hàng</a>

                                <!-- <a style="margin-right:10px;" href="javascript:;" class="btn btn-info pull-left display-block" id="import-customers">Import Excel</a> -->
                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading"/>
                        <?php } ?>
                        <div class="clearfix"></div>


                        <div id="debts_customer" class="">
                            <div class="row">

                                <div class="col-md-2">
                                    <?php echo render_select('id_customer', $customer, array('id', 'display_shop_code'), 'Khách Hàng'); ?>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="province">Tỉnh/Thành</label>
                                        <select data-live-search="true" class="form-control selectpicker"
                                                id="province_filter" name="province_filter">
                                            <option value="null">Chọn Tỉnh/Thành</option>
                                            <?php foreach ($province as $key => $value): ?>
                                                <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>


                                    <div class="col-md-12">
                                        <div id="loader-repo4" class="lds-ellipsis">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                        </div>
                                    </div>


                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="type_customer">Quận Huyện/Thành Phố:</label>
                                        <select class="form-control" id="district_filter" name="district_filter">

                                            <?php foreach ($district_hd as $key => $value): ?>
                                                <option value='<?php echo $value->name ?>'><?php echo $value->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <?php echo render_date_input('date_start_customer', 'Ngày bắt đầu', _d($date_start)); ?>
                                </div>
                                <div class="col-md-2">
                                    <?php echo render_date_input('date_end_customer', 'Ngày kết thúc', _d($date_end)); ?>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="province">Người Tạo</label>
                                        <select data-live-search="true" class="form-control selectpicker"
                                                id="staff_filter" name="staff_filter">
                                            <option value="null">Chọn Người Tạo</option>
                                            <?php foreach ($staffs as $key => $staff): ?>
                                                <option value='<?php echo $staff->staffid ?>'><?php echo $staff->lastname . ' ' . $staff->firstname ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>


                                </div>

                                <div class="col-md-12 total_calc_cover">
                                    <button class="btn btn-info mtop25" type="button" id="filter_create_order">Lọc Danh
                                        Sách
                                    </button>
                                    <input type="hidden" name="enable_filter" value="0">
                                </div>
                                <div class="clearfix"></div>

                            </div>

                        </div>

                        <?php render_datatable(array(
                            _l('id'), //0
                            _l('Ngày Tạo'), //1
                            _l('product'),//2
                            _l('Mã YC'),//3
                            _l('Mã Đơn Hàng'), //4
                            _l('Khách Hàng'),//5
                            _l('Khách Hàng'),// 6
                            _l('Dịch Vụ Super Ship'),//7
                            _l('Tiền Hàng'),//8
                            _l('Thu hộ'),//9
                            _l('Họ và Tên Người Nhận'),//10
                            _l('SDT'),//11
                            _l('SDT Phụ'),//12
                            _l('Địa Chỉ Khách hàng'),//13
                            _l('Phường Xã'),//14
                            _l('Quận Huyện/Thành Phố'),//15
                            _l('Tỉnh'),//16
                            _l('Cân Nặng'),//17
                            _l('Thể Tích'),//18
                            _l('Mã Đơn Hàng'),//19
                            _l('Ghi Chú'),//20
                            _l('Dịch Vụ'),//21
                            _l('Cấu Hình'),//22
                            _l('Người Trả Phí'),//23
                            _l('Đổi/Lấy Hàng Về'),//24
                            _l('Giá Trị Đơn Hàng'),//25
                            _l('Người tạo'),//26
                            _l('Người tạo'),//27
                            _l('status'),//28
                            _l('Người Chịu Phí'),//29
                            _l('options'),//30
                        ), 'create_order'); ?>

                        <hr>
                        <b style="font-size: 20px">Danh Sách Trạng Thái</b>
                        <table class="table dataTable no-footer">
                            <thead>
                            <tr>
                                <th style="text-align: center">TT</th>
                                <th style="text-align: center">Trạng thái gốc</th>
                                <th style="text-align: center">Trạng thái chuyển đổi</th>
                                <th style="text-align: center">Tính nợ theo ngày đối soát</th>
                                <th style="text-align: center">Tính nợ theo ngày giao hàng</th>
                                <th style="text-align: center">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $index = 0;
                            foreach ($list_status_order as $status_order) {
                                $index++; ?>
                                <tr>
                                    <td style="text-align: center"><?= $index ?></td>
                                    <td style="text-align: center"><?= $status_order->status_ghtk ?></td>
                                    <td style="text-align: center"><?= $status_order->status_change ?></td>
                                    <td style="text-align: center"><?= (empty($status_order->status_debit)) ? 'Không tính nợ' : 'Có tính nợ' ?></td>
                                    <td style="text-align: center"><?= (empty($status_order->group_debits)) ? 'Không tính nợ' : 'Có tính nợ' ?></td>
                                    <td style="text-align: center">
                                        <a href="javascript:void(0)" onclick="fnEdit(<?= $status_order->id ?>)">Sửa</a>
                                        |
                                        <a href="<?= base_url('admin/create_order_ghtk/delete_status/' . $status_order->id) ?>">Xóa</a>
                                    </td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_order" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('create_order_viettel/add_vpost'), array('id' => 'create_order_ob', 'autocomplete' => 'off')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo "Tạo Đơn Hàng"; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="row-custom">
                        <div class="col-md-5">
                            <div class="form-group" style="position:relative">
                                <label for="customer_phone_zalo">Chọn Khách Hàng</label>
                                <input autocomplete="off" placeholder="Chọn Khách Hàng" onkeyup="enterViettel(event)"
                                       class="form-control"
                                       id="search_customer" type="text" name="search_customer"
                                       value="<?php echo set_value('search_customer'); ?>">
                                <i class="fa fa-search search-icon" aria-hidden="true"></i>

                                <ul class="list-group search-item">
                                    <?php foreach ($custommer as $value): ?>

                                        <li
                                                data-phone="<?php echo $value['customer_phone'] ?>"
                                                data-config="<?php echo $value['config'] ?>"
                                                data-note="<?php echo $value['note_default'] ?>"
                                                data-id="<?php echo $value['id'] ?>"
                                                data-token="<?php echo $value['token_customer'] ?>"
                                                data-id="<?php echo $value['id'] ?>" class="list-group-item">
                                            <?php echo $value['customer_shop_code'] ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <input type="hidden" id="customer_id" name="customer_id"
                                       value="<?php echo set_value('customer_id'); ?>">
                                <input type="hidden" id="pickup_phone" name="pickup_phone"
                                       value="<?php echo set_value('pickup_phone'); ?>">
                                <input type="hidden" id="pickup_code" name="pickup_code"
                                       value="<?php echo set_value('pickup_code'); ?>">


                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group mb0">
                                <input name="customer_local" id="customer_local" type="checkbox" value="">
                                <label for="customer_local" style="padding-left:5px;font-weight:bold;font-size:15px;">
                                    Khách hàng vừa nhập
                                </label>
                            </div>

                        </div>


                        <div class="col-md-5">
                            <!-- Chọn Khách Hang -->
                            <div id="repo_customer_cover" class="form-group">

                            </div>
                        </div>
                    </div>

                    <div id="loader-repo" class="lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <div class="row-custom">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="product">Sản Phẩm</label>
                                <input type="text" onkeyup="enterViettel(event)" class="form-control"
                                       placeholder="Sản Phẩm" id="product"
                                       name="product"></textarea>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="phone">Điện Thoại</label>
                                <input type="text" onkeyup="enterViettel(event)" class="form-control"
                                       placeholder="Điện Thoại" id="ap" name="ap"
                                       onblur="autoCompleteInfoUser(this)"/>
                                <a class="add-more-phone" href="#"><i class="fa fa-plus" aria-hidden="true"></i></a>
                            </div>
                            <!-- Phone -->
                            <div class="form-group phone-more" style="display:none">
                                <label for="phone_more">Điện Thoại Phụ </label>
                                <input onkeyup="enterViettel(event)" type="text" class="form-control"
                                       placeholder="Điện Thoại Phụ" id="phone_more"
                                       name="phone_more"/>
                            </div>
                            <!-- Phone More-->
                        </div>

                        <div class="col-md-3">
                            <div class="form-group ">
                                <label for="f">Họ Và Tên </label>
                                <input type="text" onkeyup="enterViettel(event)" class="form-control"
                                       placeholder="Họ Và Tên" id="f" name="f"/>
                            </div>
                            <!-- Họ Và Tên-->
                        </div>

                        <div class="col-md-3">
                            <div class="form-group ">
                                <label for="a">Địa Chỉ </label>
                                <input type="text" onkeyup="enterViettel(event)" class="form-control"
                                       placeholder="Địa Chỉ" id="a" name="a"
                                       autocomplete="off"/>
                            </div>
                            <!-- Địa Chỉ-->

                        </div>
                    </div>

                    <div class="row-custom">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="province">Tỉnh/Thành</label>
                                <select data-live-search="true" class="form-control selectpicker" id="province"
                                        name="province">
                                    <option value="null">Chọn Tỉnh/Thành</option>
                                    <?php foreach ($province as $key => $value): ?>
                                        <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type_customer">Chọn Quận Huyện/Thành Phố:</label>
                                <select class="form-control" id="district" name="district">

                                    <?php foreach ($district_hd as $key => $value): ?>
                                        <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type_customer">Chọn Phường Xã:</label>
                                <div class="load-area">
                                    <select class="form-control" id="area_hd" name="area_hd">


                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="region-box col-md-3">
                            <input type="hidden" name="region_id" id="region_id">
                            <div class="load-html">

                            </div>
                        </div>


                    </div>
                    <hr/>

                    <div class="row-custom">
                        <div id="loader-repo2" class="lds-ellipsis">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>


                    <div class="row-custom">
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="mass">Khối Lượng Thực [gr] </label>
                                <input style="color:red;font-weight:bold;"
                                       onkeyup="formatNumBerKeyUp(this);enterViettel(event)" type="text"
                                       class="form-control" placeholder="Khối Lượng" id="mass" name="mass"
                                       value="<?php echo $mass ?>"/>
                                <span style="color:#03a9f4;">Đơn vị tính gram (gr).</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="mass">Khối Lượng Ảo [gr] </label>
                                <input style="color:red;font-weight:bold;"
                                       onkeyup="formatNumBerKeyUp(this);enterViettel(event)" type="text"
                                       class="form-control" placeholder="Khối Lượng" id="mass_fake" name="mass_fake"
                                       value="<?php echo $mass_fake ?>"/>
                                <span style="color:#03a9f4;">Đơn vị tính gram (gr).</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="volume">Thể Tích [Cm³] </label>
                                <input style="color:red;font-weight:bold;"
                                       onkeyup="formatNumBerKeyUp(this);enterViettel(event)" type="text"
                                       class="form-control" placeholder="Thể Tích" id="volume" name="volume"
                                       value="<?php echo $volume ?>"/>
                                <span style="color:#03a9f4;">Đơn vị tính gram (Cm³).</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="value_order">Trị Giá [₫]</label>
                                <input onkeyup="formatNumBerKeyUp(this);enterViettel(event)" type="text"
                                       class="form-control"
                                       placeholder="Trị Giá" id="value_order" name="value_order"/>
                                <span style="color:transparent;">test</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="value_order">Phụ Phí [₫]</label>
                                <input onkeyup="formatNumBerKeyUp(this);enterViettel(event)" type="text"
                                       class="form-control"
                                       placeholder="Phụ Phí" id="price" name="price"/>
                                <span style="color:transparent;">test</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="supership_value">Tiền Dịch Vụ Supership [₫]</label>
                                <input onkeyup="formatNumBerKeyUp(this);enterViettel(event)" type="text"
                                       class="form-control"
                                       placeholder="Tiền Dịch Vụ Supership" id="supership_value" name="supership_value"
                                       disabled/>
                                <span style="color:transparent;">test</span>

                            </div>
                        </div>
                        <div class="col-md-2" style="display: none">
                            <div class="form-group">
                                <!-- <label for="check_disable_super"><input  id="check_disable_super" type="checkbox" >Theo Chính Sách</label> -->
                                <label for="check_disable_super"><input checked id="check_disable_super"
                                                                        type="checkbox">Theo Chính Sách</label>
                            </div>
                        </div>
                    </div>

                    <div class="row-custom">


                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="service">Người chịu phí</label>
                                <select class="form-control" id="the_fee_bearer" name="the_fee_bearer">
                                    <option value="0">Người Nhận</option>
                                    <option value="1">Người Gửi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="cod">Tiền Hàng [₫]</label>
                                <input onkeyup="formatNumBerKeyUp(this);enterViettel(event)" type="text"
                                       class="form-control"
                                       placeholder="Tiền Hàng" id="cod" name="cod"/>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="total_money">Thu Hộ</label>

                                <input onkeyup="formatNumBerKeyUp(this);enterViettel(event)" type="text"
                                       class="form-control"
                                       placeholder="Thu Hộ" id="total_money" name="total_money"/>
                            </div>
                        </div>

                        <div class="cover-checked col-md-3">
                            <label for="barter" class="container-checkbox">Đổi/Lấy Hàng Về
                                <input type="checkbox" id="barter" onclick="calc()" name="barter">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>

                    <div class="row-custom">
                        <div class="col-md-4">
                            <div class="form-group" id="special">
                                <label for="note">Chính Sách Đặc Biệt :</label>
                                <textarea style="resize:none; height:100px;" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="note">Ghi Chú Khi Giao :</label>
                                <textarea placeholder="Ghi Chú Khi Giao" style="resize:none; height:100px;"
                                          class="form-control" rows="5" id="note" name="note"></textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="note">Ghi Chú Nội Bộ :</label>
                                <textarea placeholder="Ghi Chú Nội Bộ" style="resize:none; height:100px;"
                                          class="form-control" rows="5" id="note_private"
                                          name="note_private"></textarea>
                            </div>
                        </div>

                        <div class="col-md-4" style="display: none">
                            <div class="form-group">
                                <label for="for-custom">Thống kê tiền dịch vụ super ship :</label>
                                <p id="tvc">Tiền Vận Chuyển : <span style="color:red;font-weight:bold"></span></p>
                                <p id="tvk">Tiền Vượt Khối Lượng : <span style="color:red;font-weight:bold"></span></p>
                                <p id="tvtt">Tiền vượt Thể Tích: <span style="color:red;font-weight:bold"></span></p>
                                <p id="tbh">Tiền Bảo Hiểm : <span style="color:red;font-weight:bold"></span></p>
                                <p id="tpp">Tiền Phụ Phí : <span style="color:red;font-weight:bold"></span></p>
                            </div>
                        </div>


                    </div>

                    <div class="row-custom ghtk-show">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="hidden" class="form-control"
                                       value="" id="address_id_hide"
                                       name="address_id_hide">
                                <input type="hidden" class="form-control"
                                       value="" id="shop" name="shop">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h2 style="font-size: 30px; font-weight: bold; color: red; float: right">Tạo Đơn Hàng
                                Viettel Post</h2>
                        </div>
                    </div>
                    <div style="float: right;margin: 18px 18px 0px;">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal"><?php echo _l('close'); ?></button>
                        <a href="javascript:;"
                           class="btn btn-primary submit_customer_policy"><?php echo _l('confirm'); ?></a>
                    </div>

                    <div class="col-md-12">


                        <div class="col-md-12" style="margin-bottom:10px;">
                            <hr/>
                            <a class="btn btn-primary explain" href="javascript:;">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Mở Rộng Nhập Liệu
                            </a>
                            <a class="btn btn-primary collapse" href="javascript:;">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                                Thu Gọn Nhập Liệu
                            </a>

                        </div>

                        <div class="more-config ">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="service">Dịch Vụ:</label>
                                    <select class="form-control" id="service" name="service">
                                        <option value="1">Tiết Kiệm</option>
                                        <option value="2">Tốc Hành</option>
                                        <option value="3">Nội Tỉnh</option>
                                    </select>
                                </div>


                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="payer">Người Trả Phí:</label>
                                    <select class="form-control" id="payer" name="payer">
                                        <option value="1">Người Gửi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="config">Cấu Hình:</label>
                                    <select class="form-control" id="config" name="config">
                                        <option value="1">Cho Xem Hàng Nhưng Không Cho Thử Hàng</option>
                                        <option value="2">Cho Thử Hàng</option>
                                        <option value="3">Không Cho Xem Hàng</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group ">
                                    <label for="code_order">Mã Đơn Của Shop</label>
                                    <input type="text" onkeyup="enterViettel(event)" class="form-control"
                                           placeholder="Mã Đơn Của Shop" id="soc"
                                           name="soc"/>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="config">Mã giảm giá:</label>
                                    <input type="text" onkeyup="enterViettel(event)" class="form-control"
                                           placeholder="Nhập mã giảm giá"
                                           name="voucher" id="voucher"/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="create_order_mobile" tabindex="-1" role="dialog">
    <?php echo form_open(admin_url('create_order/add'), array('id' => 'create_order_ob',)); ?>
    <div class="cover-modal">
        <div class="header-modal-mobile">
            <a class="xclose" href="#">
                Đóng
            </a>
            <p>Tạo Đơn Hàng</p>
        </div>
        <div class="container-body">
            <div class="section-body">
                <div class="header-body">
                    <p>
                        Chọn Kho Hàng
                    </p>
                </div>


                <div class="col-md-12">
                    <div class="form-group" style="position:relative">


                        <input autocomplete="off" placeholder="Chọn Khách Hàng" class="form-control"
                               id="search_customer" type="text" name="search_customer"
                               value="<?php echo set_value('search_customer'); ?>">
                        <i class="fa fa-search search-icon" aria-hidden="true"></i>

                        <ul class="list-group search-item">
                            <?php foreach ($custommer as $value): ?>

                                <li
                                        data-phone="<?php echo $value['customer_phone'] ?>"
                                        data-note="<?php echo $value['note_default'] ?>"
                                        data-config="<?php echo $value['config'] ?>"
                                        data-id="<?php echo $value['id'] ?>"
                                        data-token="<?php echo $value['token_customer'] ?>"
                                        data-id="<?php echo $value['id'] ?>" class="list-group-item">
                                    <?php echo $value->customer_shop_code ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>


                        <input type="hidden" id="customer_id" name="customer_id"
                               value="<?php echo set_value('customer_id'); ?>">
                        <input type="hidden" id="pickup_phone" name="pickup_phone"
                               value="<?php echo set_value('pickup_phone'); ?>">
                        <input type="hidden" id="pickup_code" name="pickup_code"
                               value="<?php echo set_value('pickup_code'); ?>">
                    </div>
                    <div id="repo_customer_cover" class="form-group">

                    </div>
                    <div id="loader-repo" class="lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>


            </div>
            <!-- section -->


            <div class="section-body">
                <div class="header-body">
                    <p>
                        Sản Phẩm
                    </p>
                </div>


                <div class="col-md-12">
                    <div class="round">
                        <input disabled type="checkbox" id="type_product_input" checked/>
                        <label for="type_product_input"></label>
                        <span>Nhập thủ công</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                               placeholder="Bạn muốn giao gì ví dụ quần áo giày dép ..." id="product"
                               name="product"></textarea>

                    </div>


                </div>


            </div>
            <!-- section -->


            <div class="section-body">
                <div class="header-body">
                    <p>
                        Người Nhận
                    </p>
                </div>


                <div class="col-md-12">

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Điện Thoại" id="ap" name="ap"/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Tên Người Nhận" id="f" name="f"/>
                    </div>

                    <div class="form-group ">
                        <input type="text" class="form-control" placeholder="Số nhà đường thôn ấp ..." id="a" name="a"/>
                    </div>

                    <div class="form-group">

                        <select data-live-search="true" class="form-control selectpicker" id="province" name="province">
                            <option value="null">Chọn Tỉnh/Thành</option>
                            <?php foreach ($province as $key => $value): ?>
                                <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" id="district" name="district">
                            <option value="NULL"> Chọn Quận Huyện/Thành Phố</option>
                        </select>
                    </div>

                    <div class="form-group">

                        <div class="load-area">
                            <select class="form-control" id="area_hd" name="area_hd">
                                <option value="NULL"> Chọn Phường Xã</option>

                            </select>
                        </div>
                    </div>

                    <div class="region-box">
                        <input type="hidden" name="region_id" id="region_id">
                        <div class="load-html">

                        </div>
                    </div>

                    <div class="col-md-12">

                        <div id="loader-repo2" class="lds-ellipsis">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>

                </div>


            </div>
            <!-- section -->


            <div class="section-body">
                <div class="header-body">
                    <p>
                        Gói Hàng
                    </p>
                </div>
                <div class="col-md-12">


                    <div class="form-group">
                        <input onkeyup="formatNumBerKeyUp(this)" type="text" class="form-control"
                               placeholder="Khối Lượng" id="mass" name="mass" value="<?php echo $mass ?>"/>
                        <span style="color:#03a9f4;">Đơn vị tính gram (gr).</span>
                    </div>

                    <div class="form-group ">
                        <input onkeyup="formatNumBerKeyUp(this)" type="text" class="form-control" placeholder="Thể Tích"
                               id="volume" name="volume" value="<?php echo $volume ?>"/>
                        <span style="color:#03a9f4;">Đơn vị tính gram (Cm³).</span>
                    </div>

                    <div class="form-group ">
                        <input onkeyup="formatNumBerKeyUp(this)" type="text" class="form-control"
                               placeholder="Tiền Dịch Vụ Supership" id="supership_value" name="supership_value"
                               disabled/>
                    </div>
                    <div class="form-group">
                        <label for="check_disable_super"><input checked id="check_disable_super" type="checkbox">Theo
                            Chính Sách</label>
                    </div>

                    <div class="form-group ">
                        <input onkeyup="formatNumBerKeyUp(this)" type="text" class="form-control" placeholder="Tiền COD"
                               id="cod" name="cod"/>
                    </div>


                    <div class="form-group ">
                        <input onkeyup="formatNumBerKeyUp(this)" type="text" class="form-control"
                               placeholder="Tổng Tiền" id="total_money" name="total_money"/>
                    </div>

                    <p id="tvc">Tiền Vận Chuyển : <span style="color:red;font-weight:bold"></span></p>
                    <p id="tvk">Tiền Vượt Khối Lượng : <span style="color:red;font-weight:bold"></span></p>
                    <p id="tvtt">Tiền vượt Thể Tích: <span style="color:red;font-weight:bold"></span></p>
                    <p id="tbh">Tiển Bảo Hiểm : <span style="color:red;font-weight:bold"></span></p>

                    <div class="form-group ">
                        <input type="text" class="form-control" placeholder="Mã Đơn Của Shop" id="soc" name="soc"/>
                    </div>

                    <div class="form-group ">
                        <input onkeyup="formatNumBerKeyUp(this)" type="text" class="form-control" placeholder="Trị Giá"
                               id="value_order" name="value_order"/>
                    </div>

                    <div class="note-batter">
                        Nhấn đổi lấy hàng về nếu muốn Supership thu về hàng đổi.Sau đó ghi chú vào ô ghi chú khi giao.ví
                        dụ đổi về 2 áo nếu không chọn vào ô này bạn có thể sẽ không nhận được hàng đổi về.
                    </div>
                    <div class="form-group form-swift">
                        <span>Đổi lấy hàng về</span>

                        <div class="cover-swift">
                            <div class="onoffswitch2">
                                <input type="checkbox" name="barter" class="onoffswitch2-checkbox" id="barter">
                                <label class="onoffswitch2-label" for="barter">
                                    <span class="onoffswitch2-inner"></span>
                                    <span class="onoffswitch2-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea placeholder="Ghi Chú Khi Giao" style="resize:none; height:193px;" class="form-control"
                                  rows="5" id="note" name="note"></textarea>
                    </div>


                </div>


            </div>
            <!-- section -->

            <div class="section-body">
                <div class="header-body">
                    <p>
                        Người Trả Phí
                    </p>
                </div>
                <div class="col-md-12">
                    <div class="round">
                        <input disabled="" value="1" type="checkbox" id="payer" name="payer" checked="">
                        <label></label>
                        <span>Người Gửi</span>
                    </div>
                </div>

            </div>

            <div class="section-body">
                <div class="header-body">
                    <p>
                        Dịch Vụ
                    </p>
                </div>
                <div class="col-md-12">
                    <div class="round">
                        <input value="1" type="radio" id="ser1" name="ser_set" checked="">
                        <label data-ser="1" for="ser1"></label>
                        <span>Tốc Hành</span>
                    </div>
                    <div class="round">
                        <input value="2" type="radio" id="ser2" name="ser_set">
                        <label data-ser="2" for="ser2"></label>
                        <span>Tiết kiệm</span>
                    </div>
                    <input type="hidden" name="service" id="service" value="1">
                </div>

            </div>

            <div class="section-body">
                <div class="header-body">
                    <p>
                        Cấu Hình
                    </p>
                </div>
                <div class="col-md-12" id="config_mobile">
                    <div class="round">
                        <input type="radio" id="radio1" name="config_set" checked="" value="1">
                        <label for="radio1"></label>
                        <span>Cho Xem Hàng Nhưng Không Cho Thử Hàng</span>
                    </div>
                    <div class="round">
                        <input type="radio" id="radio2" name="config_set" value="2">
                        <label for="radio2"></label>
                        <span>Cho Thử Hàng</span>
                    </div>
                    <div class="round">
                        <input type="radio" id="radio3" name="config_set" value="3">
                        <label for="radio3"></label>
                        <span>Không Cho Xem Hàng</span>
                    </div>

                    <input type="hidden" id="config" name="config" value="1">
                </div>


            </div>


        </div>

        <div class="fix-footer">
            <div class="col-md-12">
                <div class="price-footer cod_set">
                    <span>Phí giao hàng:  </span>
                    <i></i>
                </div>

                <div class="price-footer total_set">
                    <span>Tổng phí:  </span>
                    <i></i>
                </div>
                <a class="btn btn-primary submit_customer_policy" href="javascript:;">Tạo Đơn</a>
            </div>

        </div>
    </div>
    <?php echo form_close(); ?>

</div><!-- /.modal -->


<div class="modal fade" id="default_value" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width:80%;margin:auto;margin-top:50px;">
        <?php echo form_open(admin_url('create_order_viettel/add_default'), array('id' => 'create_order_default')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo "Khai Báo Mặc Định"; ?></h4>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id_default" value="<?php echo $id_default ?>">
                <div class="form-group ">
                    <label for="mass_default">Khối Lượng Thực [gr] </label>
                    <input onkeyup="formatNumBerKeyUp(this)" type="text" class="form-control" placeholder="Khối Lượng"
                           id="mass_default" name="mass_default" value="<?php echo $mass ?>">
                    <span style="color:#03a9f4;">Đơn vị tính gram (gr).</span>
                </div>

                <div class="form-group ">
                    <label for="mass_default">Khối Lượng Ảo [gr] </label>
                    <input onkeyup="formatNumBerKeyUp(this)" type="text" class="form-control" placeholder="Khối Lượng"
                           id="mass_fake" name="mass_fake" value="<?php echo $mass_fake ?>">
                    <span style="color:#03a9f4;">Đơn vị tính gram (gr).</span>
                </div>

                <div class="form-group ">
                    <label for="volume_default">Thể Tích [Cm³] </label>
                    <input onkeyup="formatNumBerKeyUp(this)" type="text" class="form-control" placeholder="Thể Tích"
                           id="volume_default" name="volume_default" value="<?php echo $volume ?>">
                    <span style="color:#03a9f4;">Đơn vị tính gram (Cm³).</span>
                </div>

                <div class="form-group ">
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" class="form-control" placeholder="Tên đăng nhập" id="username"
                           name="username" value="<?php echo $username ?>">
                    <!-- <span style="color:#03a9f4;">Token .</span> -->
                </div>

                <div class="form-group ">
                    <label for="password">Mật khẩu</label>
                    <input type="text" class="form-control" placeholder="Mật khẩu" id="password"
                           name="password" value="<?php echo $password ?>">
                    <!-- <span style="color:#03a9f4;">Token .</span> -->
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
</div>

<div class="modal fade" id="modal_import_excel_customers" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width:80%;margin:auto;margin-top:50px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Import Đơn Hàng</h4>
            </div>
            <a class="btn btn-primary" href="<?php echo base_url(); ?>assets/file_mau_customers.xlsx"
               style="margin:15px 0 0 15px">Tải file mẫu</a>
            <form method="post" id="form-submit-import-excel"
                  action="<?php echo base_url(); ?>admin/import_excel/import_customers">


                <div class="modal-body">
                    <!--                <input type="hidden" name="-->
                    <?php //echo $this->security->get_csrf_token_name();?><!--" value="-->
                    <?php //echo $this->security->get_csrf_hash();?><!--">-->
                    <input type="file" id="file" name="file">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-primary"
                            id="btn-import_excel"><?php echo _l('confirm'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .table-create_order thead tr th:nth-child(1) {
        width: 10%;
    }

    .circle-check {
        display: -webkit-flex;
        display: -ms-flex;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 70px;
        height: 70px;
        border: 3px solid #ddd;
        border-radius: 50%;
        margin: 0 auto;
        margin-bottom: 20px;
    }

    .circle-check i {
        display: block;
        font-size: 40px;
        color: green;
    }

    #show-code {
        display: block;
        font-weight: bold;
        font-size: 17px;
    }
</style>
<div class="modal fade" id="success-order" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center">
                <p class="circle-check"><i class="fa fa-check" aria-hidden="true"></i></p>
                <p class="code-order-show">Mã đơn hàng bạn vừa tạo là: <span id="show-code">sadasdsadasd</span></p>
                <a target="_blank" class="print-order btn btn-primary" href="">In</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Xác Nhận</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="default_declare" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width:80%;margin:auto;margin-top:50px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo "Khai Báo Trạng Thái Đơn Hàng"; ?></h4>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id_default" value="<?php echo $id_default ?>">
                <div class="form-group ">
                    <label for="mass_default">Trạng Thái Của Viettel Post</label>
                    <input type="text" class="form-control" placeholder="Trạng Thái Của Viettel Post" id="status_ghtk"
                           name="status_ghtk" value="<?= set_value('status_ghtk') ?>">
                    <a href="https://docs-apiv2.viettelpost.vn" target="_blank">Tra mã của VTP</a>
                </div>
                <div class="form-group ">
                    <label for="volume_default">Trạng thái chuyển đổi</label>
                    <input type="text" class="form-control" placeholder="Trạng thái chuyển đổi" id="status_change"
                           name="status_change" value="<?= set_value('status_change') ?>">
                </div>

                <div class="form-group ">
                    <label for="volume_default">Tính nợ theo ngày đối soát</label>
                    <select class="form-control" id="status_debit" name="status_debit">
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="0">Không tính</option>
                        <option value="1">Có tính</option>
                    </select>
                </div>

                <div class="form-group ">
                    <label for="volume_default">Tính nợ theo ngày giao hàng</label>
                    <select class="form-control" id="group_debits" name="group_debits">
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="0">Không tính</option>
                        <option value="1">Có tính</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <input type="hidden" id="active" value="0">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"
                        onclick="fnChangeStatus()"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div>

</div>

<div class="modal fade" id="modal-address" tabindex="-1" role="dialog" style="left: 35%;top: 10%;">
    <div class="modal-dialog">
        <div class="modal-content" style="text-align: center; height: 300px; width: 474px">
            <div class="modal-body" style="text-align: center;">
                <p class="circle-check"><i class="fa fa-home" aria-hidden="true"></i></p>
                <p class="code-order-show">Khách Hàng Này Chưa Có Mã Kho ĐVVC Vui Lòng Nhập Mã Kho ĐVVC cho Khách Hàng
                    <span id="title">sadasdsadasd</span></p>
                <input type="hidden" value="" id="shop_code">
                <input type="hidden" value="" id="shop_id">
                <input type="text" value="" id="txt-address-id" class="form-control"
                       placeholder="Nhập mã kho hàng..."><br>
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" id="btnUpdate" class="btn btn-success" onclick="fnUpdateShop()">Cập nhật</button>
            </div>

        </div>
    </div>
</div>

<?php init_tail(); ?>

<script src="/system/assets/plugins/select2/index.js"></script>


<script type="text/javascript">
    $(document).on('click', '#btn-import_excel', function (e) {
        e.preventDefault();
        var form = $('#form-submit-import-excel');
        var file_data = $('#file').prop('files');
        var form_data = new FormData();
        $.each(file_data, function (i, v) {
            form_data.append('file[]', v);
        })
        form_data.append('csrf_token_name', csrfData.hash);
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                if (response.status == '200') {
                    alert('Thêm file thành công');
                    $('#modal_import_excel_customers').modal('hide');
                } else {
                    alert('Thêm file thất bại');
                }
            }
        });
    });


    $('.print-order').click(function () {
        $('#success-order').modal('hide');
    });
    $('.open-modal-default-value').click(function () {
        $('#default_value').modal('show');
    });

    $('.open-modal-declare').click(function () {
        $('#default_declare').modal('show');
    });

    $('#import-customers').click(function () {
        $('#modal_import_excel_customers').modal('show');
    });

    var the_fee_bearer = 0;
    var province = '';
    var district = '';
    var policy_id = '';
    var data_for_calc = '';
    var checkAlert = <?php echo isset($_SESSION['success']) ? 'true' : 'false'?>;
    var success_default = <?php echo isset($_SESSION['success_default']) ? 'true' : 'false'?>;
    var delete_order = <?php echo isset($_SESSION['delete_order']) ? 'true' : 'false'?>;
    var delete_order_error = <?php echo !empty($_SESSION['delete_order_error']) ? $_SESSION['delete_order_error'] : 'false'?>;

    var success_default_region = <?php echo isset($_SESSION['success_default_region']) ? 'true' : 'false'?>;

    // status notification
    var delete_status_order = <?= !empty($_SESSION['delete_status_order']) ? $_SESSION['delete_status_order'] : 'false'?>;

    var w = window.innerWidth;
    var h = window.innerHeight;


    if (w < 768) {
        $('#create_order').empty();
        var heightContainer = h - (40 + 98);
        $('.container-body').css('height', heightContainer);
    } else {
        $('#create_order_mobile').empty();
    }

    $('.xclose').click(function () {
        $('#create_order_mobile').modal('hide');
    });

    // For mobile Change
    //
    $(document).on('click', '[name="ser_set"]', function (e) {
        $('#service').val($(this).val());
    });

    $(document).on('click', '[name="config_set"]', function (e) {
        $('#config').val($(this).val());
    });

    $(document).on('change', '#the_fee_bearer', function (e) {
        the_fee_bearer = $(this).val();
        $('#cod').val('');
        $('#total_money').val('');

        if (the_fee_bearer == 1) {

            if ($('#note').val() != '') {
                $('#note').val($('#note').val());
            } else {

                // $('#note').val('Người GỬI CHỊU PHÍ supership.');
            }
        } else {
            // $('#note').val($('#note').val().replace('Người GỬI CHỊU PHÍ supership.' , ''));
        }
    });


    $(document).on('click', '#customer_local', function (e) {
        var getLocalCustomer = JSON.parse(localStorage.getItem('dataSaveLocalCustomer'));
        if ($(this).prop('checked')) {
            $('#search_customer').val(getLocalCustomer.search_text.trim());
            $('#customer_id').val(getLocalCustomer.customer_id);
            $('#pickup_phone').val(getLocalCustomer.phone);
            $('#pickup_code').val(getLocalCustomer.code);

            $.ajax({
                url: '/system/admin/create_order_viettel/check_customer_policy_exits/' + getLocalCustomer.customer_id,
                method: 'GET',
                success: function (check) {
                    var result = JSON.parse(check);
                    if (result.address_id_vpost !== "" && result.address_id_vpost !== "0") {
                        $("#address_id_hide").val(result.address_id_vpost);
                    } else {
                        $("#create_order").modal('hide');
                        $("#title").html(getLocalCustomer.search_text.trim());
                        $("#shop_code").val(result.customer_shop_code);
                        $("#shop_id").val(getLocalCustomer.customer_id);
                        $("#modal-address").modal();
                        return false;
                    }
                }
            });
            $("#shop").val(getLocalCustomer.search_text.trim());

            var text = getLocalCustomer.search_text.trim();

            var s = 'Đơn hàng của: ' + text.trim().substring(text.trim().length, text.trim().indexOf('-')).replace('-', '').trim() + ` \n `;
            $('#note').val(s + getLocalCustomer.note);
            $('#config').val(getLocalCustomer.config);
            token = getLocalCustomer.token;

            if (getLocalCustomer.special_policy) {
                $('#special').empty();
                $('#special').append(getLocalCustomer.special_policy);
            }

            $('#repo_customer_cover').empty();
            $('#repo_customer_cover').append(getLocalCustomer.repo_customer);
            policy_id = getLocalCustomer.policy_id;
        } else {
            emptyOld();
        }
    });

    function emptyOld() {
        $('#search_customer').val('');
        $('#customer_id').val('');
        $('#pickup_phone').val('');
        $('#pickup_code').val('');
        $('#note').val('');
        $('#config').val('1');
        token = '';
        $('#special').empty();
        $('#repo_customer_cover').empty();
        policy_id = '';
    }

    if (checkAlert) {
        alert_float('success', 'Delete Thành Công');
    }
    if (success_default) {
        alert_float('success', 'Khai báo thành công');
    }
    if (delete_order) {
        alert_float('success', 'Huỷ đơn thành công');

    }
    if (delete_order_error == 1) {
        alert_float('danger', 'Huỷ đơn không thành công');
    } else if (delete_order_error == 2) {
        alert_float('danger', 'Đơn hàng trạng thái không thể hủy');
    }

    if (delete_status_order == 1) {
        alert_float('success', 'Xóa trạng thái thành công');
    } else if (delete_status_order == 2) {
        alert_float('danger', 'Trạng thái không tồn tại');
    } else if (delete_status_order == 3) {
        alert_float('danger', 'Xóa trạng thái thất bại');
    }

    if (success_default_region) {
        alert_float('success', 'Huyện – Tỉnh đã được thêm vào dữ liệu');

    }

    var param = {
        "id_customer": '[name="id_customer"]',
        "date_start_customer": '[name="date_start_customer"]',
        "date_end_customer": '[name="date_end_customer"]',
        "province_filter": '[name="province_filter"]',
        "district_filter": '[name="district_filter"]',
        "staff_filter": '[name="staff_filter"]',
        "enable_filter": '[name="enable_filter"]',
    }

    var data = initDataTable('.table-create_order', window.location.href, [1], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], param, [0, 'desc']);

    data.column(0).visible(false);
    data.column(2).visible(false);
    data.column(5).visible(false);
    data.column(8).visible(false);
    data.column(10).visible(false);
    data.column(11).visible(false);
    data.column(12).visible(false);
    data.column(13).visible(false);
    data.column(14).visible(false);

    data.column(18).visible(false);
    data.column(19).visible(false);
    data.column(20).visible(false);
    data.column(21).visible(false);
    data.column(22).visible(false);
    data.column(23).visible(false);
    data.column(24).visible(false);
    data.column(25).visible(false);
    data.column(26).visible(false);
    data.column(28).visible(false);
    data.column(29).visible(false);


    $('#filter_create_order').click(() => {
        $('[name="enable_filter"]').val('1');
        if ($('.table-create_order').hasClass('dataTable')) {

            $('.table-create_order').DataTable().destroy();
            var data = initDataTableDungbt2('.table-create_order', window.location.href, [1], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], param, [0, 'desc'], 'Tổng tiền trả shop: ');

            data.column(0).visible(false);
            data.column(2).visible(false);
            data.column(5).visible(false);
            data.column(8).visible(false);
            data.column(10).visible(false);
            data.column(11).visible(false);
            data.column(12).visible(false);
            data.column(13).visible(false);
            data.column(14).visible(false);

            data.column(18).visible(false);
            data.column(19).visible(false);
            data.column(20).visible(false);
            data.column(21).visible(false);
            data.column(22).visible(false);
            data.column(23).visible(false);
            data.column(24).visible(false);
            data.column(25).visible(false);
            data.column(26).visible(false);
            data.column(28).visible(false);
            data.column(29).visible(false);
        }
    });

    $('#btn-export-excel').on('click', function () {
        console.log("1234");
        let url = window.location.href + '/export_exel_orders?customer_id=' + $("[name='id_customer']").val() + '&endDate=' + $('#date_end_customer').val() + '&startDate=' + $('#date_start_customer').val();
        window.open(url, '_blank');
        win.focus();
    });
    //
    $('.submit_customer_policy').click(function () {
        $('#create_order_ob').submit();
    });

    function enterViettel(event) {
        if (event.keyCode === 13) {
            $('#create_order_ob').submit();
        }
    }

    var check_disable_super = true;

    $(document).on('change', '#check_disable_super', function () {
        var check = $('#check_disable_super').prop('checked');
        check_disable_super = check;
        $('#total_money').val('');
        $('#cod').val('');
        $('#supership_value').val('');
        if (check) {
            $('#supership_value').attr('disabled', true);
            // $('#total_money').attr('disabled',false);
        } else {
            $('#supership_value').attr('disabled', false);
            // $('#total_money').attr('disabled',true);
        }
    });
    $(document).on('change', '#mass', function () {
        $('#total_money').val('');
        $('#cod').val('');
        $('#supership_value').val('');
    });
    $(document).on('change', '#volume', function () {
        $('#total_money').val('');
        $('#cod').val('');
        $('#supership_value').val('');
    });
    $(document).on('change', '#repo_customer', function () {

        var code = $(this).find(':selected').attr('data-code');
        $('#pickup_code').val(code);
    });
    $(document).on('change', '#province', function () {
        if (policy_id === '') {
            $('#province.selectpicker').selectpicker('val', 'null');
            alert('Chọn Khách Hàng Trước');
            return false;
        }
        var val = JSON.parse($(this).val());
        $('#total_money').val('');
        $('#cod').val('');
        $('#supership_value').val('');
        if (val) {

            $('.disable-view').show();
            $('#loader-repo2').show();
            province = val.name;
            district = '';
            $.ajax({
                url: '/system/admin/create_order_viettel/get_district_by_hd/' + val.code,
                method: 'GET',
                success: function (data) {
                    $('.disable-view').hide();
                    $('#loader-repo2').hide();
                    data = JSON.parse(data);
                    data_districts = data;
                    $('#district').empty();
                    $('#area_hd').empty();
                    $('.load-html').empty();
                    $('#cod').val('');
                    $('#supership_value').val('');
                    $('#total_money').val('');
                    var html = '';
                    html += `<option  value='null'>Chọn Quận Huyện/Thành Phố</option>`;
                    for (var i = 0; i < data.length; i++) {
                        html += `<option  value='${i}'>${data[i].name}</option>`;
                    }
                    $('#district').append(html);
                    $('#district').selectpicker({
                        liveSearch: true
                    });
                    $('#district').selectpicker('refresh');
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }


    });


    $(document).on('change', '#province_filter', function () {

        var val = JSON.parse($(this).val());

        if (val) {

            $('.disable-view').show();
            $('#loader-repo4').show();
            province = val.name;
            district = '';
            $.ajax({
                url: '/system/admin/create_order_ghtk/get_district_by_hd/' + val.code,
                method: 'GET',
                success: function (data) {


                    $('.disable-view').hide();
                    $('#loader-repo4').hide();
                    data = JSON.parse(data);

                    $('#district_filter').empty();


                    var html = '';

                    html += `<option  value='null'>Chọn Quận Huyện/Thành Phố</option>`;
                    for (var i = 0; i < data.length; i++) {
                        html += `<option  value='${data[i].name}'>${data[i].name}</option>`;
                    }
                    $('#district_filter').append(html);
                    $('#district_filter').selectpicker({
                        liveSearch: true
                    });
                    $('#district_filter').selectpicker('refresh');
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }


    });

    $(document).on('change', '#district', function () {
        var index = $(this).val();

        if (index > -1) {
            val = data_districts[index];
            if (policy_id === '') {
                alert('Chọn Khách Hàng Trước');
                return false;
            }
            $('.disable-view').show();
            $('#loader-repo2').show();
            $('#total_money').val('');
            $('#cod').val('');
            $('#supership_value').val('');
            district = val.name;

            $.ajax({
                url: '/system/admin/pick_up_points/get_commune_by_hd/' + val.code,
                method: 'GET',
                success: function (data) {
                    $('.disable-view').hide();
                    $('#loader-repo2').hide();
                    data = JSON.parse(data);

                    $('#area_hd').empty();
                    var html = '<option  value="null">Chọn Phường Xã</option>';
                    for (var i = 0; i < data.length; i++) {
                        html += `<option  value='${JSON.stringify(data[i])}'>${data[i].name}</option>`;
                    }
                    $('#area_hd').append(html);
                    $('#area_hd').selectpicker({
                        liveSearch: true
                    });
                    $('#area_hd').selectpicker('refresh');

                    //Call Api
                    $.ajax({
                        url: `/system/admin/create_order_ghtk/search_region?province=${province}&district=${district}&policy_id=${policy_id}`,
                        method: 'GET',
                        success: function (region) {

                            region = JSON.parse(region);
                            if (region.error === true) {
                                alert('Huyện – Tỉnh này chưa có trong cơ sở dữ liệu thuộc nhóm vùng miền tính phí nào. Vui lòng chọn nhóm vùng miền tính phí.');
                                // open modal add new region
                                $('#create_order').modal('hide');
                                $('#repo_customer_cover').empty();
                                $('#search_customer').val('');
                                $('#customer_id').val('');
                                $('#pickup_phone').val('');

                                $('#create_order_ob input').val('');
                                $('#special').val('');
                                $('#pickup_code').val(0);
                                $('#province').val('');
                                $('#province').selectpicker({
                                    liveSearch: true,
                                    liveSearchNormalize: true
                                });
                                $('#province').selectpicker('refresh');

                                $('#district').empty();
                                $('#area_hd').empty();


                                $('#add_new_region').modal('show');
                                window.open(`/system/admin/create_order_ghtk/add_new_region?province=${province}&district=${district}`, '_blank');


                                $('#city_region').val(province);
                                $('#district_region').val(district);

                                province = '';
                                district = '';
                                policy_id = '';
                                data_for_calc = '';


                            } else {
                                var cost_super_ship;
                                var fee_transport = '';

                                data_for_calc = region.data_region;
                                $('.load-html').empty();
                                $('#cod').val('');
                                $('#supership_value').val('');
                                $('#total_money').val('');

                                $('.region-box input').val(region.id);
                                var tableHTML = `<table class="table">
								    <tbody>
								      <tr>
								        <td style="color:red">Nhóm Vùng Miền : ${region.name_region}</td>
								      </tr>

								    </tbody>
								  </table>`;
                                $('.region-box .load-html').empty();
                                $('.region-box .load-html').append(tableHTML);
                            }


                        }
                    });


                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
    });


    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        return arg !== value;
    }, "Hãy chọn.");

    $.validator.addMethod("valueUnique", function (value, element, arg) {
        var ReturnVal = false;

        return ReturnVal;
    }, "Mã đơn hàng đã tồn tại ");


    $(document).on('change', '#soc', function (e) {
        var value = $('#soc').val();
        $.ajax({
            url: '/system/admin/create_order_ghtk/check_soc/' + value,
            type: 'GET',
            success: function (data) {
                if (JSON.parse(data).length === 0) {
                    return true;

                } else {
                    $('#soc').val('');
                    alert('Mã đơn hàng đã tồn tại');
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    });
    $('#success-order').on('hidden.bs.modal', function () {
        $('.table-create_order').DataTable().ajax.reload();
        window.location.reload();
    })


    function setValidGHTK() {

        $("#create_order_ob").validate({
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
            ignore: [],
            rules: {
                customer_id: {
                    required: true,
                },
                product: {
                    required: true,
                },
                ap: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10
                },
                province: {
                    valueNotEquals: "null"
                },
                f: {
                    required: true,
                },
                a: {
                    required: true,
                    minlength: 5,
                },
                district: {
                    valueNotEquals: "null"
                },
                area_hd: {
                    valueNotEquals: "null"
                },
                mass: {
                    required: true,
                },
                volume: {
                    required: true,
                },
                cod: {
                    required: true,
                },
                total_money: {
                    required: true,
                }
            },
            messages: {},
            submitHandler: function (form) {
                var shop_code = $("#shop").val();
                var address_id_hide = $("#address_id_hide").val();
                if (address_id_hide == "" || address_id_hide == null) {
                    $("#create_order").modal('hide');
                    $("#title").html($("#search_customer").val());
                    $("#shop_code").val(shop_code);
                    $("#shop_id").val($('#create_order_ob #customer_id').val());
                    $("#modal-address").modal();
                    return false;
                }

                var data = {};
                var repo_customer = $('#repo_customer').val().split(",");

                if (parseINT($('#total_money').val()) >= 3000000 && !$('#value_order').val()) {
                    alert('Đơn hàng này phải tính phí bảo hiểm. Vui lòng nhập trị giá. ');
                    return false;
                }

                data.customer_id = $('#create_order_ob #customer_id').val();


                data.pickup_address = repo_customer.slice(0, repo_customer.length - 2).toString().trim();

                data.pickup_district = repo_customer[repo_customer.length - 2].trim();
                data.pickup_province = repo_customer[repo_customer.length - 1].trim();

                data.shop = shop_code;
                data.product = $('#product').val();
                data.product_type = "1";
                data.name = $('#f').val();
                data.phone = $('#ap').val();
                data.sphone = $('#phone_more').val();
                data.address = $('#a').val();
                data.province = province;
                data.district = district;
                data.commune = area_hd;
                data.amount = parseINT($('#total_money').val());
                data.weight = parseINT($('#mass').val());
                data.volume = parseINT($('#volume').val());
                data.soc = $('#soc').val();
                data.note = $('#note').val();
                data.pickup_phone = $('#pickup_phone').val();
                data.service = $('#service').val();
                data.supership_value = parseINT($('#supership_value').val());
                data.config = $('#config').val();
                data.payer = $('#payer').val();
                data.the_fee_bearer = $('#the_fee_bearer').val();
                data.cod = parseINT($('#cod').val());
                if (barterValue) {
                    data.barter = "1";
                } else {
                    data.barter = "0";
                }
                data.value = parseINT($('#value_order').val());
                data.token = token;
                data.token_ghtk = $('#token_ghtk').val();
                data.address_id = address_id_hide;
                data.transport = $('#transport').val();
                data.ghtk = 1;
                data.price = parseINT($("#price").val());
                data.note_private = $("#note_private").val();
                data.region_id = $("#region_id").val();
                data.mass_fake = parseINT($("#mass_fake").val());
                data.voucher = $("#voucher").val();

                $('.overlay-dark').show();
                $('#loader-repo3').show();

                $.ajax({
                    url: '/system/admin/create_order_viettel/add_vpost',
                    data,
                    type: 'POST',
                    success: function (data) {
                        $('.overlay-dark').hide();
                        $('#loader-repo3').hide();
                        $('#create_order').modal('hide');

                        if (JSON.parse(data).success === 'ok') {

                            $('#success-order').modal();

                            $('#show-code').text(JSON.parse(data).code);

                            $('.print-order').attr('href', '/system/admin/create_order_ghtk/print_data_order/' + JSON.parse(data).id + '?print=true&dv=VTP');


                        } else {
                            alert_float('danger', 'Thêm Mới Thất Bại');
                            $('.overlay-dark').hide();
                            $('#loader-repo3').hide();
                        }

                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            }
        });
    }

    // S530192 - 038667346
    function parseINT(a) {
        a = a.replace(/\,/g, ''); // 1125, but a string, so convert it to number
        return a = parseInt(Number(a), 10);
    }

    var typeofCreate = 'sps';
    // $('#create_order').modal('show');
    $('.open-modal-addnew').click(function () {
        var w = window.innerWidth;

        if (w < 768) {
            $('#create_order_mobile').modal('show');
        } else {
            $('#create_order').modal('show');
            $('.modal-body').css('height', h - (44));
        }

        if ($(this).attr('data-type') == 'ghtk') {
            typeofCreate = 'ghtk';
            $('.ghtk-show').show();
            // $('#token_ghtk').val('');
            setValidGHTK();
        } else {
            $('.ghtk-show').hide();
            typeofCreate = 'sps';
            // $('#token_ghtk').val('1');
            setValidSPS();
        }


    });

    var barterValue = false;
    $(document).on('click', '#barter', function (e) {

        barterValue = !barterValue;
    })


    $(document).on('keyup', '#supership_value', function (e) {

        var cod = parseINT($('#cod').val());
        var super_ship = parseINT($('#supership_value').val());
        var price = parseINT($('#price').val());
        var total = Number(cod) + Number(super_ship) + Number(price);
        if (policy_id === '') {
            alert('Hãy chọn khách hàng trước');
            return false;
        } else if (district === '') {
            alert('Hãy chọn huyện trước');
            return false;
        } else if (province === '') {
            alert('Hãy chọn tỉnh trước');
            return false;
        } else if (data_for_calc === '') {
            alert('Xảy ra lỗi đáng tiếc');
            return false;
        } else if ($('#mass').val() === '') {
            alert('Hãy Nhập Khối lượng trước');
            return false;
        } else if ($('#volume').val() === '') {
            alert('Hãy Nhập thể tích trước');
            return false;
        } else {
            if (the_fee_bearer == 0) {
                if (cod) {
                    var total = Number(cod) + Number(super_ship) + Number(price);
                } else {
                    var total = Number(super_ship);
                }
                $('#total_money').val(formatNumber(total));
            }

        }
    });


    $(document).on('keyup', '#value_order', function (e) {
        $('#total_money').val('');
        $('#cod').val('');
    });

    $(document).on('keyup', '#price', function (e) {
        $('#total_money').val('');
        $('#cod').val('');
    });

    function CalcAll() {
        var supership_cost = '';
        var cod;
        if (policy_id === '') {
            alert('Hãy chọn khách hàng trước');

            return false;
        } else if (district === '') {

            alert('Hãy chọn huyện trước');

            return false;
        } else if (province === '') {
            alert('Hãy chọn tỉnh trước');


            return false;
        } else if (data_for_calc === '') {
            alert('Xảy ra lỗi đáng tiếc');
            return false;
        } else if ($('#mass').val() === '') {
            alert('Hãy Nhập Khối lượng trước');
            return false;
        } else if ($('#volume').val() === '') {
            alert('Hãy Nhập thể tích trước');
            return false;
        } else {
            supership_cost = Number(data_for_calc.price_region);
            $('#tvc span').html(formatNumber(Number(data_for_calc.price_region)));

            //Tính khối lượng
            var massInput = parseINT($('#mass').val());
            var massFree = Number(data_for_calc.mass_region);
            var masscalc = (massInput - massFree) / Number(data_for_calc.mass_region_free)
            if (masscalc < 0) {
                masscalc = 0;
            } else {
                masscalc = Math.ceil(masscalc) * Number(data_for_calc.price_over_mass_region);
            }
            $('#tvk span').html(formatNumber(masscalc));


            //Tính Thể Tích
            var volumeInput = parseINT($('#volume').val());
            var volumeFree = Number(data_for_calc.volume_region);
            var volumecalc = (volumeInput - volumeFree) / Number(data_for_calc.volume_region_free)
            if (volumecalc < 0) {
                volumecalc = 0;
            } else {
                volumecalc = Math.ceil(volumecalc) * Number(data_for_calc.price_over_volume_region);
            }
            $('#tvtt span').html(formatNumber(volumecalc));

            if (masscalc > volumecalc) {
                supership_cost += masscalc;
            } else if (masscalc < volumecalc) {
                supership_cost += volumecalc;
            } else {
                supership_cost += masscalc;
            }


            var value_order = 0;
            if ($('#value_order').val() == '') {
                value_order = 0;
            } else {
                value_order = parseINT($('#value_order').val());
            }
            //Tính bảo hiểm
            //don gia bao hiem


            var x = value_order - data_for_calc.amount_of_free_insurance;
            //So sánh trị giá
            if (x <= 0) {
                insurance = 0;
            } else {
                insurance = (value_order * data_for_calc.insurance_price) / 100;
                insurance = Math.round(insurance / 1000) * 1000;
            }


            $('#tbh span').html(formatNumber(insurance));
            $('#tpp span').html($("#price").val());

            supership_cost = supership_cost + insurance + parseINT($("#price").val());


            $('#supership_value').val(formatNumber(supership_cost));


            return supership_cost;
        }
    }


    $(document).on('keyup', '#cod', function (e) {
        // var check_disable_super = $('#check_disable_super').prop('checked',false);
        // console.log(check_disable_super);
        var superShip = 0;
        if (check_disable_super) {
            superShip = CalcAll();
        } else {
            if ($('#supership_value').val() != '') {
                superShip = Number(parseINT($('#supership_value').val()));
            } else {
                superShip = 0;
            }

        }


        var total;

        if (the_fee_bearer == '0') {

            total = superShip + parseINT($('#cod').val());
        } else {
            total = parseINT($('#cod').val());
        }

        $('#total_money').val(formatNumber(total));
    });

    $(document).on('keyup', '#total_money', function (e) {
        if (check_disable_super) {
            var superShip = CalcAll();
            var cod;
            if (the_fee_bearer == '0') {
                cod = parseINT($('#total_money').val()) - superShip;
            } else {
                cod = parseINT($('#total_money').val());
            }
            $('#cod').val(formatNumber(cod));
        } else {
            if (the_fee_bearer == 0) {

                $('#cod').val(formatNumber(Number(parseINT($('#total_money').val())) - Number(parseINT($('#supership_value').val()))));
            } else {

                if ($('#total_money').val() != '') {
                    $('#cod').val($('#total_money').val());
                } else {
                    $('#cod').val('0');

                }


            }
        }


    });


    $(document).on('click', '.add-more-phone', function (e) {
        $('.phone-more').toggle();
    });


    var token;

    $(document).on('click', '.search-item li', function (e) {
        e.stopPropagation();
        emptyOld();
        $('#customer_local').prop('checked', false);
        var dataSaveLocal = {};

        token = $(this).attr('data-token');
        phone = $(this).attr('data-phone');
        var id = $(this).attr('data-id');
        var text = $(this).text();
        dataSaveLocal.token = token;
        dataSaveLocal.phone = phone;
        dataSaveLocal.note = $(this).attr('data-note');
        dataSaveLocal.customer_id = id;
        dataSaveLocal.search_text = text;
        dataSaveLocal.config = $(this).attr('data-config');

        $.ajax({
            url: '/system/admin/create_order_viettel/check_customer_policy_exits/' + id,
            method: 'GET',
            success: function (check) {
                $('#special').empty();
                if (check === "custommer_no") {
                    alert("Khách Hàng Này Chưa Có Chính Sách");
                    window.open('/system/admin/customer_policy/', '_blank');
                } else {
                    policy_id = JSON.parse(check).id;
                    dataSaveLocal.policy_id = policy_id;
                    if (JSON.parse(check).special_policy !== '') {
                        var html = `<label>Chhính Sách Đặc Biệt</label><div style="height: 100px;overflow: auto;font-weight:bold;color:red" class="form-control">${JSON.parse(check).special_policy}</div>`;
                        dataSaveLocal.special_policy = html;

                        $('#special').append(html);
                    }

                    var s = 'Đơn hàng của: ' + text.trim().substring(text.trim().length, text.trim().indexOf('-')).replace('-', '').trim() + ` \n `;
                    if (JSON.parse(check).note_default != '') {
                        $('#note').val(s + JSON.parse(check).note_default);
                    } else {
                        $('#note').val(s);
                    }
                    $('#config').val(JSON.parse(check).config);

                    if (JSON.parse(check).address_id_vpost !== '' && JSON.parse(check).address_id_vpost !== '0') {
                        $("#address_id_hide").val(JSON.parse(check).address_id_vpost);
                    } else {
                        $("#create_order").modal('hide');
                        $("#title").html(JSON.parse(check).customer_shop_code);
                        $("#shop_code").val(JSON.parse(check).customer_shop_code);
                        $("#shop_id").val(JSON.parse(check).customer_id);
                        $("#modal-address").modal();
                        return false;
                    }
                    dataSaveLocal.address_id_vpost = JSON.parse(check).address_id_vpost;
                    if (JSON.parse(check).customer_shop_code !== '') {
                        $("#shop").val(JSON.parse(check).customer_shop_code);
                    }

                    $('#search_customer').val(text.trim());
                    $('.search-item').hide();
                    $('#customer_id').val(id);
                    $('#pickup_phone').val(phone);
                    $('.disable-view').show();

                    $.ajax({
                        url: '/system/admin/pick_up_points/curlGetRepo',
                        method: 'POST',
                        data: {token},
                        success: function (data) {
                            $('.disable-view').hide();

                            data = JSON.parse(data);


                            $('#pickup_code').val(data[0].code);
                            dataSaveLocal.code = data[0].code;
                            dataSaveLocal.address_id = data[0].address_id;
                            var html = '<label for="repo_customer">Chọn Kho:</label><select class="form-control" id="repo_customer" name="repo_customer">';

                            for (var i = 0; i < data.length; i++) {
                                html += `<option data-code="${data[i].code}" value="${data[i].formatted_address}">${data[i].formatted_address}</option>`;
                            }
                            html += '</select>';

                            dataSaveLocal.repo_customer = html;
                            $('#repo_customer_cover').empty();

                            localStorage.setItem('dataSaveLocalCustomer', JSON.stringify(dataSaveLocal));
                            $('#repo_customer_cover').append(html);


                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                }
            }
        })


    });


    $(document).on('focus', '#search_customer', function () {
        $('.search-item').show();
    });
    $(document).on('focusout', '#search_customer', function (e) {
        setTimeout(function () {
            $('.search-item').hide();
        }, 400);

    });

    function remove_unicode(str) {
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-");

        str = str.replace(/-+-/g, "-"); //thay thế 2- thành 1-
        str = str.replace(/^\-+|\-+$/g, "");

        return str;
    }

    function autoCompleteInfoUser(id) {
        if ($(id).val()) {
            $('.disable-view').show();
            $('#loader-repo2').show();
            $.ajax({
                url: '/system/user/get_info_user_by_phone',
                method: 'POST',
                data: {
                    phone: $(id).val(),
                },
                success: function (data) {
                    $('.disable-view').hide();
                    $('#loader-repo2').hide();
                    if (data) {
                        data = JSON.parse(data);
                        if (data.status == '200') {
                            let user = data.user;
                            let districts = data.districts;
                            let areas = data.areas;

                            $('#f').val(user.receiver);
                            $('#a').val(user.address);

                            // thành phố
                            province = user.city;
                            district = '';
                            $('#province').val(JSON.stringify({'name': user.city}));
                            $('.filter-option-inner-inner').html(user.city);

                            $('.disable-view').hide();
                            $('#loader-repo4').hide();

                            // Quận huyện
                            $('#district').empty();
                            var html = '';
                            html += `<option  value='null'>Chọn Quận Huyện/Thành Phố</option>`;
                            for (var i = 0; i < districts.length; i++) {
                                html += `<option   value='${JSON.stringify(districts[i])}'>${districts[i].name}</option>`;
                            }
                            $('#district').append(html);
                            $('#district').selectpicker({
                                liveSearch: true
                            });
                            $('#district').selectpicker('refresh');
                            district = user.district;
                            $('#district').val(JSON.stringify({'name': user.district}));

                            $('#district').parent().find('.filter-option-inner-inner').html(user.district);

                            // phường xã
                            $('#area_hd').empty();
                            var html = '<option  value="null">Chọn Phường Xã</option>';
                            for (var i = 0; i < areas.length; i++) {
                                html += `<option  value='${JSON.stringify(areas[i])}'>${areas[i].name}</option>`;
                            }
                            $('#area_hd').append(html);
                            $('#area_hd').selectpicker({
                                liveSearch: true
                            });
                            $('#area_hd').selectpicker('refresh');
                            $('#area_hd').parent().find('.filter-option-inner-inner').html(user.ward);
                            $('#area_hd').val(user.ward);
                            area_hd = user.ward;

                            $.ajax({
                                url: `/system/admin/create_order_viettel/search_region?province=${province}&district=${district}&policy_id=${policy_id}`,
                                method: 'GET',
                                success: function (region) {

                                    region = JSON.parse(region);
                                    if (region.error === true) {
                                        alert('Huyện – Tỉnh này chưa có trong cơ sở dữ liệu thuộc nhóm vùng miền tính phí nào. Vui lòng chọn nhóm vùng miền tính phí.');
                                        // open modal add new region
                                        $('#create_order').modal('hide');
                                        $('#repo_customer_cover').empty();
                                        $('#search_customer').val('');
                                        $('#customer_id').val('');
                                        $('#pickup_phone').val('');

                                        $('#create_order_ob input').val('');
                                        $('#special').val('');
                                        $('#pickup_code').val(0);
                                        $('#province').val('');
                                        $('#province').selectpicker({
                                            liveSearch: true,
                                            liveSearchNormalize: true
                                        });
                                        $('#province').selectpicker('refresh');

                                        $('#district').empty();
                                        $('#area_hd').empty();


                                        $('#add_new_region').modal('show');
                                        window.open(`/system/admin/create_order_ghtk/add_new_region?province=${province}&district=${district}`, '_blank');


                                        $('#city_region').val(province);
                                        $('#district_region').val(district);

                                        province = '';
                                        district = '';
                                        policy_id = '';
                                        data_for_calc = '';


                                    } else {
                                        var cost_super_ship;
                                        var fee_transport = '';

                                        data_for_calc = region.data_region;
                                        $('.load-html').empty();
                                        $('#cod').val('');
                                        $('#supership_value').val('');
                                        $('#total_money').val('');

                                        $('.region-box input').val(region.id);
                                        var tableHTML = `<table class="table">
								    <tbody>
								      <tr>
								        <td style="color:red">Nhóm Vùng Miền : ${region.name_region}</td>
								      </tr>

								    </tbody>
								  </table>`;
                                        $('.region-box .load-html').empty();
                                        $('.region-box .load-html').append(tableHTML);
                                    }
                                }
                            });
                        }
                    }
                }
            });
        }
    }

    $(document).on('keyup', '#search_customer', function () {
        $('.search-item').show();
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;

        // Loop through the comment list
        $(".search-item li").each(function () {
            // If the list item does not contain the text phrase fade it out
            if (remove_unicode($(this).text().trim()).search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();
                // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }
        });

        // Update the count
        var numberItems = count;
        $("#filter-count").text("Number of Filter = " + count);
    });

    $(document).on('click', '.explain', function () {

        $(this).hide();
        $('.more-config').show();
        $('.collapse').show();
    });

    $(document).on('click', '.collapse', function () {
        $(this).hide();
        $('.more-config').hide();
        $('.explain').show();
    });

    $(document).on('click', '.delete-reminder-custom', function () {
        var c = confirm('Bạn có muốn huỷ đơn hàng');

        if (c === false) {
            return false;
        }
    });
    let area_hd;
    $(document).on('change', '#area_hd', function () {
        area_hd = JSON.parse($(this).val()).name;
    });

    function fnChangeStatus() {
        var status_ghtk = $("#status_ghtk").val();
        var status_change = $("#status_change").val();
        var active = $("#active").val();

        var status_debit = $("#status_debit").find(':selected').val();
        var group_debits = $("#group_debits").find(':selected').val();

        if (status_ghtk == "") {
            alert('Bạn chưa nhập trạng thái của Viettel Post!');
            $("#status_ghtk").focus();
            return false;
        }

        if (status_change == "") {
            alert('Bạn chưa nhập trạng thái chuyển đổi!');
            $("#status_change").focus();
            return false;
        }
        $('.search-item').show();
        $.ajax({
            url: '<?= base_url('add_status')?>',
            method: "POST",
            data: {
                status_ghtk: status_ghtk,
                status_change: status_change,
                status_debit: status_debit,
                group_debits: group_debits,
                active: active,
                dvvc: 'VTP'
            },
            success: function (data) {
                var result = JSON.parse(data);
                $('.search-item').hide();
                if (result.status == true && result.error == '') {
                    alert(result.message);
                    $("#default_declare").modal('hide');
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000);
                }
            }
        });

    }

    function fnEdit(id) {
        $.ajax({
            url: '<?= base_url('api/getStatus')?>',
            data: {id: id},
            method: "post",
            success: function (data) {
                var result = JSON.parse(data);
                if (result.status == true && result.error == "") {
                    $("#status_ghtk").val(result.info.status_ghtk);
                    $("#status_change").val(result.info.status_change);
                    $("#status_debit").val(result.info.status_debit);
                    $("#active").val(result.info.id);
                    $("#group_debits").val(result.info.group_debits);
                    $("#default_declare").modal('show');
                } else {
                    alert('Xảy ra lỗi.');
                }
            }
        });
    }

    function fnUpdateShop() {
        var id = $("#shop_id").val();
        var code = $("#shop_code").val();
        var address_id = $("#txt-address-id").val();

        $.ajax({
            url: '<?= base_url('api/updateShop')?>',
            data: {id: id, code: code, address_id: address_id},
            method: "POST",
            beforeSend: function () {
                $("#btnUpdate").html('<i class="fa fa-spin fa-refresh"></i>');
            },
            success: function (data) {
                $("#btnUpdate").html('CẬP NHẬT');
                if (data == 1) {
                    alert_float('success', 'Cập nhật thành công');
                } else if (data == 2) {
                    alert_float('danger', 'Không có thông tin liên quan.');
                } else {
                    alert_float('danger', 'Cập nhật thất bại');
                }

                $("#modal-address").modal('hide');
            }
        });
    }

    function calc() {
        if (document.getElementById('barter').checked) {
            document.getElementById('note').value = document.getElementById('note').value + "\nCó Hàng Đổi Trả. Ship Lấy Hàng Về Giúp Shop"
        } else {
            textOld = document.getElementById('note').value
            document.getElementById('note').value = textOld.replace("\nCó Hàng Đổi Trả. Ship Lấy Hàng Về Giúp Shop", "");

        }
    }

</script>

</body>
</html>
