<?php init_head(); ?>

<style>
    table.table thead tr th{
        color: #000000!important;
        font-weight: 500;
    }
</style>

<style type="text/css">
    .textR{
        color: red;
        font-weight: bold;
    }
    .textB{
        color: blue;
        font-weight: bold;
    }
    .textG{
        color: green;
        font-weight: bold;
    }
    .title{
        font-weight: bold;
        font-style: italic;
    }
    .table-diaries-report tr td:nth-child(12){
        max-width: 300px;
        white-space: inherit;
        min-width: 300px;
    }
    .table-detailed-sales-contract-report tr td:nth-child(5){
        max-width: 200px;
        white-space: inherit;
        min-width: 200px;
    }
    .table-general-order-tracking-book-PO-report tr td:nth-child(3){
        max-width: 200px;
        white-space: inherit;
        min-width: 200px;
    }
    .table-order-tracking-book-PO-report tr td:nth-child(7){
        max-width: 200px;
        white-space: inherit;
        min-width: 200px;
    }
    .table-order-tracking-book-report tr td:nth-child(4){
        max-width: 200px;
        white-space: inherit;
        min-width: 200px;
    }
    .table-order-tracking-book-report tr td:nth-child(6){
        max-width: 200px;
        white-space: inherit;
        min-width: 200px;
    }
    .table-order-tracking-book-PO-report tr td:nth-child(5){
        max-width: 200px;
        white-space: inherit;
        min-width: 200px;
    }
    .table-general-order-tracking-book-report tr td:nth-child(4){
        max-width: 150px;
        white-space: inherit;
        min-width: 150px;
    }
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">
                        <button class="btn btn-info" type="button" onclick="add_attrition('')">Thêm Tài sản</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-md-3">
                            <?php echo render_date_input('datestart','Lọc tài sản từ ngày đến hết hạn')?>
                        </div>
<!--                        <div class="col-md-3">-->
<!--                            --><?php //echo render_date_input('dateend','Đến')?>
<!--                        </div>-->
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="pill" href="#having">Đang sử dụng</a></li>
                            <li><a data-toggle="pill" href="#not_having">Hết hạn khấu hao hoặc bán</a></li>
                        </ul>
                        <div class="tab-content">

                            <div id="having" class="tab-pane fade in active">
                                <h4>TÀI SẢN ĐANG SỬ DỤNG</h4>
                                <table class="table table-striped table-attrition">
                                    <thead>
                                        <tr>
                                            <th>TÊN SP</th>
                                            <th>NGÀY BẮT ĐẦU</th>
                                            <th>SỐ LƯỢNG</th>
                                            <th>GIÁ TRỊ BAN ĐẦU</th>
                                            <th>THỜI GIAN KHẤU HAO(THÁNG)</th>
                                            <th>SỐ TIỀN KHẤU HAO(THÁNG)</th>
                                            <th>TỔNG GIÁ TRỊ CÒN LẠI</th>
                                            <th>THUỘC TÍNH</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><p class="text-danger">Tổng:</p></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><p class="text-danger total_month"></p></th>
                                            <th><p class="text-danger total_money"></p></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div id="not_having" class="tab-pane fade">
                                <h4>HẾT KHẤU HAO HOẶC BÁN</h4>
                                <table class="table table-striped table-attrition_not_having">
                                    <thead>
                                        <tr>
                                            <th>TÊN SP</th>
                                            <th>NGÀY NHẬP</th>
                                            <th>SỐ LƯỢNG</th>
                                            <th>GIÁ TRỊ BAN ĐẦU</th>
                                            <th>THỜI GIAN KHẤU HAO(THÁNG)</th>
                                            <th>SỐ TIỀN KHẤU HAO(THÁNG)</th>
                                            <th>NỘI DUNG BÁN</th>
                                            <th>SỐ TIỀN BÁN</th>
                                            <th>NGƯỜI BÁN</th>
                                            <th>THUỘC TÍNH</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><p class="text-danger">Tổng:</p></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><p class="text-danger total_month_not_having"></p></th>
                                            <th><p class="text-danger total_money_not_having"></p></th>
                                            <th></th>
                                            <th><p class="total_price_buy text-danger"></p></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <hr />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_attrition" role="dialog">
    <?php echo form_open(admin_url('reports_revenue/add_attrition'), array('id'=>'form_attrition','method'=>'post')); ?>
<!--    <form action="--><?//=admin_url('reports_revenue/add_attrition')?><!--" method="post" id="form_attrition">-->
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title title-attrition"></h4>
                </div>
                <div class="modal-body">
                    <?php echo render_input('id','','','hidden')?>
                    <?php echo render_input('name','Tên tài sản')?>
                    <?php echo render_date_input('date','Ngày bắt đầu')?>
                    <?php echo render_input('quantity','Số lượng','','text',array('onkeyup'=>'formatNumBerKeyUp(this)'))?>
                    <?php echo render_input('price','Đơn giá','','text',array('onkeyup'=>'formatNumBerKeyUp(this)'))?>
                    <?php echo render_input('month','Thời gian khấu hao(Tháng)')?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Lưu</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>

        </div>
    </form>
</div>

<div id="update_status" class="modal fade" role="dialog">
<!--    <form action="--><?//=admin_url('reports_revenue/update_status')?><!--" method="post" id="form_update_status">-->
        <?php echo form_open(admin_url('reports_revenue/update_status'), array('id'=>'form_update_status','method'=>'post')); ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title title_status">Chuyến tài sản sang trạng thái bán</h4>
                </div>
                <div class="modal-body">
                    <input id="id_attrition" type="hidden" name="id_attrition" value="">
                    <div class="form-group">
                        <label for="price_buy" class="control-label"> <small class="req text-danger">* </small>Giá bán</label>
                        <input id="price_buy" name="price_buy" class="form-control" onkeyup="formatNumBerKeyUp(this)">
                    </div>

                    <div class="form-group">
                        <label for="note_buy" class="control-label"> <small class="req text-danger">* </small>Nội dung bán</label>
                        <textarea name="note_buy" id="note_buy" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Lưu</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </form>
</div>


<?php init_tail(); ?>
<script>
    var true_attr=[];
    var colum_attr=[];
    var array_true=[];
    var filterList = {
        "datestart":"[name='datestart']",
        "dateend":"[name='dateend']"
    };
    $(function() {
        _validate_form($("#form_attrition"), {
            name: "required",
            date:"required",
            quantity:"required",
            price: "required",
            month: "required"
        }, manage_war_attrition)

        _validate_form($("#form_update_status"),
        {
            note_buy: "required"
        }, manage_war_attrition)
    });
    $('.table-attrition').on('draw.dt', function() {
        var invoiceReportsTable = $(this).DataTable();
        var attrition = invoiceReportsTable.ajax.json().attrition;
        $('.total_month').html((attrition.total_month));
        $('.total_money').html((attrition.total_money));
    })
    $('.table-attrition_not_having').on('draw.dt', function() {
        var invoiceReportsTable = $(this).DataTable();
        var attrition = invoiceReportsTable.ajax.json().attrition;
        $('.total_month_not_having').html((attrition.total_month));
        $('.total_money_not_having').html((attrition.total_money));
        $('.total_price_buy').html((attrition.total_price_buy));
    })

    function manage_war_attrition(form) {
        var data = $(form).serialize(),
            action = form.action;
        jQuery.ajax({
            type: "post",
            url:action,
            data: data,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                alert_float(response.alert_type,response.message);
                $('#modal_attrition').modal('hide');
                $('#update_status').modal('hide');
                $('.table-attrition').DataTable().ajax.reload();
                $('.table-attrition_not_having').DataTable().ajax.reload();
            }
        });
    }



    var tables_pagination_limit=[-1,"Tất cả"];
    function init_load_report(e,type,warehouse) {
        $('#type').val(type);
        $('#warehouse').val(warehouse);
        create_load_products(type);

    }
    initDataTable('.table-attrition', admin_url + 'reports_revenue/get_table_attrition/0', [], false, filterList, [1, 'desc']);
    initDataTable('.table-attrition_not_having', admin_url + 'reports_revenue/get_table_attrition/2', [], false, filterList, [1, 'desc']);
    $(function() {
        $.each(filterList, function (filterIndex, filterItem) {
            $(filterItem).on('change', function () {
                $('.table-attrition').DataTable().ajax.reload();
                $('.table-attrition_not_having').DataTable().ajax.reload();
            });
        });
    })


    function add_attrition(id="") {
        if(id=="")
        {
            $('.title-attrition').html('NHẬP TÀI SẢN CỐ ĐỊNH');
            $('#form_attrition')[0].reset();
            $('#id').val('');
        }
        else
        {
            $('.title-attrition').html('SỬA PHIẾU TÀI SẢN CỐ ĐỊNH');
            $.get(admin_url + 'reports_revenue/add_attrition/'+id).done(function(response) {
                var _json = JSON.parse(response);
                $('#id').val(_json.id);
                $('#name').val(_json.name);
                $('#date').val(_json.date);
                $('#month').val(_json.month);
                $('#quantity').val(formatNumber(_json.quantity));
                $('#price').val(formatNumber(_json.price));
            });
        }
        $('#modal_attrition').modal('show');
    }
    function formatNumber(nStr, decSeperate=".", groupSeperate=",") {
        nStr += '';
        x = nStr.split(decSeperate);
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
        }
        return x1 + x2;
    }


    function var_status(id)
    {
        dataString={id:id};
        if (typeof(csrfData) !== 'undefined') {
            dataString[csrfData['token_name']] = csrfData['hash'];
        }
        jQuery.ajax({
            type: "post",
            url:admin_url+'reports_revenue/update_status',
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $('.table-attrition').DataTable().ajax.reload();
                    $('.table-attrition_not_having').DataTable().ajax.reload();
                    alert_float('success', response.message);
                }
                else
                {
                    alert_float('danger', response.message);
                }
                return false;
            }
        });

    }

    function modal_status(id,type)
    {
        if(type=='edit')
        {
            $('#update_status').modal('show');
            $('#id_attrition').val(id);
            $('.title_status').html('Sửa nội dung và giá tiền phiếu xuất');
            // if (typeof(csrfData) !== 'undefined') {
            //     dataString[csrfData['token_name']] = csrfData['hash'];
            // }

            $.get(admin_url + 'reports_revenue/add_attrition/'+id).done(function(response) {
                var _json = JSON.parse(response);
                $('#price_buy').val(formatNumber(_json.price_buy));
                console.log(_json.note_buy)
                if(_json.note_buy!=NULL)
                {
                    $('#note_buy').val((_json.note_buy));
                }
                else
                {
                    $('#note_buy').val('');
                }
            })
        }
        else
        {
            $('#update_status').modal('show');
            $('#id_attrition').val(id);
            $('#price_buy').val('');
            $('#note_buy').val('');
            $('.title_status').html('Chuyến tài sản sang trạng thái bán');
        }
    }




</script>
</body>
</html>
