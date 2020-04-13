<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">

                        <?php render_datatable(array(
                            _l('id'),
                            _l('Tên'),
                            _l('CMND'),
                            _l('Số điện thoại'),
                            _l('Địa chỉ'),
                            _l('Người tạo'),
                            _l('Số dư đầu kỳ'),
                            _l('options'),
                            _l('options'),
                            _l('options').
                            _l('options'),
                            _l('options'),
                            _l('options'),
                            _l('options')
                        ),'cash_book1'); ?>
                        <div class="clearfix"></div>
<!--                        --><?php //render_datatable(array(
//                            _l('id'),
//                            _l('Tên'),
//                            _l('CMND'),
//                            _l('Số điện thoại'),
//                            _l('Địa chỉ'),
//                            _l('Người tạo'),
//                            _l('Số dư đầu kỳ'),
//                            _l('options')
//                        ),'other'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    initDataTable('.table-cash_book1', window.location.href, [], [], {}, []);
    // initDataTable('.table-other', admin_url+'other_object', [1], [1]);
</script>

