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
$mass_fake_ghtk = ($default_data) ? number_format($default_data->mass_fake_ghtk) : '';
$mass_fake_vpost = ($default_data) ? number_format($default_data->mass_fake_vpost) : '';
$mass_fake_vnc = ($default_data) ? number_format($default_data->mass_fake_vnc) : '';

$volume = ($default_data) ? number_format($default_data->volume_default) : '';
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
                                    <p class="total-append"></p>
                                    <input type="hidden" name="enable_filter" value="0">
                                </div>
                                <div class="clearfix"></div>

                            </div>

                        </div>
                        <?php render_datatable(array(
                            _l('<input type="checkbox" id="cbTotal" onclick="fnCheckAll()" value="0"/>'),
                            _l('Ngày Tạo'), //ok
                            _l('product'),
                            _l('Mã yêu cầu'), //ok
                            _l('Khách Hàng'),//ok
                            _l('Khách Hàng'),
                            _l('Dịch Vụ Super Ship'),//ok
                            _l('Tiền Hàng'),
                            _l('Thu hộ'),
                            _l('Họ và Tên Người Nhận'),
                            _l('SDT'),
                            _l('SDT Phụ'),
                            _l('Địa Chỉ Khách hàng'),
                            _l('Phường Xã'),
                            _l('Quận Huyện/Thành Phố'),//ok
                            _l('Tỉnh'),//ok
                            _l('Cân Nặng'),//ok
                            _l('Thể Tích'),//ok
                            _l('Mã Đơn Hàng'),
                            _l('Ghi Chú'),
                            _l('Dịch Vụ'),
                            _l('Cấu Hình'),
                            _l('Người Trả Phí'),
                            _l('Đổi/Lấy Hàng Về'),
                            _l('Giá Trị Đơn Hàng'),
                            _l('Người tạo'),
                            _l('Người tạo'),
                            _l('status'),
                            _l('Người Chịu Phí'),
                            _l('options'),
                        ), 'create_order'); ?>
                        <button class="btn btn-danger" onclick="fnConfirm_Orders(0)">Hủy</button>
                        <button class="btn btn-success" onclick="fnConfirm_Orders(1)">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-choose-dvvc" tabindex="-1" role="dialog" style="left: 35%;top: 10%;">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content" class="modal-content" style="text-align: center; height: 450px; width: 474px">
            <div class="modal-body" style="text-align: center;">
                <p class="circle-check"><i class="fa fa-home" aria-hidden="true"></i></p>
                <p class="code-order-show" id="titleCode"></p>
                <select name="dvvc" id="dvvc" class="form-control"
                        onchange="fnChooseDVVC(<?= str_replace(',', '', $mass_fake) ?>, <?= str_replace(',', '', $mass_fake_ghtk) ?>,<?= str_replace(',', '', $mass_fake_vpost) ?>,<?= str_replace(',','',$mass_fake_vnc)?>)">
                    <option value="">-- Chọn đơn vị vận chuyển --</option>
                    <option value="SPS">SPS</option>
                    <option value="GHTK">GHTK</option>
                    <option value="VTP">Viettel Post</option>
					<option value="VNC">VNC Post</option>
					<option value="NB">NB</option>
                </select>
                <br>
                <div id="boxmass">
                    <lable>Khối Lượng Thực</lable>
                    <input type="text" class="form-control" id="mass" name="mass" value="100">
                </div>
                <br>
                <lable>Khối Lượng Ảo</lable>
                <input type="text" class="form-control" id="mass_fake" name="mass_fake" value="">
                <br>
                <div id="boxtranspot"></div>
				<div id="boxwarehouse"></div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" id="btnUpdate" data-id="" data-code="" data-transport="" class="btn btn-success"
                        onclick="fnCreateOrder()">Tạo mới
                </button>
            </div>

        </div>
    </div>
</div>


<style>
    /*.table-create_order thead tr th:nth-child(1) {*/
    /*    width: 10%;*/
    /*}*/

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
                <p class="code-order-show" id="titleOrder">Mã đơn hàng bạn vừa tạo là: <span
                            id="show-code">sadasdsadasd</span></p>
                <a class="print-order btn btn-primary" target="_blank" href="">In</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Xác Nhận</button>
            </div>

        </div>
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

    $('#success-order').on('hidden.bs.modal', function () {
        $('.table-create_order').DataTable().ajax.reload();
        $('#modal-choose-dvvc').modal('hide');
        window.location.reload();
    });

    $('.print-order').click(function () {
        $('#success-order').modal('hide');
    });
    $('.open-modal-default-value').click(function () {
        $('#default_value').modal('show');
    });
    $('#import-customers').click(function () {
        $('#modal_import_excel_customers').modal('show');
    });
    let data_districts;
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


    var w = window.innerWidth;
    var h = window.innerHeight;

    // Confirm Order
    var info_order_error = <?php echo isset($_SESSION['info_order_error']) ? 'true' : 'false'?>;
    var update_order_error = <?php echo isset($_SESSION['update_order_error']) ? 'true' : 'false'?>;
    var update_order_success = <?php echo isset($_SESSION['update_order_success']) ? 'true' : 'false'?>;

    if (info_order_error)
        alert_float('danger', 'Đơn hàng không tồn tại');
    if (update_order_error)
        alert_float('danger', 'Hủy đơn hàng thất bại');
    if (update_order_success)
        alert_float('success', 'Hủy đơn hàng thành công');

    // End confirm order

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
            $('#note').val(getLocalCustomer.note);
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

    data.column(2).visible(false);
    data.column(4).visible(false);
    data.column(6).visible(false);
    data.column(7).visible(false);
    data.column(9).visible(false);
    data.column(13).visible(false);
    data.column(11).visible(false);
    data.column(12).visible(false);

    data.column(17).visible(false);
    data.column(18).visible(false);
    data.column(19).visible(false);
    data.column(20).visible(false);
    data.column(21).visible(false);
    data.column(22).visible(false);
    data.column(23).visible(false);
    data.column(24).visible(false);
    data.column(25).visible(false);
    data.column(26).visible(false);
    data.column(27).visible(false);
    data.column(28).visible(false);


    $('#filter_create_order').click(() => {
        $('[name="enable_filter"]').val('1');
        if ($('.table-create_order').hasClass('dataTable')) {

            $('.table-create_order').DataTable().destroy();
            var data = initDataTableDungbt2('.table-create_order', window.location.href, [1], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], param, [0, 'desc'], 'Tổng tiền trả shop: ');

            data.column(2).visible(false);
            data.column(4).visible(false);
            data.column(6).visible(false);
            data.column(7).visible(false);
            data.column(9).visible(false);
            data.column(13).visible(false);
            data.column(11).visible(false);
            data.column(12).visible(false);

            data.column(17).visible(false);
            data.column(18).visible(false);
            data.column(19).visible(false);
            data.column(20).visible(false);
            data.column(21).visible(false);
            data.column(22).visible(false);
            data.column(23).visible(false);
            data.column(24).visible(false);
            data.column(25).visible(false);
            data.column(26).visible(false);
            data.column(27).visible(false);
            data.column(28).visible(false);
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
                url: '/system/admin/create_order/get_district_by_hd/' + val.code,
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
                url: '/system/admin/create_order/get_district_by_hd/' + val.code,
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

        console.log("crash");
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
                        url: `/system/admin/create_order/search_region?province=${province}&district=${district}&policy_id=${policy_id}`,
                        method: 'GET',
                        success: function (region) {

                            region = JSON.parse(region);
                            if (region.error === true) {
                                r = confirm("Huyện – Tỉnh này chưa có trong cơ sở dữ liệu thuộc nhóm vùng miền tính phí nào. Vui lòng chọn nhóm vùng miền tính phí.");
                                if (r == true) {
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
                                    window.open(`/system/admin/create_order/add_new_region?province=${province}&district=${district}`, '_blank');


                                    $('#city_region').val(province);
                                    $('#district_region').val(district);

                                    province = '';
                                    district = '';
                                    policy_id = '';
                                    data_for_calc = '';
                                }
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
            url: '/system/admin/create_order/check_soc/' + value,
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

    function setValidSPS() {


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
                $('.overlay-dark').show();
                $('#loader-repo3').show();


                $.ajax({
                    url: '/system/admin/create_order/add',
                    data,
                    type: 'POST',
                    success: function (data) {
                        $('.overlay-dark').hide();
                        $('#loader-repo3').hide();
                        $('#create_order').modal('hide');

                        if (JSON.parse(data).success === 'ok') {

                            $('#success-order').modal();
                            $('#show-code').text(JSON.parse(data).code);
                            $('.print-order').attr('href', `https://mysupership.com/orders/print?code=${JSON.parse(data).code}&size=S9`);

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
                data.transport = $('#transport').val();
                data.ghtk = 1;
                $('.overlay-dark').show();
                $('#loader-repo3').show();


                $.ajax({
                    url: '/system/admin/create_order/add_ghtk',
                    data,
                    type: 'POST',
                    success: function (data) {
                        $('.overlay-dark').hide();
                        $('#loader-repo3').hide();
                        $('#create_order').modal('hide');

                        if (JSON.parse(data).success === 'ok') {

                            $('#success-order').modal();
                            $('#show-code').text(JSON.parse(data).code);
                            $('.print-order').attr('href', `https://mysupership.com/orders/print?code=${JSON.parse(data).code}&size=S9`);

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
            $('#token_ghtk').val('');
            setValidGHTK();
        } else {
            $('.ghtk-show').hide();
            typeofCreate = 'sps';
            $('#token_ghtk').val('1');
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
        var total = Number(cod) + Number(super_ship);
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
                    var total = Number(cod) + Number(super_ship);
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


    // $(document).on('keyup', '#cod', function(e){
    //
    //
    // 	var check = $('#check_disable_super').prop('checked');
    //
    //     var value_order = $('#value_order').val();
    //   if (value_order === '') {
    //     value_order = 0;
    //   }else {
    //     value_order = parseINT(value_order);
    //   }
    //   $('#supership_value').val('');
    //
    // 	if (policy_id === '') {
    // 		alert('Hãy chọn khách hàng trước');
    // 		return false;
    // 	}else if(district === ''){
    // 		alert('Hãy chọn huyện trước');
    // 		return false;
    // 	}else if(province === ''){
    // 		alert('Hãy chọn tỉnh trước');
    // 		return false;
    // 	}else if(data_for_calc === ''){
    // 		alert('Xảy ra lỗi đáng tiếc');
    // 		return false;
    // 	}else if($('#mass').val() === ''){
    // 		alert('Hãy Nhập Khối lượng trước');
    // 		return false;
    // 	}
    // 	else if($('#volume').val() === ''){
    // 		alert('Hãy Nhập thể tích trước');
    // 		return false;
    // 	}
    // 	else {
    //     if (the_fee_bearer != '1' ) {
    //       if (check) {
    // 				var supership_cost = '';
    //
    // 				supership_cost = Number(data_for_calc.price_region);
    // 				$('#tvc span').html(formatNumber(Number(data_for_calc.price_region)));
    //
    // 				//Tính khối lượng
    // 				var massInput = parseINT($('#mass').val());
    // 				var massFree = Number(data_for_calc.mass_region);
    //
    //
    // 				var masscalc = (massInput - massFree)/Number(data_for_calc.mass_region_free);
    // 				if(masscalc <= 0) {
    // 					masscalc = 0;
    // 				}else {
    // 					 masscalc = Math.ceil(masscalc)*Number(data_for_calc.price_over_mass_region);
    // 				}
    // 				$('#tvk span').html(formatNumber(masscalc));
    //
    //
    // 				//Tính Thể Tích
    // 				var volumeInput = parseINT($('#volume').val());
    // 				var volumeFree = Number(data_for_calc.volume_region);
    // 				var volumecalc = (volumeInput - volumeFree)/Number(data_for_calc.volume_region_free);
    //
    //
    // 				if(volumecalc <= 0) {
    // 					volumecalc = 0;
    // 				}else {
    // 					 volumecalc = Math.ceil(volumecalc)*Number(data_for_calc.price_over_volume_region);
    // 				}
    //
    // 				$('#tvtt span').html(formatNumber(volumecalc));
    //
    //
    // 				//Phí bảo hiểm
    //
    // 				if (volumecalc > masscalc) {
    // 					var totalCalc = supership_cost + volumecalc + parseINT($('#cod').val());
    // 				}else if(volumecalc < masscalc){
    // 					var totalCalc = supership_cost  + parseINT($('#cod').val()) + masscalc;
    // 				}else {
    // 					var totalCalc = supership_cost  + parseINT($('#cod').val()) + masscalc;
    // 				}
    //
    //
    //
    // 				//So sánh trị giá
    // 				var value_order = 0;
    // 				if ($('#value_order').val() == '') {
    // 					 value_order = 0;
    // 				}else {
    // 					value_order = parseINT($('#value_order').val());
    // 				}
    //
    // 				//So sánh trị giá
    //
    // 				if(totalCalc > value_order){
    //
    // 					if (totalCalc > Number(data_for_calc.amount_of_free_insurance)) {
    //
    // 						var insurance = (totalCalc/100)*Number(data_for_calc.insurance_price);
    // 							insurance = Math.round(insurance / 1000) * 1000;
    //
    // 						supership_cost = (totalCalc + insurance) - parseINT($('#cod').val());
    // 						var total = supership_cost + parseINT($('#cod').val());
    //
    //
    // 						var insuranceCheck = (total/100)*Number(data_for_calc.insurance_price);
    // 						insuranceCheck = Math.round(insuranceCheck / 1000) * 1000;
    //
    //
    // 						while (insurance != insuranceCheck) {
    //
    // 							total = total + (insuranceCheck - insurance);
    // 							insurance = insuranceCheck;
    // 						}
    // 						supership_cost = total - parseINT($('#cod').val());
    // 					}else {
    // 						supership_cost = (totalCalc) - parseINT($('#cod').val());
    // 						var total = totalCalc;
    // 						insurance = 0;
    // 					}
    //
    //
    //
    //
    //
    // 				}
    // 				else {
    // 					if (value_order > Number(data_for_calc.amount_of_free_insurance)) {
    // 						var insurance = (value_order/100)*Number(data_for_calc.insurance_price);
    // 						insurance = Math.round(insurance / 1000) * 1000;
    // 					}else {
    // 						insurance = 0;
    // 					}
    //
    // 					supership_cost = (totalCalc + insurance) - parseINT($('#cod').val());
    // 					var total = supership_cost + parseINT($('#cod').val());
    // 				}
    //
    // 				$('#tbh span').html(formatNumber(insurance));
    // 				$('#supership_value').val(formatNumber(supership_cost));
    // 				$('#total_money').val(formatNumber(total));
    // 				$('.price-footer.total_set i').html(formatNumber(total));
    // 				$('.price-footer.cod_set i').html(formatNumber(total));
    // 			}
    // 			else {
    // 				var cod = parseINT($('#cod').val());
    // 				var super_ship = parseINT($('#supership_value').val());
    // 				if (super_ship) {
    // 					var total = Number(cod) + Number(super_ship);
    // 				}else {
    // 					var total = Number(cod);
    // 				}
    //
    // 				$('#total_money').val(formatNumber(total));
    // 			}
    //     }else {
    //       // formatNumber
    //       $('#total_money').val('');
    //       $('#total_money').val($('#cod').val());
    //
    //
    //
    //     }
    //
    //
    //
    // 	}
    //
    //
    //
    //
    //
    //
    // });
    //
    //
    //
    // $(document).on('keyup', '#total_money', function(e){
    //
    // 	var total = parseINT($('#total_money').val());
    // 	$('.price-footer.total_set i').html(formatNumber(total));
    // 	var supership_cost = '';
    // 	var cod;
    // 	if (policy_id === '') {
    // 		alert('Hãy chọn khách hàng trước');
    //
    // 		return false;
    // 	}else if(district === ''){
    //
    // 		alert('Hãy chọn huyện trước');
    //
    // 		return false;
    // 	}else if(province === ''){
    // 		alert('Hãy chọn tỉnh trước');
    //
    //
    // 		return false;
    // 	}else if(data_for_calc === ''){
    // 		alert('Xảy ra lỗi đáng tiếc');
    // 		return false;
    // 	}else if($('#mass').val() === ''){
    // 		alert('Hãy Nhập Khối lượng trước');
    // 		return false;
    // 	}
    // 	else if($('#volume').val() === ''){
    // 		alert('Hãy Nhập thể tích trước');
    // 		return false;
    // 	}
    // 	else {
    //
    //     if (the_fee_bearer == '0') {
    //       supership_cost = Number(data_for_calc.price_region);
    // 			$('#tvc span').html(formatNumber(Number(data_for_calc.price_region)));
    //
    // 			//Tính khối lượng
    // 			var massInput = parseINT($('#mass').val());
    // 			var massFree = Number(data_for_calc.mass_region);
    // 			var masscalc = (massInput - massFree)/Number(data_for_calc.mass_region_free)
    // 			if(masscalc < 0) {
    // 				masscalc = 0;
    // 			}else {
    // 				 masscalc = Math.ceil(masscalc)*Number(data_for_calc.price_over_mass_region);
    // 			}
    // 			$('#tvk span').html(formatNumber(masscalc));
    //
    //
    //
    // 			//Tính Thể Tích
    // 			var volumeInput = parseINT($('#volume').val());
    // 			var volumeFree = Number(data_for_calc.volume_region);
    // 			var volumecalc = (volumeInput - volumeFree)/Number(data_for_calc.volume_region_free)
    // 			if(volumecalc < 0) {
    // 				volumecalc = 0;
    // 			}else {
    // 				 volumecalc = Math.ceil(volumecalc)*Number(data_for_calc.price_over_volume_region);
    // 			}
    // 			$('#tvtt span').html(formatNumber(volumecalc));
    //
    // 			if (masscalc > volumecalc) {
    // 				supership_cost += masscalc;
    // 			}else if(masscalc < volumecalc) {
    // 				supership_cost += volumecalc;
    // 			}else {
    // 				supership_cost += masscalc;
    // 			}
    //
    //
    //
    // 			var value_order = 0;
    // 			if ($('#value_order').val() == '') {
    // 				 value_order = 0;
    // 			}else {
    // 				value_order = parseINT($('#value_order').val());
    // 			}
    // 			//Tính bảo hiểm
    // 			//So sánh trị giá
    // 			if (total > value_order) {
    // 				if (total > data_for_calc.amount_of_free_insurance) {
    // 					var insurance = (total/100)*Number(data_for_calc.insurance_price);
    // 					 insurance = Math.round(insurance / 1000) * 1000;
    // 				}else {
    // 					insurance = 0;
    // 				}
    // 			}else {
    // 				if (value_order > data_for_calc.amount_of_free_insurance) {
    // 					var insurance = (value_order/100)*Number(data_for_calc.insurance_price);
    // 					 insurance = Math.round(insurance / 1000) * 1000;
    // 				}else {
    // 					insurance = 0;
    // 				}
    // 			}
    //
    //
    // 			$('#tbh span').html(formatNumber(insurance));
    //
    //
    // 			supership_cost = supership_cost + insurance;
    //
    // 			cod  = total - supership_cost;
    // 			$('#supership_value').val(formatNumber(supership_cost));
    // 			$('#cod').val(formatNumber(cod));
    // 			$('.price-footer.cod_set i').html(formatNumber(cod));
    //     }else {
    //
    //
    //       $('#cod').val('');
    //       $('#cod').val($('#total_money').val());
    //
    //     }
    //
    //
    //
    // 	}
    //
    //
    // });

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


            supership_cost = supership_cost + insurance;


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
        ;
        $.ajax({
            url: '/system/admin/create_order/check_customer_policy_exits/' + id,
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


                    if (JSON.parse(check).note_default != '') {
                        $('#note').val(JSON.parse(check).note_default);
                    }
                    $('#config').val(JSON.parse(check).config);


                    $('#search_customer').val(text.trim());
                    $('.search-item').hide();
                    $('#customer_id').val(id);
                    $('#pickup_phone').val(phone);
                    $('.disable-view').show();
                    $('#loader-repo').show();

                    $.ajax({
                        url: '/system/admin/pick_up_points/curlGetRepo',
                        method: 'POST',
                        data: {token},
                        success: function (data) {
                            $('.disable-view').hide();
                            $('#loader-repo').hide();

                            data = JSON.parse(data);


                            $('#pickup_code').val(data[0].code);
                            dataSaveLocal.code = data[0].code;
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
                                html += `<option   value='${i}'>${districts[i].name}</option>`;
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
                                url: `/system/admin/create_order/search_region?province=${province}&district=${district}&policy_id=${policy_id}`,
                                method: 'GET',
                                success: function (region) {

                                    region = JSON.parse(region);
                                    if (region.error === true) {
                                        r = confirm("Huyện – Tỉnh này chưa có trong cơ sở dữ liệu thuộc nhóm vùng miền tính phí nào. Vui lòng chọn nhóm vùng miền tính phí.");
                                        if (r == true) {
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
                                            window.open(`/system/admin/create_order/add_new_region?province=${province}&district=${district}`, '_blank');


                                            $('#city_region').val(province);
                                            $('#district_region').val(district);

                                            province = '';
                                            district = '';
                                            policy_id = '';
                                            data_for_calc = '';
                                        }
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


    function fnConfirm_Order(id, code, mass, tranport, idCustomer) {
        $("#titleCode").html('Chọn đơn vị vận chuyển cho đơn hàng có mã <b>' + code + '</b>');
        $("#mass").val(mass).select();

        $("#btnUpdate").attr('data-id', id);
        $("#btnUpdate").attr('data-code', code);
        $("#btnUpdate").attr('data-transport', tranport);
        $("#btnUpdate").attr('data-customer', idCustomer);
        $("#btnUpdate").attr('onclick', 'fnCreateOrder()');
        $("#boxmass").show();
        $("#modal-choose-dvvc").modal('show');
    }


    function fnCreateOrder() {
        var transpot = 0;
        var dvvc = $("#dvvc").find(":selected").val();
        var warehouse = $("#warehouse").find(":selected").val();
        var id = $("#btnUpdate").attr('data-id');
        var code = $("#btnUpdate").attr('data-code');
        var mass = $("#mass").val();
        var mass_fake = $("#mass_fake").val();

        if (dvvc == "") {
            alert('Bạn cần chọn đơn vị vận chuyển.');
            $("#dvvc").focus();
            return false;
        }

        if (dvvc === 'GHTK') {
            transpot = $("#transspot").find(":selected").val();
        }

        $.ajax({
            url: '<?= base_url('api/comfirm_order')?>',
            data: {
                id: id,
                code: code,
                dvvc: dvvc,
                mass: mass,
                mass_fake: mass_fake,
                transpot: transpot,
                warehouse: warehouse
            },
            method: "POST",
            beforeSend: function () {
                $("#btnUpdate").html('<i class="fa fa-spin fa-refresh"></i>');
            },
            success: function (data) {
                $("#modal-choose-dvvc").modal('hide');
                var result = JSON.parse(data);
                if (result.status === true && result.error === '') {
                    alert_float('success', 'Tạo đơn thành công');
                    $('#success-order').modal();
                    $('#modal-choose-dvvc').modal('hide');
                    $('#show-code').text(JSON.parse(data).code);

                    var url = 'https://mysupership.com/orders/print?code=' + result.code + '&size=S9';
                    if (dvvc === 'GHTK') {
                        url = '/system/admin/create_order_ghtk/print_data_order/' + JSON.parse(data).id + '?print=true';
                    } else if (dvvc === 'VTP') {
                        url = '/system/admin/create_order_ghtk/print_data_order/' + JSON.parse(data).id + '?print=true&dv=VTP';
                    } else if (dvvc === 'VNC') {
                        url = '/system/admin/create_order_ghtk/print_data_order/' + JSON.parse(data).id + '?print=true&dv=VNC';
                    } else if (dvvc === 'NB') {
                        url = '/system/admin/create_order_ghtk/print_data_order/' + JSON.parse(data).id + '?print=true&dv=NB';
                    }

                    $('.print-order').attr('href', url);
                } else if (result.status === false && result.error === 'noOrder') {
                    alert_float('danger', 'Đơn hàng không tồn tại.');
                    setTimeout(function () {
                        window.location.reload();
                    }, 5000);
                } else if (result.status === false && result.error === 'Error') {
                    alert_float('danger', 'Đơn hàng không tồn tại.');
                } else if (result.status === false && result.error === 'noAddress_id') {
                    $("#title").html(result.code);
                    $("#shop_id").val(result.id);
                    $("#shop_code").val(result.code);
                    $("#modal-address").modal("show");
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


    function fnConfirm_Orders(obj) {

		$("#boxmass").hide();
        var ids = new Array();
        $("input[type='checkbox']:checked").each(function () {
            if ($(this).val() != '0') {
                ids.push($(this).val());
            }
        });
        if (ids.length < 1) {
            alert('Bạn chưa chọn đơn nào');
            return false;
        }

        if (obj === 0) {
			var messages = 'Bạn Muốn Xoá Thực Sự';
			var c = confirm(messages);

			if (!c) {
				return false;
			}

            $.ajax({
                url: '<?= base_url('api/removeOrder')?>',
                data: {ids: ids},
                method: "POST",
                beforeSend: function () {

                },
                success: function (data) {
                    var result = JSON.parse(data);
                    if (result.status == true) {
                        alert_float('success', 'Xóa danh sách đơn hàng thành công.');
                        setTimeout(function () {
                            window.location.reload();
                        }, 5000);
                    } else
                        alert_float('danger', 'Xóa danh sách đơn hàng thất bại');
                }
            });
        } else if (obj === 1) {
            $("#titleCode").html('Chọn đơn vị vận chuyển cho danh sách đơn hàng đã chọn');
            $("#btnUpdate").attr('onclick', 'fnCreateOrders()');
            $("#btnUpdate").attr('data-id', btoa(ids));
            $("#modal-choose-dvvc").modal('show');
        }


    }


    function fnCreateOrders() {
        var transport = 0;
        var dvvc = $("#dvvc").find(":selected").val();
        var id = $("#btnUpdate").attr('data-id');
        var mass = $("#mass").val();
        var mass_fake = $("#mass_fake").val();

        if (dvvc == "") {
            alert('Bạn cần chọn đơn vị vận chuyển.');
            $("#dvvc").focus();
            return false;
        }

        if (dvvc === 'GHTK') {
            transport = $("#transspot").find(":selected").val();
        }

        $.ajax({
            url: '<?= base_url('api/comfirm_orders')?>',
            data: {id: id, dvvc: dvvc, mass: mass, mass_fake: mass_fake, transport: transport},
            method: "POST",
            beforeSend: function () {
                $("#btnUpdate").html('<i class="fa fa-spin fa-refresh"></i>');
            },
            success: function (data) {
                var result = JSON.parse(data);
                $("#btnUpdate").html('Tạo mới');
                $("#modal-choose-dvvc").modal('hide');
                if (result.status == true && result.error == '') {
                    if (result.totalSuccess > 0) {
                        alert_float('success', 'Tạo ' + result.totalSuccess + ' đơn thành công');

                        $('#success-order').modal();
                        $('#titleOrder').html('Tạo ' + result.totalSuccess + ' đơn thành công');
                        var url = 'https://mysupership.com/orders/print?code=' + result.codes + '&size=S9';
                        if (dvvc === 'GHTK') {
                            url = '/system/admin/confirm_order/print_data_order/?ids=' + JSON.parse(data).codes + '&print=true';
                        } else if (dvvc === 'VTP') {
                            url = '/system/admin/confirm_order/print_data_order/?ids=' + JSON.parse(data).codes + '&print=true&dv=VTP';
                        }else if(dvvc === 'VNC'){
                            url = '/system/admin/confirm_order/print_data_order/?ids=' + JSON.parse(data).codes + '&print=true&dv=VNC';
                        }else if(dvvc === 'NB'){
                            url = '/system/admin/confirm_order/print_data_order/?ids=' + JSON.parse(data).codes + '&print=true&dv=NB';
                        }
                        $('.print-order').attr('href', url);

                    }
                    if (result.totalError > 0) {
                        alert_float('danger', 'Tạo ' + result.totalError + ' đơn thất bại');
                    }
                } else if (result.status == false && result.error == 'noOrder') {
                    alert_float('danger', 'Đơn hàng không tồn tại.');
                    setTimeout(function () {
                        window.location.reload();
                    }, 5000);
                }
            }
        });

    }



    function fnChooseDVVC(mass_fake, mass_fake_ghtk, mass_fake_vpost, mass_fake_vnc) {
        var dvvc = $("#dvvc").find(":selected").val();

        if (dvvc == "") {
            return false;
        }
        $("#mass_fake").val(mass_fake_ghtk);
        if (dvvc === 'SPS') {
            var idCustomer = $("#btnUpdate").attr('data-customer');

            $.ajax({
                url: '<?= base_url('api/getWarehouseConfirm')?>',
                data: {dv: 0, idCustomer: idCustomer, obj: 0},
                beforeSend: function () {

                },
                success: function (data) {
                    var result = JSON.parse(data);
                    var listResult = result.list_result;
                    var html = "";
                    var html = "<lable>Chọn kho</lable>";
                    html += "<select class='form-control' name='warehouse' id='warehouse'>";
                    $.each(listResult, function (index, value) {
                        html += '   <option value="' + value.formatted_address + '">' + value.formatted_address + '</option>';
                    });
                    html += '</select><br/>';
                    $("#boxwarehouse").html(html);
                }
            });

            $("#mass_fake").val(mass_fake);

        } else if (dvvc === 'VTP') {
            $("#mass_fake").val(mass_fake_vpost);
        } else if (dvvc === 'VNC') {
            $("#mass_fake").val(mass_fake_vnc);
        }
        $("#boxtranspot").html("");
        if (dvvc === "GHTK" || dvvc === 'VNC') {
            var transport = $("#btnUpdate").attr('data-transport');
            var html = "<lable>Phương Thức Vận Chuyển</lable>";
            html += "<select class='form-control' name='transpot' id='transspot'>";
            if (transport === 'road') {
                html += "   <option value='1' selected>Tiết Kiệm</option>";
            } else {
                html += "   <option value='1'>Tiết Kiệm</option>";
            }

            if (transport === 'fly') {
                html += "   <option value='2' selected>Tốc Hành</option>";
            } else {
                html += "   <option value='2'>Tốc Hành</option>";
            }
            html += "</select><br>";

            $("#boxtranspot").html(html);

            $.ajax({
                url: '<?= base_url('api/getWarehouseConfirm')?>',
                data: {dv: 1, obj: 0},
                beforeSend: function () {

                },
                success: function (data) {

                    var result = JSON.parse(data);
                    var listResult = result.list_result;
                    var htmlWarehouse = "";
                    var htmlWarehouse = "<lable>Chọn kho</lable>";
                    htmlWarehouse += "<select class='form-control' name='warehouse' id='warehouse'>";
                    $.each(listResult, function (index, value) {
                        if (value.name === "") {
                            htmlWarehouse += '   <option value="' + value.id + '">' + value.address + '</option>';
                        } else {
                            htmlWarehouse += '   <option value="' + value.id + '">' + value.name + '</option>';
                        }
                    });
                    htmlWarehouse += '</select><br/>';
                    $("#boxwarehouse").html(htmlWarehouse);
                }
            });
            $("#modal-content").css('height', '550px');
        }
        var textmass = document.getElementById("mass");
        textmass.select();
    }



    function fnCheckAll(obj = 0) {
        if (obj == 0) {
            $("input[type='checkbox']").each(function () {
                this.checked = true;
            });
            $("#cbTotal").attr('onclick', 'fnCheckAll(1)');
        } else {
            $("input[type='checkbox']").each(function () {
                this.checked = false;
            });
            $("#cbTotal").attr('onclick', 'fnCheckAll(0)');
        }
    }

</script>

</body>
</html>
