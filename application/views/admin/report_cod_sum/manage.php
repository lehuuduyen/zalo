<?php init_head(); ?>

<style>
    .report_cod_sum tr th:nth-child(3), .report_cod_sum tr td:nth-child(3)
    {
        max-width: 500px;
        white-space: inherit;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-md-3">
                            <?php
                                echo render_date_input('date_start', 'Ngày bắt đầu', _d($date_start));
                            ?>
                        </div>
                        <div class="col-md-3">
                            <?php
                                echo render_date_input('date_end', 'Ngày kết thúc', _d($date_end));
                            ?>
                        </div>
                        <div class="col-md-3"><button class="btn btn-info mtop25" onclick="SearchTable()">Tìm kiếm</button></div>
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
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>

    var filterList = {
        'date_start' : '[id="date_start"]',
        'date_end' : '[id="date_end"]',
    };

    $(function(){
        initDataTable('.table-report_cod_sum', admin_url+'report_cod_sum/table', [0,1,2,3,4,5], [0,1,2,3,4,5], filterList);
    });
    function SearchTable()
    {
        if($('.table-report_cod_sum').hasClass('dataTable'))
        {
            $('.table-report_cod_sum').DataTable().ajax.reload();
        }
    }




</script>
</body>
</html>
