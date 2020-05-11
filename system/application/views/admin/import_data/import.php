<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <div class="col-md-4">
                                <form action="<?= base_url('admin/import_data/add_file_orders') ?>"
                                      id="form_excel_orders"
                                      autocomplete="off" enctype="multipart/form-data" method="post"
                                      accept-charset="utf-8">
                                    <div class="form-group">
                                        <label for="company" class="control-label">FILE Dữ liệu danh sách đơn
                                            hàng</label>
                                        <input type="file" id="file_order" name="file_order[]" class="form-control"
                                               value="" multiple>
                                    </div>
                                    <button class="btn btn-info btnfile_order" type="button"
                                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> Đang upload"
                                            onclick="add_order_excel()">
                                        Up dữ liệu danh sách đơn hàng
                                    </button>
                                    <h3 class="text-danger label_file_order"></h3>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form action="<?= base_url('admin/import_data/add_file_delivery_orders') ?>"
                                      id="form_excel_delivery"
                                      autocomplete="off" enctype="multipart/form-data" method="post"
                                      accept-charset="utf-8">
                                    <div class="form-group">
                                        <label for="company" class="control-label">File dữ liệu danh sách đơn hàng giao
                                            hàng</label>
                                        <input type="file" id="file_delivery_order" name="file_delivery_order[]"
                                               class="form-control" value="" multiple>
                                    </div>
                                    <button class="btn btn-info btnfile_delivery_order" type="button"
                                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> Đang upload"
                                            onclick="add_delivery_orders_excel()">
                                        Up dữ liệu danh sách đơn hàng giao hàng
                                    </button>
                                    <h3 class="text-danger label_file_delivery_order"></h3>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form action="<?= base_url('admin/import_data/add_file_delivery_list') ?>"
                                      id="form_excel_delivery_list"
                                      autocomplete="off" enctype="multipart/form-data" method="post"
                                      accept-charset="utf-8">
                                    <div class="form-group">
                                        <label for="company" class="control-label">File dữ liệu danh sách giao
                                            hàng</label>
                                        <input type="file" id="file_delivery_list" name="file_delivery_list[]"
                                               class="form-control" value="" multiple>
                                    </div>
                                    <button class="btn btn-info btnfile_delivery_list" type="button"
                                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> Đang upload"
                                            onclick="add_delivery_list()">
                                        Up dữ liệu danh sách giao hàng
                                    </button>
                                    <h3 class="text-danger label_file_delivery_list"></h3>
                                </form>
                            </div>

                            <div class="col-md-4 div_tb_file text-danger mtop30"></div>
                            <div class="clearfix"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <div class="col-md-4">
                                <form action="<?= base_url('admin/import_data/revenue_allocation') ?>"
                                      id="revenue_allocation"
                                      autocomplete="off" enctype="multipart/form-data" method="post"
                                      accept-charset="utf-8">
                                    <div class="form-group">
                                        <label for="company" class="control-label">FILE Dữ liệu Phân Bổ Doanh Thu
                                            Tổng </label>
                                        <input type="file" id="file_revenue_allocation" name="file_revenue_allocation[]"
                                               class="form-control" value="" multiple>
                                    </div>
                                    <button class="btn btn-info btnfile_revenue_allocation" type="button"
                                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> Đang upload"
                                            onclick="add_revenue_allocation()">
                                        Up dữ liệu Phân Bổ Doanh Thu Tổng
                                    </button>
                                    <h3 class="text-danger label_revenue_allocation"></h3>
                                </form>
                                <a class="btn btn-default" href="http://spshd.com/system/assets/file_mau_PBDT.xlsx">Tải
                                    File Mẫu</a>
                            </div>

                            <div class="col-md-4">
                                <form action="<?= base_url('admin/import_data/update_order_vtp') ?>"
                                      id="update_order_VTP"
                                      autocomplete="off" enctype="multipart/form-data" method="post"
                                      accept-charset="utf-8">
                                    <div class="form-group">
                                        <label for="company" class="control-label">FILE Dữ liệu đơn hàng Viettel
                                            Post</label>
                                        <input type="file" id="file_order_vtp" name="file_order_vtp" class="form-control" value="">
                                    </div>
                                    <button class="btn btn-info btnfile_update_order" type="button"
                                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> Đang upload"
                                            onclick="fnUpdateOrderVTP()">
                                        Up dữ liệu danh sách đơn hàng
                                    </button>
                                    <h3 class="text-danger label_revenue_allocation" id="lable-error"></h3>
                                </form>
                                <a class="btn btn-default" href="http://spshd.com/system/assets/file_mau.txt">Tải
                                    File Mẫu</a>
                            </div>


                            <div class="col-md-4 div_tb_file text-danger mtop30"></div>
                            <div class="clearfix"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>

    function add_order_excel() {
        var form = $('#form_excel_orders');
        var file_data = $('input#file_order').prop('files');
        var form_data = new FormData();
        $.each(file_data, function (i, v) {
            form_data.append('file_order[]', v);
        })
        if (typeof (csrfData) != "undefined") {
            form_data.append('csrf_token_name', csrfData.hash);
        }
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                alert_float(data.alert_type, data.message);
                if (data.success) {
                    $('.label_file_order').html('THÀNH CÔNG');
                } else {
                    $('.label_file_order').html('KHÔNG THÀNH CÔNG');
                }
                $('.btnfile_order').button('reset');
            }
        });
    }

    function add_delivery_orders_excel() {
        var form = $('#form_excel_delivery');
        var file_data = $('input#file_delivery_order').prop('files');
        var form_data = new FormData();
        $.each(file_data, function (i, v) {
            form_data.append('file_delivery_order[]', v);
        })
        if (typeof (csrfData) != "undefined") {
            form_data.append('csrf_token_name', csrfData.hash);
        }
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                alert_float(data.alert_type, data.message);
                if (data.success) {
                    $('.label_file_delivery_order').html('THÀNH CÔNG');
                } else {
                    $('.label_file_delivery_order').html('KHÔNG THÀNH CÔNG');
                }
                $('.btnfile_delivery_order').button('reset');
            }
        });
    }

    function add_delivery_list() {
        var form = $('#form_excel_delivery_list');
        var file_data = $('input#file_delivery_list').prop('files');
        var form_data = new FormData();
        $.each(file_data, function (i, v) {
            form_data.append('file_delivery_list[]', v);
        })
        if (typeof (csrfData) != "undefined") {
            form_data.append('csrf_token_name', csrfData.hash);
        }
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                alert_float(data.alert_type, data.message);
                if (data.success) {
                    $('.label_file_delivery_list').html('THÀNH CÔNG');
                } else {
                    $('.label_file_delivery_list').html('KHÔNG THÀNH CÔNG');
                }
                $('.btnfile_delivery_list').button('reset');
            }
        });
    }

    function add_revenue_allocation() {
        var form = $('#revenue_allocation');
        var file_data = $('input#file_revenue_allocation').prop('files');
        var form_data = new FormData();
        $.each(file_data, function (i, v) {
            form_data.append('file_revenue_allocation[]', v);
        })
        if (typeof (csrfData) != "undefined") {
            form_data.append('csrf_token_name', csrfData.hash);
        }
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                alert_float(data.alert_type, data.message);
                if (data.success) {
                    $('.label_revenue_allocation').html('THÀNH CÔNG');
                } else {
                    $('.label_revenue_allocation').html('KHÔNG THÀNH CÔNG');
                }

                $('.btnfile_revenue_allocation').button('reset');
            }
        });
    }

    function fnUpdateOrderVTP() {
        var form = $('#update_order_VTP');
        var file_data = $('input#file_order_vtp').prop('files');
        var form_data = new FormData();
        $.each(file_data, function (i, v) {
            form_data.append('file_order_vtp[]', v);
        })
        if (typeof (csrfData) != "undefined") {
            form_data.append('csrf_token_name', csrfData.hash);
        }
        $.ajax({
            url: form.attr('action'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                alert_float(data.alert_type, data.message);
                if (data.success) {
                    $('#lable-error').html('THÀNH CÔNG');
                } else {
                    $('#lable-error').html('KHÔNG THÀNH CÔNG');
                }

                $('.btnfile_update_order').button('reset');
            }
        });
    }
</script>
</body>
</html>
