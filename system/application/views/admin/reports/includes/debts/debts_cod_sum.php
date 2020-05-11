    <div id="report_cod_sum" class="">
        <div class="row">
            <div class="col-md-3">
                <?php
                    echo render_date_input('date_start_code_sum', 'Ngày bắt đầu', _d($date_start));
                ?>
            </div>
            <div class="col-md-3">
                <?php
                    echo render_date_input('date_end_code_sum', 'Ngày kết thúc', _d($date_end));
                ?>
            </div>
            <div class="col-md-3"><button class="btn btn-info mtop25" onclick="SearchTable('.table-report_cod_sum')">Tìm kiếm</button></div>
            <div class="clearfix"></div>
            <?php render_datatable(array(
                'Ngày',
                'Mã',
                'Nội dung',
                'PS tăng',
                'PS Giảm',
                'SPS Việt Nam Nợ'
            ),'report_cod_sum'); ?>
        </div>
   </div>

