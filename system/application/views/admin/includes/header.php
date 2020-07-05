<?php defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>

<style>
    #over_popup tr th:nth-child(10) {
        width: 65px;
    }

    #over_popup table.dataTable thead .sorting:after {
        display: none;
    }

    #over_popup table.dataTable thead th {
        padding-right: 10px;
    }
    .dropbtn {
        background-color: #4CAF50;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #ddd;}

    .dropdown:hover .dropdown-content {display: block;}

    .dropdown:hover .dropbtn {background-color: #3e8e41;}
    .close-noti{
        opacity:0;
        margin-bottom: 10px;
    }
    .show-noti{
        opacity:1 ;
        transition: 1000ms;
        margin-bottom: 0px;

    }
</style>


<li id="top_search" class="dropdown" data-toggle="tooltip" data-placement="bottom"
    data-title="<?php echo _l('search_by_tags'); ?>">
    <input type="search" id="search_input_header" class="form-control"
           placeholder="<?php echo _l('top_search_placeholder'); ?>">

</li>

<?php
$top_search_area = ob_get_contents();
ob_end_clean();
?>
<div id="header">

    <?php if (is_mobile()) : ?>
        <div class="hide-menu"><i class="fa fa-bars"></i></div>
    <?php endif; ?>
    <div id="logo">
        <?php get_company_logo(get_admin_uri() . '/') ?>
    </div>
    <nav>
        <div class="small-logo">
         <span class="text-primary">
            <?php get_company_logo(get_admin_uri() . '/') ?>
         </span>
        </div>
        <div class="mobile-menu">
            <button type="button" class="navbar-toggle visible-md visible-sm visible-xs mobile-menu-toggle collapsed"
                    data-toggle="collapse" data-target="#mobile-collapse" aria-expanded="false">
                <i class="fa fa-chevron-down"></i>
            </button>
            <ul class="mobile-icon-menu">

                <li class="icon all-noti">
                    <a href="javascript:;" class="click-show-all-noti" data-toggle="tooltip" title="DS Thông Báo"
                       data-placement="bottom">
                        <i class="fa fa-bell" style="    font-size: 30px;
    color: red;
    margin-top: 12px;" aria-hidden="true"></i>
                    </a>
                </li>

            </ul>
            <ul class=" close-noti mobile-icon-menu">
                <li class="icon">
                    <a href="javascript:;" class="click-show-table-delay" data-toggle="tooltip" title="DS Hoãn Giao Hàng"
                       data-placement="bottom">
                        <i class="fa fa-list fa-fw fa-lg" aria-hidden="true"></i>
                        <i class="number_delay"></i>
                    </a>

                </li>
                <li class="icon">
                    <a href="javascript:;" class="click-show-table-order_half" data-toggle="tooltip"
                       title="Danh Sách Đơn Hàng Giao Hàng Một Phần Chưa Trả Hàng Về" data-placement="bottom">
                        <i class="fa fa-cart-plus" style="font-size: 21px;" aria-hidden="true"></i>
                        <i class="number_status_half"></i>
                    </a>
                </li>


                <li class="icon">
                    <a href="javascript:;" class="click-show-table-over" data-toggle="tooltip"
                       title="DS Đơn Hàng Sửa Khối Lượng" data-placement="bottom">
                        <i class="fa fa-hourglass-end fa-fw fa-lg" aria-hidden="true"></i>
                        <i class="number_over"></i>
                    </a>

                </li>

                <li class="icon">
                    <a href="javascript:;" class="click-show-table-check_status" data-toggle="tooltip"
                       title="DS Đơn Hàng Cần Xử Lý" data-placement="bottom">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <i class="number_status"></i>
                    </a>
                </li>
                <li class="icon">
                    <a href="javascript:;" class="click-show-table-check_status2" data-toggle="tooltip"
                       title="DS Đơn Hàng Cần Xử Lý 2" data-placement="bottom">
                        <i class="fa fa-industry" aria-hidden="true"></i>
                        <i class="number_status2"></i>
                    </a>
                </li>
                <li class="icon">
                    <a href="javascript:;" class="click-show-table-check_status3" data-toggle="tooltip"
                       title="Danh Sách Thông Báo GHTK" data-placement="bottom">
                        <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                        <i class="number_status3"></i>
                    </a>
                </li>
                <li class="icon " >
                    <a href="javascript:;" title="Khai Báo Vùng Miền" class="click-show-province" data-toggle="tooltip"
                       data-placement="bottom">
                        <i class="fa fa-flag" aria-hidden="true"></i>
                        <i class="number_province"></i>
                    </a>
                </li>
            </ul>
            <div class="mobile-navbar collapse" id="mobile-collapse" aria-expanded="false" style="height: 0px;"
                 role="navigation">
                <ul class="nav navbar-nav">

                    <li class="header-my-profile"><a
                                href="<?php echo admin_url('profile'); ?>"><?php echo _l('nav_my_profile'); ?></a></li>
                    <li class="header-my-timesheets"><a
                                href="<?php echo admin_url('staff/timesheets'); ?>"><?php echo _l('my_timesheets'); ?></a>
                    </li>
                    <li class="header-edit-profile"><a
                                href="<?php echo admin_url('staff/edit_profile'); ?>"><?php echo _l('nav_edit_profile'); ?></a>
                    </li>
                    <?php if (is_staff_member()) { ?>
                        <li class="header-newsfeed">
                            <a href="#" class="open_newsfeed mobile">
                                <?php echo _l('whats_on_your_mind'); ?>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="header-logout"><a href="#"
                                                 onclick="logout(); return false;"><?php echo _l('nav_logout'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <ul class=" close-noti nav navbar-nav navbar-right">
            <li class="icon">
                <a href="javascript:;" class="click-show-table-delay" data-toggle="tooltip" title="DS Hoãn Giao Hàng"
                   data-placement="bottom">
                    <i class="fa fa-list fa-fw fa-lg" aria-hidden="true"></i>
                    <i class="number_delay"></i>
                </a>

            </li>
            <li class="icon">
                <a href="javascript:;" class="click-show-table-order_half" data-toggle="tooltip"
                   title="Danh Sách Đơn Hàng Giao Hàng Một Phần Chưa Trả Hàng Về" data-placement="bottom">
                    <i class="fa fa-cart-plus" style="font-size: 21px;" aria-hidden="true"></i>
                    <i class="number_status_half"></i>
                </a>
            </li>


            <li class="icon">
                <a href="javascript:;" class="click-show-table-over" data-toggle="tooltip"
                   title="DS Đơn Hàng Sửa Khối Lượng" data-placement="bottom">
                    <i class="fa fa-hourglass-end fa-fw fa-lg" aria-hidden="true"></i>
                    <i class="number_over"></i>
                </a>

            </li>

            <li class="icon">
                <a href="javascript:;" class="click-show-table-check_status" data-toggle="tooltip"
                   title="DS Đơn Hàng Cần Xử Lý" data-placement="bottom">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <i class="number_status"></i>
                </a>
            </li>
            <li class="icon">
                <a href="javascript:;" class="click-show-table-check_status2" data-toggle="tooltip"
                   title="DS Đơn Hàng Cần Xử Lý 2" data-placement="bottom">
                    <i class="fa fa-industry" aria-hidden="true"></i>
                    <i class="number_status2"></i>
                </a>
            </li>
            <li class="icon">
                <a href="javascript:;" class="click-show-table-check_status3" data-toggle="tooltip"
                   title="Danh Sách Thông Báo GHTK" data-placement="bottom">
                    <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                    <i class="number_status3"></i>
                </a>
            </li>
            <li class="icon " >
                <a href="javascript:;" title="Khai Báo Vùng Miền" class="click-show-province" data-toggle="tooltip"
                   data-placement="bottom">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <i class="number_province"></i>
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right ">
            <?php
            if (!is_mobile()) {
                echo $top_search_area;
            } ?>
            <?php hooks()->do_action('after_render_top_search'); ?>
            <li class="icon header-user-profile" data-toggle="tooltip" title="<?php echo get_staff_full_name(); ?>"
                data-placement="bottom">
                <a href="#" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="false">
                    <?php echo staff_profile_image($current_user->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')); ?>
                </a>
                <ul class="dropdown-menu animated fadeIn">

                    <li class="header-my-profile"><a
                                href="<?php echo admin_url('profile'); ?>"><?php echo _l('nav_my_profile'); ?></a></li>
                    <li class="header-my-timesheets"><a
                                href="<?php echo admin_url('staff/timesheets'); ?>"><?php echo _l('my_timesheets'); ?></a>
                    </li>
                    <li class="header-edit-profile"><a
                                href="<?php echo admin_url('staff/edit_profile'); ?>"><?php echo _l('nav_edit_profile'); ?></a>
                    </li>
                    <?php if (get_option('disable_language') == 0) { ?>
                        <li class="dropdown-submenu pull-left header-languages">
                            <a href="#" tabindex="-1"><?php echo _l('language'); ?></a>
                            <ul class="dropdown-menu dropdown-menu">
                                <li class="<?php if ($current_user->default_language == "") {
                                    echo 'active';
                                } ?>">
                                    <a href="<?php echo admin_url('staff/change_language'); ?>"><?php echo _l('system_default_string'); ?></a>
                                </li>
                                <?php foreach ($this->app->get_available_languages() as $user_lang) { ?>
                                <li<?php if ($current_user->default_language == $user_lang) {
                                    echo ' class="active"';
                                } ?>>
                                    <a href="<?php echo admin_url('staff/change_language/' . $user_lang); ?>"><?php echo ucfirst($user_lang); ?></a>
                                    <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <li class="header-logout">
                        <a href="#" onclick="logout(); return false;"><?php echo _l('nav_logout'); ?></a>
                    </li>
                </ul>

            </li>

            <li class="icon all-noti">
                <a href="javascript:;" class="click-show-all-noti" data-toggle="tooltip" title="DS Thông Báo"
                   data-placement="bottom">
                    <i class="fa fa-bell" style="    font-size: 30px;
    color: red;
    margin-top: 12px;" aria-hidden="true"></i>
                </a>
            </li>
        </ul>

    </nav>
</div>
<div id="mobile-search" class="<?php if (!is_mobile()) {
    echo 'hide';
} ?>">
    <ul>
        <?php
        if (is_mobile()) {
            echo $top_search_area;
        } ?>
    </ul>


</div>



<div class="modal fade" id="delay_popup">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Danh Sách Hoãn Giao Hàng</h4>
            </div>
            <div class="modal-body">
                <?php render_datatable(array(
                    _l('id'),
                    _l('Thời Gian Hoãn'), //ok
                    _l('Ngày Tạo'), //ok
                    _l('Trạng Thái'),
                    _l('Mã Đơn Hàng'), //ok
                    _l('Tên Shop'),//ok
                    _l('DVVC'),
                    _l('Huyện Giao'),
                    _l('Tỉnh Giao'),
                    _l('status_delay'),
                    _l('Ghi Chú'),
                    _l('Cài Đặt')
                ), 'header_order_delay'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="delay_popup_note">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" value="" id="id_note">
                    <textarea id="note_delay" style="height:300px;" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="status_popup">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Danh Sách Đơn Hàng Cần Xử Lý</h4>
            </div>
            <div class="modal-body">
                <?php render_datatable(array(
                    _l('id'),
                    _l('Ngày Tạo'), //ok
                    _l('Trạng Thái'),
                    _l('Mã Đơn Hàng'), //ok
                    _l('Tên Shop'),//ok
                    _l('DVVC'),
                    _l('Huyện Giao'),
                    _l('Tỉnh Giao'),
                    _l('Update lần cuối'),
                    _l('status_status'),
                    // _l('Ghi Chú'),
                    _l('Cài Đặt')
                ), 'header_status'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<div class="modal fade" id="status_popup2">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Danh Sách Đơn Hàng Cần Xử Lý 2</h4>
            </div>
            <div class="modal-body">
                <?php render_datatable(array(
                    _l('id'),
                    _l('Ngày Tạo'), //ok
                    _l('Mã Đơn Hàng'), //ok
                    _l('Trạng Thái'),

                    _l('Tên Shop'),//ok
                    _l('DVVC'),
                    _l('Huyện Giao'),
                    _l('Tỉnh Giao'),
                    _l('Deadline'),
                    _l('status_status'),
                    // _l('Ghi Chú'),
                    _l('Cài Đặt')
                ), 'header_status2'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<div class="modal fade" id="webhook_gh">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Danh Sách Thông Báo GHTK</h4>
            </div>
            <div class="modal-body">
                <?php render_datatable(array(
                    _l('id'),
                    _l('Time Thông Báo'), //ok
                    _l('Mã Đơn Hàng'), //ok
                    _l('Tên Shop'),

                    _l('Nội Dung'),//ok
                    _l('Cài Đặt'),
                ), 'header_status3'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<div class="modal fade" id="over_popup">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Danh Sách Đơn Hàng Sửa Khối Lượng</h4>
            </div>
            <div class="modal-body">
                <?php render_datatable(array(
                    _l('id'),
                    _l('Ngày Sửa'),
                    _l('Shop_id'),
                    _l('Tên Shop'),
                    _l('Mã Đơn Hàng'),
                    _l('Cân Nặng Cũ'),
                    _l('Cân Nặng Mới'),
                    _l('Cài Đặt')
                ), 'header_order_over'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="province-flag">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Danh Sách Khai Báo Vùng Miền</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Tỉnh</th>
                        <th>Huyện/Thành Phố</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>




                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="over_popup_note">
    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" value="" id="id_note_over">
                    <textarea id="note_over" style="height:300px;" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>


<style>

    .table-header_order_half tr td, .table-header_order_half tr th {
        white-space: nowrap;
    }

    .table-header_status  .label-border input,.table-header_status2  .label-border input,.table-header_status3  .label-border input, .table-header_order_half .label-border input {
        padding: 0;
        height: initial;
        width: initial;
        margin-bottom: 0;
        display: none;
        cursor: pointer;
    }

    .table-header_status .label-border,.table-header_status2 .label-border,.table-header_status3 .label-border, .table-header_order_half .label-border {
        position: relative;
        cursor: pointer;
    }

    .table-header_status .label-border:before,.table-header_status2 .label-border:before,.table-header_status3 .label-border:before, .table-header_order_half .label-border:before {
        content: '';
        -webkit-appearance: none;
        background-color: transparent;
        border: 2px solid red;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
        padding: 10px;
        display: inline-block;
        position: relative;
        vertical-align: middle;
        cursor: pointer;
        margin-right: 5px;
    }

    .table-header_status  .label-border.active:before,.table-header_status2  .label-border.active:before,.table-header_status3  .label-border.active:before, .table-header_order_half .label-border.active:before {
        border: 2px solid #008ece;
    }

    .table-header_status .label-border.active:after,.table-header_status2 .label-border.active:after,.table-header_status3 .label-border.active:after,
    .table-header_order_half .label-border.active:after {
        content: '';
        display: block;
        position: absolute;
        top: 2px;
        left: 9px;
        width: 6px;
        height: 14px;
        border: solid #0079bf;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }


    .change_status_to_back {
        width: 25px;
        display: flex;
        justify-content: center;
        font-size: 25px;
    }

    .table-header_status thead th:nth-child(9) {
        width: 50px;
    }
    .table-header_status2 thead th:nth-child(9) {
        width: 50px;
    }

    .table-header_status td:nth-child(9) div {
        display: -webkit-flex;
        display: -ms-flex;
        display: flex;
        justify-content: center;
    }.table-header_status2 td:nth-child(9) div {
        display: -webkit-flex;
        display: -ms-flex;
        display: flex;
        justify-content: center;
    }

    #delay_popup_note {
        z-index: 999999991;
    }

    .table-list-header {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 99999999;
        background: #fff;
    }

    #delay_popup_note .modal-dialog, #over_popup_note .modal-dialog {
        width: 500px;
        margin: 0 auto;
    }

    #delay_popup .modal-dialog {
        width: 95%;
        margin: 0 auto;
        margin-top: 20px;
    }

    #over_popup .modal-dialog {
        width: 95%;
        margin: 0 auto;
        margin-top: 20px;
    }

    #status_popup .modal-dialog {
        width: 95%;
        margin: 0 auto;
        margin-top: 20px;
    }
    #status_popup2 .modal-dialog {
        width: 95%;
        margin: 0 auto;
        margin-top: 20px;
    }
    #webhook_gh .modal-dialog {
        width: 95%;
        margin: 0 auto;
        margin-top: 20px;
    }

    .number_delay {
        display: block;
        position: absolute;
        top: 13px;
        left: 2px;
        padding: 3px;
        border-radius: 50%;
        color: #fff;
        background: red;
        margin: 0;
        min-width: 20px;
        height: 20px;
        font-size: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .number_delay.for-mobile {
        top: -13px;
    }

    .number_status {
        display: block;
        position: absolute;
        top: 13px;
        left: 2px;
        padding: 3px;
        border-radius: 50%;
        color: #fff;
        background: red;
        margin: 0;
        min-width: 20px;
        height: 20px;
        font-size: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .number_status2 {
        display: block;
        position: absolute;
        top: 13px;
        left: 2px;
        padding: 3px;
        border-radius: 50%;
        color: #fff;
        background: red;
        margin: 0;
        min-width: 20px;
        height: 20px;
        font-size: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .number_status3 {
        display: block;
        position: absolute;
        top: 13px;
        left: 2px;
        padding: 3px;
        border-radius: 50%;
        color: #fff;
        background: red;
        margin: 0;
        min-width: 20px;
        height: 20px;
        font-size: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .number_province {
        display: block;
        position: absolute;
        top: 13px;
        left: 2px;
        padding: 3px;
        border-radius: 50%;
        color: #fff;
        background: red;
        margin: 0;
        min-width: 20px;
        height: 20px;
        font-size: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .number_over {
        display: block;
        position: absolute;
        top: 13px;
        left: 2px;
        padding: 3px;
        border-radius: 50%;
        color: #fff;
        background: red;
        margin: 0;
        min-width: 20px;
        height: 20px;
        font-size: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .click-show-table-delay {
        position: relative;
    }

    .will-show-hover {
        display: none;
        position: absolute;
        top: 40px;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 2px;
        z-index: 123123123123;
        max-width: 400px;
        word-break: break-all;
        white-space: pre-wrap;
        white-space: -moz-pre-wrap;
        white-space: -pre-wrap;
        white-space: -o-pre-wrap;
        word-wrap: break-word;
        min-width: 200px;
    }

    .table-header_order_delay tbody tr td {
        position: relative;
    }

    .table-header_status tbody tr td {
        position: relative;
    }

    .table-header_status2 tbody tr td {
        position: relative;
    }
    .table-header_status3 tbody tr td {
        position: relative;
    }

    .table-header_order_over tbody tr td {
        position: relative;
    }

    #delay_popup .dataTables_length,
    #delay_popup .dataTables_paginate,
    #delay_popup .dt-page-jump,
    #delay_popup .dataTables_filter,
    #delay_popup .dataTables_info,
    #over_popup .dataTables_length,
    #over_popup .dataTables_paginate,
    #over_popup .dt-page-jump,
    #over_popup .dataTables_filter,
    #over_popup .dataTables_info,
    #status_popup .dataTables_length,
    #status_popup .dataTables_paginate,
    #status_popup .dt-page-jump,
    #status_popup .dataTables_filter,
    #status_popup .dataTables_info {
        display: none !important;
    }
    #status_popup2 .dataTables_length,
    #status_popup2 .dataTables_paginate,
    #status_popup2 .dt-page-jump,
    #status_popup2 .dataTables_filter,
    #status_popup2 .dataTables_info {
        display: none !important;
    }
    #webhook_gh .dataTables_length,
    #webhook_gh .dataTables_paginate,
    #webhook_gh .dt-page-jump,
    #webhook_gh .dataTables_filter,
    #webhook_gh .dataTables_info {
        display: none !important;
    }


    .number_status_half {
        display: block;
        position: absolute;
        top: 13px;
        left: 2px;
        padding: 3px;
        border-radius: 50%;
        color: #fff;
        background: red;
        margin: 0;
        min-width: 20px;
        height: 20px;
        font-size: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #modal_order_half .dataTables_length {
        display: none;
    }

    #modal_order_half .dataTables_info {
        display: none;
    }

    #modal_order_half .dataTables_paginate paging_simple_numbers {
        display: none;
    }
</style>


<div class="modal fade" id="modal_order_half">
    <div class="modal-dialog  modal-xl" style="min-width:95%">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Danh Sách Đơn Hàng Giao Hàng Một Phần Chưa Trả Hàng Về</h4>
            </div>
            <div class="modal-body">
                <?php render_datatable(array(
                    _l('Ngày sửa'), //ok
                    _l('Ngày tạo'), //ok
                    _l('Tên Shop'),
                    _l('Mã đơn hàng'), //ok
                    _l('Trạng thái'), //ok
                    _l('Trạng thái khác'), //ok
                    _l('Tiền Cũ'),//ok
                    _l('Tiền mới'),
                    _l('Cài đặt')
                ), 'header_order_half'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="delay_popup_note_half">
    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" value="" id="id_half">
                    <textarea id="note_half" style="height:300px;" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<script>
    var input = document.getElementById("search_input_header");

    // Execute a function when the user releases a key on the keyboard
    input.addEventListener("keyup", function(event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // console.log($(event).val())
            // console.log($(this).val())

            window.location.href ="/system/admin/order?search_fast="+input.value
        }
    });

</script>