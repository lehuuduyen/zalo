<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="tab1" class="cover_tab">
    <?php $this->load->view('tab1.php') ?>
</div>

<div id="tab2" class="cover_tab">
    <?php $this->load->view('tab2.php') ?>
</div>

<div id="tab3" class="cover_tab">
    <?php $this->load->view('tab3.php') ?>
</div>

<div id="tab4" class="cover_tab">
    <?php $this->load->view('tab4.php') ?>
</div>

<div id="tab5" class="cover_tab">
    <?php $this->load->view('tab5.php') ?>
</div>

<div id="tab6" class="cover_tab">
    <?php $this->load->view('tab6.php') ?>
</div>

<div id="tab7" class="cover_tab">
    <?php $this->load->view('tab7.php') ?>
</div>


<div class="modal fade" id="customer" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

        <?php echo form_open('/', array('id' => 'add_new_pick_up_points',)); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Sửa Điểm Nhận Hàng</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">


                        <div id="repo_customer_cover2" class="form-group">

                        </div>

                        <div class="form-group">
                            <label for="note">Ghi Chú :</label>
                            <textarea style="resize:none; height:121px;" class="form-control" rows="5" id="note"
                                      name="note"></textarea>
                        </div>

                        <div class="hidden-form">
                            <!-- <input type="hidden" id="district_filter" name="district_filter" value="">
                            <input type="hidden" id="commune_filter" name="commune_filter" value="">
                            <input type="hidden" id="address_filter" name="address_filter" value=""> -->

                        </div>

                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('Đóng'); ?></button>
                <a href="javasript:;" class="btn btn-primary submit_customer_policy"><?php echo _l('Xác Nhận'); ?></a>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="customer_create" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

        <?php echo form_open('/', array('id' => 'add_new_pick_up_points_create',)); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Thêm Điểm Nhận Hàng</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">


                            <div class="form-group">
                                <label for="created">Ngày Tạo</label>
                                <input placeholder="Ngày chứng từ" type="text" class="form-control" id="created"
                                       name="created">
                                <?php $id = get_staff_user_id(); ?>
                                <input type="hidden" id="user_created" name="user_created" value="<?php echo $id; ?>">
                            </div>

                            <div class="form-group">
                                <label for="receive_or_pay">Loại Hàng:</label>
                                <select disabled class="form-control" id="receive_or_pay" name="receive_or_pay">
                                    <option value="0">Lấy hàng</option>
                                </select>
                            </div>

                            <div class="form-group hide">
                                <label for="type_customer">Loại Khách Hàng:</label>
                                <select class="form-control" id="type_customer" name="type_customer">
                                    <option value="old">Khách Hàng Cũ</option>
                                    <option value="new">Khách Hàng Mới</option>
                                </select>
                            </div>


                            <div class="form-group old_cus" style="position:relative">
                                <label for="customer_phone_zalo">Chọn Khách Hàng</label>

                                <input disabled autocomplete="off" placeholder="Chọn Khách Hàng" class="form-control"
                                       id="search_customer" type="text" name="search_customer"
                                       value="<?php echo set_value('search_customer'); ?>">


                                <input type="hidden" id="customer_id" name="customer_id"
                                       value="<?php echo set_value('customer_id'); ?>">
                            </div>


                        </div>


                        <div id="repo_customer_cover" class="form-group">

                        </div>
                        <div id="phone_customer_cover" class="form-group">

                        </div>


                        <div style="display:none" class="new-cus">
                            <div class="form-group">
                                <label for="name_customer_new">Nhập Tên Khách Hàng</label>
                                <input placeholder="Nhập Tên Khách Hàng" type="text" class="form-control"
                                       id="name_customer_new" name="name_customer_new">
                            </div>
                            <div class="form-group">
                                <label for="name_customer_new">Nhập SĐT Khách Hàng</label>
                                <input placeholder="Nhập SĐT Khách Hàng" type="text" class="form-control"
                                       id="phone_customer_new" name="phone_customer_new">
                            </div>


                            <div class="form-group">
                                <label for="type_customer">Chọn Quận Huyện/Thành Phố:</label>
                                <select class="form-control" id="district" name="district">

                                    <?php foreach ($district_hd as $key => $value): ?>
                                        <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="type_customer">Chọn Phường Xã:</label>
                                <div class="load-area">
                                    <select class="form-control" id="area_hd" name="area_hd">

                                        <?php foreach ($area_hd as $key => $value): ?>
                                            <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="free_address">Nhập Địa Chỉ</label>
                                <input placeholder="Nhập Địa Chỉ" type="text" class="form-control" id="free_address"
                                       name="free_address">
                            </div>

                        </div>

                        <div id="loader-repo" class="lds-ellipsis">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>

                        <div class="form-group">
                            <label for="note">Ghi Chú :</label>
                            <textarea style="resize:none; height:121px;" class="form-control" rows="5"
                                      id="note_pickup_create" name="note"></textarea>
                        </div>

                        <div class="hidden-form">
                            <input type="hidden" id="district_filter" name="district_filter" value="">
                            <input type="hidden" id="commune_filter" name="commune_filter" value="">
                            <input type="hidden" id="address_filter" name="address_filter" value="">

                        </div>

                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('Đóng'); ?></button>
                <button type="button"
                        class="btn btn-primary submit_customer_policy_new"><?php echo _l('Xác Nhận'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="create_order" tabindex="-1" role="dialog" style="position: fixed;padding-left:0">
    <div class="modal-dialog" role="document">
        <?php echo form_open('/', array('id' => 'create_order_ob', 'autocomplete' => 'off')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo "Tạo Đơn Hàng"; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="row-custom">
                        <div class="col-md-5">
                            <div class="form-group" style="position:relative">
                                <label for="customer_phone_zalo">Chọn Khách Hàng</label>

                                <input autocomplete="off" onkeyup="enterTab4(event)" placeholder="Chọn Khách Hàng" class="form-control"
                                       id="search_customer_create" type="text" name="search_customer_create"
                                       value="<?php echo $customer_shop_code; ?>">
                                <i class="fa fa-search search-icon" aria-hidden="true"></i>


                                <input type="hidden" id="customer_id" name="customer_id_create" value="">
                                <input type="hidden" id="pickup_phone" name="pickup_phone" value="">


                            </div>
                        </div>


                        <div class="col-md-5">
                            <!-- Chọn Khách Hang -->
                            <div id="repo_customer_cover_create_order" class="form-group">

                            </div>
                        </div>
                    </div>

                    <div id="loader-repo" class="lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <div class="row-custom">
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="product">Sản Phẩm</label>
                                <input type="text" onkeyup="enterTab4(event)" class="form-control" placeholder="Sản Phẩm" id="product"
                                       name="product"></textarea>
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="phone">Điện Thoại</label>
                                <input type="text" onkeyup="enterTab4(event)" class="form-control" placeholder="Điện Thoại" id="ap" name="ap"/>
                                <a class="add-more-phone" href="#"><i class="fa fa-plus" aria-hidden="true"></i></a>
                            </div>
                            <!-- Phone -->
                            <div class="form-group phone-more" style="display:none">
                                <label for="phone_more">Điện Thoại Phụ </label>
                                <input type="text" onkeyup="enterTab4(event)" class="form-control" placeholder="Điện Thoại Phụ" id="phone_more"
                                       name="phone_more"/>
                            </div>
                            <!-- Phone More-->
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <div class="form-group ">
                                <label for="f">Họ Và Tên </label>
                                <input type="text" onkeyup="enterTab4(event)" class="form-control" placeholder="Họ Và Tên" id="f" name="f"/>
                            </div>
                            <!-- Họ Và Tên-->
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <div class="form-group ">
                                <label for="a">Địa Chỉ </label>
                                <input type="text" onkeyup="enterTab4(event)" class="form-control" placeholder="Địa Chỉ" id="a" name="a"
                                       autocomplete="off"/>
                            </div>
                            <!-- Địa Chỉ-->

                        </div>
                    </div>

                    <div class="row-custom">

                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="province">Tỉnh/Thành</label>
                                <select data-live-search="true" class="form-control selectpicker" id="province"
                                        name="province">
                                    <option value="null">Chọn Tỉnh/Thành</option>
                                    <?php foreach ($province as $key => $value): ?>
                                        <option value='<?php echo json_encode($value) ?>'><?php echo $value->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="type_customer">Chọn Quận Huyện/Thành Phố:</label>
                                <select class="form-control" id="district_order" name="district">


                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label for="type_customer">Chọn Phường Xã:</label>
                                <div class="load-area">
                                    <select class="form-control" id="area_hd_order" name="area_hd">


                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="region-box col-md-3 col-xs-12">
                            <input type="hidden" name="region_id" onkeyup="enterTab4(event)" id="region_id">
                            <div class="load-html">

                            </div>
                        </div>


                    </div>
                    <hr/>

                    <div class="row-custom">
                        <div id="loader-repo2" class="lds-ellipsis">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>


                    <div class="row-custom">
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="mass">Khối Lượng [gr] </label>
                                <input style="color:red;font-weight:bold;" onkeyup="formatNumBerKeyUp(this);enterTab4(event)" type="text"
                                       class="form-control" placeholder="Khối Lượng (gram)" id="mass" name="mass"
                                       value=""/>
                            </div>
                        </div>

                        <div class="col-md-2" style="display: none">
                            <div class="form-group ">
                                <label for="volume">Thể Tích [Cm³] </label>
                                <input style="color:red;font-weight:bold;" onkeyup="formatNumBerKeyUp(this);enterTab4(event)" type="text"
                                       class="form-control" placeholder="Thể Tích" id="volume" name="volume"
                                       value="<?php echo number_format(27000) ?>"/>
                                <span style="color:#03a9f4;">Đơn vị tính gram (Cm³).</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="value_order">Trị Giá [₫]</label>
                                <input onkeyup="formatNumBerKeyUp(this);enterTab4(event)" type="text" class="form-control"
                                       placeholder="Trị Giá (Không bắt buộc nhập)" id="value_order" name="value_order"/>
                            </div>
                        </div>

                        <div class="col-md-2" style="display:none">
                            <div class="form-group">
                                <label for="check_disable_super">
                                    <input checked id="check_disable_super" onkeyup="enterTab4(event)" type="checkbox">
                                    Theo Chính Sách
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="service">Người chịu phí</label>
                                <select class="form-control" id="the_fee_bearer" name="the_fee_bearer">
                                    <option value="0">Người Nhận</option>
                                    <option value="1">Người Gửi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="cod">Tiền Hàng [₫]</label>
                                <input onkeyup="formatNumBerKeyUp(this);enterTab4(event)" type="text" class="form-control"
                                       placeholder="Tiền Hàng" id="cod" name="cod"/>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group ">
                                <label for="total_money">Thu Hộ</label>

                                <input onkeyup="formatNumBerKeyUp(this);enterTab4(event)" type="text" class="form-control"
                                       placeholder="Tổng Tiền" id="total_money" name="total_money"/>
                            </div>
                        </div>

                        <div class="cover-checked col-md-3">
                            <label for="barter" class="container-checkbox">Đổi/Lấy Hàng Về
                                <input type="checkbox" id="barter" name="barter">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>

                    <div class="row-custom" style="align-items: normal;">
<!--                        <div class="col-md-4">-->
<!--                            <div class="form-group" id="special">-->
<!--                                <label for="note">Chính Sách Đặc Biệt :</label>-->
<!--                                <textarea style="resize:none; height:100px;" class="form-control" rows="5"></textarea>-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="col-md-4" style="<?= (!$isAppMobile) ? 'width: 31%;':''?>">
                            <div class="form-group">
                                <label for="note">Ghi Chú Khi Giao :</label>
                                <textarea placeholder="Ghi Chú Khi Giao" style="resize:none; height:100px;"
                                          class="form-control" rows="5" onkeyup="enterTab4(event)" id="note_create" name="note_create"></textarea>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="supership_value">Tiền Dịch Vụ Supership [₫]</label>
                                <input onkeyup="formatNumBerKeyUp(this);enterTab4(event)" type="text" class="form-control" style="width: 90%;"
                                       placeholder="Tiền Dịch Vụ Supership" id="supership_value" name="supership_value"
                                       disabled/>
                                <span style="color:transparent;">test</span>

                            </div>
                        </div>
                        <div class="col-md-4" style="<?= (!$isAppMobile) ? 'padding-left: 0px':''?>">
                            <div class="form-group">
                                <label for="for-custom">Thống kê tiền dịch vụ super ship :</label>
                                <p id="tvc" style="margin-top: 10px">Tiền Vận Chuyển : <span style="color:red;font-weight:bold"></span></p>
                                <p id="tvk" style="margin-top: 10px">Tiền Vượt Khối Lượng : <span style="color:red;font-weight:bold"></span></p>
                                <p id="tvtt" style="margin-top: 10px">Tiền vượt Thể Tích: <span style="color:red;font-weight:bold"></span></p>
                                <p id="tbh" style="margin-top: 10px">Tiển Bảo Hiểm : <span style="color:red;font-weight:bold"></span></p>
                            </div>
                        </div>
                    </div>


                    <div class="bottom-mobile">


                        <div class="col-md-12 col-xs-12" style="margin-bottom:10px;">
                            <hr/>
                            <a class="btn btn-primary explain" href="javascript:;">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Mở Rộng Nhập Liệu
                            </a>
                            <a class="btn btn-primary collapse" href="javascript:;">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                                Thu Gọn Nhập Liệu
                            </a>
                        </div>


                        <div class="more-config ">
                            <div class="col-md-3 col-xs-12">
                                <div class="form-group">
                                    <label for="service">Dịch Vụ:</label>
                                    <select class="form-control" id="service" name="service">
                                        <option value="1">Tốc Hành</option>
                                        <option value="2">Tiết Kiệm</option>
                                    </select>
                                </div>


                            </div>

                            <div class="col-md-3 col-xs-12">
                                <div class="form-group">
                                    <label for="payer">Người Trả Phí:</label>
                                    <select class="form-control" id="payer" name="payer">
                                        <option value="1">Người Gửi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-xs-12">
                                <div class="form-group">
                                    <label for="config">Cấu Hình:</label>
                                    <select class="form-control" id="config" name="config">
                                        <option value="1">Cho Xem Hàng Nhưng Không Cho Thử Hàng</option>
                                        <option value="2">Cho Thử Hàng</option>
                                        <option value="3">Không Cho Xem Hàng</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-xs-12">
                                <div class="form-group ">
                                    <label for="code_order">Mã Đơn Của Shop</label>
                                    <input type="text" onkeyup="enterTab4(event)" class="form-control" placeholder="Mã Đơn Của Shop" id="soc"
                                           name="soc"/>

                                </div>
                            </div>

                        </div>


                    </div>


                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('Đóng'); ?></button>
                <a href="javascript:;" id="btn_create" class="btn btn-primary submit_create_order"><?php echo _l('Xác Nhận'); ?></a>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="create_order-excel" tabindex="-1" role="dialog" style="padding-left:0">
    <div class="modal-dialog" role="document">
        <?php echo form_open('/', array('id' => 'create_order_ob_excel', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo "Tải Đơn Hàng"; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="row-custom">
                        <div class="col-md-12">
                            <div class="alert alert-primary" id="alertSuccess" role="alert" style="background-color: #cce5ff;border-color: #b8daff;display: none;border-radius: 0px;height: 80px;">
                                A simple primary alert—check it out!
                            </div>
                        </div>
                    </div>

                    <div class="row-custom">
                        <div class="col-md-12">
                            <div class="alert alert-danger" id="alertError" role="alert" style="background-color: #eaabb1;border-color: #eaabb1;display: none;border-radius: 0px;height: 46px;">
                                A simple primary alert—check it out!
                            </div>
                        </div>
                    </div>

                    <div class="row-custom">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a class="btn btn-danger" style="width: 100%" href='<?= base_url('assets/order/Mau_Tao_Don_Hang.xlsx')?>'>Tải file mẫu</a>
                            </div>
                        </div>
                    </div>


                    <div class="row-custom">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div id="repo_customer_cover_create_order_excel"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row-custom">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="repo_customer" style="margin-top: 2%">Tệp Excel <span style="color: red">[*]</span></label>
                                <input type="file" id="uploadfile" style="width: 100%;padding: 1%;">
                            </div>
                        </div>
                    </div>

                    <div class="row-custom">
                        <div class="col-md-12" style="margin-top: 4%;">
                            <div class="alert alert-danger" role="alert" style="background-color: #eaabb1;border-color: #eaabb1;display: none;border-radius: 0px;height: 46px;">
                                A simple primary alert—check it out!
                            </div>
                        </div>
                    </div>

                    <div class="row-custom">
                        <div class="col-md-12" style="margin-top: 4%;">
                            <div class="alert alert-danger" role="alert" style="background-color: #eaabb1;border-color: #eaabb1;display: none;border-radius: 0px;height: 46px;">
                                A simple primary alert—check it out!
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('Đóng'); ?></button>
                <a href="javascript:void (0);" id="btn_upload" class="btn btn-primary submit_create_order_excel"
                   data-id="0"><?php echo _l('Tải'); ?></a>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<style>
    .circle-check {
        display: -webkit-flex;
        display: -ms-flex;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 70px;
        height: 70px;
        border: 3px solid #ddd;
        border-radius: 50%;
        margin: 0 auto;
        margin-bottom: 20px;
    }

    .circle-check i {
        display: block;
        font-size: 40px;
        color: green;
    }

    #show-code {
        display: block;
        font-weight: bold;
        font-size: 17px;
    }
    #success-order .modal-dialog {
        width: 300px;
        height: 210px;
        left: 50%;
        top: 50%;
        margin-top: -105px;
        margin-left: -150px;
        position: absolute;
        overflow: hidden;
    }

    #success-order .modal-dialog .modal-body {
        height: auto !important;
    }
</style>
<div class="modal fade" id="success-order" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="height:255px">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center">
                <p class="circle-check"><i class="fa fa-check" aria-hidden="true"></i></p>
                <p class="code-order-show">Mã Yêu Cầu Bạn Là: <span id="show-code">sadasdsadasd</span>. <br> Bạn Vui Lòng Ghi Mã Yêu Cầu Lên Đơn Hàng</p>
                <button type="button" class="btn btn-default" data-dismiss="modal">Xác Nhận</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="info-order" tabindex="-1" role="dialog" style="top: 10%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center">
                <p class="circle-check"><i class="fa fa-check" aria-hidden="true"></i></p>
                <p class="code-order-show">Mã đơn hàng bạn vừa tạo là: <span id="show-code">sadasdsadasd</span></p>
                <button type="button" class="btn btn-default" data-dismiss="modal">Xác Nhận</button>
            </div>

        </div>
    </div>
</div>

