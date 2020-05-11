
    <div id="genernal-receivables-suppliers-debts-report" class="">
      <div class="row">
          <div class="col-md-4" style="text-align: center;">
              <div class="panel_s">
                  <div class="panel-body">
                      <h3 class="text-muted total_type_supplier_1"></h3>
                      <span class="text-info">Tổng NCC nợ</span>
                  </div>
              </div>
          </div>
          <div class="col-md-4" style="text-align: center;">
              <div class="panel_s">
                  <div class="panel-body">
                      <h3 class="text-muted total_type_supplier_0"></h3>
                      <span class="text-danger">Tổng Nợ NCC</span>
                  </div>
              </div>
          </div>
          <div class="col-md-4" style="text-align: center;">
              <div class="panel_s">
                  <div class="panel-body">
                      <h3 class="text-muted  total_type_supplier_2"></h3>
                      <span class="text-warning">Đối trừ công nợ </span>
                  </div>
              </div>
          </div>
      </div>
          <div class="col-md-2">
              <?php  echo render_select('id_supplier',$suppliers,array('userid','company'),'Nhà cung cấp');?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_start_supplier','Ngày bắt đầu',_d($date_start));?>
          </div>
          <div class="col-md-2">
              <?php  echo render_date_input('date_end_supplier','Ngày kết thúc',_d($date_end));?>
          </div>
          <div class="col-md-2">
              <?php  echo render_select('id_rows_supplier',array(array('id'=>'1','name'=>'Dương'),array('id'=>'2','name'=>'Âm'),array('id'=>'3','name'=>'Hiện ra tất cả dự liệu')),array('id','name'),'Lọc giá trị');?>
          </div>

          <div class="col-md-2">
              <button class="btn btn-info mtop25" type="button" onclick="load_table_supper()">Load danh sách</button>
          </div>
         <div class="clearfix"></div>

      <table class="table table table-striped table-genernal-receivables-suppliers-debts-report">
         <thead>
            <tr>
               <th><?php echo _l('suppliers_code'); ?></th>
               <th><?php echo _l('suppliers_name'); ?></th>
               <th>SỐ DƯ ĐẦU KỲ</th>
               <th>SỐ PHÁT GIẢM</th>
               <th>SỐ PHÁT TĂNG</th>
               <th>CÒN NỢ</th>
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
    <div id="detail_suppliers" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="min-width: 90%;height:90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title title_debits">Chi tiết công nợ</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-6">
                        <?php
                            $end = date('Y-m-d');
                            $date = new DateTime($end);;
                            date_sub($date, date_interval_create_from_date_string('30 days'));
                            $start = date_format($date, 'Y-m-d');
                        ?>
                        <?php echo render_date_input('start_detail_supplier','Ngày từ',_d($start))?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_date_input('end_detail_supplier','Đến Ngày',_d($end))?>
                        <?php echo render_input('id_suppliers_detail','','','hidden')?>
                    </div>
                    <div class="row div_total_stock_products">
                        <div class="col-lg-4 col-xs-12 col-md-12">
                            <div class="panel_s">
                                <div class="panel-body text-center">
                                    <h3 class="text-muted total_stock_products"></h3>
                                    <span class="text-warning">Số Công Nợ Trừ Tồn Kho</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-12 col-md-12">
                            <div class="panel_s">
                                <div class="panel-body text-center">
                                    <h3 class="text-muted total_three_products"></h3>
                                    <span class="text-warning">Tiền xuất kho 7 ngày gần nhất</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-12 col-md-12">
                            <div class="panel_s">
                                <div class="panel-body text-center">
                                    <h3 class="text-muted total_pay"></h3>
                                    <span class="text-warning">Tổng số tiền phải thanh toán</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table table-striped table-detail_suppliers_debts">
                        <thead>
                        <tr>
                            <th style="width:8%;">NGÀY</th>
                            <th style="width:15%;">LOẠI</th>
                            <th style="width:13%;">MÃ PHIẾU</th>
                            <th style="width:8%;text-align:center;">TÊN SP</th>
                            <th style="width:8%;">SỐ LƯỢNG</th>
                            <th style="width:8%;">ĐƠN GIÁ</th>
                            <th style="width:8%;">PS GIẢM</th>
                            <th style="width:8%;">PS TĂNG</th>
                            <th style="width:10%;">CÒN NỢ</th>
                            <th style="width:13%;max-width:13%;">GHI CHÚ</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
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
    <div id="debt_suppliers" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <form action="<?=admin_url('reports/update_suppliers')?>" method="post" id="update_supplier">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title title_debt_suppliers">Form nhập dư đầu kỳ Công Nợ nhà cung cấp</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-4" style="border-right: 1px solid blue;">
                            <?php echo render_select('id_supplier',$suppliers,array('userid','company'),'Nhà cung cấp'); ?>
                            <?php echo render_select('type_supplier',$type,array('id','name'),'Loại công nợ'); ?>
                            <div class="hide">
                                <?php echo render_input('debt_supplier','Số lượng đầu kì')?>
                            </div>

                            <?php echo render_date_input('debit_date','Nhập ngày nhắc')?>
                            <?php echo render_textarea('note','Nội dung nhắc')?>
                        </div>
                        <div class="col-md-8" style="border-left: 1px solid blue;margin-left: -1px;">
                            <fieldset>
                                <legend>Lịch sử gọi</legend>
                                <button class="btn btn-info mbot20 mleft10" type="button" onclick="add_history_supplier()">ADD</button>
                                <div class="history_supplier_add"></div>
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
