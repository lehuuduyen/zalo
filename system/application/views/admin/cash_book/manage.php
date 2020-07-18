<?php init_head(); ?>
<style>
    @media (min-width: 992px) {
        .col-md-2-2 {
            width: 14% !important;
        }
    }
    .point-ev{
        pointer-events: none;
    }
.menu-item-bookcash{ padding: 10px;
    border: 2px dashed #000;
    color: #000;
    text-transform: uppercase;
    padding: 12px 20px 12px 16px;
    font-size: 13px;
    text-align: center;
    font-weight: bold;
 }
 .menu-item-bookcash a:hover{
    cursor: pointer;
 }
 .totalcashbook{margin-bottom: 10px !important;}
.totalcashbook span {text-align: center !important;}
}
.width200{
    width: 200px;
}
.bg-info {
    background-color: #d9edf7!important;
}
.table-cash_book tr td{
    white-space: pre-line;
    word-break: break-word;
    vertical-align: middle;
}
.table-cash_book tr td:nth-child(4){
    min-width: 150px;
}
.table-cash_book tr td:nth-child(1),.table-cash_book tr td:nth-child(2),.table-cash_book tr td:nth-child(3),.table-cash_book tr td:nth-child(5),.table-cash_book tr td:nth-child(6),.table-cash_book tr td:nth-child(7){
    white-space: nowrap;
}

.text-elip {
  white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 80%;
}

    .table-cash_book{
        font-size: 12px!important;
    }
    .table>tbody>tr>td, .table>tfoot>tr>td, .table>thead>tr>th {
        padding: 9px 5px 5px 5px!important;
    }


</style>
<link rel="stylesheet" href="/system/assets/css/cash_book.css">
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                    <div class="panel_s">
                        <div class="panel-body _buttons">
                        <h4 class="bold no-margin"><?=_l('Sổ quỹ')?></h4>
                        <hr class="no-mbot no-border">
                            <?php
                                if(has_permission('cash_book','','create')){
                            ?>
                            <a href="#" onclick="detail_group('')" class="btn btn-info pull-left mright5 display-block"><?php echo _l('Thêm phiếu'); ?></a>
                            <?php } ?>
                            <a href="#" onclick="inventory_money('')" class="btn btn-success pull-left mright5 display-block"><?php echo _l('Kiểm kê tiền mặt'); ?></a>
                            <?php if(!empty($is_admin)){?>
                            <a href="<?=admin_url('paymentmodes')?>" class="btn btn-info pull-left mright5 display-block"><?php echo _l('Khai Báo Loại Quỹ'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">
                    <input type="hidden" id="filterStatus" value="" />
                    <div data-toggle="btn" class="btn-group mbot15 mobile-custom-btn">
                        <button style=" font-size: 11px;" type="button" id="btnDatatableFilterAll" data-toggle="tab" class="btn btn-info active">Tất cả</button>

                        <?php
                        $list_not_payactive = array();
                        foreach($payments_modes as $value){
                            if(!empty($value['active'])){?>
                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilter<?php echo $value['id']?>" data-toggle="tab" class="btn btn-info"><?php echo $value['name']?></button>
                            <?php } else {
                                $list_not_payactive[] = $value['id'];
                            }?>
                        <?php } ?>

                    </div>
                    <div>
                        <div class="col-md-8">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type_search" class="control-label">Loại phiếu</label>
                                    <select id="type_search" name="type_search" class="selectpicker" data-width="100%" data-none-selected-text="Không có gì được chọn" data-live-search="true" tabindex="-98">
                                        <option></option>
                                        <option value="0">Phiếu thu</option>
                                        <option value="1">Phiếu chi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <?php $array_object_search=array(
                                    array('id'  => 'null',  'name'  =>  'Khác'),
                                    array('id'  =>  'tblstaff', 'name'  =>  'nhân viên'),
                                    array('id'  =>  'tblcustomers',   'name'  =>  'Khách hàng'),
                                    array('id'  =>  'tblother_object',  'name'  =>  'Vay-Mượn')

                                );?>
                                <?php echo render_select('object_search', $array_object_search, array('id','name'), 'Đối tượng');?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_select('staff_id_search', array(), array('id','name'), 'Danh sách đối tượng');?>
                            </div>
                            <div class="col-md-3">
                                <?php echo render_select('groups_search', $group_cash_book, array('id','name'), 'Nhóm quỷ');?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-6">
                                <?php echo render_date_input('date_start', 'Ngày bắt đầu', _d($date_start));?>
                            </div>
                            <div class="col-md-6">
                                <?php echo render_date_input('date_end', 'Ngày kết thúc', _d($date_end));?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <?php  foreach ($payments_modes as $key=>$value){?>
                            <div class="col-md-2 <?=empty($value['active'])?'hide not_active' : ''?>" style="text-align: center;">
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <h5  class="text-muted _total total_payments_<?=$value['id']?>" style="font-size: 15px">0</h5>
                                        <span class="text-success text-center"><?=$value['name'].($value['selected_by_default']==1?'(Không tổng)':'')?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-2 col-md-2-2" style="text-align: center;">
                            <div class="panel_s">
                                <div class="panel-body">
                                    <h5 class="text-muted _total totalcashbook" style="font-size: 15px">0</h5>
                                    <span class="text-danger"  >TỔNG TỒN</span>
                                </div>
                            </div>
                        </div>
                    </div>
                        <table class="table table-striped table-bordered table-cash_book no-footer dtr-inline" role="grid">
                            <thead>
                                <tr>
                                    <th rowspan="2">#</th>
                                    <th rowspan="2">Ngày tháng</th>
                                    <th colspan="2">Chứng từ</th>
                                    <th rowspan="2">Nội dung</th>
                                    <th rowspan="2">Thu</th>
                                    <th rowspan="2">Chi</th>
                                    <th rowspan="2">Tồn quỹ</th>
                                    <th rowspan="2">Đối tượng</th>
                                    <th rowspan="2">Nhóm quỹ</th>
                                    <th rowspan="2">Tài khoản</th>
                                    <th rowspan="2">Người tạo</th>
                                    <th rowspan="2">Thuộc tính</th>
                                </tr>
                                <tr>
                                    <th>Thu</th>
                                    <th>Chi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="modal_cash_book" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form action="<?=admin_url('cash_book/order_detail')?>" id="form_cash_book">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thêm phiếu</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                         <div class="col-md-12">
                           <input type="hidden" id="staus_id" name="staus_id">
                            <div class="col-md-6">
                                    <input type="radio" style="width: 17px;height: 17px" id="type_no" name="type" value="0" >
                                    <label for="type_no">Phiếu thu</label>
                                    <input type="radio" style="width: 17px;height: 17px" id="type_yes" name="type" value="1">
                                    <label for="type_no">Phiếu chi</label>

                                    <div class="total-cover-append-html-group-8">

                                    </div>
                                <div class="form-group ">
                                    <label for="code" class="control-label">Số phiếu</label>
                                    <div class="input-group">
                                        <input type="text" value="" name="code" id="code" readonly class="form-control">
                                        <span class="input-group-addon">
                                            <i class="fa fa-barcode calendar-icon"></i>
                                        </span>
                                    </div>
                                </div>


                                <?php echo render_date_input('date','Ngày Thanh Toán',_d(date('Y-m-d'))) ?>
                                <?php echo render_date_input('date_control','Ngày đối soát',_d(date('Y-m-d'))) ?>
                                <?php echo render_textarea('note','Nội dung') ?>
                                <div id="fee_get_shiper"></div>
                                <div class="id_bill hide">
                                    <div class="text-danger"><h4 class="total_bill"></h4></div>
                                    <div class="text-danger"><h4 class="old_debt"></h4></div>
                                    <div class="text-danger"><h4 class="total_go"></h4></div>
                                </div>
                            </div>

                             <div class="col-md-6" >
                                 <?php echo render_input('price','Tiền','','text',array('onkeyup'=>'formatNumBerKeyUp(this)')); ?>
                                 <?php $array_object=array(
                                         array('id'=>'','name'=>'Khác'),
                                         array('id'=>'tblstaff','name'=>'nhân viên'),
                                         array('id'=>'tblcustomers','name'=>'Khách hàng'),
                                         array('id'=>'tblsuppliers','name'=>'Nhà cung cấp'),
                                         array('id'=>'tblracks','name'=>'Lái xe'),
                                         array('id'=>'tblporters','name'=>'Bốc vác'),
                                         array('id'=>'tblother_object','name'=>'Vay-Mượn')
                                 );?>
                                <div class="form-group div_object">
                                    <label for="id_object" class="control-label">Đối tượng</label>
                                    <select id="id_object" name="id_object" class="selectpicker" data-width="100%" data-none-selected-text="Không có gì được chọn" data-live-search="true" tabindex="-98">
                                        <?php foreach ($array_object as $key=>$value){?>
                                            <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                        <?php }?>
                                    </select>
                                </div>
<!--                                --><?php //echo render_select('id_object',$array_object,array('id','name'),'Đối tượng')?>

                                 <div class="staff_not div_object"><?php echo render_input('staff_id_not','Thông tin đối tượng')?></div>
                                 <div class="staff_yes div_object"><?php echo render_select('staff_id',$staff,array('staffid', ['lastname', 'firstname']),'Danh sách đối tượng');?></div>

                                 <div class="id_contract_borrowing hide">
                                    <?php echo render_select('id_contract_borrowing',array(),array(),'Hợp đồng vay-mượn','') ?>
                                 </div>
                                 <div id="div_groups" class="<?=!empty($is_admin)?'':'point-ev'?>">
                                    <?php echo render_select('groups',$group_cash_book,array('id','name'),'Nhóm quỹ');?>
                                 </div>
                                <?php echo render_select('payment_mode_id',$payments_modes,array('id','name'),'Tài khoản');?>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><?=_l('submit')?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>

    </div>
</div>


<div id="modal_cash_book_ward" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form action="<?=admin_url('cash_book/order_ward')?>" id="form_cash_book_ward">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Chuyển quỹ</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="well col-md-4">
                                    <?php echo render_select('payment_mode_to',$payments_modes,array('id','name'),'Tài khoản nguồn')?>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    $value=date('Y-m-d');
                                    echo render_date_input('date_ward','Ngày tháng',_d($value));
                                    ?>
                                    <div class="form-group">
                                        <label for="id_object_war" class="control-label">Đối tượng</label>
                                        <select id="id_object_war" name="id_object_war" class="selectpicker" data-width="100%" data-none-selected-text="Không có gì được chọn" data-live-search="true" tabindex="-98">
                                            <option value="">Khác</option>
                                            <option value="tblstaff">Nhân viên</option>
                                        </select>
                                    </div>
                                    <div class="staff_not"><?php echo render_input('staff_id_to_not','Thông tin đối tượng')?></div>
                                    <div class="staff_yes"><?php echo render_select('staff_id_to',$staff,array('staffid', ['lastname', 'firstname']),'Danh sách đối tượng');?></div>
                                    <?php echo render_input('price_war','Số tiền','');?>
                                </div>
                                <div class="well col-md-4">
                                    <?php echo render_select('payment_mode_from',$payments_modes,array('id','name'),'Tài khoản đích')?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php echo render_textarea('note_war','Nội dung') ?>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><?=_l('submit')?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div id="modal_inventory_money" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bảng kiểm kê tiền mặt</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Loại tiền</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>500,000</td>
                                        <td><input type="text" id="money_500" onkeyup="formatNumBerKeyUp_job(this)" class="input_money form-control width200"/></td>
                                        <td><p class="p_input_money p_500"></p></td>
                                    </tr>
                                    <tr>
                                        <td>200,000</td>
                                        <td><input type="text" id="money_200" class="input_money form-control width200"/></td>
                                        <td><p class="p_input_money p_200"></p></td>
                                    </tr>
                                    <tr>
                                        <td>100,000</td>
                                        <td><input type="text" id="money_100" class="input_money form-control width200"/></td>
                                        <td><p class="p_input_money p_100"></p></td>
                                    </tr>
                                    <tr>
                                        <td>50,000</td>
                                        <td><input type="text" id="money_50" class="input_money form-control width200"/></td>
                                        <td><p class="p_input_money p_50"></p></td>
                                    </tr>
                                    <tr>
                                        <td>20,000</td>
                                        <td><input type="text" id="money_20" class="input_money form-control width200"/></td>
                                        <td><p class="p_input_money p_20"></p></td>
                                    </tr>
                                    <tr>
                                        <td>10,000</td>
                                        <td><input type="text" id="money_10" class="input_money form-control width200"/></td>
                                        <td><p class="p_input_money p_10"></p></td>
                                    </tr>
                                    <tr>
                                        <td>5,000</td>
                                        <td><input type="text" id="money_5" class="input_money form-control width200"/></td>
                                        <td><p class="p_input_money p_5"></p></td>
                                    </tr>
                                    <tr>
                                        <td>2,000</td>
                                        <td><input type="text" id="money_2" class="input_money form-control width200"/></td>
                                        <td><p class="p_input_money p_2"></p></td>
                                    </tr>
                                    <tr>
                                        <td>1,000</td>
                                        <td><input type="text" id="money_1" class="input_money form-control width200"/></td>
                                        <td><p class="p_input_money p_1"></p></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2"><h3>Tổng tiền phiếu</h3></th>
                                        <th><h3><p class="total_modal_inventory_money"></p></h3></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" onclick="create_inventory_money()">In Phiếu</button>
                    <button type="button" class="btn btn-warning" onclick="refresh_inventory_money()">Làm rổng phiếu</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

    </div>
</div>




<div id="detail_groups" class="modal fade" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nhóm quỹ</h4>
            </div>
            <div class="modal-body">
                <ul >

                    <div class="row">
                         <div class="col-md-12" style="height: 77px;">
                             <div class="col-md-3">
                                <li class="menu-item-bookcash">
                                    <a onclick="add_cash_book('','5')" aria-expanded="false">
                                        <i class="fa fa-money"></i>  TIỀN KHÁCH HÀNG - 01
                                    </a>
                                </li>
                            </div>
                            <div class="col-md-3">
                                Thu chi tiền của khách hàng (Cả tiền COD và tiền phí dịch vụ)
                            </div>


                            <div class="col-md-3">
                                <li class="menu-item-bookcash">
                                    <a onclick="add_cash_book('','1')"aria-expanded="false">
                                        <i class="fa fa-users"></i> CHẾ ĐỘ KHÁCH HÀNG - 02
                                    </a>
                                </li>
                            </div>
                            <div class="col-md-3">
                            </div>
                         </div>
                           <div class="col-md-12" style="height: 100px;">
                            <div class="col-md-3">
                                <li class="menu-item-bookcash">
                                    <a onclick="add_cash_book('','14')" aria-expanded="false">
                                        <i class="fa fa-users"></i> Tiền Tổng Đối Soát
                                    </a>
                                </li>
                            </div>
                            <div class="col-md-3">
                                Là tiền mà mỗi lần đối soát xong Tổng sẽ gửi về chi nhánh mình.
                            </div>
                           <div class="col-md-3">
                               <li class="menu-item-bookcash">
                                   <a onclick="add_cash_book('','8')" aria-expanded="false">
                                       <i class="fa fa-users"></i> VAY MƯỢN - 10
                                   </a>
                               </li>
                           </div>
                           <div class="col-md-3" >
                               Thu tiền Shiper, Chi tiền COD cho tổng hằng ngày, Thu, chi tiền các khoản liên quan đến vay mượn của cty như Cá nhân, Ngân Hàng… Không tính tiền lãi. Lãi tính vào chi phí

                           </div>
                           </div>
                             <div class="col-md-12" style="height: 77px;">
                                 <div class="col-md-3">
                                     <li class="menu-item-bookcash">
                                         <a onclick="add_cash_book('','13')" aria-expanded="false">
                                             <i class="fa fa-users"></i> TÀI SẢN CỐ ĐỊNH 5
                                         </a>

                                     </li>
                                 </div>
                                 <div class="col-md-3" >
                                     Các khoản mua bán tài sản cố định của công ty trị giá tổng đơn trên 5 triệu đồng
                                 </div>

                            <div class="col-md-3">
                                <li class="menu-item-bookcash">
                                    <a onclick="add_cash_book('','3')" aria-expanded="false">
                                        <i class="fa fa-users"></i> LƯƠNG - 06
                                    </a>
                                </li>
                            </div>
                              <div class="col-md-3">
                               Thu, chi tiền các khoản liên quan đến chi lương, Các hỗ trợ, thưởng nhân viên ngoài lương.
                            </div>
                             </div>
                             <div class="col-md-12" style="height: 77px;">
                                <div class="col-md-3">
                                    <li class="menu-item-bookcash">
                                        <a onclick="add_cash_book('','4')" aria-expanded="false">
                                            <i class="fa fa-users"></i> CHI PHÍ  CTY - 7
                                        </a>
                                    </li>
                                </div>
                                  <div class="col-md-3">
                                      Thu, chi tiền các khoản liên quan đến hạch toán chi phí: Hội họp, Sinh nhật, Lãi Ngân Hàng, Bảng Biểu, Văn Phòng Phẩm, Sửa chữa các thiết bị..v..v
                                 </div>
                                  <div class="col-md-3">
                                    <li class="menu-item-bookcash">
                                        <a onclick="add_cash_book('','12')" aria-expanded="false">
                                            <i class="fa fa-users"></i> Chi Ứng Lương -8
                                        </a>
                                    </li>
                                </div>
                                 <div class="col-md-3">
                                     Thu, Chi liên quan đến ứng lương nhân viên
                                </div>
                            </div>
                            <div class="col-md-12" style="height: 77px;">
                                <div class="col-md-3">
                                    <li class="menu-item-bookcash">
                                        <a onclick="add_cash_book('','2')" aria-expanded="false">
                                            <i class="fa fa-users"></i> NHẬP QUỸ- 09
                                        </a>
                                    </li>
                                </div>
                                 <div class="col-md-3">
                                     Thu chi các khoản nhập xuất quỹ
                                 </div>
                                <div class="col-md-3">
                                    <li class="menu-item-bookcash">
                                        <a onclick="add_cash_book('','11')" aria-expanded="false">
                                            <i class="fa fa-users"></i> Chuyển quỹ
                                        </a>
                                    </li>
                                </div>
                                <div class="col-md-3">
                                </div>
                            </div>
                            <div class="col-md-12" style="height: 77px;">
                                <div class="col-md-3">
                                    <li class="menu-item-bookcash">
                                        <a onclick="add_cash_book('','15')" aria-expanded="false">
                                            <i class="fa fa-users"></i> Tiền Doanh Thu
                                        </a>
                                    </li>
                                </div>
                                <div class="col-md-3"></div>
                                 <div class="col-md-3">
                                    <li class="menu-item-bookcash">
                                        <a onclick="add_cash_book('','16')" aria-expanded="false">
                                            <i class="fa fa-users"></i> SMAN NỘI BỘ
                                        </a>
                                    </li>
                                </div>
                                <div class="col-md-3"></div>
                            </div>

                    </div>

                </ul>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>

        </div>

    </div>
</div>

<?php init_tail(); ?>

<script type="text/javascript">
var paymode_not_active = <?=!empty($list_not_payactive) ? json_encode($list_not_payactive) : "[]"?>;
    $(function(){
         $('[data-toggle="btn"] .btn').on('click', function(){
            var $this = $(this);
            $this.parent().find('.active').removeClass('active');
            $this.addClass('active');
        });
        $('#btnDatatableFilterAll').click(function(){

            $('#filterStatus').val('');
            $('#filterStatus').change();
        });
         <?php foreach($payments_modes as $value){?>

            $('#btnDatatableFilter<?php echo $value['id']?>').click(function(){

                $('#filterStatus').val(<?php echo $value['id']?>);
                $('#filterStatus').change();
            });
         <?php }?>

        var filterList = {
            'filterStatus' : '[id="filterStatus"]',
            'type_search' : '[id="type_search"]',
            'object_search' : '[id="object_search"]',
            'groups_search' : '[id="groups_search"]',
            'date_start' : '[id="date_start"]',
            'date_end' : '[id="date_end"]',
            'staff_id_search' : '[id="staff_id_search"]'
        };
        initDataTable('.table-cash_book', window.location.href, [1,2,3,4,5,6,7,8,9,10,11,12], [1,2,3,4,5,6,7,8,9,10,11,12], filterList, [[1,'DESC'],[0,'DESC']]);
        $('.table-cash_book').DataTable().columns([0]).visible(false, false).columns.adjust();
         $.each(filterList, function(filterIndex, filterItem){
            $(filterItem).on('change',function()
            {
                $('.table-cash_book').DataTable().ajax.reload();
            });
        });
    });
    function update_status(id)
    {
        dataString={id:id};
        jQuery.ajax({
            type: "post",
            url:"<?=admin_url()?>cash_book/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
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

    $('body').on('click', '.delete-remind,.delete-reminder', function() {
        var r = confirm(app.lang.confirm_action_prompt);
        var table='.table-cash_book';
        if (r == false) {
            return false;
        } else {
            $.get($(this).attr('href'), function(response) {
                alert_float(response.alert_type, response.message);
                    if ($.fn.DataTable.isDataTable(table)) {
                        $('body').find(table).DataTable().ajax.reload();
                    }
            }, 'json');
        }
        return false;
    });

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

    function init_cash_code()
    {
        var type=$('[name="type"]:checked').val();
        var payment_mode_id=$('[name="payment_mode_id"]').val();
        $.post(admin_url+'cash_book/get_cash_code',{type:type,payment_id:payment_mode_id}).done(function(data){
                var data=JSON.parse(data);
                $('#code').val(data.code);
                $('.receipt-code').text(data.prefix);
                $('#note').focus();
        });
    }
    $('body').on('change','#payment_mode_id',function(e){
        if($('#form_cash_book').prop('action')=="<?=admin_url('cash_book/order_detail')?>") {
            init_cash_code();
        }
    });

    $(document).on('change','input[name="type"]',function(e) {
      if($('#form_cash_book').prop('action')=="<?=admin_url('cash_book/order_detail')?>")
      {
          init_cash_code();
      }

      //GET group id when name type is change
      var group_id = $('#form_cash_book').attr('data-group');
      if (group_id === '8') {
        //IF THIEU PHU
        if ($('input[name="type"]:checked').val() === '0') {

          var html = `
            <input style="width:17px;height:17px;" type="checkbox" name="get_money_shiper_total" id="get_money_shiper_total"/>
            <label for="get_money_shiper_total" style="color:red">Nếu là thu tiền của Shiper cho tổng thì tích vào đây:
            </label>
            <div class="select-code-shiper">

            </div>
          `;
          $( ".total-cover-append-html-group-8" ).empty();
          $( ".total-cover-append-html-group-8" ).append( html );
        }
        else { //PHIEU CHI
          $( ".total-cover-append-html-group-8" ).empty();
          $( "#fee_get_shiper" ).empty();
        }
      }



    });

    function formatdate(data) {
      var today = new Date(data);
      var dd = today.getDate();
      var mm = today.getMonth() + 1; //January is 0!

      var yyyy = today.getFullYear();
      if (dd < 10) {
        dd = '0' + dd;
      }
      if (mm < 10) {
        mm = '0' + mm;
      }
      return dd + '/' + mm + '/' + yyyy;
    }

    $(document).on('change','#get_money_shiper_total',function(e) {



      var val = $('#get_money_shiper_total').is(':checked');

      if (val) {
        // $('#staff_id').attr('disabled',true);
        $('#staff_id').selectpicker('refresh');
        if ($('#select_here').length) {
          $('#select_here').show();
          $('#fee_get_shiper').show();
        }else {
          $.ajax({
      			url: '/system/admin/cash_book/list_shiper_group_8',
      			method: 'GET',
      			success: function(data){

              var listShiperCode = JSON.parse(data);

              var html = '<div id="select_here" class="form-group"><label for="usr">Mã Giao Hàng Thu:</label><select multiple class="form-control" id="list_shiper">';
              // html += "<option value='null'> Chọn Mã Giao Hàng Thu </option>";
              for (var i = 0; i < listShiperCode.length; i++) {

                var value = JSON.stringify(listShiperCode[i]);

                var pos_ = listShiperCode[i].deliver.search('-') + 1;
                var deliver = listShiperCode[i].deliver.slice(pos_ , listShiperCode[i].deliver.length);

                var lengthCode = listShiperCode[i].code_delivery.length;
                var code_delivery = listShiperCode[i].code_delivery.slice(lengthCode - 3 , lengthCode);

                var date = formatdate(listShiperCode[i].date_create);


                html += `<option value='${value}'> ${deliver.trim()} - ***${code_delivery.trim()} - ${date.trim()} </option>`;

              }
              html += '</select></div> ';
              var html_fee = '<div id="fee_get_shiper"></div>'
              $('.select-code-shiper').empty();
              $('.select-code-shiper').append(html);
              $('#list_shiper').selectpicker({
                liveSearch:true,
                liveSearchNormalize:true,
                noneSelectedText:"Chọn Mã Giao Hàng Thu",
              });
      			},
      			error:function(e) {
      				console.log(e);
      			}
      		});
        }


        $('#staff_id option').addClass("hide");
        $('#staff_id').val(1);
        $('#staff_id option[value="1"]').removeClass('hide');
        $('#staff_id').selectpicker('refresh');


      }


      else {
        $('#staff_id option').removeClass('hide');
        $('#staff_id').val('');
        $('#staff_id').selectpicker('refresh');

        if ($('#select_here').length) {
          $('#select_here').hide();
          $('#fee_get_shiper').hide();
        }else {
          $('#fee_get_shiper').show();
        }
      }

    });


    $(document).on('change','#select_here select',function(e) {


      var value =  $('#select_here select').val();



      var data = 0;
      var stringDetail = '';
      var staus_id = '';
      for (var i = 0; i < value.length; i++) {



        var pos_ = JSON.parse(value[i]).deliver.search('-') + 1;

        var deliver = JSON.parse(value[i]).deliver.slice(pos_ , JSON.parse(value[i]).deliver.length);

        var code_delivery = JSON.parse(value[i]).code_delivery;

        var date = formatdate(JSON.parse(value[i]).date_create);
        if (i !== value.length - 1 ) {
          staus_id += JSON.parse(value[i]).id+',';
        }else {

          staus_id += JSON.parse(value[i]).id;
        }


        stringDetail += `${deliver.trim()} - ${code_delivery.trim()} - ${date.trim()} : ${ formatNumber(JSON.parse(value[i]).collect_report)} \n`;

        data += Number(JSON.parse(value[i]).collect_report);
      }

      $('#price').val(formatNumber(data));
      $('#note').val(stringDetail);
      $('#staus_id').val(staus_id);

    });





    function add_cash_book(id='', group_id='') {

      $('#form_cash_book').attr('data-group',group_id);
      $('.id_contract_borrowing').addClass('hide');
      $('.total-cover-append-html-group-8').empty();
      $('#staff_id').attr('disabled',false);
      $('#staff_id').selectpicker('refresh');
      $('#fee_get_shiper').empty();

        var id_object=$('#id_object').val();
        if(id_object != "")
        {
            $('#staff_id_not').removeAttr('required','true');
            $('#staff_id').attr('required','true');
        }
        else
        {
            $('#staff_id_not').attr('required','true');
            $('#staff_id').removeAttr('required');
        }

        $('#form_cash_book').find('button[type="submit"]').removeClass('hide');
        if(id == "")
        {
            $('#form_cash_book').find('.modal-body').removeClass('point-ev');
            $('#id_object').selectpicker('refresh');
            $('#id_object_war').val('').selectpicker('refresh').trigger('change');
            $('.div_object').removeClass('hide');
            if(group_id == '11')
            {
                $.each(paymode_not_active, function(i,v){
                    $('#payment_mode_to option[value="'+v+'"]').addClass('hide');
                    $('#payment_mode_from option[value="'+v+'"]').addClass('hide');
                })
                $('#modal_cash_book_ward').modal('show');
                $('#detail_groups').modal('hide');
                $('#payment_mode_to').val('').selectpicker('refresh');
                $('#payment_mode_from').val('').selectpicker('refresh');
                $('#staff_id_war').val('').selectpicker('refresh');
                $('#price_war').val('');
                $('#note_war').val('');
                $('#id_object_war').val('tblstaff').selectpicker('refresh').trigger('change');
                return;
            }

            $('.id_bill').addClass('hide');
            $('#id_bill').html('').selectpicker('refresh');
            $('#id_bill').removeAttr('required');

            $('#modal_cash_book').modal('show');
            $('#modal_cash_book #groups').val(group_id).selectpicker('refresh');
            $('#form_cash_book').prop('action','<?=admin_url('cash_book/order_detail')?>');
            $('#form_cash_book').attr('id_index','');
            $('#type_no').prop('checked',false);
            $('#type_yes').prop('checked',false);
            $('#price').val('');
            $('#note').val('');
            $.each(paymode_not_active, function(i,v){
                $('#payment_mode_id option[value="'+v+'"]').addClass('hide');
            })
            $('#payment_mode_id').val('').selectpicker('refresh');
            $('#id_object option[value=""]').removeClass('hide');
            $('#id_object option[value="tblstaff"]').removeClass('hide');
            $('#id_object option[value="tblcustomers"]').removeClass('hide');
            $('#id_object option[value="tblsuppliers"]').removeClass('hide');
            $('#id_object option[value="tblracks"]').removeClass('hide');
            $('#id_object option[value="tblporters"]').removeClass('hide');
            $('#id_object option[value="tblother_object"]').removeClass('hide');
            $('#date').val('<?=_d(date('Y-m-d'));?>');
            $('#date_control').val('<?=_d(date('Y-m-d'));?>');

            $('#id_object').selectpicker('refresh').trigger('change');


            // if(group_id=='5')
            // {
            //     $('#id_object option').attr('class','hide');
            //     $('#id_object option[value="tblclients"]').removeClass('hide');
            //     $('#id_object option[value="tblstaff"]').removeClass('hide');
            //     $('#id_object').val('tblclients').selectpicker('refresh').trigger('change');
            //
            // }
            if(group_id=='7')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value="tblsuppliers"]').removeClass('hide');
                $('#id_object').val('tblsuppliers').selectpicker('refresh').trigger('change');
            }
            if(group_id=='6')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value="tblracks"]').removeClass('hide');
                $('#id_object').val('tblracks').selectpicker('refresh').trigger('change');
            }
            if(group_id=='3' || group_id=='16')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value="tblstaff"]').removeClass('hide');
                $('#id_object').val('tblstaff').selectpicker('refresh').trigger('change');
            }
            if(group_id=='8')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value="tblother_object"]').removeClass('hide');
                $('#id_object').val('tblother_object').selectpicker('refresh').trigger('change');
            }
            if(group_id=='1')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value="tblcustomers"]').removeClass('hide');
                $('#id_object option[value="tblstaff"]').removeClass('hide');
                $('#id_object').val('tblcustomers').selectpicker('refresh').trigger('change');
            }
            if(group_id=='10')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value="tblstaff"]').removeClass('hide');
                $('#id_object option[value=""]').removeClass('hide');
                $('#id_object').val('tblstaff').selectpicker('refresh').trigger('change');
            }
            if(group_id=='12')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value="tblstaff"]').removeClass('hide');
                $('#id_object').val('tblstaff').selectpicker('refresh').trigger('change');
            }
            if(group_id=='14')
            {
                $('#id_object option[value=""]').removeClass('hide');
                // $('.div_object').addClass('hide');
                $('#staff_id_not').attr('required','true');
                $('#staff_id_not').val('SPS Việt Nam');
                $('#staff_id').removeAttr('required','true');

            }
            if(group_id=='15')
            {
                $('#id_object option[value=""]').removeClass('hide');
                // $('.div_object').addClass('hide');
                $('#staff_id_not').attr('required','true');
                $('#staff_id_not').val('SPS Việt Nam');
                $('#staff_id').removeAttr('required','true');

            }
            if(group_id=='13' || group_id=='5')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value = ""]').removeClass('hide');
                $('#id_object').val('').selectpicker('refresh').trigger('change');
            }
            if(group_id=='5')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value = ""]').removeClass('hide');
                $('#id_object option[value="tblcustomers"]').removeClass('hide');
                $('#id_object').val('tblcustomers').selectpicker('refresh').trigger('change');
            }
            if(group_id=='4')
            {
                $('#id_object option').attr('class','hide');
                $('#id_object option[value = ""]').removeClass('hide');
                $('#id_object option[value="tblcustomers"]').removeClass('hide');
                $('#id_object option[value="tblstaff"]').removeClass('hide');
                $('#id_object option[value="tblother_object"]').removeClass('hide');
                $('#id_object').val('tblcustomers').selectpicker('refresh').trigger('change');
            }
            init_cash_code();
        }
        else
        {
            $('#payment_mode_id option').removeClass('hide');
            $('#payment_mode_id').selectpicker('refresh');

            $('#payment_mode_to option').removeClass('hide');
            $('#payment_mode_to').selectpicker('refresh');

            $('#payment_mode_from option').removeClass('hide');
            $('#payment_mode_from').selectpicker('refresh');

            $('#id_object option[value=""]').removeClass('hide');
            $('#id_object option[value="tblstaff"]').removeClass('hide');
            $('#id_object option[value="tblcustomers"]').removeClass('hide');
            $('#id_object option[value="tblsuppliers"]').removeClass('hide');
            $('#id_object option[value="tblracks"]').removeClass('hide');
            $('#id_object option[value="tblporters"]').removeClass('hide');
            $('#id_object option[value="tblother_object"]').removeClass('hide');
            $('#id_object').selectpicker('refresh');
            $.post("<?=admin_url('cash_book/get_cash')?>", {id:id<?=(isset($is_admin)&&$is_admin==true?',thang:true':'')?>}).done(function(form) {
                obj = JSON.parse(form);
                $('#form_cash_book').find('.modal-body').removeClass('point-ev');
                $('#form_cash_book').find('button[type="submit"]').removeClass('hide');
                if(obj.not_edit)
                {
                    $('#form_cash_book').find('.modal-body').addClass('point-ev');
                    $('#form_cash_book').find('button[type="submit"]').addClass('hide');
                }
                $('#date').val(obj.date);
                $('#date_control').val(obj.date_control);
                if(obj.type == 0)
                {
                    $('#type_no').prop('checked',true);
                }
                else
                {
                    $('#type_yes').prop('checked',true);
                }
                if(obj.groups=='11')
                {
                    $('#id_object option').attr('class','hide');
                    $('#id_object option[value="tblstaff"]').removeClass('hide');
                }
                if(obj.groups=='1')
                {
                        $('#id_object option').attr('class','hide');
                        $('#id_object option[value="tblstaff"]').removeClass('hide');
                        $('#id_object option[value="tblcustomers"]').removeClass('hide');
                }
                if(obj.groups=='3' || obj.groups=='16')
                {
                    $('#id_object option').attr('class','hide');
                    $('#id_object option[value="tblstaff"]').removeClass('hide');
                }
                if(obj.groups=='7')
                {
                    $('#id_object option').attr('class','hide');
                    $('#id_object option[value="tblsuppliers"]').removeClass('hide');
                }
                if(obj.groups=='6')
                {
                    $('#id_object option').attr('class','hide');
                    $('#id_object option[value="tblracks"]').removeClass('hide');
                }
                if(obj.groups=='8')
                {
                    $('#id_object option').attr('class','hide');
                    $('#id_object option[value="tblother_object"]').removeClass('hide');
                }
                if(obj.groups=='10')
                {
                    $('#id_object option').attr('class','hide');
                    $('#id_object option[value="tblstaff"]').removeClass('hide');
                    $('#id_object option[value=""]').removeClass('hide');
                }
                if(group_id=='12')
                {
                    $('#id_object option').attr('class','hide');
                    $('#id_object option[value="tblstaff"]').removeClass('hide');
                    $('#id_object').val('tblstaff').selectpicker('refresh').trigger('change');
                }
                if(group_id=='14')
                {
                    $('.div_object').addClass('hide');
                    $('#staff_id_not').removeAttr('required','true');
                    $('#staff_id').removeAttr('required','true');
                }
                if(group_id=='15')
                {
                    $('.div_object').addClass('hide');
                    $('#staff_id_not').removeAttr('required','true');
                    $('#staff_id').removeAttr('required','true');
                }
                if(group_id=='13' || group_id=='5')
                {
                    $('#id_object option').attr('class','hide');
                    $('#id_object option[value=""]').removeClass('hide');
                    $('#id_object').val('').selectpicker('refresh').trigger('change');
                }
                if(group_id=='4')
                {
                    $('#id_object option').attr('class','hide');
                    $('#id_object option[value = ""]').removeClass('hide');
                    $('#id_object option[value="tblcustomers"]').removeClass('hide');
                    $('#id_object option[value="tblstaff"]').removeClass('hide');
                    $('#id_object option[value="tblother_object"]').removeClass('hide');
                    $('#id_object').val('tblcustomers').selectpicker('refresh').trigger('change');
                }
                $('#id_object').selectpicker('refresh');
                $('#id_object').val(obj.id_object).selectpicker('refresh');

                var id_object = $('#id_object').val();
                if(group_id != '14' && group_id != '15')
                {
                    if(id_object != "")
                    {
                        $('#staff_id_not').removeAttr('required','true');
                        $('#staff_id').attr('required','true');
                    }
                    else
                    {
                        $('#staff_id_not').attr('required','true');
                        $('#staff_id').removeAttr('required');
                    }
                }

                check_data(obj.staff_id);
                $('#code').val(obj.code);
                $('#note').val(obj.note);
                $('#price').val(obj.price);
                $('#staff_id').val(obj.staff_id).selectpicker('refresh');
                $('#groups').val(obj.groups).selectpicker('refresh');
                $('#payment_mode_id').val(obj.payment_mode_id).selectpicker('refresh');
                $('#modal_cash_book').modal('show');
                $('#form_cash_book').prop('action','<?=admin_url('cash_book/order_detail/')?>'+id);
                $('#form_cash_book').attr('id_index',id);
                if(parseFloat(obj.groups) == 8 && obj.id_object == 'tblother_object' && obj.id_contract_borrowing != "NULL")
                {
                    $('.id_contract_borrowing').removeClass('hide');
                    load_other_object(obj.staff_id,obj.id_contract_borrowing);
                }
                else
                {
                    $('#id_contract_borrowing').val('').selectpicker('refresh');
                    $('.id_contract_borrowing').addClass('hide');
                }

            }), !1
        }
        $('#detail_groups').modal('hide');
    }
    function detail_group()
    {
        $('#detail_groups').modal('show');
    }


    $(function() {
        var type=$('input[name="type"]');
        _validate_form($("#form_cash_book"), {
            <?php if(empty($is_admin)){?>
            date: {
                required: true,
                remote:{
                    url: site_url + "admin/cash_book/kt_date_now",
                    type:'post',
                    data: {
                        date:function(){
                            return $('input[name="date"]').val();
                        }
                    }
                }
            },
            <?php } else {?>
            date:"required",
            <?php } ?>
            groups:"required",
            note: "required",
            price: "required",payment_mode_id:"required",
            type:"required"
        }, manage_cash)
    });
    $(function() {
        _validate_form($("#form_cash_book_ward"), {
            date_ward: "required",
            payment_mode_to:"required",
            payment_mode_from:"required",
            price_war: "required",
            note_war: "required"
        }, manage_war_cash)
    });
    function manage_cash(form) {
        var button = $(form).find('button[type="submit"]');
        button.button({loadingText: 'Vui lòng chờ...'});
        button.button('loading');
        var data = $(form).serialize();
        var action = form.action;
        if(typeof(csrfData) != "undefined") {
            data+= '&'+[csrfData['token_name']]+'='+csrfData['hash'];

        }
        if ($('#staff_id').attr('disabled')) {
          // data+= '&staff_id=1';
        }
        return $.post(action, data).done(function(form) {
            form = JSON.parse(form), alert_float(form.alert_type, form.message),
                $('.table-cash_book').DataTable().ajax.reload(),
                $('#modal_cash_book').modal('hide');
            if(form.add)
            {
                // window.open(admin_url+'receipts/pdf/'+form.add+'?print=true','_blank');
            }

        }).always(function() {
                button.button('reset')
            });
    }
    function manage_war_cash(form) {
         var button = $(form).find('button[type="submit"]');
        button.button({loadingText: 'Vui lòng chờ...'});
        button.button('loading');
        var data = $(form).serialize();
        var action = form.action;
        if(typeof(csrfData) != "undefined")
        {
            data+= '&'+[csrfData['token_name']]+'='+csrfData['hash'];
        }
        var price = $('#price_war').val().replace(/\,/g, '');
        var payment_mode_id=$('#payment_mode_to').val();
        jQuery.ajax({
            type: "post",
            url:"<?=admin_url()?>cash_book/get_price_limit",
            data: {price:price,payment_mode_id:payment_mode_id},
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if(parseFloat(response.price)<parseFloat(price))
                {
                    $('#form_cash_book').find('button[type="submit"]').addClass('hide');
                    alert('thiếu tiền');
                    return false;
                }
            }
        });

        return $.post(action, data).done(function(form) {
            form = JSON.parse(form), alert_float(form.alert_type, form.message),
                $('.table-cash_book').DataTable().ajax.reload(),
                $('#modal_cash_book_ward').modal('hide');
                if(form.add_from)
                {
                    // window.open(admin_url+'receipts/pdf/'+form.add_from+'?print=true','_blank');
                }
                if(form.add_to)
                {
                    // window.open(admin_url+'receipts/pdf/'+form.add_to+'?print=true','_blank');
                }

        }).always(function() {
                button.button('reset')
            });
    }


    $('body').on('change','#id_object',function(data){
        var id=$(this).val();
        var id_object=$('#id_object').val();
        console.log(id_object);
        if(id_object)
        {
            $('#staff_id_not').removeAttr('required','true');
            $('#staff_id').attr('required','true');
            $('.staff_not').hide();
            $('.staff_yes').show();
            $('#staff_id_not').val();
            $.post("<?=admin_url('cash_book/get_object')?>", {id:id}).done(function(form) {
                obj = JSON.parse(form);
                var_option="<option></option>";
                $.each(obj,function(i,v){
                    var_option+="<option value='"+v.id+"'>"+v.name+"</option>";
                })
                $('#staff_id').html(var_option).selectpicker('refresh');
                return true;
            })
        }
        else
        {
            $('.staff_not').show();
            $('.staff_yes').hide();
            $('#staff_id').val('').selectpicker('refresh');
            $('#staff_id_not').attr('required','true');
            $('#staff_id').removeAttr('required');
            return true;
        }
    })
    $('body').on('change','#id_object_war',function(data){
        var id=$(this).val();
        if(id!="")
        {
            $('.staff_not').hide();
            $('.staff_yes').show();
            $('#staff_id_to_not').val();
            $.post("<?=admin_url('cash_book/get_object')?>", {id:id}).done(function(form) {
                obj = JSON.parse(form);
                var_option="<option></option>";
                $.each(obj,function(i,v){
                    var_option+="<option value='"+v.id+"'>"+v.name+"</option>";
                })
                $('#staff_id_to').html(var_option).selectpicker('refresh');
                return true;
            })
        }
        else
        {
            $('.staff_not').show();
            $('.staff_yes').hide();
            $('#staff_id_to').val('').selectpicker('refresh');
            return true;
        }
    })
    $('body').on('change','#id_object, #groups,#staff_id',function(e){
        var id_object=$('#id_object').val();
        var groups=$('#groups').val();
        var id_other_object=$('#staff_id').val();
        if(id_object=='tblother_object'&&(parseFloat(groups)==8||parseFloat(groups)==4)&&id_other_object!="")
        {
            $('.id_contract_borrowing').removeClass('hide');
            $.post("<?=admin_url('cash_book/get_information')?>", {id:id_other_object}).done(function(form) {
                obj = JSON.parse(form);
                var_option="<option></option>";
                $.each(obj,function(i,v){
                    var_option+="<option value='"+v.id+"'>"+v.name+"</option>";
                })
                $('#id_contract_borrowing').html(var_option).selectpicker('refresh').selectpicker('refresh');
                return true;
            })
        }
    });

    //xong

    function load_other_object(id_other_object,id_contract)
    {
        if(id_other_object!="")
        {
            $.post("<?=admin_url('cash_book/get_information/')?>"+id_contract, {id:id_other_object}).done(function(form) {
                obj = JSON.parse(form);
                var_option="<option></option>";
                $.each(obj,function(i,v){
                    var_option+="<option value='"+v.id+"'>"+v.code+"</option>";
                })
                $('#id_contract_borrowing').html(var_option).selectpicker('refresh').val(id_contract).selectpicker('refresh');
                return true;
            })
        }
    }
    function check_data(data){
        var id = $('#id_object').val();
        if(id)
        {
            $('.staff_not').hide();
            $('.staff_yes').show();
            $('#staff_id_not').val();
            $.post("<?=admin_url('cash_book/get_object')?>", {id:id}).done(function(form) {
                obj = JSON.parse(form);
                var_option="<option></option>";
                $.each(obj,function(i,v){
                    var_option+="<option value='"+v.id+"'>"+v.name+"</option>";
                })
                $('#staff_id').html(var_option).selectpicker('refresh').val(data).selectpicker('refresh');
                return true;
            })
        }
        else
        {
            $('.staff_not').show();
            $('.staff_yes').hide();
            $('#staff_id').val('').selectpicker('refresh');
            $('#staff_id_not').val(data);
            return true;
        }
    }

     $('.table-cash_book').on('draw.dt', function() {
         var invoiceReportsTable = $(this).DataTable();
         var sums = invoiceReportsTable.ajax.json().sum;
         if (sums != 'undefined'){
            $('.totalcashbook').text(sums+' $');
         }
         <?php foreach ($payments_modes as $key=>$value){?>
             var total_payment_<?=$value['id']?>=invoiceReportsTable.ajax.json().total_payment_<?=$value['id']?>;
             if (total_payment_<?=$value['id']?> != 'undefined'){
                 $('.total_payments_<?=$value['id']?>').text(total_payment_<?=$value['id']?>);
              }
             <?php if(empty($value['active'])){?>
                    if (total_payment_<?=$value['id']?> == 'undefined' || total_payment_<?=$value['id']?> == 0){
                        console.log('vao');
                        $('.total_payments_<?=$value['id']?>').parent().parent().parent('.not_active').addClass('hide');
                    }
                    else
                    {
                        console.log('ko vao');
                        $('.total_payments_<?=$value['id']?>').parent().parent().parent('.not_active').removeClass('hide');
                    }
             <?php } ?>
          <?php }?>
   });


    $('body').on('change','.table-cash_book .check_1',function(e)
    {
        if($(this).prop('checked'))
        {
            $(this).parents('tr').removeClass('bg-info');
        }
        else
        {
            $(this).parents('tr').addClass('bg-info');
        }
    })






    function inventory_money()
    {
        $('#modal_inventory_money').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function create_inventory_money()
    {
        var money_500=($.isNumeric(parseFloat($('#money_500').val().replace(/\,/g, '')))?parseFloat($('#money_500').val().replace(/\,/g, '')):0);
        var money_200=($.isNumeric(parseFloat($('#money_200').val().replace(/\,/g, '')))?parseFloat($('#money_200').val().replace(/\,/g, '')):0);
        var money_100=($.isNumeric(parseFloat($('#money_100').val().replace(/\,/g, '')))?parseFloat($('#money_100').val().replace(/\,/g, '')):0);
        var money_50= ($.isNumeric(parseFloat($('#money_50').val().replace(/\,/g, '')))?parseFloat($('#money_50').val().replace(/\,/g, '')):0);
        var money_20= ($.isNumeric(parseFloat($('#money_20').val().replace(/\,/g, '')))?parseFloat($('#money_20').val().replace(/\,/g, '')):0);
        var money_10= ($.isNumeric(parseFloat($('#money_10').val().replace(/\,/g, '')))?parseFloat($('#money_10').val().replace(/\,/g, '')):0);
        var money_5 = ($.isNumeric(parseFloat($('#money_5').val().replace(/\,/g, '')))?parseFloat($('#money_5').val().replace(/\,/g, '')):0);
        var money_2 = ($.isNumeric(parseFloat($('#money_2').val().replace(/\,/g, '')))?parseFloat($('#money_2').val().replace(/\,/g, '')):0);
        var money_1 = ($.isNumeric(parseFloat($('#money_1').val().replace(/\,/g, '')))?parseFloat($('#money_1').val().replace(/\,/g, '')):0);
        window.open('<?=admin_url()?>cash_book/inventory_money_pdf?money_500='+money_500+'&money_200='+money_200+'&money_100='+money_100+'&money_50='+money_50+'&money_20='+money_20+'&money_10='+money_10+'&money_5='+money_5+'&money_2='+money_2+'&money_1='+money_1+'&print=true', '_blank');
    }

    function formatNumBerKeyUp_job(id_input)  // dùng cho phép xóa ký tự để cộng
    {
        key="";
        money=$(id_input).val().replace(/[^\d\+-]/g, '');
        a=money.split(" ");
        $.each(a , function (index, value){
            key=key+value;
        });
        $(id_input).val(key);
    }


    $('body').on('change','.input_money',function(e){
        var _tr=$(this).parents('tr');
        var type_money=_tr.find('td:nth-child(1)').text().replace(/\,/g, '');
        var input_money=_tr.find('td:nth-child(2) input').val().replace(/\,/g, '');
        $.post("<?=admin_url('cash_book/get_job')?>", {ct:input_money}).done(function(form) {
            input_money=form;
            if(!$.isNumeric(input_money))
            {
                input_money=0;
            }
            _tr.find('td:nth-child(2) input').val(input_money);
            _tr.find('td:nth-child(3) p').text(formatNumber(parseFloat(type_money)*parseFloat(input_money)));

            var p_input=$('.p_input_money');
            var total=0;
            $.each(p_input,function(i,v){
                if($.isNumeric(parseFloat($(v).text().replace(/\,/g, ''))))
                {
                    total+=parseFloat($(v).text().replace(/\,/g, ''));
                }
            })
            $('.total_modal_inventory_money').text(formatNumber(total));
        })
    })


    $('#modal_inventory_money').on('hidden.bs.modal', function (e) {
        $('#money_500').val('');
        $('#money_400').val('');
        $('#money_300').val('');
        $('#money_200').val('');
        $('#money_100').val('');
        $('#money_50').val('');
        $('#money_20').val('');
        $('#money_10').val('');
        $('#money_5').val('');
        $('#money_2').val('');
        $('#money_1').val('');
        $('.p_input_money').text('');
        $('.total_modal_inventory_money').text('');
    })
    function refresh_inventory_money()
    {
        if(confirm('Bạn có chắc muốn xóa dữ liệu vừa nhập'))
        {
            $('#money_500').val('');
            $('#money_400').val('');
            $('#money_300').val('');
            $('#money_200').val('');
            $('#money_100').val('');
            $('#money_50').val('');
            $('#money_20').val('');
            $('#money_10').val('');
            $('#money_5').val('');
            $('#money_2').val('');
            $('#money_1').val('');
            $('.p_input_money').text('');
            $('.total_modal_inventory_money').text('');
        }
    }



    $('body').on('change','#groups',function(e){

        var group_id=$(this).val();
        if(group_id=='11')
        {
            $('#id_object_war').val('tblstaff').selectpicker('refresh').trigger('change');
            return;
        }
        $('#id_object option[value=""]').removeClass('hide');
        $('#id_object option[value="tblstaff"]').removeClass('hide');
        $('#id_object option[value="tblcustomers"]').removeClass('hide');
        $('#id_object option[value="tblsuppliers"]').removeClass('hide');
        $('#id_object option[value="tblracks"]').removeClass('hide');
        $('#id_object option[value="tblporters"]').removeClass('hide');
        $('#id_object option[value="tblother_object"]').removeClass('hide');
        $('#id_object').selectpicker('refresh').trigger('change');


        if(group_id=='5')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblcustomers"]').removeClass('hide');
            $('#id_object option[value="tblstaff"]').removeClass('hide');
            $('#id_object').val('tblcustomers').selectpicker('refresh').trigger('change');
        }
        if(group_id=='7')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblsuppliers"]').removeClass('hide');
            $('#id_object').val('tblsuppliers').selectpicker('refresh').trigger('change');
        }
        if(group_id=='6')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblracks"]').removeClass('hide');
            $('#id_object').val('tblracks').selectpicker('refresh').trigger('change');
        }
        if(group_id=='3')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblstaff"]').removeClass('hide');
            $('#id_object').val('tblstaff').selectpicker('refresh').trigger('change');
        }
        if(group_id=='8')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblother_object"]').removeClass('hide');
            $('#id_object').val('tblother_object').selectpicker('refresh').trigger('change');
        }
        if(group_id=='1')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblsuppliers"]').removeClass('hide');
            $('#id_object option[value="tblracks"]').removeClass('hide');
            $('#id_object option[value="tblporters"]').removeClass('hide');
            $('#id_object option[value="tblstaff"]').removeClass('hide');
            $('#id_object').val('tblsuppliers').selectpicker('refresh').trigger('change');
        }
        if(group_id=='10')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblstaff"]').removeClass('hide');
            $('#id_object option[value=""]').removeClass('hide');
            $('#id_object').val('tblstaff').selectpicker('refresh').trigger('change');
        }
        if(group_id=='12')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblstaff"]').removeClass('hide');
            $('#id_object').val('tblstaff').selectpicker('refresh').trigger('change');
        }
        if(group_id=='14')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblcustomers"]').removeClass('hide');
            $('#id_object').val('tblcustomers').selectpicker('refresh').trigger('change');
        }
        if(group_id=='15')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value="tblcustomers"]').removeClass('hide');
            $('#id_object').val('tblcustomers').selectpicker('refresh').trigger('change');
        }
        if(group_id=='13')
        {
            $('#id_object option').attr('class','hide');
            $('#id_object option[value=""]').removeClass('hide');
            $('#id_object').val('').selectpicker('refresh').trigger('change');
        }
    })



    $('body').on('change','#object_search',function(){
        var id=$('#object_search').val();
        if(id)
        {
            $.post("<?=admin_url('cash_book/get_object')?>", {id:id}).done(function(form) {
                obj = JSON.parse(form);
                var_option="<option></option>";
                $.each(obj,function(i,v){
                    var_option+="<option value='"+v.id+"'>"+v.name+"</option>";
                })
                $('#staff_id_search').html(var_option).selectpicker('refresh');
                return true;
            })
        }
        else
        {
            $('#staff_id_search').html('').selectpicker('refresh');
            return true;
        }

    })

$('body').on('click', '._delete-reminder', function() {
    var r = confirm('Bạn chắc chắn muốn xóa phiếu này');
    if (r == false) {
        return false;
    } else {
        var x = confirm('Xác nhận lại bạn chắc chắn muốn xóa phiếu này');
        if (x == false) {
            return false;
        }
        var c = confirm('Xác nhận lại lần cuối bạn muốn xóa phiếu này');
        if (c == false) {
            return false;
        }
        $.get($(this).attr('href'), function(response) {
            alert_float(response.alert_type, response.message);
            // Looop throug all availble reminders table to reload the data
            $.each(available_reminders_table, function(i, table) {
                if ($.fn.DataTable.isDataTable(table)) {
                    $('body').find(table).DataTable().ajax.reload();
                }
            });
        }, 'json');
    }
    return false;
});


</script>
