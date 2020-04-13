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
$volume = ($default_data) ? number_format($default_data->volume_default) : '';
$token_ghtk = ($default_data) ? $default_data->token_ghtk : '';
$address_id = ($default_data) ? $default_data->address_id : '';
$id_default = ($default_data) ? $default_data->id : '';
?>
<script>
    var token_ghtk = "<?php echo $default_data->token_ghtk; ?>";
    var address_id = "<?php echo $default_data->address_id; ?>";
</script>
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
                                <h2>Sửa Thông Tin Đơn Hàng</h2>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading"/>
                        <?php } ?>
                        <div class="clearfix"></div>


                        <div id="debts_customer" class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="province">Mã đơn hàng</label>
                                        <input type="text" class="form-control" placeholder="Nhập mã đơn hàng" name="p"
                                               id="p">
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

                                <div class="col-md-12 total_calc_cover">
                                    <button class="btn btn-info mtop25" type="button" id="filter_create_order"
                                            onclick="fnSearchOrder()">
                                        Lọc Danh Sách
                                    </button>
                                    <input type="hidden" name="enable_filter" value="0">
                                </div>
                                <div class="clearfix"></div>

                            </div>

                        </div>
                        <table class="table dataTable no-footer">
                            <thead>
                            <tr>
                                <th style="text-align: center">Mã đơn hàng</th>
                                <th style="text-align: center">Trạng thái</th>
                                <th style="text-align: center">Phí tính</th>
                                <th style="text-align: center">&nbsp;</th>
                                <th style="text-align: center">Trạng thái mới</th>
                                <th style="text-align: center">Phí tính mới</th>
                            </tr>
                            </thead>
                            <tbody id="listOrder">

                            </tbody>
                        </table>

                        <hr/>
                        <h4>LỊCH SỬ THAY ĐỔI ĐƠN HÀNG</h4>
                        <?php
                            $date_end = date('Y-m-d');
                            $date = new DateTime($date_end);
                            date_sub($date, date_interval_create_from_date_string('30 days'));
                            $date_start = date_format($date, 'Y-m-d');
                        ?>
                        <div class="col-md-3 row"><?php echo render_date_input('date_start', "Ngày bắt đầu", _d($date_start)) ?></div>
                        <div class="col-md-3"><?php echo render_date_input('date_end', "Ngày kết thúc", _d($date_end)) ?></div>
                        <table class="table no-footer">
                            <thead>
                            <tr>
                                <th style="text-align: center">Mã đơn hàng</th>
                                <th style="text-align: center">Trạng thái cũ</th>
                                <th style="text-align: center">Trạng thái mới</th>
                                <th style="text-align: center">Phí tính cũ</th>
                                <th style="text-align: center">Phí tính mới</th>
                                <th style="text-align: center">Người thay đổi</th>
                                <th style="text-align: center">Thời Gian Cập Nhật</th>
                            </tr>
                            </thead>
                            <tbody id="listOrderHistory">

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script src="/system/assets/plugins/select2/index.js"></script>

<script>

    var no_info_order = <?php echo isset($_SESSION['no_info_order']) ? 'true' : 'false'?>;
    var update = <?= isset($_SESSION['no_info_order']) ? $_SESSION['update'] : '0'?>;

    if (no_info_order) {
        alert_float('danger', 'Đơn hàng không tồn tại.');
    }

    if (update == 1) {
        alert_float('danger', 'Cập nhật thất bại.');
    } else if (update == 2) {
        alert_float('success', 'Cập nhật thành công.');
    }
    fnSearchOrder();
    function fnSearchOrder() {
        var p = $("#p").val();
        // if (p == "") {
        //     alert('Bạn cần nhập mã đơn hàng cần tìm!');
        //     $("#p").focus();
        //     return false;
        // }
        $('#listOrderHistory').html('');
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();


        var dataSend = {p : p};
        dataSend['date_start'] = date_start;
        dataSend['date_end'] = date_end;
        if (typeof (csrfData) !== 'undefined') {
            dataSend['token_name'] = csrfData['hash'];
        }

        $.ajax({
            url: '<?= base_url('api/search')?>',
            data: dataSend,
            type: 'POST',
            beforeSend: function () {
                $("#filter_create_order").html('<i class="fa fa-support fa-spin"></i>');
            },
            success: function (data) {
                var result = JSON.parse(data);
                $("#filter_create_order").html('Lọc Danh Sách');
                var html = "";
                $("#listOrder").html('');
                if(result.info) {
                    var info = result.info;
                    html += '<tr>';
                    html += '   <td style="text-align: center">' + info.code_supership + '</td>';
                    html += '   <td style="text-align: center">' + info.status + '</td>';
                    if (info.hd_fee == null) {
                        html += '   <td style="text-align: center">' + formatNumber(info.hd_fee_stam) + '</td>';
                    } else {
                        html += '   <td style="text-align: center">' + formatNumber(info.hd_fee) + '</td>';
                    }
                    html += '   <td style="text-align: center">&nbsp;</td>';
                    html += '   <td style="text-align: center">';
                    html += '       <select class="form-control" name="status" id="status">';
                    html += '           <option value="">-- Chọn trạng thái --</option>';
                    html += '           <option value="Hủy">Hủy</option>';
                    html += '           <option value="Đã Đối Soát Giao Hàng">Đã Đối Soát Giao Hàng</option>';
                    html += '           <option value="Đã Trả Hàng">Đã Trả Hàng</option>';
                    html += '           <option value="Đã Trả Hàng Một Phần">Đã Trả Hàng Một Phần</option>';
                    html += '       </select>';
                    html += '   </td>';

                    html += '   <td style="text-align: center">';
                    html += '       <input type="text" class="form-control" placeholder="Nhập phí mới" name="price" id="price"/>';
                    html += '   </td>';
                    html += '</tr>';
                    html += '<tr>';
                    html += '   <td colspan="5" ></td>';
                    html += '   <td>';
                    html += '       <input value="' + info.id + '" name="id" id="id" type="hidden"/>';
                    html += '       <button type="button" id="btnChange" onclick="fnChangeOrder()" class="btn-success btn" style="float: right;">Xác nhận</button>';
                    html += '   </td>';
                    html += '<tr>';

                    $("#listOrder").html(html);
                }


                var info_history = result.info_history;

                $.each(info_history, function(i, v){
                    var tr_history = $('<tr></tr>');
                    var td_code_history = $('<td class="text-center">' + v.code_supership + '</td>');
                    var td_status_history = $('<td class="text-center">' + v.status + '</td>');
                    var td_status_history_new = $('<td class="text-center">' + v.status_new + '</td>');
                    if (v.hd_fee == null) {
                        var td_price_history = $('<td class="text-center">' + (v.hd_fee_stam != null ? formatNumber(v.hd_fee_stam) : 'Không đổi') + '</td>');
                    }
                    else {
                        var td_price_history = $('<td class="text-center">' + (v.hd_fee != null ? formatNumber(v.hd_fee) : 'Không đổi') + '</td>');
                    }

                    if (v.hd_fee_new == null) {
                        var td_price_history_new = $('<td class="text-center">' + (v.hd_fee_stam_new != null ? formatNumber(v.hd_fee_stam_new) : 'Không đổi') + '</td>');
                    }
                    else {
                        var td_price_history_new = $('<td class="text-center">' + (v.hd_fee_new != null ? formatNumber(v.hd_fee_new) : 'Không đổi') + '</td>');
                    }
                    var td_date_history = $('<td class="text-center">' + v.date_create + '</td>');
                    var td_create_history = $('<td class="text-center">' + v.fullname + '</td>');
                    tr_history.append(td_code_history);
                    tr_history.append(td_status_history);
                    tr_history.append(td_status_history_new);
                    tr_history.append(td_price_history);
                    tr_history.append(td_price_history_new);
                    tr_history.append(td_create_history);
                    tr_history.append(td_date_history);
                    $('#listOrderHistory').append(tr_history);
                })
            }
        });

    }

    function fnChangeOrder() {
        var status = $("#status").find(":selected").val();
        var price = $("#price").val();
        var id = $("#id").val();

        $.ajax({
            url: '<?= base_url('api/updateOrder')?>',
            data: {status: status, price: price, id: id},
            method: "POST",
            beforeSend: function () {
                $("#btnChange").html('<i class="fa fa-spin fa-support"></i>');
            },
            success: function (data) {
                $("#btnChange").html('Xác nhận');
                if (data == 0) {
                    alert_float('danger', 'Đơn hàng không tồn tại');
                } else if (data == 1) {
                    alert_float('danger', 'Cập nhật thất bại.');
                } else {
                    alert_float('success', 'Cập nhật thành công.');
                    fnSearchOrder();
                }
            }
        });
    }


    $('body').on('change','#date_start,#date_end', function(e){
        if($('#p').val() != "") {
            fnSearchOrder();
        }
    })

    function formatNumber(nStr, decSeperate = ".", groupSeperate = ",") {
        nStr += '';
        x = nStr.split(decSeperate);
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        x2 = x2.substr(0, 2);
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
        }
        return x1 + x2;
    };
</script>

</body>
</html>
