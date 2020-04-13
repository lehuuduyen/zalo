<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">


                        <?php if (has_permission('staff', '', 'create')) { ?>
                            <div class="_buttons">

                                <div class="col-md-6">
                                    <a style="margin-right:10px;" href="javascript:;"
                                       class="open-modal-addnew btn btn-info pull-left display-block"><?php echo _l('new_polycy'); ?></a>


                                    <div class="fileUpload btn btn--browse">
                                        <span>Import HTML</span>

                                        <input class="upload" type="file" name="file" value="" id="file_html_data">
                                    </div>
                                    <span class="success-import">success</span>
                                    <div id="table-tmp">

                                    </div>

                                    <div class="loader">
                                        <h1>Đang Phân Tích File Html <span class="bullets">.</span></h1>
                                    </div>
                                </div>


                                <div class="col-md-6">

                                    <div class="col-md-8">
                                        <?php echo render_select('customer_filter', $customer, array('id', 'customer_shop_code'), 'Khách Hàng'); ?>
                                    </div>


                                    <div class="col-md-4 total_calc_cover">
                                        <button class="btn btn-info mtop25" type="button" onclick="filter_customer()">
                                            Load danh sách
                                        </button>
                                        <p class="total-append"></p>
                                    </div>
                                    <div class="clearfix"></div>

                                </div>


                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading"/>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <h3>Khách Hàng Đã Đầy Đủ THông Tin</h3>
                        <?php render_datatable(array(
                            _l('id'),
                            _l('Mã Shop'),
                            _l('Điện Thoại'),
                            _l('Email'),
                            _l('Mật Khẩu'),
                            _l('SĐT Zalo'),
                            _l('Kinh Doanh Giám Sát'),
                            _l('Số Tài Khoản Ngân Hàng'),
                            _l('Tên Tài Khoản'),
                            _l('Tên Ngân Hàng'),
                            _l('Ghi Chú'),
                            _l('Ghi Chú'),
                            _l('Ghi Chú'),
                            _l('Chính Sách Khách Hàng'),
                            _l('options')
                        ), 'customers'); ?>

                        <h3>Khách Hàng Không Đầy Đủ THông Tin</h3>


                        <?php render_datatable(array(
                            _l('id'),
                            _l('Mã Shop'),
                            _l('Điện Thoại'),
                            _l('Email'),
                            _l('Mật Khẩu'),
                            _l('SĐT Zalo'),
                            _l('Kinh Doanh Giám Sát'),
                            _l('Số Tài Khoản Ngân Hàng'),
                            _l('Tên Tài Khoản'),
                            _l('Tên Ngân Hàng'),
                            _l('Ghi Chú'),
                            _l('Ghi Chú'),
                            _l('Ghi Chú'),
                            _l('Chính Sách Khách Hàng'),
                            _l('options')
                        ), 'customers_non_active'); ?>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


<div class="modal fade" id="customer" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <input type="hidden" id="admin_url" value="<?php echo admin_url(); ?>">
        <?php echo form_open(admin_url('customer/add'), array('id' => 'add_new_customer',)); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo _l('modal_add_customer_policy'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="customer_shop_code">Mã Shop</label>
                            <input placeholder="Mã Shop - Tên Shop ( ví dụ S999999 - Tên Shop )" type="text"
                                   class="form-control" id="customer_shop_code" name="customer_shop_code"
                                   value="<?php echo set_value('customer_shop_code'); ?>">
                        </div>


                        <!-- <div class="form-group">
                <label for="customer_shop_name">Tên Shop</label>
                <input placeholder="Tên Shop" type="text" class="form-control" id="customer_shop_name" name="customer_shop_name" value="<?php echo set_value('customer_shop_name'); ?>">
              </div> -->


                        <div class="form-group">
                            <label for="customer_phone">Điện Thoại</label>
                            <input placeholder="Điện Thoại" type="text" class="form-control" id="customer_phone"
                                   name="customer_phone" value="<?php echo set_value('customer_phone'); ?>">
                            <?php echo form_error('customer_phone', '<div class="text-danger">', '</div>'); ?>
                        </div>


                        <div class="form-group">
                            <label for="customer_email">Email</label>
                            <input placeholder="Email" type="text" class="form-control" id="customer_email"
                                   name="customer_email" value="<?php echo set_value('customer_email'); ?>">
                            <?php echo form_error('customer_email', '<div class="text-danger">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="customer_password">Mật Khẩu</label>
                            <input placeholder="Mật Khẩu" type="text" class="form-control" id="customer_password"
                                   name="customer_password" value="<?php echo set_value('customer_password'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="customer_phone_zalo">SĐT Zalo</label>
                            <input placeholder="SĐT Zalo" type="text" class="form-control" id="customer_phone_zalo"
                                   name="customer_phone_zalo" value="<?php echo set_value('customer_phone_zalo'); ?>">
                        </div>

                        <div class="form-group" style="position:relative">
                            <label for="customer_phone_zalo">Kinh Doanh Giám Sát</label>

                            <input autocomplete="off" placeholder="Chọn Nhân Viên" class="form-control"
                                   id="search_employ" type="text" name="search_employ"
                                   value="<?php echo set_value('search_employ'); ?>">
                            <i class="fa fa-search search-icon" aria-hidden="true"></i>

                            <ul class="list-group search-item">
                                <?php foreach ($staff as $value): ?>
                                    <li data-id="<?php echo $value->staffid ?>" class="list-group-item">
                                        <?php echo $value->lastname . " " . $value->firstname ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>


                            <input type="hidden" id="customer_monitoring" name="customer_monitoring"
                                   value="<?php echo set_value('customer_monitoring'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="customer_note">Token Khách Hàng</label>
                            <textarea placeholder="Token Khách Hàng" class="form-control txt-area" id="token_customer"
                                      name="token_customer"><?php echo set_value('token_customer'); ?></textarea>

                        </div>


                        <div class="form-group">
                            <label style="display:block" for="customer_phone_zalo">Tài Khoản Ngân Hàng</label>
                            <div class="col-md-4 three-inline">
                                <input placeholder="Số Tài Khoản" type="text" class="form-control col-md-4"
                                       id="customer_number_bank" name="customer_number_bank"
                                       value="<?php echo set_value('customer_number_bank'); ?>">
                            </div>
                            <div class="col-md-4 three-inline">
                                <input placeholder="Tên Tài Khoản" type="text" class="form-control col-md-4"
                                       id="customer_id_bank" name="customer_id_bank"
                                       value="<?php echo set_value('customer_id_bank'); ?>">
                            </div>
                            <div class="col-md-4 three-inline">
                                <input placeholder="Tên Ngân Hàng" type="text" class="form-control col-md-4"
                                       id="customer_name_bank" name="customer_name_bank"
                                       value="<?php echo set_value('customer_name_bank'); ?>">
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="customer_note">Ghi Chú</label>
                            <textarea placeholder="Ghi Chú" class="form-control txt-area" id="customer_note"
                                      name="customer_note"><?php echo set_value('customer_note'); ?></textarea>

                        </div>

                        <div class="form-group">
                            <label for="customer_note">Địa Chỉ Giao Hàng Tiết Kiệm</label>
                            <input placeholder="Địa Chỉ Giao Hàng Tiết Kiệm (ID địa chỉ GHTK)" class="form-control txt-area" id="address_id"
                                      name="address_id" value="<?php echo set_value('address_id'); ?>">

                        </div>


                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit"
                        class="btn btn-primary submit_customer_policy"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php init_tail(); ?>

<input type="text" name="thang" id="thang" value="<?php echo $_GET['thang'] ?>">


<script type="text/javascript">

    var param = {
        "thang": '[name="thang"]',
        "customer": '[name="customer_filter"]'
    }
    $(function () {

        var data = initDataTable('.table-customers', $('#admin_url').val() + 'customer', [1], [1], param);
        data.column(0).visible(false);
        data.column(3).visible(false);
        data.column(7).visible(false);
        data.column(8).visible(false);
        data.column(9).visible(false);
        data.column(10).visible(false);
        data.column(11).visible(false);
        data.column(12).visible(false);


        var data2 = initDataTable('.table-customers_non_active', $('#admin_url').val() + 'customer/nonActive', [1], [1], param);
        data2.column(0).visible(false);
        data2.column(3).visible(false);
        data2.column(7).visible(false);
        data2.column(8).visible(false);
        data2.column(9).visible(false);
        data2.column(10).visible(false);
        data2.column(11).visible(false);
        data2.column(12).visible(false);


    });


    function filter_customer() {
        if ($.fn.DataTable.isDataTable('.table-customers')) {
            $('.table-customers').DataTable().ajax.reload(false);
        }
        if ($.fn.DataTable.isDataTable('.table-customers_non_active')) {
            $('.table-customers_non_active').DataTable().ajax.reload(false);
        }

    }

    function setValid() {

        $("#add_new_customer").validate().destroy();

        $("#add_new_customer").validate({
            errorClass: 'error text-danger',
            highlight: function (element) {
                $(element).parent().addClass("has-error");
            },
            unhighlight: function (element) {
                $(element).parent().removeClass("has-error");
            },
            ignore: [],
            rules: {
                // customer_shop_name: {
                //   required: true,
                // },
                customer_shop_code: {
                    required: true,
                },
                token_customer: {
                    required: true,
                },
                customer_email: {
                    email: true
                },
                customer_password: {
                    required: true,
                },
                customer_phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10
                },
                customer_phone_zalo: {
                    number: true,
                    minlength: 10,
                    maxlength: 10
                },
                customer_monitoring: {
                    required: true,
                }
            }

        });
    }

    var checkAlert = <?php echo isset($_SESSION['success']) ? 'true' : 'false'?>;

    if (checkAlert) {
        alert_float('success', 'Thêm Thành Công');
    }

    var checkError = <?php echo isset($_SESSION['error_add']) ? 'true' : 'false'?>;

    if (checkError) {
        $('#customer').modal('show');
        setValid();
    }

    var checkDelete = <?php echo isset($_SESSION['delete_ok']) ? 'true' : 'false'?>;

    if (checkDelete) {

        alert_float('success', 'Xoá Thành Công');

    }
    var checkEdit = <?php echo isset($_SESSION['success_edit']) ? 'true' : 'false'?>;

    if (checkEdit) {

        alert_float('success', 'Sửa Thành Công');

    }


    $('.open-modal-addnew').click(function () {
        var action = $('#admin_url').val() + '/customer/add';
        $('#customer').modal('show');
        setValid();

    });


    $(document).on('click', '.search-item li', function () {
        var id = $(this).attr('data-id');
        var text = $(this).text();

        $('#search_employ').val(text.trim());
        $('.search-item').hide();
        $('#customer_monitoring').val(id);
    });

    $(document).on('focus', '#search_employ', function () {
        $('.search-item').show();
    });
    $(document).on('focusout', '#search_employ', function (e) {
        setTimeout(function () {
            $('.search-item').hide();
        }, 400);

    });

    $(document).on('keyup', '#search_employ', function () {
        $('.search-item').show();
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;

        // Loop through the comment list
        $(".search-item li").each(function () {

            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
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

    $(document).on('click', '.delete-reminder-custom', function () {
        var c = confirm("Bạn Muốn Xoá Thực Sự");
        if (!c) {
            return false;
        }
    });


    function setFromData(data) {


        var value = Object.values(data);
        var key = Object.keys(data);

        for (var i = 0; i < value.length; i++) {

            if ($(`#add_new_customer [name="${key[i]}"]`).length) {
                if (key[i] == 'customer_monitoring') {
                    $.ajax({
                        url: '/system/admin/customer/getEmploy/' + value[i],
                        success: function (data) {
                            data = JSON.parse(data);
                            $('#search_employ').val(data.lastname + ' ' + data.firstname);

                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                }
                $(`#add_new_customer [name="${key[i]}"]`).val(value[i]);
            }
        }

    }

    $(document).on('click', '.edit-customer', function () {

        var id = $(this).attr('data-id');
        var action = $('#admin_url').val() + 'customer/edit/' + id;
        $('#add_new_customer').attr('action', action);
        $.ajax({
            url: '/system/admin/customer/getDataEdit/' + id,
            success: function (data) {
                data = JSON.parse(data);
                $('#customer').modal('show');
                setValid();
                setFromData(data);

            },
            error: function (e) {
                console.log(e);
            }
        });


    });


    //import customer


    document.getElementById('file_html_data').addEventListener('change', getFile)


    function getFile(event) {
        $('.loader').addClass('active');
        setTimeout(function () {
            const input = event.target;
            if ('files' in input && input.files.length > 0) {
                placeFileContent(input.files[0])
            }
        }, 100);
    }


    function placeFileContent(file) {

        readFileContent(file).then((content) => {


            $('#table-tmp').append(content);

            var contentTable = $('#custom-table_wrapper').html();


            $('#table-tmp').empty();
            $('#table-tmp').append(contentTable);

            var dataSendShop = [];
            $('#table-tmp tbody tr').each(function (i, obj) {

                if ($('#table-tmp tbody tr').eq(i).find('td').eq(5).find('.bold')[0]) {
                    var shopName = $('#table-tmp tbody tr').eq(i).find('td').eq(5).find('.bold')[0].innerText;
                }


                if ($('#table-tmp tbody tr').eq(i).find('td').eq(4).find('.bold > span')[0]) {
                    var customer_shop_code = $('#table-tmp tbody tr').eq(i).find('td').eq(4).find('.bold > span')[0].innerText + ' - ' + shopName;
                }


                if ($('#table-tmp tbody tr').eq(i).find('td').eq(4).find('.pull-right .label')[0]) {
                    var isShop = $('#table-tmp tbody tr').eq(i).find('td').eq(4).find('.pull-right .label')[0].innerText;
                }

                if ($('#table-tmp tbody tr').eq(i).find('td').eq(4).find('div').eq(2).find('> span')[0]) {
                    var customer_phone = $('#table-tmp tbody tr').eq(i).find('td').eq(4).find('div').eq(2).find('> span')[0].innerText;
                }


                if ($('#table-tmp tbody tr').eq(i).find('td').eq(4).find('div').eq(2).find('.font-red')[0]) {
                    var customer_email = $('#table-tmp tbody tr').eq(i).find('td').eq(4).find('div').eq(2).find('.font-red')[0].innerText;
                }

                if (isShop === 'Khách Hàng') {
                    dataSendShop.push({
                        shop: shopName,
                        customer_phone: customer_phone,
                        customer_email: customer_email,
                        customer_shop_code: customer_shop_code
                    });
                }

            });

            if (dataSendShop.length === 0) {
                $('#table-tmp tbody tr').each(function (i, obj) {

                    if ($('#table-tmp tbody tr').eq(i).find('td').eq(6).find('.bold')[0]) {
                        var shopName = $('#table-tmp tbody tr').eq(i).find('td').eq(6).find('.bold')[0].innerText;
                    }


                    if ($('#table-tmp tbody tr').eq(i).find('td').eq(5).find('.bold > span')[0]) {
                        var customer_shop_code = $('#table-tmp tbody tr').eq(i).find('td').eq(5).find('.bold > span')[0].innerText + ' - ' + shopName;
                    }


                    if ($('#table-tmp tbody tr').eq(i).find('td').eq(5).find('.pull-right .label')[0]) {
                        var isShop = $('#table-tmp tbody tr').eq(i).find('td').eq(5).find('.pull-right .label')[0].innerText;
                    }

                    if ($('#table-tmp tbody tr').eq(i).find('td').eq(5).find('div').eq(2).find('> span')[0]) {
                        var customer_phone = $('#table-tmp tbody tr').eq(i).find('td').eq(5).find('div').eq(2).find('> span')[0].innerText;
                    }


                    if ($('#table-tmp tbody tr').eq(i).find('td').eq(5).find('div').eq(2).find('.font-red')[0]) {
                        var customer_email = $('#table-tmp tbody tr').eq(i).find('td').eq(5).find('div').eq(2).find('.font-red')[0].innerText;
                    }

                    if (isShop === 'Khách Hàng') {
                        dataSendShop.push({
                            shop: shopName,
                            customer_phone: customer_phone,
                            customer_email: customer_email,
                            customer_shop_code: customer_shop_code
                        });
                    }

                });
            }
            var data = {};
            if (typeof (csrfData) !== 'undefined') {
                data[csrfData['token_name']] = csrfData['hash'];
            }

            data.dataSendShop = dataSendShop;

            console.log(data);

            // console.log(data);
            //
            //
            $('.loader').removeClass('active');


            // ajax import
            if (dataSendShop.length > 0) {
                $.ajax({
                    url: '/system/admin/customer/read_html',
                    data,
                    type: 'POST',
                    success: function (data) {

                        $('.loader').removeClass('active');
                        $('#table-tmp').empty();

                        $('.success-import').show();
                        $('.table-customers_non_active').DataTable().ajax.reload();

                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            } else {
                $('.loader').removeClass('active');
                alert('Không có Dữ Liệu Khách Hàng');
            }


        }).catch(error => console.log(error));
    }

    function readFileContent(file) {
        const reader = new FileReader()
        return new Promise((resolve, reject) => {
            reader.onload = event => resolve(event.target.result)
            reader.onerror = error => reject(error)
            reader.readAsText(file)
        })
    }


</script>
<style media="screen">
    .col-md-4.three-inline {
        padding: 3px;
    }

    .col-md-4.three-inline:nth-child(1) {
        padding-left: 0;
    }

    .col-md-4.three-inline:nth-child(3) {
        padding-right: 0;
    }

    textarea.txt-area.form-control {
        height: 150px;
        resize: none;
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
        top: 64px;
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

    .loader {
        background-color: #bfcbd9;
        text-align: center;
        height: 100vh;
        display: none;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 123123123123;
    }

    .loader.active {
        display: flex;
    }

    .loader h1 {
        color: white;
        font-family: 'arial';
        font-weight: 800;
        font-size: 4em;
    }

    .bullets {
        animation: dots 2s steps(3, end) infinite;
    }

    @keyframes dots {
        0%, 20% {
            color: rgba(0, 0, 0, 0);
            text-shadow: .25em 0 0 rgba(0, 0, 0, 0),
            .5em 0 0 rgba(0, 0, 0, 0);
        }
        40% {
            color: white;
            text-shadow: .25em 0 0 rgba(0, 0, 0, 0),
            .5em 0 0 rgba(0, 0, 0, 0);
        }
        60% {
            text-shadow: .25em 0 0 white,
            .5em 0 0 rgba(0, 0, 0, 0);
        }
        80%, 100% {
            text-shadow: .25em 0 0 white,
            .5em 0 0 white;
        }
    }


    .fileUpload {
        position: relative;
        overflow: hidden;
    }

    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }

    .btn--browse {

        border-left: 0;

        background-color: #03a9f4;
        color: #fff;
        height: 33px;
        padding: 6px 14px;
    }


    .f-input {
        height: 42px;
        background-color: white;
        border: 1px solid gray;
        width: 100%;
        max-width: 400px;
        float: left;
        padding: 0 14px;
    }

    .success-import {
        color: red;
        position: absolute;
        left: 153px;
        top: 53px;
        display: none;
    }
</style>
</body>
</html>
