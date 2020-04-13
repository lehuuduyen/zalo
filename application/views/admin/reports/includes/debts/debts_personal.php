<div id="debts_personal" class="">
    <div class="row">
        <div class="row">
            <div class="col-md-4" style="text-align: center;">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="text-muted total_type_1"></h3>
                        <span class="text-info">Tổng cho vay</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4" style="text-align: center;">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="text-muted total_type_0"></h3>
                        <span class="text-danger">Tổng đi vay</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4" style="text-align: center;">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="text-muted  total_type_2"></h3>
                        <span class="text-warning">Tổng đuối trừ</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-2">
            <?php  echo render_select('id_other_object',$other_object,array('id','name'),'Cá nhân');?>
        </div>
        <div class="col-md-2">
            <?php  echo render_date_input('date_start_personal','Ngày bắt đầu',_d($date_start));?>
        </div>
        <div class="col-md-2">
            <?php  echo render_date_input('date_end_personal','Ngày kết thúc',_d($date_end));?>
        </div>
        <div class="col-md-2">
            <?php  echo render_select('id_rows_personal',array(array('id'=>'1','name'=>'Dương'),array('id'=>'2','name'=>'Âm'),array('id'=>'3','name'=>'Hiển thị tất cả')),array('id','name'),'Lọc giá trị');?>
        </div>
        <div class="col-md-2">
            <button class="btn btn-info mtop25" type="button" onclick="load_table_personal()">Load danh sách</button>
        </div>

    </div>
    <table class="table table table-striped table-debts_personal">
        <thead>
        <tr>
            <th><?php echo _l('TÊN'); ?></th>
            <th><?php echo _l('Đối tượng'); ?></th>
            <th>SỐ TIỀN VAY MƯỢN TRƯỚC ĐÓ</th>
            <th>SỐ TIỀN PHÁT SINH GIẢM</th>
            <th>SỐ TIỀN PHÁT SINH TĂNG</th>
            <th>SỐ TIỀN VAY MƯỢN HIỆN TẠI</th>
            <th>THUỘC TÍNH</th>
        </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>
    </table>
</div>
<div id="detail_personal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="min-width: 90%;height:90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title_debits_personal">Chi tiết công nợ</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-6">
                    <?php
                    $end = date('Y-m-d');
                    $date = new DateTime($end);;
                    date_sub($date, date_interval_create_from_date_string('30 days'));
                    $start = date_format($date, 'Y-m-d');
                    ?>
                    <?php echo render_date_input('start_detail_personal','Ngày từ',_d($start))?>
                </div>
                <div class="col-md-6">
                    <?php echo render_date_input('end_detail_personal','Đến Ngày',_d($end))?>
                    <?php echo render_input('id_object_detail','','','hidden')?>
                    <?php echo render_input('staff_id_detail','','','hidden')?>
                </div>
                <div class="clearfix"></div>
                <table class="table table table-striped table-debts_detail_personal">
                    <thead>
                    <tr>
                        <th>NGÀY</th>
                        <th>LOẠI</th>
                        <th>MÃ PHIẾU</th>
                        <th>PS GIẢM</th>
                        <th>PS TĂNG</th>
                        <th>CÒN NỢ</th>
                        <th>GHI CHÚ</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer" style="padding: 6px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>




<div id="debt_other_object_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form action="<?=admin_url('reports/update_other_object')?>" method="post" id="update_other_object">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title title_debt_porters">Form nhập dư đầu kỳ Đối tượng vay mượn</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-4" style="border-right: 1px solid blue;">
                        <?php echo render_select('id_other_object',$other_object,array('id','name'),'Đối tượng vay mượn'); ?>
                        <?php echo render_select('type_other_object',$type,array('id','name'),'Loại công nợ'); ?>
                        <div class="hide">
                            <?php echo render_input('debt_other_object','Công nợ đầu kì')?>
                        </div>
                        <?php echo render_date_input('debit_date','Nhập ngày nhắc')?>
                        <?php echo render_textarea('note','Nội dung nhắc')?>
                    </div>
                    <div class="col-md-8" style="border-left: 1px solid blue;margin-left: -1px;">
                        <fieldset>
                            <legend>Lịch sử gọi</legend>
                            <button class="btn btn-info mbot20 mleft10" type="button" onclick="add_history_object()">ADD</button>
                            <div class="history_object_add"></div>
                        </fieldset>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Lưu</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
