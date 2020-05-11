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
                            <div class="_buttons">
                                <h2>Chuyển Đổi Đơn Hàng</h2>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading"/>
                        <?php } ?>
                        <div class="clearfix"></div>


                        <div id="debts_customer" class="">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="province">Mã đơn hàng cần chuyển</label>
                                        <input type="text" class="form-control"
                                               placeholder="Nhập mã đơn hàng hoặc số điện thoại cần chuyển"
                                               name="codesupership"
                                               id="codesupership">
                                    </div>
                                </div>

                                <div class="col-md-5" style="padding: 25px">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success" id="btnConvert"
                                                onclick="fnSearchConvertOrder()">
                                            Thực hiện
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <table class="table dataTable no-footer">
                            <thead>
                            <tr>
                                <th style="text-align: center">Ngày tạo</th>
                                <th style="text-align: center">Mã đơn hàng</th>
                                <th style="text-align: center">Trạng thái</th>
                                <th style="text-align: center">Thu hộ</th>
                                <th style="text-align: center">Phí DV</th>
                                <th style="text-align: center">Khối lượng</th>
                                <th style="text-align: center">DVVC</th>
                                <th style="text-align: center">Thông tin</th>
                                <th style="text-align: center">Chuyển đổi</th>
                            </tr>
                            </thead>
                            <tbody id="listConvert_Orders"></tbody>
                        </table>
                        <hr>
                        <h2>Lịch sử chuyển đổi</h2>
                        <table class="table dataTable no-footer">
                            <thead>
                            <tr>
                                <th style="text-align: center">Ngày thực hiện</th>
                                <th style="text-align: center">Mã đơn nguồn</th>
                                <th style="text-align: center">Mã đơn đích</th>
                                <th style="text-align: center">Khối lượng</th>
                                <th style="text-align: center">DVVC nguồn</th>
                                <th style="text-align: center">DVVC mới</th>
                                <th style="text-align: center">In tem mới</th>
                            </tr>
                            </thead>
                            <tbody id="listOrder_history">

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
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
<div class="modal fade" id="modal-change" tabindex="-1" role="dialog" style="left: 35%;top: 10%;">
    <div class="modal-dialog">
        <div class="modal-content" style="text-align: center; height: 400px; width: 474px">
            <div class="modal-body" style="text-align: center;">
                <p class="circle-check"><i class="fa fa-home" aria-hidden="true"></i></p>
                <p class="code-order-show">Nhập khối lượng mới cho đơn hàng có mã
                    <span id="titleCode">sadasdsadasd</span></p>
                <input type="hidden" value="" id="_shop">
                <input type="hidden" value="" id="_idOrder">
                <input type="hidden" value="" id="_code">
                <input type="hidden" value="" id="dvvcSource">

                <lable>Đơn Vị Vận Chuyển</lable>
                <select name="dvvcFinsh" id="dvvcFinsh" class="form-control"
                        onchange="fnChooseDVVC(<?= str_replace(',', '', $mass_fake) ?>, <?= str_replace(',', '', $mass_fake_ghtk) ?>,<?= str_replace(',', '', $mass_fake_vpost) ?>)">
                    <option value="">-- Chọn Đơn Vị Vận Chuyển --</option>
                    <option value="SPS">SPS</option>
                    <option value="GHTK">GHTK</option>
                    <option value="VTP">Viettel Post</option>
                </select><br>

                <lable>Khối Lượng Thực</lable>
                <input type="text" value="100" id="txt-mass" class="form-control"
                       placeholder="Nhập khối lượng mới..."><br>

                <lable>Khối Lượng Ảo</lable>
                <input type="text" value="100" id="txt-mass_fake" class="form-control"
                       placeholder="Nhập khối lượng mới..."><br>
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" id="btnUpdate" class="btn btn-success" onclick="fnChangeNewOrder()">Tạo mới
                </button>
            </div>

        </div>
    </div>
</div>


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

<?php init_tail(); ?>

<script src="/system/assets/plugins/select2/index.js"></script>

<script>

    function fnSearchConvertOrder() {
        var codesupership = $("#codesupership").val();
        $.ajax({
            url: '<?= base_url('api/search_convert_order')?>',
            data: {code: codesupership},
            beforeSend: function () {
                $("#btnConvert").html('<i class="fa fa-spin fa-refresh"></i>');
            },
            success: function (data) {
                var result = JSON.parse(data);
                $("#btnConvert").html('Thực hiện');
                var html = '';
                if (result.status == true) {
                    $.each(result.info, function (index, value) {
                        html += '<tr>';
                        html += '   <td style="text-align: center">' + value.date_create.split(" ")[0].split('-')[2] + '/' + value.date_create.split(" ")[0].split('-')[1] + '/' + value.date_create.split(" ")[0].split('-')[0] + '</td>';
                        html += '   <td style="text-align: center">' + value.code_supership + '</td>';
                        html += '   <td style="text-align: center">' + value.status + '</td>';
                        html += '   <td style="text-align: center">' + formatNumber(value.collect) + '</td>';
                        if (value.hd_fee == null)
                            html += '   <td style="text-align: center">' + formatNumber(value.hd_fee_stam) + '</td>';
                        else
                            html += '   <td style="text-align: center">' + formatNumber(value.hd_fee) + '</td>';
                        html += '   <td style="text-align: center">' + value.mass + '</td>';
                        html += '   <td style="text-align: center">' + value.DVVC + '</td>';
                        html += '   <td style="text-align: center">' + value.receiver + ' - ' + value.phone + ' - ' + value.district + ' - ' + value.city + '</td>';
                        html += '   <td style="text-align: center"><button class="btn btn-success" type="button" onclick="fnShowPopup(' + value.id + ',\'' + value.shop + '\',\'' + value.code_supership + '\',\'' + value.DVVC + '\',' + value.mass + ')">Chuyển đổi</button></td>';
                        html += '</tr>';
                    });

                    $("#listConvert_Orders").html(html);
                }
            }
        });
    }

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

    function fnShowPopup(id, shop, code_supership, dvvc, mass) {
        $("#titleCode").html(code_supership);
        $("#_shop").val(shop);
        $("#_idOrder").val(id);
        $("#_code").val(code_supership);
        $("#dvvcSource").val(dvvc);
        $("#txt-mass").val(mass);
        $('#modal-change').modal('show');
    }

    function fnChangeNewOrder() {
        var idOrder = $("#_idOrder").val();
        var shop = $("#_shop").val();
        var code_supership = $("#_code").val();
        var dvvcSource = $("#dvvcSource").val();
        var mass = $("#txt-mass").val();
        var mass_fake = $("#txt-mass_fake").val();

        var dvvcFinsh = $("#dvvcFinsh").find(":selected").val();

        $.ajax({
            url: '<?= base_url('api/convert_order')?>',
            data: {
                idOrderShop: idOrder,
                shop: shop,
                code: code_supership,
                mass: mass,
                mass_fake: mass_fake,
                dvvcSource: dvvcSource,
                dvvcFinsh: dvvcFinsh
            },
            method: "POST",
            beforeSend: function () {
                $('#btnUpdate').html('<i class="fa fa-refresh fa-spin"></i>');
            },
            success: function (data) {
                var result = JSON.parse(data);
                $('#btnUpdate').html('Tạo mới');
                if (result.status && result.error == '') {
                    $('#modal-change').modal('hide');
                    $('#show-code').text(result.info.code);

                    if (dvvcFinsh === 'SPS') {
                        $('.print-order').attr('href', 'https://mysupership.com/orders/print?code=' + result.info.code + '&print=true&size=S9');
                    } else if (dvvcFinsh === 'GHTK') {
                        $('.print-order').attr('href', '/system/admin/create_order_ghtk/print_data_order/' + result.info.id + '?print=true');
                    } else if (dvvcFinsh === 'VTP') {
                        $('.print-order').attr('href', '/system/admin/create_order_ghtk/print_data_order/' + result.info.id + '?print=true&dv=VTP');
                    }

                    $('#success-order').modal();
                } else {
                    alert(result.error);
                }
            }
        });

    }

    $('.print-order').click(function () {
        $('#success-order').modal('hide');
    });

    $(document).ready(function () {
        $.get('<?= base_url('api/get_history')?>', '', function (data) {
            var result = JSON.parse(data);
            var list = result.list;
            var html = '';
            $.each(list, function (index, value) {
                html += '<tr>';
                html += '    <td style="text-align: center">' + value.date_create + '</td>';
                html += '    <td style="text-align: center">' + value.code_old + '</td>';
                html += '    <td style="text-align: center">' + value.code_new + '</td>';
                html += '    <td style="text-align: center">' + value.mass + '</td>';
                html += '    <td style="text-align: center">' + value.dvvc_source + '</td>';
                html += '    <td style="text-align: center">' + value.dvvc_finsh + '</td>';
                if (value.dvvc_finsh === 'GHTK')
                    html += '    <td style="text-align: center"><a href="/system/admin/create_order_ghtk/print_data_order/' + value.orders_id + '?print=true" target="_blank" class="btn btn-primary btn-icon"><i class="fa fa-print"></i></a> </td>';
                else if(value.dvvc_finsh === 'VTP')
                    html += '    <td style="text-align: center"><a href="/system/admin/create_order_ghtk/print_data_order/' + value.orders_id + '?print=true&dv=VTP" target="_blank" class="btn btn-primary btn-icon"><i class="fa fa-print"></i></a> </td>';
                else
                    html += '    <td style="text-align: center"><a href="https://mysupership.com/orders/print?code=' + value.code_new + '" target="_blank" class="btn btn-primary btn-icon"><i class="fa fa-print"></i></a> </td>';

                html += '</tr>';
            });

            $("#listOrder_history").html(html);
        });
    });

    function fnChooseDVVC(mass_fake, mass_fake_ghtk, mass_fake_vpost) {
        var dvvc = $("#dvvc").find(":selected").val();
        if (dvvc == "") {
            return false;
        }
        $("#txt-mass_fake").val(mass_fake_ghtk);
        if (dvvc === 'SPS') {
            $("#txt-mass_fake").val(mass_fake);
        } else if (dvvc === 'VTP') {
            $("#txt-mass_fake").val(mass_fake_vpost);
        }

        var textmass = document.getElementById("txt-mass_fake");
        textmass.select();
    }

</script>

</body>
</html>
