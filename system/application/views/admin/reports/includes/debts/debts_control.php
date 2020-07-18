    <div id="debts_control" class="">
        <div class="row">
            <div class="col-md-3">
                <?php
                echo render_date_input('date_start_control', 'Ngày bắt đầu', _d($date_start));
                ?>
            </div>
            <div class="col-md-3">
                <?php
                echo render_date_input('date_end_control', 'Ngày kết thúc', _d($date_end));
                ?>
            </div>
            <div class="col-md-3"><button class="btn btn-info mtop25" onclick="SearchTable('.table-report_control')">Tìm kiếm</button></div>
            <div class="clearfix"></div>
            <?php render_datatable(array(
                'Ngày tính nợ',
                'Mã đơn hàng',
                'Shop',
                'Nội dung',
                'PS tăng',
                'PS Giảm',
                'SPS Việt Nam Nợ'
            ),'report_control'); ?>
        </div>
   </div>

