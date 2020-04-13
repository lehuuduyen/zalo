<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-orders_shop th, .table-orders_shop td { white-space: nowrap; }
</style>
<div id="wrapper">
    <div class="panel_s mbot10 H_scroll" id="H_scroll">
        <div class="panel-body _buttons">
            <a href="<?=admin_url('import_data')?>" class="btn btn-info pull-left H_action_button">
                <i class="lnr lnr-plus-circle" aria-hidden="true"></i>
                Import excel
            </a>
            <div class="line-sp"></div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">

                        <?php
                            $data_end = date('Y-m-d');
                            $data_start  = date("Y-m-d", strtotime("$data_end -1 month"));
                        ?>
                        <div class="col-md-3">
                            <?php echo render_date_input('create_start', 'Ngày tạo từ', _d($data_start));?>
                        </div>
                        <div class="col-md-3">
                            <?php echo render_date_input('create_end', 'Ngày tạo đến', _d($data_end) );?>
                        </div>
                        <div class="col-md-3">
                            <?php echo render_date_input('collection_start', 'Ngày thu tiền từ');?>
                        </div>
                        <div class="col-md-3">
                            <?php echo render_date_input('collection_end', 'Ngày thu tiền đến');?>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-md-3">
                            <?php echo render_date_input('control_start', 'Ngày đối soát từ');?>
                        </div>
                        <div class="col-md-3">
                            <?php echo render_date_input('control_end', 'Ngàu đối soát đến');?>
                        </div>
                        <div class="col-md-3">
                            <?php echo render_select('shop', $shop, ['shop', 'shop'], 'Cửa hàng');?>
                        </div>
                        <div class="col-md-3">
                            <?php echo render_select('status', $status_orders, ['status', 'status'], 'Trạng thái');?>
<!--                            --><?php //echo render_input('status', 'Trạng thái');?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <?php echo render_select('city_send', $city_send, ['city_send', 'city_send'], 'Tỉnh gửi');?>
<!--                            --><?php //echo render_input('city_send', 'Tỉnh gửi');?>
                        </div>
                        <div class="col-md-3">
                            <?php echo render_select('city', $city, ['city', 'city'], 'Tỉnh nhận');?>
<!--                            --><?php //echo render_input('city', 'Tỉnh nhận');?>
                        </div>
                        <div class="col-md-3">
                            <?php echo render_select('deliver', $deliver, ['deliver', 'deliver'], 'Người giao');?>
<!--                            --><?php //echo render_input('deliver', 'Người giao');?>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <?php echo render_date_input('date_debits_start', 'Doanh thu từ ngày');?>
                        </div>
                        <div class="col-md-3">
                            <?php echo render_date_input('date_debits_end', 'Doanh thu đến ngày');?>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info mtop25" onclick="filterData()">Lọc dữ liệu</button>
                        </div>
                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('#'),
                            _l('Cửa hàng'),
                            _l('mã đối soát'),
                            _l('mã đơn khách hàng'),
                            _l('mã đơn supership'),
                            _l('Trạng thái'),
                            _l('ngày tạo'),
                            _l('khối lượng'),
                            _l('thu hộ'),
                            _l('trị giá'),
                            _l('trả trước'),
                            _l('phí vận chuyển'),
                            _l('phí bảo hiểm'),
                            _l('phí chuyển hoàn'),
                            _l('khuyến mãi'),
                            _l('gói cước'),
                            _l('người trả phí'),
                            _l('người nhận'),
                            _l('số điện thoại'),
                            _l('địa chỉ'),
                            _l('phường xã'),
                            _l('quận huyện'),
                            _l('thành phố'),
                            _l('ghi chú'),
                            _l('kho hàng'),
                            _l('sản phẩm'),
                            _l('ngày đối soát'),
                            _l('loại'),
                            _l('tỉnh thành gửi'),
//                            _l('phí hải dương'),
                            _l('mã giao hàng'),
//                            _l('trạng thái giao hàng'),
                            _l('người giao'),
                            _l('Thu hộ báo cáo'),
                            _l('Thu bởi'),
                            _l('Thu lúc'),
                            _l('Phí Hải Dương'),
                            _l('Doanh thu tổng tính'),
                            _l('Doanh thu thực'),
                            _l('Ngày tính công nợ'),
                            _l('Chi Nhánh Lấy')
                        ),'orders_shop table-bordered'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>

    var filterList = {
        'create_start' : '[id="create_start"]',
        'create_end' : '[id="create_end"]',
        'collection_start' : '[id="collection_start"]',
        'collection_end' : '[id="collection_end"]',
        'control_start' : '[id="control_start"]',
        'control_end' : '[id="control_end"]',
        'shop' : '[id="shop"]',
        'status' : '[id="status"]',
        'city_send' : '[id="city_send"]',
        'city' :'[id="city"]',
        'deliver':'[id="deliver"]',
        'date_debits_end':'[id="date_debits_end"]',
        'date_debits_start':'[id="date_debits_start"]'
    };
    app.options.tables_pagination_limit = "15";
    $(function(){
        initDataTable('.table-orders_shop', admin_url+'import_data/table', [0], [0], filterList, [6, 'desc']);
    });

    function filterData()
    {
        if($('.table-orders_shop').hasClass('dataTable'))
        {
            $('.table-orders_shop').DataTable().ajax.reload();
        }
    }
    $('body').on('change', '#collection_start, #collection_end', function(e){
        $('#create_start').val('');
        $('#create_end').val('');
    })
    $('body').on('change', '#create_start, #create_end', function(e){
        $('#collection_start').val('');
        $('#collection_end').val('');

        $('#date_debits_start').val('');
        $('#date_debits_end').val('');
    })

    $('body').on('change', '#date_debits_start, #date_debits_end', function(e){
        $('#create_start').val('');
        $('#create_end').val('');
    })
</script>

</body>
</html>
